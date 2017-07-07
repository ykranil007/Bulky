<?php
/**
 * Checkout_model
 * 
 * @package bulk
 * @author ANIL Yadav
 * @copyright 2016
 * @version $Id$
 * @access public
 */
class Checkout_model extends CI_Model
{
	function __construct()
    {
        parent::__construct();
    }

    public function UpdateDeliveryAddress($data)
    {
    	$query = 'UPDATE tblblk_users_delivery_address 
    		SET  
    		name='.$this->db->escape($data['dname']).',
    		pincode='.$this->db->escape($data['dpincode']).',
    		address='.$this->db->escape($data['dadd']).' ,
    		landmark='.$this->db->escape($data['dlandmark']).',
    		mobile='.$this->db->escape($data['dmobile']).',
    		state='.$this->db->escape($data['dstate']).',
    		city='.$this->db->escape($data['dcity']).',
    		user_id='.$this->db->escape($data['userid']).',
			default_status='.$this->db->escape($data['dstatus']).'
			WHERE user_id = '.$this->db->escape($data['userid']).' AND delivery_id = '.$this->db->escape($data['deliveryId']).' ';
            if($this->db->query($query))
                return true;
            else
                return false;


    }
    public function insertDeliveryAddress($data,$defaultStatus)
    {
        $this->db->select('user_id');
        $this->db->where(array('user_id'=>$data['userid'],'status'=>1));
        $query1 = $this->db->get('tblblk_users_delivery_address');
        if(!empty($query1->row()->user_id))
        {
            $this->db->select('default_status');
            $this->db->where(array('user_id'=>$data['userid'],'status'=>1));
            $query2 = $this->db->get('tblblk_users_delivery_address');
            if(!empty($query2->row()->default_status) == 'Y')
            {
                $update = array('default_status'=>'N');
                $this->db->where(array('user_id'=>$data['userid'],'status'=>1));

                if($this->db->update('tblblk_users_delivery_address',$update))
                {
                    $status = $this->add_delivery_address($data,$defaultStatus);
                    if($status)
                        return 1;
                    else
                        return 0;
                }
            }
            else
            {
                $status = $this->add_delivery_address($data,$defaultStatus);
                if($status)
                    return 1;
                else
                    return 0;
            }
        }
        else{
                $status = $this->add_delivery_address($data,$defaultStatus);
                if($status)
                    return 1;
                else
                    return 0;
        }
    }

    public function add_delivery_address($data,$defaultStatus)
    {
        $insert['user_id']  = $data['userid'];
        $insert['name']     = $data['dname'];
        $insert['pincode']  = $data['dpincode'];
        $insert['address']  = $data['dadd'];
        $insert['landmark'] = $data['dlandmark'];
        $insert['mobile']   = $data['dmobile'];
        $insert['state']    = $data['dstate'];
        $insert['city']     = $data['dcity'];
        $insert['default_status'] = $defaultStatus;
        $this->db->insert('tblblk_users_delivery_address',$insert);
        $inserted_id = $this->db->insert_id();
        if($this->db->insert_id() > 0)        
            return $inserted_id;
        else
            return '';
    }
    public function destroy_cart_data($userid)
    {
        $this->db->where('user_id',$userid);
        $this->db->delete('tblblk_product_cart');
        if($this->db->trans_status() === FALSE)         
            return false;           
        else            
            return true;
    }
    
    public function getDeliveryAddress($userid)
    {
    	$this->db->select('address.*');
    	$this->db->from('tblblk_users_delivery_address As address');
    	$this->db->where(array('address.user_id'=>$userid,'address.status'=>1));
    	$query = $this->db->get();
    	return $query->result();
    }

    public function getPincodeDetails($pincode)
    {
        $this->db->select('pin.state,pin.city');
        $this->db->where('pin.pincode',$pincode);
        $this->db->from('tblblk_city_pincode As pin');
        $query = $this->db->get();
        return $query->row();
    }

    public function deleteDeliveryAddress($data)
    {   

        $userid = $data['userId'];
        $deliveryId = $data['deliveryid'];
        $update['status'] = 0;
        $this->db->where(array('user_id'=>$userid,'delivery_id'=>$deliveryId));        
        $this->db->update('tblblk_users_delivery_address',$update);       
        if($this->db->affected_rows() > 0)
            return 1;
        else
            return 0;
    }

    public function updateUserProfile($data)
    {
       
        $userid = $data['userid'];
        $name = explode(" ", $data['username']);
        $update['first_name'] = $name[0];
        if(!empty($name[1]))
            $update['last_name']  = $name[1];
        $update['mobile']     = $data['usermobile'];
        $update['gender']     = $data['usergender'];
       
        $this->db->where(array('user.user_id'=>$userid,'user.user_type'=>2));
        if($this->db->update('tblblk_users As user',$update))
            return 1;
        else
            return 0;
    }

    public function getUserData($userid)
    {
        $this->db->select('user.user_id,user.first_name,user.mobile,user.gender,user.email');
        $this->db->from('tblblk_users As user');
        $this->db->where(array('user.user_id'=>$userid,'user.user_type'=>2));
        $query = $this->db->get();
        return $query->row();
    }

    public function getPassword($data)
    {
        $oldpass  = $data['old_password'];
        $userid  = $data['user_id'];
        $this->db->select('user.password');
        $this->db->from('tblblk_users As user');
        $this->db->where(array('user.user_id'=>$userid,'user.password'=>md5($oldpass),'user.user_type'=>2));
        $query = $this->db->get();
        return $query->row();
    }

    public function changePassword($data)
    {
        $update['password'] = md5($data['new_password']);
        $userid  = $data['user_id'];
        $this->db->where(array('user_id'=>$userid,'user_type'=>2));
        if($this->db->update('tblblk_users',$update))
            return 1;
        else
            return 0;
    }

    public function add_Wishlist($value,$pid)
    {
        $this->db->select('product_id,wishlist_id');
        $this->db->where('product_id',$pid);
        $query = $this->db->get('tblblk_user_wishlist');
        
        if($query->num_rows() > 0)
        {
            $this->db->where('product_id',$pid);
            $this->db->update('tblblk_user_wishlist',$value);
            if($this->db->trans_status() === FALSE){
                    return false;
            } 
            else{
                $this->remove_cart_data($value,$pid);
                return $query->row()->product_id; // When Wishlist is Added successfully
            }
        } 
        else
        {
            $this->db->insert('tblblk_user_wishlist',$value); // inserting wishlist info
            if($this->db->trans_status() === FALSE){
                    return false;
            } 
            else{
                $this->remove_cart_data($value,$pid);
                return $this->db->insert_id(); // When Wishlist is Added successfully
            }            
        }
    }
    public function remove_cart_data($value,$pid)
    {
        $this->db->where('product_id',$pid);
        $this->db->delete('tblblk_product_cart',$value);
        if($this->db->trans_status() === FALSE)         
            return false;           
        else            
            return true;
    }
    public function user_Wish_List($userid)
    {
        $this->db->select('wish.product_id,wish.wishlist_id, category.category_url,sub_category.sub_category_url,product.set_description,product.product_url,product.item_name,product.standard_price,product.selling_price,image.image_name');
        $this->db->from('tblblk_user_wishlist As wish');
        $this->db->join('tblblk_product AS product','product.product_id = wish.product_id','LEFT');
         $this->db->join('tblblk_category AS category','category.category_id = product.category_id','LEFT');
       	$this->db->join('tblblk_sub_category AS sub_category','sub_category.sub_category_id = product.sub_category_id','LEFT');
        $this->db->join('tblblk_product_images AS image','image.product_id = product.product_id','LEFT');
        $this->db->where('wish.user_id',$userid);
        $this->db->group_by('product.product_id');
        $query = $this->db->get();
        return $query->result();
    }

    public function delete_Wish_List($id)
    {
        $this->db->where('wishlist_id', $id);
        $this->db->delete('tblblk_user_wishlist');
        if($this->db->trans_status() === false)
        {
            return 0;
        }
        else
        {
            return 1;
        }
    }

    public function OrderOTP($user_id,$otp)
	{
		$this->db->query("INSERT INTO tblblk_product_orders_otps
			                  SET
                                user_id  = 	".$user_id.",		                  	
								otpcode  = ".$this->db->escape($otp).",
								status = '1' ");
        	$inserted_id = $this->db->insert_id();
        	if($this->db->insert_id() > 0)		  
		  		return $inserted_id;
        	else
		  		return '';
	}

    public function verifyProductOrderOTP($user_id,$code)
	{
		$query = $this->db->get_where("tblblk_product_orders_otps", array("otpcode" => "$code","user_id"=>$user_id,'status'=>1));
		//echo $this->db->last_query();exit;
        if ($query->num_rows() > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
	}
    public function ResetOrdersOTP($user_id)
	{
		$this->db->trans_start(); // Start transaction
        $update = array('status'=>0);
        $this->db->where(array('user_id'=>$user_id));
        $this->db->update('tblblk_product_orders_otps',$update);
        $this->db->trans_complete();// transaction ends here
	        if ($this->db->trans_status() == false) 
	        	return 0;
	        else
	         	return 1;
	}
    public function getUserWallet($userid)
    {
        $this->db->select('user_id,
           IFNULL(SUM(CASE WHEN transaction_type="debit" THEN amount ELSE 0 END),0) As Debit,
           IFNULL(SUM(CASE WHEN transaction_type="credit" THEN amount ELSE 0 END),0) As Credit');
        $this->db->from('tblblk_user_wallet as wallet');
        $this->db->where(array('wallet.user_id'=>$userid));
        $query = $this->db->get();
        if($query->row() != '')
            return $query->row()->Credit - $query->row()->Debit;
        else
            return array('Debit'=>0,'Credit'=>0);
    }

    public function EmptyUserWallet($userid,$amount)
    {
        $insert['user_id'] = $userid;
        $insert['transaction_type'] = 'debit';
        $insert['amount'] = $amount;
        $this->db->insert('tblblk_user_wallet',$insert);
    }
    
    public function OrderProducts($shipping_charges,$logistic_id,$userid,$cart_total,$otype,$products_info,$razorpay_payment_id,$delivery_id,$product_iamge_path)
	{ 
		$seller_array = array(); // for storing seller id
		$order_id_array = array();
        $orderdate = date('Y-m-d H:i:s');
        $trackid  = "BNM".$this->common->generateRandomNo(10);
		foreach($products_info as $key=>$val)
		{
			$seller_array[] = $val->seller_id;
		}
		$seller_array = array_unique($seller_array);
		$final_array = array();
		foreach($seller_array as $seller)
		{
			$seller_prod_array = array();
			foreach($products_info as $product)
			{
				$obj = new stdclass();
				$obj->pid         = $product->pid;
				$obj->image		  = $product->image;
				$obj->seller_id   = $product->seller_id;
				$obj->pname		  = $product->pname;
				$obj->pquantity	  = $product->pquantity;
                $obj->voucher_code	  = $product->voucher_code;
				$obj->pprice	  = $product->pprice;
				if($seller == $product->seller_id){
					$seller_prod_array[] = $obj;
				} 	
			}
			if(count($seller_prod_array)>0){
				$final_array[$seller] = $seller_prod_array;
			}
		}
                
        if($otype == 1)
            $master_order_status = 1; // It will change after approval by admin
        else
            $master_order_status = 2;
            
		$this->db->trans_start();// Starting transaction
        $this->db->query("INSERT INTO tblblk_master_orders
									SET 
									user_id = ".$this->db->escape($userid).",
                                    logistic_id = ".$this->db->escape($logistic_id).",
                                    tracking_id = ".$this->db->escape($trackid).",
                                    payment_id = ".$this->db->escape($razorpay_payment_id).",
									order_date = ".$this->db->escape($orderdate).",
                                    delivery_id = ".$delivery_id.",
                                    payment_type = ".$this->db->escape($otype).",                 
									order_status = ".$this->db->escape($master_order_status)." ");
		$master_inserted_id = $this->db->insert_id();
                
        $this->update_delivery_address_status($delivery_id,$userid); // Updating Delivery Address of Buyer
        // For Buyer Tracking Table with Master Order ID
        $date = date('Y-m-d H:i:s');
        $insert_buyer_tracking_array = array('master_order_id'=>$master_inserted_id,'order_status'=>$master_order_status,'status_date'=>$date); 
        $this->insert_recored_in_tracking_table($insert_buyer_tracking_array,'tblblk_buyer_order_tracking');
		
        foreach($seller_array as $seller_id)
		{
			$this->db->query("INSERT INTO tblblk_product_orders
									SET 
									user_id = ".$this->db->escape($userid).",
                                    seller_id = ".$seller_id.",
									master_order_id = ".$master_inserted_id.",
									order_date = ".$this->db->escape($orderdate).",                                   
									order_status = ".$this->db->escape($master_order_status)." ");
			$inserted_id = $this->db->insert_id();
			$seller_products = $final_array[$seller_id]; // getting all products of a seller
            
			if($inserted_id > 0)
    	    {
    	    	$order_id_array[] = $inserted_id; // taking all order id into array
    			$product_details_array = array();
    			$total_vat = 0;
                foreach($seller_products as $products)
    			{
    			    
		            $this->db->select('pro.pack_of,pro.transfer_price,vat.vat_class_per');
                    $this->db->from('tblblk_product pro');
                    $this->db->join('tblblk_vat_classes vat','vat.vat_class_id = pro.vat_class','LEFT');
                    $this->db->where('pro.product_id',$products->pid);
                    $query = $this->db->get();
                    
    				$obj = new stdclass();
    				$obj->order_id 		  = $inserted_id;
    				$obj->product_id	  = $products->pid;
                    $obj->master_order_id = $master_inserted_id;
                    $obj->transfer_price  = $query->row()->transfer_price;
                    $obj->piece_per_set   = $query->row()->pack_of;
    				$obj->quantity		  = $products->pquantity;
    				$obj->price		      = $products->pprice;
    				$obj->vat_per         = $query->row()->vat_class_per;
                    $vat_amt              = get_tax_total($query->row()->vat_class_per,(($products->pprice * $query->row()->pack_of) * $products->pquantity));
                    $obj->vat_amt         = $vat_amt;
                    $total_vat            += $vat_amt;
                    if(strlen($products->voucher_code) == 15){
                        $this->reedeem_user_voucher($products->voucher_code);
                    }
                    $product_details_array[] = $obj;
    			}
    			
    			if(!empty($product_details_array))
    			{ 
    				$this->db->insert_batch('tblblk_order_items',$product_details_array);
    			}
    		}            
            // For Seller Tracking Table with Order ID
            $date = date('Y-m-d H:i:s');
            $insert_seller_tracking_array = array('order_id'=>$inserted_id,'order_status'=>$master_order_status,'status_date'=>$date);
            $this->insert_recored_in_tracking_table($insert_seller_tracking_array,'tblblk_seller_order_tracking');
            $this->insert_recored_in_order_charges_table($master_inserted_id,$shipping_charges,$seller_id,$cart_total,$otype,$total_vat);            
		}        
		$this->db->trans_complete();// transaction ends here
		
		if($this->db->trans_status()===FALSE){
		  return false;
		}			
		else{
		 return array('order_id_array'=>$order_id_array,'master_inserted_id'=>$master_inserted_id);
		}
	}
    
    public function reedeem_user_voucher($voucher_code)
    {
        $update = array('voucher_status'=>2,'is_applied'=>0);
        $this->db->where('voucher_code',$voucher_code);
        $this->db->update('tblblk_user_offers',$update);
    }
    
    public function update_delivery_address_status($delivery_id,$userid)
    {
        $update = array('status'=>2); // 2 for deliverd or order placed address
        $this->db->where(array('delivery_id'=>$delivery_id,'user_id'=>$userid,'status'=>1));
        if($this->db->update('tblblk_users_delivery_address',$update))
        {
            $this->db->select('*');
            $this->db->where('delivery_id',$delivery_id);
            $this->db->from('tblblk_users_delivery_address');
            $query = $this->db->get();
            if($query->row())
            {
                $insert_arr = array('user_id'=>$query->row()->user_id,'name'=>$query->row()->name,'address'=>$query->row()->address,'city'=>$query->row()->city,'state'=>$query->row()->state,'country'=>$query->row()->country,'mobile'=>$query->row()->mobile,'pincode'=>$query->row()->pincode,'dateadded'=>$query->row()->dateadded,'default_status'=>$query->row()->default_status,'status'=>1);
                $this->db->insert('tblblk_users_delivery_address',$insert_arr);
            }
        }
    }    
    
    public function insert_recored_in_tracking_table($insert_array,$table_name)
	{
		$this->db->insert($table_name,$insert_array);
	}
    
    public function insert_recored_in_order_charges_table($master_order_id,$shipping_charges,$seller_id,$total_amt,$order_type,$service_tax)
	{
		$this->db->trans_start();// Starting transaction
		$date = date('Y-m-d H:i:s');
		$insert = array('shipping_charge'=>$shipping_charges,'master_order_id'=>$master_order_id,'seller_id'=>$seller_id,'product_amount'=>$total_amt,'cod_amount'=>0,'bnm_commsion'=>0,'service_tax'=>$service_tax,'online_amount'=>0,'kkc_tax'=>0,'sb_tax'=>0,'payment_type'=>$order_type,'date_added'=>$date);
		$this->db->insert('tblblk_order_charges',$insert);
		$this->db->trans_complete();// transaction ends here
		if($this->db->trans_status()===FALSE)
			return false;
		else
			return true;
	}
    
    public function save_online_payments($payment_id,$online_amount,$masterid,$bank_name,$payment_status,$user_wallet,$cod_amount,$voucher_amount)
	{
		$total_amt = $user_wallet + $online_amount + $cod_amount;
		$insert = array('payment_type'=>$payment_id,'online_pay_amount'=>$online_amount,'master_order_id'=>$masterid,'bank_name'=>$bank_name,'payment_status'=>$payment_status,'wallet_amount'=>$user_wallet,'total_amount'=>$total_amt,'cod_amount'=>$cod_amount,'voucher_amount'=>$voucher_amount);
		$sql = $this->db->insert_string('tblblk_orders_online_payments',$insert). " ON DUPLICATE KEY UPDATE payment_type = '".$payment_id."', online_pay_amount = '".$online_amount."', master_order_id = '".$masterid."', bank_name = '".$bank_name."', payment_status = '".$payment_status."', wallet_amount = '".$user_wallet."', total_amount = '".$total_amt."', cod_amount = '".$cod_amount."', voucher_amount = '".$$voucher_amount."' ";
		$this->db->query($sql);
	}
    
    public function product_email_information($id)
	{
            $this->db->select('user.email as seller_email,user.first_name as seller_name,product.product_sku,product.item_name,product.set_description,product.product_url,product.product_id,product.category_id,product.sub_category_id,product.subtosub_category_id,item.price,
			item.quantity,item.order_id,brand.brand_name,image.image_name,order.master_order_id,order.order_date,product.shipping_time,order.delivery_id,pro_order.seller_id,address.name,address.address,
			landmark,address.state,address.city,address.mobile,address.pincode,category.category_url,sub_category.sub_category_url,subtosbu_category_name.subtosub_category_url, (item.quantity * item.piece_per_set) * item.price + item.vat_amt + charge.shipping_charge as total_amount');
            
            $this->db->from('tblblk_product AS product');
            $this->db->join('tblblk_order_items AS item','item.product_id = product.product_id','LEFT');
            $this->db->join('tblblk_brands AS brand','brand.brand_id = product.brand_id','LEFT');
            $this->db->join('tblblk_product_images AS image','image.product_id = product.product_id','LEFT');
            $this->db->join('tblblk_master_orders AS order','item.master_order_id = order.master_order_id','LEFT');
            $this->db->join('tblblk_product_orders AS pro_order','order.master_order_id = pro_order.master_order_id','LEFT');
            $this->db->join('tblblk_users_delivery_address AS address','address.delivery_id = order.delivery_id','LEFT');
            $this->db->join('tblblk_category AS category','category.category_id = product.category_id','LEFT'); 
            $this->db->join('tblblk_order_charges AS charge','charge.master_order_id = order.master_order_id','LEFT');
            $this->db->join('tblblk_sub_category AS sub_category','sub_category.sub_category_id = product.sub_category_id','LEFT');
            $this->db->join('tblblk_subtosub_category AS subtosbu_category_name','subtosbu_category_name.sub_category_id = product.sub_category_id','LEFT');                       
            $this->db->join('tblblk_users AS user','user.user_id = product.seller_id','LEFT');
            
            $this->db->where('item.order_id',$id);
            $this->db->group_by('product.product_id');
            $query = $this->db->get();
            return $query->result_array();  
	}

    public function get_autoemail_info($id)
    {
        $this->db->select('email_type,email_subject, email_from_email, email_from_name, email_description');
        $this->db->from('tblblk_autoemail');
        $this->db->where('email_id',$id);
        $query = $this->db->get();
        return   $query->row();
    }

    public function getUserInfo($user_id)
    {
        $this->db->select('email,first_name');
        $this->db->from('tblblk_users');
        $this->db->where('user_id',$user_id);
        $query = $this->db->get();
       return  $query->row();
    }
    public function getOrderStatus($masterid,$order_id)
	{
		$this->db->select('order_status');
		$this->db->from('tblblk_product_orders');
		$this->db->where(array('master_order_id'=>$masterid,'order_id'=>$order_id));
		$query = $this->db->get();
		return $query->row()->order_status;
	}
    public function getOrderDetails($masterid)
	{
		$this->db->select('item.order_id,item.product_id,item.price,item.piece_per_set,item.order_item_status,order.exp_order_date,product.set_description,product.pack_of,product.item_name,order.order_status,order.order_date,order.master_order_id,order.master_unique_id,item.quantity,image.image_name,address.name,address.address,landmark,address.state,address.city,address.mobile,address.pincode,payment.total_amount');
		$this->db->from('tblblk_master_orders As order');
        $this->db->join('tblblk_product_orders AS pro_order','pro_order.master_order_id= order.master_order_id','LEFT');
		$this->db->join('tblblk_users_delivery_address AS address','address.delivery_id= order.delivery_id','LEFT');
		$this->db->join('tblblk_order_items AS item','item.order_id= pro_order.order_id','LEFT');
		$this->db->join('tblblk_product AS product','product.product_id= item.product_id','LEFT');		        
        $this->db->join('tblblk_product_images AS image','image.product_id= product.product_id','LEFT');
        $this->db->join('tblblk_orders_online_payments AS payment','payment.master_order_id= order.master_order_id','LEFT');
        $this->db->group_by('image.product_id');
		$this->db->where(array('order.master_order_id'=>$masterid));
		$query = $this->db->get();
		return $query->result();
	}

    public function updateExpecterdDeliveryDate($exp_order_date,$masterid)
	{
		$this->db->trans_start();// Starting transaction
		$data['exp_order_date'] = date('Y-m-d',strtotime($exp_order_date));
		$this->db->where(array('master_order_id'=>$masterid));
		$this->db->update('tblblk_master_orders',$data);
		$this->db->trans_complete();// transaction ends here
		//echo $this->db->last_query();exit;
		if($this->db->trans_status()===FALSE)
			return false;
		else
			return true;
	}
}