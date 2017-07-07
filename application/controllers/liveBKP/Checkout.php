<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require (APPPATH.'third_party/razorpay/Razorpay.php');
use Razorpay\Api\Api;
class Checkout extends BNM_Controller 
{   
    public function __construct()
    {
        parent::__construct();
        $this->load->model('home_model');    
        $this->load->model('checkout_model');
        $this->load->library('email');
    }
    
    public function login_checkout_user()
    {
        $json = array();
        $post = $this->input->post();
        $user_status = $this->checkout_model->auth_checkout_user($post);
        if(!empty($user_status))
        {  
            $json['success_msg'] = 'Successfully Login! redirecting....';
            get_cart_info($user_status->user_id);
            $this->session->set_userdata('user_id',$user_status->user_id);           
        }
        else
        {
            $json['error_msg'] = 'Sorry! You entered wrong email or password!';
        }
        echo json_encode($json);
    }
    public function Validate_Checkout_User()
    {
        $json = array();
        $post = $this->input->post();
        if(is_numeric($post['value'])){
            $mobile = $this->checkout_model->check_user_mobile($post['value']);
            if(empty($mobile)) {
                $user_id = $this->checkout_model->register_user(array('email'=>'','mobile'=>$post['value']));
                $this->session->set_userdata('reg_user_id',$user_id);
                if(!empty($user_id)) {
                    $json['mobile_no_exist'] = 'Mobile No Exist';
                    $mobile = $post['value'];
                    $otp = rand(1000,9999);
                    $message = $otp."-is One Time Password(OTP) for Registration Verification to BulknMore, Pls do not Share With Anyone!";
                    sending_otp($mobile,$message);
                    $status = $this->checkout_model->update_checkout_user_otp($otp,$user_id);
                    if($status > 0) {
                        $json['success_msg'] = 'Your OTP Verification Code is Successfully Send on -'.substr($mobile,0,2).'xxxxxxxx'.substr($mobile,8,9);
                    }
                    else {
                        $json['error_msg'] = 'Sorry! Something went wrong!';
                    }
                }                
            }else{
                $json['user_exist'] = 'User Exist';
            }
        }
        else{
            $email = $this->checkout_model->check_user_email($post['value']);
            if (empty($email))
            {
                $json['email_no_exist'] = 'Email No Exist';
            }
            else{
                $json['user_exist'] = 'User Exist';
            }
        }
        echo json_encode($json);
    }
    public function register_checkout_user()
    {
        $json = array();
        $post = $this->input->post();
        $user_id = $this->checkout_model->register_user($post);
        $this->session->set_userdata('reg_user_id',$user_id);
        if(!empty($user_id))
        {   
            $mobile = $post['mobile'];
            $otp = rand(1000,9999);
            $message = "Great work! You're almost there. We just wanna make sure its you and we would need you to verify this magical number. Go ahead and put in - ".$otp;
            $api = sending_otp($mobile,$message);
            if($api){
                $status = $this->checkout_model->update_checkout_user_otp($otp,$user_id);
                if($status > 0)
                {
                    $json['success_msg'] = 'Your OTP Verification Code is Successfully Send on -'.substr($mobile,0,2).'xxxxxxxx'.substr($mobile,8,9);
                    $this->session->set_userdata('reg_user_id',$user_id);
                }
                else
                {
                    $json['error_msg'] = 'Sorry! Something went wrong!';
                }
            }
            else{
                $json['error_msg'] = 'Sorry! Something went wrong!'; 
            }
        }
        echo json_encode($json);
    }
    public function verify_checkout_user()
    {
        $json = array();
        $post = $this->input->post();
        $userid = $this->session->userdata('reg_user_id');
        $otp = $this->checkout_model->verify_checkout_user($post,$userid);
        if(!empty($post))
        {   
            if(!empty($otp->otpcode))
            {
                $json['success_msg'] = 'Successfully Verify!';
                $this->checkout_model->reset_checkout_user_otp($post,$userid);
            }
            else{
                $json['error_msg'] = 'Sorry! You entered wrong OTP!'; 
            }
        }
        echo json_encode($json);
    }
    public function update_checkout_user_password()
    {
        $json = array();
        $post = $this->input->post();
        $userid = $this->session->userdata('reg_user_id');
        $status = $this->checkout_model->update_checkout_user_password($post['password'],$userid);
        if(!empty($post))
        {   
            if($status > 0)
            {
                $json['success_msg'] = 'Successfully Registered!';
                $this->checkout_model->active_checkout_user($userid);
                get_cart_info($userid);
                $this->session->set_userdata('user_id',$userid);
            }
            else{
                $json['error_msg'] = 'Sorry! Something went wrong!'; 
            }
        }
        echo json_encode($json);
    }
    public function ProductCheckout()
    {
        $data   = $this->data;
        $data['page_settings']  = $this->Comman_model->get_page_setting(38);
        $vat_total = 0;
        $userid = $this->session->userdata('user_id');
        $data['user_id'] = $userid;
        $cart = $this->cart->contents();
        if(empty($data['cart_info']) && empty($cart)){
            redirect();
        }
        elseif($userid > 0){
            $data['delvry_ads'] = $this->checkout_model->DeliveryAddress($userid);
            $data['user_wallet'] = $this->checkout_model->getUserWallet($userid);
            $data['cancel_count'] = $this->checkout_model->getUserCancelRecords($userid);
            $cart_info 			 = create_cart_product_listing($data['cart_info']);
            //print_r($cart_info['cart_list']);exit;
    		$data['cart_totals'] = $cart_info['cart_totals'];
    		$data['cart']   	 = $cart_info['cart_list'];

		 //-------------============================== Start Section for saving checkout analytics info ==============================-------------
	        if(isset($cart_info['cart_list']) && !empty($cart_info['cart_list']) && isset($cart_info['cart_totals']) && !empty($cart_info['cart_totals'])){
		        if(!isset($userid) || trim($userid)==''){
		        	$user_id = 0;
		        }else{
		        	$user_id = $userid;
		        }

		        $_product_ids_array = array_map(function($row){return $row->product_id;},$cart_info['cart_list']);
		        $_product_ids       = is_array($_product_ids_array) ? implode(',',$_product_ids_array) : array();
		        $_tot_products      = count($cart_info['cart_list']);
		        $_tot_sets          = isset($cart_info['cart_totals']['total_set']) ? $cart_info['cart_totals']['total_set'] : 0;
		        $_tot_pieces        = isset($cart_info['cart_totals']['total_pieces']) ? $cart_info['cart_totals']['total_pieces'] : 0;
		        $_tot_amount        = isset($cart_info['cart_totals']['sub_total']) ? $cart_info['cart_totals']['sub_total'] : 0;
		        $_tot_vat_amt       = isset($cart_info['cart_totals']['total_vat']) ? $cart_info['cart_totals']['total_vat'] : 0;
		        $_tot_price         = isset($cart_info['cart_totals']['cart_total']) ? $cart_info['cart_totals']['cart_total'] : 0;

		        $analytics_insert_array = array(
				        'user_id'     => $user_id,
				        'ip_address'  => $this->input->ip_address(),
				        'product_ids' => $_product_ids,
				        'tot_product' => $_tot_products,
				        'tot_set'     => $_tot_sets,
				        'tot_pieces'  => $_tot_pieces,
				        'tot_amount'  => $_tot_amount,
				        'tot_vat_amt' => $_tot_vat_amt,
				        'tot_price'   => $_tot_price,
				        'date_added'  => date('Y-m-d')
			    );
		        $on_duplicate = "product_ids = '".$_product_ids."', tot_product = ".$_tot_products.", tot_set = ".$_tot_sets.",
								 tot_pieces  = ".$_tot_pieces.", tot_amount  = ".$_tot_amount.", tot_vat_amt = 	".$_tot_vat_amt.",
								 tot_price   = 	".$_tot_price.", hit_count = hit_count + 1";

		        $this->Common_model->insert_analytics_record('tblblk_checkout_analytics',$analytics_insert_array,$on_duplicate);
	        }
	       
	     //-------------============================== End of Section for saving checkout analytics info ==============================-------------
        }
        $this->load->view('viw_check_out', $data);
    }
    public function EditDeliveryAddress($delvryid)
    {
        $data = $this->data;
        $data['edit'] = $this->checkout_model->EditDeliveryAddress(make_decrypt($delvryid),$this->user_id);        
        //echo "<pre>"; print_r($data['edit']);exit;
        $this->load->view('viw_check_out', $data);
    }
    public function UpdateDeliveryAddress($delvryid)
    {
        $data = $this->data;
        $data['user'] = $this->session->userdata('user_id');
        //echo "<pre>"; print_r($data['user']);exit;
        $formdata = $this->input->post();
        if (!empty($formdata['default'])) {
            $default = 'Y';
            $status = $this->checkout_model->GetDefaultDeliveryAddress($this->user_id);
            $this->checkout_model->ManageDefaultDeliveryAddress($this->user_id);
        } else
            $default = 'N';
        $data['delvry_ads'] = $this->checkout_model->UpdateDeliveryAddress($formdata,make_decrypt($delvryid),$default);
        redirect('checkout');
    }
    public function AddNewDeliveryAddress()
    {
        $data = $this->data;
        $user_id = $this->user_id;
        $formdata = $this->input->post();
        //echo "<pre>"; print_r($formdata);exit;
        if (!empty($formdata['default'])) {
            $default = 'Y';
            $status = $this->checkout_model->GetDefaultDeliveryAddress($this->user_id);
            $this->checkout_model->ManageDefaultDeliveryAddress($this->user_id);
        } else
            $default = 'N';
        //echo "<pre>"; print_r($default);exit;
        $this->checkout_model->AddNewDeliveryAddress($formdata, $user_id, $default);
        redirect('checkout');
    }
    public function DeleteDeliveryAddress($delvryid)
    {
        $data = $this->data;
        $this->checkout_model->DeleteDeliveryAddress(make_decrypt($delvryid),$this->user_id);
        redirect('checkout');
    }
    public function GetDeliveryAddress()
    {
        $data = $this->data;
        $json = array();
        $delvry_id = $this->input->post('delvry_address_id');
        $data['user'] = $this->session->userdata('user_id');
        $addrs = $this->checkout_model->GetDeliveryAddress($data['user'],$delvry_id);
        $pincode_status = checkPincodeAvailability($addrs->pincode);
        $cart_info 	  = create_cart_product_listing($data['cart_info']);     
        if(!empty($pincode_status))
        {   
            if(!empty($addrs))
            {
                $json['name'] = $addrs->name;
                $json['mobile'] = $addrs->mobile;
                $json['address'] = $addrs->address;
                $json['city'] = $addrs->city;
                $json['state'] = $addrs->state;
                $json['pincode'] = $addrs->pincode;
                if($cart_info['cart_totals']['cart_total'] >= 5000)
                    $json['cod_restrict'] = $pincode_status->value_capping;
            }            
        }
        else
        {
            $json['pincode_status'] = 'Sorry! No Seller Deliver in Your Area.';
        }
        echo json_encode($json);
    }
    public function CheckoutProducts()
    {
        $data = $this->data;
        $cart = $this->cart->contents();
        //echo "<pre>"; print_r($cart);exit;
        redirect('checkout');
    }
    public function ValidateOrderOTP()
    {
        $data = $this->data;
        $json = array();
        $formdata = $this->input->post();
        $user_id = $this->session->userdata('user_id');
        if($formdata) 
        {
            $mobile = $formdata['checkout_mobile'];
            $otp = rand(1000, 9999);
            $msg = "Your Order OTP Verification code is-".$otp;                   
            $x = sending_otp($mobile,$msg);             
            if ($x) {
                $this->checkout_model->ResetOrdersOTP($user_id);
                $json['otpsuccess'] = 'Your OTP Verification Code is Successfully Send!';
                $this->checkout_model->OrderOTP($user_id,$otp);
            } else {
                $json['otpfailed'] = 'Oops! Something went wrong, Please Try Again!';
            }
        }
        echo json_encode($json);
    }
    public function VerifyProductOrderOTP()
    {
        $data = $this->data;
        $json = array();
        $formdata = $this->input->post();
        $user_id = $this->session->userdata('user_id');
        $otp = $formdata['order_otp_number'];
        $otpcode = $this->checkout_model->verifyProductOrderOTP($user_id,$otp);
        if($otpcode) {
            $json['success'] = 'You Successfully Verify Your OTP! Please Go Ahead and Place Your Order.';
            $this->checkout_model->ResetOrdersOTP($user_id);
        } else {
            $json['failed'] = 'Please Enter Correct OTP!';
        }
        echo json_encode($json);
    }
    public function PlaceOrder()
    {
        $data = $this->data;      
        $formdata = $this->input->post();
        $products = array();
        //print_r($data['cart_info']);exit;
        if(!empty($data['cart_info']))
        {
        	if(isset($formdata['wallet_radio_status']))
        	{
        		$delivery_id = $formdata['cod_delivery_id'];
        		if(strtolower(trim($formdata['wallet_radio_status'])) == 'yes') // if wallet money is more than order price
	        	{
	        		$this->order_procces($formdata['cod_type'],'',$delivery_id,$product_iamge_path='',$bank_name='',$payment_status='',$amount='',$formdata['wallet_radio_status']);
	        	}
        	}        	
            else
            {
            	$delivery_id = $formdata['delivery_id'];
            	$this->order_procces($formdata['cod_type'],'',$delivery_id,$product_iamge_path='',$bank_name='',$payment_status='',$amount='',$wallet_status='');
            }            
        } else {
            redirect();
        }
    }
    private function order_procces($order_type,$razorpay_payment_id = '',$delivery_id,$product_iamge_path,$bank_name,$payment_status,$online_pay_amount,$wallet_status)
    {   
        $data = $this->data;
        $shipping_charges = 0;        
        $user_wallet = $this->checkout_model->getUserWallet($this->user_id);
        $cart_info 	  = create_cart_product_listing($data['cart_info']);
        foreach($cart_info['cart_list'] as $value) 
        {   
            if(get_product_stocks(array('product_id'=>$value->product_id,'product_url'=>$value->product_url)) > 0) 
            {
                $logistic_details   = get_logistic_charges($value->product_id,$delivery_id,$cart_info['cart_totals']['total_set']);
                $shipping_charges  += $logistic_details['shipping_charges'];
                $logistic_id        = $logistic_details['logistic_id'];
                $objcate            = new stdClass();
                $objcate->pid       = $value->product_id;
                $objcate->voucher_code = empty($value->voucher_code)? 0 :$value->voucher_code;
                $objcate->image     = $value->image;              
                $objcate->seller_id = $value->seller_id;
                $objcate->pname     = $value->name;
                $objcate->pquantity = $value->qty;
                $objcate->pprice    = $value->price;
                $products[]         = $objcate;
                $this->session->set_userdata('test', $products);                
            }
        }                        
        $cart_total = ($cart_info['cart_totals']['cart_total'] + $shipping_charges);
        if($order_type == 5 || $order_type == 2 || $order_type == 3)
        {
            $order_ids = $this->checkout_model->OrderProducts($shipping_charges,$logistic_id,$this->user_id,$cart_total,$order_type,$products,$razorpay_payment_id,$delivery_id,$product_iamge_path);
            if(strtolower(trim($wallet_status)) == 'yes')
            {
            	if($cart_total <= $user_wallet)
            	{
            		$this->checkout_model->save_online_payments($order_type,$online_pay_amount,$order_ids['master_inserted_id'],$bank_name,$payment_status,$cart_total,$cod_amount='',$cart_info['cart_totals']['voucher_total']);
            		$this->checkout_model->EmptyUserWallet($this->user_id,$cart_total);
            	}
            	else{
            		$this->checkout_model->save_online_payments($order_type,$online_pay_amount,$order_ids['master_inserted_id'],$bank_name,$payment_status,$user_wallet,$cod_amount='',$cart_info['cart_totals']['voucher_total']);
            		$this->checkout_model->EmptyUserWallet($this->user_id,$user_wallet);
            	}            	
            }
            else{
            	$this->checkout_model->save_online_payments($order_type,$online_pay_amount,$order_ids['master_inserted_id'],$bank_name,$payment_status,$user_wallet=0,$cod_amount='',$cart_info['cart_totals']['voucher_total']);
            }
            $this->Order_Email($order_ids['order_id_array'],$product_iamge_path,$delivery_id,$order_ids['master_inserted_id'],$order_type);
            $this->session->set_userdata('master_id',$order_ids['master_inserted_id']);
            $this->checkout_model->remove_cart_data($this->session->userdata('user_id'));
			$this->remove_checkout_analytics($order_ids); //For removing checkout analytics for current user
            return make_encrypt($order_ids['order_date']);
        }else{
            $order_ids = $this->checkout_model->OrderProducts($shipping_charges,$logistic_id,$this->user_id,$cart_total,$order_type, $products,$razorpay_payment_id,$delivery_id,$product_iamge_path);
            $this->checkout_model->save_online_payments($order_type,$online_pay_amount=0,$order_ids['master_inserted_id'],$bank_name,$payment_status,$user_wallet=0,$cart_total,$cart_info['cart_totals']['voucher_total']);
            $this->Order_Email($order_ids['order_id_array'],$product_iamge_path,$delivery_id,$order_ids['master_inserted_id'],$order_type);
            $this->checkout_model->remove_cart_data($this->session->userdata('user_id'));
			$this->remove_checkout_analytics($order_ids); //For removing checkout analytics for current user
            redirect('order/thank-you/'.make_encrypt($order_ids['order_date']));
        }       
    }
    public function PlaceOrderDetails($order_date)
    {
        $data = $this->data;
        $data['page_settings']  = $this->Comman_model->get_page_setting(39);
        $order_details = $this->checkout_model->getOrderDetails(make_decrypt($order_date),$this->session->userdata('user_id'));
        $reason_list  = $this->checkout_model->getCancellationReasonDropdown();
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
        foreach ($order_details as $key=>$value) 
        {   
            $obj = new stdClass();            
            $obj->order_id      = $value->order_id;
            $obj->product_id    = $value->product_id;
            $obj->order_item_status    = $value->order_item_status;
            $obj->item_name     = $value->item_name;
            $obj->set_description     = $value->set_description;
            $obj->pack_of     = $value->pack_of;
            $obj->image_name    = $value->image_name;
            $obj->quantity      = $value->quantity;
            $obj->price         = $value->price;
            $obj->master_id     = $value->master_order_id;
            $obj->order_date    = $value->order_date;            
            $obj->exp_order_date = $value->exp_order_date;
            $total_price        = round($value->total_amount);            
            $shipping_time      = $obj->exp_order_date; // Getting For Single Show At Shipping Details.
            if(!isset($main_order_array[$value->master_order_id])){
                $main_order_array[$value->master_order_id] = $value->master_unique_id;
                $order_item_array[$value->master_order_id][] = $obj;
            }else{
                $order_item_array[$value->order_id][] = $obj;    
            }
        }
        if(!empty($main_order_array) && !empty($order_item_array))
        {
            $data['shipping_time']      = $shipping_time;
            $data['total_price']        = $total_price;
            $data['delivery_address']   = $address_array;
            $data['order_products']     = $main_order_array;
            $data['order_items']        = $order_item_array;
            $data['reason_list']        = $reason_list;
            $this->load->view('viw_placeorder', $data);
        }
        else
        {
            echo "<center><h1>404 Page Not Found</h1></center>";
        }
    }
    public function online_banking()
    {
         $data = $this->data;
         $product_iamge_path =  $data['image_path']['product_image'];
         $json = array();      
         $post = $this->input->post();
         //$this->load->library('razorpay');//'pay_6XJofEgdREpCaJ'
         //$payment = $this->razorpay->fetch();//$post['razorpay_payment_id']
         $api     = new Api(RAZORPAY_API, RAZORPAY_PASS);
         $payment = $api->payment->fetch($post['razorpay_payment_id']);
         $payment->capture(array('amount' => $payment->amount));
         if($payment->method == 'netbanking')
         {
            $payment_id = 2;
         }
         else if($payment->method == 'card')
         {
            $payment_id = 3;
         }
         $amount = ($payment->amount / 100);
         //$masterid = "BNMO".$this->user_id.date('YmdHis').$this->generateRandomString(4);       
         $products = array();
         $count    = $data['cart_info'];
         if (!empty($count)) 
         {
            $order = $this->order_procces($payment_id,$post['razorpay_payment_id'],$post['delivery_id'],$product_iamge_path,$payment->bank,$payment->status,$amount,$post['wallet_status']);
            $json['url'] = base_url('order/thank-you/'.$order);
         }else {
            $json['url'] = base_url();
         }
         echo json_encode($json);
    }
    //==========cash if online payment dismiss============
    public function online_banking_faliure()
    {
         $json = array();      
         $post = $this->input->post();
         $user_id = $this->session->userdata('user_id');
         $insert_array = array(
                            'user_id'=>$user_id,
                            'response'=>$post['response'],
                            'amount'=>$post['amount']);
         $this->checkout_model->online_banking_faliure($insert_array);
         $json['reload'] = 'true';
         echo json_encode($json);
    }
    public function PincodeDetails()
    {
        $data = $this->data;
        $json = array();
        $pincode = $this->input->get('pincode');
        $json['obj'] = $this->checkout_model->getPincodeDetails($pincode);
        echo json_encode($json);
    }
    public function getRazorKey()
    {
        $json = array();
        $data = $this->data;
        $post = $this->input->post();
        $shipping_charges = 0;
        foreach($data['cart_info'] as $cart_data)
        {
            if(get_product_stocks(array('product_id'=>$cart_data['product_id'],'product_url'=>$cart_data['product_url'])) > 0) 
            {
                $shipping_charges += get_logistic_charges($cart_data['product_id'],$post['delivery_id'],$cart_data['qty'])['shipping_charges'];
            }
        }
        $cart_info = create_cart_product_listing($data['cart_info']);
        if($this->input->post())
        {
            $user_id = $this->session->userdata('user_id');
            if($post['user_id'] == $user_id)
            {
                $json['razor_key']  = RAZORPAY_API;
                $json['user_info']  = $data['user_info'];
                $json['user_mobile']  = $this->checkout_model->get_user_delivery_mobile($post['delivery_id']);
                $json['amount']     = (strtolower(trim($post['is_wallet'])) == 'yes')?$this->create_user_amount_with_bulk_wallet($user_id,$shipping_charges):round($cart_info['cart_totals']['cart_total'] + $shipping_charges);
                echo json_encode($json);
            }
            else
            {
                //404
            }
        }
    }
    private function create_user_amount_with_bulk_wallet($user_id,$shipping_charges)
    {
        $data = $this->data;
        $user_wallet = $this->checkout_model->getUserWallet($user_id);
        $cart_info 	 = create_cart_product_listing($data['cart_info']);
        if(($cart_info['cart_totals']['cart_total'] + $shipping_charges) - $user_wallet >= 0)
            return round(($cart_info['cart_totals']['cart_total'] + $shipping_charges ) - $user_wallet);
        else
            return 0;        
    }
    public function Order_Email($order_id_array,$product_iamge_path,$delivery_id,$master_id,$order_type)
    {
        $data = $this->data;
        $shipping_charges = 0;
        foreach($data['cart_info'] as $cart_data)
        {
            if(get_product_stocks(array('product_id'=>$cart_data['product_id'],'product_url'=>$cart_data['product_url'])) > 0) 
            {
                $shipping_charges += get_logistic_charges($cart_data['product_id'],$delivery_id,$cart_data['qty'])['shipping_charges'];
            }
        }
        $cart_info = create_cart_product_listing($data['cart_info']);
        foreach($order_id_array as $id)
        { 
//==========Product Information================================================================== 
            $mail_result = $this->checkout_model->product_email_information($id);
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
            $seller_order_id = "BNMO".str_pad($mail_result[0]['order_id'],10,0,STR_PAD_LEFT);
            //echo $mail_result[0]=>['address'];exit;
            //==================== msg OTP ==============//
            $msg = 'Order Received: Your order with order id '.$order_id.' for ';
//==========Product Information==================================================================           
//==========EmailInformation==================================================================
            $email_info = $this->checkout_model->get_autoemail_info(12);     
//==========EmailInformation==================================================================
//==========UserInformation==================================================================
            $user_id = $this->session->userdata('user_id');
            $user_info = $this->checkout_model->getUserInfo($user_id); 
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
                $this->checkout_model->updateExpecterdDeliveryDate($shipping_days,$result['master_order_id']);
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
                    $this->sendEmailTOSeller($email_from,$seller_email,$seller_name,$info,$seller_order_id,$content,$cnt_amount,$order_data,$logo,$app_logo,$facebook,$google,$linkedin,$twitter,$youtube);             
                $extra_msg = ' '.substr($result['item_name'], 0,20).'... of Rs. '.round(($cart_info['cart_totals']['cart_total'] + $shipping_charges )).' expected delivery by '.date('d-M-Y',strtotime($shipping_days)).'. We will send you an update when your order is packed by bulknmore.';
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
    /*public function sendMail($from,$to,$subject,$content)
    {
       $headers    = 'MIME-Version: 1.0' . "\r\n";
       $headers   .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
       $headers   .= "X-Priority: 1 (Highest)\n";
       $headers   .= "X-MSMail-Priority: High\n";
       $headers   .= "Importance: High\n";
       $headers   .= 'From:'.$from."\r\n";
       if(mail($to,$subject,$content,$headers)) 
       {
           return true; // if message send successfully
       }
       else
       {
           return false; 
       }
    }*/
	public function remove_checkout_analytics($order_ids)
    {
	   if(isset($this->user_id) && $this->user_id!='' && $this->user_id>0){
		    $where_array = array(
			    'date_added' => date('Y-m-d'),
			    'order_ids'  => ''
		    );
		    $or_where = array(
			    'user_id'    => $this->user_id,
		    	'ip_address' => $this->input->ip_address()
		    );
		    $update_array = array(
		    	'status'    => 2,
			    'order_ids' => (is_array($order_ids) && isset($order_ids['master_inserted_id'])) ? $order_ids['master_inserted_id'] : 0
		    );
		    $this->Common_model->update_table_row("tblblk_checkout_analytics",$update_array,$where_array,$or_where);
	    }

    }
}