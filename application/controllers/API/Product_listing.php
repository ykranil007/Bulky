<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Product_listing extends BNM_Controller 
{	
	public function __construct()
	{
		parent::__construct();
		$this->output->set_content_type('application/json');
		$this->load->model('API/Product_listing_model');
        $this->load->library('encrypt');        
	}
	
    public function get_product_list_by_ajax()
    {
        $json = array();
        $post = $this->input->post();
        //print_r($post);exit;
        if(isset($post))
        {
            $category = $sub_category = $sub_tosub_category = $brand = $product_key_word = $price = $color = $size = $discount = $sets = $credit_days = $sort_by = '';
            $page_num = array('from'=>0,'to'=>20);
            if(isset($post['category']) && $post['category'] != '')
            {
                $category = $post['category'];
            }
            if(isset($post['sub_category']) && $post['sub_category'] != '')
            {
                $sub_category = $post['sub_category'];
            }
            if(isset($post['sub_tosub_category']) && $post['sub_tosub_category'] != '')
            {
                $sub_tosub_category = $post['sub_tosub_category'];
            }
            if(isset($post['brands']) && $post['brands'] != '')
            {
                $brand = explode(",",trim($post['brands']));
            }
            if(isset($post['product_key_word']) && $post['product_key_word'] != '')
            {
                $product_key_word = trim(str_replace('%20',' ',$post['product_key_word']));
            }
            if(isset($post['price']) && $post['price'] != '' && $post['price'] != null)
            {
                $price = (!empty($post['price'][0]))? $post['price']:'';
            }
            if(isset($post['color']) && $post['color'] != '')
            {
                $color = explode(",",trim($post['color']));
            }
            if(isset($post['size']) && $post['size'] != '')
            {
                $size = explode(",",trim($post['size']));
            }
            if(isset($post['discount']) && $post['discount'] != '')
            {
                $discount = explode(",",trim($post['discount']));
            }
            if(isset($post['sets']) && $post['sets'] != '')
            {
                $sets = $post['sets'];
            }
            if(isset($post['credit_days']) && $post['credit_days'] != '')
            {
                $credit_days = $post['credit_days'];
            }
             
            if(isset($post['sort_by']) && $post['sort_by'] != '')
            {
                $sort_by = $post['sort_by'];
            }
            //new--------------------------
            /*if(isset($post['page_num']) && $post['page_num'] != '')
            {
                $page_num = $post['page_num'];
            }*/
            if(isset($post['from']) && isset($post['to']))
            {
                $page_num = array('from'=>$post['from'],'to'=>$post['to']);
            }
            
            if($sort_by == 'new' && $sort_by != '')
            {
                $sort_by = ' ORDER BY product.product_id DESC';
            }
            elseif($sort_by == 'asc' )
            {
                $sort_by = ' ORDER BY product.selling_price ASC';
            }
            elseif($sort_by == 'desc' )
            {
                $sort_by = ' ORDER BY product.selling_price DESC';
            }
            elseif($sort_by == 'high_percentage' )
            {
                $sort_by = ' ORDER BY offer_percentage DESC';
            }
            elseif($sort_by == 'low_percentage' )
            {
                $sort_by = ' ORDER BY offer_percentage ASC';
            }
            $json['total_product']              = $this->Product_listing_model->count_products($category, $sub_category, $sub_tosub_category, $brand, $product_key_word, $price, $color, $size, $discount,$sets, $credit_days);
            //echo $this->db->last_query();
            
            $json['no_listing_img']             = 'assets/images/app_images/no_listing.jpg';
                        
            $product_listing                    = $this->Product_listing_model->get_products_list($category, $sub_category, $sub_tosub_category, $brand, $product_key_word, $price, $color, $size, $discount,$sets, $credit_days, $page_num, $sort_by);
            //echo $this->db->last_query();
            $json['products_listing']           = $product_listing;
            
            //$brand_info                         = $this->Product_listing_model->get_brand_info($brand);
            $json['brand_info']                 = (object)array();//$this->create_brand_info($category, $sub_category, $sub_tosub_category, $brand_info, $product_key_word, $price, $color, $size, $discount,$sets, $credit_days, $brand);
            $color_info                         = $this->Product_listing_model->get_color_info($color);
            $json['color_info']                 = $this->create_color_info($category, $sub_category, $sub_tosub_category, $brand, $product_key_word, $price, $color_info, $size, $discount,$sets, $credit_days,$color);
            $size_info                          = $this->Product_listing_model->get_size_info($size);
            $json['size_info']                  = $this->create_size_info($category, $sub_category, $sub_tosub_category, $brand, $product_key_word, $price, $size_info, $color, $discount,$sets, $credit_days, $size);
            
            $sets_info                          = $this->Product_listing_model->get_sets_info($sets);
            //print_r($sets_info);exit();
            $json['sets_info']                  = $this->create_sets_info($category, $sub_category, $sub_tosub_category, $brand, $product_key_word, $price, $discount,$color, $size, $sets_info,$credit_days,$sets);
            
            //$credit_days_info                   = $this->Product_listing_model->get_credit_days_info($credit_days);
            $json['credit_days_info']           = '';//$this->create_credit_days_info($category, $sub_category, $sub_tosub_category, $brand, $product_key_word, $price, $discount, $color, $size, $sets, $credit_days_info, $credit_days);
            
            $discount_info                      = get_discount_info();//for static creating
            $json['discount_info']              = $this->create_discount_info($category, $sub_category, $sub_tosub_category, $brand, $product_key_word, $price, $discount_info, $color, $size, $sets, $credit_days, $discount);
            $json['sub_tosub_category']         = str_replace('-',' ',$sub_tosub_category); 
            $json['category']                   = str_replace('-',' ',$category);
            $json['product_key_word']           = str_replace('-',' ',$product_key_word);
            //--===========sub to sub category info----==========
            $sub_tosub_category_info            = $this->Product_listing_model->get_sub_tosub_category($category, $sub_category, $sub_tosub_category);
            $json['sub_tosub_category_info']    = $this->create_sub_tosub_category_info($sub_tosub_category_info, $brand, $product_key_word, $price, $color, $size, $discount, $sets, $credit_days, $sub_tosub_category);
            $json['price_info']                 = $this->Product_listing_model->get_min_max_price($category, $sub_category, $sub_tosub_category, $brand, $product_key_word, $price, $color, $size, $discount, $sets, $credit_days);
            $json['status']                     = true;
        }
        echo json_encode($json);
    }
    
   private function create_sub_tosub_category_info($records, $brand = '', $product_key_word = '', $price = '', $color = '', $size = '', $discount = '', $sets = '', $credit_days = '', $selete_arr = '')
    {
        if(!empty($records))
        {
            $arr = array();
            foreach($records as $record)
            {
                $obj  = new stdClass();
                $obj->category_name         = $record->category_name;
                $obj->category_url          = $record->category_url;
                $obj->category_id           = $record->category_id;
                $obj->sub_category_name     = $record->sub_category_name;
                $obj->sub_category_url      = $record->sub_category_url;
                $obj->sub_category_id       = $record->sub_category_id;
                $obj->subtosub_category_name= $record->subtosub_category_name;
                $obj->subtosub_category_id  = $record->subtosub_category_id;
                $obj->subtosub_category_url = $record->subtosub_category_url;
                $count_product              = $this->Product_listing_model->count_products($record->category_id, $record->sub_category_id, $record->subtosub_category_id, $brand, $product_key_word, $price, $color, $size, $discount, $sets, $credit_days);
                $obj->count_product         = $count_product;
                if($count_product > 0)
                {
                    $arr[] = $obj;
                }
            }
            return $arr;
        }
    }
    private function create_brand_info($category = '', $sub_category = '', $sub_tosub_category = '', $brands = '', $product_key_word = '', $price = '', $color = '', $size = '', $discount = '', $sets = '', $credit_days = '', $selet_arr)
    {
        if(!empty($brands))
        {
            $arr = array();
            foreach($brands as $record)
            {
                $obj  = new stdClass();
                $obj->brand_id       = $record->brand_id;
                $obj->brand_name     = $record->brand_name;
                $obj->brand_url      = $record->brand_url;
                $count_product       = $this->Product_listing_model->count_products($category, $sub_category, $sub_tosub_category, $record->brand_id, $product_key_word, $price, $color, $size, $discount,$sets, $credit_days);
                $obj->count_product  = $count_product;
                if($count_product > 0)
                {
                    $arr[] = $obj;
                } 
            }
            return $arr;
        }
        else
            return '';
    }
    private function create_color_info($category = '', $sub_category = '', $sub_tosub_category = '', $brands = '', $product_key_word = '', $price = '', $color = '', $size = '', $discount = '', $sets = '', $credit_days = '', $selet_arr)
    {
        if(!empty($color))
        {
            $arr = array();
            foreach($color as $record)
            {
                $obj  = new stdClass();
                $obj->color_id       = $record->color_id;
                $obj->color_name     = $record->color_name;
                $obj->color_code     = $record->color_code;
                $obj->color_url      = $record->color_url;
                $count_product       = $this->Product_listing_model->count_products($category, $sub_category, $sub_tosub_category, $brands, $product_key_word, $price, $record->color_id, $size, $discount, $sets, $credit_days);
                $obj->count_product = $count_product;
                if($count_product > 0)
                {
                    $arr[] = $obj;
                }  
            }
            return $arr;
        }
        else
            return '';
    }
    private function create_size_info($category = '', $sub_category = '', $sub_tosub_category = '', $brands = '', $product_key_word = '', $price = '', $size = '', $color = '', $discount = '', $sets = '', $credit_days = '',  $selet_arr)
    {
        if(!empty($size))
        {
            $arr = array();
            foreach($size as $record)
            {
                $obj  = new stdClass();
                $obj->size_id       = $record->size_id;
                $obj->size_name     = $record->size_name;                 
                $obj->size_url      = $record->size_url;
                $count_product      = $this->Product_listing_model->count_products($category, $sub_category, $sub_tosub_category, $brands, $product_key_word, $price, $color, $record->size_id, $discount,$sets, $credit_days);
                $obj->count_product = $count_product;
                if($count_product > 0)
                {
                    $arr[] = $obj;
                }  
            }
            return $arr;
        }
        else
            return '';
    }
    private function create_discount_info($category = '', $sub_category = '', $sub_tosub_category = '', $brands = '', $product_key_word = '', $price = '', $discount_info = '', $color, $size, $sets ='', $credit_days ='', $selet_arr)        
    {
        if(!empty($discount_info))
        {
            $arr = array();
            foreach($discount_info as $record)
            {
                $obj = new stdClass();
                $obj->discount_id   =  $record->discount_id;
                $obj->discount_url  =  $record->discount_url; 
                $obj->discount_name =  $record->discount_name;
                $count_product      =  $this->Product_listing_model->count_products($category, $sub_category, $sub_tosub_category, $brands, $product_key_word, $price, $color, $size, array($record->discount_url),$sets, $credit_days);
                $obj->count_product = $count_product;
                if($count_product > 0)
                {
                    $arr[] = $obj;
                } 
            }
            return $arr;
        }
        else
            return '';
    }
    
    private function create_sets_info($category = '', $sub_category = '', $sub_tosub_category = '', $brands = '', $product_key_word = '', $price = '', $discount = '', $color, $size, $sets_info ='', $credit_days ='', $selet_arr)        
    {
        if(!empty($sets_info))
        {
            $arr = array();
            foreach($sets_info as $record)
            {
                $obj = new stdClass();
                $obj->set_id   =  $record->set_id;
                $obj->set_url  =  $record->set_url; 
                $obj->set_name =  $record->set_name;
                $count_product =  $this->Product_listing_model->count_products($category, $sub_category, $sub_tosub_category, $brands, $product_key_word, $price, $color, $size, $discount, array($record->set_url), $credit_days);
                $obj->count_product = $count_product;
                if($count_product > 0)
                {
                    $arr[] = $obj;
                }  
            }
            return $arr;
        }
        else
            return '';
    } 
}