<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Account_settings_model extends CI_Model
{

    private $table = 'baby_account_info';
    public $id = 'Id';
    public $acc_name = 'accountName';
    public $acc_no = 'accountNo';
    public $account_type = 'account_type';
    public $contact = 'contactNo';
    public $circle_tax = 'circle_tax';

    function __construct()
    {
        parent::__construct();
    }


    // Create new account process
    public function create_new_account_process($insertData)
    {
        $result = $this->db->insert($this->table, $insertData);
        return $result;
    }

    // Check account number either exists
    public function check_contact_exist($contact_no, $updateId)
    {
        $query = $this->db->select('*')
            ->from($this->table)
            ->where($this->contact, $contact_no)
            ->where('Id !=', $updateId)
            ->get();
        $num = $query->num_rows();
        if ($num > 0) {
            return true;
        } else {
            return false;
        }
    }


    // Creating account id table
    public function baby_id_account_table($baby_accounts_info_table)
    {

        $this->load->dbforge();

        $fields = array(
            'Id' => array(
                'type' => 'INT',
                'constraint' => '5',
                'unsigned' => TRUE,
                'auto_increment' => TRUE
            ),
            'entryDate' => array(
                'type' => 'date',
            ),
            'deposit' => array(
                'type' => 'double',
                'unsigned' => TRUE,
                'default' => '0',
            ),
            'withDraw' => array(
                'type' => 'double',
                'unsigned' => TRUE,
                'default' => '0',
            ),
            'discount' => array(
                'type' => 'double',
                'unsigned' => TRUE,
                'default' => '0',
            ),
            'balance' => array(
                'type' => 'varchar',
                'constraint' => '20',
            ),
            'comments' => array(
                'type' => 'varchar',
                'constraint' => '500',
            ));

        $this->dbforge->add_field($fields);
        $this->dbforge->add_key('Id', TRUE);
        $result = $this->dbforge->create_table($baby_accounts_info_table);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    // Get all accounts information
    public function get_all_accounts()
    {
        $query = $this->db->select('*')
            ->from($this->table)
            ->order_by($this->id, 'DESC')
            ->get();
        $result = $query->result_array();
        return $result;
    }

    public function get_account_by_type_circle($account_type, $circle)
    {
        if ($account_type != '' && $circle != '') {
            $query = $this->db->select('*')
                ->from($this->table)
                ->where($this->account_type, $account_type)
                ->where($this->circle_tax, $circle)
                ->order_by($this->id, 'DESC')
                ->get();
            $result = $query->result_array();
            return $result;

        } else if ($account_type != '') {
            $query = $this->db->select('*')
                ->from($this->table)
                ->where($this->account_type, $account_type)
                ->order_by($this->id, 'DESC')
                ->get();
            $result = $query->result_array();
            return $result;
        } else if ($circle != '') {
            $query = $this->db->select('*')
                ->from($this->table)
                ->where($this->circle_tax, $circle)
                ->order_by($this->id, 'DESC')
                ->get();
            $result = $query->result_array();
            return $result;
        } else {
            $query = $this->db->select('*')
                ->from($this->table)
                ->order_by($this->id, 'DESC')
                ->get();
            $result = $query->result_array();
            return $result;
        }
    }

    // Get all account number from main table
    public function get_account_id()
    {
        $query = $this->db->select('*')
            ->from($this->table)
            ->get();
        $result = $query->result_array();
        return $result;
    }

    // Update account settings process
    public function update_accounts_settings_process($updateData, $id)
    {
        $result = $this->db->where($this->id, $id)->update($this->table, $updateData);
        return $result;
    }

    // Get single records from a table
    public function get_single_account($id)
    {
        $query = $this->db->select('*')
            ->from($this->table)
            ->where($this->id, $id)
            ->get();
        $result = $query->result_array();
        return $result;
    }

    public function get_account_by_accountNo($accountNo)
    {
        $query = $this->db->select('*')
            ->from($this->table)
            ->where($this->acc_no, $accountNo)
            ->get();
        $result = $query->result_array();
        return $result;
    }

}