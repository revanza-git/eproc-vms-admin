<?php defined('BASEPATH') or exit('No direct script access allowed');

class Main extends CI_Controller
{

	public function index()
	{
		if ($this->session->userdata('user')) {
			redirect('dashboard');
		} elseif ($this->session->userdata('admin')) {
			redirect('admin');
		} else {
			$this->session->sess_destroy('form');
			// $item['header'] = '';
			// $item['content'] = $this->load->view('login', NULL, TRUE);
			// $this->load->view('template', $item);
			header('Location: ' . TO_LOGIN);
		}
	}
	public function login()
	{


		$this->load->model('main_model');

		if ($this->input->post('username') && $this->input->post('password')) {
			$is_logged = $this->main_model->cek_login();

			if ($is_logged) {

				if ($this->session->userdata('user')) {
					$data = $this->session->userdata('user');

					$item['content'] 	= $this->load->view('redirect', $data, TRUE);
					$this->load->view('template', $item);
				} else if ($this->session->userdata('admin')) {
					if ($this->session->userdata('admin')['id_role'] == 6) {
						redirect(site_url('auction'));
					} else {
						//header('Location:http://10.10.10.3/eproc');
						redirect(site_url('admin'));
					}
				}
			} else {
				$this->session->set_flashdata('error_msg', 'Data tidak dikenal. Silahkan login kembali!');
				redirect(site_url());
			}
		} else {

			$this->session->set_flashdata('error_msg', 'Isi form dengan benar!');
			redirect(site_url());
		}
	}
	public function logout()
	{
		$this->session->sess_destroy();
		// redirect(site_url());
		header('Location: ' . TO_LOGIN);
	}
}
