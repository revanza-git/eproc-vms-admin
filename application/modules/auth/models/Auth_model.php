<?php defined('BASEPATH') or exit('No direct script access allowed');

class Auth_model extends CI_Model
{
    public function get_user($id_user, $type)
    {
        if ($type == 'user') {
            $vendor = $this->db->where(array('del' => 0, 'id' => $id_user, 'is_active' => 1))->get('ms_vendor')->row_array();
            $set_session = array(
                'id_user'       =>  $vendor['id'],
                'name'          =>  $vendor['name'],
                'id_sbu'        =>  $vendor['id_sbu'],
                'vendor_status' =>  $vendor['vendor_status'],
                'is_active'     =>  $vendor['is_active'],
                'app'           =>  'vms'
            );

            $this->session->set_userdata('user', $set_session);
            return true;
        } else {
            $ct_sql = "SELECT *,ms_admin.id id, ms_admin.name name, tb_role.name role_name FROM ms_admin JOIN tb_role ON ms_admin.id_role = tb_role.id WHERE ms_admin.id=? AND ms_admin.del=?";
            $ct_sql = $this->db->query($ct_sql, array($id_user, 0));

            $data = $ct_sql->row_array();
            $set_session = array(
                'id_user'   => $data['id'],
                'name'      => $data['name'],
                'id_sbu'    => $data['id_sbu'],
                'id_role'   => $data['id_role'],
                'role_name' => $data['role_name'],
                'sbu_name'  => $data['sbu_name'],
                'app'       => 'vms'

            );

            $this->session->set_userdata('admin', $set_session);
            return true;
        }
    }
}
