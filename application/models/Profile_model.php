<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Contacts table.
 */
class Profile_model extends CI_Model {

    public $rules = array(
        'update' =>
        array(
            'first_name' => array('field' => 'name', 'label' => 'Name', 'rules' => 'trim|required|min_length[5]'),
            'province' => array('field' => 'province', 'label' => 'Province', 'rules' => 'trim|min_length[2]')
    ));

// Constructor
    function __construct()
    {
        parent::__construct();
    }

    public function get_profile($user_id)
    {

        $sql = "SELECT * FROM profile WHERE user_id = ?";

        $query = $this->db->query($sql, array($user_id));
        $row = $query->row();
        return $row;
    }

    //public function create($user_id, $name, $postal_code, $province, $city, $address)
    public function create($user_id, $name)
    {

        $data = array(
            'user_id' => $user_id,
            'name' => $name,
            'last_update' => NULL
        );

        $this->db->insert('profile', $data);     
        
        if ($this->db->affected_rows() == 0)
        {
            return FALSE;
        }

        return TRUE;
    }

    public function update($user_id, $name, $phone, $postal_code, $province, $city, $address, $gst)
    {

        $data = array(
            'name' => $name,
            'phone' => $phone,
            'postal_code' => $postal_code,
            'province' => $province,
            'city' => $city,
            'address' => $address,
            'gst' => $gst,
            'last_update' => NULL
        );

        $this->db->where('user_id', $user_id);



        $result = $this->db->update('profile', $data);





        if ($this->db->affected_rows() == 0)
        {
            return FALSE;
        }

        return TRUE;
    }

}
