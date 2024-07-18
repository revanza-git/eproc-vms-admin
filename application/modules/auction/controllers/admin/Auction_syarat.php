<?php
class Auction_syarat extends CI_Controller{

	public function __construct(){
		parent::__construct();
		
		$this->load->model('auction_package/syarat_model');
	}
	
	public function index($id_lelang = ''){
		$master = $this->syarat_model->get_auction($id_lelang);
		$fill = $this->syarat_model->select_data($id_lelang);
		$master = $this->syarat_model->get_master($id_lelang);
				
		if ($master['type_lelang'] == "reverse") {
      $limit = "minimum";
      $indicator = "rendah";
      $reverse = "tinggi";
  } elseif ($master['type_lelang'] == "forward") {
      $limit = "maximum";
      $indicator = "tinggi";
      $reverse = "rendah";
  }
		
		$data['action'] = "edit"; 
		$value = $fill['content'];
		
		$data['content'] = $this->form->text_area('content', $value, array(40, 15));
		$data['id_lelang'] = $this->form->hidden('id_lelang', $id_lelang);
		
		$this->load->view('content/auction_package/auction_syarat', $data);
	}
	
	public function save(){
		$param = array(
			$_POST['content'],
			$_POST['id_lelang'],
			date("Y-m-d H:i:s")	
		);
		
		$this->syarat_model->save(false, $param);
		
		$json = array(
			'status' => 'success',
			'message' => 'Data persyaratan auction telah di simpan !'
		);
		die(json_encode($json));
	}
	
	public function edit(){
		$param = array(
			$_POST['content'],
			date("Y-m-d H:i:s"),
			$_POST['id_lelang']
		);
		
		$this->syarat_model->save(true, $param);
		
		$json = array(
			'status' => 'success',
			'message' => 'Data persyaratan auction telah di edit !'
		);
		die(json_encode($json));
	}
}