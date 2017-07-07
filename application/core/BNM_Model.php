<?php
class BNM_model extends CI_Model
{
	function __construct()
    {
	   parent::__construct();
    }
	/* ------------------------------ Note - Start --------------------------------- */
	//	Please be careful while making any change in function, as these functions might be used some where else. 
	//	Please varify first before making changes.
	//	Please use proper commenting and add new functions in proper block.
	/* ------------------------------ Note - End --------------------------------- */
	/* ------------------------------ For Comman ---------------------------------- */
    //--------get All categorys
	public function get_category()
	{
		$this->db->select('tblblk_category.*,(SELECT COUNT(1) FROM tblblk_product WHERE tblblk_product.category_id = tblblk_category.category_id AND tblblk_product.is_active = \'Y\' AND product_status = 4) AS product_count');
		$this->db->where('status',1);
        $this->db->order_by('display_order','ASC');
		$this->db->from('tblblk_category');
        $this->db->having('product_count > 0');
		$query = $this->db->get();
		return $query->result();
	}
    //--------get sub categorys By category_id
    public function get_sub_category($category_id)
	{
		$this->db->select('sub_category.sub_category_id, category.category_name, sub_category.sub_category_name, sub_category.subcategory_images, sub_category.sub_category_url,sub_category.status,
        (SELECT COUNT(1) FROM tblblk_product WHERE tblblk_product.sub_category_id = sub_category.sub_category_id AND tblblk_product.is_active = \'Y\' AND product_status = 4) AS product_count');
        $this->db->join("tblblk_category category",'category.category_id = sub_category.category_id','LEFT');
		$this->db->where(array('category.category_id'=>$category_id,'sub_category.status'=>1,'category.status'=>1));
        $this->db->or_where(array('category.category_url'=>$category_id));
        $this->db->order_by('sub_category.display_order','ASC');
		$this->db->from('tblblk_sub_category sub_category');
        $this->db->having('product_count > 0');
		$query = $this->db->get();
		return $query->result();
	}
    //--------get subtosub categorys By sub_category_id
    public function get_subtosub_category($sub_category_id)
	{
		$this->db->select('subtosub_category.sub_category_id,subtosub_category.subtosub_category_id, subtosub_category.subtosub_category_image, 
                           subtosub_category.subtosub_category_name, subtosub_category.subtosub_category_url, subtosub_category.status,
                           (SELECT COUNT(1) FROM tblblk_product WHERE tblblk_product.subtosub_category_id = subtosub_category.subtosub_category_id AND tblblk_product.is_active = \'Y\' AND product_status = 4) AS product_count');
        $this->db->join("tblblk_sub_category sub_category",'sub_category.sub_category_id = subtosub_category.sub_category_id','LEFT');
		$this->db->where(array('subtosub_category.sub_category_id'=>$sub_category_id,'subtosub_category.status'=>1));
        $this->db->or_where(array('sub_category.sub_category_url'=>$sub_category_id));
        $this->db->order_by('subtosub_category.display_order','ASC');
		$this->db->from('tblblk_subtosub_category subtosub_category');
        $this->db->having('product_count > 0');
		$query = $this->db->get();
		return $query->result();
	}
	/* ------------------------------ For Comman - End --------------------------------- */
    public function get_user_details($id)
	{
        $this->db->select('user_id, user_type, first_name, last_name, email, mobile, gender, user_secret_key');
		$this->db->where(array('user_id'=>$id,'is_active'=>'Y','status'=>1));
		$this->db->from('tblblk_users');
		$query = $this->db->get();
        if($query->num_rows()> 0)
		  return $query->row();
        else
          return '';    
	}
    public function get_site_setting()
	{
        $this->db->select('*');
		$query = $this->db->get_where('tblblk_setting',array('status'=>1));
        return $query->row();    
	}
    public function get_page_setting($page_id)
	{
        $this->db->select('page_name,page_title,page_heading,meta_keyword,meta_description,page_content');
		$query = $this->db->get_where('tblblk_page',array('status'=>1,'page_id'=>$page_id));		
        return $query->row();    
	}
	public function get_page_banners($page_id)
	{
        $this->db->select('*');
        $this->db->order_by('tblblk_banner.display_order','ASC');
		$query = $this->db->get_where('tblblk_banner',array('status'=>1,'page_id'=>$page_id));
        return $query->result();    
	}	
	public function get_category_page_banners($cat_id = '', $sub_cat_id = '',$subtosub_cat_id = '',$page_id,$banner_possition)
	{
		$where = array('banner.status'=>1,'banner.page_id'=>$page_id,'banner.banner_possition'=>$banner_possition);
		if($cat_id != '')
		{
		    $this->db->group_start();
			$this->db->where('category.category_id',$cat_id);
			$this->db->or_where('category.category_url',$cat_id);
            $this->db->group_end();
			$this->db->join("tblblk_category category",'category.category_id = banner.category_id','LEFT');
            $this->db->limit(5);
		}
		elseif($sub_cat_id != '')
		{
			$this->db->where('sub_category.sub_category_id', $sub_cat_id);
			$this->db->or_where('sub_category.sub_category_url', $sub_cat_id);
			$this->db->join("tblblk_sub_category sub_category",'sub_category.sub_category_id = banner.sub_category_id','LEFT');
		}
	    elseif($subtosub_cat_id != '')
	    {
    	    $this->db->where('subtosub_category.subtosub_category_id',$subtosub_cat_id);
			$this->db->or_where('subtosub_category.subtosub_category_url',$subtosub_cat_id);
			$this->db->join("tblblk_subtosub_category subtosub_category",'subtosub_category.subtosub_category_id = banner.subtosub_cat_id','LEFT');
	    }
		$this->db->select('*');
		$this->db->from('tblblk_banner banner');
		$this->db->where($where);
        
		$query = $this->db->get();
        //echo $this->db->last_query();exit;		
		return $query->result(); 
	}
	public function get_sub_category_banners($cat_id,$page_id)
	{
		if($cat_id != '')
		{
			$this->db->where('category.category_id',$cat_id);
			$this->db->or_where('category.category_url',$cat_id);
			$this->db->join("tblblk_category category",'category.category_id = banner.category_id','LEFT');			
			$query = $this->db->get_where('tblblk_banner banner',array('banner.status'=>1,'banner.page_id'=>$page_id,'banner.banner_possition'=>'middle'));			
		}
		$this->db->select('*');
		return $query->result();
	}
	public function get_faq_details()
	{
		$this->db->select('*');
		$query = $this->db->get('tblblk_faq');
		return $query->result();
	}

	public function getContactPageSetting()
	{
		$this->db->select('*');
		$query = $this->db->get('tblblk_contact_page_setting');
		return $query->result();
	}
    //--get user cart data from database----
    public function get_cart_data($userid)
	{
        $this->db->select('cart.*,product.product_url,product.cash_on_delivery,category.category_url category,sub_category.sub_category_url sub_category,vat.vat_class_per as vat_amt,product_info.package_weight,product_info.package_length,product_info.package_breadth,product_info.package_height');
       	$this->db->join('tblblk_product As product','cart.product_id = product.product_id','LEFT');
       	$this->db->join('tblblk_vat_classes AS vat','vat.vat_class_id = product.vat_class','LEFT');
        $this->db->join('tblblk_category AS category','category.category_id = product.category_id','LEFT');
       	$this->db->join('tblblk_sub_category AS sub_category','sub_category.sub_category_id = product.sub_category_id','LEFT');
	    $this->db->join('tblblk_product_additional_info product_info', 'product_info.product_id = product.product_id','LEFT');
        $this->db->where('cart.user_id',$userid);
		$query = $this->db->get('tblblk_product_cart cart');
        //echo $this->db->last_query();exit;		
        return $query->result_array();
	}
    
	/* ------------------------------ For ... -auto complete search Start --------------------------------- */
    public function get_search_brand($serch_string)
    {
        $this->db->select('brands.brand_id,brands.brand_name,brands.brand_url');
		$this->db->join("tblblk_product AS product",'product.brand_id = brands.brand_id','LEFT');
        $this->db->like('brands.brand_name', $serch_string);
		$this->db->order_by('brands.brand_name','ASC');
		$this->db->group_by('brands.brand_id');
		$query = $this->db->get_where('tblblk_brands brands',array('product.product_status'=>4,'brands.status'=>1));
		return $query->result();
    }
    public function get_search_subtosub_category($serch_string)
    {
        $this->db->select('subtosub_category.subtosub_category_id, subtosub_category.subtosub_category_name, subtosub_category.subtosub_category_url');
		$this->db->join("tblblk_product AS product",'product.subtosub_category_id = subtosub_category.subtosub_category_id','LEFT');
        $this->db->like('subtosub_category.subtosub_category_name', $serch_string);
		$this->db->order_by('subtosub_category.subtosub_category_name','ASC');
		$this->db->group_by('subtosub_category.subtosub_category_id');
		$query = $this->db->get_where('tblblk_subtosub_category subtosub_category',array('product.product_status'=>4,'subtosub_category.status'=>1));
		return $query->result();
    }
    public function get_search_sub_category_product($serch_string)
    {
        $this->db->select('sub_category.sub_category_id, sub_category.sub_category_name, sub_category.sub_category_url');
		$this->db->join("tblblk_product AS product",'product.sub_category_id = sub_category.sub_category_id','LEFT');
        $this->db->like('sub_category.sub_category_name', $serch_string);
        $this->db->like('sub_category.sub_category_url', $serch_string);
		$this->db->order_by('sub_category.sub_category_name','ASC');
		$this->db->group_by('sub_category.sub_category_id');
		$query = $this->db->get_where('tblblk_sub_category sub_category',array('product.product_status'=>4,'sub_category.status'=>1));
        //echo $this->db->last_query();exit;

		return $query->result();
    }
    public function get_product_kewwords($serch_string)
    {
        $this->db->select('product.product_id, product_keywords.product_keyword,product_keywords.keyword_url');//, product_keywords.keyword_url
		$this->db->join("tblblk_product AS product",'product.product_id = product_keywords.product_id','LEFT');
        $this->db->like('product_keywords.product_keyword', $serch_string,'after');
		$this->db->order_by('product_keywords.product_keyword','ASC');
		$this->db->group_by('product_keywords.product_keyword','product.product_id','product.group_id');
		$query = $this->db->get_where('tblblk_product_keywords product_keywords',array('product.product_status'=>4));
        //echo $this->db->last_query();exit;
		return $query->result();
    }
    
    public function get_vat_percentage($product_id)
    {
        $this->db->select('vat.vat_class_per as vat');
        $this->db->from('tblblk_product As product');
        $this->db->join('tblblk_vat_classes AS vat','vat.vat_class_id = product.vat_class','LEFT');
        $this->db->where('product.product_id', $product_id); 
		$query = $this->db->get();
        //echo $this->db->last_query();exit;
        return $query->row()->vat;
    }
	/* ------------------------------ For ... auto complete search - End --------------------------------- */
    
    public function save_buyer_feedback()
    {
        $insert = array('feedback_list_id'=>$post['feedback_list_id'],'user_id'=>$post['user_id'],'app_type'=>1,'feedback_description'=>$post['feedback_description'],'added'=>date('d-m-Y H:i:s'));
        $this->db->insert('tblblk_users_feedback',$insert);
    }
}
?>