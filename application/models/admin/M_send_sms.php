<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_send_sms extends CI_Model
{

    public $table = 'sms_template';
    public $temp_name = 'temp_name';
    public $temp_type = 'temp_type';
    public $id = 'id';

    function __construct()
    {
        parent::__construct();
    }

    public function create_new_template_process($insertData)
    {
        $result = $this->db->insert($this->table, $insertData);
        return $result;
    }

    public function get_single_template()
    {
        $query = $this->db->select('*')
            ->from($this->table)
            ->where($this->temp_type, 'Single')
            ->order_by($this->id, 'DESC')
            ->get();
        $result = $query->result_array();
        return $result;
    }

    public function get_group_template()
    {
        $query = $this->db->select('*')
            ->from($this->table)
            ->where($this->temp_type, 'Group')
            ->order_by($this->id, 'DESC')
            ->get();
        $result = $query->result_array();
        return $result;
    }

    public function get_all_template()
    {
        $query = $this->db->select('*')
            ->from($this->table)
            ->order_by($this->id, 'DESC')
            ->get();
        $result = $query->result_array();
        return $result;
    }

    public function get_all_account_type()
    {
        $query = $this->db->select('*')
            ->from('baby_account_info')
            ->order_by('Id', 'DESC')
            ->group_by('account_type', 'ASC')
            ->get();
        $result = $query->result_array();
        return $result;
    }


    public function get_all_account()
    {
        $query = $this->db->select('*')
            ->from('baby_account_info')
            ->order_by('accountName', 'ASC')
            ->get();
        $result = $query->result_array();
        return $result;
    }


    public function get_template_by_id($id)
    {
        $query = $this->db->select('*')
            ->from($this->table)
            ->where($this->id, $id)
            ->get();
        $result = $query->result_array();
        return $result;
    }

    public function check_template_exist($template_name, $updateId)
    {
        $query = $this->db->select('*')
            ->from($this->table)
            ->where($this->temp_name, $template_name)
            ->where('id !=', $updateId)
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


    public function update_template_process($updateData, $updateId)
    {
        $result = $this->db->where($this->id, $updateId)->update($this->table, $updateData);
        return $result;
    }


    public function delete_template($id)
    {
        $result = $this->db->delete($this->table, array($this->id => $id));
        return $result;
    }

}