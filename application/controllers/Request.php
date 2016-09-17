<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH . 'Format.php');
require_once(APPPATH . 'REST_Controller.php');

class Request extends REST_Controller
{
    public function index_get()
    {
        $action = $this->get('action');

        if($action == 'one')
        {
            $user_id = $this->get('user_id');
            $result = $this->users->get_by_id($user_id);

            echo json_encode($result);
        }
        elseif($action == 'all')
        {
            $result = $this->users->get_all();

            echo json_encode($result);
        }
    }

    public function index_post()
    {
        $action = $this->post('action');

        if($action == 'add_user')
        {
            $username = $this->post('username');
            $account_balance = $this->post('account_balance');
            $budget_used = $this->post('budget_used');
            $budget_limit = $this->post('budget_limit');

            $this->users->insert(array(
                'username' => $username,
                'account_balance' => $account_balance,
                'budget_used' => $budget_used,
                'budget_limit' => $budget_limit
            ));
        }
        elseif($action == 'change')
        {
            $user_id = $this->post('user_id');
            $data = json_decode( $this->post('json_string') );

            $this->users->update($user_id, $data);
        }
        elseif($action == 'add_transaction')
        {
            $user_id = $this->post('user_id');

            $itemname = $this->post('itemname');
            $amount = $this->post('amount');
            $payment_method = $this->post('payment_method');
            $date = date('Y-m-d H:i:s');

            $this->users->add_transaction($user_id, array(
                'itemname' => $itemname,
                'amount' => $amount,
                'payment_method' => $payment_method,
                'date' => $date
            ));
        }
    }

    public function index_delete()
    {
        $user_id = $this->delete('user_id');
        $this->users->delete_by_id($user_id);
    }
}