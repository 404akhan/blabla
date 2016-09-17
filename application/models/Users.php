<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends CI_Model
{
    public function get_by_id($user_id)
    {
        $result = $this->db->where('user_id', $user_id)->get('users')->row();

        return $result;
    }

    public function get_all()
    {
        $result = $this->db->get('users')->result_array();

        return $result;
    }

    public function delete_by_id($user_id)
    {
        $this->db->delete('users', array('user_id' => $user_id));
    }

    public function update($user_id, $data)
    {
        $this->db->update('users', $data, array('user_id' => $user_id));
    }

    public function insert($data)
    {
        $data['transaction_history'] = json_encode(array());

        $this->db->insert('users', $data);
    }

    public function add_transaction($user_id, $data)
    {
        $person = $this->get_by_id($user_id);

        $transactions = json_decode( $person->transaction_history, true );
        array_push($transactions, $data);
        $transactions_update = json_encode( $transactions );

        $this->update(
            $user_id,
            array('transaction_history' => $transactions_update)
        );
    }

    public function add_merchant($user_id, $data)
    {
        $person = $this->get_by_id($user_id);

        $merchants = json_decode( $person->available_merchants, true );
        array_push($merchants, $data);
        $merchants_update = json_encode( $merchants );

        $this->update(
            $user_id,
            array('available_merchants' => $merchants_update)
        );
    }

    public function validate($user_id, $merchant_id)
    {
        $result = $this->db->where('user_id', $user_id)->like('available_merchants', $merchant_id)
            ->get('users')->result_array();

        if(count($result) == 0)
            return 0;
        else
            return 1;
    }

    public function get_products($user_id, $token)
    {
        $array = array();

        $total = $this->db->count_all('products');

        for($i = 1; $i <= $total; $i++)
        {
            if(mt_rand(0, 1) == 0)
                array_push($array, $i);
        }

        $products = array();

        foreach($array as $id)
        {
            $result = $this->db->where('id', $id)->get('products')->row_array();

            $result['quantity'] = 1;
            array_push($products, $result);
        }

        return $products;
    }
}