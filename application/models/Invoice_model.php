<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Invoice_model extends CI_Model {

    private $_invoice_id;
    private $_arr_items;
    public $rules = array(
        'update' =>
        array(
            'date_issue' => array('field' => 'date_issue', 'label' => 'Date of Issue', 'rules' => 'trim|required|valid_date'),
            'due_date' => array('field' => 'due_date', 'label' => 'Due Date', 'rules' => 'trim|required|valid_date')
    ));

    function __construct()
    {
        parent::__construct();
        $this->load->model('invoice_detail_model');
    }

    function get_invoices($client_id)
    {
        $this->db->where('client_id', $client_id);
        $query = $this->db->get('invoice');

        $invoices = $query->result_array();
        foreach ($invoices as &$invoice)
        {
            $invoice['invoice_number'] = $this->get_invoice_number($invoice);
            $invoice["invoice_id"] = (int)$invoice["invoice_id"];
            $invoice["client_id"] = (int)$invoice["client_id"];
            $invoice["voided"] = (int)$invoice["voided"];        
            $invoice["tax"] = (double)$invoice["tax"];
            $invoice["amount_paid"] = (int)$invoice["amount_paid"];
            $invoice["items"] = $this->invoice_detail_model->get_invoice_details($invoice["invoice_id"]);
            $invoice["status"] = self::getStatus($invoice);
        }


        return $invoices;
    }

    public function getStatus($invoice) {
        $status = "Draft";
        
        if($invoice["voided"] === 1) {
            $status = "Voided";
        }
        
        if($invoice["date_sent"] !== NULL) {
            $status = "Sent";
        }
        
        if($invoice["date_transaction"] !== NULL) {
            $status = "Paid";
        }
        

        	
        return $status;
    }

    public function get_invoice($invoice_id)
    {

        $sql = "SELECT * FROM invoice WHERE invoice_id = ?";

        $query = $this->db->query($sql, array($invoice_id));
        $row = $query->row();
        if ($row)
        {
            //$row->invoice_number = $this->get_invoice_number((array) $row);

            
            $row->invoice_number = $this->get_invoice_number((array) $row);
            $row->invoice_id = (int)$row->invoice_id;
            $row->client_id = (int)$row->client_id;
            $row->voided = (int)$row->voided;
            $row->tax = (int)$row->tax;
            $row->amount_paid = (int)$row->amount_paid;
            $row->items = $this->invoice_detail_model->get_invoice_details($row->invoice_id);
            $row->status = self::getStatus((array) $row);
            
        }

        return $row;
    }

    public function delete_items($invoice_id, $arr_invoice_entries_del)
    {
        $countsize = count($arr_invoice_entries_del);
        for ($i = 0; $i < $countsize; $i++)
        {
            $this->invoice_detail_model->delete($invoice_id, $arr_invoice_entries_del[$i]);
        }
    }

    public function add_items($invoice_id, $arr_items)
    {
        $countsize = count($arr_items);
        for ($i = 0; $i < $countsize; $i++)
        {
            $arr_items[$i]['unit_price'] = $this->form_validation->clear_currency($arr_items[$i]['unit_price']);
            $arr_items[$i]['quantity'] = $this->form_validation->clear_currency($arr_items[$i]['quantity']);
            $this->invoice_detail_model->create($invoice_id, $arr_items[$i]['description'], $arr_items[$i]['quantity'], $arr_items[$i]['unit_price']);
        }
    }

    public function edit_items($invoice_id, $arr_items)
    {
        $countsize = count($arr_items);

        for ($i = 0; $i < $countsize; $i++)
        {
            $arr_items[$i]['unit_price'] = $this->form_validation->clear_currency($arr_items[$i]['unit_price']);
            $arr_items[$i]['quantity'] = $this->form_validation->clear_currency($arr_items[$i]['quantity']);
            $this->invoice_detail_model->update($invoice_id, $arr_items[$i]['invoice_detail_id'], $arr_items[$i]['description'], $arr_items[$i]['quantity'], $arr_items[$i]['unit_price']);
        }
    }

    public function get_invoice_id()
    {
        return $this->_invoice_id;
    }

    public function create($client_id, $date_issue, $due_date, $tax, $arr_items)
    {
        $data = array(
            'client_id' => $client_id,
            'date_issue' => $date_issue,
            'due_date' => $due_date,
            'tax' => $tax
        );

        if ($this->db->insert('invoice', $data))
        {
            $invoice_id = $this->db->insert_id();
            $this->add_items($invoice_id, $arr_items);
            $this->_invoice_id = $invoice_id;
            return true;
        }
        return false;
    }

    public function update($invoice_id, $date_issue, $due_date, $tax, $arr_items, $arr_invoice_entries_del)
    {

        $data = array(
            'date_issue' => $date_issue,
            'due_date' => $due_date,
            'tax' => $tax
        );


        $this->db->where('invoice_id', $invoice_id);



        self::edit_items($invoice_id, $arr_items);
        self::delete_items($invoice_id, $arr_invoice_entries_del);

        return $this->db->update('invoice', $data);
    }

    function get_count_invoices($client_id)
    {
        $this->db->from('invoice');
        $this->db->where('client_id', $client_id);
        return $this->db->count_all_results();
    }

    function get_invoice_number($invoice)
    {
        //0001-31072017-03
        $client_id = sprintf("%'.04d", $invoice['client_id']);
        $date_issue = dataBR($invoice['date_issue']);
        $date_issue = str_replace("/", "", $date_issue);
        // $sequence = sprintf("%'.04d", $invoice['sequence']);
        $sequence = sprintf("%'.04d", $invoice['invoice_id']);

        $number = $client_id . '-' . $date_issue . '-' . $sequence;
        return $number;
    }

    public function get_total_invoice($invoice_id)
    {

        $sql = "select sum(`quantity` * `unit_price`) as total from invoice_item where invoice_id = ?";

        $query = $this->db->query($sql, array($invoice_id));
        $row = $query->row();
        $total = money_format('%i', $row->total);
        return $total;
    }

    public function make_payment($invoice_id, $date_paid, $amount_paid)
    {

        $data = array(
            'date_transaction' => $date_paid,
            'amount_paid' => $amount_paid
        );

        $this->db->where('invoice_id', $invoice_id);
        return $this->db->update('invoice', $data);
    }
    
    
        public function make_void($invoice_id, $note)
    {

        $data = array(
            'voided' => 1,
            'note' => $note
        );

        $this->db->where('invoice_id', $invoice_id);
        return $this->db->update('invoice', $data);
    }

}
