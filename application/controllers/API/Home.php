<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Home extends BNM_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->output->set_content_type('application/json');
        $this->load->model('API/home_model');
    }
    public function index()
    {
        $data = $this->data;
        $post = $this->input->post();
        $json = array();
        $this->home_model->save_device_id($post);
        $json['page_banner']       = $this->home_model->get_app_page_banners(1);
        $json['offer_products']    = $this->home_model->get_products('', 5);
        $json['men_products']      = $this->home_model->get_products(1, 5);
        $json['kids_products']     = $this->home_model->get_products(3, 5);
        $json['women_products']    = $this->home_model->get_products(2, 5);
        $json['home_products']     = $this->home_model->get_products(4, 5);
        $new_products1  = $this->home_model->get_products(2,5,'','new');
        $new_products2  = $this->home_model->get_products(1,5,'','new');
        $new_products3  = $this->home_model->get_products(3,5,'','new');
        $new_products_all  = array_merge($new_products1,$new_products2);
        $new_products_all  = array_merge($new_products_all,$new_products3);
        $new_products_all  = array_merge($new_products_all,$this->home_model->get_products(4,5,'','new'));
        $json['new_arrival_products'] = $new_products_all;
        $json['recent_products'] = $this->home_model->get_products('', 18, $post['ip']);        
        $json['menus']             = $this->Comman_model->get_category();
        $json['login_img']         = 'assets/images/app_images/login_fashion.jpg';
        $json['more_img']          = 'assets/images/app_images/more.png';
        $json['payment_img']       = 'assets/images/app_images/payment_successfull.png';
        $json['fin_start_img']     = 'assets/images/app_images/finance.jpg';
        echo json_encode($json);
    }
    
    public function get_keywords_list()
    {
        $data = $this->data;
        $arr = array();
        if (isset($_GET['keywords']) && $_GET['keywords']!='') 
        {
            $search_string = trim($_GET['keywords']);
            $search_list = $this->Comman_model->get_search_subtosub_category($search_string);
            foreach ($search_list as $subto_subcategory)// add sub category in autocomplete box
            {
                $arr[] = array('label' => $subto_subcategory->subtosub_category_name, 'category' =>'Categories');
            }

            $search_list = $this->Comman_model->get_search_brand($search_string);
            foreach ($search_list as $brand) // add Brand in autocomplete box
            {
                $arr[] = array('label' => $brand->brand_name, 'category' => 'Brands');
            }

            $search_list = $this->Comman_model->get_product_kewwords($search_string);
            foreach ($search_list as $key_words)// add add product key words in autocomplete box
            {
                $arr[] = array('label' => $key_words->product_keyword, 'category' =>'All Others');
            }
        }
        echo json_encode($arr);
    }
}
