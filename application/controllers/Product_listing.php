<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Product_listing extends BNM_Controller 
{	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Product_listing_model');
        $this->load->library('encrypt');        
	}
	public function index($category = '', $sub_category = '', $sub_tosub_category = '')
	{
	    $data = $this->data;
        $product_key_word                   = $this->postback();        
        $data['product_key_word']           = $product_key_word;        
        $brand_info                         = $this->Product_listing_model->get_brand_info();        
        $data['price_info']                 = $this->Product_listing_model->get_min_max_price($category, $sub_category, $sub_tosub_category, '', $product_key_word,'','','','','','');
        $data['page_settings']              = $this->Comman_model->get_page_setting(26);
        $data['page_settings']->page_title  = ucfirst(explode('-',$category)[0]).' '.ucfirst(str_replace('-', ' ',$sub_tosub_category)).ucfirst(str_replace('-', ' ',$product_key_word)).' - Buy '.ucfirst(explode('-',$category)[0]).' '.ucfirst(str_replace('-', ' ',$sub_tosub_category)).' Products in BULK Online at Low Price in India Only on - '.$data['page_settings']->page_title;
		//--===============for url show ================
		if(!empty($category))
		{
			$data['category_name']			= $this->Product_listing_model->get_category_name($category);
		}
		if(!empty($sub_category))
		{
			$data['sub_category_name']		= $this->Product_listing_model->get_sub_category_name($sub_category);
		}
		if(!empty($sub_tosub_category))
		{
			$data['subtosub_category_name']	= $this->Product_listing_model->get_subtosub_category_name($sub_tosub_category);
		}
		//================for end or show url ===========
        $data['category']                   = $category; 		
        $data['sub_category']               = $sub_category;
        $data['sub_tosub_category']         = $sub_tosub_category;
        if(!empty($data['sub_tosub_category']))
        {
            $data['page_banners'] = $this->Comman_model->get_category_page_banners('', '', $data['sub_tosub_category'], 26, 'top');       
        }
	    $this->load->view('viw_listing',$data);
	}
    public function page_banners()
    {
        $data = $this->data;
        $category_url = $_GET['subtosub_category_id'];
        $page_banner = $this->Comman_model->get_category_page_banners('', '', $category_url, 26, 'top');
        $banner_images = '';
        foreach($page_banner as $banner):   
            $banner_images .= '<a href="javascript:void(0);"><img src="'.$data['image_path']['banner_image'].$banner->banner_image.'" alt=""></a>';
        endforeach; 
        echo $banner_images;
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
                $obj->count_product         = $this->Product_listing_model->count_products($record->category_id, $record->sub_category_id, $record->subtosub_category_id, $brand, $product_key_word, $price, $color, $size, $discount, $sets, $credit_days);
                //echo $this->db->last_query();exit;
                $arr[] = $obj;
            }
            //print_r($arr);exit;
            return $this->create_subtosubcategory_listing($arr, $selete_arr);
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
                $obj->count_product  = $this->Product_listing_model->count_products($category, $sub_category, $sub_tosub_category, $record->brand_id, $product_key_word, $price, $color, $size, $discount,$sets, $credit_days);
                $arr[] = $obj; 
            }
            return $this->create_brand_listing($arr,$selet_arr);
        }
        else
        {
            return '';
            // if $records is empty
        }
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
                $obj->count_product  = $this->Product_listing_model->count_products($category, $sub_category, $sub_tosub_category, $brands, $product_key_word, $price, $record->color_id, $size, $discount, $sets, $credit_days);
                $arr[] = $obj; 
            }
            return $this->create_color_listing($arr, $selet_arr);
        }
        else
        {
            return '';
            // if $records is empty
        }
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
                $obj->count_product = $this->Product_listing_model->count_products($category, $sub_category, $sub_tosub_category, $brands, $product_key_word, $price, $color, $record->size_id, $discount,$sets, $credit_days);
                $arr[] = $obj; 
            }
            //print_r($arr);exit;
            return $this->create_size_listing($arr, $selet_arr);
        }
        else
        {
            return '';
            // if $records is empty
        }
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
                $obj->count_product =  $this->Product_listing_model->count_products($category, $sub_category, $sub_tosub_category, $brands, $product_key_word, $price, $color, $size, array($record->discount_url),$sets, $credit_days);
                $arr[] = $obj; 
            }
            return $this->create_discount_listing($arr, $selet_arr);
        }
        else
        {
            return '';
            // if $records is empty
        }
    }
    
    private function create_sets_info($category = '', $sub_category = '', $sub_tosub_category = '', $brands = '', $product_key_word = '', $price = '', $discount = '', $color, $size, $sets_info ='', $credit_days ='', $selet_arr)        
    {
        if(!empty($sets_info))
        {
            //print_r($selet_arr);exit;
            $arr = array();
            foreach($sets_info as $record)
            {
                $obj = new stdClass();
                $obj->set_id   =  $record->set_id;
                $obj->set_url  =  $record->set_url; 
                $obj->set_name =  $record->set_name;
                $obj->count_product =  $this->Product_listing_model->count_products($category, $sub_category, $sub_tosub_category, $brands, $product_key_word, $price, $color, $size, $discount, array($record->set_url), $credit_days);
                //echo $this->db->last_query();exit;
                $arr[] = $obj; 
            }
            //print_r($arr);exit;
            return $this->create_sets_listing($arr, $selet_arr);
        }
        else
            return '';
    }
    
    private function create_credit_days_info($category = '', $sub_category = '', $sub_tosub_category = '', $brands = '', $product_key_word = '', $price = '', $discount = '', $color, $size = '', $sets = '', $credit_days_info = '', $selet_arr)        
    {
        if(!empty($credit_days_info))
        {
            $arr = array();
            foreach($credit_days_info as $record)
            {
                $obj = new stdClass();
                $obj->credit_days_id   =  $record->credit_days_id;
                $obj->credit_days_name  =  $record->credit_days_name; 
                $obj->credit_days_url =  $record->credit_days_url;
                $obj->count_product =  $this->Product_listing_model->count_products($category, $sub_category, $sub_tosub_category, $brands, $product_key_word, $price, $color, $size, $discount,$sets, array($record->credit_days_url));
                //echo $this->db->last_query();
                $arr[] = $obj; 
            }
            return $this->create_credit_days_listing($arr, $selet_arr);
        }
        else
            return '';
    }
   public function get_product_list_by_ajax()
   {
        //$this->output->set_content_type('application/json');
        $data = $this->data;
        $json = array();
        $post = $this->input->get(null,true);
        //print_r($post);exit;
        if(isset($post))
        {
            $category = $sub_category = $sub_tosub_category = $brand = $product_key_word = $price = $color = $size = $discount = $sets = $credit_days = $sort_by = '';
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
                $sub_tosub_category = $post['sub_tosub_category'];
            }
            if(isset($post['brands']) && $post['brands'] != '')
            {
                $brand = $post['brands'];
            }
            if(isset($post['product_key_word']) && $post['product_key_word'] != '')
            {
                $product_key_word = trim(str_replace('%20',' ',$post['product_key_word']));
            }
            if(isset($post['price']) && $post['price'] != '')
            {
                $price = $post['price'];
            }
            if(isset($post['color']) && $post['color'] != '')
            {
                $color = $post['color'];
            }
            if(isset($post['size']) && $post['size'] != '')
            {
                $size = $post['size'];
            }
            if(isset($post['discount']) && $post['discount'] != '')
            {
                $discount = $post['discount'];
            }
            if(isset($post['sets']) && $post['sets'] != '')
            {
                $sets = $post['sets'];
            }
            if(isset($post['credit_days']) && $post['credit_days'] != '')
            {
                $credit_days = $post['credit_days'];
            }
            if(isset($post['page_num']) && $post['page_num'] != '')
            {
                $page_num = $post['page_num'];
            }
            if(isset($post['sort_by']) && $post['sort_by'] != '')
            {
                $sort_by = $post['sort_by'];
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
            $product_listing                    = $this->Product_listing_model->get_products_list($category, $sub_category, $sub_tosub_category, $brand, $product_key_word, $price, $color, $size, $discount,$sets, $credit_days, $page_num, $sort_by);
            $json['products_listing']           = $this->create_product_listing($product_listing);
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
            //echo $json['product_key_word'];
            //--===========sub to sub category info----==========
            $sub_tosub_category_info            = $this->Product_listing_model->get_sub_tosub_category($category, $sub_category, $sub_tosub_category);
            $json['sub_tosub_category_info']    = $this->create_sub_tosub_category_info($sub_tosub_category_info, $brand, $product_key_word, $price, $color, $size, $discount, $sets, $credit_days, $sub_tosub_category);
            $json['price_info']                 = $this->Product_listing_model->get_min_max_price($category, $sub_category, $sub_tosub_category, $brand, $product_key_word, $price, $color, $size, $discount, $sets, $credit_days);
            $json['status']                     = true;
        }
        echo json_encode($json);
   }
   private function create_subtosubcategory_listing($sub_tosub_category_info,$selet_arr)
   {
        if(!empty($sub_tosub_category_info))
        {
            $html = '';
            foreach($sub_tosub_category_info as $subtosub_category)
            {
                if($subtosub_category->count_product > 0)
                {
                    $select = '';
                    if($selet_arr != '')
                    {
                        if(in_array($subtosub_category->subtosub_category_url, $selet_arr) || in_array($subtosub_category->subtosub_category_id, $selet_arr))
                        {
                           $select = ' checked="checked"';
                           
                           $html .='<div class="radio">';
                           $html .='<label>';
                                $html .='<input type="radio" '.$select.' name="subtosub" id="subtosub-'.$subtosub_category->subtosub_category_id.'" url="'.$subtosub_category->subtosub_category_url.'"/><i class="helper"></i>'.$subtosub_category->subtosub_category_name.'<span>['.$subtosub_category->count_product.']</span>';
                           $html .='</label>';
                           $html .='</div>';
                           break;
                        }
                    }
                    else
                    {
                        $html .='<div class="radio">';
                        $html .='<label>';
                            $html .='<input type="radio" '.$select.' name="subtosub" id="subtosub-'.$subtosub_category->subtosub_category_id.'" url="'.$subtosub_category->subtosub_category_url.'"/><i class="helper"></i>'.$subtosub_category->subtosub_category_name.'<span>['.$subtosub_category->count_product.']</span>';
                        $html .='</label>';
                        $html .='</div>';
                    }
                }
            }
            return $html;
        }
    }
    private function create_product_listing($products)
    {
        //$this->load->helper('create_product');
        $data = $this->data;
        if(!empty($products))
        {
            $html = '<ul>';
            foreach($products as $product)
            {
                $html .= create_product_helper($product, $data['image_path']['product_image']);
            }
            $html .= '</ul>';
            return $html;
        }
        else
        {
            $html  = '<div class="no-results">';
                $html .= '<div class="dock-search"><!--<i class="material-icons search_icon">search</i> You searched for:  <span>cxx65656</span>--></div>';
                $html .= '<center><img src="assets/images/noresult--img.png" alt="" style="height:25%;width:25%;"></center>';
                $html .= '<center><h3>We couldn\'t find any matches!</h3></center>';
                $html .= '<center><p>Please check the spelling or try searching something else</p></center>';
            $html .= '</div>';
            return $html;
        }
    }
    public function create_quickview()
    {
        $data = $this->data;
        $json = array();
        $post = $this->input->get();
        $product_info = $this->Product_listing_model->get_product_info($post['product_id']);
        $product_info['image_path'] = $data['image_path']['product_image'];
        view_loader('custom/viw_quickview', $product_info);
    }            
    private function create_brand_listing($brands,$selet_arr)
    {
        return create_fillters($brands,$selet_arr,'brand');
    }
    private function create_color_listing($colors,$selet_arr)
    {
        return create_fillters($colors,$selet_arr, 'color');
    }
    private function create_size_listing($size_info, $selet_arr)
    {
        return create_fillters($size_info, $selet_arr, 'size');
    }
    private function create_discount_listing($discount_info, $selet_arr)
    {
        return create_fillters($discount_info, $selet_arr, 'discount');
    }
    private function create_sets_listing($set_info, $selet_arr)
    {
        return create_fillters($set_info, $selet_arr, 'set', 'set_id', SORT_ASC);
    }
    private function create_credit_days_listing($credit_days, $selet_arr)
    {
        return create_fillters($credit_days, $selet_arr, 'credit_days');
    }
    private function postback() 
    {
        if(isset($_GET['keywords']))
        {
            if(preg_match("/^<script>/", trim(strtolower($_GET['keywords']))))
            {
                show_404();
            }
            else
                return trim(str_replace('%20',' ',trim($_GET['keywords'])));
        }
        else
            return '';
        //return (isset($_GET['keywords']))?trim(str_replace('%20',' ',$_GET['keywords'])):'';
    }
}