<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}

	function get_data($item)
	{
		$user = $item["user"];
		unset($user["anony"], $user["avatar"], $user["displayRatePic"], $user["vip"]);

		return $user;
	}

	function add_data($item)
	{	
		$userData = $this->get_data($item);

		$this->db->replace('user', $userData);
	}
}