<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Client_model extends CI_Model {

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
    
    public $rules = array(
        'create' =>
        array(
            'name' => array('field' => 'name', 'label' => 'Name', 'rules' => 'trim|required|min_length[3]'),
        ),
        'update' =>
        array(
            'client_id' => array('field' => 'client_id', 'label' => '', 'rules' => 'trim|required|min_length[1]'),
            'name' => array('field' => 'name', 'label' => 'Name', 'rules' => 'trim|required|min_length[3]'),
        )
    );

    function __construct()
    {
        parent::__construct();
    }

    function get_clients($user_id)
    {
        $data = array('user_id' => $user_id);
        $this->db->order_by("name", "asc");
        $query = $this->db->get_where('client', $data);
        
        $results = $query->result_array();
        
        foreach($results as &$result) 
        {
            $result["client_id"] = (int)$result["client_id"];
        }
        return $results;
    }

    public function get_client($client_id)
    {
        $this->db->select('*');
        $this->db->from('client');
        $this->db->join('user', 'user.user_id = client.user_id');
        $this->db->where('client_id', $client_id);
        $query = $this->db->get();
        //$row = $query->row();
        //return $row;
        return $query->result_array();
    }

    public function create($user_id, $data)
    {
        $data['user_id'] = $user_id;
        return $this->db->insert('client', $data);
    }

    public function update($user_id, $data)
    {
        $this->db->where('client_id', $data['client_id']);
        return $this->db->update('client', $data);
    }

}
