<?php

//help from http://webeasystep.com/blog/view_article/Generate_MS_Word_document_files_with_Codeigniter_and_Phpword_library
/* @property phpword_model $phpword_model */
include_once(APPPATH . "third_party/PhpWord/Autoloader.php");

//include_once(APPPATH."core/Front_end.php");

use PhpOffice\PhpWord\Autoloader;
use PhpOffice\PhpWord\Settings;

Autoloader::register();
Settings::loadConfig();

class Phpword extends Auth_controller 
{

        private $filename = 'test.docx';  
        private $_templateProcessor;
        private $_invoice;
        private $_client;
                
                
    function __construct()
    {
        parent::__construct();
        $this->force_login();
        $this->load->model('client_model');
        $this->load->model('client_contact_model');
        $this->load->model('invoice_model');
        $this->load->model('invoice_detail_model');
    }

    
    private function _set_invoice($invoice_id) {
       
        $invoice = $this->invoice_model->get_invoice($invoice_id);
       
        if ($invoice == NULL)
        {
            show_error('Invoice not found', 404, '');
            
        }

        $this->_invoice['client_id'] = $invoice->client_id;
        $this->_invoice['date_issue'] = dataBR($invoice->date_issue);
        $this->_invoice['due_date'] = dataBR($invoice->due_date);
        $this->_invoice['amount_paid'] = $invoice->amount_paid;
        $this->_invoice['date_paid'] = $invoice->date_paid;
        $this->_invoice['invoice_number'] = $invoice->invoice_number;
        $this->_invoice['total_invoice'] = $this->invoice_model->get_total_invoice($invoice_id);

        
         $this->_templateProcessor->setValue('total_invoice', $this->_invoice['total_invoice']);
    }
    
    
    private function _set_client($client_id) {
        
        $client = $this->client_model->get_client($client_id);
        
        $this->_client['name'] = $client->name;
        $this->_client['postal_code'] = $client->postal_code;
        $this->_client['province'] = $client->province;
        $this->_client['city'] = $client->city;
        $this->_client['address'] = $client->address;
        
        $this->_client['contact_name'] = "ABC";      

        $this->_templateProcessor->setValue('client_name', $this->_client['name']);
        $this->_templateProcessor->setValue('client_postal_code', $this->_client['postal_code']);
        $this->_templateProcessor->setValue('client_province', $this->_client['province']);
        $this->_templateProcessor->setValue('client_city', $this->_client['city']);
        $this->_templateProcessor->setValue('client_address', $this->_client['address']);
        $this->_templateProcessor->setValue('client_contact_name', $this->_client['contact_name']);

    }
    
    private function _set_invoice_detail($invoice_id) {
               //pegar os items
        $arr_details = $this->invoice_detail_model->get_invoice_details($invoice_id);
                $i = 1;
        $this->_templateProcessor->cloneRow('row_description', count($arr_details));
        foreach ($arr_details as $detail)
        {               
            $this->_templateProcessor->setValue('row_description#'. $i, htmlspecialchars($detail['description']));
            $this->_templateProcessor->setValue('row_qty#'. $i, $detail['quantity']);
            $this->_templateProcessor->setValue('row_rate#'. $i, $detail['unit_price']);
            $total_line = $this->invoice_detail_model->get_total_line($detail['unit_price'], $detail['quantity'] );
            $this->_templateProcessor->setValue('row_amount#'. $i, $total_line);
            $i++;
        }
        
        
    }
    
    public function download($invoice_id)
    {

        $this->_templateProcessor = new \PhpOffice\PhpWord\TemplateProcessor('assets/template/invoice.docx');
       
        self::_set_invoice($invoice_id);
        self::_set_invoice_detail($invoice_id);
        self::_set_client($this->_invoice['client_id']);


        $this->_save_docx();

    }
    
    private function _save_docx() {
         $filename = $this->filename;

         
         $this->_templateProcessor->saveAs($filename);
        // send results to browser to download
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filename));
        flush();
        readfile($filename);
        unlink($filename); // deletes the temporary file
        exit;
    }

}
