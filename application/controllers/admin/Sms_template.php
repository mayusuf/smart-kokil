<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Sms_template extends Admin_Base_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('admin/m_sms_template');
    }


    // Create new account
    function index()
    {
        $this->data['page_title'] = 'Manage SMS Template';
        $this->data['breadcrumbs'] = 'Manage SMS Template';
        $the_view = 'admin/pages/sms/v_manage_sms_template';
        $template = 'admin_master_view';
        parent::render($the_view, $template);
    }

    public function get_all_template()
    {
        if ($this->input->is_ajax_request()) {
            $this->data['all_template'] = $this->m_sms_template->get_all_template();
            $view = $this->load->view('admin/pages/sms/v_all_template', $this->data, TRUE);
            $this->output->set_output($view);
        } else {
            redirect('admin/dashboard');
        }
    }


    public function get_insert_template_form_view()
    {
        if ($this->input->is_ajax_request()) {
            $view = $this->load->view('admin/pages/sms/v_add_template_form', $this->data, TRUE);
            $this->output->set_output($view);
        } else {
            redirect('admin/dashboard');
        }
    }

    public function get_update_template_form_view()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id');
            $this->data['single_template'] = $this->m_sms_template->get_template_by_id($id);
            $view = $this->load->view('admin/pages/sms/v_update_template_form', $this->data, TRUE);
            $this->output->set_output($view);
        } else {
            redirect('admin/dashboard');
        }
    }

    public function get_template_details_view()
    {

        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id');
            $this->data['single_template'] = $this->m_sms_template->get_template_by_id($id);
            $view = $this->load->view('admin/pages/sms/v_template_details', $this->data, TRUE);
            $this->output->set_output($view);
        } else {
            redirect('admin/dashboard');
        }
    }

    public function create_new_template_process()
    {

        header('Content-Type: application/json');
        if ($this->input->is_ajax_request()) {

            $template_name = $this->input->post('template_name');
            $temp_type = $this->input->post('temp_type');
            $message = $this->input->post('message');
            $created_date = date('Y-m-d');
            $modified_by = $_SESSION['admin_logged_in']['user_name'];
            $is_available = 1;


            //set validations
            $this->form_validation->set_rules('temp_type', 'Template Type','trim|required');
            $this->form_validation->set_rules(
                'template_name', 'Template Name',
                'trim|required|callback_template_name_check|is_unique[sms_template.temp_name]',
                array(
                    'is_unique' => 'This Template Name is already exists.'
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
                    'temp_name' => trim($template_name),
                    'temp_type' => trim($temp_type),
                    'temp_message' => trim($message),
                    'cre_or_up_date' => trim($created_date),
                    'cre_or_up_by' => trim($modified_by),
                    'status' => trim($is_available)
                );


                //    print_r($insertData);

                $insertData = $this->security->xss_clean($insertData);

                $results = $this->m_sms_template->create_new_template_process($insertData);

                if ($results) {

                    $response_array['type'] = 'success';
                    $response_array['message'] = '<div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><strong>  Congratulations! </strong> Template Created  Successfully. </div>';
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


    public function template_name_check($str)
    {
        if (preg_match('/^[a-zA-Z-&0-9_ ]+$/', $str)) {
            return TRUE;
        } else {
            $this->form_validation->set_message('template_name_check', 'Allowed Characters are a-zA-Z-&0-9_ ');
            return FALSE;

        }

    }


    public function update_template_process()
    {

        header('Content-Type: application/json');
        if ($this->input->is_ajax_request()) {

            $updateId = $this->input->post('updateId');
            $template_name = $this->input->post('template_name');
            $temp_type = $this->input->post('temp_type');
            $message = $this->input->post('message');
            $created_date = date('Y-m-d');
            $modified_by = $_SESSION['admin_logged_in']['user_name'];
            $is_available = 1;


            //set validations
            $this->form_validation->set_rules('temp_type', 'Template Type','trim|required');
            $this->form_validation->set_rules('template_name', 'Template Name', 'trim|required|callback_template_name_check');

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

                $temp_exists = $this->m_sms_template->check_template_exist($template_name, $updateId);
                if ($temp_exists) {

                    $updateData = array(
                        'temp_name' => trim($template_name),
                        'temp_type' => trim($temp_type),
                        'temp_message' => trim($message),
                        'cre_or_up_date' => trim($created_date),
                        'cre_or_up_by' => trim($modified_by),
                        'status' => trim($is_available)
                    );

                    $updateData = $this->security->xss_clean($updateData);

                    $results = $this->m_sms_template->update_template_process($updateData,$updateId);

                    if ($results) {

                        $response_array['type'] = 'success';
                        $response_array['message'] = '<div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><strong>  Congratulations! </strong> Template Created  Successfully. </div>';
                        echo json_encode($response_array);

                    } else {
                        $response_array['type'] = 'danger';
                        $response_array['message'] = '<div class="alert alert-danger alert-dismissable"><i class="fa fa-times"></i><strong> Sorry! </strong>  Failed.</div>';
                        echo json_encode($response_array);
                    }
                } else {
                    $response_array['type'] = 'danger';
                    $response_array['message'] = '<div class="alert alert-danger alert-dismissable"><i class="fa fa-times"></i><strong> Sorry! </strong>  Template is already exists.</div>';
                    echo json_encode($response_array);
                }
            }
        } else {
            redirect('admin/dashboard');
        }
    }

    public function delete_template()
    {
        header('Content-Type: application/json');

        if ($this->input->is_ajax_request()) {

            $id = $this->input->post('id');
            $result = $this->m_sms_template->delete_template($id);

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