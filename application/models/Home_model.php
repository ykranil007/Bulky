<?php
class Home_model extends CI_Model
{
	function __construct()
    {
        parent::__construct();
    }

    public function get_products($category_id='',$sub_category_id='', $subtosub_category_id='', $limit='', $page='',$ip = '',$pro_type = '')
    {
        
        $array = array('product.product_status'=>4,'product.is_active'=>'Y');
        if($category_id != '')
        {
            $array['product.category_id'] = $category_id;
        }
        if($sub_category_id != '')
        {
            $array['product.sub_category_id'] = $sub_category_id;
        }
        if($subtosub_category_id != '')
        {
            $array['product.subtosub_category_id'] = $subtosub_category_id;
        }
        $this->db->select('product.product_id, category.category_id,sub_category.sub_category_id,FLOOR(((standard_price - selling_price) * 100) / standard_price) AS offer_percentage,product.set_description,product.product_url, product.seller_id, product.product_sku, product.item_name,product.short_description AS product_description,product.selling_price,product.standard_price,
                           category.category_name, category.category_url, sub_category.sub_category_url, sub_category.sub_category_name, subtosub_category.subtosub_category_name, subtosub_category.subtosub_category_url,product_image.image_name');
        $this->db->join('tblblk_category AS category', 'category.category_id = product.category_id','LEFT');
        $this->db->join('tblblk_sub_category AS sub_category', 'sub_category.sub_category_id = product.sub_category_id','LEFT');
        $this->db->join('tblblk_subtosub_category AS subtosub_category', 'subtosub_category.subtosub_category_id = product.subtosub_category_id','LEFT');       
        $this->db->join("tblblk_product_images product_image", 'product_image.product_id = product.product_id','LEFT');        
        if($ip != '')
        {
            $array['visitor.visitor_ip_address'] = $ip;
            $this->db->join("tblblk_buyer_visitor visitor",'visitor.product_id = product.product_id','LEFT');
            $this->db->order_by('visitor.modified_date','DESC');    
        }
        else if($pro_type == 'new')
        {
            $this->db->order_by('product.product_id', 'DESC');
        }else {
            $this->db->order_by('offer_percentage', 'DESC');   
        }            
        $this->db->group_by('product.product_id');
        
        if($limit != '')
        {		
           $this->db->limit($limit);
        }
        else
        {
           $this->db->limit(10,($page-1)*10);
        }
        
        $query = $this->db->get_where('tblblk_product product',$array);
        //echo $this->db->last_query();exit;
        return $query->result();
    }
	public function insert_record($table_name,$insert_array)
	{
		$this->db->insert($table_name,$insert_array);
        //echo $this->db->last_query();exit;
		return $this->db->insert_id();
	}
} 