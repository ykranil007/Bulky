<?php
class Dashboard_model extends CI_Model
{
	function __construct()
    {
        parent::__construct();
    }
    public function checkEmailExist($user_id,$password)
    {
    	$this->db->select('password');
    	$this->db->where(array('user_id'=>$user_id,'password'=>md5($password),'user_type'=>2));
    	$this->db->from('tblblk_users');
    	$query = $this->db->get();
    	if($query->row())
    		return $query->row();
    	else
    		return 0;
    }
    public function deactive_user_account($user_id)
    {
    	$update = array('status'=>2);
    	$this->db->where('user_id',$user_id);
    	$this->db->update('tblblk_users',$update);
    	if($this->db->trans_status() == false)
			return 0;
		else
			return 1;
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
		if($this->db->trans_status() === FALSE)			
			return false;			
		else			
			return true; // When Wallet is Redeem successfully
	}
	public function addWalletMoney($userid)
	{
		$this->db->select('voucher.voucher_pin,wallet.voucher_code,wallet.voucher_id,wallet.voucher_amount');
		$this->db->from('tblblk_user_voucher voucher');
		$this->db->join('tblblk_wallet_voucher AS wallet','wallet.voucher_id = voucher.voucher_id','LEFT');
		$this->db->where(array('voucher.user_id'=>$userid,'voucher.voucher_status'=>1));
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
	public function get_Delivery_address($userid)
	{
		$this->db->select('*');
		$this->db->from('tblblk_users_delivery_address as address');
		$this->db->where(array('address.user_id'=>$userid,'status'=>1));
		$this->db->order_by('address.dateadded','DESC');
		//$this->db->limit(3);
		$query = $this->db->get();
		return $query->result();
	}
	public function get_user_Orders($userid)
	{
		$this->db->select('items.order_id,order.master_unique_id,order.master_order_id,order.order_date,order.order_status');
		$this->db->from('tblblk_master_orders As order');
        $this->db->join('tblblk_product_orders AS pro_order','pro_order.master_order_id= order.master_order_id','LEFT');
		$this->db->join('tblblk_order_items AS items','order.master_order_id = items.master_order_id','LEFT');        
		$this->db->join('tblblk_product AS product','product.product_id = items.product_id','LEFT');
		$this->db->where(array('order.user_id' => $userid));		
		$this->db->group_by('order.master_order_id');
		$this->db->order_by('items.master_order_id','DESC');
		//$this->db->limit(10,($pageNum-1)*10);
		//$this->db->limit(5);
		$query = $this->db->get();
		return $query->result();
	}
    
    public function getReturnOrderStatus($master_order_id)
	{
		$this->db->select('order_status,status_date');
		$this->db->from('tblblk_buyer_return_tracking_status');
		$this->db->where('master_order_id',$master_order_id);
		$this->db->order_by('status_date','ASC');
		$query = $this->db->get();
		return $query->result();
	}
    
    public function get_user_Product_list($userid,$orderid)
	{
		$this->db->select('order.master_order_id,order.master_unique_id,order.payment_type,order.exp_order_date,item.vat_amt,item.price,order.order_status,product.product_url,product.item_name,order.order_date,item.quantity,item.piece_per_set,item.order_item_status,product.set_description,image.image_name,product.product_id,return.return_id as master_return_id,charge.shipping_charge,category.category_url,sub_category.sub_category_url,subtosub_category_name.subtosub_category_url');
		$this->db->from('tblblk_master_orders As order');
        $this->db->join('tblblk_product_orders AS pro_order','pro_order.master_order_id= order.master_order_id','LEFT');
		$this->db->join('tblblk_order_items AS item','item.master_order_id= order.master_order_id','LEFT');
        $this->db->join('tblblk_master_order_returns AS return','order.master_order_id = return.master_order_id','LEFT');
		$this->db->join('tblblk_product AS product','product.product_id= item.product_id','LEFT');
        $this->db->join('tblblk_category AS category','category.category_id = product.category_id','LEFT');
        $this->db->join('tblblk_sub_category AS sub_category','sub_category.sub_category_id = product.sub_category_id','LEFT');
        $this->db->join('tblblk_subtosub_category AS subtosub_category_name','subtosub_category_name.sub_category_id = product.sub_category_id','LEFT');	
        $this->db->join('tblblk_product_images AS image','image.product_id= product.product_id','LEFT');
        $this->db->join('tblblk_order_charges AS charge','charge.master_order_id= order.master_order_id','LEFT');
        $this->db->group_by('image.product_id');
		$this->db->where(array('order.user_id' => $userid,'item.master_order_id' => $orderid));
		$query = $this->db->get();
		return $query->result();
	}
    public function getOrderDetails($userid,$orderid)
	{
		$this->db->select('order.master_unique_id,order.master_order_id,order.payment_type,order.exp_order_date,item.vat_amt,item.product_id,item.price,item.piece_per_set,order.order_status,product.item_name,product.set_description,charge.shipping_charge,product.shipping_time,order.order_date,item.quantity,image.image_name,address.name,address.address,landmark,address.state,address.city,address.mobile,address.pincode');
		$this->db->from('tblblk_master_orders As order');
        $this->db->join('tblblk_product_orders AS pro_order','pro_order.master_order_id= order.master_order_id','LEFT');
		$this->db->join('tblblk_order_items AS item','item.master_order_id= order.master_order_id','LEFT');
		$this->db->join('tblblk_users_delivery_address AS address','address.delivery_id= order.delivery_id','LEFT');
		$this->db->join('tblblk_product AS product','product.product_id= item.product_id','LEFT');		
        $this->db->join('tblblk_product_images AS image','image.product_id= product.product_id','LEFT');
        $this->db->join('tblblk_order_charges AS charge','charge.master_order_id= order.master_order_id','LEFT');
        $this->db->group_by('image.product_id');
		$this->db->where(array('order.user_id' => $userid,'order.master_order_id' => $orderid));
		$query = $this->db->get();
		return $query->result();
	}
	public function getOrderStatus($order_id)
	{
		$this->db->select('order_status,status_date');
		$this->db->from('tblblk_buyer_order_tracking');
		$this->db->where('master_order_id',$order_id);
		$this->db->order_by('status_date','ASC');
		$query = $this->db->get();
		return $query->result();
	}
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
    
    public function get_user_cashback_vouchers($user_id)
    {
        $this->db->select('voucher_code,voucher_value,voucher_expiry');
        $this->db->where(array('user_id'=>$user_id,'voucher_status'=>1));
        $this->db->from('tblblk_user_offers');
        $query = $this->db->get();
        return $query->result();
    }
}