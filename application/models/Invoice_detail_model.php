<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Contacts table.
 */
class Invoice_detail_model extends CI_Model {

    private $_invoice_detail_id;
    private $_description;
    private $_unit_price;
    private $_quantity;
    public $rules = array();

    public function set_rules($i)
    {

        $this->rules = array(
            'update' =>
            array(
                'description[' . $i . ']' => array('field' => 'description[' . $i . ']', 'label' => '', 'rules' => 'trim|required', 'errors' => array(
                        'required' => 'Required.',
                    ),)
        ));
    }

    // Constructor
    function __construct()
    {
        parent::__construct();
        //$this->setTable('contacts', 'ID');
    }

    // return all images desc order by post date
    function get_invoice_details($invoice_id)
    {
        $this->db->where('invoice_id', $invoice_id);
        $query = $this->db->get('invoice_item');
        return $query->result_array();
    }

    public function get_invoice_detail($user_id, $client_id)
    {

        $sql = "SELECT * FROM client_contact WHERE client_id = ? AND user_id = ?";

        $query = $this->db->query($sql, array($client_id, $user_id));
        $row = $query->row();
        return $row;
    }

    public function validade_items($arr_items)
    {

        foreach ($arr_items as $ind => $item)
        {

            $description = $item['description'];
            $quantity = $item['quantity'];
            $unit_price = $item['unit_price'];

            $this->invoice_detail_model->set_rules($ind);
            $invoice_item_rules = $this->invoice_detail_model->rules['update'];
            $this->form_validation->set_rules($invoice_item_rules);
        }
    }

    public function create($invoice_id, $description, $quantity, $unit_price)
    {

        $data = array(
            'invoice_id' => $invoice_id,
            'description' => $description,
            'quantity' => $quantity,
            'unit_price' => $unit_price
        );

        return $this->db->insert('invoice_item', $data);
    }

    public static function get_total_line($unit_price, $quantity)
    {
        $total_line = money_format('%i', $unit_price * $quantity);
        return $total_line;
    }

    public function update($invoice_id, $invoice_detail_id, $description, $quantity, $unit_price)
    {

        $data = array(
            'description' => $description,
            'quantity' => $quantity,
            'unit_price' => $unit_price
        );

        $this->db->where('invoice_detail_id', $invoice_detail_id);


        return $this->db->update('invoice_item', $data);
    }

    public function delete($invoice_id, $invoice_detail_id)
    {

        $this->db->where('invoice_detail_id', $invoice_detail_id);
        $this->db->where('invoice_id', $invoice_id);
        $this->db->delete('invoice_item');
    }

}
