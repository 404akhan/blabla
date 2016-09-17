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

            header('Content-Type: application/json');
            echo json_encode($result);
        }
        elseif($action == 'all')
        {
            $result = $this->users->get_all();

            header('Content-Type: application/json');
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
        elseif($action == 'add_merchant')
        {
            $user_id = $this->post('user_id');

            $merchant_name = $this->post('merchant_name');

            $this->users->add_merchant($user_id, $merchant_name);
        }
        elseif($action == 'validate')
        {
            $user_id = $this->post('user_id');
            $merchant_id = $this->post('merchant_id');

            if($this->users->validate($user_id, $merchant_id))
            {
                $data = array();
                $data['result_code'] = 200;
                $data['result_description'] = 'merchant is allowed';

                header('Content-Type: application/json');
                echo json_encode($data);
            }
            else
            {
                $data = array();
                $data['result_code'] = 403;
                $data['result_description'] = 'merchant is not allowed';

                header('Content-Type: application/json');
                echo json_encode($data);
            }
        }
        elseif($action == 'purchase')
        {
            $user_id = $this->post('user_id');
            $token = $this->post('token');

            $products = $this->users->get_products($user_id, $token);

            header('Content-Type: application/json');
            echo json_encode($products);
        }
    }

    public function index_delete()
    {
        $user_id = $this->delete('user_id');
        $this->users->delete_by_id($user_id);
    }
}