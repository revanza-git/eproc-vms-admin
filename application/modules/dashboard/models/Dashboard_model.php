<?php
class Dashboard_model extends CI_Model{
	 
	public function __construct(){
		parent::__construct();
	}
	
	public function get_auction(){
		$sql = "SELECT a.* 
						
				FROM ms_procurement a 
				
				LEFT JOIN ms_procurement_peserta b ON a.id = b.id_proc 
				LEFT JOIN ms_vendor c ON b.id_vendor = c.id
				
				WHERE c.id = ? AND a.auction_date <= ? AND a.auction_date >= ? GROUP BY a.id ";
		// echo $this->db->last_query();
		// var_dump($res);
		return $this->db->query($sql, array($this->session->userdata('user')['id_user'],date("Y-m-d"),date("Y-m-d")));
	}

	public function get_pernyataan(){
		$query = $this->db 	->select('*')
							->get('tb_pernyataan');

		return $query->result_array();
	}
	
}