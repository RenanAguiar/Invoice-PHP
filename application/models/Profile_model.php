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

//        $sql = "SELECT * FROM profile WHERE user_id = ?";
//
//        $query = $this->db->query($sql, array($user_id));
//        $row = $query->row();
//        return $row;
        
        
        
        
        
                $this->db->where('user_id', $user_id);
        $query = $this->db->get('profile');
        
               // $item = $query->result_array();
                $item =  $query->row_array();
               
                
//        foreach ($items as &$item)
//        {
//            $item["invoice_detail_id"] = (int)$item["invoice_detail_id"];
//             $item["invoice_id"] = (int)$item["invoice_id"];
//             $item["unit_price"] = (double)$item["unit_price"];
//             $item["quantity"] = (double)$item["quantity"];
//        }
               $item["tax"] = (double)$item["tax"];
               // $item->tax =  (double)$item->tax;
        return $item;
        
        
        
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
            'tax' => $gst,
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
