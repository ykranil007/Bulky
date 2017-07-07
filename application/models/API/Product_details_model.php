<?php
class Product_details_model extends CI_Model
{
	function __construct()
    {
        parent::__construct();
    }

	public function get_products_list($product_id,$product_url)
	{
	   
	    $this->db->select('product.product_id, product.seller_id, product.product_sku, product.style_code, product.group_id, product.item_name, product.sleeve_id, product.model_no, product.model_name, product.short_description As product_description, product.fabric, product.pack_of, product.cash_on_delivery, product.selling_price,product.standard_price,
	                       category.category_name, category.category_url, sub_category.sub_category_url, sub_category.sub_category_name, subtosub_category.subtosub_category_name, subtosub_category.subtosub_category_url, colors.color_id,colors.color_name,product_size.size_name,brands.brand_name,pattern.pattern_type,sleeve.sleeves_name As sleeve_name');
		$this->db->from('tblblk_product AS product');
        $this->db->join('tblblk_category AS category','category.category_id = product.category_id','LEFT');
		$this->db->join('tblblk_sub_category AS sub_category','sub_category.sub_category_id = product.sub_category_id','LEFT');
		$this->db->join('tblblk_subtosub_category AS subtosub_category','subtosub_category.subtosub_category_id = product.subtosub_category_id','LEFT');
		$this->db->join('tblblk_brands AS brands','brands.brand_id = product.brand_id','LEFT');
		$this->db->join('tblblk_product_sizes AS product_size','product_size.size_id = product.size_id','LEFT');
	    $this->db->join('tblblk_clothing_size AS size','size.size_id = product_size.size_id','LEFT');
        //$this->db->join('tblblk_clothing_size AS size','size.size_id = product.size_id','LEFT');
        $this->db->join('tblblk_clothing_patterns AS pattern','pattern.pattern_id   = product.pattern_id','LEFT');
        $this->db->join('tblblk_clothing_sleeves AS sleeve','sleeve.sleeve_id   = product.sleeve_id','LEFT');
        $this->db->join('tblblk_product_colors AS product_colors','product_colors.product_id = product.product_id','LEFT');
        $this->db->join('tblblk_colors AS colors','colors.color_id = product_colors.color_id','LEFT');
        $this->db->join('tblblk_product_keywords AS product_keywords','product_keywords.product_id = product.product_id','LEFT');	   
	    $where = array('category.status'=>1,'sub_category.status'=>1,'subtosub_category.status'=>1,'product.product_status'=>4,'product.is_active'=>'Y');
	    $this->db->where($where);
	    $this->db->where(array('product.product_id'=>$product_id,'product.product_url'=>$product_url));
	    $this->db->order_by('product.group_id');
	    $this->db->order_by("product.standard_price","ASC");
	    $query = $this->db->get();
	    //echo $this->db->last_query();exit;
		return $query->row();
	}

	public function get_product_images($product_id)
	{
		$this->db->select('image.image_name');
		$this->db->from('tblblk_product_images AS image');
		$this->db->where(array('image.product_id'=>$product_id,'image.status'=>1));
		$query = $this->db->get();
		return $query->result();
	}

	public function get_product_color($group_id,$seller_id)//Get color according to product group 
	{
		$this->db->select('product.product_id, product.seller_id, colors.color_id, colors.color_name, image.image_name');
		$this->db->from('tblblk_product AS product');
	    //$this->db->join('tblblk_colors AS colors','colors.color_id = product.color_id','LEFT');
        $this->db->join('tblblk_product_colors AS product_colors','product_colors.product_id = product.product_id','LEFT');
        $this->db->join('tblblk_colors AS colors','colors.color_id = product_colors.color_id','LEFT');
	    $this->db->join('tblblk_product_images AS image','image.product_id = product.product_id','LEFT');
	    $this->db->where(array('product.group_id'=>$group_id,'product.seller_id'=>$seller_id,'product.product_status'=>4));
	    $this->db->group_by('product_colors.color_id');
	    $query = $this->db->get();
	    //echo $this->db->last_query();exit;
	    return $query->result();
	}

	public function get_product_size($group_id,$seller_id,$color_id)
	{
		$this->db->select('product.product_id, product.seller_id,product_size.size_id,product_size.size_name,colors.color_id');
		$this->db->from('tblblk_product AS product');
		$this->db->join('tblblk_product_colors AS colors','colors.product_id = product.product_id','LEFT');
		$this->db->join('tblblk_product_sizes AS product_size','product_size.size_id = product.size_id','LEFT');
	    $this->db->join('tblblk_clothing_size AS size','size.size_id = product_size.size_id','LEFT');
	    $this->db->where(array('product.group_id'=>$group_id,'product.seller_id'=>$seller_id,'colors.color_id'=> $color_id,'product.product_status'=>4));
	    $query = $this->db->get();
	    //echo $this->db->last_query();exit;
	    return $query->result();
	}

	public function check_pincode_availability($data)//product.color_id'
	{
		$this->db->select('pincode.pincode_id, pincode.pincode, pincode.cod, pincode.city, pincode.state');
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
}
?>