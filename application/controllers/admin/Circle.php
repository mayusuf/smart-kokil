<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Circle extends Admin_Base_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('admin/m_circle');
    }


    // Create new account
    function index()
    {
        $this->data['page_title'] = 'Manage Circle';
        $this->data['breadcrumbs'] = 'Manage Circle';
        $the_view = 'admin/pages/circle/v_manage_circle';
        $circle = 'admin_master_view';
        parent::render($the_view, $circle);
    }

    public function get_all_circle()
    {
        if ($this->input->is_ajax_request()) {
            $this->data['all_circle'] = $this->m_circle->get_all_circle();
            $view = $this->load->view('admin/pages/circle/v_all_circle', $this->data, TRUE);
            $this->output->set_output($view);
        } else {
            redirect('admin/dashboard');
        }
    }


    public function get_insert_circle_form_view()
    {
        if ($this->input->is_ajax_request()) {
            $view = $this->load->view('admin/pages/circle/v_add_circle_form', $this->data, TRUE);
            $this->output->set_output($view);
        } else {
            redirect('admin/dashboard');
        }
    }

    public function get_update_circle_form_view()
    {
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id');
            $this->data['single_circle'] = $this->m_circle->get_circle_by_id($id);
            $view = $this->load->view('admin/pages/circle/v_update_circle_form', $this->data, TRUE);
            $this->output->set_output($view);
        } else {
            redirect('admin/dashboard');
        }
    }


    public function create_new_circle_process()
    {

        header('Content-Type: application/json');
        if ($this->input->is_ajax_request()) {

            $circle_name = $this->input->post('circle_name');
            $created_date = date('Y-m-d');
            $modified_by = $_SESSION['admin_logged_in']['user_name'];
            $is_available = 1;


            //set validations
            $this->form_validation->set_rules(
                'circle_name', 'Circle Name',
                'trim|required|callback_circle_name_check|is_unique[circle.circle_name]',
                array(
                    'is_unique' => 'This Circle Name is already exists.'
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
                    'circle_name' => trim($circle_name),
                    'cre_or_up_date' => trim($created_date),
                    'cre_or_up_by' => trim($modified_by),
                    'status' => trim($is_available)
                );

                $insertData = $this->security->xss_clean($insertData);

                $results = $this->m_circle->create_new_circle_process($insertData);

                if ($results) {

                    $response_array['type'] = 'success';
                    $response_array['message'] = '<div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><strong>  Congratulations! </strong> Circle Created  Successfully. </div>';
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


    public function circle_name_check($str)
    {
        if (preg_match('/^[a-zA-Z-&0-9_ ]+$/', $str)) {
            return TRUE;
        } else {
            $this->form_validation->set_message('circle_name_check', 'Allowed Characters are a-zA-Z-&0-9_ ');
            return FALSE;

        }

    }


    public function update_circle_process()
    {

        header('Content-Type: application/json');
        if ($this->input->is_ajax_request()) {

            $updateId = $this->input->post('updateId');
            $circle_name = $this->input->post('circle_name');
            $created_date = date('Y-m-d');
            $modified_by = $_SESSION['admin_logged_in']['user_name'];
            $is_available = 1;


            //set validations
            $this->form_validation->set_rules('circle_name', 'Circle Name', 'trim|required|callback_circle_name_check');

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

                $circle_exists = $this->m_circle->check_circle_exist($circle_name, $updateId);
                if ($circle_exists) {

                    $updateData = array(
                        'circle_name' => trim($circle_name),
                        'cre_or_up_date' => trim($created_date),
                        'cre_or_up_by' => trim($modified_by),
                        'status' => trim($is_available)
                    );

                    $updateData = $this->security->xss_clean($updateData);

                    $results = $this->m_circle->update_circle_process($updateData, $updateId);

                    if ($results) {

                        $response_array['type'] = 'success';
                        $response_array['message'] = '<div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><strong>  Congratulations! </strong> Circle Created  Successfully. </div>';
                        echo json_encode($response_array);

                    } else {
                        $response_array['type'] = 'danger';
                        $response_array['message'] = '<div class="alert alert-danger alert-dismissable"><i class="fa fa-times"></i><strong> Sorry! </strong>  Failed.</div>';
                        echo json_encode($response_array);
                    }
                } else {
                    $response_array['type'] = 'danger';
                    $response_array['message'] = '<div class="alert alert-danger alert-dismissable"><i class="fa fa-times"></i><strong> Sorry! </strong>  Circle Name is already exists.</div>';
                    echo json_encode($response_array);
                }
            }
        } else {
            redirect('admin/dashboard');
        }
    }

    public function delete_circle()
    {
        header('Content-Type: application/json');

        if ($this->input->is_ajax_request()) {

            $id = $this->input->post('id');
            $result = $this->m_circle->delete_circle($id);

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