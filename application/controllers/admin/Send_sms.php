<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Send_sms extends Admin_Base_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('admin/m_send_sms');
        $this->load->model('admin/m_sms_template');
        $this->load->model('admin/account_settings_model');
    }


    // Create new account
    function index()
    {
        $this->data['page_title'] = 'Send message';
        $this->data['breadcrumbs'] = 'Send message';
        $this->data['group_template'] = $this->m_send_sms->get_group_template();
        $this->load->model('admin/m_circle');
        $this->data['all_circle'] = $this->m_circle->get_all_circle();
        $this->load->model('admin/m_tax');
        $this->data['all_tax'] = $this->m_tax->get_all_tax();
        $the_view = 'admin/pages/message/v_send_message';
        $template = 'admin_master_view';
        parent::render($the_view, $template);
    }

    // Create new account
    function single_sms()
    {
        $this->data['page_title'] = 'Send Single SMS';
        $this->data['breadcrumbs'] = 'Send Single SMS';
        $this->data['account_no'] = $this->m_send_sms->get_all_account();
        $this->data['single_template'] = $this->m_send_sms->get_single_template();
        $the_view = 'admin/pages/message/v_send_single_message';
        $template = 'admin_master_view';
        parent::render($the_view, $template);
    }

    public function single_sms_preview()
    {
        if ($this->input->is_ajax_request()) {
            $account_no = $this->input->post('account_no');
            $table_name = 'baby_ld_account_' . $account_no;
            $this->data['account_inf'] = $this->account_settings_model->get_account_by_accountNo($account_no);
            $this->load->model('admin/account_transactions_model');
            $this->data['last_balance'] = $this->account_transactions_model->get_old_balance($table_name);
            $temp_id = $this->input->post('temp_name');
            $this->load->model('admin/m_sms_template');
            $this->data['single_template'] = $this->m_sms_template->get_template_by_id($temp_id);
            $preview = $this->load->view('admin/pages/message/v_single_sms_preview', $this->data, TRUE);
            echo $preview;
        } else {
            redirect('admin/dashboard');
        }
    }

    public function send_single_sms()
    {
        header('Content-Type: application/json');
        if ($this->input->is_ajax_request()) {

            if ($this->input->post('submit') == "Submit") {

                $contact = $this->input->post('contact');
                $message = $this->input->post('preview_message');

                $check_contact_exist = $this->account_settings_model->check_contact_exist($contact);

                if ($check_contact_exist) {

                    $ch = curl_init();
                    $str = urlencode($message);

                    $url = "http://app.itsolutionbd.net/api/v3/sendsms//plain?user=YafiTech&password=12332112&sender=YafiTech&SMSText=" . $str . "&GSM=88" . $contact;

                    //initialize curl handle
                    curl_setopt($ch, CURLOPT_URL, $url);
                    //set the url
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    //run the whole process and return the response
                    $response = curl_exec($ch);
                    //close the curl handle
                    curl_close($ch);

                    if ($response) {
                        $response_array['type'] = 'success';
                        $response_array['message'] = '<div class="alert alert-success"><strong>Congratulations!</strong> Message sent successfully</div>';
                        echo json_encode($response_array);

                    } else {
                        $response_array['type'] = 'danger';
                        $response_array['message'] = '<div class="alert alert-warning"><strong>Sorry!</strong> Message sending  Failed.</div>';
                        echo json_encode($response_array);
                    }

                } else {
                    $response_array['type'] = 'danger';
                    $response_array['message'] = '<div class="alert alert-danger alert-dismissable"><i class="icon fa fa-times"></i><strong> Sorry! </strong>  Contact Number is not found.</div>';
                    echo json_encode($response_array);
                }

            } else {
                redirect('admin/dashboard');
            }
        } else {
            redirect('admin/dashboard');
        }
    }


    public function group_sms_preview()
    {
        if ($this->input->is_ajax_request()) {
            $account_type = $this->input->post('account_type');
            $circle_tax = $this->input->post('circle_tax');

            $this->data['account_inf'] = $this->account_settings_model->get_account_by_type_circle($account_type, $circle_tax);
            $temp_id = $this->input->post('temp_name');
            $this->load->model('admin/m_sms_template');
            $this->data['group_template'] = $this->m_sms_template->get_template_by_id($temp_id);
            $preview = $this->load->view('admin/pages/message/v_group_sms_preview', $this->data, TRUE);
            echo $preview;
        } else {
            redirect('admin/dashboard');
        }
    }


    public function send_group_sms()
    {
        header('Content-Type: application/json');
        if ($this->input->is_ajax_request()) {

            $list_id = $this->input->post('id');
            //var_dump($list_id);
            $sms = $this->input->post('preview_message');

            foreach ($list_id as $account) {

                $table_name = 'baby_ld_account_' . $account;
                $account_inf = $this->account_settings_model->get_account_by_accountNo($account);
                foreach ($account_inf as $account) {
                    $name = $account['accountName'];
                    $contact = $account['contactNo'];
                }
                $this->load->model('admin/account_transactions_model');
                $last_balance = $this->account_transactions_model->get_old_balance($table_name);
                if ($last_balance) {
                    $balance = $last_balance[0]['balance'];
                } else {
                    $balance = 0;
                }

                if ($contact != "") {

                    $search = array("{name}", "{balance}");
                    $replace = array($name, $balance);

                    $message = str_replace($search, $replace, $sms);

                    $ch = curl_init();
                    $str = urlencode($message);

                    $url = "http://app.itsolutionbd.net/api/v3/sendsms//plain?user=YafiTech&password=12332112&sender=YafiTech&SMSText=" . $str . "&GSM=88" . $contact;

                    //initialize curl handle
                    curl_setopt($ch, CURLOPT_URL, $url);
                    //set the url
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                    //run the whole process and return the response
                    $response = curl_exec($ch);
                    //close the curl handle
                    curl_close($ch);
                    $response = true;
                }
            }
            if ($response) {
                $response_array['type'] = 'success';
                $response_array['message'] = '<div class="alert alert-success"><strong>Congratulations!</strong> Message sent successfully</div>';
                echo json_encode($response_array);

            } else {
                $response_array['type'] = 'danger';
                $response_array['message'] = '<div class="alert alert-warning"><strong>Sorry!</strong> Message sending  Failed.</div>';
                echo json_encode($response_array);
            }
        } else {
            redirect('admin/dashboard');
        }
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
            $message = $this->input->post('message');
            $created_date = date('Y-m-d');
            $modified_by = $_SESSION['admin_logged_in']['user_name'];
            $is_available = 1;


            //set validations
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
            $message = $this->input->post('message');
            $created_date = date('Y-m-d');
            $modified_by = $_SESSION['admin_logged_in']['user_name'];
            $is_available = 1;


            //set validations
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
                        'temp_message' => trim($message),
                        'cre_or_up_date' => trim($created_date),
                        'cre_or_up_by' => trim($modified_by),
                        'status' => trim($is_available)
                    );

                    $updateData = $this->security->xss_clean($updateData);

                    $results = $this->m_sms_template->update_template_process($updateData, $updateId);

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