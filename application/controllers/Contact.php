<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Contact extends Auth_controller {

    public $contact = array(
        'first_name' => '',
        'last_name' => '',
        'email' => '',
        'phone' => ''
    );

    public function __construct()
    {
        parent::__construct();
        $this->load->model('client_model');
        $this->load->model('client_contact_model');
    }

    public function create_contact_get()
    {
        echo "f";
        $this->data['modal_title'] = "New Contact";
        $this->data['modal_action'] = "create";
        $this->data['modal_content'] = form_open("") . $this->parser->parse('client/contact_form', $this->contact, TRUE) . form_close();
        $this->render_modal();
    }

    public function edit_contact_get()
    {
        $contacts['contact_entries'] = $this->client_contact_model->get_client_contact($this->input->get('client_contact_id'));
        $this->data['modal_title'] = "Edit Contact";
        $this->data['modal_action'] = "edit";
        $this->data['modal_content'] = form_open("") . $this->parser->parse('client/contact_form', $contacts, TRUE) . form_close();
        $this->render_modal();
    }

    public function create_contact_post()
    {
        $this->form_validation->set_rules($this->client_contact_model->rules['create']);

        if ($this->form_validation->run() == FALSE)
        {

            // validation not ok, send validation errors to the view
            $this->output
                    ->set_content_type('application/json')
                    ->set_status_header('400')
                    ->set_output(json_encode($this->form_validation->error_array()));
            // ->_display();
            return;
        }

        $this->client_contact_model->create($this->input->post());


        $client_contact_id = $this->db->insert_id();

        $this->output
                ->set_content_type('application/json')
                ->set_status_header('200')
                ->set_output(json_encode(array('client_contact_id' => $client_contact_id))); //->_display();
    }

    public function edit_contact_post()
    {

        $this->form_validation->set_rules($this->client_contact_model->rules['update']);

        if ($this->form_validation->run() === FALSE)
        {
            $this->output
                    ->set_content_type('application/json')
                    ->set_status_header('400')
                    ->set_output(json_encode($this->form_validation->error_array()));
            return;
        }

        $client_contact_id = $this->client_contact_model->edit($this->input->post());

        $this->output
                ->set_content_type('application/json')
                ->set_status_header('200')
                ->set_output(json_encode(array('client_contact_id' => $client_contact_id))); //->_display();
    }

    public function delete_contact()
    {

        if ($this->input->method() !== "delete")
        {
            $this->output
                    ->set_content_type('application/json')
                    ->set_status_header('400')
                    ->set_output(json_encode(array('error' => true)));
            return;
        }


        $client_contact_id = $this->input->input_stream('client_contact_id', TRUE); // XSS Clean


        $this->client_contact_model->delete($client_contact_id);

        $this->output
                ->set_content_type('application/json')
                ->set_status_header('200')
                ->set_output(json_encode(array('client_contact_id' => $client_contact_id)));
        //->_display();
    }

    public function get_client_contact()
    {

        $client_contact_id = $this->input->get('client_contact_id');
        $contacts['contact_entries'] = $this->client_contact_model->get_client_contact($client_contact_id);
        $html = $this->parser->parse('client/contact_details', $contacts, TRUE);

        $this->output
                ->set_content_type('application/json')
                ->set_status_header('200')
                //   ->set_output(json_encode('sucess'));
                ->set_output(json_encode(array('html' => $html)));
    }

}
