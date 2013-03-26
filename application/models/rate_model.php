<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rate_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function get_data($item)
	{
		$rate["rateId"] = $item["rateId"];
		$rate["aucNumId"] = $item["auction"]["aucNumId"];
		$rate["userId"] = $item["user"]["userId"];
		$rate["date"] = $item["date"];
		$rate["rate"] = $item["rate"];
		$rate["content"] = $item["content"];

		return $rate;
	}

	function add_data($item)
	{
		$rateData = $this->get_data($item);
		$this->db->replace('rate', $rateData);
	}
}