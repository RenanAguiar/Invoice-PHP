<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Homepage extends Application 
{
    
	public function index()
	{
//            $players = $this->players->all();
//            $stocks = $this->stocks->all();
//        
//            foreach($players as $player) 
//            {
//                $playerarr[] = $this->parser->parse('Homepage/player_table',(array)$player,true);
//            }
//            
//            foreach($stocks as $stock) 
//            {
//                $stockarr[] = $this->parser->parse('Homepage/stock_table',(array)$stock,true);
//            }
        
            $parms = array('table_open' => '<table class="players">');
            $this->table->set_template($parms);

//            $rows = $this->table->make_columns($playerarr,1);
//            $this->data['playertable'] = $this->table->generate($rows);
//
//
//            $parm = array (
//                'table_open' => '<table class ="stocks">'
//            );
//
//            $this->table->set_template($parm);
//
//            $rows_stock = $this->table->make_columns($stockarr,1);
//            $this->data['stocktable'] = $this->table->generate($rows_stock);

            $this->data['pagebody'] = 'Homepage/homeview';
            $this->render();    
	}
        
        
        
        
        
        
        
        
        
        
        
 	/**
	 * login function.
	 * 
	 * @access public
	 * @return void
	 */
	public function login() {
		
		// create the data object
		$data = new stdClass();
		
		// load form helper and validation library
		$this->load->helper('form');
		$this->load->library('form_validation');
		
		// set validation rules
		$this->form_validation->set_rules('username', 'Username', 'required|alpha_numeric');
		$this->form_validation->set_rules('password', 'Password', 'required');
		
		if ($this->form_validation->run() == false) {
			
			// validation not ok, send validation errors to the view
			$this->load->view('header');
			$this->load->view('user/login/login');
			$this->load->view('footer');
			
		} else {
			
			// set variables from the form
			$username = $this->input->post('username');
			$password = $this->input->post('password');
			
			if ($this->user_model->resolve_user_login($username, $password)) {
				
				$user_id = $this->user_model->get_user_id_from_username($username);
				$user    = $this->user_model->get_user($user_id);
				
				// set session user datas
				$_SESSION['user_id']      = (int)$user->id;
				$_SESSION['username']     = (string)$user->username;
				$_SESSION['logged_in']    = (bool)true;
				$_SESSION['is_confirmed'] = (bool)$user->is_confirmed;
				$_SESSION['is_admin']     = (bool)$user->is_admin;
				
				// user login ok
				$this->load->view('header');
				$this->load->view('user/login/login_success', $data);
				$this->load->view('footer');
				
			} else {
				
				// login failed
				$data->error = 'Wrong username or password.';
				
				// send error to the view
				$this->load->view('header');
				$this->load->view('user/login/login', $data);
				$this->load->view('footer');
				
			}
			
		}
		
	}       
        
        
        
        
        
        
        
        
        
        
}


	