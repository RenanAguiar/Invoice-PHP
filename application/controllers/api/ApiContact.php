<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ApiContact extends API_Controller {

    public function __construct()
    {
        parent::__construct();
        // $this->load->model('profile_model');
        $this->load->model('client_model');
        $this->load->model('client_contact_model');
        $this->load->model('auth_token_model');
    }

    public function all()
    {

        $token = $_GET['token'];
        $client_id = $_GET['client_id'];


        $contacts = $this->client_contact_model->get_client_contacts($client_id);


        $sucess['sucess'] = "yes";
        $sucess['token'] = "123";
        $arr = array("meta" => $sucess, "result" => $contacts);
        $this->render_json(200, $arr);
    }

    public function add()
    {

        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean, TRUE);
        $token = $this->input->get_request_header('Authorization', TRUE);
        $request["Authorization"] = $token;
        $client["first_name"] = $request["first_name"];
        $client["last_name"] = $request["last_name"];
        $client["email"] = $request["email"];
        $client["phone"] = $request["phone"];
      //  $client["client_contact_id"] = $request["client_contact_id"];
        $client["client_id"] = $request["client_id"];
        
        
         $file = 'people.txt';
// Open the file to get existing content
        $current = file_get_contents($file);
// Append a new person to the file
        $current .=print_r($client, true);
// Write the contents back to the file
        file_put_contents($file, $current);
        
        
        $user_id = $this->auth_token_model->get_user_id($token);


        $this->user_id = $user_id;
//$client["user_id"] = $user_id;


        

        if ($this->client_contact_model->create($client))
        {
            $client["client_contact_id"] = (int) $this->db->insert_id();
            $meta['sucess'] = "yes";
            $result[0]['error'] = "";
            $result[0]['token'] = "0";
            $arr = array("meta" => $meta, "result" => array($client));
            $this->render_json(200, $arr);
            return TRUE;
        }


        $meta['sucess'] = "no";
        $meta['token'] = "";

        $result[0]['error'] = "Error creating user";
        $result[0]['token'] = "";
        //$result = array("error" => "Wrong username or password2.");
        $arr = array("meta" => $meta, "result" => $result);
        $this->render_json(400, $arr);
        return FALSE;
        //}
    }

    public function update()
    {



        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean, TRUE);
        $token = $this->input->get_request_header('Authorization', TRUE);
        $request["Authorization"] = $token;
        $client["first_name"] = $request["first_name"];
        $client["last_name"] = $request["last_name"];
        $client["email"] = $request["email"];
        $client["phone"] = $request["phone"];
        $client["client_contact_id"] = $request["client_contact_id"];
        $client["client_id"] = $request["client_id"];
        
        
 
        
        $user_id = $this->auth_token_model->get_user_id($token);


        $this->user_id = $user_id;


        if ($this->client_contact_model->update($this->user_id, $client))
        //if ($this->client_model->create($this->user_id, $client))
        {
            // $client["client_id"]= (int)$this->db->insert_id();            
            $meta['sucess'] = "yes";
            $result[0]['error'] = "";
            $result[0]['token'] = "0";
            $arr = array("meta" => $meta, "result" => array($client));
            $this->render_json(200, $arr);
            return TRUE;
        }

        if ($this->db->trans_status() === FALSE)
        {
            $meta['sucess'] = "no";
            $meta['token'] = "";
            $result[0]['error'] = "Error editing user";
            $result[0]['token'] = "";
            $arr = array("meta" => $meta, "result" => $result);
            $this->render_json(400, $arr);
            //return FALSE;
        }
    }
    
    
    public function delete($client_contact_id) {
        $client = array();
     if($this->client_contact_model->delete($client_contact_id))
                 {
            // $client["client_id"]= (int)$this->db->insert_id();            
            $meta['sucess'] = "yes";
            $result[0]['error'] = "";
            $result[0]['token'] = "0";
            $arr = array("meta" => $meta, "result" => array($client));
            $this->render_json(200, $arr);
            return TRUE;
        }
        
                if ($this->db->trans_status() === FALSE)
        {
            $meta['sucess'] = "no";
            $meta['token'] = "";
            $result[0]['error'] = "Error deleting contact";
            $result[0]['token'] = "";
            $arr = array("meta" => $meta, "result" => $result);
            $this->render_json(400, $arr);
            //return FALSE;
        }
         
       
        
    }

}
