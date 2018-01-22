<?php

if (!defined('APPPATH'))
    exit('No direct script access allowed');


if (!function_exists('get_client'))
{

    function get_client($client_id)
    {
        $CI = get_instance();
        $CI->load->model('client_model');

        $client = $CI->client_model->get_client($client_id);

        if ($client === NULL)
        {
            show_error('Client not found', 404, '');
        }

        if ($CI->user_id != $client->user_id)
        {
            show_error('Not Allowed', 403, '');
        }

        return $client;
    }

}
