<?php
error_reporting(0);
class Product_details_model extends CI_Model
{
	function __construct()
    {
        parent::__construct();
    }

	public function get_products_list($product_id,$product_url)
	{
	   
	    $this->db->select('product.product_id,product.product_url,product.shipping_time, product.quantity,product.set_description,info.product_feature_1,info.product_feature_2,info.product_feature_3,info.product_feature_4,info.product_feature_5,product.seller_id, product.product_sku,product.item_name,product.short_description As product_description, product.fabric, product.pack_of, product.cash_on_delivery, product.selling_price,product.standard_price,
	                       category.category_name,category.category_id,category.category_url,sub_category.sub_category_id,sub_category.sub_category_url,subtosub_category.subtosub_category_id,sub_category.sub_category_name,subtosub_category.subtosub_category_name, subtosub_category.subtosub_category_url,info.product_full_description,product.short_description,info.fabric_wash, info.search_terms ,info.product_feature_1 , info.product_feature_2,info.product_feature_3,info.product_feature_4,info.product_feature_5,product_keywords.product_keyword');
		$this->db->from('tblblk_product AS product');        
        $this->db->join('tblblk_category AS category','category.category_id = product.category_id','LEFT');
		$this->db->join('tblblk_sub_category AS sub_category','sub_category.sub_category_id = product.sub_category_id','LEFT');
		$this->db->join('tblblk_subtosub_category AS subtosub_category','subtosub_category.subtosub_category_id = product.subtosub_category_id','LEFT');
		$this->db->join('tblblk_product_additional_info AS info','info.product_id = product.product_id','LEFT');
        $this->db->join('tblblk_product_keywords AS product_keywords','product_keywords.product_id = product.product_id','LEFT');        
	    $where = array('category.status'=>1,'sub_category.status'=>1,'subtosub_category.status'=>1,'product.product_status'=>4,'product.is_active'=>'Y');
	    $this->db->where($where);
	    $this->db->where(array('product.product_id'=>$product_id,'product.product_url'=>$product_url));	    
	    $query = $this->db->get();
	    //echo $this->db->last_query();exit;
		return $query->row();
	}
    public function getSimilarProducts($subtosub_cat_id)
	{
		 $this->db->select('product.product_id,product.product_url, product.seller_id, product.product_sku, product.item_name, product.short_description As product_description, product.fabric, product.pack_of, product.cash_on_delivery, product.selling_price,product.standard_price,
	                       category.category_name, category.category_url, sub_category.sub_category_url, sub_category.sub_category_name, subtosub_category.subtosub_category_name, subtosub_category.subtosub_category_url,product_image.image_name');
		$this->db->from('tblblk_product AS product');		
        $this->db->join('tblblk_category AS category','category.category_id = product.category_id','LEFT');
		$this->db->join('tblblk_sub_category AS sub_category','sub_category.sub_category_id = product.sub_category_id','LEFT');
		$this->db->join('tblblk_subtosub_category AS subtosub_category','subtosub_category.subtosub_category_id = product.subtosub_category_id','LEFT');
	    $this->db->join("tblblk_product_images product_image",'product_image.product_id = product.product_id','LEFT');
	    $where = array('category.status'=>1,'sub_category.status'=>1,'subtosub_category.status'=>1,'product.product_status'=>4,'product.is_active'=>'Y');
	    $this->db->where($where);
	    $this->db->where('product.subtosub_category_id',$subtosub_cat_id);
	    $this->db->group_by(array('product.product_id'));
	    $this->db->order_by("product.standard_price","ASC");
        $this->db->limit(12);
	    $query = $this->db->get();
	    //echo $this->db->last_query();exit;
		return $query->result();
	}
	public function get_product_images($product_id)
	{
		$this->db->select('image.image_name,image.product_id');
		$this->db->from('tblblk_product_images AS image');
		$this->db->where(array('image.product_id'=>$product_id,'image.status'=>1));
		$query = $this->db->get();
		return $query->result();
	}

	public function get_product_stock($product_id)
	{
		$status_array = array(1,2,3,4,5,6);
		$this->db->select('product_id,sum(quantity) as tot_sold');
		$this->db->from('tblblk_product_orders order');
		$this->db->join('tblblk_order_items AS items','items.order_id = order.order_id','LEFT');
		$this->db->where(array('items.product_id'=>$product_id,'items.order_item_status'=>1));
		$this->db->where_in('order.order_status',$status_array);
  		$this->db->group_by('product_id');
		$query = $this->db->get();
		if($query->num_rows() > 0)
			return $query->row()->tot_sold;
		else
			return 0;
	}

	public function get_product_color($product_id)//Get color according to product group 
	{ 
		$this->db->select('product.product_id, product.product_url,colors.color_id, colors.color_name, image.image_name,tblblk_product_size_color.color_id');
		$this->db->from('tblblk_product AS product');
	    //$this->db->join('tblblk_colors AS colors','colors.color_id = product.color_id','LEFT');
        $this->db->join('tblblk_product_size_color','tblblk_product_size_color.product_id = product.product_id','LEFT');
        $this->db->join('tblblk_colors as colors','colors.color_id = tblblk_product_size_color.color_id','LEFT');
       // / $this->db->join('tblblk_colors AS colors','colors= color_id.color_id','LEFT');

	    $this->db->join('tblblk_product_images AS image','image.product_id = product.product_id','LEFT');
	    //$this->db->where(array('product.group_id'=>$group_id,'product.seller_id'=>$seller_id,'product.product_status'=>4,'product.is_active'=>'Y'));
	     $this->db->where(array('product.product_id'=>$product_id,'product.product_status'=>4,'product.is_active'=>'Y'));
	    $this->db->group_by('colors.color_id');
	    $query = $this->db->get();
	   //echo $this->db->last_query();exit;
	    return $query->result();
	}

	public function get_product_size($product_id)
	{
		$this->db->select('product.product_id, product.product_url,product_size.size_id,product_size.size_name,color.color_id,color.color_name');
		$this->db->from('tblblk_product AS product');
		$this->db->join('tblblk_product_size_color AS size_color','size_color.product_id = product.product_id','LEFT');
		$this->db->join('tblblk_product_sizes AS product_size','product_size.size_id = size_color.size_id','LEFT');	    
	    $this->db->join('tblblk_colors AS color','color.color_id = size_color.color_id','LEFT');    
	    $this->db->where(array('product.product_id'=>$product_id,'product.product_status'=>4,'product.is_active'=>'Y','available_in_pcs'=>1));
        //$this->db->group_by('product_size.size_id');
	    $query = $this->db->get();
	    //echo $this->db->last_query();exit;
	    return $query->result();
	}

	public function check_pincode_availability($data)//product.color_id'
	{
		$this->db->select('pincode.pincode_id, pincode.pincode, pincode.cod, pincode.city, pincode.state,pincode.value_capping');
		$this->db->from('tblblk_city_pincode AS pincode');
		$this->db->where('pincode',$data);
		$query = $this->db->get();
		return $query->row();
	}
	
	public function Product_Buyer_Visitor($ip,$product_id)
	{
        $data = array('visitor_ip_address'=>$ip,'product_id'=>$product_id);
        $sql = $this->db->insert_string('tblblk_buyer_visitor', $data) . " ON DUPLICATE KEY UPDATE modified_date = '".date('Y-m-d h:s:m')."'";//2016-11-26 05:46:46
        $this->db->query($sql);
        //echo $this->db->last_query();exit;
	}

	public function getSubtoSubCategory($product_id,$product_url)
	{
		$this->db->select('product.subtosub_category_id');	
		$this->db->from('tblblk_product AS product');
		$this->db->join('tblblk_subtosub_category AS subtosub_category','subtosub_category.subtosub_category_id = product.subtosub_category_id','LEFT');
		$where = array('subtosub_category.status'=>1,'product.product_status'=>4,'product.is_active'=>'Y');
	    $this->db->where($where);
	    $this->db->where(array('product.product_id'=>$product_id,'product.product_url'=>$product_url));
	    $query = $this->db->get();
	    //echo $this->db->last_query();exit;
		return $query->row();
	}	

	public function get_products_quantity($product_id,$product_url)
	{
		$this->db->select('product.quantity');
		$this->db->where(array('product.product_id'=>$product_id,'product.product_url'=>$product_url,'product.product_status'=>4,'product.is_active'=>'Y'));
		$this->db->from('tblblk_product AS product');
		$query = $this->db->get();
		return $query->row();
	}
	public function get_bulk_prices($product_id)
	{
		$this->db->select("bulk.price,CONCAT(bulk.from,'-',bulk.to) AS price_range");
		$this->db->from('tblblk_bulk_price bulk');
		$this->db->where('bulk.product_id',$product_id);
		$query = $this->db->get();
		return $query->result();
	}
    
    public function save_product_query($post)
    {
        $this->db->trans_start(); // Start transaction
        $this->db->insert('tblblk_product_enquiry',$post);
        $this->db->trans_complete();// transaction ends here
        if ($this->db->trans_status() == false) 
        	return 0;
        else
         	return 1;
    }
    
    public function get_active_user_info($user_id)
    {
        $query = $this->db->select('*')
                      ->from('tblblk_users')
                      ->where(array('user_id'=>$user_id,'user_type'=>2,'is_active'=>'Y','status'=>1))
                      ->get();
        return $query->row();
    }
}
?>