<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_tax extends CI_Model
{

    public $table = 'type';
    public $tax_name = 'tax_name';
    public $id = 'id';

    function __construct()
    {
        parent::__construct();
    }

    public function create_new_tax_process($insertData)
    {
        $result = $this->db->insert($this->table, $insertData);
        return $result;
    }

    public function get_all_tax()
    {
        $query = $this->db->select('*')
            ->from($this->table)
            ->order_by($this->id, 'DESC')
            ->get();
        $result = $query->result_array();
        return $result;
    }

    public function get_tax_by_id($id)
    {
        $query = $this->db->select('*')
            ->from($this->table)
            ->where($this->id, $id)
            ->get();
        $result = $query->result_array();
        return $result;
    }

    public function check_tax_exist($tax_name, $updateId)
    {
        $query = $this->db->select('*')
            ->from($this->table)
            ->where($this->tax_name, $tax_name)
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


    public function update_tax_process($updateData, $updateId)
    {
        $result = $this->db->where($this->id, $updateId)->update($this->table, $updateData);
        return $result;
    }


    public function delete_tax($id)
    {
        $result = $this->db->delete($this->table, array($this->id => $id));
        return $result;
    }

}