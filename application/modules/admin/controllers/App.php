<?php
/**
 * 
 */
class App extends CI_Controller
{
	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('main_model','mm');
		$this->load->helper('string');
	}

	public function index()
	{		
		$admin = $this->session->userdata('admin');

		$getUser = $this->mm->to_app($admin['id_user']);
		
		$this->session->sess_destroy();

		$data = array(
			'name' 			=> $getUser['name'],
			'id_user' 		=> $getUser['id'],
			'id_role' 		=> $getUser['id_role_app2'],
			'id_division'	=> $getUser['id_division'],
			'app_type'		=> 1,
			'email'	 		=> $getUser['email'],
			'photo_profile' => $getUser['photo_profile'],
		);

		$key = random_string('unique').random_string('unique').random_string('unique').random_string('unique');
		$this->db->insert('ms_key_value', array(
			'key' => $key,
			'value'=> json_encode($data),
		));

		header("Location: http://10.10.10.3/eproc_nusantararegas/main/from_eks?key=".$key);
	}
}