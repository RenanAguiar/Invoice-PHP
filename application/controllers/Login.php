<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Login extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('profile_model');
    }

    public function index()
    {


        
        $this->data['error'] = $this->session->flashdata('error');
        $this->data['previous_page'] = $this->session->flashdata('previous_page');
        $this->data['pagebody'] = 'login/login';
        $this->render();
    }

    public function sign_up()
    {
        $this->form_validation->set_rules($this->user_model->rules['sign_up']);

        if ($this->form_validation->run() === FALSE)
        {
            // validation not ok, send validation errors to the view
            $error = $this->form_validation->error_array();
            $error['error'] = "Error creating user.";
            $this->render_json(400, $error);
            return FALSE;
        }

        $this->db->trans_start();

        $this->user_model->create($this->input->post('email'), $this->input->post('password'));
        $user_id = $this->db->insert_id();
        $this->profile_model->create($user_id, $this->input->post('name'));

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
        {
            //if something went wrong, rollback everything
            $this->db->trans_rollback();
            $error['error'] = "Error creating user.";
            $this->render_json(400, $error);
            return FALSE;
        }

        $this->db->trans_commit();
        return TRUE;
    }

    public function do_login()
    {
         $this->form_validation->set_rules($this->user_model->rules['do_login']);


        if ($this->form_validation->run() === FALSE)
        {
            $error = $this->form_validation->error_array();
            $error['error'] = "Wrong username or password.";
            $this->render_json(400, $error);
            return FALSE;
        }

        if ($this->user_model->login($this->input->post('login_email'), $this->input->post('login_password')))
        {

            $user_id = $this->user_model->get_user_by_email($this->input->post('login_email'));
            $user = $this->user_model->get_user($user_id);

            $this->session->set_userdata('user_id', (int) $user->user_id);
            $this->session->set_userdata('logged_in', (bool) TRUE);
            $this->render_json(200, 'sucess');
            return TRUE;
        }

        $error = array("error" => "Wrong username or password.");
        $this->render_json(400, $error);
        return FALSE;

    }

    public function logout()
    {
        $this->session->userdata = array();
        $this->session->sess_destroy();
        redirect('', 'refresh');
    }

}
