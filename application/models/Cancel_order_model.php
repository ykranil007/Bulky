<?php 
class Cancel_order_model extends CI_Model
{
    var $date = null;
	public function __construct()
	{
		parent::__construct();
        $this->date = date('Y-m-d H:i:s');
	}
	public function getCancelReasonDropdown()
	{
		$this->db->select('reason_id,reason_comment');
		$this->db->from('tblblk_cancellation_reason');
		$query = $this->db->get();
       	return  $query->result_array();
	}

	public function is_product_exist($data, $app_master_id='', $userid)
	{
		$order_ids  = $item_id_arr = array();
		foreach ($data['item_id'] as $key => $value) {
        	try {
        		$pro_ids = make_decrypt($value);
        		if($pro_ids != '' && $pro_ids > 0)
        			$item_id_arr[] = $pro_ids;
        	} catch(Exception $e) {}        	
        }

        if($app_master_id == '')
            $master_id = make_decrypt($data['master_order_id']);
        else
            $master_id = $app_master_id;        
        
		$this->db->select('order.master_order_id,order.master_unique_id,item.order_id');
		$this->db->from('tblblk_master_orders order');
		$this->db->join('tblblk_order_items AS item','item.master_order_id = order.master_order_id','LEFT');
		$this->db->group_start();
            $this->db->where(array('order.master_order_id'=>$master_id,'order.user_id'=>$userid));
            $this->db->where_in('item.item_id',$item_id_arr);   
		$this->db->group_end();
        $this->db->group_start();
            $this->db->or_where('order.order_status',1);
            $this->db->or_where('order.order_status',2);
        $this->db->group_end();
        $query1 = $this->db->get();
        
        foreach ($query1->result() as $key => $value) {
        	$order_ids[] = $value->order_id;
        }

		if(!empty($query1->row()->master_order_id))
		{
			$this->db->select('count(item_id) as total_item,item_id,order_id');
			$this->db->from('tblblk_order_items');
            $this->db->where(array('master_order_id'=>$master_id,'order_item_status'=>1));                
			$query2 = $this->db->get();
            
			if($query2->row()->total_item == count($order_ids))
			{				
				$this->db->trans_start();// Starting transaction

                foreach ($order_ids as $key => $value) 
                {
                	$this->db->select('item_id')
		                			   ->from('tblblk_order_items')
		                			   ->where(array('order_id'=>$value,'order_item_status'=>1))
		                			   ->where_in('item_id',$item_id_arr);
					$query  = $this->db->get();		                			   
					$query_result = $query->result_array();
					$_item_ids = array_map(function($row){ return $row['item_id'];},$query_result);
				
                	$this->insert_cancellation_reason($userid,$_item_ids,$master_id,$value,$data['reason'],$data['comment']);

                	$insert_seller_array = array('order_id'=>$value,'order_status'=>7,'status_date'=>$this->date);
                	$this->insert_recored_in_tracking_table($insert_seller_array,'tblblk_seller_order_tracking');
                }

		        $insert_buyer_array = array('master_order_id'=>$master_id,'order_status'=>7,'status_date'=>$this->date);
                $this->insert_recored_in_tracking_table($insert_buyer_array,'tblblk_buyer_order_tracking');
                
                $this->cancel_product($item_id_arr,$master_id);
				$this->cancel_whole_order($userid,$master_id);
                $this->cancel_seller_order($userid,$order_ids);

				$this->db->trans_complete();// transaction ends here

				if($this->db->trans_status()===FALSE)
					return 0;
				else
					return 1;
			}
			else
			{	
				$this->db->trans_start();// Starting transaction
				$p_status = $this->cancel_product($item_id_arr,$master_id); // Cancelled Products
				if($p_status)
				{
					$this->db->select('item_id,order_id,count(item_id) as total_item,(
										SELECT count(item_id) 
										from tblblk_order_items order_items 
										where order_items.item_id = tblblk_order_items.item_id AND order_item_status = 2
									)as tot_cancel');
					$this->db->from('tblblk_order_items');
					$this->db->where(array('master_order_id'=>$master_id));                    			
					$this->db->group_by('order_id');
					$result = $this->db->get();

					foreach ($result->result() as $key => $value) 
					{
						if($value->total_item == $value->tot_cancel)
						{						
							$this->cancel_seller_order($userid,array($value->order_id));
							$insert_array = array('order_id'=>$value->order_id,'order_status'=>7,'status_date'=>$this->date);
	                    	$this->insert_recored_in_tracking_table($insert_array,'tblblk_seller_order_tracking');
						}
						if($value->tot_cancel > 0)
						{							
	                    	$this->insert_cancellation_reason($userid,$value->item_id,$master_id,$value->order_id,$data['reason'],$data['comment']);	
						}					
					}
				}
				
				$this->db->trans_complete();// transaction ends here

				if($this->db->trans_status()===FALSE)
					return 0;
				else
					return 1;			
			}
		}
		else
		{
			return 0;
		}
	}
	public function cancel_product($item_id,$master_order_id)
	{
		$this->db->trans_start();// Starting transaction
		$update = array('order_item_status'=>2);
		$this->db->group_start();
            $this->db->where(array('master_order_id'=>$master_order_id,'order_item_status'=>1));
            $this->db->where_in('item_id',$item_id);
        $this->db->group_end();
        $this->db->update('tblblk_order_items',$update);
		$this->db->trans_complete();// transaction ends here
		if($this->db->trans_status()===FALSE)
			return false;
		else
			return true;
	}
	public function cancel_whole_order($user_id,$master_order_id)
	{
		$this->db->trans_start();// Starting transaction
		$update = array('order_status'=>7);
		$this->db->where(array('user_id'=>$user_id,'master_order_id'=>$master_order_id));
		$this->db->update('tblblk_master_orders',$update);
		$this->db->trans_complete();// transaction ends here
		if($this->db->trans_status()===FALSE){
			return false;
		}
		else{
			return true;
		}
	}
    
    public function cancel_seller_order($user_id,$order_ids)
	{
		$update = array('order_status'=>7);
		
        $this->db->where(array('user_id'=>$user_id));
        $this->db->where_in('order_id',$order_ids);
        $this->db->where('order_status !=',7);
	
		if(!$this->db->update('tblblk_product_orders',$update)){
			return false;
		}
		else{
			return true;
		}
	}
	public function insert_cancellation_reason($userid,$item_id,$master_order_id,$order_id,$reason_id,$comment)
	{
		if(is_array($item_id)){
			foreach ($item_id as $value) {
				$insert = array('user_id'=>$userid,'item_id'=>$value,'master_order_id'=>$master_order_id,'order_id'=>$order_id,'reason_id'=>$reason_id,'cancelled_by'=>1,'cancel_comment'=>$comment,'date_added'=>$this->date);
				$insert_query = $this->db->insert_string('tblblk_orders_cancellation',$insert);
				$insert_query = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $insert_query);
				$this->db->query($insert_query);				
			}			
		}else{
			$insert = array('user_id'=>$userid,'item_id'=>$item_id,'master_order_id'=>$master_order_id,'order_id'=>$order_id,'reason_id'=>$reason_id,'cancelled_by'=>1,'cancel_comment'=>$comment,'date_added'=>$this->date);

			$insert_query = $this->db->insert_string('tblblk_orders_cancellation',$insert);
	   		$insert_query = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $insert_query);
	   		$this->db->query($insert_query);	
		}
		return true;
	}
	public function insert_recored_in_tracking_table($insert_array,$table_name)
	{		
		$insert_query = $this->db->insert_string($table_name,$insert_array);
		$insert_query = str_replace('INSERT INTO', 'INSERT IGNORE INTO', $insert_query);
		$this->db->query($insert_query);
		
	}
	public function insert_returns_order($userid,$unique_master_id,$master_order_id,$reason_id,$comment)
	{
		$insert = array('user_id'=>$userid,'master_unique_id'=>$unique_master_id,'master_order_id'=>$master_order_id,'reason_id'=>$reason_id,'return_by'=>1,'return_comment'=>$comment,'return_date'=>$this->date);		
		if($this->db->insert('tblblk_master_order_returns',$insert)){
            $this->insert_seller_returns_order($userid,$master_order_id,$reason_id,$comment);
		}			
		else{
            return false;
		}	
	}
    
    public function insert_seller_returns_order($userid,$master_order_id,$reason_id,$comment)
    {
        return true;
    }
    
	public function update_order_details($master_order_id,$userid,$product_id_arr)
	{
        $this->db->select('count("item_id") AS total_items,order_id');
        $this->db->from('tblblk_order_items');
        $this->db->where(array('master_order_id'=>$master_order_id,'order_item_status'=>1));
        $query1 = $this->db->get();
        if($query1->row()->total_items == count($product_id_arr))
        {	
            $w_status = $this->return_whole_order($master_order_id,$userid);
            $o_status = $this->return_seller_order($query1->row()->order_id,$userid);
            $p_status = $this->return_product($master_order_id,$product_id_arr);
	        $insert_buyer_array = array('master_order_id'=>$master_order_id,'master_unique_id'=>"BNMM".str_pad($master_order_id,10,0,STR_PAD_LEFT),'order_status'=>1,'status_date'=>$this->date);
            $t_status = $this->insert_recored_in_return_tracking_table($insert_buyer_array,'tblblk_buyer_return_tracking_status');            
	        $insert_seller_array = array('order_id'=>$query1->row()->order_id,'order_status'=>1,'status_date'=>$this->date);
            $t_status = $this->insert_recored_in_return_tracking_table($insert_seller_array,'tblblk_seller_return_tracking_status');
            if($w_status == true && $p_status == true && $o_status == true && $t_status == true)
				return 1;
			else
				return 0;
        }
        else
        {
            $this->db->select('count(item_id) as total_item,item_id,order_id');
			$this->db->from('tblblk_order_items');
            $this->db->where(array('order_id'=>$query1->row()->order_id,'order_item_status'=>1));                    			
			$query2 = $this->db->get();
            
            if($query2->row()->total_item == 1 && $query2->row()->total_item != 0)
            {
    	        $insert_buyer_array = array('master_order_id'=>$master_order_id,'master_unique_id'=>"BNMM".str_pad($master_order_id,10,0,STR_PAD_LEFT),'order_status'=>1,'status_date'=>$this->date);
                $t_status = $this->insert_recored_in_return_tracking_table($insert_buyer_array,'tblblk_buyer_return_tracking_status');
		        $insert_array = array('order_id'=>$query2->row()->order_id,'order_status'=>1,'status_date'=>$this->date);
                $t_status = $this->insert_recored_in_return_tracking_table($insert_array,'tblblk_seller_return_tracking_status');
				$p_status = $this->return_product($master_order_id,$product_id_arr);
				$o_status = $this->return_seller_order($query2->row()->order_id,$userid);
                if($p_status == true && $o_status == true && $t_status == true)
					return 1;
				else
					return 0;
            }
            else
            {
            	$insert_buyer_array = array('master_order_id'=>$master_order_id,'master_unique_id'=>"BNMM".str_pad($master_order_id,10,0,STR_PAD_LEFT),'order_status'=>1,'status_date'=>$this->date);
                $t_status = $this->insert_recored_in_return_tracking_table($insert_buyer_array,'tblblk_buyer_return_tracking_status');
		        $insert_array = array('order_id'=>$query2->row()->order_id,'order_status'=>1,'status_date'=>$this->date);
                $t_status = $this->insert_recored_in_return_tracking_table($insert_array,'tblblk_seller_return_tracking_status');
                $p_status = $this->return_product($master_order_id,$product_id_arr);
                if($p_status && $t_status)
                    return 1;
                else
                    return 0;
            }
        }
        
	}
    
	public function return_product($master_order_id,$product_id_arr)
	{
		$update = array('order_item_status'=>3);
        $this->db->where(array('master_order_id'=>$master_order_id,'order_item_status'=>1));
        $this->db->where_in('item_id',$product_id_arr);		
		if($this->db->update('tblblk_order_items',$update))
            return true;
		else
			return false;
	}
	public function insert_recored_in_return_tracking_table($insert_array,$table_name)
	{
		if($this->db->insert($table_name,$insert_array))
			return true;		
		else
			return false;		
	}
    
    public function return_seller_order($order_id,$user_id)
    {
		$update = array('order_status'=>10);
		$this->db->where(array('user_id'=>$user_id,'order_id'=>$order_id));
        		
		if($this->db->update('tblblk_product_orders',$update))
			return true;		
		else
			return false;
    }
    
    public function return_whole_order($master_order_id,$user_id)
    {
		$update = array('order_status'=>10);
		$this->db->where(array('master_order_id'=>$master_order_id,'user_id'=>$user_id,'order_status'=>6));
		
		if($this->db->update('tblblk_master_orders',$update))           
			return true;
		else
        	return false;
    }
    
	public function get_order_details($data,$userid)
	{
		$this->db->select('item.item_id,pro_order.unique_order_id As order_id,order.master_order_id,order.payment_type,order.exp_order_date,item.piece_per_set,item.product_id,product.set_description,item.price,item.order_item_status,order.order_status,track.order_status as track_status,track.status_date,product.item_name,product.shipping_time, order.order_date, order.master_order_id, item.quantity,image.image_name, payment.total_amount,item.size_name');
		$this->db->from('tblblk_master_orders AS order');
		$this->db->join('tblblk_product_orders AS pro_order','pro_order.master_order_id= order.master_order_id','LEFT');
        $this->db->join('tblblk_order_items AS item','item.order_id= pro_order.order_id','LEFT');        
		$this->db->join('tblblk_product AS product','product.product_id= item.product_id','LEFT');		
	    $this->db->join('tblblk_product_images AS image','image.product_id= product.product_id','LEFT');
	    $this->db->join('tblblk_orders_online_payments AS payment','payment.master_order_id = order.master_order_id','LEFT');        
	    $this->db->join('tblblk_buyer_order_tracking AS track','track.master_order_id = order.master_order_id','LEFT');
	    $this->db->group_by('item.product_id,item.size_name');
		$this->db->where(array('order.user_id' => $userid,'item.master_order_id' => make_decrypt($data['order_id']),'item.order_item_status' =>1));
		$query = $this->db->get();
		return $query->result();
	}
        
    public function get_order_info($order_id, $user_id, $product_id)
    {
        $where_array = array('product_orders.order_id' => $order_id, 'product_orders.user_id' => $user_id);
        $this->db->select("product_orders.order_id, product_orders.seller_id, product_orders.unique_order_id, product_orders.order_date, product_orders.payment_type,
						   product_orders.delivery_charge, product_orders.logistic_id, product_orders.order_delivery_type, 	
						   delivery_address.name AS Name, delivery_address.address AS Address, delivery_address.city AS City, 
						   delivery_address.state AS State, delivery_address.country as Country, delivery_address.pincode AS Pincode, 
						   delivery_address.mobile AS Mobile,
						   seller_kyc.merchant_name, seller_kyc.reg_address AS Merchant_Address, seller_kyc.merchant_pincode, 
						   tblblk_states.state_name AS merchant_state, tblblk_cities.city_name as merchant_city, 'India' as merchant_country,
						   seller_kyc.pickup_mobile, seller_kyc.pickup_address, pickup_state.state_name as pickup_state,
						   pickup_city.city_name AS pickup_city, seller_kyc.pickup_pincode, 'India' as pickup_country,
						   seller_kyc.tax_certificate_id AS seller_tin_number, seller_kyc.vat_certificate_id AS VAT_NO, 
						   seller_kyc.cts_certi_number AS CST_NO, 
						   logistics.logistic_name, logistics.logistic_short_name, logistics.surface_logistic_user_name, logistics.express_logistic_user_name, logistics.surface_token,
						   logistics.express_token, payment_type.delhivery_payment_mode, 
						   invoice.waybill, invoice.invoice_no, invoice.date_added as invoice_date 
						 ");
        $this->db->from('tblblk_product_orders product_orders');
        $this->db->join('tblblk_users_delivery_address delivery_address','product_orders.delivery_id = delivery_address.delivery_id', 'LEFT');
        $this->db->join('tblblk_seller_kyc seller_kyc','product_orders.seller_id = seller_kyc.seller_id', 'LEFT');
        $this->db->join('tblblk_states','tblblk_states.state_id = seller_kyc.merchant_state_id', 'LEFT');
        $this->db->join('tblblk_cities','tblblk_cities.city_id = seller_kyc.merchant_city_id', 'LEFT');
        $this->db->join('tblblk_states pickup_state','pickup_state.state_id = seller_kyc.pickup_state_id', 'LEFT');
        $this->db->join('tblblk_cities pickup_city','pickup_city.city_id = seller_kyc.pickup_city_id', 'LEFT');
        $this->db->join('tblblk_logistics logistics','logistics.logistic_id = product_orders.logistic_id', 'LEFT');
        $this->db->join('tblblk_orders_payment_type payment_type','payment_type.order_status_id = product_orders.payment_type', 'LEFT');
        $this->db->join('tblblk_invoice invoice','invoice.order_id = product_orders.order_id', 'LEFT');
        $this->db->where($where_array);
        $this->db->group_by('product_orders.order_id');
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        return array('order_info'=>$query->row(),'order_details'=>$this->get_order_item_by_order_id($order_id, $user_id,$product_id));
    }
    public function get_order_item_by_order_id($order_id, $user_id, $product_id)
    {
        $where_array = array('orders.user_id' =>$user_id,'order_items.order_item_status' => 1,'orders.order_id'=>$order_id);
        $this->db->select('order_items.order_id, order_items.item_id, order_items.product_id, order_items.quantity as ordered_qty,
						   order_items.price as order_item_price, order_items.vat_amt, order_items.vat_per,
						   orders.order_status, orders.order_date,
						   product.item_name,product.product_sku, product.standard_price as mrp, product.selling_price, 
						   vat_classes.vat_class_per as mainVatPer
						 ');
        $this->db->from('tblblk_order_items order_items');
        $this->db->join('tblblk_product_orders orders','orders.order_id = order_items.order_id', 'LEFT');
        $this->db->join('tblblk_product product','product.product_id = order_items.product_id', 'LEFT');
        $this->db->join('tblblk_vat_classes vat_classes','vat_classes.vat_class_id  = product.vat_class', 'LEFT');
        $this->db->where($where_array);
        if(!empty($product_id))
        {
            $this->db->where_in('order_items.product_id', $product_id);
        }
        
        $this->db->group_by('order_items.item_id');
        $query = $this->db->get();
        return $query->result();
    }
    public function get_waybill($logistic_id, $order_id)
    {
        $waybill = '';
        $this->db->trans_start(); // Transaction starts here
        $where_array = array('logistic_id' => $logistic_id, 'status' => 1);
        $this->db->select('waybill_id, logistic_id, waybill, order_id, status');
        $this->db->from('tblblk_waybills');
        $this->db->where($where_array);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            $waybill = $query->row()->waybill;
            $waybill_id = $query->row()->waybill_id;
            $update_array = array('order_id' => $order_id, 'status' => 0);
            $this->db->where('waybill_id', $waybill_id);
            $this->db->where('status', 1);
            if (!$this->db->update('tblblk_waybills', $update_array)) {
                $waybill = '';
            }
        }
        $this->db->trans_complete(); // Transaction ends here
        return $waybill;
    }
    
    public function update_return_order_status($order_id, $user_id, $order_status)
	{
		$this->db->trans_start();// Starting transaction
        
		$this->db->where(array('user_id'=>$user_id,'order_id'=>$order_id));
		$this->db->update('tblblk_product_orders',array('order_status'=>$order_status));
        
        $this->db->where(array('order_id'=>$order_id));
		$this->db->update('tblblk_order_items',array('order_item_status'=>$order_status));
        
		$date = date('Y-m-d H:i:s');        
		$this->db->insert('tblblk_order_tracking_status',array('order_id'=>$order_id,'order_status'=>$order_status,'status_date'=>$date));
		$this->db->trans_complete();// transaction ends here
		if($this->db->trans_status()===FALSE)
			return false;
		else
			return true;
	}
	public function getCancellationReasonDropdown()
	{
		$this->db->select('reason_id,reason_comment');
		$this->db->from('tblblk_cancellation_reason');
		$query = $this->db->get();
       	return  $query->result();
	}

	public function getReturnReasonDropdown()
	{
		$this->db->select('reason_id,reason_comment');
		$this->db->from('tblblk_return_reason');
		$query = $this->db->get();
       	return  $query->result();
	}
	public function check_order_exist($order_id,$userid)
	{
		$this->db->select('order.master_order_id As order_id');
		$this->db->from('tblblk_master_orders order');
		$this->db->join('tblblk_order_items item','item.master_order_id = order.master_order_id', 'LEFT');
		$this->db->where(array('order.master_order_id'=>$order_id,'order.user_id'=>$userid,'item.order_item_status'=>1));
		$query = $this->db->get();
		return $query->row();		
	}
    
    public function get_seller_user_mobile($data)
    {
    	$item_id_arr = array();
    	foreach ($data['item_id'] as $key => $value) {
        	try {
        		$pro_ids = make_decrypt($value);
        		if($pro_ids != '' && $pro_ids > 0)
        			$item_id_arr[] = $pro_ids;
        	} catch(Exception $e) {}        	
        }

        $this->db->select('product_id');
        $this->db->from('tblblk_order_items items');
        $this->db->where_in('items.item_id',$item_id_arr);
        $queryy = $this->db->get();
        
        $this->db->select('seller_id');
        $this->db->from('tblblk_product product');
        $this->db->where_in('product.product_id',$queryy->row()->product_id);
        $query = $this->db->get();
                
		if(!empty($query->row()->seller_id))
        {
            $this->db->select('mobile');
            $this->db->from('tblblk_users');
            $this->db->where(array('user_id'=>$query->row()->seller_id,'is_active'=>'Y'));
            $query = $this->db->get();
            return $query->row()->mobile;
		}
		else
        {
		  return false;
		}
			       
    }
    
    public function get_user_mobile($userid)
    {
        $this->db->select('mobile');
        $this->db->from('tblblk_users');
        $this->db->where(array('user_id'=>$userid,'is_active'=>'Y'));
        $query = $this->db->get();
        return $query->row()->mobile;
        
    }
}