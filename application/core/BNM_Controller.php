<?php
ob_start();
//error_reporting(0);
date_default_timezone_set("Asia/Kolkata");
class BNM_Controller extends CI_Controller 
{
	public $data;
    public $user_id;
	function __construct() 
	{
		parent::__construct();
		$this->site_navigaion_bar();
    	$this->get_user_deatils();
    	$this->get_site_setting();
        $this->load->library('Common');
        $this->load->helper('cookie');

	}
	private function site_navigaion_bar()
    {
       $this->load->model('shared/Comman_model');
       $all_menus = array();
       $categorys = $this->Comman_model->get_category();
        if(!empty($categorys))
        {
            foreach($categorys as $category)//------------For Category--------
            {
                $obj_category   = new stdClass();
                $obj_category->category_id   = $category->category_id;
                $obj_category->category_name = $category->category_name;
                $obj_category->category_url  = $category->category_url;
                $obj_category->category_image= $category->category_image;
                $sub_categorys               = $this->Comman_model->get_sub_category($category->category_id);
                $obj_category->sub_category_count   =   count($sub_categorys);
                if(!empty($sub_categorys))
                {
                    foreach($sub_categorys as $sub_category)//------------For sub Category----------
                    {
                        $obj_sub_categorys                    = new stdClass();
                        $obj_sub_categorys->sub_category_id   = $sub_category->sub_category_id;
                        $obj_sub_categorys->category_id       = $category->category_id;
                        $obj_sub_categorys->sub_category_name = $sub_category->sub_category_name;
                        $obj_sub_categorys->sub_category_url  = $sub_category->sub_category_url;
                        $obj_sub_categorys->product_count= $sub_category->product_count;
                        $obj_sub_categorys->subcategory_images = $sub_category->subcategory_images;
                        
                        $subtosub_categorys                   = $this->Comman_model->get_subtosub_category($sub_category->sub_category_id);
                        $obj_category->subtosub_categorys     = count($subtosub_categorys);
                        if(!empty($subtosub_categorys))
                        {
                            foreach($subtosub_categorys as $subtosub_category)//---------For subtosub Category------
                            {
                                $obj_subtosub_categorys                         = new stdClass();
                                $obj_subtosub_categorys->subtosub_category_id   = $subtosub_category->subtosub_category_id;
                                $obj_subtosub_categorys->sub_category_id        = $sub_category->sub_category_id;
                                $obj_subtosub_categorys->subtosub_category_name = $subtosub_category->subtosub_category_name;
                                $obj_subtosub_categorys->subtosub_category_url  = $subtosub_category->subtosub_category_url;
                                $obj_subtosub_categorys->subtosub_category_image= $subtosub_category->subtosub_category_image;
                                $obj_subtosub_categorys->product_count= $subtosub_category->product_count;         
                                $obj_sub_categorys->subtosub_categorys[]        = $obj_subtosub_categorys;
                            }
                        }
                        $obj_category->sub_category[]  = $obj_sub_categorys;
                    } 
                }       
                $all_menus[]  = $obj_category;
            }
            //print_r($all_menus);exit; 
        }
        $this->data['image_path']['product_image'] = $this->config->item('seller_url').'assets/images/product_images/';
        $this->data['image_path']['banner_image']  = $this->config->item('admin_url').'assets/banner_image/';
        $this->data['image_path']['admin_image']   = $this->config->item('admin_url').'assets/images/';
        $this->data['menu_bar']['all_menus'] = $all_menus;
    }
    private function get_user_deatils()
    {
        if($this->session->userdata("user_id"))
		{
            $this->user_id            = $this->session->userdata('user_id');
            $this->data['user_info']  = $this->Comman_model->get_user_details($this->user_id);
            $this->data['cart_info']  = $this->Comman_model->get_cart_data($this->user_id);
		}
        else
        {
           $this->data['user_info'] = '';
           $this->data['cart_info'] = array();
        }   
    }
    private function get_site_setting()
    {
        $this->data['site_settings']  = $this->Comman_model->get_site_setting();
    }

    public function generateRandomString($length = 10) 
    {
      return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    }
    public function curPageURL() 
    {
            $pageURL = 'http';
            if(isset($_SERVER["HTTPS"]))
            if ($_SERVER["HTTPS"] == "on") {
                $pageURL .= "s";
            }
            $pageURL .= "://";
            if ($_SERVER["SERVER_PORT"] != "80") {
                $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
            } else {
                $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
            }
            return $pageURL;
    }    
}
?>