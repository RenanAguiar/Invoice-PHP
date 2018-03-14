<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ApiProfile extends API_Controller {

    public function __construct()
    {
        parent::__construct();
        // $this->load->model('profile_model');
        $this->load->model('profile_model');

        $this->load->model('auth_token_model');
    }


    
    public function get()
    {
        $token = $_GET['token'];
        $user_id = $this->auth_token_model->get_user_id($token);

        $profile = $this->profile_model->get_profile($user_id);
        $sucess['sucess'] = "yes";
        //$sucess['token'] = "123";
        $arr = array("meta" => $sucess, "result" => array($profile));
        $this->render_json(200, $arr);
    }



    public function update()
    {



        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean, TRUE);
        $token = $this->input->get_request_header('Authorization', TRUE);
        $request["Authorization"] = $token;

        
        
        $data = json_decode(file_get_contents('php://input'), true);
        $file = 'invoice.txt';
        $current = file_get_contents($file);
        $current = print_r($data, true);
        file_put_contents($file, $current);
        
        
        $name = $data["name"];
        $phone = $data["phone"];
        $postal_code = $data["postal_code"];
        $province = $data["province"];
        $city = $data["city"];
        $address = $data["address"];
        $tax = $data["tax"];
 
        
        $user_id = $this->auth_token_model->get_user_id($token);


        $this->user_id = $user_id;


        if ($this->profile_model->update($user_id, $name, $phone, $postal_code, $province, $city, $address, $tax))
        //if ($this->client_model->create($this->user_id, $client))
        {
            // $client["client_id"]= (int)$this->db->insert_id();            
            $meta['sucess'] = "yes";
            $result[0]['error'] = "";
            $result[0]['token'] = "0";
            $arr = array("meta" => $meta, "result" => $result);
            $this->render_json(200, $arr);
            return TRUE;
        }

        if ($this->db->trans_status() === FALSE)
        {
            $meta['sucess'] = "no";
            $meta['token'] = "";
            $result[0]['error'] = "Error editing profile";
            $result[0]['token'] = "";
            $arr = array("meta" => $meta, "result" => $result);
            $this->render_json(400, $arr);
            //return FALSE;
        }
    }
    
    

    

}
