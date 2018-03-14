<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ApiInvoice extends API_Controller {

    public function __construct()
    {
        parent::__construct();
        // $this->load->model('profile_model');
        $this->load->model('client_model');
        $this->load->model('invoice_model');
        $this->load->model('invoice_detail_model');
        $this->load->model('auth_token_model');
    }

//        public function delete($client_contact_id)
//    {
//
//        $token = $this->input->post('token');
//        $user_id = $this->auth_token_model->get_user_id($token);
//        $this->user_id = $user_id;
//
//
//        $contact = $this->client_contact_model->get_client_contact($client_contact_id);
//        if(count($contact) == 0) 
//        {
//            $this->render_json(404, NULL);
//        }
//        
//        if ($this->client_contact_model->delete($client_contact_id))
//        {
//            $this->render_json(200, NULL);
//        }
//        
//        else
//        {
//            $this->render_json(409, NULL);
//        }
//    }

    public function all()
    {
        $token = $_GET['token'];
        $client_id = $_GET['client_id'];

        $invoices = $this->invoice_model->get_invoices($client_id);

        $sucess['sucess'] = "yes";
        $sucess['token'] = "123";
        $arr = array("meta" => $sucess, "result" => $invoices);
        $this->render_json(200, $arr);
    }

    public function add()
    {

        $data = json_decode(file_get_contents('php://input'), true);

        $client["date_issue"] = $data["date_issue"];
        $client["due_date"] = $data["due_date"];
        $client["client_id"] = $data["client_id"];
        $client["tax"] = $data["tax"];
        $client["items"] = $data["items"];

        $token = $this->input->get_request_header('Authorization', TRUE);
        $user_id = $this->auth_token_model->get_user_id($token);
        $this->user_id = $user_id;




        if ($this->invoice_model->create($client["client_id"], $client["date_issue"], $client["due_date"], $client["tax"], $client["items"]))
        {
            // $client["invoice_id"] = (int) $this->db->insert_id();
            // $client["invoice_number"] = $this->invoice_model->get_invoice_number($client);

            $invoice = $this->invoice_model->get_invoice($this->db->insert_id());
            // var_dump($invoice);
            $meta['sucess'] = "yes";
            $result[0]['error'] = "";
            $result[0]['token'] = "0";
            $arr = array("meta" => $meta, "result" => array($invoice));
            $this->render_json(200, $arr);
            return TRUE;
        }


        $meta['sucess'] = "no";
        $meta['token'] = "";

        $result[0]['error'] = "Error creating invoice";
        $result[0]['token'] = "";
        //$result = array("error" => "Wrong username or password2.");
        $arr = array("meta" => $meta, "result" => $result);
        $this->render_json(400, $arr);
        return FALSE;
        //}
    }

    public function create_item()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $file = 'invoice.txt';
        $current = file_get_contents($file);
        $current = print_r($data, true);
        file_put_contents($file, $current);


        $invoice_id = $data["invoice_id"];
        $description = $data["description"];
        $quantity = $data["quantity"];
        $unit_price = $data["unit_price"];


        if ($this->invoice_detail_model->create($invoice_id, $description, $quantity, $unit_price))
        {
            $data["invoice_detail_id"] = (int) $this->db->insert_id();
            // $client["invoice_number"] = $this->invoice_model->get_invoice_number($client);

            $meta['sucess'] = "yes";
            $result[0]['error'] = "";
            $result[0]['token'] = "0";
            $arr = array("meta" => $meta, "result" => array($data));
            $this->render_json(200, $arr);
            return TRUE;
        }


        $meta['sucess'] = "no";
        $meta['token'] = "";

        $result[0]['error'] = "Error creating item";
        $result[0]['token'] = "";
        //$result = array("error" => "Wrong username or password2.");
        $arr = array("meta" => $meta, "result" => $result);
        $this->render_json(400, $arr);
        return FALSE;
    }

    public function update_item()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $file = 'invoice.txt';
        $current = file_get_contents($file);
        $current = print_r($data, true);
        file_put_contents($file, $current);


        $invoice_detail_id = $data["invoice_detail_id"];
        $invoice_id = $data["invoice_id"];
        $description = $data["description"];
        $quantity = $data["quantity"];
        $unit_price = $data["unit_price"];


        if ($this->invoice_detail_model->update($invoice_detail_id, $invoice_id, $description, $quantity, $unit_price))
        {
            //  $data["invoice_detail_id"] = (int) $this->db->insert_id();
            // $client["invoice_number"] = $this->invoice_model->get_invoice_number($client);

            $meta['sucess'] = "yes";
            $result[0]['error'] = "";
            $result[0]['token'] = "0";
            $arr = array("meta" => $meta, "result" => array($data));
            $this->render_json(200, $arr);
            return TRUE;
        }


        $meta['sucess'] = "no";
        $meta['token'] = "";

        $result[0]['error'] = "Error updating item";
        $result[0]['token'] = "";
        //$result = array("error" => "Wrong username or password2.");
        $arr = array("meta" => $meta, "result" => $result);
        $this->render_json(400, $arr);
        return FALSE;
    }

    public function delete_item($invoice_detail_id)
    {

        if ($this->invoice_detail_model->delete($invoice_detail_id))
        {
            $this->render_json(200, NULL);
        }
        else
        {
            $this->render_json(409, NULL);
        }
    }

    public function make_payment()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $file = 'invoice2.txt';
        $current = file_get_contents($file);
        $current = print_r($data, true);
        file_put_contents($file, $current);

        $date_paid = $data["date_transaction"];
        $invoice_id = $data["invoice_id"];
        $amount_paid = $data["amount_paid"];

        //  $date_paid = $this->form_validation->make_date_db($date_paid);

        if ($this->invoice_model->make_payment($invoice_id, $date_paid, $amount_paid))
        {
            $data["invoice_id"] = (int) $data["invoice_id"];
            $data["amount_paid"] = (double) $data["amount_paid"];
            $data["status"] = "Paid";
            $meta['sucess'] = "yes";
            $result[0]['error'] = "";
            $result[0]['token'] = "0";
            $arr = array("meta" => $meta, "result" => array($data));
            $this->render_json(200, $arr);
            return TRUE;
        }

        $meta['sucess'] = "no";
        $meta['token'] = "";

        $result[0]['error'] = "Error creating item";
        $result[0]['token'] = "";
        //$result = array("error" => "Wrong username or password2.");
        $arr = array("meta" => $meta, "result" => $result);
        $this->render_json(400, $arr);
        return FALSE;
    }
    
    
    
    
        public function void_invoice()
    {
        $data = json_decode(file_get_contents('php://input'), true);

        $file = 'invoice2.txt';
        $current = file_get_contents($file);
        $current = print_r($data, true);
        file_put_contents($file, $current);

        $note = $data["note"];
        $invoice_id = $data["invoice_id"];

        //  $date_paid = $this->form_validation->make_date_db($date_paid);

        if ($this->invoice_model->make_void($invoice_id, $note))
        {
            $data["invoice_id"] = (int) $data["invoice_id"];
            $data["status"] = "Voided";
            $meta['sucess'] = "yes";
            $result[0]['error'] = "";
            $result[0]['token'] = "0";
            $arr = array("meta" => $meta, "result" => array($data));
            $this->render_json(200, $arr);
            return TRUE;
        }

        $meta['sucess'] = "no";
        $meta['token'] = "";

        $result[0]['error'] = "Error voiding invoice";
        $result[0]['token'] = "";
        //$result = array("error" => "Wrong username or password2.");
        $arr = array("meta" => $meta, "result" => $result);
        $this->render_json(400, $arr);
        return FALSE;
    }

    
    
    
    
        public function update()
    {
        $data = json_decode(file_get_contents('php://input'), true);
        $file = 'invoice.txt';
        $current = file_get_contents($file);
        $current = print_r($data, true);
        file_put_contents($file, $current);


        $invoice_id = $data["invoice_id"];
        $date_issue = $data["date_issue"];
        $due_date = $data["due_date"];
        $tax = $data["tax"];
            
            


        if ($this->invoice_model->update($invoice_id, $date_issue, $due_date, $tax, null, null))
        {
           $invoice = $this->invoice_model->get_invoice($invoice_id);
           $meta['sucess'] = "yes";
            $result[0]['error'] = "";
            $result[0]['token'] = "0";
            $arr = array("meta" => $meta, "result" => array($invoice));
            $this->render_json(200, $arr);
            return TRUE;
        }


        $meta['sucess'] = "no";
        $meta['token'] = "";

        $result[0]['error'] = "Error updating invoice";
        $result[0]['token'] = "";
        //$result = array("error" => "Wrong username or password2.");
        $arr = array("meta" => $meta, "result" => $result);
        $this->render_json(400, $arr);
        return FALSE;
    }
}
