<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Auth_token_model extends CI_Model {

    const API_TOKEN = "renan";

    //protected $client_contact_id;
    protected $first_name;
    protected $last_name;
    protected $email;
    // protected $phone;

    private $_name;
    private $_address;
    private $_postal_code;
    private $_province;
    private $_city;
    private $_user_id;
    private $token;
    
//`id_auth_token`, `user_id`, `token`, `created``

    function __construct()
    {
        parent::__construct();
       
    }

    function get_token() {
        return $this->token;
    }
    
    function get_user_id($token) {
                $this->db->select('user_id');
        $this->db->from('auth_token');
        $this->db->where('token', $token);
        $query = $this->db->get();
        $row = $query->row();
       
        return $row->user_id;
        
    }
    
    
    
    function get_clients($user_id)
    {
        $data = array('user_id' => $user_id);
        $this->db->order_by("name", "asc");
        $query = $this->db->get_where('client', $data);
        return $query->result_array();
    }

    public function get_client($client_id)
    {
        $this->db->select('*');
        $this->db->from('client');
        $this->db->join('user', 'user.user_id = client.user_id');
        $this->db->where('client_id', $client_id);
        $query = $this->db->get();
        $row = $query->row();
        return $row;
    }

    public function create($user_id)
    {

        $token =  hash('ripemd160', self::API_TOKEN+$user_id);
        $date = date('Y-m-d H:i:s');
        $data['user_id'] = $user_id;
        $data['token'] = $token;
        $data['created'] = $date;
        
        $this->token = $token;
        
        $this->db->insert('auth_token', $data);
        
        return true;
    }

    public function update($user_id, $data)
    {
        $this->db->where('client_id', $data['client_id']);
        return $this->db->update('client', $data);
    }

}
