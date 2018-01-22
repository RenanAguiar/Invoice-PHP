<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

    protected $data = array();

    function __construct()
    {
        parent::__construct();
        $this->load->helper(array('common', 'url'));
        $this->data = array();
        $this->data['toast_type'] = "";
        $this->data['toast_message'] = "";
        $this->data['extra_js'] = '';
        $this->data['pagetitle'] = 'RA Invoice';
        $this->data['menu'] = '';
        $this->data['csrf_name'] = $this->security->get_csrf_token_name();
        $this->data['csrf_value'] = $this->security->get_csrf_hash();
    }

    function render()
    {
        $this->data['content'] = $this->parser->parse($this->data['pagebody'], $this->data, true);
        $this->data['data'] = &$this->data;

        $this->parser->parse('_template', $this->data);
    }

    public function render_json($status, $response)
    {
        $this->output
                ->set_content_type('application/json')
                ->set_status_header($status)
                ->set_output(json_encode($response));
        // ->_display();
    }

    function render_modal()
    {
        $this->data['data'] = &$this->data;
        $this->parser->parse('_template_modal', $this->data);
    }

    function set_toaster($type = '', $message = '')
    {
        $this->data['toast_type'] = $type;
        $this->data['toast_message'] = $message;
    }

}

class Auth_controller extends MY_Controller {

    public $user_id = 0;

    function __construct()
    {
        parent::__construct();
        $user = self::force_login();
        $this->user_id = $user->user_id;
        $this->data['user_email'] = $user->email;
        $this->data['menu'] = $this->parser->parse('menu', $this->data, TRUE);
    }

    function force_login()
    {
        if (!self::is_loged()) :

            $previous_page = urlencode(current_url());
            $this->session->set_flashdata('previous_page', $previous_page);
            $this->session->set_flashdata('error', 'Your session has expired. Please, login again!');
            redirect('/', 'refresh');

        else:
            $user = $this->user_model->get_user($this->session->userdata('user_id'));
            return $user;
        endif;
    }

    function is_loged()
    {

        if ($this->session->userdata('logged_in') === (bool) TRUE) :
            $retorno = $this->session->userdata('user_id');
        else:
            $retorno = 0;
        endif;
        return $retorno;
    }

}

class API_Controller extends MY_Controller {

    function __construct()
    {
        parent::__construct();
    }

        public function render_json($status, $response)
    {
        $this->output
                ->set_content_type('application/json')
                ->set_status_header($status)
                ->set_output(json_encode($response));
    }
    
}
