<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Product_details extends BNM_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Product_details_model');
        $this->load->model('home_model');
    }
    
    public function index()
    { 
        $ip = $this->input->ip_address();
        $data = $this->data;
        
        $data['category']           = $this->uri->segment(1);
        $data['sub_category']       = $this->uri->segment(2);
        $data['item_name']          = $this->uri->segment(4);

        $product_id                 = make_decrypt($this->uri->segment(5));        
        //Get Product Details
        $data['page_settings']= $this->Comman_model->get_page_setting(36);
        $products_detail = $this->Product_details_model->get_products_list($product_id, $data['item_name']);
        //echo "<pre>";print_r($products_detail);exit;
        $data['product_sizes'] = $this->Product_details_model->get_product_size($products_detail->product_id);
        //echo "<pre>";print_r($product_sizes);exit;
        $data['page_settings']->page_title  = ucwords(str_replace('-', ' ', $data['item_name'])).' | Buy '.ucwords(str_replace('-', ' ',$data['item_name'])).' Products in BULK Online at Low Price in India Only on - '.$data['page_settings']->page_title ;
        $data['page_settings']->meta_description  = $products_detail->short_description;//ucwords(str_replace('-', ' ',$data['item_name']));
        $data['page_settings']->meta_keyword  = $products_detail->product_keyword;
        $data['page_settings']->meta_keyword  = $products_detail->search_terms;
        $data['product_images'] = $this->Product_details_model->get_product_images($product_id);
         
        
        
        if(empty($products_detail))
        {
            $this->load->view('view_404',$data);
        }
        else
        {
            $this->load->view('viw_details', $data);
        }
    }
    
    public function get_product_details()
    {
        $data = $this->data;
        $details_html = $image_html = $similar_html = $recent_html = $p_type = '';
        $ip = $this->input->ip_address();
        $post = $this->input->post();
        
        $product_id = make_decrypt($post['pro_id']);
        $data['page_settings']= $this->Comman_model->get_page_setting(36);
        $data['page_settings']->page_title  = ucwords(str_replace('-', ' ', $post['pro_url'])).' | Buy '.ucwords(str_replace('-', ' ',$post['pro_url'])).' Products in BULK Online at Low Price in India Only on - '.$data['page_settings']->page_title;
        $data['page_settings']->meta_description  = ucwords(str_replace('-', ' ',$post['pro_url']));
        $this->Product_details_model->Product_Buyer_Visitor($ip,$product_id);
        $products_details = $this->Product_details_model->get_products_list($product_id, $post['pro_url']);
        $product_sizes = $this->Product_details_model->get_product_size($product_id);        
        //echo "<pre>";print_r($product_color);exit;        
        $subtosub_cat_id = $this->Product_details_model->getSubtoSubCategory($product_id,$post['pro_url']);
        $data['page_settings']->meta_keyword  = $products_detail->search_terms;
        $product_images = $this->Product_details_model->get_product_images($product_id);
        $similar_products = Percentage_Calculate($this->Product_details_model->getSimilarProducts($subtosub_cat_id->subtosub_category_id));
        $recent_products  = Percentage_Calculate($this->home_model->get_products('','','',18,'',$ip,''));
        
        //---------------============================= Start code For Keeping track of logged user analytics =============================---------------
        if($product_id>0){
            if(!isset($this->user_id) || trim($this->user_id)==''){
                $user_id = 0;
            }else{
                $user_id = $this->user_id;
            }
            if(!isset($ip) || $ip==""){
                $ip = $this->input->ip_address();
            }
            $analytics_array = array(
                'user_id'    => $user_id,
                'ip_address' => $ip,
                'product_id' => $product_id,
                'date_added' => date('Y-m-d H:i:s'),
                'by_app_web' => 1, // 1 means by web, 2 means by app //testfortes
                'hit_count'  => 1
            );
            $on_duplicate = " hit_count = hit_count + 1";
            $this->Common_model->insert_analytics_record('tblblk_product_detail_analytics',$analytics_array,$on_duplicate);
        }
        //---------------============================= End of code For Keeping track of logged user analytics =============================---------------

        $image_html .= '<div class="large_view"> 
                        <a href="javascript:void(0)" id="add_wishlist_'.$products_details->product_id.'" class="wish_link"><i class="material-icons">favorite</i></a>
                        <img class="cloudzoom" alt ="" id ="detail-zoom" src="'.$data['image_path']['product_image'].'/'.$product_images[0]->image_name.'" data-cloudzoom= zoomSizeMode: "image", tintColor:"#000", tintOpacity:0.25, maxMagnification:4, autoInside:768>
                      </div>
                      <div class="thumbs">';
        foreach($product_images as $key=>$image) {
          $cls = ($key==0) ? "cloudzoom-gallery cloudzoom-gallery-active":"cloudzoom-gallery";
          $image_html .=  '<img class="'.$cls.'" width="64" src="'.$data['image_path']['product_image'].$image->image_name.'" alt ="" data-cloudzoom="useZoom:"#detail-zoom", image:"'.$data['image_path']['product_image'].$image->image_name.'"">';
        }
        $image_html .=  '</div>';
        
        if($products_details->category_id == 6) { $p_type = '/Pair'; } else { $p_type = '/Piece'; } // For Footwear Category

        $details_html .= '<div class="wbox">                       
                            <h3>'.ucwords(str_replace('_',' ',$products_details->item_name)).'</h3>           
                            <div class="rating_price_wrap clearfix">
                                <div class="price">
                                    <span class="selling-price" style="color:green;">&#x20B9; '.number_format($products_details->selling_price).''.$p_type.'</span>';
            $details_html .='</div>
			                <a href="#inquery" class="fancybox">Ask A Question</a>
                            <div id="inquery">
                                <p>Ask a question about this product.</p>
                                <form class="form-horizontal">
                                    <span class="green_text" style="text-align: center;" id="show_msg"></span>
                                    <input type="hidden" id="p" value="'.make_encrypt($products_details->product_id).'"/>
                                    <input type="hidden" id="s" value="'.make_encrypt($products_details->seller_id).'"/>
                                    <div class="form-group">
                                        <label>Name</label>
                                        <div class="col-value"><input value="" type="text" name="name" id="query_name" ><span class="red_text" id="query_name_error"></span></div>
                                    </div>
                                    <div class="form-group">
                                        <label>Mobile</label>
                                        <div class="col-value"><input value="" type="tel" name="mobile" id="query_mobile" maxlength="10"><span class="red_text" id="query_mobile_error"></span></div>
                                    </div>
                                    <div class="form-group">
                                        <label>Email</label>
                                        <div class="col-value"><input value="" type="email" name="email" id="query_email" ><span class="red_text" id="query_email_error"></span></div>
                                    </div>
                                    <div class="form-group">
                                        <label>Question</label>
                                        <div class="col-value"><textarea name="message" id="query_message"></textarea><span class="red_text" id="query_message_error"></span></div>
                                    </div>
                                    <div class="col-offset">
                                        <button type="button" id="send_product_enquiry">Send</button>
                                    </div>
                                </form>
                            </div>
			';
                                if(!empty($bulk_price)) {
                                    $details_html .= '<a href="javascript:void(0)" class="bulk_price_btn" id="bulk_price_btn">Buy in Bulk</a>';
                                }
           $details_html .= '</div>
                            <!--rating / price wrap end-->
                            <div class="btn_wrap clearfix">
                                <a href="user-wishlist" class="btn save_later_btn"><i class="material-icons">favorite</i>Save For Later</a>
                                <a href="javascript:void(0)" class="btn write_review_btn"><i class="material-icons">insert_comment</i>Write a Review</a>
                            </div>';
                            if(!empty($product_sizes))
                            {
            $details_html .=    '<div class="color_size_wrap clearfix">                                                                       
                                    <div class="select_size clearfix">
                                        <span>Select Size</span>';
                                    $active_class = 'class="active"';
                                    foreach($product_sizes as $key=>$size) 
                                    {   
                                        $color_id = $size->color_id;
            $details_html .=            '<a '.$active_class.' href="javascript:void(0);" id="pro_size_name" value="'.make_encrypt($size->size_id).'">'.$size->size_name.'</a>';
                                        $active_class = '';
                                    }                                     
            $details_html .=        '</div>                                    
                                </div>';
                            }
            $details_html .= '<div class="bulk_price_view" >
                                <div class="select_qty clearfix">
                                    <span>Update Quantity</span>
                                    <input type="number" id="bulk_qty" min="" max="" value="1">
                                    <div class="final_price" id="final_price" style="display: none;">&#x20B9; <span>8,000</span></div>
                                </div>
                                <h4>Buy in bulk quantities & save more!!</h4>
                                <table>
                                    <thead>
                                        <tr>
                                            <th>Select</th>
                                            <th>Quantity</th>
                                            <th>Discount</th>
                                            <th>Price Per Piece</th>
                                        </tr>
                                    </thead>
                                    <tbody>';
                                foreach($bulk_price as $prices)
                                {
            $details_html .=            '<tr>
                                            <td>
                                                <div class="form-radio">
                                                    <div class="radio">
                                                      <label>
                                                        <input type="hidden" name="single_price" class="single_price" value="'.make_encrypt($prices->bulk_price).'">
                                                        <input type="radio" name="price_radio" class="price_radio" value="'.make_encrypt($prices->bulk_range).'" /><i class="helper"></i>
                                                      </label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>'.$prices->bulk_range.'</td>
                                            <td>'.number_format((float)$prices->bulk_per, 2, '.', '').' %</td>
                                            <td>&#x20B9; '.$prices->bulk_price.'</td>
                                        </tr>';
                                }
            $details_html .=       '</tbody>
                                </table>
                            </div>';                            
        if(get_product_stocks(array('product_id'=>$products_details->product_id,'product_url'=>$products_details->product_url)) <= 0) {
           $details_html .= '<div class="soldout clearfix">
                                <strong>Sold Out</strong>
                                <p>This item is currently out of stock</p>
                                <!--<div class="form-group">
                                  <input type="email" name="sold_out" id="sold_item" required>
                                  <label for="input" class="control-label">Enter email to get notified</label><i class="bar"></i>
                                  <span class="validation_error" id="soldout_error"></span>
                                </div>
                                <button type="button" id="sold_item_btn" class="btn">Notify Me</button>-->
                            </div>';
            }
           $details_html .= '<div class="clearfix">
                                <div class="sortinfo">
                                    <div class="sort_specifications clearfix">                            
                                        <div class="spec clearfix">';
            if(!empty($products_details->product_feature_1) || !empty($products_details->product_feature_2) || !empty($products_details->product_feature_3) || !empty($products_details->product_feature_4) || !empty($products_details->product_feature_5)) 
            {
                    $details_html .=    '<div class="label">Features</div>
                                            <div class="list">
                                                <ul>';                      
                                                    if(!empty($products_details->product_feature_1)) { $details_html .= '<li>'.ucwords($products_details->product_feature_1).'</li>'; }
                                                    if(!empty($products_details->product_feature_2)) { $details_html .= '<li>'.ucwords($products_details->product_feature_2).'</li>'; }
                                                    if(!empty($products_details->product_feature_3)) { $details_html .= '<li>'.ucwords($products_details->product_feature_3).'</li>'; }
                                                    if(!empty($products_details->product_feature_4)) { $details_html .= '<li>'.ucwords($products_details->product_feature_4).'</li>'; }
                                                    if(!empty($products_details->product_feature_5)) { $details_html .= '<li>'.ucwords($products_details->product_feature_5).'</li>'; }
                    $details_html .=            '</ul>
                                            </div>';
            }
                    $details_html .=       '<div class="set_list">
                                                <ul>';
                                                    if(!empty($product_sizes[0]->color_name)) { $details_html .= '<li><strong>Color:</strong> '.ucwords($product_sizes[0]->color_name).'</li>'; }
                                                    if(!empty($products_details->set_description)) { $details_html .= '<li><strong>Set Description:</strong> '.ucwords($products_details->set_description).'</li>'; }
                                                    if(!empty($products_details->fabric)) {          $details_html .= '<li><strong>Fabric:</strong> '.ucwords($products_details->fabric).'</li>'; } 
                    $details_html .=                '<li><strong>Minimum Order:</strong> 1 SET </li>
                                                    <li><strong>MRP:</strong> &#x20B9; '.floor($products_details->standard_price).' / Piece</li>
                                                </ul>
                                             </div>
                                        </div>
                                        <!--features popup here-->                                        
                                    </div>
                                </div>
                                <!--sort specifications end-->
                                <div class="detail_right">
                                    <div class="pincode-widget clearfix">
                                        <label><i class="material-icons">location_on</i>Check Availability at</label>  
                                        <input type="text" name="pincode_availability" id="pincode_availability" placeholder="Enter Your Pincode" maxlength="6">
                                        <span class="validation_error" id="pincode_error"></span>
                                        <button type="button" id="btn_pincode_availability" >Check</button>                            
                                    </div>
                                    <div class="seller_badge">
                                        <div class="delivery_info_wrap">
                                            <div class="cash_on_delivery">
                                                <h5>Cash On Delivery <a class="fancybox" href="#delivery_cash"><i class="material-icons">help</i></a></h5>';
                                                if($products_details->cash_on_delivery =="Y") { $details_html .= '<p>Yes</p>'; } else { $details_html .= '<p>No</p>'; }
                        $details_html .=        '</p>
                                                <div id="delivery_cash">
                                                    <strong>How do I place a Cash on Delivery (C-o-D) order?</strong>
                                                    <p>All items that have the "Cash on Delivery Available" icon are valid for order by Cash on Delivery.</p>
                                                    <p>Add the item(s) to your cart and proceed to checkout. When prompted to choose a payment option, select "Pay By Cash on Delivery". Enter the OTP Code, for validation.</p>
                                                    <p>Once verified and confirmed, your order will be processed for shipment in the time specified, from the date of confirmation. You will be required to make a cash-only payment to our courier partner at the time of delivery of your order to complete the payment.</p>
                                                    <strong>Terms &amp; Conditions</strong>
                                                    <ul>
                                                        <li>The maximum order value for C-o-D is ?50,000.</li>
                                                        <li>e-Gift Vouchers or Store Credit cannot be used for C-o-D orders.</li>
                                                        <li>Cash-only payment at the time of delivery.</li>
                                                    </ul>
                                                    More answers in our <a href="'.base_url('help').'">Shipping FAQs</a>
                                                </div>
                                                <!--popup info end-->
                                            </div>
                                            <div class="return_policy">
                                                <p><strong>3 day</strong> Replacement Guarantee. <a class="fancybox" href="#return_policy"><i class="material-icons">help</i></a></p>
                                                <div id="return_policy">
                                                    <table>
                                                        <tr>
                                                            <th>Validity</th>
                                                            <th>Covers</th>
                                                            <th>Type Accepted</th>
                                                        </tr>
                                                        <tr>
                                                            <td>3 days from delivery</td>
                                                            <td>Damaged, Defective, Item not as described</td>
                                                            <td>Easy Replacement</td>
                                                        </tr>
                                                    </table>
                                                    <p>If you have received a damaged or defective product or if it is not as described, you can raise a replacement request on the Website/App/Mobile site within 3 days of receiving the product.</p>
                                                    <p>We shall help by verifying and trying to resolve your product issue as part of the return verification process. The seller will arrange for a replacement if the issue has not been resolved</p>
                                                    <p>Successful pick-up of the product is subject to the following conditions being met:</p>
                                                    <ul>
                                                        <li>Correct and complete product (with the original brand/product Id/undetached MRP tag/products original packaging/freebies and accessories)</li>
                                                        <li>The product should be in unused, undamaged and original condition without any scratches or dents</li>
                                                        <li>Before returning a Mobile/Laptop/Tablet, the device should be formatted and iCloud accounts should be unlocked for iOS devices</li>
                                                    </ul>
                                                    More answers in our <a href="'.base_url('help').'">Shipping FAQs</a>
                                                </div>
                                                <!--popup info end-->
                                            </div>
                                        </div>
                                    </div>
                                    <!--end seller info-->
                                </div>
                                <!--end detail right-->
                            </div>
                            <div class="warranty-text">';
                                          if(!empty($products_details->product_full_description)) { 
                        $details_html .=    '<h4>DESCRIPTION</h4>
                                            <p>'.substr(str_replace('_',' ',ucwords($products_details->product_full_description)),0,500).'</p>';
                                          } else {
                        $details_html .=    '<h4>SPECIFICATION</h4>
                                            <p>'.ucwords($products_details->short_description).'</p>';
                                          }
                        $details_html .='</div>';
                        
    if(get_product_stocks(array('product_id'=>$products_details->product_id,'product_url'=>$products_details->product_url)) > 0) { 
        $details_html .=    '<div class="btn_wrap_bottom clearfix">
                                <a href="javascript:void(0);" class="btn add_to_cart" pid="'.make_encrypt($products_details->product_id).'" sid="'.make_encrypt($product_sizes[0]->size_id).'"><i class="material-icons">add_shopping_cart</i>Add to Cart</a>
                                <a href="javascript:void(0)" class="btn buy_now" pid="'.make_encrypt($products_details->product_id).'" sid="'.make_encrypt($product_sizes[0]->size_id).'">Buy Now</a>
                            </div>';
    }
        $details_html .=    '<div class="cart_noti wishlist_path" style="display: none;">
                             <p><i class="fa fa-check-circle" aria-hidden="true"></i> Product Added to Wishlist Successfully. <a href="user-wishlist">Go to wishlist</a></p>
                            </div>
                          <!--</form>-->
                        </div>';
        
        $similar_html .='<div class="header_title clearfix">
                            <h2>Similar Products</h2>
                            <!--<a href="javascript:void(0)" class="viewall_btn">View All</a>-->
                        </div>
                        <div class="owl-carousel product-carousel">';
        foreach($similar_products as $keyy=>$products) 
        {
            $item_name = ((strlen($products->item_name) > 20)?substr(ucwords(strtolower(str_replace('_',' ',$products->item_name))),0,20).'...':ucwords(strtolower(str_replace('_',' ',$products->item_name))));
            $similar_html .='<div class="item">
                                <div class="item_wrap">
                                  <a href="'.$products->category_url.'/'.$products->sub_category_url.'/'.$products->subtosub_category_url.'/'.$products->product_url.'/'.make_encrypt($products->product_id).'">
                                    <figure><img class="lazy" data-original="'.$data['image_path']['product_image'].'home/'.$products->image_name.'" alt="'.ucwords($products->item_name).'"></figure>
                                    <div class="product_info">
                                    <h3>'.$item_name.'</h3>
                                      <div class="price">
                                          <span class="standard-price" style="color:green;">&#x20B9; '.floor($products->selling_price).''.$p_type.'</span>
                                      </div>
                                    </div>
                                  </a>
                              </div>              
                          </div>'; 
                      
        }
        $similar_html .='</div>';    


        $recent_html .='<div class="header_title clearfix">
                            <h2>Recently View Products</h2>
                            <!--<a href="javascript:void(0)" class="viewall_btn">View All</a>-->
                        </div>
                        <div class="owl-carousel product-carousel">';
        foreach($recent_products as $keyy=>$products) 
        {
            $item_name = ((strlen($products->item_name) > 20)?substr(ucwords(strtolower(str_replace('_',' ',$products->item_name))),0,20).'...':ucwords(strtolower(str_replace('_',' ',$products->item_name))));
            $recent_html .='<div class="item">
                                <div class="item_wrap">
                                  <a href="'.$products->category_url.'/'.$products->sub_category_url.'/'.$products->subtosub_category_url.'/'.$products->product_url.'/'.make_encrypt($products->product_id).'">
                                    <figure><img class="lazy" data-original="'.$data['image_path']['product_image'].'home/'.$products->image_name.'" alt="'.ucwords($products->item_name).'"></figure>
                                    <div class="product_info">
                                      <h3>'.$item_name.'</h3>
                                      <div class="price">
                                          <span class="standard-price" style="color:green;">&#x20B9; '.floor($products->selling_price).' / Piece</span>
                                      </div>
                                    </div>
                                  </a>
                              </div>              
                          </div>'; 
                      
        }
        $recent_html .='</div>';
        
        $json['product_details'] = $details_html;
        $json['similar_html'] = $similar_html; 
        $json['recent_html'] = $recent_html;
        $json['image_html'] = $image_html;
        $json['item_name']  = $products_details->item_name;

        echo json_encode($json);
    }
    
    public function checkPincode()
    {
        $json     = array();
        $data     = $this->data;
        $formdata = $this->input->post('pincode');
        if(empty($formdata)) 
        {
            $json['pincode_error'] = 'Please Enter Pincode.';
        } 
        else if(strlen($formdata) < 6)
        {
            $json['pincode_error'] = 'Please Enter Valid Pincode.';
        }
        else 
        {
            $status = checkPincodeAvailability($formdata);
            if (empty($status)) 
            {
                $json['pincode_error'] = 'No sellers deliver in this area.';
            } 
            else 
            {
                $json['pincode_success'] = 'Available! delivery in your area.';
            }
        }
        echo json_encode($json);
    }
    public function calculate_bulk_price_on_radio()
    {
        $data = $this->data;
        $json     = array();
        $formdata = $this->input->post();
        
        if(!empty($formdata))
        {
            $bulk_range = explode('-', make_decrypt($formdata['bulk_range']));            
            $json['min_range'] = $bulk_range[0];
            $json['max_range'] = $bulk_range[1];
            $single_price = make_decrypt($formdata['single_price']);
            $this->session->set_userdata('bulk_qty',$bulk_range[0]);
            $json['total_price'] = ($single_price * $bulk_range[0]); // based on minimun range    
        }   
        echo json_encode($json);
    }

    public function update_bulk_price()
    {
        $data = $this->data;
        $json     = array();
        $formdata = $this->input->post();
        if(!empty($formdata))
        {
            $bulk_qty = $formdata['bulk_qty'];
            $bulk_range = explode('-', make_decrypt($formdata['bulk_range']));
            if($bulk_range[0] <= $bulk_qty && $bulk_range[1] >= $bulk_qty)
            {
                $this->session->set_userdata('bulk_qty',$bulk_qty);
                $single_price = make_decrypt($formdata['single_price']);
                $json['total_price'] = ($single_price * $bulk_qty); // based on minimun range 
            }
        }
        echo json_encode($json);
    }
    
    public function save_product_enquiry()
    {
        $data   = $this->data;
		$response   = array(); 
		$post   = $this->input->post();
        $post['product_id'] = make_decrypt($post['product_id']);
        $post['seller_id']  = make_decrypt($post['seller_id']);
		$status = $this->Product_details_model->save_product_query($post);
		if($status)
		{
			$response['success'] = 'Thanks ! We will get back to you shortly.';	
		}
		else
		{
			$response['failed'] = 'Failed ! Please Try Again.';
		}
		echo json_encode($response);
    }
    
    public function show_404_page()
    {
        $data = $this->data;
        $data['page_settings']= 'Page Requested Not Found !!';
        $this->load->view('view_404',$data);
    }
}