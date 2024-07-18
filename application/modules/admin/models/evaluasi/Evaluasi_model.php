<?php defined('BASEPATH') || exit('No direct script access allowed');

class evaluasi_model extends CI_Model{

	public function __construct(){
		parent::__construct();
		$this->evaluasi 		= array(
								'id_ms_quest',
								'name',
								'point_a',
								'point_b',
								'point_c',
								'point_d',
							);
		}

	public function get_evaluasi_list($search='', $sort='', $page='', $per_page='',$is_page=FALSE,$filter=array()){
		$this->db->select('*');
		$this->db->where('del',0);
		
		$a = $this->filter->generate_query($this->db->group_by('id'),$filter);

		if($this->input->get('sort')&&$this->input->get('by')){
			$this->db->order_by($this->input->get('by'), $this->input->get('sort')); 
		}
  
		if($is_page){
			$cur_page = ($this->input->get('per_page')) ? $this->input->get('per_page') : 1;
			$this->db->limit($per_page, $per_page*($cur_page - 1));
		}
		
		$query = $a->get('tb_evaluasi');
		return $query->result_array();	
    }

    public function save_evaluasi($data){
    	$_param = array();
		$sql = "INSERT INTO tb_evaluasi (
							id_ms_quest,
							name,
							point_a,
							point_b,
							point_c,
							point_d
							) 
				VALUES (?,?,?,?,?,?) ";
		
		
		foreach($this->evaluasi as $_param) $param[$_param] = $data[$_param];
  
		// echo print_r($this->input->post());
		$this->db->query($sql, $param);
		
		return $this->db->insert_id();
    }

    public function get_data($id){
    	$query = $this->db->select('*')->where('id', $id)->where('del',0)->get('tb_evaluasi');
		// $sql = "SELECT * FROM tb_evaluasi WHERE id = ".$id;
		// $query = $this->db->query($sql);
		return $query->row_array();
	}

	public function edit_data($data,$id){
				
		$this->db->where('id',$id);
		
		return $this->db->update('tb_evaluasi',$data);
	}




	public function get_header(){
    	$get = $this->db->where('del',0)->select('id,question')->get('tb_ms_quest_k3');
		$raw = $get->result_array();
		$res = array();
		$res[''] = 'Pilih salah satu';
		foreach($raw as $val){
			$res[$val['id']] = $val['question'];
		}
  
		return $res;
    }
}