<?php
/**
 * Product_listing_model 
 * @package bulk
 * @author Mohan Sharma
 * @copyright 2016
 * @version $Id$
 * @access public
 */
class Product_listing_model extends CI_Model
{
	function __construct()
    {
        parent::__construct();
    }
    
    public function count_products($category = '', $sub_category ='',$subtosub_category = '',$brand = '',$product_key_word = '',$price = '',$color = '', $size='', $discount = '')
    {
        $select = 'product.product_id, product.seller_id, product_colors.color_id, group_id AS product_count, COUNT(1) AS cnt';
        $sql    = $this->create_query($select,'', $category, $sub_category, $subtosub_category, $brand, $product_key_word, $price, $color, $size, $discount, true,'');
        //echo $sql;exit;
        $query  = $this->db->query('SELECT * FROM ('.$sql.') AS tmp GROUP BY tmp.color_id,tmp.seller_id');
        if($size ==36)
        {
            //echo $this->db->last_query();exit;
        }
        if($query->num_rows()>0)
		    return $query->num_rows();
        else
            return '0';
    }
    public function get_products_list($category = '', $sub_category ='', $subtosub_category = '', $brand = '', $product_key_word = '', $price = '', $color = '', $size = '',$discount = '', $page_num=1,$sort_by)
    {
        $select = "product.product_id, product.product_url, product.seller_id, product.standard_price, product.selling_price, product_colors.color_id, brands.brand_name,brands.brand_id,
                   floor(((product.standard_price - product.selling_price) * 100) / product.standard_price) AS offer_percentage,
                   (SELECT GROUP_CONCAT( DISTINCT size_name separator ', ') FROM tblblk_product_sizes WHERE size_id IN(select size_id from tblblk_product WHERE group_id=product.group_id)) AS size_name, product.size_id, 
                   product.product_sku, product.style_code, product.group_id, product.item_name, product.sleeve_id, product.model_no, product.model_name, product.short_description, 
                   product.fabric, product.pack_of, product.cash_on_delivery, category.category_name, category.category_url, sub_category.sub_category_url, sub_category.sub_category_name, 
                   subtosub_category.subtosub_category_name, subtosub_category.subtosub_category_url, product_images.image_name AS product_image";
        $sql    = $this->create_query($select, $page_num, $category, $sub_category, $subtosub_category, $brand, $product_key_word, $price, $color, $size, $discount, true, $sort_by);
        //echo $sql;exit; 
        $query  = $this->db->query('SELECT * FROM ('.$sql.') AS product GROUP BY product.color_id, product.seller_id'.$sort_by);
        //echo $this->db->last_query();exit;
        if($query->num_rows() >0)
		    return $query->result();
        else
            return '';                     
    }
    public function get_min_max_price($category = '', $sub_category ='', $subtosub_category = '', $brand = '', $product_key_word = '', $price = '', $color = '', $size = '', $discount = '')
    {
		$select = "IFNULL(MAX(FLOOR(product.selling_price)),'0') AS max_price, IFNULL(MIN(FLOOR(product.selling_price)),'0') AS min_price";
        $sql    = $this->create_query($select, '', $category, $sub_category, $subtosub_category, $brand, $product_key_word, $price, $color, $size, $discount, false,'');
        $query  = $this->db->query($sql);
        //echo $this->db->last_query();exit;
		return $query->row(); 
    }
    
    public function get_sub_tosub_category($category, $sub_category, $subtosub_category)
    {
        if($category != '')
		{
		    $this->db->group_start();
			$this->db->or_where_in('category.category_id',$category);
            $this->db->or_where_in('category.category_url',$category);
            $this->db->group_end();
		}
        if($sub_category != '')
		{
		    $this->db->group_start();
			$this->db->or_where_in('sub_category.sub_category_id',$sub_category);
            $this->db->or_where_in('sub_category.sub_category_url',$sub_category);
            $this->db->group_end();
		}
        /*if($subtosub_category != '')
		{
		    $this->db->group_start(); 
			$this->db->or_where_in('subtosub_category.subtosub_category_id',$subtosub_category);
            $this->db->or_where_in('subtosub_category.subtosub_category_url',$subtosub_category);
            $this->db->group_end();
		}*/
        $this->db->select('category.category_name, category.category_url, category.category_id, sub_category.sub_category_name, sub_category.sub_category_id, sub_category.sub_category_url, subtosub_category.subtosub_category_name, subtosub_category.subtosub_category_id,subtosub_category.subtosub_category_url');
		$this->db->from('tblblk_subtosub_category AS subtosub_category');
		$this->db->join('tblblk_sub_category AS sub_category','sub_category.sub_category_id = subtosub_category.sub_category_id','LEFT');
		$this->db->join('tblblk_category AS category','category.category_id = sub_category.category_id ','LEFT'); 
	    $query = $this->db->get();
        return $query->result();
    }
    public function get_brand_info($brand = '')
    {
        /*if($brand != '')
		{
		    $this->db->group_start();
			$this->db->or_where_in('brands.brand_id',$brand);
            $this->db->or_where_in('brands.brand_url',$brand);
            $this->db->group_end();
		}*/
        $this->db->select('brand_id, brand_name, brand_url');
		$this->db->from('tblblk_brands AS brands'); 
		$this->db->where('status',1); 
	    $query = $this->db->get();
        return $query->result();
    }
    public function get_color_info($color = '')
    {
        /*if($color != '')
		{
		    $this->db->group_start();
			$this->db->or_where_in('colors.color_id',$color);
            $this->db->or_where_in('colors.color_url',$color);
            $this->db->group_end();
		}*/
        $this->db->select('color_id, color_name, color_code, color_url');
		$this->db->from('tblblk_colors AS colors'); 
		$this->db->where('status',1); 
	    $query = $this->db->get();
        return $query->result();
    }
    public function get_size_info($size = '')
    {
        $this->db->select('size_id, size_name, status, size_url');
		$this->db->from('tblblk_product_sizes AS sizes'); 
		$this->db->where('status',1); 
	    $query = $this->db->get();
        return $query->result();
    }
    
    Private function create_query($select, $page_num = '', $category = '', $sub_category ='',$subtosub_category = '', $brand = '', $product_key_word = '', $price = '', $color = '', $size = '', $discount = '',$group_by = true,$sort_by = '')
    {
        if($category != '')
		{
		    $this->db->group_start();
			$this->db->where_in('category.category_id',$category);
            $this->db->or_where_in('category.category_url',$category);
            $this->db->group_end();
		}
        if($sub_category != '')
		{
		    $this->db->group_start();
			$this->db->where_in('sub_category.sub_category_id',$sub_category);
            $this->db->or_where_in('sub_category.sub_category_url',$sub_category);
            $this->db->group_end();
		}
        if($subtosub_category != '')
		{
		    $this->db->group_start(); 
			$this->db->where_in('subtosub_category.subtosub_category_id',$subtosub_category);
            $this->db->or_where_in('subtosub_category.subtosub_category_url',$subtosub_category);
            $this->db->group_end();
		}
        if($brand != '')
		{
		    $this->db->group_start();
			$this->db->where_in('brands.brand_id',$brand);
            $this->db->or_where_in('brands.brand_url',$brand);
            $this->db->group_end();
		}
        if($color != '')
		{
		    $this->db->group_start();
			$this->db->where_in('colors.color_id',$color);
            $this->db->or_where_in('colors.color_url',$color);
            $this->db->group_end();
		}
        if($size != '')
		{
		    $this->db->group_start();
			$this->db->where_in('product_size.size_id',$size);
            $this->db->where_in('product.size_id',$size);
            $this->db->group_end();
		}
        
        if($discount != '')
        {
            $this->db->group_start();
            $where = '';
            $or = '';
            for($i = 0; $i < count($discount); $i++)
            {
                list($min,$max) = explode('-',$discount[$i]);
                $where .= $or. ' floor(((product.standard_price - product.selling_price) * 100) / product.standard_price) BETWEEN '.$this->db->escape($min).'+1 AND '.$this->db->escape($max);
                $or = ' OR';
                
            }
            $this->db->where($where);
            $this->db->group_end();
        }
        if($price != '')
		{
		    //$this->db->group_start();
			$this->db->where('product.selling_price >=',str_replace(', ','',$price[0]));
            $this->db->where('product.selling_price <=',str_replace(', ','',$price[1]));
            //$this->db->group_end();
		}
        if($product_key_word !='')
        {
            $this->db->group_start();
            $this->db->or_like('brands.brand_url',$product_key_word, 'after');
            $this->db->or_like('product.product_url',$product_key_word, 'after');
            $this->db->or_like('sub_category.sub_category_url',$product_key_word, 'after');
            $this->db->or_like('category.category_url',$product_key_word, 'after');
			$this->db->or_like('product_keywords.keyword_url',$product_key_word, 'after');
            $this->db->or_like('subtosub_category.subtosub_category_url',$product_key_word, 'after');
            $this->db->group_end();
        }
        
        if($page_num != '')
        {
            $this->db->limit(30,($page_num-1)*30);
        }
         
        $this->db->select($select);
		$this->db->from('tblblk_product AS product');
		$this->db->join('tblblk_category AS category','category.category_id = product.category_id','LEFT');
		$this->db->join('tblblk_sub_category AS sub_category','sub_category.sub_category_id = product.sub_category_id','LEFT');
		$this->db->join('tblblk_subtosub_category AS subtosub_category','subtosub_category.subtosub_category_id = product.subtosub_category_id','LEFT');
		$this->db->join('tblblk_brands AS brands','brands.brand_id = product.brand_id','LEFT');
        $this->db->join('tblblk_product_colors AS product_colors','product_colors.product_id = product.product_id','LEFT');
        $this->db->join('tblblk_colors AS colors','colors.color_id = product_colors.color_id','LEFT');
        $this->db->join('tblblk_product_sizes AS product_size','product_size.size_id = product.size_id','LEFT');
        //$this->db->join('tblblk_colors AS colors','colors.color_id = product_colors.color_id','LEFT');
        $this->db->join('tblblk_product_keywords AS product_keywords','product_keywords.product_id = product.product_id','LEFT');
        $this->db->join('tblblk_product_images AS product_images','product_images.product_id = product.product_id','LEFT');
        $where = array('category.status'=>1,'sub_category.status'=>1,'subtosub_category.status'=>1,'product.is_active'=>'Y','product.product_status'=>4);
        $this->db->where($where);
        if($group_by == true)
        {
            $this->db->group_by(array('product.product_id','product_keywords.product_id'));
            /*if($sort_by != '')
            {
                $this->db->order_by($sort_by);
            }*/
        }
        /*else
        {
            $this->db->group_by(array('product.product_id'));
        }*/
        return $this->db->get_compiled_select(); 
	    //$query = $this->db->get();
    }
    
    public function get_product_info($product_id)
    {
        $this->db->select('product.product_id, product.item_name, product.product_url, color.color_name, color.color_url, color.color_id, category.category_url AS category, sub_category.sub_category_url AS sub_category');
		$this->db->from('tblblk_product As product');
		$this->db->join('tblblk_product_sizes AS product_size','product_size.size_id = product.size_id','LEFT');
        $this->db->join('tblblk_category AS category','category.category_id = product.category_id','LEFT');
        $this->db->join('tblblk_sub_category AS sub_category','sub_category.sub_category_id = product.sub_category_id','LEFT');
		$this->db->join('tblblk_subtosub_category AS subtosub_category','subtosub_category.subtosub_category_id = product.subtosub_category_id','LEFT');
	    $this->db->join('tblblk_product_colors colors','colors.product_id = product.product_id','LEFT');
	    $this->db->join('tblblk_colors color','color.color_id = colors.color_id','LEFT');
		$this->db->where('product.product_id', $product_id); 
		$query = $this->db->get();
		//echo $this->db->last_query();exit;
		return $query->row_array();
    } 
} 
?>