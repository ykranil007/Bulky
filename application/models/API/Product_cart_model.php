<?php 
class Product_cart_model extends CI_Model
{	
	public function __construct()
	{
		parent::__construct();
	}

	public function ProductsCartData($product_id)
	{
		$this->db->select('product.product_id,vat.vat_class_per as vat,product.seller_id,product.product_sku,category.category_url,sub_category.sub_category_url,product.product_url,product.item_name,product.selling_price,product.standard_price,product.shipping_time,product_size.size_name,color.color_name,image.image_name');
		$this->db->from('tblblk_product As product');
		$this->db->join('tblblk_product_sizes AS product_size','product_size.size_id = product.size_id','LEFT');
        $this->db->join('tblblk_category AS category','category.category_id = product.category_id','LEFT');
       	$this->db->join('tblblk_sub_category AS sub_category','sub_category.sub_category_id = product.sub_category_id','LEFT');
		$this->db->join('tblblk_vat_classes AS vat','vat.vat_class_id = product.vat_class','LEFT');
	    $this->db->join('tblblk_clothing_size AS size','size.size_id = product_size.size_id','LEFT');
	    $this->db->join('tblblk_product_colors colors','colors.product_id = product.product_id','LEFT');
	    $this->db->join('tblblk_colors color','color.color_id = colors.color_id','LEFT');
	    $this->db->join('tblblk_product_images AS image','image.product_id = product.product_id','LEFT');
		$this->db->join('tblblk_product_additional_info product_info', 'product_info.product_id = product.product_id','LEFT');
		$this->db->where('product.product_id', $product_id);
		$this->db->group_by('product.product_id');
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		return $query->row();
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
	public function remove_wishlist_data($wishlist_id)
	{
		$this->db->where('wishlist_id',$wishlist_id);
		$this->db->delete('tblblk_user_wishlist');
		if($this->db->trans_status() === FALSE)			
			return false;			
		else			
			return true;
	}
	public function Save_Cart_Data($cart,$user_id)
	{
	    $cart['user_id'] = $user_id;
        $cart['ip']  = $this->input->ip_address();
        $sql = $this->db->insert_string('tblblk_product_cart', $cart) . " ON DUPLICATE KEY UPDATE qty = '".$cart['qty']."', price = '".$cart['price']."', date_added = '".date('Y-m-d')."'";
        $this->db->query($sql);
        //echo $this->db->last_query();exit;
		return $this->db->insert_id();
	}
	//--get user cart data from database----
    public function get_cart_data($userid)
	{
        $this->db->select('cart.*,product.product_url,product.cash_on_delivery,category.category_url category,sub_category.sub_category_url sub_category,color.color_id,vat.vat_class_per as vat_amt,product_info.package_weight,product_info.package_length,product_info.package_breadth,product_info.package_height,
        (
            SELECT `product`.`quantity` - IFNULL(SUM(quantity),0) AS tot_sold
             FROM tblblk_order_items orders_item
             LEFT JOIN tblblk_product_orders orders ON orders.order_id = orders_item.order_id
             WHERE orders_item.product_id = product.product_id AND orders_item.order_item_status=1 AND orders.order_status in(2,3,4,5,6)
        ) AS product_stock
        
        ');
       	$this->db->join('tblblk_product As product','cart.product_id = product.product_id','LEFT');
       	$this->db->join('tblblk_vat_classes AS vat','vat.vat_class_id = product.vat_class','LEFT');
        $this->db->join('tblblk_category AS category','category.category_id = product.category_id','LEFT');
       	$this->db->join('tblblk_sub_category AS sub_category','sub_category.sub_category_id = product.sub_category_id','LEFT');
        $this->db->join('tblblk_product_colors colors','colors.product_id = product.product_id','LEFT');
	    $this->db->join('tblblk_colors color','color.color_id = colors.color_id','LEFT');
	    $this->db->join('tblblk_product_additional_info product_info', 'product_info.product_id = product.product_id','LEFT');
        $this->db->where('cart.user_id',$userid);
		$query = $this->db->get('tblblk_product_cart cart');		
        return $query->result_array();
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
	public function updateCartQuantity($product_id,$quantity)
	{
		$this->db->where('product_id',$product_id);	
		$this->db->update('tblblk_product_cart',array('qty' => $quantity));
		return $this->db->affected_rows();
	}

	public function get_bulk_product_details($qty,$product_id)
	{
		$this->db->select("bulk_price.price");
		$this->db->from('tblblk_bulk_price bulk_price');
		$this->db->where(array('bulk_price.from <='=>$qty,'bulk_price.to >='=>$qty,'bulk_price.product_id'=>$product_id));
		$query = $this->db->get();
		return $query->row();
	}
}