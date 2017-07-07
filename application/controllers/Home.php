<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Home extends BNM_Controller
{
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('home_model');
    }
    public function index()
    {
        $data = $this->data;
        $data['page_settings']   = $this->Comman_model->get_page_setting(1);
        $this->load->view('viw_home', $data);
    }

    public function get_products()
    {
        $data = $this->data;
        $json = array();
        $new_html = $offer_html = $kurtis_html = $mens_html = $womens_html = $kids_html = $home_html = $recent_html = '';
        $ip = $this->input->ip_address();
        $new_products1  = $this->home_model->get_products(2,'','',5,'','','new');
        $new_products2  = $this->home_model->get_products(1,'','',5,'','','new');
        $new_products3  = $this->home_model->get_products(3,'','',5,'','','new');
        $new_products_all  = array_merge($new_products1,$new_products2);
        $new_products_all  = array_merge($new_products_all,$new_products3);
        $new_products_all  = array_merge($new_products_all,$this->home_model->get_products(4,'','',5,'','','new'));
        $offer_products  = $this->home_model->get_products('','','',18,'','','');
        $kurtis_products  = $this->home_model->get_products('','',45,18,'','','');
        $men_products    = $this->home_model->get_products(1,'','',18,'','','');
        $women_products  = $this->home_model->get_products(2,'','',18,'','','');
        $footwear_products  = $this->home_model->get_products('',42,'',18,'','','');
        $kids_products   = $this->home_model->get_products(3,'','',18,'','','');
        $home_products   = $this->home_model->get_products(4,'','',18,'','','');
        $recent_products = $this->home_model->get_products('','','',18,'',$ip,'');
        
        $product_image = $data['image_path']['product_image'];

        $new_title = 'New Arrival Products';
        $new_html = $this->get_home_html($new_title,$product_image,'',$new_products_all);
        
        $offer_title = 'Best Offer on Products';
        $offer_html = $this->get_home_html($offer_title,$product_image,'',$offer_products);
        
        $kurtis_title = 'Best Deal for Kurtis';
        $kurtis_html = $this->get_home_html($kurtis_title,$product_image,'',$kurtis_products);
        
        $men_title = 'Best Deal for Men';
        $mens_html = $this->get_home_html($men_title,$product_image,$this->create_category_id($men_products),$men_products);
        
        $women_title = 'Best Deal for Women';
        $womens_html = $this->get_home_html($women_title,$product_image,$this->create_category_id($women_products),$women_products);
        
        $footwear_title = 'Footwear for Women';
        $footwears_html = $this->get_home_html($footwear_title,$product_image,'',$footwear_products);

        $kids_title = 'Best Deal for Kids';
        $kids_html = $this->get_home_html($kids_title,$product_image,$this->create_category_id($kids_products),$kids_products);

        $home_title = 'Best Deal on Home Furnishing';
        $home_html = $this->get_home_html($home_title,$product_image,$this->create_category_id($home_products),$home_products);

        $recent_title = 'Recent View Products';
        $recent_html = $this->get_home_html($recent_title,$product_image,'',$recent_products);

        $json['new_html']       = $new_html;
        $json['offer_html']     = $offer_html;
        $json['kurtis_html']    = $kurtis_html;
        $json['mens_html']      = $mens_html;
        $json['womens_html']    = $womens_html;
        $json['footwears_html'] = $footwears_html;
        $json['kids_html']      = $kids_html;
        $json['home_html']      = $home_html;
        $json['recent_html']    = $recent_html;
        echo json_encode($json);

    }

    public function get_home_html($title,$product_image,$item_id,$products_data)
    {
        $full_html = '';
        if(!empty($products_data))
        {
            $full_html  .= '<div class="header_title clearfix">
                                <h2 style="color:#ff5722">'.$title.'</h2>';
                                if(!empty($item_id))
                                {
                                    $full_html  .=    '<a href="view-all-products/'.make_encrypt($item_id).'" class="viewall_btn">View All</a>';                       
                                }             
            $full_html  .= '</div>
                            <div class="owl-carousel">';
            foreach ($products_data as $key => $value) {
                $value->image = $product_image;
                $full_html .=  $this->create_home_html($value);
            }
            $full_html .= '</div>';
        }
        return $full_html;
    }

    public function create_home_html($product)
    {
        $item_name = ((strlen($product->item_name) > 20)?substr(ucwords(strtolower(str_replace('_',' ',$product->item_name))),0,20).'...':ucwords(strtolower(str_replace('_',' ',$product->item_name))));
        $html = $p_type = '';
        if($product->category_id == 6) { $p_type = '/Pair'; } else { $p_type = '/Piece'; }
        $html = '<div class="item">
                    <div class="item_wrap">
                        <a href="'.$product->category_url.'/'.$product->sub_category_url.'/'.$product->subtosub_category_url.'/'.$product->product_url.'/'.make_encrypt($product->product_id).'">
                            <figure><img class="lazy" src="'.$product->image.'home/'.$product->image_name.'" alt="'.$product->item_name.'"></figure>
                            <div class="product-hover-state-top">
                                <p>'.$product->set_description.'</p>
                            </div>
                            <div class="product_info">
                                 <h3>'.$item_name.'</h3>
                                <div class="price">
                                    <span class="standard-price" style="color:green">&#x20B9; '.floor($product->selling_price).' '.$p_type.'</span>
                                </div>
                            </div>
                        </a>
                    </div>                        
               </div>';
        return $html;       
    }
    
    public function show_Sub_Categorys()
    {
        $data = $this->data;
        $data['page_settings'] = $this->Comman_model->get_page_setting(2);
        $data['sub_cat_url'] = $this->uri->segment(2);
        $category_url = $data['sub_cat_url'];
        $data['page_settings']->page_title = $data['page_settings']->page_title .ucfirst($data['sub_cat_url']);
        $data['sub_cat_page_banner'] = $this->Comman_model->get_category_page_banners($category_url, $sub_cat = '', $subtosub_cat = '', 2, 'middle');
        //echo "<pre>";print_r($data['page_banner']);exit;
        $this->load->view('shared/viw_subCatgories', $data);
    }
    public function get_sub_category_banner()
    {
        $data = $this->data;
        $this->load->library('user_agent');        
        $_temp = "";
        if($this->agent->is_mobile()){
          $_temp = "responsive_banner_images/";
        }
        $category_url = $_GET['category_id'];
        $page_banner = $this->Comman_model->get_category_page_banners($category_url,$sub_cat = '', $subtosub_cat = '', 2, 'top');
        $banner_images = '';
        foreach($page_banner as $banner): 
			if($banner->banner_redirection == '' || $banner->banner_redirection == 'javascript:void(0)' || $banner->banner_redirection == '#') 
			{
				$banner_images .= '<a href="javascript:void(0);"><img src="'.$data['image_path']['banner_image'].$_temp.$banner->banner_image.'" alt="'.$banner->banner_image.'"></a>';
			} else { 
            $banner_images .= '<a href="/products/'.$banner->banner_redirection.'"><img src="'.$data['image_path']['banner_image'].$_temp.$banner->banner_image.'" alt="'.$banner->banner_image.'"></a>';
			}
        endforeach; 
        echo $banner_images;
    }
    public function get_page_banner()
    {
        $data = $this->data;
        $this->load->library('user_agent');        
        $_temp = "";
        if($this->agent->is_mobile()){
          $_temp = "responsive_banner_images/";
        }
        $page_banner = $this->Comman_model->get_page_banners(1);
        $banner_images = '';
        foreach($page_banner as $banner) {
			if($banner->banner_redirection == '' || $banner->banner_redirection == '#' || $banner->banner_redirection == 'javascript:void(0)')  
			{
			     
			     if($this->agent->is_mobile())
                 {
                    $banner_images .= '<a href="javascript:void(0)"><img src="'.base_url().'admin/assets/banner_image/'.$_temp.$banner->responsive_images.'" alt="'.$banner->responsive_images.'"></a>';
                 }
                 else 
                 {
                    $banner_images .= '<a href="javascript:void(0)"><img src="'.base_url().'admin/assets/banner_image/'.$_temp.$banner->banner_image.'" alt="'.$banner->banner_image.'"></a>';   
                 }				 
			}
            else 
            {   
                if($this->agent->is_mobile())
                 {
                    $banner_images .= '<a href="/products/'.$banner->banner_redirection.'"><img src="'.base_url().'admin/assets/banner_image/'.$_temp.$banner->responsive_images.'" alt="'.$banner->responsive_images.'"></a>';
                 }
                 else 
                 {
                    $banner_images .= '<a href="/products/'.$banner->banner_redirection.'"><img src="'.base_url().'admin/assets/banner_image/'.$_temp.$banner->banner_image.'" alt="'.$banner->banner_image.'"></a>';   
                 }
                 
            } 
        } 
        echo $banner_images;
    }
    public function get_keywords_list()
    {
        $data          = $this->data;
        $search_string = trim($_GET['keywords']);        
    	$arr           = array();
        $search_list   = $this->Comman_model->get_search_sub_category_product($search_string);
    	foreach($search_list as $sub_subcategory)// add sub category in autocomplete box
    	{ 
    		$arr[]  = array('label' => $sub_subcategory->sub_category_name,'url' => $sub_subcategory->sub_category_url,'category' => 'Categories');
    	}
        $search_list   = $this->Comman_model->get_search_subtosub_category($search_string);
    	foreach($search_list as $subto_subcategory)// add sub to sub category in autocomplete box
    	{ 
    		$arr[]  = array('label' => $subto_subcategory->subtosub_category_name,'url' => $subto_subcategory->subtosub_category_url,'category' => 'Categories');
    	}
        $search_list = $this->Comman_model->get_search_brand($search_string);
    	foreach($search_list as $brand)// add Brand in autocomplete box
    	{ 
    		$arr[]  = array('label' => $brand->brand_name,'url' => $brand->brand_url,'category' => 'Brands');
    	}
        $search_list   = $this->Comman_model->get_product_kewwords($search_string);
        //echo $this->db->last_query();
        /*foreach($search_list as $key_words)// add add product key words in autocomplete box
    	{
    	   if(!empty($key_words))
           {
              $product_keyword = explode(',', $key_words->product_keyword);
              $keyword_url = explode(',', $key_words->keyword_url);
              
              for($i = 0; $i < count($product_keyword); $i++)
              {
                $arr[]  = array('label' => $product_keyword[$i],'url' => $product_keyword[$i],'category' => 'All Others');
              }  
           }
    	}*/
        
        $arr = $this->array_unique_by_key($arr, 'url');
        $this->array_sort_by_column_value($arr, 'url', SORT_ASC);
        echo json_encode($arr);
    }
    private function array_unique_by_key (&$array, $key) 
    {
        $tmp = $result = array();
        foreach ($array as $value) 
        {
            if (!in_array($value[$key], $tmp)) 
            {
                array_push($tmp, $value[$key]);
                array_push($result, $value);
            }
        }
        return $array = $result;
    }
    private function array_sort_by_column_value(&$array, $col, $dir = SORT_ASC) 
    {
        $sort_col = array();
        foreach ($array as $key=> $row) 
        {
            $sort_col[$key] = $row[$col];
        }
        array_multisort($sort_col, $dir, $array);
    }
	//=========for download app.=============
	public function send_link_todownload_app()
	{
		$json					= array();
		$bulkapp_link 			= 'https://www.bulknmore.com/assets/downloads/BulknMore.apk';//https://play.google.com/store/apps/details?id=com.bulknmore.app
		$mobile					= $this->input->post('mob_no');
		sending_otp($mobile,'We love your smartness for choosing to download our app. Now lets get you started, just click on '.$bulkapp_link.'');
		$json['mobileNo']		= $mobile;
		echo json_encode($json);	
	}
    
	public function download_app($document='')
    {	
        if(is_file("assets/downloads/".$document)){
            $this->load->helper('download');    
            $path = file_get_contents("assets/downloads/".$document."");
            force_download($document,$path);
        }
        else
        	redirect($_SERVER['HTTP_REFERER']);
    }
    private function create_category_id($data)
    {
        if(!empty($data))
        {
            foreach($data as $key=>$value)
            {
                $category_id = $value->category_id;
            }
            return $category_id;
        }
    }
    private function create_sub_category_id($data)
    {
        if(!empty($data))
        {
            foreach($data as $key=>$value)
            {
                $sub_category_id = $value->sub_category_id;
            }
            return $sub_category_id;
        }
    }
    public function view_all_home_products()
    {
        $data = $this->data;
        $data['page_settings'] = $this->Comman_model->get_page_setting(1);
        $this->load->view('shared/viw_all_home_products', $data);
    }
    public function get_all_products()
    {
        $data = $this->data;
        $response = array();
        $html = $title = '';
        $total_products = 0;
        $post = $this->input->post();
        $category_id = make_decrypt($post['category_id']);
        $products = Percentage_Calculate($this->home_model->get_products($category_id,'','','',$post['page_no'],'',''));
        if(!empty($products))
        {
            if($category_id == 1)
            {
                $title = 'Best Deals For Men';
            }
            else if($category_id == 2)
            {
                $title = 'Best Deals For Women';
            }
            else if($category_id == 3)
            {
                $title = 'Best Deals For Kids';
            }
            else if($category_id == 4)
            {
                $title = 'Best Deals on Home Furnishing';
            }
            else if($category_id == 5)
            {
                $title = 'Best Deals on Valentine';
            }
            else
            {
                $title = 'Best Offer on Products';
            }
            $html .= '<ul>';
            foreach($products as $key=>$product)
            {
                $item_name = '';
                if(strlen($product->item_name) > 30)
                {
                    $item_name = substr($product->item_name,0,40);
                }
                else
                {
                    $item_name = $product->item_name;
                }
                $html .= '<li>
                            <div class="item_wrap">
                                <a href="'.$product->category_url.'/'.$product->sub_category_url.'/'.$product->subtosub_category_url.'/'.$product->product_url.'/'.make_encrypt($product->product_id).'">
                                      <figure><img class="lazy"  alt="'.ucwords($item_name).'" src="'.$data['image_path']['product_image'].'home/'.$product->image_name.'"></figure>
                                      <div class="product_info">
                                          <h3>'.ucwords($item_name).'</h3>
                                          <div class="price">
                                              <span class="standard-price" style="color:green;">&#8377; '.$product->selling_price.' /Piece</span>                                              
                                          </div>                                          
                                      </div>
                                  </a>
                              </div>
                        </li>';            
            }
            $html .=    '</ul>';            
        }
        else
        {
            $html .= '<h3 style="text-align:cenetr">NO MORE RECORD</h3>';
        }
        $response['html'] = $html;
        $response['title'] = $title;
        echo json_encode($response);
    }
    public function test()
    {
        echo facebook_url();exit();
        
    }
	public function save_franchise_data()
	{
		$data = $this->data;
		$json = array();
		$post = $this->input->post();
		$form_array = array();
		$obj = new stdClass();
			$obj->name = $post['name'];
			$obj->email = $post['email'];
			$obj->mobile = $post['mobile'];
			$form_array = $obj;
		$status  = $this->home_model->insert_record('tblblk_franchise_visitors',$form_array);
		if($status > 0)
		{
			$json['success'] = 1;
		}
		else
		{
			$json['success'] = 0;
		}
		echo json_encode($json);
	}
}