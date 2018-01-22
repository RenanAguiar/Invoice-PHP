<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ApiClient extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('profile_model');
        $this->load->model('client_model');
        $this->load->model('client_contact_model');
        $this->load->model('auth_token_model');
    }

    public function all()
    {

        $token = $_GET['token'];
        $user_id = $this->auth_token_model->get_user_id($token);
      
        $clients = $this->client_model->get_clients($user_id);
        $sucess['sucess'] = "yes";
        $sucess['token'] = "123";
        $arr = array("meta" => $sucess, "result" => $clients);
        $this->render_json(200, $arr);
    }

    public function gg()
    {
        $client = $this->client_model->get_client(63);
        $sucess['sucess'] = "yes";
        $sucess['token'] = "123";
        $arr = array("meta" => $sucess, "result" => $client);

        $this->render_json(200, $arr);
    }

    public function ggg()
    {

        $current = file_get_contents("json2.json");
        $sucess['sucess'] = "yes";
        $sucess['token'] = "123";

        $arr = array("meta" => $sucess, "breweries" => $current);



        $this->render_json(200, $arr);
    }
    
    
    
    
    
    
     public function add()
    {

        $data = json_decode(file_get_contents('php://input'), true);

        $file = 'people.txt';
// Open the file to get existing content
        $current = file_get_contents($file);
// Append a new person to the file
        $current .=print_r($data, true);
// Write the contents back to the file
        file_put_contents($file, $current);


        $client["name"]= $data["name"];
        $client["address"]= $data["address"];
        $client["city"]= $data["city"];
        $client["province"]= $data["province"];
        $client["postal_code"]= $data["postal_code"];
        
        $token = $this->input->get_request_header('Authorization', TRUE); 
        $user_id = $this->auth_token_model->get_user_id($token);
        $this->user_id = $user_id;
        
        
        if ($this->client_model->create($this->user_id, $client))
        {           
          $client["client_id"]= (int)$this->db->insert_id();            
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

        $data = json_decode(file_get_contents('php://input'), true);

        $file = 'people.txt';
// Open the file to get existing content
        $current = file_get_contents($file);
// Append a new person to the file
        $current .=print_r($data, true);
// Write the contents back to the file
        file_put_contents($file, $current);


        $client["name"]= $data["name"];
        $client["address"]= $data["address"];
        $client["city"]= $data["city"];
        $client["province"]= $data["province"];
        $client["postal_code"]= $data["postal_code"];
        $client["client_id"]= $data["client_id"];
                
     //   $this->user_id = 1;
                $token = $this->input->get_request_header('Authorization', TRUE); 
        $user_id = $this->auth_token_model->get_user_id($token);
        $this->user_id = $user_id;

        if ($this->client_model->update($this->user_id, $client))
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
            return FALSE;
        }


    }  
    
}