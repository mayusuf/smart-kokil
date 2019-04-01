<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends Admin_Base_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('admin/m_user');
    }

    public function index()
    {
        $this->data['title'] = 'Manage users';
        $this->data['breadcrumbs'] = 'Manage users';
        $the_view = 'admin/pages/user/v_manage_user';
        $template = 'admin_master_view';
        parent::render($the_view, $template);
    }

    public function get_all_user()
    {
        if ($this->input->is_ajax_request()) {
            $this->data['all_user'] = $this->m_user->select_all_user();
            $all_user_view = $this->load->view('admin/pages/user/v_all_user', $this->data, TRUE);
            $this->output->set_output($all_user_view);
        } else {
            redirect('admin/dashboard');
        }
    }


    public function get_insert_user_form_view()
    {


        if ($this->input->is_ajax_request()) {
            $insert_user_form = $this->load->view('admin/pages/user/v_add_user_form', $this->data, TRUE);
            $this->output->set_output($insert_user_form);
        } else {
            redirect('admin/dashboard');
        }
    }

    public function get_update_user_form_view()
    {


        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id');

            $this->data['single_users'] = $this->m_user->get_user_by_id($id);
            $update_user_form = $this->load->view('admin/pages/user/v_update_user_form', $this->data, TRUE);
            $this->output->set_output($update_user_form);
        } else {
            redirect('admin/dashboard');
        }
    }


    public function create_new_user_process()
    {

        header('Content-Type: application/json');

        if ($this->input->is_ajax_request()) {

            if ($this->input->post('submit') == "Save") {

                $users_name = $this->input->post('user_name');
                $users_email = $this->input->post('user_email');
                $users_password = $this->input->post('user_password');
                $created_date = date('Y-m-d');
                $modified_by = $_SESSION['admin_logged_in']['user_name'];
                $is_available = 1;

                //set validations
                $this->form_validation->set_rules('user_name', 'users Name', 'trim|required|callback_users_name_check');
                $this->form_validation->set_rules('user_password', 'Password');
                $this->form_validation->set_rules('user_email', 'User Email',
                    'trim|required|callback_email_address_check|is_unique[users.user_email]',
                    array(
                        'is_unique' => 'This User Email already exists.'
                    )
                );

                if ($this->form_validation->run() == FALSE) {
                    $errors = array();
                    foreach ($this->input->post() as $key => $value) {
                        $errors[$key] = form_error($key);
                    }
                    $response_array['errors'] = array_filter($errors);

                    $response_array['type'] = 'danger';
                    $response_array['message'] = '<div class="alert alert-danger alert-dismissable"><i class="icon fa fa-times"></i> <strong class="alert  alert-dismissable"> Sorry!  Validation errors occurs. </strong></div>';
                    echo json_encode($response_array);

                } else {

                    $insertData = array(
                        'user_name' => trim($users_name),
                        'user_email' => trim($users_email),
                        'pass_phrase' => trim(md5($users_password)),
                        'cre_or_up_date' => trim($created_date),
                        'cre_or_up_by' => trim($modified_by),
                        'is_available' => trim($is_available)
                    );

                    $insertData = $this->security->xss_clean($insertData);

                    $results = $this->m_user->create_new_user_process($insertData);

                    if ($results) {

                        $response_array['type'] = 'success';
                        $response_array['message'] = '<div class="alert alert-success alert-dismissable"><i class="icon fa fa-check"></i><strong>  Congratulations! </strong> users Created  Successfully. </div>';
                        echo json_encode($response_array);

                    } else {
                        $response_array['type'] = 'danger';
                        $response_array['message'] = '<div class="alert alert-danger alert-dismissable"><i class="icon fa fa-times"></i><strong> Sorry! </strong>  Failed.</div>';
                        echo json_encode($response_array);
                    }
                }
            } else {
                redirect('admin/dashboard');
            }
        } else {
            redirect('admin/dashboard');
        }
    }

    public function users_name_check($str)
    {
        if (preg_match('/^[a-zA-Z ]+$/', $str)) {
            return TRUE;
        } else {
            $this->form_validation->set_message('users_name_check', 'The users name contains only characters and underscore.');
            return FALSE;

        }

    }

    public function email_address_check($str)
    {
        if (!filter_var($str, FILTER_VALIDATE_EMAIL)) {
            $this->form_validation->set_message('email_address_check', 'Please enter a valid email address');
            return FALSE;
        } else {

            return TRUE;
        }

    }


    public function update_user_process()
    {

        header('Content-Type: application/json');

        if ($this->input->is_ajax_request()) {

            if ($this->input->post('submit') == "Save") {

                $updateId = $this->input->post('updateId');
                $users_name = $this->input->post('user_name');
                $users_password = $this->input->post('user_password');
                $created_date = date('Y-m-d');
                $modified_by = $_SESSION['admin_logged_in']['user_name'];
                $is_available = 1;

                //set validations
                $this->form_validation->set_rules('user_name', 'users Name', 'trim|required|callback_users_name_check');
                $this->form_validation->set_rules('user_password', 'Password');

                if ($this->form_validation->run() == FALSE) {
                    $errors = array();
                    foreach ($this->input->post() as $key => $value) {
                        $errors[$key] = form_error($key);
                    }
                    $response_array['errors'] = array_filter($errors);

                    $response_array['type'] = 'danger';
                    $response_array['message'] = '<div class="alert alert-danger alert-dismissable"><i class="icon fa fa-times"></i> <strong class="alert  alert-dismissable"> Sorry!  Validation errors occurs. </strong></div>';
                    echo json_encode($response_array);

                } else {

                    $updateData = array(
                        'user_name' => trim($users_name),
                        'pass_phrase' => trim(md5($users_password)),
                        'cre_or_up_date' => trim($created_date),
                        'cre_or_up_by' => trim($modified_by),
                        'is_available' => trim($is_available)
                    );

                    $updateData = $this->security->xss_clean($updateData);

                    $results = $this->m_user->update_user_process($updateData, $updateId);

                    if ($results) {

                        $response_array['type'] = 'success';
                        $response_array['message'] = '<div class="alert alert-success alert-dismissable"><i class="icon fa fa-check"></i><strong>  Congratulations! </strong> Updated  Successfully. </div>';
                        echo json_encode($response_array);

                    } else {
                        $response_array['type'] = 'danger';
                        $response_array['message'] = '<div class="alert alert-danger alert-dismissable"><i class="icon fa fa-times"></i><strong> Sorry! </strong>  Failed.</div>';
                        echo json_encode($response_array);
                    }
                }
            } else {
                redirect('admin/dashboard');
            }
        } else {
            redirect('admin/dashboard');
        }
    }


    public function assign_role_form_view()
    {


        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id');

            $this->data['single_users'] = $this->m_user->get_user_by_id($id);
            $this->load->model('m_roll');
            $this->data['all_roll'] = $this->m_roll->select_all_roll();
            $update_user_form = $this->load->view('admin/pages/user/v_assign_user_role_form', $this->data, TRUE);
            $this->output->set_output($update_user_form);
        } else {
            redirect('admin/dashboard');
        }
    }


    public function assign_user_role_process()
    {

        header('Content-Type: application/json');

        if ($this->input->is_ajax_request()) {

            if ($this->input->post('submit') == "Save") {

                $updateId = $this->input->post('updateId');
                $user_name = $this->input->post('user_name');
                $roll_id = $this->input->post('roll_name');
                $created_date = date('Y-m-d');
                $modified_by = $_SESSION['admin_logged_in']['user_name'];
                $is_available = 1;

                //set validations
                $this->form_validation->set_rules('user_name', 'User Name', 'trim|required');

                if ($this->form_validation->run() == FALSE) {
                    $errors = array();
                    foreach ($this->input->post() as $key => $value) {
                        $errors[$key] = form_error($key);
                    }
                    $response_array['errors'] = array_filter($errors);

                    $response_array['type'] = 'danger';
                    $response_array['message'] = '<i class="icon fa fa-times"></i> <strong class="alert  alert-dismissable"> Sorry!  Validation errors occurs. </strong>';
                    echo json_encode($response_array);

                } else {

                    $updateData = array(
                        'role_id' => trim($roll_id),
                        'cre_or_up_date' => trim($created_date),
                        'cre_or_up_by' => trim($modified_by),
                        'is_available' => trim($is_available)
                    );

                    $updateData = $this->security->xss_clean($updateData);

                    $results = $this->m_user->update_user_process($updateData, $updateId);

                    if ($results) {

                        $response_array['type'] = 'success';
                        $response_array['message'] = '<div class="alert alert-success alert-dismissable"><i class="icon fa fa-check"></i><strong>  Congratulations! </strong> Updated  Successfully. </div>';
                        echo json_encode($response_array);

                    } else {
                        $response_array['type'] = 'danger';
                        $response_array['message'] = '<div class="alert alert-danger alert-dismissable"><i class="icon fa fa-times"></i><strong> Sorry! </strong>  Failed.</div>';
                        echo json_encode($response_array);
                    }
                }
            } else {
                redirect('admin/dashboard');
            }
        } else {
            redirect('admin/dashboard');
        }
    }

    public function delete_user()
    {
        header('Content-Type: application/json');

        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id');

            $result = $this->m_user->delete_user($id);

            if ($result) {
                $response_array['type'] = 'success';
                $response_array['message'] = '<div class="alert alert-success alert-dismissable"><i class="icon fa fa-check"></i><strong> Congratulations! </strong>  Successfully Deleted. </div>';
                echo json_encode($response_array);
            } else {
                $response_array['type'] = 'danger';
                $response_array['message'] = '<div class="alert alert-danger alert-dismissable"><i class="icon fa fa-times"></i><strong> Sorry! </strong>  Failed.</div>';
                echo json_encode($response_array);
            }
        }
    }

}
