<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_client_status extends CI_Model
{

    public $table = 'task_status';
    public $client_status = 'client_status';
    public $id = 'id';

    function __construct()
    {
        parent::__construct();
    }

    public function create_new_client_status_process($insertData)
    {
        $result = $this->db->insert($this->table, $insertData);
        return $result;
    }


    public function get_all_client_status()
    {
        $query = $this->db->select('*')
            ->from($this->table)
            ->order_by($this->id, 'DESC')
            ->get();
        $result = $query->result_array();
        return $result;
    }

    public function get_client_status_by_id($id)
    {
        $query = $this->db->select('*')
            ->from($this->table)
            ->where($this->id, $id)
            ->get();
        $result = $query->result_array();
        return $result;
    }

    public function check_client_status_exist($client_status_name, $updateId)
    {
        $query = $this->db->select('*')
            ->from($this->table)
            ->where($this->client_status, $client_status_name)
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


    public function update_client_status_process($updateData, $updateId)
    {
        $result = $this->db->where($this->id, $updateId)->update($this->table, $updateData);
        return $result;
    }


    public function delete_client_status($id)
    {
        $result = $this->db->delete($this->table, array($this->id => $id));
        return $result;
    }

}