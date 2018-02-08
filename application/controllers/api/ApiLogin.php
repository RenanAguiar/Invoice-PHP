<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class ApiLogin extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('profile_model');
        $this->load->model('client_model');
        $this->load->model('client_contact_model');
        $this->load->model('auth_token_model');
    }

//    public function index()
//    {
//
//
//
//        $this->data['error'] = $this->session->flashdata('error');
//        $this->data['previous_page'] = $this->session->flashdata('previous_page');
//        $this->data['pagebody'] = 'login/login';
//        $this->render();
//    }

    public function sign_up()
    {

       // $data = json_decode(file_get_contents('php://input'), true);
        
        
        $stream_clean = $this->security->xss_clean($this->input->raw_input_stream);
        $request = json_decode($stream_clean, TRUE);
      //  $token = $this->input->get_request_header('Authorization', TRUE);
        
        

//        $file = 'people.txt';
//// Open the file to get existing content
//        $current = file_get_contents($file);
//// Append a new person to the file
//        $current .=print_r($request, true);
//// Write the contents back to the file
//        file_put_contents($file, $current);


        $email = $request["email"];
        $password = $request["password"];
        $password_c = $request["passwordConfirmation"];
        $name = $request["businessName"];


//$password_c = $data["password_c"];

        $this->db->trans_start();

        $this->user_model->create($email, $password);
        $user_id = $this->db->insert_id();

        $this->profile_model->create($user_id, $name);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
        {
            //if something went wrong, rollback everything
            $this->db->trans_rollback();
            $meta['sucess'] = "no";
           // $meta['token'] = "";

            $result[0]['error'] = "Error creating user";
            $result[0]['token'] = "";
            //$result = array("error" => "Wrong username or password2.");
            $arr = array("meta" => $meta, "result" => $result);
            $this->render_json(400, $arr);
            return FALSE;
        }

        $this->db->trans_commit();
        $meta['sucess'] = "yes";
        $meta['token'] = "ff";



        //  $result[0]['error'] = "";
        //   $result[0]['token'] = "hh";


//        $result[0]["email"] = $email;
//        $result[0]["password"] = $password;
//        $result[0]["passwordConfirmation"] = $password_c;
//        $result[0]["businessName"] = $name;
        
        $this->auth_token_model->create($user_id);
        
         $result[0]['error'] = "no";
            $result[0]['token'] = $this->auth_token_model->get_token();
            
            
        $arr = array("meta" => $meta, "result" => $result);

       // $this->render_json(200, $arr);
        
        
        
        
        
        
        
            $this->auth_token_model->create($user_id);
             
            $meta['sucess'] = "yes";
            $meta['token'] = $this->auth_token_model->get_token();



            $result[0]['error'] = "";
            $result[0]['token'] = $this->auth_token_model->get_token();

            $arr = array("meta" => $meta, "result" => $result);

            $this->render_json(200, $arr);
            //$this->render_json(200, $arr);
           // return TRUE;
        // return TRUE;
    }

    public function do_login()
    {

        
        $this->form_validation->set_rules($this->user_model->rules['do_login']);
        $data = json_decode(file_get_contents('php://input'), true);
        $login_email = $data["email"];
        $login_password = $data["password"];

       // $this->auth_token_model->create(1);

        if ($this->user_model->login($login_email, $login_password))
        {

            $user_id = $this->user_model->get_user_by_email($login_email);
            $user = $this->user_model->get_user($user_id);

             $this->auth_token_model->create($user_id);
             
            $meta['sucess'] = "yes";
           // $meta['message'] = $this->auth_token_model->get_token();



            $result[0]["field"] = "token";
            $result[0]["message"] = $this->auth_token_model->get_token();

            $arr = array("meta" => $meta, "result" => $result);

            $this->render_json(200, $arr);
            //$this->render_json(200, $arr);
            return TRUE;
        }
        $meta['sucess'] = "no";
        $meta['message'] = "Wrong username or password";
   
        

        
        
$result[0]["field"] = "gggg";
$result[0]["message"] = "33";        
$result[1]["field"] = "ff";
$result[1]["message"] = "400";

        $arr = array("meta" => $meta, "result" => $result);
        $this->render_json(400, $arr);
        return FALSE;
    }



    public function logout()
    {
        $this->session->userdata = array();
        $this->session->sess_destroy();
        redirect('', 'refresh');
    }

}
