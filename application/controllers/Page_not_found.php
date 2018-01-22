<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
class Page_not_found extends MY_Controller {
    public function __construct() {
        parent::__construct(); 
    } 
 
    public function index() { 
        $this->output->set_status_header('404'); // setting header to 404
        echo "jh";
        exit;
        $this->load->view('page_not_found');//loading view
    } 
} 
?> 