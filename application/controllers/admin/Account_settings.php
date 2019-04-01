<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Account_settings extends Admin_Base_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('admin/account_settings_model');
    }


    // Create new account
    function create_new_account()
    {
        $this->data['page_title'] = 'Create new account';
        $this->data['breadcrumbs'] = 'new_account';
        $this->load->model('admin/m_circle');
        $this->data['all_circle'] = $this->m_circle->get_all_circle();
        $this->load->model('admin/m_tax');
        $this->data['all_tax'] = $this->m_tax->get_all_tax();
        $the_view = 'admin/pages/account_settings/create_new_account';
        $template = 'admin_master_view';
        parent::render($the_view, $template);
    }

    // Create new account process
    public function create_new_account_process()
    {
        header('Content-Type: application/json');
        if ($this->input->is_ajax_request()) {

            if ($this->input->post('submit')) {

                $account_no = $this->input->post('account_no');
                $account_name = $this->input->post('account_name');
                $account_address = $this->input->post('account_address');
                $contact_no = $this->input->post('contact_no');
                $account_type = $this->input->post('account_type');
                $circle_tax = $this->input->post('circle_tax');
                $created_by = $_SESSION['admin_logged_in']['user_name'];
                $created_date = date('Y-m-d');
                $status = 1;


                //set validations
                $this->form_validation->set_rules("account_no", "Account Number", 'trim|required|callback_account_number_check|is_unique[baby_account_info.accountNo]',
                    array(
                        'is_unique' => 'This Account is already exists.'
                    )
                );
                $this->form_validation->set_rules("account_name", "Account Name", "trim|required");
                $this->form_validation->set_rules("account_address", "Address", "trim|required");
                $this->form_validation->set_rules("contact_no", "Contact Number", 'trim|required|is_unique[baby_account_info.contactNo]',
                    array(
                        'is_unique' => 'This Contact is already exists.'
                    )
                );
                $this->form_validation->set_rules("account_type", "Type", "trim|required");
                $this->form_validation->set_rules("circle_tax", "Circle", "trim|required");


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

                    $insertData = array(
                        'accountNo' => trim($account_no),
                        'accountName' => trim($account_name),
                        'accountAddress' => trim($account_address),
                        'contactNo' => trim($contact_no),
                        'account_type' => trim($account_type),
                        'circle_tax' => trim($circle_tax),
                        'cre_or_up_by' => trim($created_by),
                        'cre_or_up_date' => trim($created_date),
                        'status' => trim($status)
                    );

                    $insertData = $this->security->xss_clean($insertData);

                    $results = $this->account_settings_model->create_new_account_process($insertData);

                    if ($results) {

                        if ($this->input->post('submit') == 'Save & Transaction') {
                            $response_array['redirect'] = 'true';
                            $this->session->set_flashdata('acc_no', $account_no);
                        }
                        $response_array['type'] = 'success';
                        $response_array['message'] = '<div class="alert alert-success alert-dismissable"><i class="icon fa fa-check"></i><strong>  Congratulations! </strong> Account Created  Successfully. </div>';
                        echo json_encode($response_array);

                    } else {
                        $response_array['type'] = 'danger';
                        $response_array['message'] = '<div class="alert alert-danger alert-dismissable"><i class="icon fa fa-times"></i><strong> Sorry! </strong>  Failed.</div>';
                        echo json_encode($response_array);
                    }
                }
            } else {
                redirect('admin/admin_dashboard');
            }
        } else {
            redirect('admin/admin_dashboard');
        }
    }

    public function account_number_check($str)
    {
        if (preg_match('/^[a-zA-Z0-9]+$/', $str)) {
            return TRUE;
        } else {
            $this->form_validation->set_message('account_number_check', 'Allowed character is 0-9a-zA-Z');
            return FALSE;

        }

    }

    // Manage all accounts
    public function manage_accounts_settings()
    {
        $this->data['page_title'] = 'Manage all accounts';
        $this->data['breadcrumbs'] = 'manage_accounts';
        $this->data['all_accounts'] = $this->account_settings_model->get_all_accounts();
        $the_view = 'admin/pages/account_settings/manage_all_accounts';
        $template = 'admin_master_view';
        parent::render($the_view, $template);
    }

    public function update_accounts_settings($id)
    {
        $this->data['page_title'] = 'Update Accounts Settings';
        $this->data['breadcrumbs'] = 'update_account';
        $this->load->model('admin/m_circle');
        $this->data['all_circle'] = $this->m_circle->get_all_circle();
        $this->load->model('admin/m_tax');
        $this->data['all_tax'] = $this->m_tax->get_all_tax();
        $this->data['account_no'] = $this->account_settings_model->get_single_account($id);
        $the_view = 'admin/pages/account_settings/edit_accounts_settings';
        $template = 'admin_master_view';
        parent::render($the_view, $template);
    }

    public function update_accounts_settings_process()
    {

        header('Content-Type: application/json');
        if ($this->input->is_ajax_request()) {

            if ($this->input->post('submit') == "Update") {


                $updateId = $this->input->post('updateId');
                $account_name = $this->input->post('account_name');
                $account_address = $this->input->post('account_address');
                $contact_no = $this->input->post('contact_no');
                $account_type = $this->input->post('account_type');
                $circle_tax = $this->input->post('circle_tax');
                $created_by = $_SESSION['admin_logged_in']['user_name'];
                $created_date = date('Y-m-d');


                //set validations
                $this->form_validation->set_rules("account_name", "Account Name", "trim|required");
                $this->form_validation->set_rules("account_address", "Address", "trim|required");
                $this->form_validation->set_rules("contact_no", "Contact Number", "trim|required");
                $this->form_validation->set_rules("account_type", "Type", "trim|required");
                $this->form_validation->set_rules("circle_tax", "Circle", "trim|required");


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
                        'accountName' => trim($account_name),
                        'accountAddress' => trim($account_address),
                        'contactNo' => trim($contact_no),
                        'account_type' => trim($account_type),
                        'circle_tax' => trim($circle_tax),
                        'cre_or_up_by' => trim($created_by),
                        'cre_or_up_date' => trim($created_date),
                    );

                    $updateData = $this->security->xss_clean($updateData);
                    $exists = $this->account_settings_model->check_contact_exist($contact_no, $updateId);
                    if (!$exists) {

                        $results = $this->account_settings_model->update_accounts_settings_process($updateData, $updateId);

                        if ($results) {
                            $response_array['type'] = 'success';
                            $response_array['message'] = '<div class="alert alert-success alert-dismissable"><i class="icon fa fa-check"></i><strong>  Congratulations! </strong> Updated  Successfully. </div>';
                            echo json_encode($response_array);
                        } else {

                            $response_array['type'] = 'danger';
                            $response_array['message'] = '<div class="alert alert-danger alert-dismissable"><i class="icon fa fa-times"></i><strong> Sorry! </strong>  Failed.</div>';
                            echo json_encode($response_array);
                        }
                    } else {
                        $response_array['type'] = 'danger';
                        $response_array['message'] = '<div class="alert alert-danger alert-dismissable"><i class="icon fa fa-times"></i><strong> Sorry! </strong>  Mobile is already exists.</div>';
                        echo json_encode($response_array);
                    }

                }
            } else {
                redirect('admin/admin_dashboard');
            }
        } else {
            redirect('admin/admin_dashboard');
        }

    }
}
