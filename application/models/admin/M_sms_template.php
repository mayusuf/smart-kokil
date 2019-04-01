<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_sms_template extends CI_Model
{

    public $table = 'sms_template';
    public $temp_name = 'temp_name';
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

    public function get_all_template()
    {
        $query = $this->db->select('*')
            ->from($this->table)
            ->order_by($this->id, 'DESC')
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