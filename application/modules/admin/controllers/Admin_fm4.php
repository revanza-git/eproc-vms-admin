<?php defined('BASEPATH') or exit('No direct script access allowed');

class Admin_fm4 extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('admin')) {
            redirect(site_url());
        }
        $this->load->model('admin_fm4_model', 'afm');
    }

    public function index()
    {
        $search     = $this->input->get('q');
        $page       = '';
        $per_page   = 10;
        $sort       = $this->utility->generateSort(array('id_bidang', 'name'));

        $data = array(
            'ms_quest' => $this->afm->get_header_list(),
            'sub_quest' => $this->afm->get_sub_quest_list(),
            'data_quest' => $this->afm->get_quest_list(),
            'data_field' => $this->afm->get_data_field(),
            'evaluasi' => $this->afm->get_evaluasi_data_list(),
            'quest_all' => array()
        );

        foreach ($data['ms_quest'] as $key_ms => $row_ms) {
            $data['quest_all'][$key_ms]['label']     = $row_ms;
        }

        foreach ($data['sub_quest'] as $key_sub_quest => $val_sub_quest) {
            foreach ($val_sub_quest as $k_sub_quest => $v_sub_quest) {
                $data['quest_all'][$key_sub_quest]['data'][$k_sub_quest] = $v_sub_quest;
            }
        }

        foreach ($data['data_quest'] as $key_quest => $val_quest) {
            $data['quest_all'][$val_quest['id_ms_header']]['data'][$val_quest['id_sub_header']]['data'][$val_quest['id']] = array();
        }
        // foreach()

        foreach ($data['data_field'] as $key_data => $value_data) {
            $data['quest_all'][$value_data['id_ms_header']]['data'][$value_data['id_sub_header']]['data'][$value_data['id_question']][$value_data['id']] = $value_data;
        }

        $vld = array(
            array(
                'field' => 'id_evaluasi',
                'label' => 'Pilih Evaluasi Untuk Penilaian',
                'rules' => 'required'
            ),
        );

        $this->form_validation->set_rules($vld);
        if ($this->form_validation->run() == TRUE) {
            unset($_POST['newGroup']);
            $_POST['entry_stamp'] = date("Y-m-d H:i:s");
            // print_r($this->input->post());
            $res = $this->afm->save_group_quest($this->input->post());
            $idh = $this->db->insert_id();
            if ($res) {
                $this->session->set_flashdata('msgSuccess', '<p class="msgSuccess">Sukses menambah kelompok pertanyaan! <a href="#' . $idh . '">klik disini</a></p>');
                redirect(site_url('admin/admin_fm4'));
            }
        }
        // echo print_r($data['quest_all']);
        $layout['content']    = $this->load->view('fm4/content', $data, TRUE);

        $item['header']     = $this->load->view('admin/header', $this->session->userdata('admin'), TRUE);
        $item['content']     = $this->load->view('admin/dashboard', $layout, TRUE);
        $this->load->view('template', $item);
    }

    ###################################################
    ################  		FM4		 	###############
    ###################################################

    public function edit_header($id)
    {
        // $data 			= $this->afm->get_data_assessment($id);
        $data['header']        = $this->afm->get_header($id);

        $_POST             = $this->securities->clean_input($_POST, 'save');
        $admin             = $this->session->userdata('admin');
        $vld             = array(
            array(
                'field' => 'question',
                'label' => 'Nama Header',
                'rules' => 'required'
            ),
        );

        $this->form_validation->set_rules($vld);
        if ($this->form_validation->run() == TRUE) {
            $_POST['edit_stamp'] = date("Y-m-d H:i:s");
            unset($_POST['Update']);

            $res = $this->afm->save_edit_header($this->input->post(), $id);

            if ($res) {
                $this->session->set_flashdata('msgSuccess', '<p class="msgSuccess">Sukses mengubah data!</p>');
                redirect(site_url('admin/admin_fm4'));
            }
        }

        $layout['content']    = $this->load->view('fm4/edit_header', $data, TRUE);

        $admin                 = $this->session->userdata('admin');
        $item['header']     = $this->load->view('admin/header', $admin, TRUE);
        $item['content']     = $this->load->view('admin/dashboard', $layout, TRUE);
        $this->load->view('template', $item);
    }

    public function edit_sub_quest($id)
    {
        $data['sub_quest']    = $this->afm->get_edit_sub_quest($id);

        $_POST             = $this->securities->clean_input($_POST, 'save');
        $admin             = $this->session->userdata('admin');
        $vld             = array(
            array(
                'field' => 'question',
                'label' => 'Nama Sub Quest',
                'rules' => 'required'
            ),
        );

        $this->form_validation->set_rules($vld);
        if ($this->form_validation->run() == TRUE) {
            $_POST['edit_stamp'] = date("Y-m-d H:i:s");
            unset($_POST['Update']);

            $res = $this->afm->save_edit_sub_quest($this->input->post(), $id);

            if ($res) {
                $this->session->set_flashdata('msgSuccess', '<p class="msgSuccess">Sukses mengubah data!</p>');
                redirect(site_url('admin/admin_fm4'));
            }
        }

        $layout['content']    = $this->load->view('fm4/edit_sub_quest', $data, TRUE);

        $admin                 = $this->session->userdata('admin');
        $item['header']     = $this->load->view('admin/header', $admin, TRUE);
        $item['content']     = $this->load->view('admin/dashboard', $layout, TRUE);
        $this->load->view('template', $item);
    }

    public function edit_quest($id)
    {
        $data['quest']    = $this->afm->get_edit_quest($id);
        // echo print_r($data['quest']);


        $admin     = $this->session->userdata('admin');
        $vld     = array(
            array(
                'field' => 'value',
                'label' => 'Pertanyaan',
                'rules' => 'required'
            ),
            array(
                'field' => 'type',
                'label' => 'Tipe',
                'rules' => 'required'
            ),
        );

        if ($this->input->post('type') == "file") {
            $vld[] = array(
                'field' => 'labelfield',
                'label' => 'Kolom Label',
                'rules' => ''
            );

            $label             = strtolower($_POST['labelfield']);
            $file            = explode(" ", $label);

            $_POST['label'] = implode("_", $file);
            unset($_POST['labelfield']);
        }

        if ($this->input->post('type') == "radio") {
            $vld[] = array(
                'field' => 'labelfield',
                'label' => 'Kolom Label',
                'rules' => ''
            );

            $label             = $_POST['labelfield'];
            $_POST['label'] = implode("|", $label);
            unset($_POST['labelfield']);
        }


        $this->form_validation->set_rules($vld);
        if ($this->form_validation->run() == TRUE) {
            $_POST['edit_stamp'] = date("Y-m-d H:i:s");
            unset($_POST['Update']);

            $res = $this->afm->save_edit_quest($this->input->post(), $id);

            if ($res) {
                $this->session->set_flashdata('msgSuccess', '<p class="msgSuccess">Sukses mengubah data!</p>');
                redirect(site_url('admin/admin_fm4'));
            }
        }

        $layout['content']    = $this->load->view('fm4/edit_quest', $data, TRUE);

        $admin                 = $this->session->userdata('admin');
        $item['header']     = $this->load->view('admin/header', $admin, TRUE);
        $item['content']     = $this->load->view('admin/dashboard', $layout, TRUE);
        $this->load->view('template', $item);
    }

    public function hapus_header($id)
    {
        if ($this->afm->hapus_header($id)) {
            $this->session->set_flashdata('msgSuccess', '<p class="msgSuccess">Sukses menghapus data!</p>');
            redirect(site_url('admin/admin_fm4'));
        } else {
            $this->session->set_flashdata('msgSuccess', '<p class="msgError">Gagal menghapus data!</p>');
            redirect(site_url('admin/admin_fm4'));
        }
    }

    public function hapus_sub_quest($id)
    {
        if ($this->afm->hapus_sub_quest($id)) {
            $this->session->set_flashdata('msgSuccess', '<p class="msgSuccess">Sukses menghapus data!</p>');
            redirect(site_url('admin/admin_fm4'));
        } else {
            $this->session->set_flashdata('msgSuccess', '<p class="msgError">Gagal menghapus data!</p>');
            redirect(site_url('admin/admin_fm4'));
        }
    }

    public function hapus_quest($id)
    {
        if ($this->afm->hapus_quest($id)) {
            $this->session->set_flashdata('msgSuccess', '<p class="msgSuccess">Sukses menghapus data!</p>');
            redirect(site_url('admin/admin_fm4'));
        } else {
            $this->session->set_flashdata('msgSuccess', '<p class="msgError">Gagal menghapus data!</p>');
            redirect(site_url('admin/admin_fm4'));
        }
    }

    public function tambah_header()
    {
        $admin     = $this->session->userdata('admin');
        $vld     = array(
            array(
                'field' => 'question',
                'label' => 'Nama Header',
                'rules' => 'required'
            )
        );

        $this->form_validation->set_rules($vld);
        if ($this->form_validation->run() == TRUE) {
            unset($_POST['Update']);
            $_POST['entry_stamp']     = date("Y-m-d H:i:s");
            $res = $this->afm->save_header($this->input->post());
            if ($res) {
                $this->session->set_flashdata('msgSuccess', '<p class="msgSuccess">Sukses menambah data!</p>');
                redirect(site_url('admin/admin_fm4'));
            }
        }

        $layout['content']    = $this->load->view('fm4/tambah_header', NULL, TRUE);

        $item['header']     = $this->load->view('admin/header', $admin, TRUE);
        $item['content']     = $this->load->view('admin/dashboard', $layout, TRUE);
        $this->load->view('template', $item);
    }

    public function tambah_sub_quest($id)
    {
        $admin     = $this->session->userdata('admin');
        $vld     = array(
            array(
                'field' => 'question',
                'label' => 'Nama Sub Quest',
                'rules' => 'required'
            )
        );

        $this->form_validation->set_rules($vld);
        if ($this->form_validation->run() == TRUE) {
            unset($_POST['Simpan']);
            $_POST['entry_stamp']     = date("Y-m-d H:i:s");
            $_POST['id_header']        = $id;
            $res = $this->afm->save_sub_quest($this->input->post());
            if ($res) {
                $this->session->set_flashdata('msgSuccess', '<p class="msgSuccess">Sukses menambah data!</p>');
                redirect(site_url('admin/admin_fm4'));
            } else {
                $this->session->set_flashdata('msgSuccess', '<p class="msgError">Gagal menambah data!</p>');
            }
        }

        $layout['content']    = $this->load->view('fm4/tambah_sub_quest', NULL, TRUE);

        $item['header']     = $this->load->view('admin/header', $admin, TRUE);
        $item['content']     = $this->load->view('admin/dashboard', $layout, TRUE);
        $this->load->view('template', $item);
    }

    public function tambah_quest($id)
    {
        $admin     = $this->session->userdata('admin');
        $vld     = array(
            array(
                'field' => 'value',
                'label' => 'Pertanyaan',
                'rules' => 'required'
            ),
            array(
                'field' => 'type',
                'label' => 'Tipe',
                'rules' => 'required'
            ),
        );

        if ($this->input->post('type') == "file") {
            $vld[] = array(
                'field' => 'labelfield',
                'label' => 'Kolom Label',
                'rules' => ''
            );

            $label             = strtolower($_POST['labelfield']);
            $file            = explode(" ", $label);

            $_POST['label'] = implode("_", $file);
            unset($_POST['labelfield']);
        }

        if ($this->input->post('type') == "radio") {
            $vld[] = array(
                'field' => 'labelfield',
                'label' => 'Kolom Label',
                'rules' => ''
            );

            $label             = $_POST['labelfield'];
            $_POST['label'] = implode("|", $label);
            unset($_POST['labelfield']);
        }


        $this->form_validation->set_rules($vld);
        if ($this->form_validation->run() == TRUE) {
            unset($_POST['Simpan']);
            $idsh = 0;

            $_POST['entry_stamp']     = date("Y-m-d H:i:s");
            $_POST['id_question']    = $id;
            $res = $this->afm->save_quest($this->input->post());
            if ($res) {
                $this->session->set_flashdata('msgSuccess', '<p class="msgSuccess">Sukses menambah data!</p>');
                redirect(site_url('admin/admin_fm4'));
            }
        }
        // print_r($this->input->post());


        $layout['content']    = $this->load->view('fm4/tambah_quest', NULL, TRUE);
        $item['header']     = $this->load->view('admin/header', $admin, TRUE);
        $item['content']     = $this->load->view('admin/dashboard', $layout, TRUE);
        $this->load->view('template', $item);
    }

    public function tambah_group_quest($idh, $idsh = 0)
    {
        // $_POST	= $this->securities->clean_input($_POST,'save');
        $admin     = $this->session->userdata('admin');

        unset($_POST['Simpan']);
        $_POST['entry_stamp']         = date("Y-m-d H:i:s");
        $_POST['id_ms_header']        = $idh;
        $_POST['id_sub_header']        = $idsh;
        $res = $this->afm->save_group_quest($this->input->post());
        if ($res) {
            $this->session->set_flashdata('msgSuccess', '<p class="msgSuccess">Sukses menambah kelompok pertanyaan! <a href="#' . $idh . '">klik disini</a></p>');
            redirect(site_url('admin/admin_fm4'));
        }
    }

    public function edit_group_quest($id)
    {
        $data['header']            = $this->afm->get_header_dropdown($id);
        $data['sub_header']        = $this->afm->get_sub_header_dropdown($id);
        $data['evaluasi']        = $this->afm->get_evaluasi_data_list($id);
        $data['quest']            = $this->afm->get_evaluasi_edit($id);

        $_POST             = $this->securities->clean_input($_POST, 'save');
        $admin             = $this->session->userdata('admin');
        $vld             = array(
            array(
                'field' => 'id_ms_header',
                'label' => 'Pilih Header',
                'rules' => 'required'
            ),
            array(
                'field' => 'id_sub_header',
                'label' => 'Pilih Sub Header',
                'rules' => 'required'
            ),
            array(
                'field' => 'id_evaluasi',
                'label' => 'Pilih Evaluasi',
                'rules' => 'required'
            )
        );

        $this->form_validation->set_rules($vld);
        if ($this->form_validation->run() == TRUE) {
            $_POST['edit_stamp'] = date("Y-m-d H:i:s");
            unset($_POST['Simpan']);

            $res = $this->afm->save_edit_group($this->input->post(), $id);
            // print_r($this->input->post());
            if ($res) {
                $this->session->set_flashdata('msgSuccess', '<p class="msgSuccess">Sukses mengubah data!</p>');
                redirect(site_url('admin/admin_fm4'));
            }
        }

        $layout['content']    = $this->load->view('fm4/edit_group_quest', $data, TRUE);

        $admin                 = $this->session->userdata('admin');
        $item['header']     = $this->load->view('admin/header', $admin, TRUE);
        $item['content']     = $this->load->view('admin/dashboard', $layout, TRUE);
        $this->load->view('template', $item);
    }

    public function hapus_group_quest($id)
    {
        if ($this->afm->hapus_group_quest($id)) {
            $this->session->set_flashdata('msgSuccess', '<p class="msgSuccess">Sukses menghapus data!</p>');
            redirect(site_url('admin/admin_fm4'));
        } else {
            $this->session->set_flashdata('msgSuccess', '<p class="msgError">Gagal menghapus data!</p>');
            redirect(site_url('admin/admin_fm4'));
        }
    }

    /*
        PENILAIAN
    */

    public function get_vendor_group()
    {
        $this->load->library('form');
        $admin = $this->session->userdata('admin');
        $data['filter_list'] = $this->filter->group_filter_post($this->get_field());
        $search = $this->input->get('q');
        $page = '';


        $per_page = 10;

        $sort = $this->utility->generateSort(array('name', 'score'));

        $data['vendor_list'] = $this->afm->get_k3_vendor($search, $sort, $page, $per_page, TRUE);

        $data['pagination'] = $this->utility->generate_page('admin/admin_fm4/get_vendor_group', $sort, $per_page, $this->afm->get_k3_vendor($search, $sort, '', '', FALSE));
        $data['sort'] = $sort;
        $data['admin'] = $admin;
        $layout['content'] = $this->load->view('fm4/content_dpt', $data, TRUE);
        $item['header'] = $this->load->view('admin/header', $admin, TRUE);
        $item['content'] = $this->load->view('admin/dashboard', $layout, TRUE);
        $this->load->view('template', $item);
    }

    public function get_field()
    {
        return array(
            array(
                'label'    =>    'User',
                'filter' =>    array(
                    array('table' => 'ms_vendor|name', 'type' => 'text', 'label' => 'Nama'),
                    array('table' => 'ms_score_k3|score', 'type' => 'number_range', 'label' => 'Skor'),
                )
            ),

        );
    }

    public function penilaian_k3($id_vendor, $act = 'create', $id_csms = 0)
    {
        $data['act'] = $act;
        $data['vendor'] = $this->afm->get_vendor_data($id_vendor);
        $data['ms_quest'] = $this->afm->get_master_header();

        $data['quest'] = $this->afm->get_quest();
        $data['field_quest'] = $this->afm->get_field_quest();
        $data['evaluasi_list'] = $this->afm->get_evaluasi_list();
        $data['evaluasi'] = $this->afm->get_evaluasi();
        $data['data_k3'] = $this->afm->get_k3_data($id_vendor);
        $data['get_csms'] = $this->afm->get_csms($id_vendor);
        $data['get_hse'] = $this->afm->get_hse($id_vendor);

        $data['csms_file']     = $this->afm->get_k3_all_data($id_vendor)['csms_file'];

        $data['value_k3'] = $this->afm->get_penilaian_value($id_vendor, $data['csms_file']['id']);
        $vendor                    = $this->afm->get_vendor_data($id_vendor);

        // print_r($data['evaluasi']);die;
        if ($this->input->post('simpan')) {

            $res = $this->afm->save_evaluasi_poin($this->input->post('evaluasi'), $id_vendor, $act, $id_csms);

            if ($res) {
                $email['subject']    = "Penilaian CSMS (" . $vendor['type'] . ". " . $vendor['name'] . ") - Sistem Aplikasi Kelogistikan PT Nusantara Regas";
                $email['message']    = 'Admin Logistik telah selesai mengadakan penilaian CSMS untuk Penyedia Barang / Jasa ' . $vendor['type'] . ". " . $vendor['name'] . "  dalam Sistem Aplikasi Kelogistikan PT Nusantara Regas.
										<br>
										Untuk melengkapi proses verifikasi, harap login ke sistem dan mengakses menu Penilaian CSMS.
										<br><br>
										Terimakasih,<br>
										PT Nusantara Regas";
                // foreach ($this->afm->get_admin_email(1) as $key => $admin) {
                //     $this->utility->mail($admin['email'], $email['message'], $email['subject']);
                // }
                $this->session->set_flashdata('msgSuccess', '<p class="msgSuccess">Sukses menyimpan data!</p>');
                redirect('fm4/penilaian_view/' . $id_vendor);
            }
        }
        $layout['content'] = $this->load->view('fm4/penilaian_k3', $data, TRUE);
        $layout['script'] = $this->load->view('fm4/penilaian_k3_js', $data, TRUE);

        $item['header'] = $this->load->view('admin/header', $this->session->userdata('admin'), TRUE);
        $item['content'] = $this->load->view('admin/dashboard', $layout, TRUE);
        $this->load->view('template', $item);
    }

    public function penilaian_view($id_vendor)
    {
        $data['id'] = $id_vendor;
        $data['vendor'] = $this->afm->get_vendor_data($id_vendor);

        $data['ms_quest'] = $this->afm->get_master_header();
        $data['quest'] = $this->afm->get_quest();
        $data['field_quest'] = $this->afm->get_field_quest();
        $data['evaluasi_list'] = $this->afm->get_evaluasi_list();
        $data['evaluasi'] = $this->afm->get_evaluasi();
        $data['get_csms'] = $this->afm->get_csms($id_vendor);

        $data['data_k3'] = $this->afm->get_k3_data($id_vendor);
        $data['csms_limit'] = $this->afm->get_csms_limit($id_vendor);



        $data['data_poin'] = $this->afm->get_poin($id_vendor);
        $data['csms_file']     = $this->afm->get_k3_all_data($id_vendor)['csms_file'];


        $data['value_k3'] = $this->afm->get_penilaian_value($id_vendor, $data['csms_file']['id']);

        // print_r($data);

        $layout['content'] = $this->load->view('fm4/penilaian_view', $data, TRUE);

        $item['header'] = $this->load->view('admin/header', $this->session->userdata('admin'), TRUE);
        $item['content'] = $this->load->view('admin/dashboard', $layout, TRUE);
        $this->load->view('template', $item);
    }

    public function history_nilai($id)
    {
        $this->load->library('form');
        $search = $this->input->get('q');
        $page = '';
        // unset($_POST);

        $per_page = 10;

        $sort = $this->utility->generateSort(array('name', 'score', 'entry_stamp'));
        $data['pagination'] = $this->utility->generate_page('admin/admin_fm4/history_nilai', $sort, $per_page, $this->afm->get_history_nilai($id, $sort, '', '', FALSE));
        $data['sort'] = $sort;
        $data['history'] = $this->afm->get_history_nilai($id);

        $layout['content'] = $this->load->view('fm4/history_nilai', $data, TRUE);
        $item['header'] = $this->load->view('admin/header', $this->session->userdata('admin'), TRUE);
        $item['content'] = $this->load->view('admin/dashboard', $layout, TRUE);
        $this->load->view('template', $item);
    }
}
