<?php

/**
 * 
 */
class App extends CI_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->model('main_model', 'mm');
	}

	public function index()
	{
		$admin = $this->session->userdata('admin');

		$id_user = $admin['id_user'];

		$this->session->sess_destroy();

		header('Location: ' . TO_EPROC . 'auth/from_external/' . $id_user);
	}
}
