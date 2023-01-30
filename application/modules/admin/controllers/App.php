<?php
/**
 * 
 */
class App extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
		$this->load->model('main_model','mm');
	}

	public function index()
	{
		// if (!$this->session->userdata('admin')) {
            // redirect('main');
        // }
		
		$admin = $this->session->userdata('admin');

		// print_r($admin);die;

		$getUser = $this->mm->to_app($admin['id_user']);
		
		$this->session->sess_destroy();
		// print_r($getUser);die;
		$name 			= $getUser['name'];
		$id_user 		= $getUser['id'];
		$id_role 		= $getUser['id_role_app2'];
		$id_division	= $getUser['id_division'];
		$app_type		= 1;
		$email	 		= $getUser['email'];
		$photo_profile 	= $getUser['photo_profile'];
		// $division	 	= $getUser['division'];

		header("Location: http://10.10.10.3/eproc_nusantararegas/main/from_eks/".$name."/".$id_user."/".$id_role."/".$id_division."/".$app_type."/".$email."/".$photo_profile);
	}
}