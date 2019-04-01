<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Client_status extends Admin_Base_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('admin/m_client_status');
    }


    // Create new account
    function index()
    {
        $this->data['page_title'] = 'Manage Client Status';
        $this->data['breadcrumbs'] = 'Manage Client Status';
        $the_view = 'admin/pages/client_status/v_manage_client_status';
        $client_status = 'admin_master_view';
        parent::render($the_view, $client_status);
    }

    public function get_all_client_status()
    {
        if ($this->input->is_ajax_request()) {
            $this->data['all_client_status'] = $this->m_client_status->get_all_client_status();
            $view = $this->load->view('admin/pages/client_status/v_all_client_status', $this->data, TRUE);
            $this->output->set_output($view);
        } else {
            redirect('admin/dashboard');
        }
    }


    public function get_insert_client_status_form_view()
    {
        if ($this->input->is_ajax_request()) {
            $view = $this->load->view('admin/pages/client_status/v_add_client_status_form', $this->data, TRUE);
            $this->output->set_output($view);
        } else {
            redirect('admin/dashboard');
        }
    }

    public function get_update_client_status_form_view()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id');
            $this->data['single_client_status'] = $this->m_client_status->get_client_status_by_id($id);
            $view = $this->load->view('admin/pages/client_status/v_update_client_status_form', $this->data, TRUE);
            $this->output->set_output($view);
        } else {
            redirect('admin/dashboard');
        }
    }


    public function create_new_client_status_process()
    {

        header('Content-Type: application/json');
        if ($this->input->is_ajax_request()) {

            $client_status_name = $this->input->post('client_status_name');
            $created_date = date('Y-m-d');
            $modified_by = $_SESSION['admin_logged_in']['user_name'];
            $is_available = 1;


            //set validations
            $this->form_validation->set_rules(
                'client_status_name', 'Client Status Name',
                'trim|required|callback_client_status_name_check|is_unique[task_status.client_status]',
                array(
                    'is_unique' => 'This Client Status Name is already exists.'
                )
            );

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
                    'client_status' => trim($client_status_name),
                    'cre_or_up_date' => trim($created_date),
                    'cre_or_up_by' => trim($modified_by),
                    'status' => trim($is_available)
                );

                $insertData = $this->security->xss_clean($insertData);

                $results = $this->m_client_status->create_new_client_status_process($insertData);

                if ($results) {

                    $response_array['type'] = 'success';
                    $response_array['message'] = '<div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><strong>  Congratulations! </strong> Client Status Created  Successfully. </div>';
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


    public function client_status_name_check($str)
    {
        if (preg_match('/^[a-zA-Z-&0-9_ ]+$/', $str)) {
            return TRUE;
        } else {
            $this->form_validation->set_message('client_status_name_check', 'Allowed Characters are a-zA-Z-&0-9_ ');
            return FALSE;

        }

    }


    public function update_client_status_process()
    {

        header('Content-Type: application/json');
        if ($this->input->is_ajax_request()) {

            $updateId = $this->input->post('updateId');
            $client_status_name = $this->input->post('client_status_name');
            $created_date = date('Y-m-d');
            $modified_by = $_SESSION['admin_logged_in']['user_name'];
            $is_available = 1;


            //set validations
            $this->form_validation->set_rules('client_status_name', 'Client Status Name', 'trim|required|callback_client_status_name_check');

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

                $client_status_exists = $this->m_client_status->check_client_status_exist($client_status_name, $updateId);
                if ($client_status_exists) {

                    $updateData = array(
                        'client_status' => trim($client_status_name),
                        'cre_or_up_date' => trim($created_date),
                        'cre_or_up_by' => trim($modified_by),
                        'status' => trim($is_available)
                    );

                    $updateData = $this->security->xss_clean($updateData);

                    $results = $this->m_client_status->update_client_status_process($updateData, $updateId);

                    if ($results) {

                        $response_array['type'] = 'success';
                        $response_array['message'] = '<div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><strong>  Congratulations! </strong> Client Status Created  Successfully. </div>';
                        echo json_encode($response_array);

                    } else {
                        $response_array['type'] = 'danger';
                        $response_array['message'] = '<div class="alert alert-danger alert-dismissable"><i class="fa fa-times"></i><strong> Sorry! </strong>  Failed.</div>';
                        echo json_encode($response_array);
                    }
                } else {
                    $response_array['type'] = 'danger';
                    $response_array['message'] = '<div class="alert alert-danger alert-dismissable"><i class="fa fa-times"></i><strong> Sorry! </strong>  Client Status Name is already exists.</div>';
                    echo json_encode($response_array);
                }
            }
        } else {
            redirect('admin/dashboard');
        }
    }

    public function delete_client_status()
    {
        header('Content-Type: application/json');

        if ($this->input->is_ajax_request()) {

            $id = $this->input->post('id');
            $result = $this->m_client_status->delete_client_status($id);

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