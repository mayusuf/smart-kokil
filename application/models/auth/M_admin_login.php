<?php

class M_admin_login extends CI_Model {

    public $table = 'users';
    public $user_name = 'user_name';
    public $email = 'user_email';
    public $pass = 'pass_phrase';

    function __construct() {
        parent::__construct();
    }

    function get_admin($user_email, $pass) {

        $query =  $this->db->select('*')
            ->from($this->table)
            ->where($this->email, $user_email)
            ->where($this->pass,  md5($pass))
            ->get();
        $result = $query->result_array();
        return $result;

    }
    function get_admin_profile()
    {

        $query = $this->db->select('*')
            ->from($this->table)
            ->get();
        $result = $query->result_array();
        return $result;

    }

}
