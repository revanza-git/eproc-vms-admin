<?php defined('BASEPATH') || exit('No direct script access allowed');

class Admin_vendor extends CI_Controller {

	public function __construct(){

		parent::__construct();

		if(!$this->session->userdata('admin')){

			redirect(site_url());

		}

		$this->load->model('user/admin_user_model','aum');
		$this->load->model('vendor/Vendor_model','vm');
		
	}

	public function get_field_dt(){

		return array(

			array(

				'label'	=>	'Daftar Tunggu',

				'filter'=>	array(
								array('table'=>'ms_vendor|name' ,'type'=>'text','label'=> 'Nama Penyedia Barang / Jasa'),

								array('table'=>'tb_legal|name' ,'type'=>'text','label'=> 'Badan Usaha'),

								array('table'=>'ms_vendor|need_approve|status_dpt' ,'type'=>'dropdown','label'=> 'Status'),

								// array('table'=>'ms_vendor|edit_stamp' ,'type'=>'text','label'=> 'Aktivitas Terakhir'),

							)

			),

		);

	}

	public function waiting_list($status=""){
		$this->load->library('form');
		$search = $this->input->get('q');

		$sort = $this->utility->generateSort(array('legal_name', 'name', 'last_update'));

		$per_page=10;

		$search = $this->input->get('q');

		$page = '';

		$data['status']	= $status;
		$data['filter_list'] = $this->filter->group_filter_post($this->filter->get_field());
		$data['pagination'] = $this->utility->generate_page('admin/admin_vendor/waiting_list/'.$status,$sort, $per_page, $this->vm->get_waiting_list($status,$search, $sort, '','',FALSE));
		$data['sort'] = $sort;
		$data['list'] = $this->vm->get_waiting_list($status,$search, $sort, $page, $per_page,TRUE,$filter,0);

		$layout['content'] =  $this->load->view('vendor/waiting_list',$data,TRUE);

		$item['header'] = $this->load->view('header',$this->session->userdata('admin'),TRUE);
		$item['content'] = $this->load->view('admin/dashboard',$layout,TRUE);

		$this->load->view('template',$item);
	}

	public function daftar(){
		$search = $this->input->get('q');

		$page = '';

		$per_page=10;

		$sort 	= $this->utility->generateSort(array('ms_vendor.name', 'legal_name', 'sbu_name', 'username', 'password','score'));

		$filter = $this->input->post('filter');

		$data['filter_list'] 	= $this->filter->group_filter_post($this->get_field());

		$data['vendor_list']	= $this->vm->get_vendor_list($search, $sort, $page, $per_page,TRUE,$filter);

		$data['pagination'] = $this->utility->generate_page('admin/admin_vendor/daftar',$sort, $per_page, $this->vm->get_vendor_list($search, $sort, '','',FALSE,$filter));

		$data['sort'] = $sort;

		$layout['content']= $this->load->view('vendor/content',$data,TRUE);

		$layout['script']= $this->load->view('dpt/content_dpt_js',$data,TRUE);

		$item['header'] = $this->load->view('admin/header',$this->session->userdata('admin'),TRUE);

		$item['content'] = $this->load->view('admin/dashboard',$layout,TRUE);

		$this->load->view('template',$item);
	}

	public function get_field(){

		return array(

			array(

				'label'	=>	'Penyedia Barang/Jasa',

				'filter'=>	array(

								array('table'=>'ms_vendor|name' ,'type'=>'text','label'=> 'Nama Penyedia Barang/Jasa'),

								array('table'=>'tb_legal|name' ,'type'=>'text','label'=> 'Badan Usaha'),

								array('table'=>'ms_login|username' ,'type'=>'text','label'=> 'Username'),

							)

			),

		);

	}

	public function hapus($id){

		if($this->vm->inactive_vendor($id)){

			$this->session->set_flashdata('msgSuccess','<p class="msgSuccess">Sukses menghapus data!</p>');

			redirect(site_url('admin/admin_vendor/daftar'));

		}else{

			$this->session->set_flashdata('msgSuccess','<p class="msgError">Gagal menghapus data!</p>');

			redirect(site_url('admin/admin_vendor/daftar'));

		}

	}

	public function export_excel($data, $title="Data Penyedia Barang/Jasa"){
		$data = $this->vm->get_all_vendor_list();
		$csms_limit = $this->vm->get_csms_limit();
		$table = "<table border=1>";

			$table .= "<tr>";
			$table .= "<td style='background: #f6e58d;'>No</td>";
			$table .= "<td style='background: #f6e58d;'>Nama Badan Usaha</td>";
			$table .= "<td style='background: #f6e58d;'>Nama Penyedia Barang/Jasa</td>";
			$table .= "<td style='background: #f6e58d;'>Kategori CSMS</td>";
			$table .= "<td style='background: #f6e58d;'>Nomor Sertifikat</td>";
			$table .= "<td style='background: #f6e58d;'>NPWP</td>";
			$table .= "<td style='background: #f6e58d;'>Alamat</td>";
			$table .= "<td style='background: #f6e58d;'>Telepon</td>";
			$table .= "</tr>";

		foreach ($data as $key => $value) {
			$no = $key + 1;
			$table .= "<tr>";
			$table .= "<td>".$no."</td>";
			$table .= "<td>".$value['legal']."</td>";
			$table .= "<td>".$value['name']."</td>";
            
			    $table_ = "<td>-</td>";
			foreach ($csms_limit as $csm_limit) {
                if ($value['score'] > $csm_limit['end_score'] && $value['score'] < $csm_limit['start_score']) {
			        $table_ = "<td>".$csm_limit['value']."</td>";
                }
			}
   
			$table .= $table_;
			$table .= "<td>".$value['certificate_no']."</td>";
			$table .= "<td>".$value['npwp_code']."</td>";
			$table .= "<td>".$value['vendor_address']."</td>";
			$table .= "<td>".$value['vendor_phone']."</td>";
			$table .= "</tr>";
		}
  
		$table .= "</table>";
		header('Content-type: application/ms-excel');

    	header('Content-Disposition: attachment; filename='.$title.'.xls');

		echo $table;
	}

	public function export_excel_waiting_list($data, $title="Data Penyedia Barang/Jasa"){
		$data = $this->vm->get_waiting_list($search, $sort, $page, $per_page,TRUE);
		$this->vm->get_csms_limit();
		$table = "<table border=1>";

			$table .= "<tr>";
			$table .= "<td style='background: #f6e58d;'>No</td>";
			$table .= "<td style='background: #f6e58d;'>Nama Badan Usaha</td>";
			$table .= "<td style='background: #f6e58d;'>Nama Penyedia Barang/Jasa</td>";
			$table .= "<td style='background: #f6e58d;'>Nomor Sertifikat</td>";
			$table .= "<td style='background: #f6e58d;'>NPWP</td>";
			$table .= "<td style='background: #f6e58d;'>Alamat</td>";
			$table .= "<td style='background: #f6e58d;'>Telepon</td>";
			$table .= "<td style='background: #f6e58d;'>Email</td>";
			$table .= "</tr>";

		foreach ($data as $key => $value) {
			$no = $key + 1;
			$table .= "<tr>";
			$table .= "<td>".$no."</td>";
			$table .= "<td>".$value['legal_name']."</td>";
			$table .= "<td>".$value['name']."</td>";
            
			$table .= "<td>".$value['certificate_no']."</td>";
			$table .= "<td>".$value['npwp_code']."</td>";
			$table .= "<td>".$value['vendor_address']."</td>";
			$table .= "<td>".$value['vendor_phone']."</td>";
			$table .= "<td>".$value['email']."</td>";
			$table .= "</tr>";
		}
  
		$table .= "</table>";
		header('Content-type: application/ms-excel');

    	header('Content-Disposition: attachment; filename='.$title.'.xls');

		echo $table;
	}
}