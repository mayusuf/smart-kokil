<?php

class M_user extends CI_Model
{

    public $table = 'users';
    public $user_name = 'user_name';
    public $user_email = 'user_email';
    public $user_id = 'user_id';
    public $role_id = 'role_id';

    function __construct()
    {
        parent::__construct();
    }

    public function create_new_user_process($insertData)
    {
        $result = $this->db->insert($this->table, $insertData);
        return $result;
    }

    public function select_all_user()
    {
        $this->db->select('u.*,r.roll_name')
            ->from('users as u')
            ->join('roll as r', 'r.roll_id = u.role_id', 'left')
            ->order_by('u.user_id', 'DESC');
        $query = $this->db->get();
        if ($query->num_rows() != 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function get_user_by_id($id)
    {
        $query = $this->db->select('*')
            ->from($this->table)
            ->where($this->user_id, $id)
            ->get();
        $result = $query->result_array();
        return $result;
    }

    public function check_mobile_exist($user_contact_no, $updateId)
    {
        $query = $this->db->select('*')
            ->from($this->table)
            ->where('main_contact_no', $user_contact_no)
            ->where('user_id !=', $updateId)
            ->get();
        //  var_dump($query);
        $num = $query->num_rows();
        //  var_dump($num);
        if ($num == 0) {
            return true;
        } else {
            return false;
        }
    }

    public function update_user_process($updateData, $updateId)
    {
        $result = $this->db->where($this->user_id, $updateId)->update($this->table, $updateData);
        return $result;
    }

    public function delete_user($id)
    {
        $result = $this->db->delete($this->table, array($this->user_id => $id));
        return $result;
    }

} 