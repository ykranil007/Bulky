<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Dashboard extends BNM_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Home_model');
		$this->load->model('dashboard/login_model');
		$this->load->model('home_model');
		$this->load->library('email');
		$this->load->model('dashboard/useraccount_setting_model');
		check_login_session();			
	}
	public function memberDashboard()
	{
		$data = $this->data;
		$ip = $this->input->ip_address();
		$data['page_settings']  = $this->Comman_model->get_page_setting(4);
		$data['page_banner']  	= $this->Comman_model->get_page_banners(1);
		$data['offer_products'] = $this->home_model->get_bestOffers_Products();
        $data['recent_products'] = $this->home_model->get_RecentView_Products($ip);
	    $this->load->view("viw_home",$data);
	}
	public function dashboard_User_Account()
	{
		$data =  $this->data;
		$data['page_settings']  = $this->Comman_model->get_page_setting(4);				
		$this->load->view("dashboard/view_my_account",$data);
	}
	public function editDashboardPersonalinfo()
	{
		$data = $this->data;
		if($this->input->post())
		{
		$datainfo =  $this->input->post();
		$firstName = $datainfo['myFirstName'];
		$lastName  = $datainfo['myLastName'];
		$gender    = $datainfo['myGender'];
		$userid = $this->session->userdata('user_id');
		$data = $this->useraccount_setting_model->editDashboardPersonalinfo_model($firstName,$lastName,$gender,$userid);
		}
		if($data)
		{
			$json['success_msg'] = "Your changes have been saved successfully.";
		}
		else
		{
			$json['error_msg']  = "Sorry Somthing Went Wrong"; 
		}
		echo json_encode($json);
	}
	public function dashboard_User_Change_Password()
	{
		$data = $this->data;
		$data['page_settings']  = $this->Comman_model->get_page_setting(4);		
		$this->load->view('dashboard/view_userAccount_changePassword',$data);
	}
	public function updateDashboardUserPassword()
	{
		$data =  $this->data;
		$json = array();
		$formStatus =true;
		$changePasswordInfo = $this->input->post();
		$oldpassword = $changePasswordInfo['oldPassword'];
		$newpassword = $changePasswordInfo['newPassword'];
		$userid = $this->session->userdata('user_id');
		$saved_password = $this->useraccount_setting_model->getPassword($changePasswordInfo,$userid);
		if($changePasswordInfo['oldPassword'] == '' || $changePasswordInfo['newPassword'] == '' || $changePasswordInfo['retypePassword'] == '')
		{
			$json['pass_error'] = "Password Cannot Left Empty";
			$formStatus = false;
		}
		elseif($changePasswordInfo['newPassword'] !=  $changePasswordInfo['retypePassword'])
		{
			$json['new_pass_error'] = "Confirm Password does not Match";
			$formStatus = false;
		}
		else	 
		{
			if(!empty($saved_password->password) == md5($oldpassword))
			{
				$data = $this->useraccount_setting_model->updateUserPassword($newpassword,$userid);
				if($data)
				{
					$json['pass_success_msg'] = "Your Password has been Changed Successfully.";
				}
				else
				{
					$json['pass_success_msg'] = "Something went wrong! Please Retry.";
				}
			}
			else
			{
				$json['invalid_pss_msg'] = 	"InValid Old Password";
			}
		}
		//$json['msg']  = $oldPass;
		echo json_encode($json);
	}
	public function user_Address()
	{
		$data = $this->data;
		$data['page_settings']  = $this->Comman_model->get_page_setting(4);
		$userid = $this->session->userdata('user_id');
		$data['delivery_address'] = $this->useraccount_setting_model->get_Delivery_address($userid);
		$this->load->view('dashboard/view_userAddress',$data);
	}
	public function addUserAddress()
	{
		$data = $this->data;
		$json =array();
		$addressInfo = $this->input->post();
		$username  = $addressInfo['userName'];
		$formStatus = true;
		$userid = $this->session->userdata('user_id');
		if($addressInfo['userName'] == '')
		{
			$json['name_error'] = "Please Enter Your Name.";
			$formStatus = false;
		}
		else
		{
			$json['name_error'] = ' ';
		}
		if($addressInfo['userePincode'] == '')	
		{
			$json['pincode_error'] = "Please Enter Your Pincode.";
			$formStatus = false;
		}
		else
		{
			$json['pincode_error'] = ' ';
		}
		if(strlen($addressInfo['userePincode']) != 6)
		{
			$json['pincode_error'] = "Please Enter Your Valid 6 Digits Pincode.";
			$formStatus = false;
		}
		if($addressInfo['userAddress'] == '')	
		{
			$json['address_error'] = "Please Enter Your Address.";
			$formStatus = false;
		}
		else
		{
			$json['address_error'] = ' ';
		}
		if($addressInfo['userCity'] == '')	
		{
			$json['city_error'] = "Please Enter Your City.";
			$formStatus = false;
		}
		else
		{
			$json['city_error'] = ' ';
		}
		if($addressInfo['userState'] == '')	
		{
			$json['state_error'] = "Please Enter Your State.";
			$formStatus = false;
		}
		else
		{
			$json['state_error'] = ' ';
		}
		if($addressInfo['userPhone'] == '' )	
		{
			$json['phone_error'] = "Please Enter Your Valid 10 Digits Mobile Number.";
			$formStatus = false;
		}
		else
		{
			$json['phone_error'] = ' ';
		}
		if(strlen($addressInfo['userPhone']) != 10)
		{
			$json['phone_error'] = "Please Enter Your Valid 10 Digits Mobile Number.";
			$formStatus = false;
		}
		if($formStatus == true )
		{
			$result = $this->useraccount_setting_model->addUserAddress_model($userid,$addressInfo);
			if($result)
			{
				$json['html'] = $this->getDeliveryAddress();
				$json['success_msg'] = "Address Added Successfully.";
			}
			else
			{
				$json['success_error_msg'] = "Somthing Went Wrong Please Retry. ";
			}
		}
		echo json_encode($json);
	}
	public function getDeliveryAddress()
	{
		$userid = $this->session->userdata('user_id');
		$addreses = $this->useraccount_setting_model->get_Delivery_address($userid);
		$html = '';
		foreach ($addreses as $key => $address) 
		{
			$selected = '';
			$checked = '';
			if($address->default_status == "Y")
			{
				$selected = 'selected';
				$checked  = 'checked="checked"';
			}
			$html .= '<li class="'.$selected.'">';
            $html .= '<div class="detail">';
            $html .= '<h4>'.$address->name.'</h4>';
            $html .= '<div class="actbtn"><a href="javascript:void(0);" id="'.$address->delivery_id.'" class="delete_del"><i class="material-icons">delete</i></a></div>';
            $html .= '<address>';
            $html .= '<p>'.$address->address.'</p>';
            $html .= '<p>'.$address->city.''.$address->state.'</p>';
            $html .= '<p>'.$address->pincode.'</p>';
            $html .= '</address>';
            $html .= '<span class="tel">'.$address->mobile.'</span>';
            $html .= '<div class="dft_btn"><input type="radio" class="default_radio" id="'.$address->delivery_id.'" '.$checked.'>Default Address</div>';
            $html .= '</div>';
            $html .= '</li>';
		}
		return $html;
	}
	public function userProfileSetting() /*----------this function not in use --------*/
	{
		$data = $this->data;
		$data['page_settings']  = $this->Comman_model->get_page_setting(4);
		$this->load->view('dashboard/view_profile_setting',$data);
	}
	public function view_Deactivate_Account()
	{
		$data = $this->data;
		$data['page_settings']  = $this->Comman_model->get_page_setting(4);
		$this->load->view('dashboard/view_deactivateAccount',$data);
	}
	public function deactivateAccount()
	{
		$data = $this->data;
		$userid = $this->session->userdata('user_id');		
		$password 				= $this->input->post('prePassword');
		$json					= array();
		$old_password			= $this->useraccount_setting_model->getExistingpassword($userid);
		if(!empty($old_password))
		{
			$entered_password		= md5($password);
			if($entered_password == $old_password )
			{
				$result = $this->useraccount_setting_model->deactivateAccount_model($userid);
				if($result)
				{	
					$json['success_msg'] = "Your account has been deactivated. ";
					$this->session->unset_userdata('user_id');
					$this->session->sess_destroy();
				}
				else
				{
					$json['error_msg']  = "Somthing went wrong please retry. ";
				}
			}
			else
			{
				$json['error_msg'] 		= 'Password Not Match! Please Check Your Password';
			}
		}
		echo json_encode($json);
	}
	/*------------------- ANIL Code Start------------------------*/
	public function makeDefaultAddress()
	{
		$data = $this->data;
		$json = array();
		$del_id = $this->input->post('del_id');
		$userid = $this->session->userdata('user_id');
		$status = $this->useraccount_setting_model->makeDefaultAddress($userid,$del_id);
		if($status > 0)
		{
			$json['html'] = $this->getDeliveryAddress();
			$json['success'] = 'Your Address Make Successfully as Default';
		}
		echo json_encode($json);
	}
	public function deleteDeliveryAddress()
	{
		$data = $this->data;
		$json = array();
		$del_id = $this->input->post('del_id');
		$userid = $this->session->userdata('user_id');
		$status = $this->useraccount_setting_model->deleteDeliveryAddress($userid,$del_id);
		if($status > 0)
		{
			$json['html'] = $this->getDeliveryAddress();
			$json['success'] = 'Your Address Deleted Successfully';
		}
		echo json_encode($json);
	}
	public function userWallet()
	{
		$data = $this->data;
		$data['page_settings']  = $this->Comman_model->get_page_setting(4);
		$userid = $this->session->userdata('user_id');
		$data['tot_money'] = $this->useraccount_setting_model->getUserWallet($userid);
		$this->load->view('dashboard/viw_user_wallet',$data);
	}
	public function addWalletMoney()
	{
		$data = $this->data;
		$json = array();
		$walletData = $this->input->post();
		$userid = $this->session->userdata('user_id');
		$voucher =	$this->useraccount_setting_model->authUserWallet($userid,$walletData['voucher_pin']);
		if($voucher)
		{
			if($voucher->voucher_code == $walletData['voucher_code'] && $voucher->voucher_pin == $walletData['voucher_pin'])
			{
				$status =	$this->useraccount_setting_model->insertUserVoucherRecord($userid,$voucher->voucher_id,$voucher->voucher_amount);
				$this->useraccount_setting_model->resetVoucherPin($userid,$voucher->voucher_id);
				if($status)
				{				
					$json['success'] = 'Voucher Redeem Successfully!';
				}
				else
				{
					$json['failed'] = 'Oops! Something went wrong. Try Again!';
				}
			}
			else
			{
				$json['invalid'] = 'No Voucher find! Contact Bulknmore.';
			}
		}
		else
		{
			$json['expired'] = 'Voucher has been Expired!';
		}		
		//print_r($walletData);
		echo json_encode($json);
	}
	public function Wish_List()
	{
		$data = $this->data;
		$data['page_settings']  = $this->Comman_model->get_page_setting(4);
		$userid = $this->session->userdata('user_id');
		$data['wishlist'] 	=	$this->useraccount_setting_model->user_Wish_List($userid);
		//echo "<pre>";print_r($data['wishlist']);exit;
		foreach ($data['wishlist'] as $key=>$value) 
		{
			$standard_price = $value->standard_price;
			$selling_price 	= $value->selling_price;
			$saving_price 	= $standard_price - $selling_price;
			$offer_per		= floor(($saving_price * 100) / $standard_price);
			$data['wishlist'][$key]->offer_per = $offer_per;
		}
		$this->load->view('dashboard/viw_wishlist',$data);
	}
	public function deleteWishList()
	{	
		$json = array();
		$wishlist_id = $this->input->post('wishlist_id');
		$status = $this->useraccount_setting_model->delete_Wish_List($wishlist_id);
		if($status)
		{
			$json['success'] = 'Successfully Deleted...!';
		}
		echo json_encode($json);
	}
	public function show_user_orders()
	{
		$data = $this->data;
		$data['page_settings']    = $this->Comman_model->get_page_setting(40);
		$this->load->view('dashboard/view_orders', $data);
	}
	public function User_Orders()
	{
		$data = $this->data;
		$post = $this->input->post();
		$html = '';
		$userid                   = $this->session->userdata('user_id');
		$order_list 	          =	$this->useraccount_setting_model->get_user_Orders($userid,$post['page']);
		$json = array();  
        if(!empty($order_list)) 
        {   
            foreach($order_list as $product_order)
            {                
            	$product_list = $this->useraccount_setting_model->get_user_Product_list($userid,$product_order->master_order_id);
                $total_amount = $shipping_chrge = 0;
                foreach($product_list as $pro=>$pro_odr)
                {
                    $total_amount += $pro_odr->total_amount;
                    $shipping_chrge =  $pro_odr->shipping_charge;
                }
            	$order_status = $this->useraccount_setting_model->getOrderStatus($product_order->master_order_id);
            	$return_order_status = $this->useraccount_setting_model->getReturnOrderStatus($product_order->master_order_id);
			    $html .= 	'<div class="order-dashboard-block dv_order_main">                
				                <div class="order-dashboard-header clearfix">
				                    <div class="order-id-block">                    
				                        <h4>Order id <span>'.$product_order->unique_order_id.'</span></h4>
				                        <p class="order-placed">Placed on: <span>'.date('d-M-Y',strtotime($product_order->order_date)).'</span></p>
				                        <p class="order-amount">Total amount:<span> &#x20B9 <b>'.round($total_amount + $shipping_chrge - $product_order->voucher_amount).'/-</b></span>(inc Taxes)</p>
				                    </div>
				                    <div class="order-btn-block">			                    
				                    <a href="javascript:void(0)" class="btn order_detail" id="order-id='.$product_order->unique_order_id.'" target="_blank">Order Details</a>
				                    <a href="#track-items-'.$product_order->order_id.'" class="btn track_btn fancybox">Track Item</a>
				                    </div>
				                </div>
				                <!--popup start-->
				                    <div class="track-popup" id="track-items-'.$product_order->order_id.'">
				                        <h4>Item tracking</h4>
				                        <div class="tracking-header-block clearfix">
				                            <div>
				                                <p>Item Name : '.ucfirst(str_replace('_',' ',$product_order->item_name)).'</p>
				                                <p>Set Description : '.ucfirst($product_order->set_description).'</p>
				                            </div>
				                            <div>
				                                <p>Order ID: '.$product_order->unique_order_id.'</p>
				                                <p>Placed on: '.date('d-M-Y',strtotime($product_order->order_date)).'</p>
				                            </div>
				                        </div>
				                        <div class="schudule-wrapper">
				                            <div class="schudule-header clearfix">
				                                <div><p><strong>Delivery Status</strong></p></div>
				                                <div><p><strong>Delivery Estimate:</strong> '.date('d-M-Y',strtotime($product_order->exp_order_date)).'</p></div>
				                            </div>
				                            <div class="tracking-status-block clearfix">                                
				                                <div class="product-track">
				                                    <div class="tracking-arrow"><i class="fa fa-check-circle" aria-hidden="true"></i></div>
				                                    <div class="order-detail">
				                                        <p><span>'.date('d-M-Y H:i:s',strtotime($product_order->order_date)).'</span></p>
				                                        <p class="green_text">PLACED</p>
				                                    </div>
				                                </div>';
			                                if($product_order->order_status == 7) 
			                                {
				                            $html .= '<div class="product-track">
				                                    <div class="cancel-tracking-arrow"><i class="fa fa-check-circle" aria-hidden="true"></i></div>
				                                    <div class="order-detail">
				                                        <p><span>'.date('d-M-Y H:i:s',strtotime($product_order->cancel_date)).'</span></p>
				                                        <strike><p class="red_text">CANCELLED</p></strike>
				                                    </div>
				                                </div>';
			                                }
			                                else 
			                                {
				                                foreach($order_status as $track) 
				                                {
					                                if($track->order_status == 6) {
					                        $html .= '<div class="product-track">
					                                    <div class="tracking-arrow"><i class="fa fa-check-circle" aria-hidden="true"></i></div>
					                                    <div class="order-detail">
					                                        <p><span>'.date('d-M-Y H:i:s',strtotime($track->status_date)).'</span></p>
					                                        <p class="green_text">DELIVERED</p>
					                                    </div>
					                                </div>';
					                                }
					                                if($track->order_status == 9) {
					                        $html .= '<div class="product-track">
					                                    <div class="tracking-arrow"><i class="fa fa-check-circle" aria-hidden="true"></i></div>
					                                    <div class="order-detail">
					                                        <p><span>'.date('d-M-Y H:i:s',strtotime($track->status_date)).'</span></p>
					                                        <p class="green_text">OUT FOR DELIVERY</p>
					                                    </div>
					                                </div>';
					                                }
					                                if($track->order_status == 8) {
					                        $html .= '<div class="product-track">
					                                    <div class="tracking-arrow"><i class="fa fa-check-circle" aria-hidden="true"></i></div>
					                                    <div class="order-detail">
					                                        <p><span>'.date('d-M-Y H:i:s',strtotime($track->status_date)).'</span></p>
					                                        <p class="green_text">REACHED AT HUB</p>
					                                    </div>
					                                </div>';
					                                }
					                                if($track->order_status == 5) {
					                        $html .= '<div class="product-track">
					                                    <div class="tracking-arrow"><i class="fa fa-check-circle" aria-hidden="true"></i></div>
					                                    <div class="order-detail">
					                                        <p><span>'.date('d-M-Y H:i:s',strtotime($track->status_date)).'</span></p>
					                                        <p class="green_text">IN-TRANSIT</p>
					                                    </div>
					                                </div>';
					                                }
					                                if($track->order_status == 4) {
					                        $html .= '<div class="product-track">
					                                    <div class="tracking-arrow"><i class="fa fa-check-circle" aria-hidden="true"></i></div>
					                                    <div class="order-detail">
					                                        <p><span>'.date('d-M-Y H:i:s',strtotime($track->status_date)).'</span></p>
					                                        <p class="green_text">HANDOVER</p>
					                                    </div>
					                                </div>';
					                                }
					                                if($track->order_status == 3) {
					                        $html .= '<div class="product-track">
					                                    <div class="tracking-arrow"><i class="fa fa-check-circle" aria-hidden="true"></i></div>
					                                    <div class="order-detail">
					                                        <p><span>'.date('d-M-Y H:i:s',strtotime($track->status_date)).'</span></p>
					                                        <p class="green_text">PACKED</p>
					                                    </div>
					                                </div>';
					                                }
					                                if($track->order_status == 2) {
					                        $html .= '<div class="product-track">
					                                    <div class="tracking-arrow"><i class="fa fa-check-circle" aria-hidden="true"></i></div>
					                                    <div class="order-detail">
					                                        <p><span>'.date('d-M-Y H:i:s',strtotime($track->status_date)).'</span></p>
					                                        <p class="green_text">CONFIRMED</p>
					                                    </div>
					                                </div>';
					                                }
				                                }
				                            }
				                        $html .= '</div>
				                            <div class="shipping-details-block clearfix">
				                                <div class="shipping-address">
				                                    <h5>Shipping to:</h5>
				                                    <p class="tracking-details">'.$product_order->name.' <br>'.$product_order->address.',<br>'.$product_order->city.','.$product_order->state.'-'.$product_order->pincode.'</p>
				                                </div>
				                                <div class="shipping-items-track">
				                                    <h5>Track item through SMS:</h5>
				                                    <p class="tracking-details">You will receive SMS on the status of your item on<br>+91-'.$product_order->mobile.'</p>
				                                </div>
				                            </div>
				                        </div>
				                    </div>
				                    <!--end popup-->
				                <!--end order header-->';
		                	foreach($product_list as $order) 
		                	{
                                foreach($order_status as $track){}
			                	if(!empty($return_order_status)) { foreach($return_order_status as $return_track){} }
						        $html .= '<div class="product-info-wrapper clearfix">
						                    <div class="thumb"><img src="'.$data['image_path']['product_image'].'seller_listing/'.$order->image_name.'" alt=""></div>
						                    <div class="product-desc">
						                        <h4><a href="'.$order->category_url.'/'.$order->sub_category_url.'/'.$order->subtosub_category_url.'/'.$order->product_url.'/'.make_encrypt($order->product_id).'">'.ucfirst(str_replace('_',' ',$order->item_name)).'</a></h4>
						                        <div class="size-block">
						                            <span class="color-block"><span class="light">Set Description:</span> '.ucwords($order->set_description).'</span>';
						                            if(!empty($order->size_name)) {
						        $html .=          	'<span class="divider">|</span>
						        					<span class="color-block"><span class="light">Size:</span> '.ucwords($order->size_name).'</span>';
						                            }
						        $html .=            '<span class="divider">|</span>
						                            <span class="color-block"><span class="light">Set:</span> '.$order->quantity.'</span>
						                            <span class="divider">|</span>
						                            <span class="color-block"><span class="light">Qty:</span> '.($order->quantity * $order->pack_of).'</span>
						                            <span class="divider">|</span>
						                            <span class="color-block"><span class="light">Per/Piece: &#x20B9</span> '.$order->price.'</span>
						                        </div>
						                    </div>                    
						                    <div class="deliver-time">';
					                        if($order->order_item_status != 2) 
					                        {
					                    		if($track->order_status == 1) { 
					                    			$html .= '<h6 class="green_text"> PLACED </h6>'; 
					                    		} else if($track->order_status == 2 ) {
					                    		 	$html .= '<h6 class="green_text"> CONFIRMED </h6>';
					                    		} else if($track->order_status == 3 ) { 
					                    			$html .= '<h6 class="green_text"> PACKED </h6>'; 
					                    		} else if($track->order_status == 4) { 
					                    			$html .= '<h6 class="green_text"> HAND-OVER </h6>'; 
					                    		} else if($track->order_status == 5) { 
					                    			$html .= '<h6 class="green_text"> IN-TRANSIT </h6>'; 
					                    		} else if($track->order_status == 6 && $order->order_item_status != 3) { 
					                    			$html .= '<h6 class="green_text"> DELIVERED </h6>'; 
					                    		} else if($track->order_status == 8) { 
					                    			$html .= '<h6 class="green_text"> REACHED AT HUB </h6>'; 
					                    		} else if($track->order_status == 9) { 
					                    			$html .= '<h6 class="green_text"> OUT FOR DELIVERY </h6>'; 
					                    		} else if(!empty($return_track->order_status) == 1 && $order->order_item_status == 3) { 
					                    			$html .= '<h6 class="green_text"> RETURN REQUESTED </h6>'; 
					                    		} else if(!empty($return_track->order_status) == 2) { 
					                    			$html .= '<h6 class="green_text"> RETURN CONFIRMED </h6>'; 
					                    		} else if(!empty($return_track->order_status) == 3) { 
					                    			$html .= '<h6 class="green_text"> RETURN IN-TRANSIT </h6>'; 
					                    		} else if(!empty($return_track->order_status) == 4) { 
					                    			$html .= '<h6 class="green_text"> RETURN COMPLETED </h6>';
					                    		}
                                                						                    
					                        	if($order->order_item_status != 3 && $track->order_status != 7) {
						                    		$html .= '<p>on '.date('D',strtotime($track->status_date)).' | '.date('d-M-Y H:i',strtotime($track->status_date)).'</p>';
						                        } else if(!empty($return_track)){
						                    		$html .= '<p>on '.date('D',strtotime($return_track->status_date)).' | '.date('d-M-Y H:i',strtotime($return_track->status_date)).'</p>';
						                        }
					                        } else {
					                    		$html .= '<h6 class="red_text"><strike> "CANCELLED" </strike></h6>                        
					                        	<p>on '.date('D',strtotime($track->status_date)).' | '.date('d-M-Y H:i',strtotime($track->status_date)).'</p>';
					                        }
						           $html .= '</div>
						                    <div class="notice-block text-right clearfix">';
					                    	if(($track->order_status == 1) && $order->order_item_status != 2) {
					                			$html .= '<input type="hidden" name="cancelled_order_id" value="'.make_encrypt($product_order->master_order_id).'">
					                    				 <a href="javascript:void(0);" name="cancel_order_button" class="btn fancybox">Cancel</a>';
					                    	}
					                    	$policy_days = 3;	
					                    	$delivery_date =  date("d-m-Y", strtotime("+".$policy_days." days", strtotime(DATE($track->status_date))));
                                            
					                    	if($order->order_item_status == 1 && empty($return_track->order_status) && !in_array($order->order_status,array(1,2,3,4,9)) && strtotime(date('d-m-Y')) <= strtotime($delivery_date)) {
						                    		$html .= '<input type="hidden" name="return_order_id" value="'.make_encrypt($product_order->master_order_id).'">
						                    			  	 <a href="javascript:void(0)" name="return_order_button" class="btn fancybox">Return</a>';
						                    	} 
						                    	else if(!empty($return_track->order_status) && $order->order_item_status == 3) {
						                    		$html .= '<p>You cannot return or exchange this item</p>';
						                    	}
						            $html .= 	'<a href="#cancel_order_request" id="cancellation_popup" class="btn fancybox" style="display: none;">Cancellation</a>
						                    	<div class="request_cancel" >						                                
						                            </div>
						                            <!--request cancel popup end-->
						                            <a href="#cancellation_request" id="popup_confirm_cancellation" class="btn fancybox" style="display: none;">Confirm Cancellation</a>
						                            <div id="cancellation_request">
						                                <h3>Cancellation Requested</h3>
						                                <p>We have received your cancellation request. You will receive a confirmation once the cancellation is completed.</p>
						                                <div class="btn_group">
						                                    <a href="javascript:parent.jQuery.fancybox.close();" class="btn close_btn">Close</a>
						                                </div>
						                            </div>
						                            <!--end cancel request-->
						                    	</div>
						                	</div>';
			               }
			     $html .=    '</div>';
        	}
        } 
        else
        { 
	        $html .= '<div class="wbox empty_cart" style="margin-top:15px;">            
	            <p> No More Record. </p>
	            </div>';
        }
		$json['html'] = $html;
		echo json_encode($json);
	}
	public function User_Order_Details($order_id)
	{
		$data = $this->data;
		$data['page_settings']  = $this->Comman_model->get_page_setting(40);
		$userid   = $this->session->userdata('user_id');
		$orderid  = $this->postback();
		$orders	  =	$this->useraccount_setting_model->getOrderDetails($userid,$orderid);		
		if(!empty($orders))
		{
			$order_details = array();
            $total_amount = $total_tax =0;
			foreach ($orders as $key => $value) 
			{
				$order_status           = $this->useraccount_setting_model->getOrderStatus($value->master_order_id);
                $return_order_status    = $this->useraccount_setting_model->getReturnOrderStatus($value->master_order_id);
                //print_r($return_order_status);exit;
				$obj                    = new stdClass();
				$obj->order_id          = $value->unique_order_id;
				$obj->order_date        = $value->order_date;
				$obj->order_status      = $value->order_status;
				$obj->cancel_date       = $value->cancel_date;
				$obj->name              = $value->name;
	            $obj->address           = $value->address;
	            $obj->mobile            = $value->mobile;
	            $obj->state             = $value->state;
	            $obj->city              = $value->city;
	            $obj->pincode           = $value->pincode;
	            $obj->size_name 		= $value->size_name;
	            $obj->payment_type      = $value->payment_type;
                $obj->voucher_amount	= $value->voucher_amount;
                $total_amount          += $value->total_amount;
				$obj->total_price       = $total_amount + $value->shipping_charge - $value->voucher_amount;
                $obj->shipping_charge   = $value->shipping_charge;
                $total_tax             += $value->service_tax;
                $obj->total_tax         = $total_tax;
				$order_details          = $obj;
				$order_details->track_status        = $order_status;
                $order_details->return_track_status = $return_order_status;
			}
			$data['order_details']   = $order_details;
			$data['orders']          = $orders;
			//echo "<pre>";print_r($data['order_details']);exit;
			//echo "<pre>";print_r($data['orders']);
			$this->load->view('dashboard/viw_order_details',$data);
		}
		else
		{
			redirect();
		}
	}
	private function postback() 
    {
        return (isset($_GET['order-id']))?trim($_GET['order-id']):'';
    }
	/*------------------- ANIL Code End------------------------*/
	/*product order pagination start here*/
	public function order_pagination()
	{
		$data     				= $this->data;
		$page_no   				= $this->input->post('page'); 
		$userid 				= $this->session->userdata('user_id');
		$order_list 	=	$this->useraccount_setting_model->get_user_Orders($userid,$page_no);
		//echo $this->db->last_query();exit;
		foreach ($order_list as $key => $value) {
			$total_price = 0;
			$product_list = $this->useraccount_setting_model->get_user_Product_list($userid,$value->order_id);
			$test =  count($product_list); 
			foreach ($product_list as $value) {
				$total_price += $value->price;
			}
			$order_list[$key]->cnt_order_list = $test;
			$order_list[$key]->total_price = $total_price;
			$order_list[$key]->orders = $product_list;
		}//exit;
		//$data['orders'] = $order_list;
		$html = '';
		$test2='';
		$json = array();
			foreach($order_list as $product_order)
			{
				$html.='
				 <div class="order-dashboard-block">                
                 <div class="order-dashboard-header clearfix">
                        <h4>Order id <span>OD'. str_pad($product_order->order_id,15,0,STR_PAD_LEFT).'</span></h4>
                        <p class="order-placed">Placed on <span>'.$product_order->order_date.'</span></p>
                        <p class="order-amount">Total amount:<span> &#x20B9 <b>'. floor($product_order->total_price).'/-</b></span></p>
                    </div>
                    <div class="order-btn-block"><a href="javascript:void(0)" class="btn order_detail" id=" '. "order-id=OD".str_pad($product_order->order_id,15,0,STR_PAD_LEFT).'" target="_blank">Order Details</a></div>
                </div>';
                foreach($product_order->orders as $order)
                {
                	if($order->order_status == 2) { $test = "Your Order Has Been Placed.";} 
                	else if($order->order_status == 3) { $test = "Your Order Has Been Packed."; } 
                	else if($order->order_status == 4) { $test = "Your Order Handover To Courier."; } 
                	else if($order->order_status == 5) { $test = "Your Order is in Transit."; } 
                	else { $test = "Your Order Has Been Placed."; }
                  $html .= '<div class="product-info-wrapper clearfix">
                    <div class="thumb"><img src="'.$data['image_path']['product_image'].''.$order->image_name.'" alt=""></div>
                    <div class="order-id-block">
                    <h4><a href="javascript:void(0)">'.ucfirst(str_replace('_',' ',$order->item_name)).'</a></h4>
                        <div class="size-block">
                            <span class="color-block"><span class="light">Color:</span>'.ucfirst($order->color_name).'</span>
                            <span class="divider">|</span>
                            <span class="color-block"><span class="light">Size:</span>'.ucfirst($order->size_name).'</span>
                            <span class="divider">|</span>
                            <span class="color-block"><span class="light">Qty:</span> '.ucfirst($order->quantity).'</span>
                            <span class="divider">|</span>
                            <span class="color-block"><span class="light">&#x20B9</span> '.ucfirst($order->price).'</span>
                        </div>
                    </div>
                    <div class="deliver-time">
                        <h6 class="green_text">'.$test.'</h6>
                        <!--<p>on Sun, 8 Dec 2013</p>-->
                    </div>
                    <div class="notice-block text-right">
                        <a href="#track-items-'.$order->product_id.'" class="btn track_btn fancybox">Track Item</a>
                    </div>
                    <!--popup start-->
                    <div class="track-popup" id="track-items-'.$order->product_id.'">
                        <h4>Item tracking</h4>
                        <div class="tracking-header-block clearfix">
                            <div>
                                <p>Item Name : '.ucfirst(str_replace('_',' ',$order->item_name)).'</p>
                                <p>Color : '.ucfirst($order->color_name).'</p>
                            </div>
                            <div>
                                <p>Order ID: '.str_pad($order->order_id,15,0,STR_PAD_LEFT).'</p>
                                <p>Placed on: '.$order->order_date.'</p>
                            </div>
                        </div>
                        <div class="schudule-wrapper">
                            <div class="schudule-header clearfix">
                                <div><p><strong>Delivered</strong></p></div>
                                <div><p><strong>Delivery Estimate:</strong> '.$order->exp_order_date.'</p></div>
                            </div>
                            <div class="tracking-status-block clearfix">
                                '.$test2.'
                                <div class="product-track">
                                    <div class="tracking-arrow"><i class="fa fa-check-circle" aria-hidden="true"></i></div>
                                    <div class="order-detail">
                                        <p><span>'.$order->order_date.'</span></p>
                                        <p class="green_text"> Your order has been confirmed!</p>
                                    </div>
                                </div>
                            </div>
                            <div class="shipping-details-block clearfix">
                                <div class="shipping-address">
                                    <h5>Shipping to:</h5>
                                    <p class="tracking-details">'.$order->name.'<br>'.$order->address.',<br>'.$order->city.', '. $order->state.'-'.$order->pincode.'</p>
                                </div>
                                <div class="shipping-items-track">
                                    <h5>Track item through SMS:</h5>
                                    <p class="tracking-details">You will receive SMS on the status of your item on<br>+91-'.$order->mobile.'</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end popup-->
                </div>';
                }    
               $html .= '</div>';
			} 
		$json['html']	  	= $html;
		$json['count']		=$this->useraccount_setting_model->cnt_get_user_Orders($userid);;
		echo json_encode($json);
	}
	/*end product order pagination*/
	//============================get added cards======================
	public function get_saved_cards()
	{
		$data					= $this->data;
		$userid 				= $this->session->userdata('user_id');
		$data['page_settings']	= $this->useraccount_setting_model->get_page_title('18');
		$data['details']		= $this->useraccount_setting_model->getUserCardsDetails($userid);
		$this->load->view('dashboard/view_saved_cards',$data);
	}
	//==========================Save Cards details======================
	public function save_card_details()
	{
		$data					= $this->data;
		$userid 				= $this->session->userdata('user_id');
		$card_number			= $this->input->post('card_no');
		$cardno_status = $this->useraccount_setting_model->get_saved_cards($userid,$card_number);
		if($cardno_status > 0)
		{
			$json['card_exist'] = 1;
		}
		else
		{
		$cardHolder_name		= $this->input->post('card_holder_name');
		$card_exp_month			= $this->input->post('exp_month');
		$card_exp_year			= $this->input->post('exp_year');
		$cardLabel				= $this->input->post('card_labels');
		$cardType				= $this->input->post('card_type');
		$json					= array();
		$card_details_array		= array();
			$obj				= new stdClass();
			$obj->user_id		= $userid;
			$obj->card_no		= $card_number;
			$obj->card_holder_name = $cardHolder_name;
			$obj->exp_month		= $card_exp_month;
			$obj->exp_year		= $card_exp_year;
			$obj->card_type		= $cardType;
			$obj->card_label	= $cardLabel;
			$card_details_array = $obj;
		$status					= $this->useraccount_setting_model->save_cards_details($card_details_array);
		if($status)
		{
			$json['type']			= $card_number;
		}
		}
		echo json_encode($json);
	}
	public function remove_saved_card()
	{
		$data					= $this->input->post();
		$userid 				= $this->session->userdata('user_id');
		$cardid					= $this->input->post('cardId');
		$json					= array();
		$status					= $this->useraccount_setting_model->delete_card_details($userid,$cardid);
		$json['cardId']			= $cardid;
		echo json_encode($json);
	}
	//=======geting data for update email/mobile ==========
	public function viewUserEmailMobile()
	{
		$data = $this->data;
		$data['test'] = $this->uri->segment(1);
		$data['pagename']  = "Update Email/Mobile";
		$data['pagename']  = "Update Email/Mobile";
		$data['page_settings']  = $this->Comman_model->get_page_setting(4);
		$userid = $this->session->userdata('user_id');
		//$data['user_details'] = $this->session->userdata();
		$data['user_details'] 	= $this->useraccount_setting_model->get_user_details_byuserid($userid);
		$this->load->view('dashboard/view_update_emailmobile',$data);
	}
	public function check_user_exist()
	{
		$data			= $this->data;
		$json			= array();
		$user_mob 		= $this->input->post('user_mob');
		$status			= $this->useraccount_setting_model->get_user_by_mobileno($user_mob);
		if($status > 0)
		{
			$json['mob_exist']	= 1;
		}
		else
		{
			$json['mob_exist']	= 0;
		}
		echo json_encode($json);
	}
	public function send_otp_to_users()
	{
		$data			= $this->data;
		$arr			= array();
		$json			= array();
		$userid 		= $this->session->userdata('user_id');
		$new_mobno		= $this->input->post('mobileno');
		$status			= $this->useraccount_setting_model->get_user_by_mobileno($new_mobno);
		if($status > 0)
		{
			$json['mob_exist']	= 1;
		}
		else
		{
		$otp			= rand(1000,9999);
		$msg = "Great work! You're almost there. We just wanna make sure its you and we would need you to verify this magical number. Go ahead and put in.  ";
		if(!empty($otp))
		{
			$arr['otpcode']	= $otp;
			$otp_status	= $this->useraccount_setting_model->update_user_otp_formobile($userid,$arr);
			sending_otp($new_mobno,$msg.' '.$otp);
		}
			$json['mob_exist'] = 0;
		}
		//$json['mob_no']	= $otp_status;
		echo json_encode($json);	
	}
	public function get_user_otp()
	{
		$data		= $this->data;
		$json		= array();
		$otp		= $this->input->post('user_otp');
		$userid 		= $this->session->userdata('user_id');
		$result			= $this->useraccount_setting_model->get_user_otp_model($userid);
		if(!empty($result))
		{
			$json['otp']	= $result->otpcode;
		}
		echo json_encode($json);	
	}
	public function get_user_pass()
	{
		$data		= $this->data;
		$json		= array();
		$user_password	= md5($this->input->post('user_pass'));
		$userid 	= $this->session->userdata('user_id');
		$result		= $this->useraccount_setting_model->get_user_password_model($userid);
		if(!empty($result))
		{
			$pass		= $result->password;
			if($user_password == $pass)
			{
				$json['pass']	= 1;
			}
			else
			{
				$json['pass']	= 0;
			}
		}
		echo json_encode($json);
	}
	public function save_user_mobile()
	{
		$data		= $this->data;
		$json		= array();
		$post		= array();
		$mobile_no	= $this->input->post('mobno');
		$userid 	= $this->session->userdata('user_id');
		$post['mobile'] = $mobile_no;
		$status		= $this->useraccount_setting_model->update_user_emailmobile($userid,$post);
		if(!empty($status))
		{
			if($status > 0)
			{
				$json['update_success'] = 1;
				$json['mob_no'] = $mobile_no;
			}
			else
			{
				$json['update_success'] = 0;
			}
		}
		$json['mob_no'] = $userid ;
		echo json_encode($json);
	}
	//---------geting data for update email---------------
	public function get_existing_email()
	{
		$data	= $this->data;
		$json	= array();
		$post	= array();
		$userid 	= $this->session->userdata('user_id');
		$post		= $this->input->post('email_id');
		$status		= $this->useraccount_setting_model->get_email_model($post);
		if($status > 0)
		{
			$json['email_exist']	= 1;
		}
		else
		{
			$json['email_exist']	= 0;
			//$msg					= "Great work! You're almost there. We just wanna make sure its you and we would need you to verify this magical number. Go ahead and put in.  ";
			$otp					= rand(1000,9999);
			$user_otp			= $otp;
			$arr['otpcode']		= $otp;
			$otp_status			= $this->useraccount_setting_model->update_user_otp_formobile($userid,$arr);
			$email				= trim($post);
			$emailid			= 16;
			//-----code for send Email -----------------------------------------
				$autoemail 			 = $this->useraccount_setting_model->get_autoemail_details($emailid);//common->GetTableRow('*', 'tblblk_autoemail', array('email_id' =>8));
				$email_from 		 = $autoemail->email_from_email;
				$subject   			 = $autoemail->email_subject;
				$content   			 = $autoemail->email_description;
				$headers     		 = 'MIME-Version: 1.0' . "\r\n";
				$headers    		.=  'Content-type: text/html; charset=iso-8859-1' . "\r\n";
				$headers    		.=  'From: '.$autoemail->email_from_email. "\r\n";//< '.$email.' >
				$link     			 = "bulknmore.com";
				$link_url   		 =  "<a href='$link' target='_blank'>$link</a>";
				$emailcontent 		 = str_replace("{code}",$user_otp,$content);
				$emailcontent 		 = str_replace("{email}",$email,$emailcontent);
				$emailcontent 		 = str_replace("{link}",$link_url,$emailcontent);
				$getemail            =$this->sendEmail($email,$email_from,$subject,$emailcontent);
				//-----End of send Email -------------------------------------------
		}
		echo json_encode($json);
	}
	public function get_user_email_otp()
	{
		$data		= $this->data;
		$json		= array();
		$otp		= $this->input->post('user_email_otp');
		$userid 		= $this->session->userdata('user_id');
		$result			= $this->useraccount_setting_model->get_user_otp_model($userid);
		if(!empty($result))
		{
			$json['otp']	= $result->otpcode;
		}
		echo json_encode($json);	
	}
	public function get_user_pass_foremail()
	{
		$data		= $this->data;
		$json		= array();
		$user_password	= md5($this->input->post('useremail_pass'));
		$userid 	= $this->session->userdata('user_id');
		$result		= $this->useraccount_setting_model->get_user_password_model($userid);
		if(!empty($result))
		{
			$pass		= $result->password;
			if($user_password == $pass)
			{
				$json['pass']	= 1;
			}
			else
			{
				$json['pass']	= 0;
			}
		}
		echo json_encode($json);
	}
	public function save_user_email()
	{
		$data		= $this->data;
		$json		= array();
		$post		= array();
		$emailid	= $this->input->post('emailId');
		$userid 	= $this->session->userdata('user_id');
		$post['email'] = $emailid;
		$status		= $this->useraccount_setting_model->update_user_emailmobile($userid,$post);
		if(!empty($status))
		{
			if($status > 0)
			{
				$json['update_success'] = 1;
				//$json['mob_no'] = $mobile_no;
			}
			else
			{
				$json['update_success'] = 0;
			}
		}
		//$json['mob_no'] = $userid ;
		echo json_encode($json);
	}
	//-----------for send mail---------------------
	public function sendEmail($email_to,$email_from,$email_subject,$message)
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
        $this->email->from($email_from,'Bulknmore | Report');
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
	  /*if(mail($email,$subject,$emailcontent,$headers))
	  {}
	  else
	  {}*/
	 }
	//-----------end send mail---------------------
	//-----------end------------------------------
	//======= End geting data for update email/mobile ==========
    //----------------fir add refund bank details --------------
	public function view_refund_bank_details()
	{
		$data 	= $this->data;
        $userid 	= $this->session->userdata('user_id');
        $data['page_settings']  = $this->Comman_model->get_page_setting(4);
		$data['return_bank_details'] = $this->useraccount_setting_model->get_user_savebank_details($userid);
		$this->load->view('dashboard/view_add_refundBank_details',$data);
	}
	public function save_bank_details()
	{
		$post 	= $this->input->post();
		$userid 	= $this->session->userdata('user_id');
		$bankdetail_array = array();
		$obj 	= new stdClass();
		$obj->user_id	= $userid ;
		$obj->bank_name	= $post['bank_name'];
		$obj->city_name	= $post['city_name'];
		$obj->ifsc_code	= $post['ifsccode'];
		$obj->account_number	= $post['account_number'];
		$obj->account_type 		= $post['account_type'];
		$bankdetail_array = $obj;
		$status		= $this->useraccount_setting_model->save_bank_details_model($bankdetail_array);
		if($status >= 0)
		{
			$msg['msg_class'] 	= 'green_text';
			$msg['message'] 	= "<strong>Well done!</strong> Your bank account has been added successfully.";
		}
		$notify 				= $msg;
		$this->session->set_flashdata('notity',$notify);
		redirect('refund-bankdetails');
	}
    public function delete_bank_details()
    {
        $json = array();
        $post = $this->input->post();
        $status		= $this->useraccount_setting_model->delete_bank_details(make_decrypt($post['bank_id']));
		if($status >= 0)
		{
            $response['status'] = 1;
			$msg['msg_class'] 	= 'green_text';
			$msg['message'] 	= "<strong>Well done!</strong> Your bank account has been deleted successfully.";
		}
		$notify 				= $msg;
		$this->session->set_flashdata('notity',$notify);        
		echo json_encode($response);
    }
	//--------------End refund bank details --------------------
    
    public function Offers_Vouchers()
    {
        $data 	= $this->data;
        
        $data['page_settings']  = $this->Comman_model->get_page_setting(4);
        $data['offer_list'] = $this->useraccount_setting_model->get_user_cashback_vouchers($data['user_info']->user_id);
        
        $this->load->view('dashboard/view_offers_vouchers',$data);
    }
}
?>