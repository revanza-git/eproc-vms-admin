<?php defined('BASEPATH') || exit('No direct script access allowed');

class Approval_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function administrasi()
	{

		$this->db->where('id', $id);

		return $this->db->update('ms_vendor_admistrasi', array('del' => 1));
	}
 
	public function get_dpt_type()
	{
		$query = $this->db->get('tb_dpt_type');
		$res   =  $query->result_array();
		$result = array();
		foreach ($res as $re) {
			$result[$re['id']] = $re['name'];
		}

		return $result;
	}
 
	public function get_total_data($id)
	{
		$table = array(
			'ms_akta' => 'Akta',
			'ms_situ' => 'SITU/Domisili',
			'ms_tdp' => 'TDP',
			'ms_pengurus' => 'Pengurus', '
						ms_pemilik' => 'Kepemilikan Saham',
			'ms_ijin_usaha' => 'Izin Usaha',
			'ms_agen' => 'Pabrikan/Keagenan/Distributor',
			'ms_pengalaman' => 'Pengalaman',
			'ms_agen_produk' => 'Produk',
			'ms_csms' => 'CSMS'
		);
		$result = array(0 => array(), 1 => array(), 2 => array(), 3 => array(), 4 => array());
		$total = 0;

		$adm = $this->db->select('data_status')->where('id_vendor', $id)->get('ms_vendor_admistrasi')->row_array();
		$result[$adm['data_status']][] = 'Data Administrasi Vendor';
		$total += 1;
		foreach (array_keys($table) as $field) {

			if ($field == 'ms_agen_produk') {
				$this->db->select('ms_agen_produk.data_status data_status');
			} elseif ($field == 'ms_akta') {
				$this->db->select('data_status, type');
			} else {
				$this->db->select('data_status');
			}

			if ($field == 'ms_csms') {
				$this->db->limit('0,1');
				$this->db->order_by('id DESC');
			}

			$this->db->where('id_vendor', $id);
			$this->db->where($field . '.del', 0);
			if ($field == 'ms_ijin_usaha') {
				$this->db->join('tb_dpt_type', 'tb_dpt_type.id = ms_ijin_usaha.id_dpt_type');
			}
   
			if ($field == 'ms_agen_produk') {
				$this->db->join('ms_agen', 'ms_agen.id=ms_agen_produk.id_agen');
			}

			$res = $this->db->get($field)->result_array();

			foreach ($res as $re) {
				if ($field != 'ms_akta') {
        $result[(($re['data_status'] == NULL) ? 0 : $re['data_status'])][] = $table[$field];
    } elseif ($re['type'] == 'pendirian') {
        $result[(($re['data_status'] == NULL) ? 0 : $re['data_status'])][] = 'Akta Pendirian';
    } else {
						$result[(($re['data_status'] == NULL) ? 0 : $re['data_status'])][] = 'Akta Perubahan';
					}

				$total += 1;
			}
		}
  
		//echo $this->db->last_query();
		$result['total'] = $total;
		return $result;
	}
 
	public function angkat_vendor($id)
	{
		$this->db->insert(
			'tr_certificate',
			array('id_vendor' => $id, 'certificate_no' => $_POST['certificate_no'], 'dpt_date' => date('Y-m-d H:i:s'), 'is_active' => 1, 'entry_stamp' => date('Y-m-d H:i:s'))
		);
		return $this->db->where('id', $id)->update('ms_vendor', array('need_approve' => 1, 'certificate_no' => $_POST['certificate_no']));
	}

	public function approve($id)
	{
		//echo "xxx";
		//print_r($_GET);
		//printr_r($this->input->post());die;
		$start_date__ = '';
		foreach ($_POST as $key) {
			$start_date__ .= $key;
		}

  $start_date = $start_date__ === 'Pilih' ? date('Y-m-d') : $start_date__;
  
		$update_status = $this->db->where('id', $id)->update('ms_vendor', array('vendor_status' => 2, 'need_approve' => 0));

		$date_dpt = $this->db->select('dpt_first_date')->where('id', $id)->get('ms_vendor')->row_array();
		// echo $this->db->last_query();
		if ($date_dpt['dpt_first_date'] == NULL || $date_dpt['dpt_first_date'] == '0000-00-00 00:00:00') {
			$this->db->where('id', $id)->update('ms_vendor', array('dpt_first_date' => $start_date));
		}

		$this->db->where('id_vendor', $id)->update('tr_assessment_point', array('point' => NULL, 'category' => NULL));

		$dpt_list = $this->db->select('*')->where('id_vendor', $id)->where('data_status', 1)->where('del', 0)->get('ms_ijin_usaha')->result_array();
		$tr_dpt_list = $this->db->where('id_vendor', $id)->get('tr_dpt')->result_array();
		if (count($tr_dpt_list) > 0) {
			foreach ($dpt_list as $row) {

				$this->db->where('id_vendor', $row['id_vendor']);
				$this->db->where('id_dpt_type', $row['id_dpt_type']);
				$update_status = $this->db->update('tr_dpt', array(
					'start_date' => $start_date,
					'status'	=> 1
				));
				if (!$update_status) {
        return false;
    }
			}
		} else {
			foreach ($dpt_list as $row) {
				$update_status = $this->db->insert('tr_dpt', array(
					'id_vendor'  => $row['id_vendor'],
					'id_dpt_type' => $row['id_dpt_type'],
					'start_date' => $start_date,
					'status'	=> 1
				));
				if (!$update_status) {
        return false;
    }
			}
		}

		//$this->clearTrDpt();
		return true;
	}



	public function set_expiry($id)
	{
		if ($_POST['start_date'] == null) {
			return $this->db->where('id_vendor', $id)
				->where('del', 0)
				->get('ms_csms')->row_array();
		}

  $array = array(
				'start_date'	=>	$_POST['start_date'],
				'expiry_date'	=>	date('Y-m-d H:i:s', strtotime(date('Y-m-d', strtotime($_POST['start_date'])) . '+2 years'))
			);
  // print_r($_POST);die;
  $this->db->where('id_vendor', $id)
				->where('del', 0)
				->update('ms_csms',	$array);
  return $array;
	}
 
	public function get_spv_mail($id)
	{
		$query = $this->db->select('id_role, name, email')
			->where('id_role', $id)
			->where('del', 0)
			->get('ms_admin');

		return $query->result_array();
	}
}
