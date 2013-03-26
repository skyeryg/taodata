<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Taobao extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in 
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	function __construct()
	{
		parent::__construct();

		$this->load->database();
		$this->load->model('Page_model');
		$this->load->helper('form');
		$this->load->helper('url');
	}

	public function index()
	{
		$this->load->view('index_view');
	}

	

	public function test()
	{

		$shop_nick = $this->input->post('shop');
		// $pdata["dealerId"];$pdata["pageNum"]
		$pdata = $this->Page_model->nick2id($shop_nick);


		if ($this->input->post('rate') === "rate") {
			# code...
			echo "rate";
			// echo $pdata["dealerId"];
			//echo $pdata["pageNum"];
			echo $this->Page_model->add_rate_data($pdata["dealerId"]);
		}

		if ($this->input->post('goods') === "goods") {
			# code...
			echo "goods";
			echo $this->Page_model->add_goods_data($shop_nick);
		}

		if ($this->input->post('deal') === "deal") {
			# code...
			echo "deal";
			//$this->Page_model->add_deal_data($pdata["dealerId"]);
			print_r($this->Page_model->get_deal_data($id, $page));
		}
		// print_r($this->input->post());
	}
}
