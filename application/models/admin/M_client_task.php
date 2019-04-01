<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_client_task extends CI_Model
{

    public $table = 'client_tasks';
    public $accountNo = 'accountNo';
    public $task_status = 'task_status';
    public $id = 'id';

    function __construct()
    {
        parent::__construct();
    }

    public function create_new_client_task_process($insertData)
    {
        $result = $this->db->insert($this->table, $insertData);
        return $result;
    }


    public function get_all_client_task2()
    {
        $query = $this->db->select('*')
            ->from($this->table)
            ->order_by($this->id, 'DESC')
            ->get();
        $result = $query->result_array();
        return $result;
    }

    public function get_all_client_task()
    {
        $this->db->select('t.*,a.accountName , s.client_status')
            ->from('client_tasks as t')
            ->join('baby_account_info as a', 'a.accountNo = t.accountNo', 'left')
            ->join('task_status as s', 's.id = t.task_status', 'left')
            ->order_by('t.id', 'DESC');
        $query = $this->db->get();
        if ($query->num_rows() != 0) {
            return $query->result_array();
        } else {
            return false;
        }
    }

    public function get_client_task_by_id($id)
    {
        $query = $this->db->select('*')
            ->from($this->table)
            ->where($this->id, $id)
            ->get();
        $result = $query->result_array();
        return $result;
    }

    public function check_client_task_exist($client_task_name, $updateId)
    {
        $query = $this->db->select('*')
            ->from($this->table)
            ->where($this->client_task, $client_task_name)
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


    public function update_client_task_process($updateData, $updateId)
    {
        $result = $this->db->where($this->id, $updateId)->update($this->table, $updateData);
        return $result;
    }


    public function delete_client_task($id)
    {
        $result = $this->db->delete($this->table, array($this->id => $id));
        return $result;
    }

}