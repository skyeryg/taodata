<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class GetDeal extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -  
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
		$this->load->database();
		$this->load->model('Page_model');
		
		//$baseUrl = "http://rate.taobao.com/member_rate.htm?identity=2&user_id=".$user_id."&page=";
		$item_id = 4902513700;
		//$user_url = "&user_id"

		for ($page=1; $page <= 100; $page++) { 
			# code...			
			$pageData = $this->Page_model->get_deal_data($user_id, $page);

			foreach ($pageData as $item) {
				# code...
				//$user = $this->User_model->get_data($item);
				$this->User_model->add_data($item);
				//$goods = $this->Goods_model->get_data($pageData["0"]);
				$this->Goods_model->add_data($item);
				//$user = $this->User_model->get_data($item);
				$this->Rate_model->add_data($item);
				//echo $user["userId"];
				//echo $user["nick"];

			}
		}
	}

	public function getdeal()
	{
		$this->load->database();
		$this->load->model('Page_model');
		
		$item_id = 4902513700;
		$page = 1;

		for ($page=1; $page <= 100; $page++) { 
			# code...			
			$pageData = $this->Page_model->get_deal_data($item_id, $page);

			foreach ($pageData as $item) {
				# code...
				$this->db->replace('deal', $item);

			}
		}
	}


	public function test()
	{
		$this->load->model('Page_model');
		$item_id = 4902513700;
		$page = 1;

		$pageData = $this->Page_model->get_deal_data($item_id, $page);
		// $dom = str_get_html($pageData);
		
		print_r($pageData[0]);
	}
}