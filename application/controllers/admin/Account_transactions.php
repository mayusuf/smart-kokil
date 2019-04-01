<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Account_transactions extends Admin_Base_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('admin/account_transactions_model');
    }


    //Manage all transactions
    public function manage_all_transaction()
    {
        $this->data['page_title'] = 'Manage All Transaction';
        $this->data['breadcrumbs'] = 'manage_transaction';
        $this->data['account_no'] = $this->account_transactions_model->get_account_id();
        $the_view = 'admin/pages/transactions/manage_all_transaction';
        $template = 'admin_master_view';
        parent::render($the_view, $template);

    }

    public function get_transaction_details_view()
    {

        if ($this->input->is_ajax_request()) {
            $account_no = $this->data['account_no'] = $this->input->post('account_no');
            $this->data['all_transaction'] = $this->account_transactions_model->get_single_account_transaction($account_no);
            $view = $this->load->view('admin/pages/transactions/v_transaction_details', $this->data, TRUE);
            $this->output->set_output($view);
        } else {
            redirect('admin/dashboard');
        }
    }

    // Create new transactions
    function create_new_transactions()
    {
        $this->data['page_title'] = 'Create new transactions';
        $this->data['breadcrumbs'] = 'new_transaction';
        $this->data['account_no'] = $this->account_transactions_model->get_account_id();
        $the_view = 'admin/pages/transactions/create_new_transaction';
        $template = 'admin_master_view';
        parent::render($the_view, $template);
    }

    // Get the old balance
    function get_last_balance()
    {
        $account_no = $this->input->post('account_no');
        $last_balance = $this->account_transactions_model->get_last_transaction($account_no);
        // var_dump($last_balance);
        if ($last_balance) {
            echo $last_balance[0]['balance'];
        } else {
            echo 0;
        }
    }


    // create new transactions process
    public function create_new_transaction_process()
    {
        header('Content-Type: application/json');
        if ($this->input->is_ajax_request()) {

            if ($this->input->post('submit') == "Save") {

                $account_no = $this->input->post('account_no');
                $account_action = $this->input->post('account_action');
                $amount = $this->input->post('amount');
                $balance = $this->input->post('new_balance');
                $transaction_date = $this->input->post('transaction_date');
                $comments = $this->input->post('comments');
                $created_by = $_SESSION['admin_logged_in']['user_name'];

                //set validations
                $this->form_validation->set_rules("account_no", "Account Number", "trim|required");
                $this->form_validation->set_rules("account_action", "Account Action", "trim|required");
                $this->form_validation->set_rules("amount", "Amount", "trim|required");
                //   $this->form_validation->set_rules("comments", "Comments", "trim|required");


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
                    $datetime = new DateTime($transaction_date);
                    $entryDate = $datetime->format('Y-m-d');

                    if ($account_action == 'deposit') {

                        $deposit = $amount;
                        $withdraw = 0;
                        $discount = 0;
                    } else if ($account_action == 'discount') {
                        $discount = $amount;
                        $deposit = 0;
                        $withdraw = 0;

                    } else {
                        $withdraw = $amount;
                        $deposit = 0;
                        $discount = 0;
                    }


                    $insertData = array(
                        'accountNo' => trim($account_no),
                        'deposit' => trim($deposit),
                        'withDraw' => trim($withdraw),
                        'balance' => trim($balance),
                        'discount' => trim($discount),
                        'comments' => trim($comments),
                        'cre_or_up_by' => trim($created_by),
                        'entryDate' => trim($entryDate)
                    );

                    $insertData = $this->security->xss_clean($insertData);

                    $results = $this->account_transactions_model->create_new_transaction_process($insertData);

                    if ($results) {

                        $response_array['type'] = 'success';
                        $response_array['message'] = '<div class="alert alert-success alert-dismissable"><i class="icon fa fa-check"></i><strong> Congratulations! </strong> Added  Successfully. </div>';
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


    // Update the transactions
    public function update_transaction($id, $accounts_no)
    {
        $this->data['page_title'] = 'Update transaction';
        $this->data['breadcrumbs'] = 'Update transaction';
        $this->data['account_no'] = $accounts_no;
        $this->data['present_id_data'] = $this->account_transactions_model->get_present_id_data($id);
        $this->data['last_id_balance'] = $this->account_transactions_model->get_previous_id_data($id, $accounts_no);
        $the_view = 'admin/pages/transactions/edit_last_transactions';
        $template = 'admin_master_view';
        parent::render($the_view, $template);

    }

    // Update transactions process
    public function update_transaction_process()
    {
        header('Content-Type: application/json');
        if ($this->input->is_ajax_request()) {

            if ($this->input->post('submit') == "Update") {

                $updateId = $this->input->post('updateId');
                $account_no = $this->input->post('account_no');
                $account_action = $this->input->post('account_action');
                $amount = $this->input->post('amount');
                $balance = $this->input->post('new_balance');
                $transaction_date = $this->input->post('transaction_date');
                $comments = $this->input->post('comments');

                //set validations
                $this->form_validation->set_rules("account_no", "Account Number", "trim|required");
                $this->form_validation->set_rules("account_action", "Account Action", "trim|required");
                $this->form_validation->set_rules("amount", "Amount", "trim|required");
                $this->form_validation->set_rules("comments", "Comments", "trim|required");


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

                    $datetime = new DateTime($transaction_date);
                    $entryDate = $datetime->format('Y-m-d');

                    if ($account_action == 'deposit') {
                        $deposit = $amount;
                        $withdraw = 0;
                    } else {
                        $withdraw = $amount;
                        $deposit = 0;
                    }


                    $updateData = array(
                        'deposit' => trim($deposit),
                        'withDraw' => trim($withdraw),
                        'balance' => trim($balance),
                        'comments' => trim($comments),
                        'entryDate' => trim($entryDate)
                    );

                    $updateData = $this->security->xss_clean($updateData);

                    $results = $this->account_transactions_model->update_transaction_process($updateData, $updateId);

                    if ($results) {


                        $total_records = $this->account_transactions_model->get_last_total_records($account_no, $updateId);
                        //  $last_balance = $this->admin_model->get_last_balance($tableName, $id);

                        $total = count($total_records);
                        //  print_r($total);

                        if ($total > 0) {

                            foreach ($total_records as $records) {

                                $present_id = $records['Id'];
                                $present_deposit = $records['deposit'];
                                $present_withdraw = $records['withDraw'];
                                $present_discount = $records['discount'];
                                $last_records = $this->account_transactions_model->get_previous_id_data($present_id, $account_no);

                                if ($last_records) {
                                    $last_balance = $last_records[0]['balance'];
                                } else {
                                    $last_balance = 0;
                                }

                                if ($present_discount == '0') {
                                    if ($present_deposit == '0') {
                                        $new_balance = $last_balance - $present_withdraw;

                                    } else {
                                        $new_balance = $last_balance + $present_deposit;
                                    }
                                } else {
                                    $new_balance = $last_balance + $present_discount;
                                }


                                $updateData = array(
                                    'balance' => trim($new_balance)
                                );

                                $updateData = $this->security->xss_clean($updateData);

                                $results = $this->account_transactions_model->update_transaction_process($updateData, $present_id);

                            }

                        }

                        $response_array['type'] = 'success';
                        $response_array['message'] = '<div class="alert alert-success alert-dismissable"><i class="icon fa fa-check"></i><strong> Congratulations! </strong>  Successfully Updated. </div>';
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

    // Delete a transactions process using ajax
    public function delete_accounts_transaction()
    {

        header('Content-Type: application/json');
        if ($this->input->is_ajax_request()) {
            $id = $this->input->post('id');
            $account_no = $this->input->post('account_no');
            $result = $this->account_transactions_model->delete_accounts_transaction($id, $account_no);
            if ($result) {

                $total_records = $this->account_transactions_model->get_last_total_records($account_no, $id);

                $total = count($total_records);

                if ($total > 0) {

                    foreach ($total_records as $records) {

                        $present_id = $records['Id'];
                        $present_deposit = $records['deposit'];
                        $present_withdraw = $records['withDraw'];
                        $last_records = $this->account_transactions_model->get_previous_id_data($present_id, $account_no);

                        if ($last_records) {
                            $last_balance = $last_records[0]['balance'];
                        } else {
                            $last_balance = 0;
                        }

                        if ($present_deposit == '0') {
                            $new_balance = $last_balance - $present_withdraw;

                        } else {
                            $new_balance = $last_balance + $present_deposit;
                        }

                        // print_r($new_balance);
                        $updateData = array(
                            'balance' => trim($new_balance)
                        );

                        $updateData = $this->security->xss_clean($updateData);

                        $results = $this->account_transactions_model->update_transaction_process($updateData, $present_id);

                    }

                }
                $response_array['type'] = 'success';
                $response_array['message'] = '<div class="alert alert-success alert-dismissable"><i class="icon fa fa-check"></i><strong> Congratulations! </strong>  Successfully Deleted. </div>';
                echo json_encode($response_array);
            } else {
                $response_array['type'] = 'danger';
                $response_array['message'] = '<div class="alert alert-danger alert-dismissable"><i class="icon fa fa-times"></i><strong> Sorry! </strong>  Failed.</div>';
                echo json_encode($response_array);
            }

        } else {
            redirect('admin/admin_dashboard');
        }
    }

    // Reports
    public function account_transactions_reports()
    {
        $this->data['page_title'] = 'Baby Account Reports';
        $this->data['breadcrumbs'] = 'reports';
        $this->data['account_no'] = $this->account_transactions_model->get_account_id();
        $the_view = 'admin/pages/baby_accounts_reports';
        $template = 'admin_master_view';
        parent::render($the_view, $template);
    }

    // Reports Process


    public function get_transaction_reports_view()
    {

        if ($this->input->is_ajax_request()) {
            $account_no = $this->input->post('account_no');
            $start_date = $this->input->post('start_date');
            $end_date = $this->input->post('end_date');

            $this->form_validation->set_rules("account_no", "Account Number", "trim|required");

            if ($this->form_validation->run() == FALSE) {
                $response_array['type'] = 'danger';
                $response_array['message'] = '<div class="alert alert-danger alert-dismissable"><i class="icon fa fa-times"></i><strong> Please! </strong>  Choose an Account Number.</div>';
                echo json_encode($response_array);

            } else {

                $this->data['details_inf'] = $this->account_transactions_model->get_account_details_inf($account_no);
                $table_name = 'baby_ld_account_' . $account_no;
                $this->data['amount_inf'] = $this->account_transactions_model->get_transaction_reports($table_name, $start_date, $end_date);
                $view = $this->load->view('admin/pages/report/v_transaction_reports', $this->data, TRUE);
                $this->output->set_output($view);
            }
        } else {
            redirect('admin/dashboard');
        }
    }

}
