<?php defined('BASEPATH') || exit('No direct script access allowed');

class pernyataan_model extends CI_Model{

	public function __construct(){
		parent::__construct();



		$this->pernyataan = array(
							'value',
							'entry_stamp');
	}

	public function get_pernyataan_list(){
		$query = $this->db 	->select('*')
							->get('tb_pernyataan');

		return $query->result_array();
	}

	public function edit_pernyataan($id, $data){
		$this->db->where('id',$id);
		// echo print_r($data);
		return $this->db->update('tb_pernyataan',$data);
	}

	public function save_pernyataan($data){
   		
   		$_param = array();
		$sql = "INSERT INTO tb_pernyataan (
								`value`,
								`entry_stamp`
								) 
				VALUES (?,?) ";

		foreach($this->pernyataan as $_param) $param[$_param] = $data[$_param];

		$this->db->query($sql, $param);
		
		return $this->db->insert_id();
   	}
}