<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Client_task extends Admin_Base_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('admin/m_client_task');
    }


    // Create new account
    function index()
    {
        $this->data['page_title'] = 'Manage Client Task';
        $this->data['breadcrumbs'] = 'Manage Client Task';
        $the_view = 'admin/pages/client_task/v_manage_client_task';
        $client_task = 'admin_master_view';
        parent::render($the_view, $client_task);
    }

    public function get_all_client_task()
    {
        if ($this->input->is_ajax_request()) {
            $this->data['all_client_task'] = $this->m_client_task->get_all_client_task();
            $view = $this->load->view('admin/pages/client_task/v_all_client_task', $this->data, TRUE);
            $this->output->set_output($view);
        } else {
            redirect('admin/dashboard');
        }
    }


    public function get_insert_client_task_form_view()
    {
        if ($this->input->is_ajax_request()) {
            $this->load->model('admin/account_transactions_model');
            $this->data['account_no'] = $this->account_transactions_model->get_account_id();
            $this->load->model('admin/m_client_status');
            $this->data['all_client_status'] = $this->m_client_status->get_all_client_status();
            $view = $this->load->view('admin/pages/client_task/v_add_client_task_form', $this->data, TRUE);
            $this->output->set_output($view);
        } else {
            redirect('admin/dashboard');
        }
    }

    public function get_update_client_task_form_view()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id');
            $this->load->model('admin/account_transactions_model');
            $this->data['account_no'] = $this->account_transactions_model->get_account_id();
            $this->load->model('admin/m_client_status');
            $this->data['all_client_status'] = $this->m_client_status->get_all_client_status();
            $this->data['single_client_task'] = $this->m_client_task->get_client_task_by_id($id);
            $view = $this->load->view('admin/pages/client_task/v_update_client_task_form', $this->data, TRUE);
            $this->output->set_output($view);
        } else {
            redirect('admin/dashboard');
        }
    }


    public function create_new_client_task_process()
    {

        header('Content-Type: application/json');
        if ($this->input->is_ajax_request()) {

            $account_no = $this->input->post('account_no');
            $task_status = $this->input->post('task_status');
            $task_details = $this->input->post('task_details');
            $created_date = date('Y-m-d');
            $modified_by = $_SESSION['admin_logged_in']['user_name'];


            //set validations
            $this->form_validation->set_rules('account_no', 'Account No', 'trim|required');

            if ($this->form_validation->run() == FALSE) {
                $errors = array();
                foreach ($this->input->post() as $key => $value) {
                    $errors[$key] = form_error($key);
                }
                $response_array['errors'] = array_filter($errors);

                $response_array['type'] = 'danger';
                $response_array['message'] = '<div class="alert alert-danger alert-dismissable"><i class="fa fa-warning"></i> <strong class="alert  alert-dismissable"> Sorry!  Validation errors occurs. </strong></div>';
                echo json_encode($response_array);

            } else {

                $insertData = array(
                    'accountNo' => trim($account_no),
                    'task_status' => trim($task_status),
                    'task_desc' => trim($task_details),
                    'cre_or_up_date' => trim($created_date),
                    'cre_or_up_by' => trim($modified_by)
                );

                $insertData = $this->security->xss_clean($insertData);

                $results = $this->m_client_task->create_new_client_task_process($insertData);

                if ($results) {

                    $response_array['type'] = 'success';
                    $response_array['message'] = '<div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><strong>  Congratulations! </strong> Client Task Created  Successfully. </div>';
                    echo json_encode($response_array);

                } else {
                    $response_array['type'] = 'danger';
                    $response_array['message'] = '<div class="alert alert-danger alert-dismissable"><i class="fa fa-times"></i><strong> Sorry! </strong>  Failed.</div>';
                    echo json_encode($response_array);
                }
            }
        } else {
            redirect('admin/dashboard');
        }
    }


    public function client_task_name_check($str)
    {
        if (preg_match('/^[a-zA-Z-&0-9_ ]+$/', $str)) {
            return TRUE;
        } else {
            $this->form_validation->set_message('client_task_name_check', 'Allowed Characters are a-zA-Z-&0-9_ ');
            return FALSE;

        }

    }


    public function update_client_task_process()
    {

        header('Content-Type: application/json');
        if ($this->input->is_ajax_request()) {

            $updateId = $this->input->post('updateId');
            $account_no = $this->input->post('account_no');
            $task_status = $this->input->post('task_status');
            $task_details = $this->input->post('task_details');
            $created_date = date('Y-m-d');
            $modified_by = $_SESSION['admin_logged_in']['user_name'];
            $is_available = 1;


            //set validations
            $this->form_validation->set_rules('account_no', 'Account No', 'trim|required');

            if ($this->form_validation->run() == FALSE) {
                $errors = array();
                foreach ($this->input->post() as $key => $value) {
                    $errors[$key] = form_error($key);
                }
                $response_array['errors'] = array_filter($errors);

                $response_array['type'] = 'danger';
                $response_array['message'] = '<div class="alert alert-danger alert-dismissable"><i class="fa fa-warning"></i> <strong class="alert  alert-dismissable"> Sorry!  Validation errors occurs. </strong></div>';
                echo json_encode($response_array);

            } else {

                $updateData = array(
                    'accountNo' => trim($account_no),
                    'task_status' => trim($task_status),
                    'task_desc' => trim($task_details),
                    'cre_or_up_date' => trim($created_date),
                    'cre_or_up_by' => trim($modified_by)
                );

                $updateData = $this->security->xss_clean($updateData);

                $results = $this->m_client_task->update_client_task_process($updateData, $updateId);

                if ($results) {

                    $response_array['type'] = 'success';
                    $response_array['message'] = '<div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><strong>  Congratulations! </strong> Client Task Created  Successfully. </div>';
                    echo json_encode($response_array);

                } else {
                    $response_array['type'] = 'danger';
                    $response_array['message'] = '<div class="alert alert-danger alert-dismissable"><i class="fa fa-times"></i><strong> Sorry! </strong>  Failed.</div>';
                    echo json_encode($response_array);
                }
            }
        } else {
            redirect('admin/dashboard');
        }
    }

    public function delete_client_task()
    {
        header('Content-Type: application/json');

        if ($this->input->is_ajax_request()) {

            $id = $this->input->post('id');
            $result = $this->m_client_task->delete_client_task($id);

            if ($result) {
                $response_array['type'] = 'success';
                $response_array['message'] = '<div class="alert alert-success alert-dismissable"><i class="icon fa fa-check"></i><strong> Congratulations! </strong>  Successfully Deleted. </div>';
                echo json_encode($response_array);
            } else {
                $response_array['type'] = 'danger';
                $response_array['message'] = '<div class="alert alert-danger alert-dismissable"><i class="icon fa fa-times"></i><strong> Sorry! </strong>  Failed.</div>';
                echo json_encode($response_array);
            }
        } else {
            redirect('admin/dashboard');
        }
    }
}

?>