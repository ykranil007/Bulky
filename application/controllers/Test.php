<?php

defined('BASEPATH') or exit('No direct script access allowed');
class Test extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        //$this->load->model('home_model');
    }
    
    public function Testing()
    {
        $file = 'http://seller.bulknmore.com/assets/seller_invoice/8/Bulknmore-Labels-21-6-2017-14-17.pdf';
        $file_exists = (@fopen($file, "r")) ? true : false;
        
        if($file_exists)
        {
            $this->load->helper('download');
            $pth = file_get_contents($file);
            print_r($pth);exit;
            //force_download($file, $pth);
        }
    }
    
}

?>