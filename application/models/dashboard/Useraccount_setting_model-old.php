<?php
	class useraccount_setting_model extends CI_Model
	{
		public function __construct()
		{
			parent::__construct();
		}

	public function editDashboardPersonalinfo_model($firstName,$lastName,$gender,$userid)
	{
		/*$firstName = $info['myFirstName'];
		$lastName  = $info['myLastName'];
		$gender    = $info['myGender'];
		$userId    = $userid;*/

		$query = " UPDATE tblblk_users SET 
										first_name = ".$this->db->escape($firstName).",
										last_name  = ".$this->db->escape($lastName).",
										gender     = ".$this->db->escape($gender)."
										
										WHERE user_id=".$userid ." ";

				if($this->db->query($query))
				{
					return true;
				}
				else
				{
					return false;
				}
	}

	public function updateUserPassword($oldpassword,$userid)
	{
		$query = " UPDATE tblblk_users SET
										password = ".$this->db->escape(md5($oldpassword))."
										WHERE user_id=".$userid." ";
				if($this->db->query($query))
				{
					return true; 
				}
				else
				{
					return false;
				}
		
	}

	public function addUserAddress_model($userid,$addressInfo)
	{
		$query = " INSERT INTO tblblk_users_delivery_address  SET 
															 
															 user_id = ".$this->db->escape($userid).",
															 name    = ". $this->db->escape($addressInfo['userName']).",
															 address = ". $this->db->escape($addressInfo['userAddress']).",
															 pincode = ". $this->db->escape($addressInfo['userePincode']).",
															 city    = ".$this->db->escape($addressInfo['userCity']).", 
															 state   = ".$this->db->escape($addressInfo['userState']).",
															 mobile  = ".$this->db->escape($addressInfo['userPhone'])."

															 ";
					if($this->db->query($query))
					{
						return true;
					}
					else
					{
						return false;
					}
	}
	
	public function deactivateAccount_model($userid)
	{
		$query = "UPDATE tblblk_users SET 
																		is_active = 'N' WHERE user_id = $userid ";

							if($this->db->query($query))
							{
								return true;
							}
							else
							{
								return false;
							}
	}
	public function getExistingpassword($userid)
	{
		
        $this->db->select('password');
        $this->db->from('tblblk_users');
		$this->db->where('user_id',$userid);
		//$this->db->where('password',md5($password));
		$this->db->where('user_type',2);
        //$this->db->where(array('user_id'=>$userid,'password'=>md5($password),'user_type'=>2));
        $query = $this->db->get();
        return $query->row()->password;
	}

	/* -------ANIL Code-----*/

	public function UpdateEmailORMobile($value,$userid)
	{
		$update = array('email'=>$value['emailData'],'mobile'=>$value['mobileData']);
		$this->db->where(array('user_id'=>$userid,'user_type'=>2));
		$this->db->update('tblblk_users',$update);
		if($this->db->trans_status() === false)
			return 0;
		else		
			return 1;
	}

	public function makeDefaultAddress($user_id,$delivery_id)
	{
		$update_array = array('default_status'=>'N');
		$this->db->where('user_id',$user_id);
		$this->db->update('tblblk_users_delivery_address',$update_array);


		$update_array = array('default_status'=>'Y');
		$this->db->where(array('user_id'=>$user_id,'delivery_id'=>$delivery_id));
		$this->db->update('tblblk_users_delivery_address',$update_array);

		if($this->db->trans_status() === false)
			return 0;
		else		
			return 1;
		
	}

	public function deleteDeliveryAddress($user_id,$delivery_id)
	{
		$this->db->where(array('user_id'=>$user_id,'delivery_id'=>$delivery_id));
		$this->db->delete('tblblk_users_delivery_address');
		if($this->db->trans_status() === false)
			return 0;
		else		
			return 1;
	}

	public function get_Delivery_address($userid)
	{
		$this->db->select('*');
		$this->db->from('tblblk_users_delivery_address as address');
		$this->db->where(array('address.user_id'=>$userid));
		$this->db->order_by('address.dateadded','DESC');
		$this->db->limit(9);
		$query = $this->db->get();
		return $query->result();

	}

	public function getPassword($data,$userid)
    {
        $oldpass  = $data['oldPassword'];
        $this->db->select('user.password');
        $this->db->from('tblblk_users As user');
        $this->db->where(array('user.user_id'=>$userid,'user.password'=>md5($oldpass),'user.user_type'=>2));
        $query = $this->db->get();
        return $query->row();
    }
	
	public function user_Wish_List($userid)
	{
		$this->db->select('wish.product_id,wish.wishlist_id, product.item_name,product.standard_price,product.selling_price,image.image_name,colors.color_name,product_size.size_name');
		$this->db->from('tblblk_user_wishlist As wish');
		$this->db->join('tblblk_product AS product','product.product_id = wish.product_id','LEFT');
		$this->db->join('tblblk_product_images AS image','image.product_id = product.product_id','LEFT');
		$this->db->join('tblblk_product_colors AS product_colors','product_colors.product_id = product.product_id','LEFT');
		$this->db->join('tblblk_colors AS colors','colors.color_id = product_colors.color_id','LEFT');
		$this->db->join('tblblk_product_sizes AS product_size','product_size.size_id = product.size_id','LEFT');
	    $this->db->join('tblblk_clothing_size AS size','size.size_id = product_size.size_id','LEFT');
		$this->db->where('wish.user_id',$userid);
		$this->db->order_by('wish.wishlist_id','DESC');
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
	
	public function get_user_Orders($userid,$pageNum=1)
	{
		$this->db->select('product.item_name,product_size.size_name,colors.color_name,order.unique_order_id,order.order_status,order.exp_order_date,items.order_id,items.order_item_status,order.order_date,order.order_status,track.order_status as track_status,track.status_date,address.name,address.address,landmark,address.state,address.city,address.mobile,address.pincode,payment.product_amount as total_amount,cancel.date_added as cancel_date');
		$this->db->from('tblblk_product_orders As order');
		$this->db->join('tblblk_order_items AS items','order.order_id = items.order_id','LEFT');
		$this->db->join('tblblk_users_delivery_address AS address','address.delivery_id= order.delivery_id','LEFT');
		$this->db->join('tblblk_product AS product','product.product_id = items.product_id','LEFT');
		$this->db->join('tblblk_product_images AS image','image.product_id = product.product_id','LEFT');
		$this->db->join('tblblk_product_colors AS product_colors','product_colors.product_id = product.product_id','LEFT');
		$this->db->join('tblblk_colors AS colors','colors.color_id = product_colors.color_id','LEFT');
		$this->db->join('tblblk_product_sizes AS product_size','product_size.size_id = product.size_id','LEFT');
	    $this->db->join('tblblk_clothing_size AS size','size.size_id = product_size.size_id','LEFT');
	    $this->db->join('tblblk_order_charges AS payment','payment.order_id = order.order_id','LEFT');
        $this->db->join('tblblk_orders_cancellation AS cancel','cancel.order_id= order.order_id','LEFT');
        $this->db->join('tblblk_order_tracking_status AS track','track.order_id = order.unique_order_id','LEFT');
		$this->db->where(array('order.user_id' => $userid));		
		$this->db->group_by('order.order_id');
		$this->db->order_by('items.order_id','DESC');
		$this->db->limit(10,($pageNum-1)*10);
		//$this->db->limit(5);
		$query = $this->db->get();		
		return $query->result();
	}

	public function cnt_get_user_Orders($user_id)
	{
		$this->db->select('order_id');
		$this->db->where('user_id',$user_id);
		$query = $this->db->get('tblblk_product_orders');
		return $query->num_rows();
	}

	public function get_user_Product_list($userid,$orderid)
	{
		$this->db->select('order.unique_order_id As order_id,order.payment_type,order.exp_order_date,item.product_id,item.price,item.order_item_status,order.order_status,track.order_status as track_status,track.status_date,product.item_name,product.shipping_time,order.order_date,order.master_id,item.quantity,product_size.size_name,colors.color_name,image.image_name,address.name,address.address,landmark,address.state,address.city,address.mobile,address.pincode,payment.total_amount');
		$this->db->from('tblblk_product_orders As order');
		$this->db->join('tblblk_order_items AS item','item.order_id= order.order_id','LEFT');
		$this->db->join('tblblk_users_delivery_address AS address','address.delivery_id= order.delivery_id','LEFT');
		$this->db->join('tblblk_product AS product','product.product_id= item.product_id','LEFT');
		$this->db->join('tblblk_product_colors AS product_colors','product_colors.product_id = product.product_id','LEFT');
        $this->db->join('tblblk_colors AS colors','colors.color_id = product_colors.color_id','LEFT');
        $this->db->join('tblblk_product_sizes AS product_size','product_size.size_id = product.size_id','LEFT');
	    $this->db->join('tblblk_clothing_size AS size','size.size_id = product_size.size_id','LEFT');
        $this->db->join('tblblk_product_images AS image','image.product_id= product.product_id','LEFT');
        $this->db->join('tblblk_orders_online_payments AS payment','payment.master_id = order.master_id','LEFT');        
        $this->db->join('tblblk_order_tracking_status AS track','track.order_id = order.unique_order_id','LEFT');
        $this->db->group_by('image.product_id');
		$this->db->where(array('order.user_id' => $userid,'item.order_id' => $orderid));
		$query = $this->db->get();
		return $query->result();
	}

	public function getOrderDetails($userid,$orderid)
	{
		$this->db->select('order.unique_order_id,order.payment_type,order.exp_order_date,item.product_id,item.price,item.order_item_status,order.order_status,product.item_name,product.shipping_time,order.order_date,order.master_id,item.quantity,product_size.size_name,colors.color_name,image.image_name,address.name,address.address,landmark,address.state,address.city,address.mobile,address.pincode,payment.product_amount as total_amount,cancel.date_added as cancel_date');
		$this->db->from('tblblk_product_orders As order');
		$this->db->join('tblblk_order_items AS item','item.order_id= order.order_id','LEFT');
		$this->db->join('tblblk_users_delivery_address AS address','address.delivery_id= order.delivery_id','LEFT');
		$this->db->join('tblblk_product AS product','product.product_id= item.product_id','LEFT');
		$this->db->join('tblblk_product_colors AS product_colors','product_colors.product_id = product.product_id','LEFT');
        $this->db->join('tblblk_colors AS colors','colors.color_id = product_colors.color_id','LEFT');
        $this->db->join('tblblk_product_sizes AS product_size','product_size.size_id = product.size_id','LEFT');
	    $this->db->join('tblblk_clothing_size AS size','size.size_id = product_size.size_id','LEFT');
        $this->db->join('tblblk_product_images AS image','image.product_id= product.product_id','LEFT');        
        $this->db->join('tblblk_order_charges AS payment','payment.order_id = order.order_id','LEFT');
        $this->db->join('tblblk_orders_cancellation AS cancel','cancel.order_id= order.order_id','LEFT');
        $this->db->group_by('image.product_id');
		$this->db->where(array('order.user_id' => $userid,'order.unique_order_id' => $orderid));
		$query = $this->db->get();
		return $query->result();
	}

	public function authUserWallet($userid,$voucher_pin)
	{
		$this->db->select('voucher.voucher_pin,wallet.voucher_code,wallet.voucher_id,wallet.voucher_amount');
		$this->db->from('tblblk_user_voucher voucher');
		$this->db->join('tblblk_wallet_voucher AS wallet','wallet.voucher_id = voucher.voucher_id','LEFT');
		$this->db->where(array('voucher.user_id'=>$userid,'voucher.voucher_pin'=>$voucher_pin,'voucher.voucher_status'=>1));
		$query = $this->db->get();
		if(count($query->row()) > 0)
			return $query->row();
		else
			return 0;
	}
	public function insertUserVoucherRecord($userid,$voucher_id,$voucher_amount)
	{
		$insert['user_id'] = $userid;
		$insert['voucher_id'] = $voucher_id;
		$insert['amount'] = $voucher_amount;
		$this->db->insert('tblblk_user_wallet',$insert);
		$inserted_id = $this->db->insert_id();

    	if($this->db->insert_id() > 0)	  
	  		return $inserted_id;
    	else
	  		return '';
	}
	public function resetVoucherPin($userid,$voucher_id)
	{
		$update['voucher_status'] = 2;
		$this->db->where(array('voucher.user_id'=>$userid,'voucher.voucher_id'=>$voucher_id));
		$this->db->update('tblblk_user_voucher voucher',$update);
		if($this->db->trans_status() === FALSE)			
			return false;			
		else			
			return true; // When Wallet is Redeem successfully
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
	//=============for user cards===========================
	public function getUserCardsDetails($userid)
	{
		$this->db->select('*');
		$this->db->from('tblblk_users_cards');
		$this->db->where('user_id',$userid);
		$this->db->where('card_status','1');
		$query	= $this->db->get();
		return $query->result();
		
	}
	public function save_cards_details($card_details_array)
	{
		$this->db->insert('tblblk_users_cards',$card_details_array);
		$insertId = $this->db->insert_id();
		return $insertId;
	}
	public function delete_card_details($userid,$cardId)
	{
		$this->db->where('user_id',$userid);
		$this->db->where('card_id',$cardId);
		$this->db->update('tblblk_users_cards',array('card_status'=>'0'));
	}
	public function get_page_title($pageid)
	{
		$this->db->select('*');
		$this->db->from('tblblk_page');
		$this->db->where('page_id',$pageid);
		$query=$this->db->get(); //echo $this->db->last_query();exit;
		return $query->row();
	}
	//======for update email/mobile==========
	public function get_user_details_byuserid($userid)
	{
		$this->db->select('email,mobile');
		$this->db->where('user_id',$userid);
		$this->db->from('tblblk_users');
		$query = $this->db->get();
		return $query->row();
	}
	public function get_user_by_mobileno($user_mob)
	{
		$this->db->select('mobile');
		$this->db->from('tblblk_users');
		$this->db->where('mobile',$user_mob);
		$this->db->where('is_active','Y');
		$this->db->where('status',1);
		$query = $this->db->get();
		return $query->num_rows();
	}
	public function update_user_otp_formobile($userid,$arr)
	{
		$this->db->where('user_id',$userid);
		$this->db->update('tblblk_users',$arr);
		return $this->db->affected_rows();
	}
	public function get_user_otp_model($userid)
	{
		$this->db->select('otpcode');
		$this->db->from('tblblk_users');
		$this->db->where('user_id',$userid);
		$query = $this->db->get();
		return $query->row();
	}
	public function get_user_password_model($userid)
	{
		$this->db->select('password');
		$this->db->from('tblblk_users');
		$this->db->where('user_id',$userid);
		$query = $this->db->get();
		return $query->row();
	}
	public function update_user_emailmobile($userid,$post)
	{
		$this->db->where('user_id',$userid);
		$this->db->update('tblblk_users',$post);
		return $this->db->affected_rows();
	}
	//------------get for user email update-------
	public function get_email_model($post)
	{
		$this->db->select('email');
		$this->db->from('tblblk_users');
		//$this->db->where('user_id',$userid);
		$this->db->where('email',$post);
		$this->db->where('status',1);
		$this->db->where('is_active','Y');
		
		$query = $this->db->get();
		return $query->num_rows();
	}
	public function get_autoemail_details($emailid)
	{
		$this->db->select('*');
		$this->db->from('tblblk_autoemail');
		$this->db->where('email_id',$emailid);
		$query = $this->db->get();
		return $query->row();
	}
	
	//------------end for user email update-------
	//====== end update email/mob ===========
	public function getCancellationReasonDropdown()
	{
		$this->db->select('reason_id,reason_comment');
		$this->db->from('tblblk_cancellation_reason');
		$query = $this->db->get();
       	return  $query->result_array();
	}

	public function getOrderStatus($order_id)
	{
		$this->db->select('order_status,status_date');
		$this->db->from('tblblk_order_tracking_status');
		$this->db->where('order_id',$order_id);
		$this->db->order_by('status_date','ASC');
		$query = $this->db->get();
		return $query->result();
	}
    public function getReturnOrderStatus($order_id)
	{
		$this->db->select('order_status,status_date');
		$this->db->from('tblblk_order_return_tracking_status');
		$this->db->where('unique_order_id',$order_id);
		$this->db->order_by('status_date','ASC');
		$query = $this->db->get();
		return $query->result();
	}
    
    //======================for add return bank account details ============
	public function get_user_savebank_details($user_id)
	{
		$this->db->select('bank.bank_detail_id,bank.ifsc_code,bank.account_number,user.first_name,user.last_name');
        $this->db->from('tblblk_user_bank_details AS bank');
        $this->db->join('tblblk_users AS user','user.user_id = bank.user_id AND user.user_type = 2','LEFT');
        $this->db->where(array('bank.user_id'=>$user_id,'bank.status'=>1));
        $query = $this->db->get();
        return $query->result();
	}
	public function save_bank_details_model($bankdetail_array)
	{
		$this->db->insert('tblblk_user_bank_details',$bankdetail_array);
		$inserted_id = $this->db->insert_id();

    	if($this->db->insert_id() > 0)
	  		return $inserted_id;
    	else
	  		return '';
	}
    
    public function delete_bank_details($bank_id)
    {
        $update = array('status'=>0); 
        $this->db->where('bank_detail_id',$bank_id);
        $this->db->update('tblblk_user_bank_details',$update);
        if($this->db->trans_status() === FALSE)			
			return false;			
		else			
			return true; // When Account is Deleted successfully
    }
	//==============================end of return bank account =============

} 
?>