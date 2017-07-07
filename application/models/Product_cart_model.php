<?php 
class Product_cart_model extends CI_Model
{	
	public function __construct()
	{
		parent::__construct();
	}
	public function ProductsCartData($product_id)
	{
		$this->db->select('product.product_id,product.pack_of,product.product_url,vat.vat_class_per as vat,product.seller_id,product.product_sku,product.item_name,product.selling_price,product.standard_price,product.shipping_time,product_size.size_name,color.color_name,color.color_id,image.image_name,category.category_url,sub_category.sub_category_url,subtosub_category.subtosub_category_url,product_size.size_name');
		$this->db->from('tblblk_product As product');
		$this->db->join('tblblk_category AS category','category.category_id = product.category_id','LEFT');
		$this->db->join('tblblk_sub_category AS sub_category','sub_category.sub_category_id = product.sub_category_id','LEFT');
		$this->db->join('tblblk_subtosub_category AS subtosub_category','subtosub_category.subtosub_category_id = product.subtosub_category_id','LEFT');
		//$this->db->join('tblblk_product_sizes AS product_size','product_size.size_id = product.size_id','LEFT');
		$this->db->join('tblblk_vat_classes AS vat','vat.vat_class_id = product.vat_class','LEFT');
	    //$this->db->join('tblblk_clothing_size AS size','size.size_id = product_size.size_id','LEFT');
	    $this->db->join('tblblk_product_size_color','tblblk_product_size_color.product_id = product.product_id','LEFT');
		$this->db->join('tblblk_product_sizes AS product_size','product_size.size_id = tblblk_product_size_color.size_id','LEFT');

		$this->db->join('tblblk_colors AS color','color.color_id = tblblk_product_size_color.color_id','LEFT');

	    //$this->db->join('tblblk_product_colors colors','colors.product_id = product.product_id','LEFT');
	    
	    $this->db->join('tblblk_product_images AS image','image.product_id = product.product_id','LEFT');
		$this->db->join('tblblk_product_additional_info product_info', 'product_info.product_id = product.product_id','LEFT');
		$this->db->where(array('product.product_id'=> $product_id,'product.product_status'=>4,'product.is_active'=>'Y')); 
        
		$this->db->group_by('product.product_id', $product_id); 
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		return $query->row();
	}
	public function add_Wishlist($value,$pid)
	{
		
		$sql = $this->db->insert_string('tblblk_user_wishlist',$value)."ON DUPLICATE KEY UPDATE user_id = '".$value['user_id']."', product_id = '".$value['product_id']."', size_id = '".$value['size_id']."'";

		$this->db->query($sql);
		$this->remove_cart_data($value,$pid);
		return true;
		
	}
	public function remove_cart_data($value,$pid)
	{

		//$this->db->where(array('product_id'=>$pid,'size_name'=>$size_name));
		$this->db->delete('tblblk_product_cart',$value);
		if($this->db->trans_status() === FALSE)			
			return false;			
		else			
			return true;
	}
    
    public function remove_app_wishlist_data($wishlist_id)
	{
		$this->db->where(array('wishlist_id'=>$wishlist_id));
		$this->db->delete('tblblk_user_wishlist');
		if($this->db->trans_status() === FALSE)			
			return false;			
		else			
			return true;
	}
    
	public function remove_wishlist_data($user_id,$pid)
	{
		$this->db->where(array('product_id'=>$pid,'user_id'=>$user_id));
		$this->db->delete('tblblk_user_wishlist');
		if($this->db->trans_status() === FALSE)			
			return false;			
		else			
			return true;
	}
	public function Save_Cart_Data($cart)
	{  
	    $cart['product_id'] = $cart['id']; 
	    unset($cart['id']);
        $cart['ip']  = $this->input->ip_address();
        $sql = $this->db->insert_string('tblblk_product_cart', $cart) . " ON DUPLICATE KEY UPDATE qty = '".$cart['qty']."', date_added = '".date('Y-m-d')."'";
        $this->db->query($sql);
        $this->check_user_voucher($cart['user_id']);
		return $this->db->insert_id();
	}
    
    public function check_user_voucher($user_id)
    {
        $this->db->select('voucher_code');
        $this->db->where('user_id',$user_id);
        $this->db->from('tblblk_product_cart');
        $query = $this->db->get();
        if($query->result_array())
        {
            //echo count($query->result_array());exit;
            foreach($query->result_array() as $arr)
            {
                if(trim($arr['voucher_code']) == '')
                {
                    $this->update_user_voucher($user_id);
                }
                else
                {
                    $this->apply_user_voucher($user_id,$query->row()->voucher_code);
                }
            }            
        }
    }
    
    public function apply_user_voucher($user_id,$voucher_code)
    {
        $this->db->select('offer_voucher_id');
	    $this->db->from('tblblk_offer_vouchers_code');
	    $this->db->where('voucher_code',$voucher_code);
	    $query = $this->db->get();
	    if(!empty($query->row()->offer_voucher_id))
	    {
		    $update = array('is_applied'=>1);
		    $this->db->where(array('user_id'=>$user_id,'offer_voucher_id'=>$query->row()->offer_voucher_id));
		    if($this->db->update('tblblk_user_offers',$update))
		    {
		    	return 1;
		    } else {
		    	return 0;
		    }
	    }
    }
    
    public function update_user_voucher($user_id)
    {
        $this->db->select('code.voucher_code');
	    $this->db->from('tblblk_offer_vouchers_code as code');
	    $this->db->join('tblblk_user_offers offer','offer.offer_voucher_id = code.offer_voucher_id','LEFT');
	    $this->db->where(array('offer.user_id'=>$user_id,'offer.is_applied'=>1));
        $query = $this->db->get();
        if(!empty($query->row()->voucher_code))
        {
            $this->again_apply_user_voucher($user_id,$query->row()->voucher_code);
        }
    }
    
    public function again_apply_user_voucher($user_id,$voucher_code)
    {
        $update = array('voucher_code'=>$voucher_code);
        $this->db->where(array('user_id'=>$user_id));
        $this->db->update('tblblk_product_cart',$update);
    }
    
	public function get_Cart_Data($userid)
	{
		$this->db->select('cart.product_id,cart.name');
		$this->db->from('tblblk_product_cart As cart');
		$this->db->join('tblblk_product product','product.product_id = cart.product_id','LEFT');
		$this->db->where('user_id',$userid);
		$query = $this->db->get();
		return $query->result();
	}
	public function getProductStockQuantity($product_id)
	{
		// getting product total quantity
		$this->db->select('quantity');
		$this->db->from('tblblk_product');
		$this->db->where(array('product_id' =>$product_id));
		$query = $this->db->get();
		return  $query->row()->quantity;
	}
	public function updateCartQuantity($product_id,$quantity,$user_id,$size_id)
	{
		$this->db->where(array('product_id'=>$product_id,'user_id'=>$user_id,'size_id'=>$size_id));	
		$this->db->update('tblblk_product_cart',array('qty' => $quantity));
		return $this->db->affected_rows();
	}
	public function getProductSoldQty($product_id)
	{
		$status_array = array(2,3,4,5,6);
		$this->db->select('product_id, sum(quantity) as tot_sold');
		$this->db->from('tblblk_order_items orders_item');
		$this->db->join('tblblk_product_orders orders','orders.order_id = orders_item.order_id','LEFT');
		$this->db->where_in('orders.order_status',$status_array);
		$this->db->where('orders_item.order_item_status',1);
		$this->db->where('orders_item.product_id',$product_id);
		$this->db->group_by('product_id');
		$query = $this->db->get();
		return $query->row();
	}
	public function get_bulk_product_details($qty,$product_id)
	{
		$this->db->select("bulk_price.price");
		$this->db->from('tblblk_bulk_price bulk_price');
		$this->db->where(array('bulk_price.from <='=>$qty,'bulk_price.to >='=>$qty,'bulk_price.product_id'=>$product_id));
		$query = $this->db->get();
		return $query->row();
	}

	public function getProductInfo($product_id)
	{
		$this->db->select('product.product_id,product.pack_of,product.product_url,vat.vat_class_per,product.set_description,product.seller_id,product.product_sku,product.item_name,product.selling_price,product.standard_price,product_size.size_name,product_size.size_id,
		product.shipping_time,image.image_name,category.category_url,sub_category.sub_category_url,subtosub_category.subtosub_category_url,Fn_Product_Quantity(product.product_id) AS product_stock,SUM(product_info.package_length*product_info.package_height*product_info.package_breadth) as pro_weight,product.product_status');
		$this->db->from('tblblk_product AS product');
		$this->db->join('tblblk_category AS category','category.category_id = product.category_id','LEFT');
		$this->db->join('tblblk_sub_category AS sub_category','sub_category.sub_category_id = product.sub_category_id','LEFT');
		$this->db->join('tblblk_subtosub_category AS subtosub_category','subtosub_category.subtosub_category_id = product.subtosub_category_id','LEFT');
		$this->db->join('tblblk_vat_classes AS vat','vat.vat_class_id = product.vat_class','LEFT');
		$this->db->join('tblblk_product_size_color','tblblk_product_size_color.product_id = product.product_id','LEFT');
		$this->db->join('tblblk_product_sizes AS product_size','product_size.size_id = tblblk_product_size_color.size_id','LEFT');
	    $this->db->join('tblblk_product_images AS image','image.product_id = product.product_id','LEFT');
		$this->db->join('tblblk_product_additional_info product_info', 'product_info.product_id = product.product_id','LEFT');
		$this->db->where(array('product.product_id'=>$product_id)); 
		$this->db->group_by('product.product_id', $product_id); 
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		return $query->row();
	}
    
	public function getProductSize($product_id,$size_id)
	{
		$this->db->select('product.product_id,product_size.size_name,product_size.size_id');
		$this->db->from('tblblk_product AS product');		
		$this->db->join('tblblk_product_size_color','tblblk_product_size_color.product_id = product.product_id','LEFT');
		$this->db->join('tblblk_product_sizes AS product_size','product_size.size_id = tblblk_product_size_color.size_id','LEFT');	    
		$this->db->where(array('product.product_id'=>$product_id,'product_size.size_id'=>$size_id)); 
		$this->db->group_by('product.product_id', $product_id); 
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		return $query->row();
	}

    public function get_reedeem_voucher_amount($voucher_code,$cart_base_amt)
    {
    	
        $this->db->select('code.offer_type_id,offer.voucher_value,offer.user_id');
	    $this->db->from('tblblk_offer_vouchers_code as code');
	    $this->db->join('tblblk_user_offers AS offer','offer.offer_voucher_id = code.offer_voucher_id','LEFT');
        $this->db->where(array('code.voucher_code'=>$voucher_code,'code.voucher_status'=>1));
        $query = $this->db->get();
        
        if($query->num_rows() > 0)
        {
            if(!empty($query->row()->user_id) && $query->row()->offer_type_id == 1) // For Cashback Voucher Redemption
            {
            	if($cart_base_amt >= 1000)
	            {
		            return $query->row()->voucher_value;
	            }
	            else
	            {
		            return ($query->row()->voucher_value / 2);
	            }

            }
            else if($query->row()->offer_type_id == 3) // For Percentage Coupon Redemption
            {
	            return (($cart_base_amt * $query->row()->voucher_value) / 100);
            }
            else
            {
                return 0;
            }                        
        }
        else
		{
			return 0;
		}       
    }
    
    public function validate_voucher_code($voucher_code,$user_id)
    {
	    $this->db->select('voucher_code,offer_type_id');
	    $this->db->from('tblblk_offer_vouchers_code');
	    $this->db->where(array('voucher_code' => $voucher_code, 'voucher_status' => 1));
	    $query = $this->db->get();
	    if(!empty($query->row()->voucher_code) && $query->row()->offer_type_id == 1)
	    {
	    	$where = array('offer.user_id' => $user_id, 'code.voucher_code' => $voucher_code, 'code.voucher_status' => 1);
	    }
	    elseif(!empty($query->row()->voucher_code) && $query->row()->offer_type_id == 3)
	    {
		    $where = array('code.voucher_code' => $voucher_code, 'code.voucher_status' => 1);
	    }
	    else{
		    return 'nothing';
	    }

	    $this->db->select('code.voucher_code,code.offer_type_id');
	    $this->db->from('tblblk_offer_vouchers_code as code');
	    $this->db->join('tblblk_user_offers AS offer', 'offer.offer_voucher_id = code.offer_voucher_id', 'LEFT');
	    $this->db->where($where);
	    $query = $this->db->get();
	    if(!empty($query->row()->voucher_code)){
		    if($this->reedeem_voucher($voucher_code,$user_id) > 0){
			    return 1;
		    }
		    else {
			    return 0;
		    }
	    } else {
		    return 'nothing';
	    }
    }
        
    public function reedeem_voucher($voucher_code,$user_id)
    {
        $update = array('voucher_code'=>$voucher_code);
        $this->db->where(array('user_id'=>$user_id));
        if($this->db->update('tblblk_product_cart',$update))
        {
            return 1;
        }
        else
        {
            return 0;
        }        
    }
}