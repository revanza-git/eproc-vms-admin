<?php defined('BASEPATH') || exit('No direct script access allowed');

class Bast_model extends CI_Model{

	public function __construct(){
		parent::__construct();



		$this->pernyataan = array(
							'value',
							'entry_stamp');
	}

	public function get_bast_format(){
		$query = $this->db 	->select('*')
							->where('del',0)
							->get('tb_bast_print');
		
		return $query->row_array();
	}

	public function edit_bast($data){
		$this->db 	->where('del',0)->update('tb_bast_print',array('del'=>1,'edit_stamp'=>date('Y-m-d H:i:s')));
		return $this->db->insert('tb_bast_print',$data);
	}
}