<?php defined('BASEPATH') || exit('No direct script access allowed');

 

class Admin_user_model extends CI_Model{



	public function __construct(){

		parent::__construct();

		$this->field_master = array(

								'id_role',

								'password',

								'id_sbu',

								'name',

								'id_division',

								'entry_stamp',

								'email'

							);

		$this->field_login 	= array(

								'id_user',

								'type',

								'username',

								'password',

								'entry_stamp'

							);

		$this->kurs 		= array(

								'name',

								'symbol',

								'entry_stamp'

							);

	}



	public function get_admin_user_list($search='', $sort='', $page='', $per_page='',$is_page=FALSE,$filter=array()) 

    {

		$this->db->select('tb_role.id id_role, ms_admin.name name, tb_role.name role_name, ms_admin.email email,password,ms_admin.id id');

		$this->db->where('del',0);

		$this->db->join('tb_role','tb_role.id=ms_admin.id_role','LEFT');

		

		$a = $this->filter->generate_query($this->db->group_by('id'),$filter);



		if($this->input->get('sort')&&$this->input->get('by')){

			$this->db->order_by($this->input->get('by'), $this->input->get('sort')); 

		}

		if($is_page){

			$cur_page = ($this->input->get('per_page')) ? $this->input->get('per_page') : 1;

			$this->db->limit($per_page, $per_page*($cur_page - 1));

		}

		

		$query = $a->get('ms_admin');
		// echo $this->db->last_query();
		return $query->result_array();

		

    }

    public function get_role(){

    	$get = $this->db->select('id,name')->get('tb_role');

		$raw = $get->result_array();

		$res = array();

		$res[''] = 'Pilih salah satu';

		foreach($raw as $val){

			$res[$val['id']] = $val['name'];

		}

		return $res;

    }

    public function save_data($data){

		$_param = array();

		$sql = "INSERT INTO ms_admin (

							id_role,

							password,

							id_sbu,

							name,

							id_division,

							entry_stamp,

							email) 

				VALUES (?,?,?,?,?,?,?) ";

		

		

		foreach($this->field_master as $_param) $param[$_param] = $data[$_param];

		

		$this->db->query($sql, $param);

		$id = $this->db->insert_id();





		$data_login['type'] 		= 'admin';

		$data_login['id_user'] 		= $id;

		$data_login['username'] 	= $data['name'];

		$data_login['password'] 	= $data['password'];

		$data_login['entry_stamp'] 	= $data['entry_stamp'];

		$param = array();

		$sql = "INSERT INTO ms_login (

							id_user,

							type,

							username,

							password,

							entry_stamp) 

				VALUES (?,?,?,?,?) ";

		

		

		foreach($this->field_login as $_param) $param[$_param] = $data_login[$_param];

		

		$this->db->query($sql, $param);

		return $this->db->insert_id();

	}





	public function get_data($id){



		$sql = "SELECT * FROM ms_admin WHERE id = ".$id;

		$query = $this->db->query($sql);

		return $query->row_array();

	}



	public function edit_data($data,$id){

				

		

		return $this->db->where('id',$id)->update('ms_admin',$data);

	}

	public function edit_data2($data,$id){

		return $this->db->where('id_user',$id)->where('type','admin')->update('ms_login',$data);

	}

	public function delete($id){

		$this->db->where('id',$id);

		

		return $this->db->update('ms_admin',array('del'=>1));

	}









	##################################################

	##################################################

	######					KURS				######

	##################################################

	##################################################

	public function get_kurs_list($search='', $sort='', $page='', $per_page='',$is_page=FALSE,$filter=array()){

		$this->db->select('*');

		$this->db->where('tb_kurs.del',0);

		



		$a = $this->filter->generate_query($this->db->group_by('id'),$filter);



		if($this->input->get('sort')&&$this->input->get('by')){

			$this->db->order_by($this->input->get('by'), $this->input->get('sort')); 

		}

		if($is_page){

			$cur_page = ($this->input->get('per_page')) ? $this->input->get('per_page') : 1;

			$this->db->limit($per_page, $per_page*($cur_page - 1));

		}

		

		$query = $a->get('tb_kurs');

		return $query->result_array();

    }

    public function edit_kurs($data,$id){

				

		$this->db->where('id',$id);

		

		return $this->db->update('tb_kurs',$data);

	}

    public function delete_kurs($id){

		$this->db->where('id',$id);

		return $this->db->update('tb_kurs',array('del'=>1));

	}

	public function get_kurs($id){

		$sql = "SELECT * FROM tb_kurs WHERE id = ".$id;

		$query = $this->db->query($sql);

		return $query->row_array();

	}

	public function save_kurs($data){



		$sql = "INSERT INTO tb_kurs (

							name,

							symbol,

							entry_stamp

							) 

				VALUES (?,?,?) ";

		

		

		foreach($this->kurs as $kur) $param[$kur] = $data[$kur];

		// echo print_r($this->input->post());

		$this->db->query($sql, $param);

		

		return $this->db->insert_id();

	

	}

}