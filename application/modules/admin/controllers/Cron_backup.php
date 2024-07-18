<?php defined('BASEPATH') || exit('No direct script access allowed');
class Cron extends CI_Controller {
	public function __construct(){
		parent::__construct();
		$this->load->library('email');
	}
 
	public function index(){
		
	}
 
	public function drop_dpt(){
		error_reporting(E_ALL);
		/*
			Query dari tr_email_blast untuk mengambil data email yang akan dikirim
		*/
		$query = "SELECT * FROM tr_email_blast WHERE DATE(`date`) <= DATE('".date('Y-m-d')."') AND id_doc != 'ms_pengurus' GROUP BY id_doc";
		$query = $this->db->query($query)->result_array();
		foreach($query as $value){
			$sql = "SELECT a.*, b.id_vendor, c.name nama_vendor FROM tr_email_blast a JOIN ".$value['doc_type']." b ON a.id_doc=b.id JOIN ms_vendor c ON c.id=b.id_vendor WHERE a.id = ".$value['id']." ";
$query_[]= $this->db->query($sql)->row_array();
		}
  
		print_r($query_);die;
	}
}
