<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Account_transactions_model extends CI_Model
{

    private $table = 'baby_account_info';
    private $transaction_table = 'transaction';
    public $balance = 'balance';
    public $id = 'Id';
    public $acc_name = 'accountName';
    public $acc_no = 'accountNo';

    function __construct()
    {
        parent::__construct();
    }


    public function get_single_account_transaction($account_no)
    {
        $query = $this->db->select('*')
            ->from($this->transaction_table)
            ->where($this->acc_no, $account_no)
            ->get();
        $num = $query->num_rows();
        //  var_dump($num);
        if ($num == 0) {
            return false;
        } else {
            $result = $query->result_array();
            return $result;
        }

    }

    // Get all account records from main table
    public function get_all_accounts()
    {
        $query = $this->db->select('*')
            ->from($this->table)
            ->get();
        $result = $query->result_array();

        return $result;
    }

    // Get all account number from main table
    public function get_account_id()
    {
        $query = $this->db->select('*')
            ->from($this->table)
            ->order_by($this->acc_name, 'ASC')
            ->get();
        $result = $query->result_array();
        return $result;
    }


    public function get_single_account($id)
    {
        $query = $this->db->select('*')
            ->from($this->table)
            ->where($this->id, $id)
            ->get();
        $result = $query->result_array();
        return $result;
    }

    public function get_last_transaction($account_no)
    {
        $query = $this->db->select($this->balance)
            ->from($this->transaction_table)
            ->where($this->acc_no, $account_no)
            ->order_by('Id', "desc")
            ->limit(1)
            ->get();
        $num = $query->num_rows();
        if ($num == 0) {
            return false;
        } else {
            $result = $query->result_array();
            return $result;
        }
    }

    public function get_old_balance($table_name)
    {

        $query = $this->db->select('Id')->order_by('Id', "desc")->limit(1)->get($table_name);
        $last_id = $query->result_array();
        //   var_dump($last_id[0]["Id"]);

        if ($last_id) {
            $Id = $last_id[0]["Id"];

            $query = $this->db->select('*')
                ->from($table_name)
                ->where('Id', $Id)
                ->get();
            $result = $query->result_array();
            return $result;
        } else {
            return false;
        }
    }

    public function create_new_transaction_process($insertData)
    {
        $result = $this->db->insert($this->transaction_table, $insertData);
        return $result;
    }

    //      var_dump($last_id);


    public function get_account_details_inf($account_no)
    {

        $query = $this->db->select('*')
            ->from($this->table)
            ->where($this->acc_no, $account_no)
            ->get();
        $result = $query->result_array();
        return $result;
    }

    public function get_table_last_records($id, $tableName)
    {
        $query = $this->db->select('*')
            ->from($tableName)
            ->where('Id', $id)
            ->get();
        $result = $query->result_array();
        return $result;
    }

    public function update_transaction_process($updateData, $updateId)
    {
        $result = $this->db->where('Id', $updateId)->update($this->transaction_table, $updateData);
        return $result;
    }

    public function get_transaction_reports($table_name, $start_date, $end_date)
    {


        if ($start_date != '' && $end_date != '') {

            $start_date = $start_date; //. ' 00:00:00';
            $end_date = $end_date; //. ' 23:59:59';


            $query = $this->db->select('*')
                ->from($table_name)
                ->where('entryDate >=', $start_date)
                ->where('entryDate <=', $end_date)
                ->order_by('Id', 'ASC')
                ->limit(30)
                ->get();

            //   var_dump($query);

            $result = $query->result_array();
            return $result;

        } else if ($start_date != '') {

            $today_date = date('Y-m-d');
            $query = $this->db->select('*')
                ->from($table_name)
                ->where('entryDate >=', $start_date)
                ->where('entryDate <=', $today_date)
                ->order_by('Id', 'ASC')
                ->limit(30)
                ->get();

            //   var_dump($query);

            $result = $query->result_array();
            return $result;
        } else if ($end_date != '') {

            $query = $this->db->select('*')
                ->from($table_name)
                ->where('entryDate <=', $end_date)
                ->order_by('Id', 'ASC')
                ->limit(30)
                ->get();

            //   var_dump($query);

            $result = $query->result_array();
            return $result;
        } else {

            $query = $this->db->select('*')
                ->from($table_name)
                ->order_by('Id', 'ASC')
                ->limit(30)
                ->get();
            $result = $query->result_array();
            return $result;
        }


    }

    public function get_all_transaction_single_table($table_name)
    {

        $query = $this->db->select('*')
            ->from($table_name)
            ->order_by('Id', 'ASC')
            ->get();
        $result = $query->result_array();
        return $result;
    }

    public function get_last_total_records($account_no, $id)
    {
        $query = $this->db->select('*')
            ->from($this->transaction_table)
            ->where($this->acc_no, $account_no)
            ->where('Id >', $id)
            ->order_by('Id', "asc")
            ->get();
        $result = $query->result_array();
        return $result;
    }

    public function get_last_balance($tableName, $id)
    {

        $query = $this->db->select('balance')
            ->from($tableName)
            ->where('Id', $id)
            ->get();
        $result = $query->result_array();
        return $result;

    }

    public function get_present_id_data($id)
    {
        $query = $this->db->select('*')
            ->from($this->transaction_table)
            ->where('Id', $id)
            ->get();
        $result = $query->result_array();
        return $result;
    }

    public function get_next_id_data($id, $tableName)
    {
        $query = $this->db->select('*')->where('Id >', $id)->order_by('Id', "asc")->limit(1)->get($tableName);
        $result = $query->result_array();
        return $result;
    }

    public function get_previous_id_data($id, $accounts_no)
    {
        $query = $this->db->select('balance')
            ->from($this->transaction_table)
            ->where($this->acc_no, $accounts_no)
            ->where('Id <', $id)
            ->order_by('Id', "desc")
            ->limit(1)
            ->get();
        $result = $query->result_array();
        return $result;
    }

    public function delete_accounts_transaction($id, $account_no)
    {
        $result = $this->db->delete($this->transaction_table, array('Id' => $id))->where($this->acc_no . $account_no);
        return $result;
    }

}
