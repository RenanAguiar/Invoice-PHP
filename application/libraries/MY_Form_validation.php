<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Form validation for UK Postcodes
 * 
 * Check that its a valid postcode
 * @author James Mills <james@koodoocreative.co.uk>
 * @version 1.0
 * @package FriendsSavingMoney
 */

class MY_Form_validation extends CI_Form_validation
{

    protected $CI;
    
    function __construct()
    {
        parent::__construct();  
    }
    
    
    
    function set_value($field = '', $default = '')
{
       
    if ( ! isset($this->_field_data[$field]))
    {
        return $default;
    }

    $field = &$this->_field_data[$field]['postdata'];

    if (is_array($field))
    {
        $current = each($field);
        return $current['value'];
    }

    return $field;
}

    function make_null_field($value)
    {
      //  return (!empty(trim($value))) ? $value : NULL;
        return $value;
    }
    
    function clear_currency($value)
    {
        $value = str_replace(',', '', $value);
        $value = str_replace('$', '', $value);
        return $value;
        
    }
    
    public function valid_date($date)
        {
        $CI = $this->CI =& get_instance();
          $arr_date = explode("/",$date);
              if(checkdate($arr_date[1], $arr_date[0], $arr_date[2]))
            {
                return TRUE;
            }

               $this->CI->form_validation->set_message('valid_date', '{field} must be a valid date.');
               return FALSE;

        }


        public function make_date_db($date)
        {
            $arr_date = explode("/",$date);
            if(checkdate($arr_date[1], $arr_date[0], $arr_date[2]))
            {
                return $arr_date[2]."-".$arr_date[1]."-".$arr_date[0];
            }
            return FALSE;
        }

        
        public function valid_invoice_line($a)
        {

          
            $CI = $this->CI =& get_instance();
            
     // echo $a;
            
          //  $this->form_validation->set_message('valid_invoice_line', 'text dont match captcha');

          //  $this->CI->form_validation->set_message('valid_invoice_line', '{field} must be a werwerew date.');


        }

}