<?php defined('BASEPATH') || exit('No direct script access allowed');

class admin_assessment_model extends CI_Model{

	public function __construct(){
		parent::__construct();
		$this->field_master = array(
								'id_group',
								'value',
								'id_role',
								'point',
								'entry_stamp',
							);
		$this->field_group = array(
								'name',
								'entry_stamp',
							);
		}

	public function get_assessment_quest_list($search='', $sort='', $page='', $per_page='',$is_page=FALSE) 
    {
		$query = $this->db->select('')->where('del',0)->get('ms_ass_group')->result_array();
		foreach($query as $key => $value){
			$ass = $this->db->select('*,ms_ass.id id,tb_role.name role')->where('del',0)
			->join('tb_role','tb_role.id=ms_ass.id_role')
			->where('id_group',$value['id'])->get('ms_ass')->result_array();
			$query[$key]['data_quest'] = $ass;
		}
  
		return $query;
		
    }
 
    public function get_data_assessment($id){
    	return $this->db->select('*')->where('del',0)->where('id',$id)->get('ms_ass')->row_array();
    }

    public function get_data_group(){
    	$result = $this->db->select('*')->get('ms_ass_group')->result_array();
    	$arr = array();
    	foreach($result as $val){
    		$arr[$val['id']] = $val['name'];
    	}
     
    	return $arr;
    }

    public function edit_data_assessment($data,$id){
    	$this->db->where('id',$id);
		
		return $this->db->update('ms_ass',$data);
    }
    
    public function save_assessment($data){
		$_param = array();
		
		$sql = "INSERT INTO ms_ass (
							`id_group`,
							`value`,
							`id_role`,
							`point`,
							`entry_stamp`
							) 
				VALUES (?,?,?,?,?) ";
		
		
		foreach($this->field_master as $_param) $param[$_param] = $data[$_param];
		
		return $this->db->query($sql, $param);
		
	}

    public function get_data_group_list($id){
    	return $this->db
    				   ->select('*')
    				   ->where('id', $id)
    				   ->get('ms_ass_group')
    				   ->row_array();
    }

    public function edit_data_group($data,$id){
    	$this->db->where('id',$id);
		
		return $this->db->update('ms_ass_group',$data);
    }

	public function save_group($data){
		$_param = array();
		
		$sql = "INSERT INTO ms_ass_group (
							`name`,
							`entry_stamp`
							) 
				VALUES (?,?) ";
		
		
		foreach($this->field_group as $_param) $param[$_param] = $data[$_param];
		
		return $this->db->query($sql, $param);
		
	}

	public function hapus_data_assessment($id){
		$this->db->where('id',$id);
		
		return $this->db->update('ms_ass',array('del'=>1));
	}

	public function hapus_data_group($id){
		$this->db->where('id',$id);
		
		return $this->db->update('ms_ass_group',array('del'=>1));
	}

}