<?php
class Beranda extends CI_Controller{
	
	public function __construct(){
		parent::__construct();
		$this->load->model('beranda_model');
		$this->load->model('side_menu_model');
	}
	
	public function index(){
		$this->load->view('content/beranda/admin/master', $data);
	}
	
	public function task(){
		$data['multi_tab'] = array(
			array(
				'title' => 'Auction Belum dimulai',
				'url' => 'auction_package/index/unstart'
			),
			array(
				'title' => 'Auction telah selesai',
				'url' => 'auction_package/index/finish'
			),
			array(
				'title' => 'Semua Auction',
				'url' => 'auction_package/index/all'
			)
		);
		
		$this->load->view('template/multi-tab', $data);
	}
	
	public function menu(){
		
		$this->load->view('side-menu/admin');		
	}
	
	public function menu_public(){
		$this->load->view('side-menu/public');		
	}
}