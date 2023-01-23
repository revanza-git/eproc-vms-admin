<?php defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();

        $this->load->model('Auth_model', 'am');
    }

    public function from_external($id_user, $type)
    {
        $set_session = $this->am->get_user($id_user, $type);

        if ($type == 'user') {
            $user = $this->session->userdata('user');
            $data['name']        = $user['name'];
            $item['content']     = $this->load->view('main/redirect', $data, TRUE);
            $this->load->view('template', $item);
        } else {
            $admin = $this->session->userdata('admin');
            if ($admin['id_role'] == 6) {
                redirect(site_url('auction'));
            } else {
                //header('Location:http://10.10.10.3/eproc');
                redirect(site_url('admin'));
            }
        }
    }

    public function from_internal($id_user)
    {
        $set_session = $this->am->get_user($id_user, 'admin');
        $admin = $this->session->userdata('admin');
        if ($admin['id_role'] == 6) {
            redirect(site_url('auction'));
        } else {
            //header('Location:http://10.10.10.3/eproc');
            redirect(site_url('admin'));
        }
    }
}
