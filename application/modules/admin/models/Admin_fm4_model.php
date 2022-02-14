<?php defined('BASEPATH') or exit('No direct script access allowed');

class Admin_fm4_model extends CI_Model
{
    public function get_header_list()
    {
        $result     = array();
        $ms_quest   = $this->db->where('del', 0)->get('tb_ms_quest_fm4')->result_array();
        foreach ($ms_quest as $key => $value) {
            $result[$value['id']] = $value['question'];
        }
        return $result;
    }

    public function get_sub_quest_list()
    {
        $result     = array();
        $sub_quest  = $this->db->where('del', 0)->get('tb_sub_quest_fm4')->result_array();
        foreach ($sub_quest as $keysq => $valuesq) {
            $result[$valuesq['id_header']][$valuesq['id']] = $valuesq;
        }
        return $result;
    }

    public function get_quest_list()
    {
        $res = $this->db->where('del', 0)->get('tb_quest_fm4')->result_array();
        $result = array();

        foreach ($res as $key => $row) {
            $result[$row['id']] = $row;
        }
        return $result;
    }

    public function get_data_field()
    {
        $quest = $this->db->select('*,ms_answer_hse_fm4.id id,')
            ->where('tb_quest_fm4.del', 0)
            ->where('ms_answer_hse_fm4.del', 0)
            ->join('tb_quest_fm4', 'tb_quest_fm4.id=ms_answer_hse_fm4.id_question')
            ->get('ms_answer_hse_fm4')
            ->result_array();
        foreach ($quest as $id => $quest) {
            $result[$quest['id']] = $quest;
        }
        return $result;
    }

    public function get_evaluasi_data_list()
    {
        $get = $this->db->where('del', 0)->select('id,name')->get('tb_evaluasi_fm4');
        $raw = $get->result_array();
        $res = array();
        $res[''] = 'Pilih Evaluasi Untuk Penilaian';
        foreach ($raw as $key => $val) {
            $res[$val['id']] = $val['name'];
        }
        return $res;
    }

    /* HEADER */

    public function save_header($data)
    {
        $a = $this->db->insert('tb_ms_quest_fm4', $data);
        return $a;
    }

    public function get_header($id)
    {
        $result = $this->db->select('*')->where('id', $id)->get('tb_ms_quest_fm4')->result_array();
        $arr = array();
        foreach ($result as $key => $val) {
            $arr[$val['id']] = $val['question'];
        }
        return $arr;
    }

    public function save_edit_header($data, $id)
    {
        $this->db->where('id', $id);
        $res = $this->db->update('tb_ms_quest_fm4', $data);

        return $res;
    }

    public function hapus_header($id)
    {
        $this->db->where('id', $id);

        return $this->db->update('tb_ms_quest_fm4', array('del' => 1));
    }

    /* SUB QUEST */

    public function save_sub_quest($data)
    {
        $a = $this->db->insert('tb_sub_quest_fm4', $data);
        return $a;
    }

    public function get_edit_sub_quest($id)
    {
        $result = $this->db->where('id', $id)->where('del', 0)->get('tb_sub_quest_fm4')->result_array();
        $arr = array();
        foreach ($result as $key => $val) {
            $arr[$val['id']] = $val['question'];
        }
        return $arr;
    }

    public function save_edit_sub_quest($data, $id)
    {
        $this->db->where('id', $id);
        $res = $this->db->update('tb_sub_quest_fm4', $data);

        return $res;
    }

    function hapus_sub_quest($id)
    {
        $this->db->where('id', $id);

        return $this->db->update('tb_sub_quest_fm4', array('del' => 1));
    }

    public function save_group_quest($data)
    {
        $a = $this->db->insert('tb_quest_fm4', $data);
        return $a;
    }

    /* QUEST */

    public function save_quest($data)
    {
        $data['del'] = 0;
        $a = $this->db->insert('ms_answer_hse_fm4', $data);
        return $a;
    }

    public function save_edit_group($data, $id)
    {
        $this->db->where('id', $id);
        $res = $this->db->update('tb_quest_fm4', $data);

        return $res;
    }

    public function hapus_group_quest($id)
    {
        $this->db->where('id', $id);

        return $this->db->update('tb_quest_fm4', array('del' => 1));
    }

    public function get_edit_quest($id)
    {
        $result = $this->db->select('*')->where('id', $id)->get('ms_answer_hse_fm4')->result_array();
        return $result;
    }

    public function save_edit_quest($data, $id)
    {
        $this->db->where('id', $id);
        $res = $this->db->update('ms_answer_hse_fm4', $data);

        return $res;
    }

    function hapus_quest($id)
    {
        $this->db->where('id', $id);

        return $this->db->update('ms_answer_hse_fm4', array('del' => 1));
    }

    /* OTHERS */

    public function get_header_dropdown($id)
    {
        $get = $this->db->where('del', 0)->select('id,question')->get('tb_ms_quest_fm4');
        $raw = $get->result_array();
        $res = array();
        $res[''] = 'Pilih salah satu';
        foreach ($raw as $key => $val) {
            $res[$val['id']] = $val['question'];
        }
        return $res;
    }

    public function get_sub_header_dropdown($id)
    {
        $get = $this->db->where('del', 0)->select('id,question')->get('tb_sub_quest_fm4');
        $raw = $get->result_array();
        $res = array();
        $res[''] = 'Pilih salah satu';
        foreach ($raw as $key => $val) {
            $res[$val['id']] = $val['question'];
        }
        return $res;
    }

    public function get_evaluasi_edit($id)
    {
        $result = $this->db->select('*')
            ->where('tb_quest_fm4.id', $id)
            ->get('tb_quest_fm4')->result_array();

        return $result;
    }

    public function get_master_header()
    {
        $result = array();
        $res = $this->db->where('del', 0)->get('tb_ms_quest_fm4')->result_array();
        foreach ($res as $key_sub => $value_sub) {
            $result[$value_sub['id']] = $value_sub['question'];
        }
        return $result;
    }

    public function get_quest()
    {
        $res = $this->db->where('del', 0)->get('tb_quest_fm4')->result_array();
        $result = array();

        foreach ($res as $key => $row) {
            $quest = $this->db->select('*')->where('del', 0)->where('id_question', $row['id'])->get('ms_answer_hse_fm4')->result_array();
            foreach ($quest as $id => $quest) {
                $result[$row['id_ms_header']][$row['id_sub_header']][$row['id']][$quest['id']] = $quest;
            }
        }

        return $result;
    }

    public function get_field_quest()
    {
        $quest = $this->db->select('*,ms_answer_hse_fm4.id id')
            ->where('ms_answer_hse_fm4.del', 0)
            ->where('tb_quest_fm4.del', 0)
            ->join('tb_quest_fm4', 'tb_quest_fm4.id=ms_answer_hse_fm4.id_question')
            ->get('ms_answer_hse_fm4')
            ->result_array();
        foreach ($quest as $id => $quest) {
            $result[$quest['id']] = $quest;
        }

        return $result;
    }

    public function get_evaluasi_list()
    {
        $result = array();
        $res = $this->db->get('tb_evaluasi_fm4')->result_array();
        foreach ($res as $key_sub => $value_sub) {
            $result[$value_sub['id']] = $value_sub;
        }
        return $result;
    }

    public function get_evaluasi()
    {
        $result = array();

        foreach ($this->get_master_header() as $key_ms => $value_ms) {

            $evaluasi = $this->db->where('id_ms_quest', $key_ms)->get('tb_evaluasi_fm4')->result_array();
            foreach ($evaluasi as $key_ev => $data_ev) {

                $quests = $this->db->where('id_evaluasi', $data_ev['id'])->where('del', 0)->get('tb_quest_fm4')->result_array();
                // echo print_r($evaluasi);
                foreach ($quests as $key_quest => $quest) {

                    $answer = $this->db->where('id_question', $quest['id'])->where('del', 0)->get('ms_answer_hse_fm4')->result_array();
                    $result[$quest['id_ms_header']][$quest['id_evaluasi']][$quest['id']] = $answer;
                }
            }
        }

        return $result;
    }

    public function get_k3_data($id_vendor)
    {
        $get = $this->db->where('id_vendor', $id_vendor)->get('tr_answer_hse_fm4')->result_array();
        $result = array();
        foreach ($get as $key => $value) {
            $result[$value['id_answer']] = $value;
        }

        return $result;
    }

    public function get_csms($id_vendor)
    {
        $res = array();
        $res = $this->db->select('ms_csms_fm4.*, tb_csms_limit_fm4.value')->where('id_vendor', $id_vendor)->where('ms_csms_fm4.del', 0)->join('tb_csms_limit_fm4', 'tb_csms_limit_fm4.id=ms_csms_fm4.id_csms_limit', 'LEFT')->order_by('ms_csms_fm4.id', 'desc')->get('ms_csms_fm4')->row_array();
        return $res;
    }

    public function get_hse($id_vendor)
    {
        return  $this->db->where('id_vendor', $id_vendor)->get('ms_hse_fm4')->row_array();
    }

    public function get_k3_all_data($id)
    {
        $result = array();
        $get_csms = $this->get_csms($id);
        if (!empty($get_csms)) {
            $result['csms_file'] = $get_csms;
        }
        $get_k3_data = $this->get_k3_data($id);
        if (!empty($get_k3_data)) {
            $result['answer'] = $get_k3_data;
        }
        return $result;
    }

    public function get_penilaian_value($id_vendor, $id_csms = 0)
    {
        $get = $this->db->select('id_evaluasi,poin')->where('id_vendor', $id_vendor)->where('id_csms', $id_csms)->get('tr_evaluasi_poin_fm4')->result_array();
        $result = array();
        foreach ($get as $key => $value) {
            $result[$value['id_evaluasi']] = $value['poin'];
        }

        return $result;
    }

    public function get_vendor_data($id)
    {
        $result = $this->db->select('ms_vendor.name name, tb_legal.name type')
            ->where('ms_vendor.id', $id)
            ->join('ms_vendor_admistrasi', 'ms_vendor_admistrasi.id_vendor = ms_vendor.id', 'LEFT')
            ->join('tb_legal', 'tb_legal.id = ms_vendor_admistrasi.id_legal', 'LEFT')
            ->get('ms_vendor')
            ->row_array();

        return $result;
    }

    function save_evaluasi_poin($post, $id, $act = 'create', $id_csms = 0)
    {
        $base_total = array();

        foreach ($post as $key => $row) {

            $base_total[$this->get_evaluasi_list()[$key]['id_ms_quest']][] = $row;
        }
        $skor = 0;
        foreach ($base_total as $ms_quest => $value) {
            $total_row = count($value);
            $sum = array_sum($value);
            $skor += ($sum / $total_row);
        }

        // $nr = $this->db->select('*')->where(array('id_vendor'=>$id))->get('ms_score_k3')->num_rows();
        $id_csms_limit = $this->get_k3_limit($skor);

        // print_r($id_csms_limit);
        $aff_id = 0;
        if ($act == 'create') {
            $this->db->insert(
                'ms_csms',
                array(
                    'id_vendor' => $id,
                    'score' => $skor,
                    'entry_stamp' => date('Y-m-d'),
                    'id_csms_limit' => $id_csms_limit
                )
            );
            $aff_id = $this->db->insert_id();
            foreach ($post as $key => $row) {
                $res = $this->db->insert(
                    'tr_evaluasi_poin',
                    array(
                        'id_evaluasi' => $key,
                        'poin' => $row,
                        'id_vendor' => $id,
                        'entry_stamp' => date('Y-m-d H:i:s'),
                        'id_csms' => $aff_id
                    )
                );
                if (!$res) {
                    return false;
                }
            }
        } else {
            $this->db->where('id_vendor', $id)->update(
                'ms_csms',
                array(
                    // 'id_vendor'=>$id,
                    'score' => $skor,
                    'edit_stamp' => date('Y-m-d H:i:s'),
                    'id_csms_limit' => $id_csms_limit
                )
            );
            $aff_id = $id_csms;
            foreach ($post as $key => $row) {
                $res = $this->db->where('id_csms', $id_csms)
                    ->where('id_evaluasi', $key)
                    ->where('id_vendor', '$id')
                    ->update(
                        'tr_evaluasi_poin',
                        array(
                            'poin' => $row,
                            'edit_stamp' => date('Y-m-d H:i:s')
                        )
                    );
                if (!$res) {
                    return false;
                }
            }
        }

        $this->db->insert('ms_score_k3', array(
            'score' => $skor,
            'data_status' => 0,
            'id_vendor' => $id,
            'id_csms_limit' => $id_csms_limit,
            'entry_stamp' => date('Y-m-d H:i:s')
        ));

        return $res;
    }

    public function get_k3_limit($poin)
    {
        $query = $this->db->get('tb_csms_limit_fm4')->result_array();

        foreach ($query as $key => $row) {
            if (($poin >= $row['end_score'] && $poin <= $row['start_score']) || ($row['start_score'] == NULL && $poin >= $row['end_score']) || ($row['end_score'] == NULL && $poin <= $row['start_score']))

                return $row['id'];
        }
        return false;
    }

    public function get_k3_vendor($search = '', $sort = '', $page = '', $per_page = '', $is_page = FALSE, $filter = array())
    {
        $this->db->query("SET sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");

        $this->db->select('ms_vendor.id as id ,ms_vendor.name name, msk.score score , msk.id csms_id')

            ->where('ms_vendor.is_active', 1)
            ->join('ms_vendor_admistrasi as mva', 'mva.id_vendor=ms_vendor.id', 'LEFT')
            ->join('ms_csms_fm4 as msk', 'msk.id_vendor=ms_vendor.id', 'LEFT')
            ->group_by('id');

        $a = $this->filter->generate_query($this->db->group_by('id'), $filter);


        if ($this->input->get('sort') && $this->input->get('by')) {
            $this->db->order_by($this->input->get('by'), $this->input->get('sort'));
        }
        if ($is_page) {
            $cur_page = ($this->input->get('per_page')) ? $this->input->get('per_page') : 1;
            $this->db->limit($per_page, $per_page * ($cur_page - 1));
        }

        $query = $a->get('ms_vendor');
        // echo $this->db->last_query();
        return $query->result_array();
    }

    public function get_poin($vendor_id)
    {
        return $this->db->select('*')->where(array('id_vendor' => $vendor_id))->get('ms_score_fm4')->row_array();
    }

    public function get_csms_limit()
    {
        $query =     $this->db->get('tb_csms_limit_fm4')->result_array();
        $data = array();
        foreach ($query as $key => $value) {
            $data[$value['id']] = $value['value'];
        }
        return $data;
    }

    public function get_history_nilai($id, $search = '', $sort = '', $page = '', $per_page = '', $is_page = FALSE, $filter = array())
    {
        $query =     $this->db->select('ms_score_fm4.*, ms_vendor.name name')
            ->join('ms_vendor', 'ms_vendor.id=ms_score_fm4.id_vendor')
            ->where('id_vendor', $id);
        if ($this->input->get('sort') && $this->input->get('by')) {
            $this->db->order_by($this->input->get('by'), $this->input->get('sort'));
        }
        if ($is_page) {
            $cur_page = ($this->input->get('per_page')) ? $this->input->get('per_page') : 1;
            $this->db->limit($per_page, $per_page * ($cur_page - 1));
        }
        $query = $this->db->get('ms_score_fm4')->result_array();
        return $query;
    }
}
