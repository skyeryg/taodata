<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Goods_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function get_data($item)
	{
		$auction = $item["auction"];
		
		//$tmp = $auction["aucNumId"] + 1 - 1;
		unset($auction["XID"], $auction["thumbnail"]);//,$auction["aucNumId"]);
		//$auction["aucNumId"] = $tmp;

		return $auction;
	}

	function add_data($item)
	{
		$goodsData = $this->get_data($item);

		$this->db->replace('goods', $goodsData);
		
	}
}