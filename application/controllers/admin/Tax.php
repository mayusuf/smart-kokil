<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Tax extends Admin_Base_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('admin/m_tax');
    }


    // Create new account
    function index()
    {
        $this->data['page_title'] = 'Manage Type';
        $this->data['breadcrumbs'] = 'Manage Type';
        $the_view = 'admin/pages/tax/v_manage_tax';
        $tax = 'admin_master_view';
        parent::render($the_view, $tax);
    }

    public function get_all_tax()
    {
        if ($this->input->is_ajax_request()) {
            $this->data['all_tax'] = $this->m_tax->get_all_tax();
            $view = $this->load->view('admin/pages/tax/v_all_tax', $this->data, TRUE);
            $this->output->set_output($view);
        } else {
            redirect('admin/dashboard');
        }
    }


    public function get_insert_tax_form_view()
    {
        if ($this->input->is_ajax_request()) {
            $view = $this->load->view('admin/pages/tax/v_add_tax_form', $this->data, TRUE);
            $this->output->set_output($view);
        } else {
            redirect('admin/dashboard');
        }
    }

    public function get_update_tax_form_view()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id');
            $this->data['single_tax'] = $this->m_tax->get_tax_by_id($id);
            $view = $this->load->view('admin/pages/tax/v_update_tax_form', $this->data, TRUE);
            $this->output->set_output($view);
        } else {
            redirect('admin/dashboard');
        }
    }


    public function create_new_tax_process()
    {

        header('Content-Type: application/json');
        if ($this->input->is_ajax_request()) {

            $tax_name = $this->input->post('tax_name');
            $created_date = date('Y-m-d');
            $modified_by = $_SESSION['admin_logged_in']['user_name'];
            $is_available = 1;


            //set validations
            $this->form_validation->set_rules(
                'tax_name', 'Tax Name',
                'trim|required|callback_tax_name_check|is_unique[tax.tax_name]',
                array(
                    'is_unique' => 'This Type Name is already exists.'
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
                    'tax_name' => trim($tax_name),
                    'cre_or_up_date' => trim($created_date),
                    'cre_or_up_by' => trim($modified_by),
                    'status' => trim($is_available)
                );

                $insertData = $this->security->xss_clean($insertData);

                $results = $this->m_tax->create_new_tax_process($insertData);

                if ($results) {

                    $response_array['type'] = 'success';
                    $response_array['message'] = '<div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><strong>  Congratulations! </strong> Tax Created  Successfully. </div>';
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


    public function tax_name_check($str)
    {
        if (preg_match('/^[a-zA-Z-&0-9_. ]+$/', $str)) {
            return TRUE;
        } else {
            $this->form_validation->set_message('tax_name_check', 'Allowed Characters are a-zA-Z-&0-9_ ');
            return FALSE;

        }

    }


    public function update_tax_process()
    {

        header('Content-Type: application/json');
        if ($this->input->is_ajax_request()) {

            $updateId = $this->input->post('updateId');
            $tax_name = $this->input->post('tax_name');
            $created_date = date('Y-m-d');
            $modified_by = $_SESSION['admin_logged_in']['user_name'];
            $is_available = 1;


            //set validations
            $this->form_validation->set_rules('tax_name', 'Tax Name', 'trim|required|callback_tax_name_check');

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

                $tax_exists = $this->m_tax->check_tax_exist($tax_name, $updateId);
                if ($tax_exists) {

                    $updateData = array(
                        'tax_name' => trim($tax_name),
                        'cre_or_up_date' => trim($created_date),
                        'cre_or_up_by' => trim($modified_by),
                        'status' => trim($is_available)
                    );

                    $updateData = $this->security->xss_clean($updateData);

                    $results = $this->m_tax->update_tax_process($updateData, $updateId);

                    if ($results) {

                        $response_array['type'] = 'success';
                        $response_array['message'] = '<div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><strong>  Congratulations! </strong> Tax Created  Successfully. </div>';
                        echo json_encode($response_array);

                    } else {
                        $response_array['type'] = 'danger';
                        $response_array['message'] = '<div class="alert alert-danger alert-dismissable"><i class="fa fa-times"></i><strong> Sorry! </strong>  Failed.</div>';
                        echo json_encode($response_array);
                    }
                } else {
                    $response_array['type'] = 'danger';
                    $response_array['message'] = '<div class="alert alert-danger alert-dismissable"><i class="fa fa-times"></i><strong> Sorry! </strong>  Tax Name is already exists.</div>';
                    echo json_encode($response_array);
                }
            }
        } else {
            redirect('admin/dashboard');
        }
    }

    public function delete_tax()
    {
        header('Content-Type: application/json');

        if ($this->input->is_ajax_request()) {

            $id = $this->input->post('id');
            $result = $this->m_tax->delete_tax($id);

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