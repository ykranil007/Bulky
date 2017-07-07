<?php
defined('BASEPATH') OR exit("No direct script access allowed");
class Product_listing extends BNM_Controller 
{	
	public function __construct()
	{
		parent::__construct();
        $this->output->set_content_type('application/json');
		$this->load->model('Product_listing_model');
        $this->load->library('encrypt');
	}
	 
    public function get_product_list_by_ajax()
    {
        //$data = $this->data;
        $json = array();
        $post = $this->input->post();
        //print_r($post);exit;
        if(isset($post))
        {
            $category = $sub_category = $sub_tosub_category = $brand = $product_key_word = $price = $color = $size = $discount = $sort_by = '';
            $page_num = 1;
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
                $sub_tosub_category = $post['sub_tosub_category'];//sub_tosub_category
            }
            if(isset($post['brands']) && $post['brands'] != '')
            {
                $brand = explode(",",trim($post['brands']));
            }
            if(isset($post['product_key_word']) && $post['product_key_word'] != '')
            {
                $product_key_word = $post['product_key_word'];
            }
            if(isset($post['price']) && $post['price'] != '')
            {
                $price = $post['price'];
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
            
            if(isset($post['page_num']) && $post['page_num'] != '')
            {
                $page_num = $post['page_num'];
            }
            if(isset($post['sort_by']) && $post['sort_by'] != '')
            {
                $sort_by = $post['sort_by'];
            }
            
            if($sort_by == 'new' || $sort_by == '')
            {
                $sort_by = ' ORDER BY product.product_id DESC';
            }
            elseif($sort_by == 'asc' )
            {
                $sort_by = ' ORDER BY selling_price ASC';
            }
            
            elseif($sort_by == 'desc' )
            {
                $sort_by = ' ORDER BY selling_price DESC';
            }
            elseif($sort_by == 'high_percentage' )
            {
                $sort_by = ' ORDER BY offer_percentage DESC';
            }
            elseif($sort_by == 'low_percentage' )
            {
                $sort_by = ' ORDER BY offer_percentage ASC';
            }
            
            $json['total_product']           = $this->Product_listing_model->count_products($category, $sub_category, $sub_tosub_category, $brand, $product_key_word, $price, $color, $size, $discount);
            $product_listing                 = $this->Product_listing_model->get_products_list($category, $sub_category, $sub_tosub_category, $brand, $product_key_word, $price, $color, $size, $discount, $page_num, $sort_by);
            $json['products_listing']        = $product_listing;//$this->create_product_listing($product_listing);
            //$brand_info                      = $this->Product_listing_model->get_brand_info($brand);
             
            //$json['brand_info']              = $this->create_brand_info($category, $sub_category, $sub_tosub_category, $brand_info, $product_key_word, $price, $color, $size, $discount, $brand);
            $color_info                      = $this->Product_listing_model->get_color_info($color);
            $json['color_info']              = $this->create_color_info($category, $sub_category, $sub_tosub_category, $brand, $product_key_word, $price, $color_info, $size, $discount,$color);
            
            $size_info                       = $this->Product_listing_model->get_size_info($size);
            $json['size_info']               = $this->create_size_info($category, $sub_category, $sub_tosub_category, $brand, $product_key_word, $price, $size_info, $color, $discount, $size);
            
            $discount_info                   = get_discount_info();//for static createing
            $json['discount_info']           = $this->create_discount_info($category, $sub_category, $sub_tosub_category, $brand, $product_key_word, $price, $discount_info, $color, $discount, $size);
            
            $json['sub_tosub_category']      = str_replace('-',' ',$sub_tosub_category); 
            $json['category']                = str_replace('-',' ',$category);
            $json['product_key_word']        = str_replace('-',' ',$product_key_word);
            //--===========sub to sub category info----==========
            $sub_tosub_category_info         = $this->Product_listing_model->get_sub_tosub_category($category, $sub_category, $sub_tosub_category);
            $json['sub_tosub_category_info'] = $this->create_sub_tosub_category_info($sub_tosub_category_info, $brand, $product_key_word, $price, $color, $size, $discount,$sub_tosub_category);
             
            $json['price_info']              = $this->Product_listing_model->get_min_max_price($category, $sub_category, $sub_tosub_category, $brand,$product_key_word, $price, $color, $size, $discount);
            $json['status']                  = true;
        }
        echo json_encode($json);
    }
    private function create_sub_tosub_category_info($records,$brand = '', $product_key_word = '', $price = '', $color = '', $discount = '', $selete_arr = '')
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
                $obj->count_product         = $this->Product_listing_model->count_products($record->category_id, $record->sub_category_id, $record->subtosub_category_id, $brand, $product_key_word, $price, $color, $discount);
                $arr[] = $obj;
            }
            return $arr;//$this->create_subtosubcategory_listing($arr, $selete_arr);
        }
        else
        {
            // if $records is empty
        }
    }
    private function create_brand_info($category = '', $sub_category = '', $sub_tosub_category = '', $brands = '', $product_key_word = '', $price = '', $color = '', $discount = '', $selet_arr)
    {
        //print_r($selet_arr);exit;
        if(!empty($brands))
        {
            $arr = array();
            foreach($brands as $record)
            {
                $obj  = new stdClass();
                $obj->brand_id       = $record->brand_id;
                $obj->brand_name     = $record->brand_name;
                $obj->brand_url      = $record->brand_url;
                $obj->count_product  = $this->Product_listing_model->count_products($category, $sub_category, $sub_tosub_category, $record->brand_id, $product_key_word, $price, $color, $discount);
                $arr[] = $obj; 
            }
            return $arr;//$this->create_brand_listing($arr,$selet_arr);
        }
        else
        {
            return '';
            // if $records is empty
        }
    }
    private function create_color_info($category = '', $sub_category = '', $sub_tosub_category = '', $brands = '', $product_key_word = '', $price = '', $color = '', $discount = '', $selet_arr)
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
                $obj->count_product  = $this->Product_listing_model->count_products($category, $sub_category, $sub_tosub_category, $brands, $product_key_word, $price, $record->color_id, $discount);
                $arr[] = $obj; 
            }
            return $arr;//$this->create_color_listing($arr, $selet_arr);
        }
        else
        {
            return '';
            // if $records is empty
        }
    }
    private function create_size_info($category = '', $sub_category = '', $sub_tosub_category = '', $brands = '', $product_key_word = '', $price = '', $size = '', $color = '', $discount = '',  $selet_arr)
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
                $obj->count_product = $this->Product_listing_model->count_products($category, $sub_category, $sub_tosub_category, $brands, $product_key_word, $price, $color, $record->size_id, $discount);
                $arr[] = $obj; 
            }
            //print_r($arr);exit;
            return $arr;//$this->create_size_listing($arr, $selet_arr);
        }
        else
        {
            return '';
            // if $records is empty
        }
    }
    private function create_discount_info($category = '', $sub_category = '', $sub_tosub_category = '', $brands = '', $product_key_word = '', $price = '', $discount_info = '', $color, $selet_arr,  $size)        
    {
        if(!empty($discount_info))
        {
            //print_r($discount_info);exit;
            $arr = array();
            foreach($discount_info as $record)
            {
                $obj = new stdClass();
                $obj->discount_url  =  $record->discount_url; 
                $obj->discount_name =  $record->discount_name;
                $obj->count_product =  $this->Product_listing_model->count_products($category, $sub_category, $sub_tosub_category, $brands, $product_key_word, $price, $color, $size, array($record->discount_url));
                $arr[] = $obj; 
            }
            return $arr;//$this->create_discount_listing($arr, $selet_arr);
        }
        else
        {
            return '';
            // if $records is empty
        }
    }
}