<?php

defined('BASEPATH') OR exit('No direct script access allowed');
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of StockController
 *
 * @author a7823
 */
class Profile extends Auth_controller {

    public function __construct()
    {
        parent::__construct();
        $this->force_login();
        $this->load->model('profile_model');
        $this->data['success'] = "";
        $this->data['error'] = "";
        $this->data['response_message'] = "";
    }

    public function index()
    {


        $method = $this->input->method(TRUE);
        if ($method === "POST")
        {
            self::_update_profile();
        }
        else
        {
            self::_get_profile();
        }
    }

    private function _get_profile()
    {

        $this->data['name'] = "";
        $this->data['phone'] = "";
        $this->data['postal_code'] = "";
        $this->data['province'] = "";
        $this->data['city'] = "";
        $this->data['address'] = "";
        $this->data['gst'] = "";

        $profile = $this->profile_model->get_profile($this->user_id);

        if ($profile !== NULL)
        {
            $this->data['name'] = $profile->name;
            $this->data['phone'] = $profile->phone;
            $this->data['postal_code'] = $profile->postal_code;
            $this->data['province'] = $profile->province;
            $this->data['city'] = $profile->city;
            $this->data['address'] = $profile->address;
            $this->data['gst'] = $profile->gst;
        }
        $this->data['pagebody'] = 'profile/index';
        $this->render();
    }

    private function _update_profile()
    {

        $rules = $this->profile_model->rules['update'];

        $this->form_validation->set_rules($rules);
        if ($this->form_validation->run() === TRUE)
        {
            $name = $this->input->post('name');
            $postal_code = $this->input->post('postal_code');
            $province = $this->input->post('province');
            $city = $this->input->post('city');
            $address = $this->input->post('address');
            $phone = $this->input->post('phone');
            $gst = $this->input->post('gst');



            if ($this->profile_model->update($this->user_id, $name, $phone, $postal_code, $province, $city, $address, $gst))
            {
                $this->data['response_class'] = "success";
                $this->data['response_message'] = "Profile was updated!";
            }
            else
            {
                $this->data['response_class'] = "danger";
                $this->data['response_message'] = "There was a problem updating this profile. Please try again.";
            }
        }
        else
        {
            $this->data['response_class'] = "danger";
            $this->data['response_message'] = "There was a problem updating this profile. Please try again.";
        }


        $this->data['pagebody'] = 'profile/index';
        $this->render();
    }

}
