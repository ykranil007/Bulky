<?php
defined('BASEPATH') OR exit("No direct script access allowed");
class Dashboard extends BNM_Controller 
{	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('API/Dashboard_model');
	}
	public function deactivateAccount()
	{
		$data = $this->data;
		$json = array();
        $post = $this->input->post();
        if(!empty($post))
        {
        	$status = $this->Dashboard_model->checkEmailExist($post['user_id'],$post['password']);
        	if($status)
        	{
        		$result = $this->Dashboard_model->deactive_user_account($post['user_id']);
				if($result)
				{	
					$responce['message'] = 'Your Account is de-activated';
					$responce['status'] = 1;					
				}
				else
				{
					$responce['message'] = 'Somthing went wrong please retry';
					$responce['status'] = 2;
				}
        	}
        	else
        	{
        		$responce['message'] = 'Sorry! You entered wrong password.';
				$responce['status'] = 0;
        	}        		
			echo json_encode($responce);
        }
	}
	public function SaveCreditCards()
	{
		$data = $this->data;
		$json = array();
        $post = $this->input->post();
        if(!empty($post))
        {
        	$status = $this->Dashboard_model->save_cards_details($post);
        	if(!empty($status))
        	{
        		$cards = $this->Dashboard_model->getUserCardsDetails($post['user_id']);
        		$responce['user_cards'] = $cards;
        		$responce['message'] = 'Card Successfully Added!';
        		$responce['status'] = 1;
        	}
        	else
        	{
        		$responce['message'] = 'Somthing went wrong please retry';
        		$responce['status'] = 0;
        	}
        	echo json_encode($responce);
        }
	}
	public function DeleteCreditCards()
	{
		$data = $this->data;
		$json = array();
                $post = $this->input->post();
                if(!empty($post))
                {
                	$status = $this->Dashboard_model->delete_card_details($post['user_id'],$post['card_id']);
                	if(!empty($status))
                	{
                		$responce['message'] = 'Successfully Deleted!';
                		$responce['status'] = 1;
                	}
                	else
                	{
                		$responce['message'] = 'Somthing went wrong please retry';
                		$responce['status'] = 0;
                	}
                	echo json_encode($responce);
                }
	}
        public function addWalletMoney()
        {
                $data = $this->data;
                $json = array();
                $walletData = $this->input->post();
                if(!$walletData)
                {
                    $json['status']     = 0;
                    $json['message']    = 'Wrong Request!';
                }
                $voucher    = $this->Dashboard_model->addWalletMoney($walletData['user_id']);
                if($voucher)
                {
                        if($voucher->voucher_code == $walletData['voucher_code'] && $voucher->voucher_pin == $walletData['voucher_pin'])
                        {
                                $status = $this->Dashboard_model->insertUserVoucherRecord($walletData['user_id'],$voucher->voucher_id,$voucher->voucher_amount);
                                $this->Dashboard_model->resetVoucherPin($walletData['user_id'],$voucher->voucher_id);
                                if($status)
                                {                               
                                        $json['message']    = 'Voucher Redeem Successfully!';
                                        $json['status']     = 1;
                                }
                                else
                                {
                                        $json['status']     = 0;
                                        $json['message']    = 'Oops! Something went wrong. Try Again!';
                                }
                        }
                        else
                        {
                                $json['status']     = 3;
                                $json['message']    = 'Oops! Please Reedem Valid Voucher. Try Again!';
                        }
                }
                else
                {
                        $json['status']     = 2;
                        $json['message']    = 'Voucher has been Expired!';
                }               
                //print_r($walletData);
                echo json_encode($json);
        }
        public function get_app_user_Orders()
        { 
            $json       = array();
            $message    = $delivery_date = '';
            $post       = $this->input->post();
            $order_list = $this->Dashboard_model->get_user_Orders($post['user_id']);
            if(!empty($order_list))
            {
                foreach ($order_list as $key => $value) {
                    $total_price = 0;
                    $product_list = $this->Dashboard_model->get_user_Product_list($post['user_id'],$value->master_order_id);
                    $test =  count($product_list);             
                    foreach ($product_list as $prod_key=>$prod_value)
                    {
                        $total_price += ($prod_value->price * ($prod_value->quantity * $prod_value->piece_per_set) + $prod_value->vat_amt + $prod_value->shipping_charge);
                        $or_status = $this->Dashboard_model->getOrderStatus($prod_value->master_order_id);
                        $return_order_status = $this->Dashboard_model->getReturnOrderStatus($prod_value->master_order_id);
                        foreach ($or_status as $or_key => $or_value) {
                            if($prod_value->order_item_status != 2 && $or_value->order_status == 1)
                                $message = 'PLACED';
                            else if($prod_value->order_item_status != 2 && $or_value->order_status == 2)
                                $message = 'CONFIRMED';
                            else if($prod_value->order_item_status != 2 && $or_value->order_status == 3)
                                $message = 'PACKED';
                            else if ($prod_value->order_item_status != 2 && $or_value->order_status == 4)
                                $message = 'HANDOVERED';
                            else if($prod_value->order_item_status != 2 && $or_value->order_status == 5)
                                $message = 'IN-TRANSIT';                        
                            else if($prod_value->order_item_status != 2 && $or_value->order_status == 8)
                                $message = 'REACHED AT HUB';
                            else if($prod_value->order_item_status != 2 && $or_value->order_status == 9)
                                $message = 'OUT FOR DELIVERY';                        
                            else if(($or_value->order_status == 7 || $prod_value->order_item_status == 2) && $prod_value->order_status != 2)
                                $message = 'CANCELLED';
                            else if($prod_value->order_item_status != 2 && $or_value->order_status == 6){
                                $message = 'DELIVERED';
                                $delivery_date = $or_value->status_date;   
                            }
                        }
                        foreach($return_order_status as $return_key=>$return)
                        {
                            if($return->order_status == 1 && $prod_value->order_item_status == 3)
                                $message = 'RETURN REQUESTED';
                            elseif($return->order_status == 2 && $prod_value->order_item_status == 3)
                                $message = 'RETURN CONFIREM';
                            elseif($return->order_status == 3 && $prod_value->order_item_status == 3)
                                $message = 'RETURN TRANSIT';
                            elseif($return->order_status == 4 && $prod_value->order_item_status == 3)
                                $message = 'RETURN COMPLETE';
                        }                    
                        
                        $product_list[$prod_key]->message = $message;
                        $product_list[$prod_key]->delivery_date = date('d-m-Y',strtotime($delivery_date));
                    }
                    $order_list[$key]->cnt_order_list = $test;
                    $order_list[$key]->total_price = round($total_price);
                    $order_list[$key]->orders = $product_list;
                }
                $json['status'] = 1;
                $json['order_list'] = $order_list;
            }
            else
            {
                $json['status'] = 0;
                $json['order_list_img'] = 'assets/images/app_images/no_order.png';
            }
            //exit;
            
            echo json_encode($json);
        }
        public function user_app_order_details()
        {
            $json = array();
            $post = $this->input->post();
            $orders  =  $this->Dashboard_model->getOrderDetails($post['user_id'],$post['order_id']);
            if(!empty($orders))
            {
                $order_details  = array();
                $total_price    = 0;                
                $msg            = '';                
                $status_date    = 0;
                foreach ($orders as $key => $value)
                {
                    $order_status = $this->Dashboard_model->getOrderStatus($value->master_order_id);
                    $obj = new stdClass();
                    $obj->order_id          = $value->master_order_id;
                    $obj->master_unique_id  = $value->master_unique_id;
                    $obj->exp_order_date    = $value->exp_order_date;
                    $obj->name              = $value->name;
                    $obj->address           = $value->address;
                    $obj->mobile            = $value->mobile;
                    $obj->state             = $value->state;
                    $obj->city              = $value->city;
                    $obj->pincode           = $value->pincode;
                    $obj->payment_type      = $value->payment_type;
                    $obj->vat_amt           = round($value->vat_amt);
                    $obj->shipping_charge   = round($value->shipping_charge);
                    if($value->payment_type == 1)
                        $pay_message = 'Cash On Delivery';
                    elseif ($value->payment_type == 2) 
                        $pay_message = 'Net Banking';
                    elseif ($value->payment_type == 3)
                        $pay_message = 'Credit Card';
                    elseif($value->payment_type == 4)
                        $pay_message = 'Debit Card';
                    elseif($value->payment_type == 5)
                        $pay_message = 'Wallet';
                    $obj->pay_message = $pay_message;
                    $total_price  += ($value->price * ($value->quantity * $value->piece_per_set) + $value->vat_amt + $value->shipping_charge);
                    $obj->total_price = $total_price;
                    $obj->sub_total   = round($total_price - ($value->vat_amt + $value->shipping_charge));
                    $order_details = $obj;
                }
                $track_msg = '';
                foreach ($order_status as $order_key => $order_value) 
                {
                    $status = $order_value->order_status;
                    if($status == 1)
                        $track_msg = 'PLACED';
                    if($status == 2)
                        $track_msg = 'CONFIRMED';
                    if($status == 3)
                        $track_msg = 'PACKED';
                    else if($status == 4)
                        $track_msg = 'HANDOVERED';
                    else if($status == 5)
                        $track_msg = 'IN-TRANSIT';
                    else if($status == 8)
                        $track_msg = 'REACHED AT HUB';
                    else if($status == 9)
                        $track_msg = 'OUT FOR DELIVERY';
                    else if($status == 6)
                        $track_msg = 'DELIVERED';
                    else if($status == 7)
                        $track_msg = 'CANCELLED';
                    else if($status == 10)
                        $track_msg = 'RETURN REQUESTED';
                    $msg = $track_msg;
                    $status_date = $order_value->status_date;
                    $order_status[$order_key]->track_msg = $track_msg;
                }
                $order_details->message         = $msg;
                $order_details->order_status    = $status;
                $order_details->status_date     = $status_date;
                $json['order_details']          = $order_details;
                $json['order_status']           = $order_status;
            }
            else
            {
                $json['status']  = 0;
                $json['message'] = 'Ooops! Something went wrong.';
            }
            echo json_encode($json);
       }
    public function user_Address()
    {
        $data       = $this->data;
        $json       = array();
        $userid     = $this->input->post('user_id');
        $json['delivery_address']   = $this->Dashboard_model->get_Delivery_address($userid);
        echo json_encode($json);
    }
    public function get_bank_details()
	{
        $response = array();
        $post 	= $this->input->post();
        if(!empty($post))
        {
            $bank_details = $this->Dashboard_model->get_user_savebank_details($post['user_id']);
            if(!empty($bank_details))
            {
                $bank_array = array();
                foreach($bank_details as $details)
                {
                    $obj = new stdClass();
                    $obj->bank_detail_id = $details->bank_detail_id;
                    $obj->ifsc_code      = $details->ifsc_code;
                    $obj->first_name     = $details->first_name;
                    $obj->last_name      = $details->last_name;
                    $obj->account_number = "*****".substr($details->account_number,10);
                    $bank_array[] = $obj;
                }
            }
                $response['bank_details'] = $bank_array;
        }
        else
        {
            $response['message'] = 'Wrong Request';
        }		
        echo json_encode($response);
	}
    public function save_bank_details()
	{
		$post 	= $this->input->post();
		$userid = $post['user_id'];
		$bankdetail_array = $response = array();
		$obj 	= new stdClass();
		$obj->user_id	= $userid ;
		$obj->bank_name	= $post['bank_name'];
		$obj->city_name	= $post['city_name'];
		$obj->ifsc_code	= $post['ifsccode'];
		$obj->account_number  = $post['account_number'];
		$obj->account_type 	  = $post['account_type'];
		$bankdetail_array = $obj;
		$status		= $this->Dashboard_model->save_bank_details_model($bankdetail_array);
		if($status >= 0)
		{
			$response['status'] 	= 1;
			$response['message'] = "Successfully Added!";
		}
        echo json_encode($response);
	}
    public function delete_bank_details()
    {
        $response = array();
        $post     = $this->input->post();
        $status	  = $this->Dashboard_model->delete_bank_details($post['bank_id']);
		if($status >= 0)
		{
            $response['status'] = 1;
			$response['message'] = "Successfully Deleted!";
		}       
		echo json_encode($response);
    }
    
    public function Offers_Vouchers()
    {
        $response = array();
        $post     = $this->input->post();
        $offer_list = $this->Dashboard_model->get_user_cashback_vouchers($post['user_id']);
        if(!empty($offer_list))
        {
            $response['status'] =1;
            $response['offer_list'] = $offer_list;
        }
        else
        {
            $response['status'] = 0;
            $response['message'] = 'Sorry! you do not have any voucher! Come by later.';
        }
        echo json_encode($response);
    }
}