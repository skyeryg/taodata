<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH.'libraries/simple_html_dom.php';

class Page_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
		$this->load->helper('MY_helper');
	}

	public function add_goods_data($shop_nick)
	{
		$need = $this->nick2id($shop_nick);
		$num = $need["pageNum"];
		$dealerId = $need["dealerId"];
		$j = 0;

		for ($page=1; $page < $num + 1 ; $page++) { 
		# code...
			$pageData = $this->get_goods_data($shop_nick, $page);

			foreach ($pageData as $item) {
				# code...
				$item["dealerId"] = $dealerId;
				$this->db->replace('goods', $item);
				$j++;
			}
		}

		return $j;
	}

	public function add_rate_data($dealerId)
	{
		$j = 0;
		for ($page=1; $page <= 100; $page++) { 
			# code...			
			$pageData = $this->get_rate_data($dealerId, $page);

			foreach ($pageData as $item) {
				# code...
				// $this->db->replace('goods', $item["auction"]);
				$this->db->replace('user', $item["user"]);
				$this->db->replace('rate', $item["rate"]);
				$j++;

			}
		}
		return $j;
	}

	public function add_deal_data($item_id)
	{
		$url = "http://detail.tmall.com/item.htm?id=".$item_id;
		$res = curlRequest($url);
		$dealerId = get_mid_str($res,"userid=", ";\">");
		$tmpurl = del_side_str($res, "detail:params=\"", ",showBuyerList");
		$tmpurl = str_replace("false", "true", $tmpurl);
		// echo $tmpurl;
		$page = 1;

		$j = 0;

		while (($pageData = $this->get_deal_data($tmpurl, $page)) != 0) { 
			# code...			
			foreach ($pageData as $item) {
				# code...
				$item["dealerId"] = $dealerId;
				$item["goodsId"] = $item_id;
				$this->db->replace('deal', $item);
				$j++;
			}
			$page++;
		}

		return $j;
	}



	public function nick2id($shop_nick)
	{
		$url = "http://".$shop_nick.".tmall.com/search.htm";

		$dom = file_get_html($url);
		$dealerId = get_mid_str($dom->find('head script', 0)->innertext,"userId: '", "',");
		$ccc  = substr($dom->find('span[class=page-info]',0)->innertext,2);
		$dom->clear();

		$result["dealerId"] = $dealerId;
		$result["pageNum"] = $ccc;

		return $result;
	}

	public function get_goods_data($shop_nick, $page)
	{
		// $url = "http://".$shop_nick.".tmall.com/search.htm";
		// $res = curlRequest($url);
		// $dealerId = get_mid_str($res,"userid=", ";\">");
		// $dom = str_get_html($res);

		// $ccc  = get_mid_str($dom->find('div[class=search-result]',0)->innertext,"<span>","</span>");
		// $dom->clear();
		// $pagesccc = trim($ccc)/20;

		$j = 0;
		// for ($i=1; $i < $shopccc+1 ; $i++) { 
		$dom = file_get_html("http://".$shop_nick.".tmall.com/search.htm?pageNum=".$page);

    	foreach($dom->find('div[class=shop-hesper-bd grid big] ul[class=shop-list] li div[class=item]') as $e){
    		$tmp["goodsId"] = get_mid_str($e->children(1)->children(0)->href, "id=", "&");
			$tmp["price"] = get_mid_str($e->innertext,"<strong>","</strong>");
			$tmp["amount"] = get_mid_str($e->innertext,"<em>","</em>");
			// $tmp["dealerId"] = $dealerId;
			$str = trim($e->children(1)->children(0)->innertext);
			$tmp["title"] = iconv("GB18030", "UTF-8//IGNORE", $str);
			$result[$j] = $tmp;
			$j++;
    	}
		// }
    	$dom->clear();

		return $result;
	}

	
	function get_rate_data($user_id, $page)
	{
		$url = "http://rate.taobao.com/member_rate.htm?identity=2&user_id=".$user_id."&page=".$page;
		$res = curlRequest($url);
		//echo $result;
		$datajson = del_side_str($res, "(", ")");
		//echo $datajson;
		//$str = mb_convert_encoding($datajson, "UTF-8", "GB18030");
		$str = iconv("GB18030", "UTF-8//IGNORE", $datajson);
		$pageData = json_decode($str, true);
		$dataList = $pageData["rateListDetail"];

		// return $dataList;
		$i = 0;
		foreach ($dataList as $item) {
			# code...
			$auction = $item["auction"];
			// unset($auction["XID"], $auction["thumbnail"]);
			// $goods["goodsId"] = $auction["aucNumId"];
			// // $goods["price"] = $auction["auctionPrice"];
			// $goods["dealerId"] = $user_id;
			// $goods["title"] = $auction["title"];

			$user = $item["user"];
			unset($user["anony"], $user["avatar"], $user["displayRatePic"], $user["vip"]);

			$rate["rateId"] = $item["rateId"];
			$rate["goodsId"] = $item["auction"]["aucNumId"];
			$rate["userId"] = $item["user"]["userId"];
			$rate["date"] = $item["date"];
			$rate["rate"] = $item["rate"];
			$rate["content"] = $item["content"];
			$rate["price"] = $auction["auctionPrice"];

			//$e["goods"] = $goods;
			$e["user"] = $user;
			$e["rate"] = $rate;
			$result[$i] = $e;
			//print_r($r);
			$i++;
		}
		return $result;

	}

	function get_deal_data($tmpurl, $page)
	{
		//item_id = 4902513700
		
		$dataurl = str_replace("bid_page=1&", "", $tmpurl)."&bid_page=".$page;
		$dataxml = curlRequest($dataurl);
		//echo $dataxml;
		$strr = iconv("GB18030", "UTF-8//IGNORE", $dataxml);
		$str = get_mid_str($strr, "<table>", "</table>");
		$dealPage = "<html><head><META http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\"></head><body>".$str."</table></body></html>";
		// return $dataDeal;
		// return $dealPage;
		// echo $dealPage;
		$i = 0;
		$dom = str_get_html($dealPage);

		if ($dom->find('tr td[class=cell-align-l]',0)) {
			# code...
			foreach ($dom->find('tr') as $val) {
			# code...
				if ($i!=0) {
					# code...
					# $deal[$i-1]["goodsId"] = $item_id;
					# $deal[$i-1]["dealerId"] = $dealerId;
					$dealData["userNick"] = str_replace(" ", "", trim($val->children(0)->plaintext));
					$dealData["price"] = get_mid_str($val->innertext, "<em>", "</em>");
					$dealData["dealTime"] = $val->children(4)->plaintext;
					$dealData["count"] = $val->children(3)->plaintext;
					$deal[$i-1] = $dealData;
				}			
				$i++;
			}
			$dom->clear();
			return $deal;
		} else {
			# code...
			$dom->clear();
			return 0;
		}
	}

}