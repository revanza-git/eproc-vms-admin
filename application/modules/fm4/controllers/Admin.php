<?php defined('BASEPATH') or exit('No direct script access allowed');
class Admin extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('admin')) {
            redirect(site_url());
        }
        $this->load->model('fm4_model', 'fm');
    }

    public function list_vendor()
    {
        $search = $this->input->get('q');
        $page = '';
        $per_page = 10;
        $sort     = $this->utility->generateSort(array('ms_vendor.name', 'legal_name', 'sbu_name', 'username', 'password', 'score'));
        $filter = $this->input->post('filter');

        $data['filter_list']     = $this->filter->group_filter_post($this->get_field());
        $data['vendor_list']    = $this->fm->get_vendor_list($search, $sort, $page, $per_page, TRUE, $filter);
        $data['pagination'] = $this->utility->generate_page('fm4/list_vendor', $sort, $per_page, $this->fm->get_vendor_list($search, $sort, '', '', FALSE, $filter));
        $data['sort'] = $sort;

        $layout['content'] = $this->load->view('content_vendor', $data, TRUE);
        $item['header'] = $this->load->view('admin/header', $this->session->userdata('admin'), TRUE);
        $item['content'] = $this->load->view('admin/dashboard', $layout, TRUE);
        $this->load->view('template', $item);
    }

    public function form_fm4($id_vendor)
    {
        $data_k3['csms_file'] = $this->fm->get_k3_all_data($id_vendor);

        if (empty($data_k3['csms_file']['csms_file']) && empty($data_k3['csms_file']['answer'])) {
            redirect(site_url('fm4/admin/first_form/' . $id_vendor));
        }
    }

    public function first_form($id_vendor)
    {
        $user = $this->session->userdata('user');
        if ($this->input->post('next')) {
            $vld =     array(
                array(
                    'field' => 'csms',
                    'label' => 'csms_radio',
                    'rules' => 'required'
                )
            );

            if ($this->input->post('csms') == 1) {
                $vld     =     array(
                    array(
                        'field' => 'csms_file',
                        'label' => 'Lampiran CSMS',
                        'rules' => 'callback_do_upload_single[csms_file]'
                    ),
                    // array(
                    // 	'field'=>'expiry_date',
                    // 	'label'=>'Masa Berlaku',
                    // 	'rules'=>'required|callback_backdate'
                    // ),
                    array(
                        'field' => 'score',
                        'label' => 'Nilai',
                        'rules' => 'required'
                    )
                );

                $this->form_validation->set_rules($vld);
                if ($this->form_validation->run() == TRUE) {

                    $_POST['entry_stamp'] = date("Y-m-d H:i:s");

                    $res = $this->km->save_csms_data($this->input->post(), $user['id_user']);

                    if ($res) {
                        $this->session->set_flashdata('msgSuccess', '<p class="msgSuccess">Sukses menambah data!</p>');
                        $this->dpt->non_iu_change($user['id_user']);
                        redirect(site_url('k3'));
                    }
                }
            } else {

                redirect(site_url('k3/csms_form'));
            }
        }

        $layout['content']    = $this->load->view('form_csms', NULL, TRUE);
        $layout['script']    = $this->load->view('form_k3_js', NULL, TRUE);

        $item['header'] = $this->load->view('admin/header', $this->session->userdata('admin'), TRUE);
        $item['content'] = $this->load->view('admin/dashboard', $layout, TRUE);
        $this->load->view('template', $item);
    }

    public function get_field()
    {
        return array(
            array(
                'label'    =>    'Penyedia Barang/Jasa',
                'filter' =>    array(
                    array('table' => 'ms_vendor|name', 'type' => 'text', 'label' => 'Nama Penyedia Barang/Jasa'),
                    array('table' => 'tb_legal|name', 'type' => 'text', 'label' => 'Badan Usaha'),
                    array('table' => 'ms_login|username', 'type' => 'text', 'label' => 'Username'),
                )
            ),
        );
    }
}
