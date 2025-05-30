<?php defined('BASEPATH') || exit('No direct script access allowed');

class Admin_sub_bidang extends CI_Controller {
	public function __construct(){
		parent::__construct();
		if(!$this->session->userdata('admin')){
			redirect(site_url());
		}
  
		$this->load->model('izin/Izin_model','im');	
	}
 
	public function get_field(){
		return array(
			array(
				'label'	=>	'Sub Bidang',
				'filter'=>	array(
								array('table'=>'tb_bidang|name' ,'type'=>'text','label'=> 'Nama Bidang'),
								array('table'=>'tb_sub_bidang|name' ,'type'=>'text','label'=> 'Nama Sub Bidang'),
							)
			),
			
		);
	}
 
	public function index(){	
		$this->load->library('form');
		$this->load->library('datatables');

		$data['filter_list'] = $this->filter->group_filter_post($this->get_field());

		$search 	= $this->input->get('q');
		$page 		= '';
		$per_page	= 10;
		$sort 		= $this->utility->generateSort(array('tb_bidang.id','name'));

		$data['subbidang']	= $this->im->get_sub_bidang_list($search, $sort, $page, $per_page,TRUE);
		$data['pagination'] = $this->utility->generate_page('admin/admin_sub_bidang/',$sort, $per_page, $this->im->get_sub_bidang_list($search, $sort, '','',FALSE));
		$data['sort'] 		= $sort;
		$layout['content']	= $this->load->view('sub_bidang/content',$data,TRUE);

		$item['header'] 	= $this->load->view('admin/header',$this->session->userdata('admin'),TRUE);
		$item['content'] 	= $this->load->view('admin/dashboard',$layout,TRUE);
		$this->load->view('template',$item);
		// echo print_r($this->db->last_query());
	}
 
	//#####################################################
	//################  	SUB BIDANG		 ##############
	//#####################################################
	public function tambah_sub_bidang(){
		$this->load->model('vendor/Vendor_model','vm');
		$_POST	= $this->securities->clean_input($_POST,'save');
		$admin 	= $this->session->userdata('admin');
		$vld 	= array(
			array(
				'field'=>'name',
				'label'=>'Nama Sub Bidang',
				'rules'=>'required'
				),
			array(
				'field'=>'id_bidang',
				'label'=>'Bidang',
				'rules'=>'required'
				)
			);
		
		$this->form_validation->set_rules($vld);
		if($this->form_validation->run()==TRUE){
			unset($_POST['Simpan']);
			$_POST['entry_stamp'] = date("Y-m-d H:i:s");

			$this->im->save_data_sub_bidang($this->input->post());

			$this->session->set_flashdata('msgSuccess','<p class="msgSuccess">Sukses menambah data!</p>');
			// echo print_r($this->input->post());
			redirect(site_url('admin/admin_sub_bidang/'));
		}

		$data['sbu'] 		= $this->vm->get_sbu();
		$data['role'] 		= $this->im->get_bidang_dropdown();
		$layout['content']	= $this->load->view('sub_bidang/tambah',$data,TRUE);

		$item['header'] 	= $this->load->view('admin/header',$admin,TRUE);
		$item['content'] 	= $this->load->view('admin/dashboard',$layout,TRUE);
		$this->load->view('template',$item);
	}



	public function edit_sub_bidang($id){
		$data 			= $this->im->get_data_sub_bidang($id);
		$data['role'] 	= $this->im->get_bidang_dropdown();
		$_POST 			= $this->securities->clean_input($_POST,'save');
		$admin 			= $this->session->userdata('admin');
		$vld 			= array(
			array(
				'field'=>'name',
				'label'=>'Nama Sub Bidang',
				'rules'=>'required'
				),
			array(
				'field'=>'id_bidang',
				'label'=>'Pilih Bidang',
				'rules'=>'required'
				)
			);

		$this->form_validation->set_rules($vld);
		if($this->form_validation->run()==TRUE){
			$_POST['edit_stamp'] = date("Y-m-d H:i:s");
			unset($_POST['Update']);

			$res = $this->im->edit_data_sub_bidang($this->input->post(),$id);

			if($res){
				$this->session->set_flashdata('msgSuccess','<p class="msgSuccess">Sukses mengubah data!</p>');
				redirect(site_url('admin/admin_sub_bidang'));
			}
		}

		$layout['content']= $this->load->view('sub_bidang/edit',$data,TRUE);

		$admin = $this->session->userdata('admin');
		$item['header'] = $this->load->view('admin/header',$admin,TRUE);
		$item['content'] = $this->load->view('admin/dashboard',$layout,TRUE);
		$this->load->view('template',$item);
	}

	public function hapus_sub_bidang($id){
		if($this->im->delete_sub_bidang($id)){
			$this->session->set_flashdata('msgSuccess','<p class="msgSuccess">Sukses menghapus data!</p>');
			redirect(site_url('admin/admin_sub_bidang'));
		}else{
			$this->session->set_flashdata('msgSuccess','<p class="msgError">Gagal menghapus data!</p>');
			redirect(site_url('admin/admin_sub_bidang'));
		}
	}
}