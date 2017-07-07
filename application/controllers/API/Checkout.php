<?php
defined('BASEPATH') OR exit("No direct script access allowed");
require (APPPATH.'third_party/razorpay/Razorpay.php');
use Razorpay\Api\Api;
class Checkout extends BNM_Controller 
{	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('API/Checkout_model');
        $this->load->library('app_auth_lib');
        $this->load->library('email');        
	}
	public function Delivery_Address()
	{
		$data = $this->data;
        $json = array();
        $post = $this->input->post();
        //print_r($post);exit;
        if($post['deliveryId'] != 0)
        {
        	$result = $this->Checkout_model->UpdateDeliveryAddress($post);
            if(!empty($result))
            {
                $addressList = $this->Checkout_model->getDeliveryAddress($post['userid']);
                $responce['address_list'] = $addressList;
                $responce["success"] = 1;
            }
            else
            {
                $responce["success"] = 0;
            }                                            
        }
        elseif($post['deliveryId'] == 0 && $post['dstatus'] == 'Y')
        {   
        	$result = $this->Checkout_model->insertDeliveryAddress($post,$post['dstatus']);
            
            if(!empty($result) && $result>0) 
            {   
                $addressList = $this->Checkout_model->getDeliveryAddress($post['userid']);
                $responce['address_list'] = $addressList;
                $responce["success"] = 1;
            }
            else 
            {
                $responce["success"] = 0;
            }
        }
        else
        {
        	$result = $this->Checkout_model->add_delivery_address($post,$post['dstatus']);
        	if(!empty($result))
        	{
        		$addressList = $this->Checkout_model->getDeliveryAddress($post['userid']);
        		$responce['address_list'] = $addressList;
        		$responce["success"] = 1;
        	}
        	else
        	{
        		$responce["success"] = 0;
        	}
        }
        echo json_encode($responce);
	}
        public function delete_delivery_address()
        {
                $data = $this->data;
                $json = array();
                $post = $this->input->post();
                $status = $this->Checkout_model->deleteDeliveryAddress($post);
                if($status)
                {
                       $responce["success"] = 1;
                        $responce['message'] = "Address Removed Successfully!";
                }
                else
                {
                        $responce["success"] = 0;
                }
                echo json_encode($responce);
        }
        public function updateUserProfile()
        {
                $data = $this->data;
                $json = array();
                $post = $this->input->post();
                $status = $this->Checkout_model->updateUserProfile($post);
                if($status)
                {       $active_user_info = get_user_info($post['userid']);
                        if(!empty($active_user_info))
                        {
                            $userData = $this->Checkout_model->getUserData($post['userid']);                        
                            $responce["success"] = 1;
                            $responce['user_details'] = $userData;    
                        }
                        else
                        {                        
                            $responce["success"] = 2;
                            $responce['message'] = 'Account Deactivated. Contact Admin.';
                        }
                        
                }
                else
                {
                        $responce["success"] = 0;
                }
                echo json_encode($responce);
        }
        public function PincodeDetails()
        {
                $data = $this->data;
                $json = array();
                $pincode = $this->input->post('pincode');
                if(empty($pincode))
                {
                        $responce["success"] = 0;
                        $responce['message'] = "Please Enter Pincode!";
                }
                else
                {
                        $list = $this->Checkout_model->getPincodeDetails($pincode);
                        if(!empty($list))
                        {
                                $responce["success"] = 1;
                                $responce['pincode_details'] = $list;
                        }
                        else
                        {
                                $responce["success"] = 2;
                                $responce['message'] = "Please Enter Valid Pincode!";
                        }
                }                
                echo json_encode($responce);
        }
        public function changePassword()
        {
            $data = $this->data;
            $json = array();
            $post = $this->input->post();
            $oldpass = $this->Checkout_model->getPassword($post);
            if(!empty($oldpass))
            {  
                $status = $this->Checkout_model->changePassword($post);
                if($status)
                {                       
                    $responce["success"] = 1;
                    $responce['message'] = "Password Successfully Changed!";
                }
                else
                {
                    $responce["success"] = 0;
                }                        
            }
            else
            {
                $responce["success"] = 2;
                $responce['message'] = "Please Enter Correct Old Password!";
            }               
            echo json_encode($responce);
        }
    public function Add_Wishlist()
    {
        $data = $this->data;
        $json = array();
        $product_id = $this->input->post('product_id');
        $userid = $this->input->post('user_id');
        
        $info = array('user_id' => $userid,'product_id' => $product_id);
        //print_r($info);exit;
        if(!empty($product_id))
        {   
            $wish_id = $this->Checkout_model->add_Wishlist($info,$product_id);           
            if(!empty($wish_id)){
                $responce["success"] = 1;
                $responce['wishlist_id'] = $wish_id;
                $responce['message']    = "Moved Successfully.";
            }
            else{
                $responce["success"] = 0;
                $responce['message']     = "Try Again."; 
            }
        }               
        echo json_encode($responce);
    }
    public function get_Wish_List()
    {
        $data = $this->data;
        $json = array();
        $post = $this->input->post();
        $wishlist   =   $this->Checkout_model->user_Wish_List($post['user_id']);
        if(!empty($wishlist))
        {
           foreach ($wishlist as $key=>$value) 
            {
                $standard_price = $value->standard_price;
                $selling_price  = $value->selling_price;
                $saving_price   = $standard_price - $selling_price;
                $offer_per      = floor(($saving_price * 100) / $standard_price);
                $wishlist[$key]->offer_per = $offer_per;
            }
            $responce["success"] = 1;
            $responce['wishlist'] = $wishlist; 
        }
        else
        {
            $responce["success"] = 0;
            $responce['wishlist_img'] = 'assets/images/app_images/no_wishlist.png';
        }
        echo json_encode($responce);
    }
    public function deleteWishList()
    {   
        $json = array();
        $wishlist_id = $this->input->post('wishlist_id');
        $status = $this->Checkout_model->delete_Wish_List($wishlist_id);
        if($status)
        {
            $responce["success"] = 1;
            $responce['message']    = 'Successfully Deleted...!';
        }
        echo json_encode($responce);
    }
    public function getDeliveryANDTaxAmount()
    {
        $json = array();
        $total_amt = $this->input->post();
        if(!empty($total_amt))
        {
            $json['status'] = 1;
            $json['delivery_charge'] = getDeliveryCharges(array('del_charge_per'=>1.6,'total_amt'=>$total_amt));
            $json['tax_charge'] = get_tax_total(5.5,$total_amt);
        }
        else
        {
            $json['status'] = 0;
            $json['message'] = 'Wrong Request!';
        }
        echo json_encode($json);
    }
    public function ValidateOrderOTP()
    {
        $json = array();
        $post = $this->input->post();
        //echo "<pre>"; print_r($formdata);exit;
        if (!empty($post)) {
            $mobile = $post['checkout_mobile'];
            $user_id = $post['user_id'];
            $otp = rand(1000, 9999);
            $msg = "Your Order OTP Verification code is-" . $otp;
            $active_user_info = get_user_info($user_id);
            if(!empty($active_user_info))
            {
                $api = sending_otp($mobile, $msg);
                if ($api) {
                    $responce['status'] = 1;
                    $this->Checkout_model->ResetOrdersOTP($user_id);
                    $responce['message'] = 'Your OTP Verification Code is Successfully Send!';
                    $this->Checkout_model->OrderOTP($user_id,$otp);
                } else {
                    $responce['status'] = 0;
                    $responce['message'] = 'Oops! Something went wrong, Please Try Again!';
                }
            }
            else
            {
                $responce['status'] = 2;
                $responce['message'] = 'Account Deactivated. Contact Admin.';
            }
            
        }
        echo json_encode($responce);
    }
    public function VerifyProductOrderOTP()
    {
        $json = array();
        $post = $this->input->post();
        //echo "<pre>"; print_r($formdata);exit;
        $otp = $post['order_otp_number'];
        $user_id = $post['user_id'];
        $otpcode = $this->Checkout_model->verifyProductOrderOTP($user_id,$otp);
        $active_user_info = get_user_info($user_id);
        if(!empty($active_user_info))
        {
            if ($otpcode) {
                $responce['status'] = 1;
                $responce['message'] =
                    'You Successfully Verify Your OTP! Please Go Ahead and Place Your Order.';
                $this->Checkout_model->ResetOrdersOTP($user_id);
            } else {
                $responce['status'] = 0;
                $responce['message'] = 'Please Enter Correct OTP!';
            }
        }
        else
        {
            $responce['status'] = 2;
            $responce['message'] = 'Account Deactivated. Contact Admin.';
        }
        echo json_encode($responce);
    }
    public function PlaceOrder()
    {
        $post = $this->input->post();
        $products = array();
        if (!empty($post['cart_info'])) 
        {
            if(isset($post['wallet_amount']))
            {
                $delivery_id = $post['delivery_id'];                
                $this->order_procces($post['cod_type'],'',$delivery_id,$product_iamge_path='',$post['cart_info'],$post['user_id'],$bank_name='',$payment_status='',$amount='',$post['wallet_amount']);                
            }           
            else
            {
                $delivery_id = $post['delivery_id'];
                $this->order_procces($post['cod_type'],'',$delivery_id,$product_iamge_path='',$post['cart_info'],$post['user_id'],$bank_name='',$payment_status='',$amount='',$wallet_status='');
            }
        } else {
            $responce['status'] = 0;
            echo json_encode($responce);
        }
    }
    private function order_procces($order_type,$razorpay_payment_id = '', $delivery_id, $product_iamge_path, $cart_info, $user_id,$bank_name,$payment_status,$online_pay_amount,$wallet_amount)
    {
        $shipping_charges = 0; 
        $user_wallet = $this->Checkout_model->getUserWallet($user_id);
        $cart_array = json_decode(utf8_encode($cart_info), true);
        //print_r($cart_array);exit;
        $cart_info 	  = create_cart_product_listing($cart_array);
        foreach ($cart_array as $value) 
        {
            if(get_product_stocks(array('product_id'=>$value['product_id'],'product_url'=>$value['product_url'])) > 0) 
            {
                $logistic_details   = get_logistic_charges($value['product_id'],$delivery_id,$value['qty']);
                $shipping_charges  += $logistic_details['shipping_charges'];
                $logistic_id        = $logistic_details['logistic_id'];
                $objcate = new stdClass();
                $objcate->pid = $value['product_id'];
                $objcate->voucher_code = empty($value['voucher_code'])? 0 :$value['voucher_code'];
                $objcate->image = $value['image'];
                $objcate->seller_id = $value['seller_id'];
                $objcate->pname = $value['name'];
                $objcate->pquantity = $value['qty'];
                $objcate->pprice = $value['price'];
                $objcate->pcolor = $value['color_name'];            
                $products[] = $objcate;
            }            
        }
        $cart_total = ($cart_info['cart_totals']['cart_total'] + $shipping_charges);
        if($order_type == 5 || $order_type == 2 || $order_type == 3) 
        {
            $order_id_array = $this->Checkout_model->OrderProducts($shipping_charges,$logistic_id,$user_id,$cart_total, $order_type, $products,$razorpay_payment_id, $delivery_id, $product_iamge_path);
            $this->Order_Email($cart_array,$order_id_array['order_id_array'],$product_iamge_path,$user_id,$delivery_id,$order_id_array['master_inserted_id']);
            if(!empty($wallet_amount))
            {
                $this->Checkout_model->save_online_payments($order_type,$online_pay_amount,$order_id_array['master_inserted_id'],$bank_name,$payment_status,$wallet_amount,$cod_amount='',$cart_info['cart_totals']['voucher_total']);
                $this->Checkout_model->EmptyUserWallet($user_id,$wallet_amount);
            }
            else
            {
                $this->Checkout_model->save_online_payments($order_type,$online_pay_amount,$order_id_array['master_inserted_id'],$bank_name,$payment_status,$user_wallet=0,$cod_amount='',$cart_info['cart_totals']['voucher_total']);
            }
            $this->PlaceOrderDetails($order_id_array['master_inserted_id'],$user_id);            
        } 
        else 
        {            
            $order_id_array = $this->Checkout_model->OrderProducts($shipping_charges,$logistic_id,$user_id,$cart_total, $order_type,$products,$razorpay_payment_id,$delivery_id, $product_iamge_path);  
            $this->Checkout_model->save_online_payments($order_type,$online_pay_amount=0,$order_id_array['master_inserted_id'],$bank_name,$payment_status,$user_wallet=0,$cart_total,$cart_info['cart_totals']['voucher_total']);
            $this->Order_Email($cart_array,$order_id_array['order_id_array'], $product_iamge_path,$user_id,$delivery_id,$order_id_array['master_inserted_id']);
            $this->PlaceOrderDetails($order_id_array['master_inserted_id'],$user_id);            
        }
        $this->Checkout_model->destroy_cart_data($user_id);
    }
    public function Order_Email($cart_array,$order_id_array,$product_iamge_path,$user_id,$delivery_id,$master_id)
    {        
        $shipping_charges = 0;
        foreach($cart_array as $cart_data)
        {
            if(get_product_stocks(array('product_id'=>$cart_data['product_id'],'product_url'=>$cart_data['product_url'])) > 0) 
            {
                $shipping_charges += get_logistic_charges($cart_data['product_id'],$delivery_id,$cart_data['qty'])['shipping_charges'];
            }
        }
        $cart_info = create_cart_product_listing($cart_array);
        
        foreach($order_id_array as $id)
        { 
//==========Product Information================================================================== 
            $mail_result = $this->Checkout_model->product_email_information($id);            
            $seller_email = $mail_result[0]['seller_email'];
            $seller_name  = $mail_result[0]['seller_name'];
            $product_sku = $mail_result[0]['product_sku'];
            $ads_name    = $mail_result[0]['name'];
            $address     = $mail_result[0]['address'];
            $state       = $mail_result[0]['state'];
            $mobile      = $mail_result[0]['mobile'];
            $city        = $mail_result[0]['city']; 
            $pincode     = $mail_result[0]['pincode']; //date("d-m-Y",strtotime($seller->dateadded));
            $order_data  = date("d-m-Y",strtotime($mail_result[0]['order_date']));
            $order_id    = "BNMM".str_pad($master_id,10,0,STR_PAD_LEFT); 
            //echo $mail_result[0]=>['address'];exit;
            //==================== msg OTP ==============//
            $msg = 'Order Received: Your order with order id '.$order_id.' for ';
//==========Product Information==================================================================           
//==========EmailInformation==================================================================
            $email_info = $this->Checkout_model->get_autoemail_info(12);                        
//==========EmailInformation==================================================================
//==========UserInformation==================================================================
            $user_info = $this->Checkout_model->getUserInfo($user_id);            
//==========UserInformation==================================================================
            $logo         = base_url().'assets/images/website_logo.png';
            $app_logo     = base_url().'assets/images/app-logo.png';
            $facebook     = base_url().'assets/images/facebook.png';
            $google       = base_url().'assets/images/google-plus.png';
            $linkedin     = base_url().'assets/images/linkedin.png';
            $twitter      = base_url().'assets/images/twitter.png';
            $youtube      = base_url().'assets/images/youtube.png';
//==========Get Logo For==================================================================
            $info            = '';
            $email_subject   = str_replace("{order_id}",$order_id,$email_info->email_subject);
            $email_from      = $email_info->email_from_email;
            $email_from_name = $email_info->email_from_name;
            $email_to        = $user_info->email; // seller email
            $content         = $email_info->email_description;
            $message         = str_replace("{name}",$user_info->first_name,$content);
            $message         = str_replace("{ads_name}",$ads_name,$message);
            $message         = str_replace("{orderid}",$order_id,$message);
            $message         = str_replace("{logo}",$logo,$message);
            $message         = str_replace("{small-logo}",$app_logo,$message);
            $message         = str_replace("{state}",$state,$message);
            $message         = str_replace("{address}",$address,$message);
            $message         = str_replace("{city}",$city,$message);
            $message         = str_replace("{pincode}",$pincode,$message);
            $message         = str_replace("{order_date}",$order_data,$message);
            $message         = str_replace("{facebook}",$facebook,$message);
            $message         = str_replace("{google-plus}",$google,$message);
            $message         = str_replace("{linkedin}",$linkedin,$message);
            $message         = str_replace("{twitter}",$twitter,$message);
            $message         = str_replace("{youtube}",$youtube,$message);
            /*foreach($mail_result as $result){
            $message .= '<p style="color:#080;font-size:18px;">'.$result->item_name.'</p>';
            }*/
            $cnt_amount  = 0;
            foreach($mail_result as $result)
            {  
                //echo $result['image_name'];exit;
                $total_days         = $result['shipping_time'] + 3;
                $shipping_days =  date('d-m-Y',strtotime("+".$total_days." day", strtotime($result['order_date'])));
                $this->Checkout_model->updateExpecterdDeliveryDate($shipping_days,$result['master_order_id']);
                $cnt_amount = round($result['total_amount']);
                $url = "bulknmore.com"."/".$result['category_url'] . "/" . $result['sub_category_url'] . "/" . $result['subtosub_category_url'] . "/" . $result['product_url'];
                $url1 = $url.'/'.make_encrypt($result['product_id']);
                //echo $url1;exit;
                $info   .= '<tr>
                              <td style="vertical-align:top;font-size:13px;line-height:18px;font-family:Arial,sans-serif"><table id="m_-8937381491213309887orderDetails" style="width:100%;border-collapse:collapse">
                                  <tbody>
                                    <tr>
                                      <td style="vertical-align:top;font-size:13px;line-height:18px;font-family:Arial,sans-serif"> Order #<a href="https://www.bulknmore.com/order-details?order-id='.$order_id.'" style="text-decoration:none;color:rgb(0,102,153);font:12px/16px Arial,sans-serif" target="_blank">'.$order_id.'</a> <br>
                                        <span style="font-size:12px;color:rgb(102,102,102)">Placed on '.date('D',strtotime($result['order_date'])).','.date('M d',strtotime($result['order_date'])).', '.date('Y',strtotime($result['order_date'])).'</span></td>
                                    </tr>
                                  </tbody>
                                </table></td>
                            </tr>
                            <tr>
                              <td style="padding-left:32px;vertical-align:top;font-size:13px;line-height:18px;font-family:Arial,sans-serif"><table style="width:95%;border-collapse:collapse">
                                  <tbody>
                                    <tr>
                                      <td style="width:60px;text-align:center;padding:16px 0 10px 0;vertical-align:top;font-size:13px;line-height:18px;font-family:Arial,sans-serif"><a href="'.$url1.'" title="" style="text-decoration:none;color:rgb(0,102,153);font:12px/16px Arial,sans-serif" target="_blank"> <img src="'.$data['image_path']['product_image'].''.$result['image_name'].'" style="width:100px; height:auto;border:0;" class="CToWUd"> </a></td>
                                      <a href="'.$url1.'">'.$result['item_name'].'</a> <br>
                                        Product SKU : '.$product_sku.' <br>
                                        Price Per Piece  : &#8377; '.number_format($result['price']*$result['quantity']).' <br>
                                        Set Quantity  : '.$result['quantity'].' <br>
                                        Set Description  : '.$result['set_description'].' <br>
                                        Sold by <a href="https://www.bulknmore.com/" style="text-decoration:none;color:rgb(0,102,153);font:12px/16px Arial,sans-serif" target="_blank"> Bulk N More </a> <br>
                                        </td>
                                    </tr>
                                  </tbody>
                                </table></td>
                            </tr>
                            <tr>
                              <td style="vertical-align:top;font-size:13px;line-height:18px;font-family:Arial,sans-serif"><table style="width:100%;border-collapse:collapse">
                                  <tbody>
                                    <tr>
                                      <!--<td style="vertical-align:top;font-size:13px;line-height:18px;font-family:Arial,sans-serif"><p style="margin:0 0 4px 0;font:12px/16px Arial,sans-serif"> Need to make changes to your order? Visit our <a href="#" style="text-decoration:none;color:rgb(0,102,153);font:12px/16px Arial,sans-serif" target="_blank">Help Page</a> for more information.<br>
                                        </p></td>
                                    </tr>-->
                                  </tbody>
                                </table></td>
                            </tr>';
            }            
                $message         = str_replace("{product_info}",$info,$message);
                $message         = str_replace("Product SKU : ",' ',$message);
                $message         = str_replace($product_sku,' ',$message);
                $message         = str_replace("{order_total}",$cnt_amount,$message);
                $this->sendEmail($email_from,$email_to,$email_subject,$message);
                if($order_type == 5 || $order_type == 2 || $order_type == 3) // Send Mail To Seller Only For Prepaid Order ( Credit, Debit, Internet Banking )
                    $this->sendEmailTOSeller($email_from,$seller_email,$seller_name,$info,$order_id,$content,$cnt_amount,$order_data,$logo,$app_logo,$facebook,$google,$linkedin,$twitter,$youtube);             
                $extra_msg = ' '.substr($result['item_name'], 0,20).'... of Rs. '.round(($cart_info['cart_totals']['cart_total'] + $shipping_charges )).' expected delivery by '.date('d-M-Y',strtotime($shipping_days)).'. We will send you an update when your order is packed by seller.';
                sending_otp($mobile,$msg.$extra_msg);
        }
    }
    
    public function sendEmailTOSeller($email_from,$seller_email,$seller_name,$product_info,$order_id,$content,$tot_amount,$order_date,$logo,$app_logo,$facebook,$google,$linkedin,$twitter,$youtube)
    {   
        $email_subject = 'Bulknmore : New Order '.$order_id;
        
        $message         = str_replace("{name}",$seller_name,$content);
        $message         = str_replace("Thank you for your order.",'Greetings from Bulknmore !' ,$message);
        $message         = str_replace("We will send a confirmation when your order ships.",'Please book a new Order No: '.$order_id ,$message);        
        $message         = str_replace("View or manage order",' ',$message);
        $message         = str_replace("We are working to get your order to you as soon as possible. We will let you know the specific delivery date when your order ships.",'Please do a through QC to avoid unnecessary returns hassles. Kindly keep the stock ready for pick-up as soon as possible, so that out pick-up team can complete all pick-ups lined up. Kindly do not delay the shipment and keep invoice ready.',$message);
        $message         = str_replace("{order_date}",$order_date,$message);
        $message         = str_replace("Your order will be sent to:","Invoice should be billed to:",$message);
        $message         = str_replace("{ads_name}",'Bulknmore',$message);
        $message         = str_replace("{state}",'Rajasthan',$message);
        $message         = str_replace("{address}",'Block -B, Floor 2, E-96, Garment Zone, Sitapura Industrial Area,',$message);
        $message         = str_replace("{city}",'Jaipur',$message);
        $message         = str_replace("{pincode}",'302022',$message);
        
        $message         = str_replace("{logo}",$logo,$message);
        $message         = str_replace("{small-logo}",$app_logo,$message);
        $message         = str_replace("{facebook}",$facebook,$message);
        $message         = str_replace("{google-plus}",$google,$message);
        $message         = str_replace("{linkedin}",$linkedin,$message);
        $message         = str_replace("{twitter}",$twitter,$message);
        $message         = str_replace("{youtube}",$youtube,$message);
        
        $message       = str_replace("{product_info}",$product_info,$message);
        $message         = str_replace("{order_total}",$tot_amount,$message);
        
        $this->sendEmail($email_from,$seller_email,$email_subject,$message);
    }    
            
    public function sendEmail($email_from,$email_to,$email_subject,$message)
	{	
        $config = array();
        $config['useragent']    = "CodeIgniter";
        $config['mailpath']     = "/usr/bin/sendmail"; // or "/usr/sbin/sendmail"
        $config['protocol']     = "smtp";
        $config['smtp_host']    = "localhost";
        $config['smtp_port']    = "25";
        $config['mailtype']     = 'html';
        $config['charset']      = 'utf-8';
        $config['newline']      = "\r\n";
        $config['wordwrap']     = TRUE;
        $this->email->initialize($config);
        $this->email->from($email_from,'Bulknmore | Order');
        $this->email->to($email_to);
        $this->email->subject($email_subject);
        $this->email->message($message);
        if($this->email->send())
        {
            return 1;
        }
        else
        {
            return 0;
        }
	}
    
    public function PlaceOrderDetails($masterid,$user_id)
    {
        $json = array();
        //$masterid = $this->Checkout_model->getMasterID($order_id_array);
        $order_details = $this->Checkout_model->getOrderDetails($masterid);
        $order_array = array();
        $address_array = array();
        $order_item_array = $main_order_array = array();
        $total_price = '';
        $shipping_time = '';
        foreach ($order_details as $key=>$value) 
        {   
            $obj = new stdClass();
            $obj->name = $value->name;
            $obj->address = $value->address;
            $obj->mobile = $value->mobile;
            $obj->state = $value->state;
            $obj->city = $value->city;
            $obj->pincode = $value->pincode;
            $address_array = $obj;
        }
        foreach($order_details as $key=>$value) 
        {   
            $obj = new stdClass();            
            $obj->order_id      = $value->order_id;
            $obj->item_name     = $value->item_name;
            $obj->image_name    = $value->image_name;
            $obj->quantity      = $value->quantity;
            $obj->set_description     = $value->set_description;
            $obj->price         = $value->price;
            $obj->master_id     = $value->master_order_id;
            $obj->order_date    = $value->order_date;
            $total_price        += $obj->price * ($value->quantity * $value->piece_per_set);            
            $obj->expected_date = $value->exp_order_date; 
            if(!isset($main_order_array[$value->order_id])){
                $main_order_array['order_id'] = $value->master_unique_id;
                $order_item_array[] = $obj;
            }else{
                $order_item_array[$value->order_id][] = $obj;    
            }
        }
        if(!empty($main_order_array) && !empty($order_item_array))
        {
            $user_wallet = $this->Checkout_model->getUserWallet($user_id);
            $responce['status'] = 1;
            $responce['shipping_time']  = $shipping_time;
            $responce['total_price']    = $total_price;
            $responce['delivery_address']  = $address_array;
            $responce['order_products'] = $main_order_array;
            $responce['order_items'] = $order_item_array;
            $responce['user_wallet'] = $user_wallet;
            echo json_encode($responce);
        }
        else
        {
            $responce['status'] = 0;
            echo json_encode($responce);
        }
    }
    public function getRazorKey()
    {
        $json = array();
        $post = $this->input->post();
        if(!empty($post))
        {
            $json['status'] = 1;
            $json['razor_key']  = RAZORPAY_API;                
            echo json_encode($json);
        }        
        else
        {
            $responce['status'] = 0;
            echo json_encode($responce);
        }
    }
    public function online_banking()
    {
         $product_iamge_path =  $this->config->item('seller_url').'assets/images/product_images/';
         $json = array();
         $post = $this->input->post();
         //print_r($post);exit;
         $api     = new Api(RAZORPAY_API, RAZORPAY_PASS);
         $payment = $api->payment->fetch($post['razorpay_payment_id']);
         if($payment->method == 'netbanking')
         {
            $payment_id = 2;
         }
         else if($payment->method == 'card')
         {
            $payment_id = 3;
         }
         $amount = ($payment->amount / 100);
         $userInfo = $this->app_auth_lib->get_user_details();               
         $products = array();
         $cart_array = json_decode(utf8_encode($post['cart_info']), true);
         if (!empty($cart_array)) 
         {
            $this->order_procces($payment_id,$post['razorpay_payment_id'],$post['delivery_id'],$product_iamge_path,$post['cart_info'],$userInfo->user_id,$payment->bank,$payment->status,$amount,$post['wallet_amount']);
            $responce['status'] = 1;
         }else {
            $responce['status'] = 0;
         }
         echo json_encode($json);
    }
    public function get_logistic_charge()
    {      
        $post = $this->input->post();
        $cart_array = json_decode(utf8_encode($post['cart_info']), true);
        $seller_product_array  = create_cart_order($cart_array, $post['user_id'], $post);
        echo json_encode(create_cart_payment($seller_product_array,$cart_array));
    }
}