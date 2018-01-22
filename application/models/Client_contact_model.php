<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Contacts table.
 */
class Client_contact_model extends CI_Model {

    public $rules = array(
        'create' =>
        array(
            'first_name' => array('field' => 'first_name', 'label' => 'First Name', 'rules' => 'trim|required|min_length[2]'),
            'last_name' => array('field' => 'last_name', 'label' => 'Last Name', 'rules' => 'trim|required|min_length[2]'),
        ),
        'update' =>
        array(
            'first_name' => array('field' => 'first_name', 'label' => 'First Name', 'rules' => 'trim|required|min_length[2]'),
            'last_name' => array('field' => 'last_name', 'label' => 'Last Name', 'rules' => 'trim|required|min_length[2]'),
        )
    );

    private function _is_owner($client_id, $client_contact_id)
    {
        $data = array('client_contact_id' => $client_contact_id, 'client_id' => $client_id);
        $query = $this->db->get_where('client_contact', $data);
        $response = $query->result_array();


        return TRUE;
    }

    // Constructor
    function __construct()
    {
        parent::__construct();
        //$this->setTable('contacts', 'ID');
    }

    // return all images desc order by post date
    function get_client_contacts($client_id)
    {
        
         $data = array('client_id' => $client_id);
         
      //  $this->db->where('client_contact', $data);
       // $this->db->order_by("first_name", "asc");
                
        $query = $this->db->get_where('client_contact', $data);
        
        $results = $query->result_array();
        
                foreach($results as &$result) 
        {
            $result["client_id"] = (int)$result["client_id"];
            $result["client_contact_id"] = (int)$result["client_contact_id"];
        }
        
        
       // $query = $this->db->get('client_contact');

return $results;
        //return $query->result_array();
    }

    public function get_client_contact($client_contact_id)
    {
        $data = array('client_contact_id' => $client_contact_id);
        $query = $this->db->get_where('client_contact', $data);
        return $query->result_array();
    }

    public function create($data)
    {
        return $this->db->insert('client_contact', $data);
    }

    public function update($user_id,$data)
    {

        $this->db->where('client_contact_id', $data['client_contact_id']);
        return $this->db->update('client_contact', $data);
       
        //return $data['client_contact_id'];

    }

    public function delete($client_contact_id)
    {

        $this->db->where('client_contact_id', $client_contact_id);
        return $this->db->delete('client_contact');
       // return $client_contact_id;
    }

}
