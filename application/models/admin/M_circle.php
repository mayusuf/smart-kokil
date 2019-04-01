<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_circle extends CI_Model
{

    public $table = 'circle';
    public $circle_name = 'circle_name';
    public $id = 'id';
    public $tax_id = 'tax_id';

    function __construct()
    {
        parent::__construct();
    }

    public function create_new_circle_process($insertData)
    {
        $result = $this->db->insert($this->table, $insertData);
        return $result;
    }

    public function get_all_circle2()
    {
        $this->db->select('c.*,t.tax_name')
            ->from('circle as c')
            ->join('tax as t', 't.id = c.tax_id', 'left')
            ->order_by('c.id', 'DESC');
        $query = $this->db->get();
        if ($query->num_rows() != 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }


    public function get_all_circle()
    {
        $query = $this->db->select('*')
            ->from($this->table)
            ->order_by($this->id, 'DESC')
            ->get();
        $result = $query->result_array();
        return $result;
    }

    public function get_circle_by_id($id)
    {
        $query = $this->db->select('*')
            ->from($this->table)
            ->where($this->id, $id)
            ->get();
        $result = $query->result_array();
        return $result;
    }

    public function check_circle_exist($circle_name, $updateId)
    {
        $query = $this->db->select('*')
            ->from($this->table)
            ->where($this->circle_name, $circle_name)
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


    public function update_circle_process($updateData, $updateId)
    {
        $result = $this->db->where($this->id, $updateId)->update($this->table, $updateData);
        return $result;
    }


    public function delete_circle($id)
    {
        $result = $this->db->delete($this->table, array($this->id => $id));
        return $result;
    }

}