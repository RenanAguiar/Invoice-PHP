<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Client extends Auth_controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('client_model');
        $this->load->model('client_contact_model');
        $this->load->model('invoice_model');
        $this->load->helper('client');
        $this->data['extra_js'] = '<script type="text/javascript" src="' . base_url() . 'assets/js/client.js"></script>' . "\n";
    }

    public function index()
    {
        $clients = $this->client_model->get_clients($this->user_id);
        $this->data['client_entries'] = $clients;
        $this->data['pagebody'] = 'client/index';
        $this->render();
    }

    /**
     * Shows the client detail page with information about the client, contact(s)
     * and invoice(s)
     * 
     * @param $client_id The client id
     */
    public function details($client_id)
    {
        $client = get_client($client_id)[0];

        if ($this->session->flashdata('success') === TRUE)
        {
            $this->set_toaster("success", "Client saved.");
        }

        $this->data += (array) $client;

        $contacts['contact_entries'] = $this->client_contact_model->get_client_contacts($client_id);
        $this->data['contact_entries'] = $this->parser->parse('client/contact_details', $contacts, TRUE);

        $arr_invoices = $this->invoice_model->get_invoices($client_id);

        foreach ($arr_invoices as &$invoice)
        {
            $invoice['date_issue'] = dataBR($invoice['date_issue']);
            $invoice['due_date'] = dataBR($invoice['due_date']);
        }

        $invoices['invoice_entries'] = $arr_invoices;
        $this->data['invoice_entries'] = $this->parser->parse('invoice/invoice_details', $invoices, TRUE);

        $this->data['pagebody'] = 'client/details';
        $this->render();
    }

    /**
     * Checks the create method and calls the appropriate function
     *
     */
    public function create()
    { 
        if ($this->input->method(TRUE) === "POST")
        {
            self::create_post();
        }
        else
        {
            self::create_get();
        }
    }

    /**
     * Shows the client create page (form)
     * 
     */
    public function create_get()
    {
        $this->data['name'] = "";
        $this->data['address'] = "";
        $this->data['city'] = "";
        $this->data['province'] = "";
        $this->data['postal_code'] = "";

        $this->data['pagebody'] = 'client/create';
        $this->render();
    }

    /**
     * Process the client form 
     * 
     */
    public function create_post()
    {
        $this->form_validation->set_rules($this->client_model->rules['create']);

        if ($this->form_validation->run() === TRUE)
        {
            //if created, shows details page
            if ($this->client_model->create($this->user_id, $this->input->post()))
            {                
                $this->session->set_flashdata('success', TRUE);
                redirect('/client/details/' . $this->db->insert_id());
            }
        }

        //if fails, shows create page (form)
        $this->set_toaster("error", "There was a problem creating this client. Please try again.");
        $this->data['pagebody'] = 'client/create';
        $this->render();
    }

    /**
     * Checks the edit method and calls the appropriate function
     *
     */
    public function edit($client_id = FALSE)
    {
        if ($this->input->method(TRUE) === "POST")
        {
            self::edit_post();
        }
        else
        {
            self::edit_get($client_id);
        }
    }

     /**
     * Shows the client edit page (form)
     * 
     */  
    public function edit_get($client_id)
    {

        $client = get_client($client_id);
        $this->data += (array) $client;
        $this->data['pagebody'] = 'client/edit';
        $this->render();
    }

    /**
     * Process the client form 
     * 
     */
    public function edit_post()
    {

        $this->form_validation->set_rules($this->client_model->rules['update']);

        if ($this->form_validation->run() === TRUE)
        {
            //$this->_get_client($this->input->post('client_id'));
            $client = get_client($this->input->post('client_id'));

            //if updated, shows details page
            if ($this->client_model->update($this->user_id, $this->input->post()))
            {
                $this->session->set_flashdata('success', TRUE);
                redirect('/client/details/' . $this->input->post('client_id'));
            }
        }

        //if fails, shows edit page (form)
        $this->set_toaster("error", "There was a problem editing this client. Please try again.");
        $this->data['client_id'] = $this->input->post('client_id');
        $this->data['pagebody'] = 'client/edit';
        $this->render();
    }

}
