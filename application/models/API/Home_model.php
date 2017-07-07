<?php
class Home_model extends CI_Model
{
	function __construct()
    {
        parent::__construct();
    }

    public function save_device_id($data)
    {
        $data = array('user_type'=>2,'device_id'=>$data['device_id'],'created_date'=>date('Y-m-d H:i:s'));
        $this->db->select('device_id');
        $this->db->where('device_id',$data['device_id']);
        $this->db->from('tblblk_app_device_ids');
        $query = $this->db->get();
        if(count($query->row()) > 0){
            $this->db->where('device_id',$data['device_id']);
            $this->db->update('tblblk_app_device_ids',$data);
        }
        else{
            $this->db->where('device_id',$data['device_id']);
            $this->db->insert('tblblk_app_device_ids',$data);
        }
    }
    
    public function get_products($category_id, $limit,$ip = '',$pro_type = '')
    {
        $array = array('product.product_status'=>4,'product.is_active'=>'Y');
        if( $category_id != '' )
        {
            $array['product.category_id'] = $category_id;
        }
        $this->db->select("product.product_id, category.category_id, FLOOR(((standard_price - selling_price) * 100) / standard_price) AS offer_percentage,product.set_description,product.product_url, 
                           product.seller_id, product.product_sku, product.item_name, colors.color_id, product.short_description AS product_description,
                           REPLACE(TRUNCATE(product.standard_price,0),', ','') AS standard_price, REPLACE(TRUNCATE(product.selling_price,0),', ','') AS selling_price,
                           category.category_name, category.category_url, sub_category.sub_category_url, sub_category.sub_category_name, subtosub_category.subtosub_category_name, subtosub_category.subtosub_category_url,product_image.image_name");
        $this->db->join('tblblk_category AS category', 'category.category_id = product.category_id','LEFT');
        $this->db->join('tblblk_sub_category AS sub_category', 'sub_category.sub_category_id = product.sub_category_id','LEFT');
        $this->db->join('tblblk_subtosub_category AS subtosub_category', 'subtosub_category.subtosub_category_id = product.subtosub_category_id','LEFT');
        $this->db->join('tblblk_brands AS brands', 'brands.brand_id  = product.brand_id','LEFT');
        
        $this->db->join('tblblk_product_size_color AS product_colors','product_colors.product_id = product.product_id','LEFT');
        $this->db->join('tblblk_colors AS colors', 'colors.color_id = product_colors.color_id','LEFT');
        $this->db->join('tblblk_product_size_color AS product_size','product_size.product_id = product.product_id','LEFT');
        $this->db->join('tblblk_product_sizes AS product_sizes', 'product_sizes.size_id = product_size.size_id','LEFT');
        $this->db->join("tblblk_product_images product_image", 'product_image.product_id = product.product_id','LEFT');
        
        if($ip != '')
        {
            $array['visitor.visitor_ip_address'] = $ip;
            $this->db->join("tblblk_buyer_visitor visitor",'visitor.product_id = product.product_id','LEFT');
            $this->db->order_by('visitor.modified_date','DESC');    
        }else if($pro_type == 'new')
        {
            $this->db->order_by('product.product_id', 'DESC');
        }else {
            $this->db->order_by('offer_percentage', 'DESC');   
        }
        
        $this->db->group_by('product.product_id');
        $this->db->limit($limit);
        $query = $this->db->get_where('tblblk_product product',$array);
        //echo $this->db->last_query();exit;  
        return $query->result();
    }
    
    public function get_new_product_by_category($category_id)
    {
        $this->db->select('product.product_id, product.product_url, floor(((product.standard_price - product.selling_price) * 100) / product.standard_price) AS offer_percentage, product.seller_id, product.product_sku,product.item_name,product.short_description AS product_description,product.selling_price,product.standard_price,
                           category.category_name,category.category_id, category.category_url, sub_category.sub_category_url, sub_category.sub_category_name, subtosub_category.subtosub_category_name, subtosub_category.subtosub_category_url,product_image.image_name');
        $this->db->join('tblblk_category AS category','category.category_id = product.category_id','LEFT');
        $this->db->join('tblblk_sub_category AS sub_category','sub_category.sub_category_id = product.sub_category_id','LEFT');
        $this->db->join('tblblk_subtosub_category AS subtosub_category','subtosub_category.subtosub_category_id = product.subtosub_category_id','LEFT');
        $this->db->join("tblblk_product_images product_image",'product_image.product_id = product.product_id','LEFT');
        $this->db->order_by('product.product_id','DESC');
        $this->db->order_by('offer_percentage','DESC');
        $this->db->limit(5);
        $this->db->group_by('product.product_id');
        $query = $this->db->get_where('tblblk_product product',array('product.product_status'=>4,'product.is_active'=>'Y','product.category_id'=>$category_id));
        //echo $this->db->last_query();exit;
        return $query->result();
    }
    
    public function save_buyer_feedback($post)
    {
        $insert = array('feedback_list_id'=>$post['feedback_list_id'],'user_id'=>$post['user_id'],'app_type'=>1,'feedback_description'=>$post['feedback_description'],'added'=>date('Y-m-d H:i:s'));
        $this->db->insert('tblblk_users_feedback',$insert);
        
        if($this->db->trans_status() === FALSE)
            return false;         
        else
            return true;
    }
    public function get_app_page_banners($page_id)
	{
        $this->db->select('category_id,app_image as banner_image,banner_redirection');
        $this->db->order_by('tblblk_banner.display_order','ASC');
		$query = $this->db->get_where('tblblk_banner',array('status'=>1,'page_id'=>$page_id));
        return $query->result();    
	}
} 