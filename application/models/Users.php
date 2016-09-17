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
}