<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_login extends MY_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('auth/m_admin_login');
    }

    public function login()
    {

        $this->data['title'] = 'Admin Account Login';

        if ($this->session->userdata('admin_logged_in')) {
            redirect('admin/admin_dashboard', 'refresh');
        }

        //get the posted values
        $username = $this->input->post("username");
        $password = $this->input->post("password");

        //set validations
        $this->form_validation->set_rules("username", "Email", "trim|required");
        $this->form_validation->set_rules("password", "Password", "trim|required");

        if ($this->form_validation->run() == FALSE) {
            //validation fails
            //  $this->load->view('admin/auth/admin_login_view');
            $this->load->view('admin/auth/admin_login_view');
        } else {
            //validation succeeds
            if ($this->input->post('submit') == "login") {
                //check if username and password is correct
                $usr_result = $this->m_admin_login->get_admin($username, $password);

                foreach ($usr_result as $key => $value) {

                    $username = $value['user_name'];
                    $user_id = $value['user_id'];
                    $user_email = $value['user_email'];
                    $role_id = $value['role_id'];
                }

                $is_admin = count($usr_result);

                if ($is_admin > 0) { //active user record is present
                    $session_array = array(
                        'user_name' => $username,
                        'user_id' => $user_id,
                        'user_email' => $user_email,
                        'role_id' => $role_id,
                        'logged_in' => TRUE
                    );
                    $this->session->set_userdata('admin_logged_in', $session_array);

                    redirect('admin/admin_dashboard', 'refresh');
                } else {

                    $this->session->set_flashdata('msg', '<div class="alert alert-danger text-center"> Invalid username or password! </div>');
                    $this->load->view('admin/auth/admin_login_view');
                }
            } else {
                $this->load->view('admin/auth/admin_login_view');
            }
        }
    }

    public function logout()
    {

        $this->session->unset_userdata('admin_logged_in');
        session_destroy();
        session_unset();
        redirect('', 'refresh');
    }

}
