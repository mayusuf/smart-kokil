<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Roll extends Admin_Base_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('admin/m_roll');
    }

    // Managing Roll
    public function index()
    {
        $this->data['title'] = 'Manage Roll';
        $this->data['breadcrumb'] = 'Manage Roll';
        $this->load->view('admin/roll/v_manage_roll', $this->data);
    }

    public function get_all_roll()
    {
        $this->setOutputMode(NORMAL);

        if ($this->input->is_ajax_request()) {
            $this->data['all_roll'] = $this->m_roll->select_all_roll();
            $all_roll_view = $this->load->view('admin/roll/v_all_roll', $this->data, TRUE);
            $this->output->set_output($all_roll_view);
        } else {
            redirect('admin/dashboard');
        }
    }

    public function get_insert_roll_form_view()
    {

        $this->setOutputMode(NORMAL);

        if ($this->input->is_ajax_request()) {
            $insert_roll_form = $this->load->view('admin/roll/v_add_roll_form', $this->data, TRUE);
            $this->output->set_output($insert_roll_form);
        } else {
            redirect('admin/dashboard');
        }
    }

    public function get_update_roll_form_view()
    {

        $this->setOutputMode(NORMAL);

        if ($this->input->is_ajax_request()) {

            $id = $this->input->post('id');

            $this->data['single_roll'] = $this->m_roll->get_roll_by_id($id);
            $update_roll_form = $this->load->view('admin/roll/v_update_roll_form', $this->data, TRUE);
            $this->output->set_output($update_roll_form);
        } else {
            redirect('admin/dashboard');
        }
    }


    public function create_new_roll_process()
    {

        header('Content-Type: application/json');
        $this->setOutputMode(NORMAL);
        if ($this->input->is_ajax_request()) {

            $roll_name = $this->input->post('roll_name');
            $is_available = 1;


            //set validations
            $this->form_validation->set_rules(
                'roll_name', 'Roll Name',
                'trim|required|callback_roll_name_check|is_unique[roll.roll_name]',
                array(
                    'is_unique' => 'This Roll Name is already exists.'
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
                    'roll_name' => trim($roll_name),
                    'is_active' => trim($is_available)
                );


                //    print_r($insertData);

                $insertData = $this->security->xss_clean($insertData);

                $results = $this->m_roll->create_new_roll_process($insertData);

                if ($results) {

                    $response_array['type'] = 'success';
                    $response_array['message'] = '<div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><strong>  Congratulations! </strong> Roll Created  Successfully. </div>';
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

    public function roll_name_check($str)
    {
        if (preg_match('/^[a-zA-Z_ ]+$/', $str)) {
            return TRUE;
        } else {
            $this->form_validation->set_message('roll_name_check', 'The roll name contains only characters and underscore');
            return FALSE;

        }

    }


    public function update_roll_process()
    {

        header('Content-Type: application/json');
        $this->setOutputMode(NORMAL);
        if ($this->input->is_ajax_request()) {


            $updateId = $this->input->post('updateId');
            $roll_name = $this->input->post('roll_name');
            $is_available = 1;

            //set validations
            $this->form_validation->set_rules('roll_name', 'Roll Name', 'trim|required|callback_roll_name_check');


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


                $exists = $this->m_roll->check_roll_exist($roll_name, $updateId);

                $updateData = array(
                    'roll_name' => trim($roll_name)
                );


                // print_r($exists);

                if ($exists) {
                    $updateData = $this->security->xss_clean($updateData);

                    $results = $this->m_roll->update_roll_process($updateData, $updateId);

                    if ($results) {
                        $response_array['type'] = 'success';
                        $response_array['message'] = '<div class="alert alert-success alert-dismissable"><i class="fa fa-check"></i><strong>  Congratulations! </strong> Roll Updated  Successfully. </div>';
                        echo json_encode($response_array);

                    } else {
                        $response_array['type'] = 'danger';
                        $response_array['message'] = '<div class="alert alert-danger alert-dismissable"><i class="fa fa-times"></i><strong> Sorry! </strong>  Failed.</div>';
                        echo json_encode($response_array);
                    }
                } else {
                    $response_array['type'] = 'danger';
                    $response_array['message'] = '<div class="alert alert-danger alert-dismissable"><i class="fa fa-times"></i><strong> Sorry! </strong>  Roll name already exists.</div>';
                    echo json_encode($response_array);
                }
            }

        } else {
            redirect('admin/dashboard');
        }
    }

    public function delete_roll()
    {
        header('Content-Type: application/json');
        $this->setOutputMode(NORMAL);
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id');


            $result = $this->m_roll->delete_roll($id);

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
