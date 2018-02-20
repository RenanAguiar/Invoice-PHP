<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Invoice extends Auth_controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('client_model');
        $this->load->model('invoice_model');
        $this->load->model('invoice_detail_model');
        $this->load->model('profile_model');

        $this->load->helper('date');
        $this->form_validation->set_message('date_control', '%s Date Special Error');

        $this->data['extra_js'] = '<script type="text/javascript" src="' . base_url() . 'assets/js/invoice.js"></script>' . "\n";

    }

    public function index($client_id = 0)
    {
        $invoices = $this->invoice_model->get_invoices($this->user_id, $client_id);
        $this->data['invoice_entries'] = $invoices;
        $this->data['pagebody'] = 'invoice/index';
        $this->render();
    }

    public function get_invoice($invoice_id)
    {
        $invoice = $this->invoice_model->get_invoice($invoice_id);

        if ($invoice == NULL)
        {
            show_error('Invoice not found', 404, '');
        }

        return $invoice;
    }

    public function details($invoice_id)
    {

        if ($this->session->flashdata('success') === TRUE)
        {
            $this->set_toaster("success", "Invoice saved.");
        }

        $invoice = self::get_invoice($invoice_id);

        $this->data['client_id'] = $invoice->client_id;
        $this->data['date_issue'] = dataBR($invoice->date_issue);
        $this->data['due_date'] = dataBR($invoice->due_date);
        $this->data['amount_paid'] = $invoice->amount_paid;
        $this->data['date_transaction'] = $invoice->date_transaction;
        $this->data['invoice_number'] = $invoice->invoice_number;

        self::_set_client($invoice->client_id);


        $total_invoice = $this->invoice_model->get_total_invoice($invoice_id);
        $this->data['total_invoice'] = $total_invoice;


        $contacts['invoice_item_entries'] = self::_get_invoice_items($invoice_id);


        $this->data['invoice_id'] = $invoice_id;
        $this->data['invoice_message'] = $this->session->flashdata('invoice_created');

        $this->data['invoice_item_entries'] = $this->parser->parse('invoice/invoice_items', $contacts, TRUE);

        
        self::_set_js_restrictions($invoice);
        


        $this->data['pagebody'] = 'invoice/details';
        $this->render();
    }
    
    private function _set_js_restrictions($invoice)
    {
        if(!is_null($invoice->date_transaction))
        {
            $this->data['class_void'] = $invoice->voided ? 'disabled' : '';
            $this->data['class_pay']  = $invoice->voided ? 'disabled' : '';
            $this->data['class_reminder'] = $invoice->voided ? 'disabled' : ''; 
        }
        
       
    }
    
    public function create($client_id = FALSE)
    {
        $method = $this->input->method(TRUE);
        if ($method === "POST")
        {
            self::create_post();
        }
        else
        {
            self::create_get($client_id);
        }
    }

    private function _set_client($client_id)
    {
        $client = (object)$this->client_model->get_client($client_id)[0];

        if ($client === NULL)
        {
            show_error('Client not found', 404, '');
        }

        if ($this->user_id != $client->user_id)
        {
            show_error('Not Allowed', 403, '');
        }

        $this->data['client_id'] = $client->client_id;
        $this->data['name'] = $client->name;
        $this->data['postal_code'] = $client->postal_code;
        $this->data['province'] = $client->province;
        $this->data['city'] = $client->city;
        $this->data['address'] = $client->address;
    }

    private function _set_profile()
    {

        $profile = $this->profile_model->get_profile($this->user_id);

        $this->data['profile_name'] = $profile->name;
        $this->data['profile_phone'] = $profile->phone;
        $this->data['profile_postal_code'] = $profile->postal_code;
        $this->data['profile_province'] = $profile->province;
        $this->data['profile_city'] = $profile->city;
        $this->data['profile_address'] = $profile->address;
        $this->data['profile_gst'] = $profile->gst;
    }

    public function create_get($client_id)
    {

        self::_set_client($client_id);
        self::_set_profile();

        $this->data['pagebody'] = 'invoice/create';
        self::create_invoice_details_get();


        $this->render();
    }

    private function _get_invoice_items($invoice_id)
    {

        //use of reference on array and foreach
        $arr_details = $this->invoice_detail_model->get_invoice_details($invoice_id);

        foreach ($arr_details as &$detail)
        {
            $detail['total_line'] = money_format('%i', $detail['unit_price'] * $detail['quantity']);
        }

        return $arr_details;
    }

    public function val_item()
    {

        foreach ($arr_description as $ind => $item)
        {
            $description = $arr_description[$ind];
            $quantity = $arr_quantity[$ind];
            $unit_price = $arr_unit_price[$ind];

            $this->invoice_detail_model->set_rules($ind);
            $invoice_item_rules = $this->invoice_detail_model->rules['update'];
            $this->form_validation->set_rules($invoice_item_rules);
        }
    }

    private function _set_items()
    {
        $arr_items = array();
        $arr_invoice_detail_id = $this->input->post('invoice_detail_id');
        $arr_description = $this->input->post('description');
        $arr_quantity = $this->input->post('quantity');
        $arr_unit_price = $this->input->post('unit_price');


        foreach ($arr_description as $ind => $description)
        {
            $arr_items[$ind]['invoice_detail_id'] = $arr_invoice_detail_id[$ind];
            $arr_items[$ind]['description'] = $description;
            $arr_items[$ind]['quantity'] = $arr_quantity[$ind];
            $arr_items[$ind]['unit_price'] = $arr_unit_price[$ind];
        }

        return $arr_items;
    }

    public function create_post()
    {
        $invoice_rules = $this->invoice_model->rules['update'];
        $this->form_validation->set_rules($invoice_rules);
        $client_id = $this->input->post('client_id');

        $arr_items = self::_set_items();

        $this->invoice_detail_model->validade_items($arr_items);


        if ($this->form_validation->run() === TRUE)
        {
            $tax = $this->input->post('tax');

            $date_issue = $this->form_validation->make_date_db($this->input->post('date_issue'));
            $due_date = $this->form_validation->make_date_db($this->input->post('due_date'));


            if ($this->invoice_model->create($client_id, $date_issue, $due_date, $tax, $arr_items))
            {
                $invoice_id = $this->invoice_model->get_invoice_id();
                $this->session->set_flashdata('invoice_created', "Invoice created!");
                redirect('/invoice/details/' . $invoice_id);
            }
        }

        $this->set_toaster("error", "There was a problem creating this invoice. Please try again.");
        self::_set_client($client_id);
        self::_set_profile();
        self::create_invoice_details_get($arr_items);

        $this->data['client_id'] = $client_id;
        $this->data['pagebody'] = 'invoice/create';
        $this->render();
    }

    public function create_invoice_details_get($arr_items = NULL)
    {

        $countsize = count($arr_items);
        if ($countsize > 0)
        {
            for ($i = 0; $i < $countsize; $i++)
            {
                $total = $this->invoice_detail_model->get_total_line($arr_items[$i]['quantity'], $arr_items[$i]['unit_price']);

                $data[] = array(
                    "invoice_detail_id" => $arr_items[$i]['invoice_detail_id'],
                    "invoice_id" => '',
                    "description" => $arr_items[$i]['description'],
                    "quantity" => $arr_items[$i]['quantity'],
                    "unit_price" => $arr_items[$i]['unit_price'],
                    "total_line" => $total,
                    "description_error" => form_error("description[{$i}]")
                );
            }
        }
        else
        {

            $data[] = array(
                "invoice_detail_id" => '',
                "description" => '',
                "quantity" => '',
                "unit_price" => '',
                "total" => '',
                "total_line" => '',
                "description_error" => ''
            );
        }
        $this->data['invoice_entries'] = $data;
    }

    public function edit_invoice_details_get($invoice_id = NULL)
    {



        $arr_items = self::_get_invoice_items($invoice_id);


        $countsize = count($arr_items);
        if ($countsize > 0)
        {
            for ($i = 0; $i < $countsize; $i++)
            {
                $total = $this->invoice_detail_model->get_total_line($arr_items[$i]['quantity'], $arr_items[$i]['unit_price']);

                $data[] = array(
                    "invoice_detail_id" => $arr_items[$i]['invoice_detail_id'],
                    "invoice_id" => '',
                    "description" => $arr_items[$i]['description'],
                    "quantity" => $arr_items[$i]['quantity'],
                    "unit_price" => $arr_items[$i]['unit_price'],
                    "total_line" => $total,
                    "description_error" => ""
                );
            }
        }

        $this->data['invoice_entries'] = $data;
    }

    public function edit($invoice_id = false)
    {

        if ($this->input->method(TRUE) === "POST")
        {
            self::edit_post($invoice_id);
        }
        else
        {
            self::edit_get($invoice_id);
        }
    }

    public function edit_get($invoice_id)
    {
        //  $client = $this->_get_client($client_id);
        $invoice = self::get_invoice($invoice_id);




        $this->data['client_id'] = $invoice->client_id;
        $this->data['date_issue'] = dataBR($invoice->date_issue);
        $this->data['due_date'] = dataBR($invoice->due_date);
        $this->data['amount_paid'] = $invoice->amount_paid;
        $this->data['date_paid'] = $invoice->date_paid;
        $this->data['invoice_number'] = $invoice->invoice_number;

        self::_set_client($invoice->client_id);


        $total_invoice = $this->invoice_model->get_total_invoice($invoice_id);
        $this->data['total_invoice'] = $total_invoice;





        //  $contacts['invoice_item_entries'] = self::_get_invoice_items($invoice_id);
        //   $this->data['invoice_item_entries'] = $this->parser->parse('invoice/invoice_items', $contacts, TRUE);


        $contacts['invoice_item_entries'] = self::edit_invoice_details_get($invoice_id);
        $this->data['invoice_item_entries'] = $this->parser->parse('invoice/invoice_items', $contacts, TRUE);


        $this->data['invoice_entries_del'] = NULL;



        $this->data['invoice_id'] = $invoice_id;
        $this->data['invoice_message'] = $this->session->flashdata('invoice_created');




        self::_set_profile();





        $this->data['pagebody'] = 'invoice/edit';
        $this->render();
    }

    public function edit_post($invoice_id)
    {

        $this->form_validation->set_rules($this->invoice_model->rules['update']);
        $arr_items = self::_set_items();
        $this->invoice_detail_model->validade_items($arr_items);


        if ($this->form_validation->run() === TRUE)
        {
            //$this->_get_client($this->input->post('client_id'));
            //$client = get_client($client_id);

            $arr_invoice_entries_del = explode(",", $this->input->post('invoice_entries_del'));


            $date_issue = $this->input->post('date_issue');
            $due_date = $this->input->post('due_date');
            $tax = $this->input->post('tax');

            $date_issue = $this->form_validation->make_date_db($date_issue);
            $due_date = $this->form_validation->make_date_db($due_date);

            if ($this->invoice_model->update($invoice_id, $date_issue, $due_date, $tax, $arr_items, $arr_invoice_entries_del))
            {
                $this->session->set_flashdata('success', TRUE);
                redirect('/invoice/details/' . $this->input->post('invoice_id'));
            }
        }


        self::_set_client($this->input->post('client_id'));
        self::_set_profile();




        self::create_invoice_details_get($arr_items);

        $this->data['invoice_entries_del'] = $this->input->post('invoice_entries_del');

        $this->set_toaster("error", "There was a problem editing this invoice. Please try again.");

        $this->data['invoice_id'] = $this->input->post('invoice_id');
        $this->data['client_id'] = $this->input->post('client_id');
        $this->data['pagebody'] = 'invoice/edit';
        $this->render();
    }

    public function payment()
    {

        $invoice_id = $this->input->get('invoice_id');
        $this->data['modal_title'] = "New Payment";
        $this->data['modal_action'] = "pay";
        $data['invoice_id'] = $invoice_id;

        $total_invoice = $this->invoice_model->get_total_invoice($invoice_id);
        $data['amount_paid'] = $total_invoice;


        $this->data['modal_content'] = form_open("") . $this->parser->parse('invoice/payment_form', $data, TRUE) . form_close();
        $this->render_modal();
    }

    public function make_payment()
    {
        $date_paid = $this->input->post('date_paid');
        $invoice_id = $this->input->post('invoice_id');
        $amount_paid = $this->input->post('amount_paid');

        $date_paid = $this->form_validation->make_date_db($date_paid);

        if ($this->invoice_model->make_payment($invoice_id, $date_paid, $amount_paid))
        {
            
        }
    }
    
    
    
    
        public function void_invoice()
    {

        $invoice_id = $this->input->get('invoice_id');
        $this->data['modal_title'] = "Void Invoice";
        $this->data['modal_action'] = "void_invoice";
        $data['invoice_id'] = $invoice_id;

        $total_invoice = $this->invoice_model->get_total_invoice($invoice_id);
        $data['amount_paid'] = $total_invoice;
        $data['note'] = "";


        $this->data['modal_content'] = form_open("") . $this->parser->parse('invoice/void_form', $data, TRUE) . form_close();
        $this->render_modal();
    }

    public function make_void()
    {
        $invoice_id = $this->input->post('invoice_id');
        $note = $this->input->post('note');
        if ($this->invoice_model->make_void($invoice_id, $note))
        {
            
        }
    }

}
