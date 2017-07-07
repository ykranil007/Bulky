<?php 
class Checkout_model extends CI_Model
{

	public function __construct()
	{
		parent::__construct();
	}

	public function auth_checkout_user($data)
	{
		$this->db->select('user_id,email,password');
		$this->db->from('tblblk_users');
		$this->db->where(array('password'=>md5($data['password']),'user_type'=>2,'is_active'=>'Y','status'=>1));
		$this->db->group_start();
			$this->db->where('email',$data['value']);		
			$this->db->or_where('mobile',$data['value']);
		$this->db->group_end();	
		$query = $this->db->get();
		return $query->row();
	}

	public function check_user_email($email)
	{
		$this->db->select('email');
		$this->db->where(array('user_type'=>2,'email'=>$email,'is_active'=>'Y'));
		$this->db->from('tblblk_users');
		$query = $this->db->get();
		return $query->row();
	}
	public function check_user_mobile($mobile)
	{
		$this->db->select('mobile');
		$this->db->where(array('user_type'=>2,'mobile'=>$mobile,'is_active'=>'Y'));
		$this->db->from('tblblk_users');
		$query = $this->db->get();
		return $query->row();
	}
	public function register_user($data)
	{	
	    $dummy_email = rand(111,999).$data['mobile'].'@dummyemail.com';
		if(!empty($data['email']) && isset($data['email'])){
			$insert = array('user_type'=>2,'email'=>$data['email'],'mobile'=>$data['mobile'],'is_bulk_user'=>'Y','terms_condition'=>1);
			$sql = $this->db->insert_string('tblblk_users', $insert) . " ON DUPLICATE KEY UPDATE email = '".$data['email']."', mobile = '".$data['mobile']."', dateadded = '".date('Y-m-d h:i:s')."'";
		}
		else{
			$insert = array('user_type'=>2,'email'=>$dummy_email,'mobile'=>$data['mobile'],'terms_condition'=>1,'is_bulk_user'=>'N');
			$sql = $this->db->insert_string('tblblk_users', $insert) . " ON DUPLICATE KEY UPDATE email = '".$dummy_email."', mobile = '".$data['mobile']."', dateadded = '".date('Y-m-d h:i:s')."'";
		}
		$this->db->query($sql);
		$inserted_id = $this->db->insert_id();
    	if($this->db->insert_id() > 0)		  
	  		return $inserted_id;
    	else
	  		return '';
	}

	public function update_checkout_user_otp($data,$userid)
	{
		$update = array('otpcode'=>$data);
		$this->db->where(array('user_id'=>$userid,'user_type'=>2));
		$this->db->update('tblblk_users',$update);
		if ($this->db->trans_status() == false) 
        	return 0;
        else
         	return 1;
	}

	public function save_online_payments($payment_id,$online_amount,$masterid,$bank_name,$payment_status,$user_wallet,$cod_amount,$voucher_amount)
	{
		$total_amt = $user_wallet + $online_amount + $cod_amount;
		$insert = array('payment_type'=>$payment_id,'online_pay_amount'=>$online_amount,'master_order_id'=>$masterid,'bank_name'=>$bank_name,'payment_status'=>$payment_status,'wallet_amount'=>$user_wallet,'total_amount'=>$total_amt,'cod_amount'=>$cod_amount,'voucher_amount'=>$voucher_amount);
		$sql = $this->db->insert_string('tblblk_orders_online_payments',$insert). " ON DUPLICATE KEY UPDATE payment_type = '".$payment_id."', online_pay_amount = '".$online_amount."', master_order_id = '".$masterid."', bank_name = '".$bank_name."', payment_status = '".$payment_status."', wallet_amount = '".$user_wallet."', total_amount = '".$total_amt."', cod_amount = '".$cod_amount."', voucher_amount = '".$voucher_amount."' ";
		$this->db->query($sql);
	}

	public function verify_checkout_user($otp,$userid)
	{
		$this->db->select('otpcode');
		$this->db->where(array('user_type'=>2,'user_id'=>$userid,'otpcode'=>$otp['otp']));
		$this->db->from('tblblk_users');
		$query = $this->db->get();
		return $query->row();
	}

	public function reset_checkout_user_otp($otp,$userid)
	{
		$update = array('otpcode'=>'');
		$this->db->where(array('user_type'=>2,'user_id'=>$userid,'otpcode'=>$otp['otp']));
		$this->db->update('tblblk_users',$update);
		if ($this->db->trans_status() == false) 
        	return 0;
        else
         	return 1;
	}

	public function update_checkout_user_password($password,$userid)
	{
		$update = array('password'=>md5($password));
		$this->db->where(array('user_type'=>2,'user_id'=>$userid));
		$this->db->update('tblblk_users',$update);
		if ($this->db->trans_status() == false) 
        	return 0;
        else
         	return 1;
	}	

	public function active_checkout_user($userid)
	{
		$update = array('is_active'=>'Y','status'=>1);
		$this->db->where(array('user_type'=>2,'user_id'=>$userid));
		$this->db->update('tblblk_users',$update);
		if ($this->db->trans_status() == false) 
        	return 0;
        else
         	return 1;
	}

	public function DeliveryAddress($userid)
	{
		$query = $this->db->query("SELECT * FROM tblblk_users_delivery_address 
			                         WHERE 
			                         user_id = ".$userid." AND status = 1 ");
		return $query->result();
	}
	public function EditDeliveryAddress($deliveryid,$user_id)
	{
		$this->db->select('*');
        $this->db->where(array('delivery_id'=>$deliveryid,'user_id'=>$user_id));
        $this->db->from('tblblk_users_delivery_address');
        $query = $this->db->get();
        return $query->row();
	}
	public function UpdateDeliveryAddress($data,$deliveryid,$default)
	{
		$query = $this->db->query("UPDATE tblblk_users_delivery_address
									SET 
									name = ".$this->db->escape($data['name']).",
									pincode = ".$this->db->escape($data['pincode']).",
									address = ".$this->db->escape($data['address']).",
									city = ".$this->db->escape($data['city']).",
									state = ".$this->db->escape($data['state']).",
									mobile = ".$this->db->escape($data['mobile']).",
									landmark = ".$this->db->escape($data['landmark']).",
									default_status = ".$this->db->escape($default)."
			                        WHERE 
			                        delivery_id = ".$deliveryid." ");
		if($this->db->affected_rows()>=0)
		{
			return true;
	    }
		else
		{
			return false;
	    }
	}
	public function AddNewDeliveryAddress($data,$userid,$default)
	{
		$this->db->query("INSERT INTO tblblk_users_delivery_address
									SET 
									user_id = ".$this->db->escape($userid).",
									name = ".$this->db->escape($data['name']).",
									pincode = ".$this->db->escape($data['pincode']).",
									address = ".$this->db->escape($data['address']).",
									city = ".$this->db->escape($data['city']).",
									state = ".$this->db->escape($data['state']).",
									mobile = ".$this->db->escape($data['mobile']).",
									landmark = ".$this->db->escape($data['landmark']).",
									default_status = ".$this->db->escape($default)." ");
		$inserted_id = $this->db->insert_id();
    	if($this->db->insert_id() > 0)		  
	  		return $inserted_id;
    	else
	  		return '';
	}
	public function GetDefaultDeliveryAddress($userid)
	{
		$query = $this->db->query("SELECT default_status FROM tblblk_users_delivery_address WHERE default_status = 'Y' AND user_id = ".$userid." AND status = 1");
		if($query->num_rows() > 1)
        {
            return 1;
        }
        else
        {
            return 0;
        }
	}
	public function ManageDefaultDeliveryAddress($userid)
	{
		$this->db->query("UPDATE tblblk_users_delivery_address
			               SET 
			                    default_status = 'N'
			                    WHERE default_status = 'Y' AND user_id = ".$userid." ");
	}
	public function DeleteDeliveryAddress($deliveryid,$user_id)
	{
		$update['status'] = 0;
		$this->db->where(array('delivery_id'=>$deliveryid,'user_id'=>$user_id));
		$this->db->update('tblblk_users_delivery_address',$update);		
	}
	public function GetDeliveryAddress($userid,$delivery_id)
	{
		$query = $this->db->query("SELECT * FROM tblblk_users_delivery_address 
			                        WHERE user_id = ".$this->db->escape($userid)." AND delivery_id = $delivery_id AND status = 1");
		return $query->row();
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
	function sortByOrder($a, $b) 
	{
     return $a['seller_id'] - $b['seller_id'];
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
                $obj->size_name   = $product->size_name;
				$obj->pprice	  = $product->pprice;
				if($seller == $product->seller_id){
					$seller_prod_array[] = $obj;
				} 	
			}
			if(count($seller_prod_array)>0){
				$final_array[$seller] = $seller_prod_array;
			}
		}
        
        $master_order_status = 0;
        if($otype == 1){
            $master_order_status = 1; // It will change after approval by admin
        }            
        else{
            $master_order_status = 2;
        }            
            
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
    				$obj->size_name 	  = $products->size_name;
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
		 return array('order_id_array'=>$order_id_array,'master_inserted_id'=>$master_inserted_id,'order_date'=>$orderdate);
		}
	}
    
    public function reedeem_user_voucher($voucher_code)
    {
		$this->db->select('offer_voucher_id')
		 	  	 ->from('tblblk_offer_vouchers_code')
		 	  	 ->where(array('voucher_code'=>$voucher_code,'voucher_status'=>1));
	    $query = $this->db->get();

        $update = array('voucher_status'=>2);
        $this->db->where('voucher_code',$voucher_code);
        $this->db->update('tblblk_offer_vouchers_code',$update);

        $update = array('is_applied'=>0);
        $this->db->where('offer_voucher_id',$query->row()->offer_voucher_id);
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
	public function getOrder($masterid)
	{
		$this->db->select('item.order_id,item.product_id,item.price,product.item_name,product.set_description,product.pack_of,product.shipping_time,order.exp_order_date,order.order_date,order.master_order_id,item.quantity,image.image_name,address.name,address.address,landmark,address.state,address.city,address.mobile,address.pincode');
		$this->db->from('tblblk_master_orders As order');
        $this->db->join('tblblk_product_orders AS pro_order','pro_order.master_order_id= order.master_order_id','LEFT');
		$this->db->join('tblblk_users_delivery_address AS address','address.delivery_id= order.delivery_id','LEFT');
		$this->db->join('tblblk_order_items AS item','item.order_id= pro_order.order_id','LEFT');
		$this->db->join('tblblk_product AS product','product.product_id= item.product_id','LEFT');		
        $this->db->join('tblblk_product_images AS image','image.product_id= product.product_id','LEFT');
        $this->db->group_by('item.order_id');
		$this->db->where(array('master_order_id'=>$masterid));
		$query = $this->db->get();
		return $query->result();
	}

	public function getOrderDetails($order_date,$user_id)
	{
		$this->db->select('order.master_unique_id,item.order_id,item.product_id,item.price,item.order_item_status,order.exp_order_date,product.set_description,product.pack_of,product.item_name,order.order_status,order.order_date,order.master_order_id,item.quantity,image.image_name,address.name,address.address,landmark,address.state,address.city,address.mobile,address.pincode,payment.total_amount');
		$this->db->from('tblblk_master_orders As order');
        $this->db->join('tblblk_product_orders AS pro_order','pro_order.master_order_id= order.master_order_id','LEFT');
		$this->db->join('tblblk_users_delivery_address AS address','address.delivery_id= order.delivery_id','LEFT');
		$this->db->join('tblblk_order_items AS item','item.order_id= pro_order.order_id','LEFT');
		$this->db->join('tblblk_product AS product','product.product_id= item.product_id','LEFT');		        
        $this->db->join('tblblk_product_images AS image','image.product_id= product.product_id','LEFT');
        $this->db->join('tblblk_orders_online_payments AS payment','payment.master_order_id= order.master_order_id','LEFT');
        $this->db->group_by('image.product_id');
		$this->db->where(array('order.order_date'=>$order_date,'order.user_id'=>$user_id));
        $this->db->order_by('order.order_date','DESC');
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

	public function getPincodeDetails($pincode)
	{
		$this->db->select('pin.state,pin.city');
		$this->db->where('pin.pincode',$pincode);
		$this->db->from('tblblk_city_pincode As pin');
		$query = $this->db->get();
		return $query->row();
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

	public function remove_cart_data($userid)
	{
		$this->db->where('user_id',$userid);
		$this->db->delete('tblblk_product_cart');
		if($this->db->trans_status() === FALSE)			
			return false;			
		else			
			return true;
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
            $this->db->join('tblblk_order_charges AS charge','charge.master_order_id = order.master_order_id','LEFT');
            $this->db->join('tblblk_users_delivery_address AS address','address.delivery_id = order.delivery_id','LEFT');
            $this->db->join('tblblk_category AS category','category.category_id = product.category_id','LEFT');
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
    //==========cash if online payment dismiss============
    public function online_banking_faliure($data)
	{
		$this->db->insert('tblblk_banking_faliure_log',$data);
	}
	public function getCancellationReasonDropdown()
	{
		$this->db->select('reason_id,reason_comment');
		$this->db->from('tblblk_cancellation_reason');
		$query = $this->db->get();
       	return  $query->result_array();
	}
	public function getOrderStatus($masterid,$order_id)
	{
		$this->db->select('order_status');
		$this->db->from('tblblk_product_orders');
		$this->db->where(array('master_order_id'=>$masterid,'order_id'=>$order_id));
		$query = $this->db->get();
		return $query->row()->order_status;
	}
    
    public function updateSellerDispatchDate($dispatch_date,$masterid)
    {
        $this->db->trans_start();// Starting transaction
		$data['seller_dispatch_date'] = date('Y-m-d',strtotime($dispatch_date));
		$this->db->where(array('master_order_id'=>$masterid));
		$this->db->update('tblblk_product_orders',$data);
		$this->db->trans_complete();// transaction ends here
		//echo $this->db->last_query();exit;
		if($this->db->trans_status()===FALSE)
			return false;
		else
			return true;
    }
    
    public function get_vat_percentage($product_id)
    {
        $this->db->select('vat.vat_class_per as vat');
        $this->db->from('tblblk_product As product');
        $this->db->join('tblblk_vat_classes AS vat','vat.vat_class_id = product.vat_class','LEFT');
        $this->db->where('product.product_id', $product_id); 
		$query = $this->db->get();
        return $query->row()->vat;
    }
    
    public function getUserCancelRecords($user_id)
    {
        $cur_date = date('Y-m-d');
        $prev_date = date('Y-m-d', strtotime("-3 days"));
        $this->db->select('COUNT(order_status) as cancel_count'); 
        $this->db->where(array('user_id'=>$user_id,'order_status'=>7));
        $this->db->where('DATE(order_date) >=', $prev_date);
        $this->db->where('DATE(order_date) <=', $cur_date);
        $this->db->from('tblblk_master_orders');
        $query = $this->db->get();
        //echo $this->db->last_query();
        return $query->row()->cancel_count;        
    }
    
    public function get_user_delivery_mobile($delivery_id)
    {
        $this->db->select('mobile');
        $this->db->where(array('delivery_id'=>$delivery_id));
        $this->db->from('tblblk_users_delivery_address');
        $query = $this->db->get();
        return $query->row()->mobile;
    }
}