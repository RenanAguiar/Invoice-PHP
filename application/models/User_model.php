<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model {

    public $rules = array(
        'sign_up' =>
        array(
            'email' => array('field' => 'email', 'label' => 'Email', 'rules' => 'trim|required|valid_email|is_unique[user.email]'),
            'name' => array('field' => 'name', 'label' => 'Business Name', 'rules' => 'trim|required|min_length[3]'),
            'password' => array('field' => 'password', 'label' => 'Password', 'rules' => 'trim|required|min_length[3]'),
            'password_c' => array('field' => 'password_c', 'label' => 'Password Confirmation', 'rules' => 'trim|required|matches[password]', 'errors' => array(
                    'required' => 'Required.',
                )),
        ),
        'do_login' =>
        array(
            'email' => array('field' => 'login_email', 'label' => 'Email', 'rules' => 'trim|required'),
            //'email' => array('field' => 'email', 'label' => 'Email', 'rules' => 'trim|required|valid_email'),
            'password' => array('field' => 'login_password', 'label' => 'Password', 'rules' => 'trim|required|min_length[1]'),
        )
    );


    function __construct()
    {
        parent::__construct();
    }


    /**
     * create_user function.
     * 
     * @access public
     * @param mixed $username
     * @param mixed $email
     * @param mixed $password
     * @return bool true on success, false on failure
     */
    public function create($email, $password)
    {

        $data = array(
            'email' => $email,
            'password' => $this->hash_password($password)
        );

        return $this->db->insert('user', $data);
    }

    /**
     * get_user_id_from_username function.
     * 
     * @access public
     * @param mixed $username
     * @return int the user id
     */
    public function get_user_by_email($email)
    {

        $this->db->select('user_id');
        $this->db->from('user');
        $this->db->where('email', $email);
        return $this->db->get()->row('user_id');
    }

    /**
     * get_user function.
     * 
     * @access public
     * @param mixed $user_id
     * @return object the user object
     */
    public function get_user($user_id)
    {

        $this->db->from('user');
        $this->db->where('user_id', $user_id);
        return $this->db->get()->row();
    }



    /**
     * resolve_user_login function.
     * 
     * @access public
     * @param mixed $username
     * @param mixed $password
     * @return bool true on success, false on failure
     */
    public function login($email, $password)
    {
        $this->db->select('password');
        $this->db->from('user');
        $this->db->where('email', $email);
        $hash = $this->db->get()->row('password');

        return $this->verify_password_hash($password, $hash);
    }

    
     /**
     * hash_password function.
     * 
     * @access private
     * @param mixed $password
     * @return string|bool could be a string on success, or bool false on failure
     */
    private function hash_password($password)
    {

        return password_hash($password, PASSWORD_BCRYPT);
    }   
    
    /**
     * verify_password_hash function.
     * 
     * @access private
     * @param mixed $password
     * @param mixed $hash
     * @return bool
     */
    private function verify_password_hash($password, $hash)
    {
        return password_verify($password, $hash);
    }

}
