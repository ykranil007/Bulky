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
    public function count_products($category = '', $sub_category ='',$subtosub_category = '',$brand = '', $product_key_word = '',$price = '', $color = '', $size='', $discount = '',$sets = '', $credit_days = '' )
    {
        $select = 'product.product_id, product.seller_id, product_colors.color_id, COUNT(1) AS cnt';
        $sql    = $this->compiled_query($select, $category, $sub_category, $subtosub_category, $brand, $product_key_word, $price, $color, $size, $discount,$sets, $credit_days, true,'');
        //echo $sql;exit;
        $query  = $this->db->query(''.$sql.''); 
        //echo $this->db->last_query();exit;
        return $query->num_rows();
    }
    public function get_products_list($category = '', $sub_category ='', $subtosub_category = '', $brand = '', $product_key_word = '', $price = '', $color = '', $size = '',$discount = '', $sets = '', $credit_days = '', $page_num=1,$sort_by)
    {
        $select = "product.product_id, product.product_url, product.set_description, product.seller_id, REPLACE(TRUNCATE(product.standard_price,0),', ','') AS standard_price, 
                   REPLACE(TRUNCATE(product.selling_price,0),', ','') AS selling_price, product_colors.color_id, brands.brand_name,brands.brand_id,
                   ((product.standard_price - product.selling_price) * 100) / product.standard_price AS offer_percentage,
                   '' AS size_name, 
                   product.product_sku, product.item_name, product.short_description,
                   product.fabric, product.pack_of, product.cash_on_delivery, category.category_name, category.category_url, sub_category.sub_category_url, sub_category.sub_category_name, 
                   subtosub_category.subtosub_category_name, subtosub_category.subtosub_category_url, product_images.image_name AS product_image";
        $query  = $this->compiled_query($select, $category, $sub_category, $subtosub_category, $brand, $product_key_word, $price, $color, $size, $discount,$sets, $credit_days, true, $sort_by);
        
        if(is_array($page_num))
            $limit  = ' LIMIT '.$page_num['from'].', '.$page_num['to'];
        else
            $limit  = ' LIMIT '.(($page_num-1)*50).', 50';
         
        $sql = $query.$sort_by.$limit;
        //echo $sql;exit;
        $query  = $this->db->query($sql);
        //echo $this->db->last_query();exit;
        
        if($query->num_rows() >0)
		    return $query->result();
        else
            return (object) array();                     
    }
    
    public function get_min_max_price($category = '', $sub_category = '', $subtosub_category = '', $brand = '', $product_key_word = '', $price = '', $color = '', $size = '', $discount = '',$sets = '', $credit_days = '')
    {
		$select = "IFNULL(MAX(FLOOR(product.selling_price)),'0') AS max_price, IFNULL(MIN(FLOOR(product.selling_price)),'0') AS min_price";
        $sql    = $this->compiled_query($select, $category, $sub_category, $subtosub_category, $brand, $product_key_word, $price, $color, $size, $discount, $sets, $credit_days, false,'');
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
    			$this->db->or_where_in('sub_category.sub_category_id', $sub_category);
                $this->db->or_where_in('sub_category.sub_category_url', $sub_category);
            $this->db->group_end();
		}
        /*if($subtosub_category != '')
		{
		    $this->db->group_start(); 
			$this->db->or_where_in('subtosub_category.subtosub_category_id',$subtosub_category);
            $this->db->or_where_in('subtosub_category.subtosub_category_url',$subtosub_category);
            $this->db->group_end();
		}*/
        
        $this->db->select('category.category_name, category.category_url, category.category_id, sub_category.sub_category_name, sub_category.sub_category_id, sub_category.sub_category_url, subtosub_category.subtosub_category_name, subtosub_category.subtosub_category_id,subtosub_category.subtosub_category_url,
        (SELECT COUNT(1) FROM tblblk_product WHERE tblblk_product.subtosub_category_id = subtosub_category.subtosub_category_id AND tblblk_product.is_active = \'Y\' AND product_status = 4) AS product_count');
		$this->db->from('tblblk_subtosub_category AS subtosub_category');
		$this->db->join('tblblk_sub_category AS sub_category','sub_category.sub_category_id = subtosub_category.sub_category_id','LEFT');
		$this->db->join('tblblk_category AS category','category.category_id = sub_category.category_id','LEFT'); 
        $this->db->group_by(array('subtosub_category.subtosub_category_id'));
        $this->db->having('product_count > 0');
        
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        return $query->result();
    }
    public function get_brand_info($brand = '')
    {
        $this->db->select('brands.brand_id, brands.brand_name, brands.brand_url');
        $this->db->join('tblblk_product AS product','product.brand_id = brands.brand_id','LEFT');
		$this->db->from('tblblk_brands AS brands'); 
		$this->db->where(array ('brands.status'=>1,'product.product_status'=>4,'product.is_active'=>'Y'));
        $this->db->group_by(array('brands.brand_id')); 
	    $query = $this->db->get();
        return $query->result();
    }
    public function get_color_info($color = '')
    {
        $this->db->select('colors.color_id, colors.color_name, colors.color_code, colors.color_url');
		$this->db->from('tblblk_colors AS colors'); 
        $this->db->join('tblblk_product_size_color AS product_colors','product_colors.color_id = colors.color_id','LEFT');
        $this->db->join('tblblk_product AS product','product.product_id = product_colors.product_id','LEFT');
		$this->db->where(array ('colors.status'=>1,'product.product_status'=>4,'product.is_active'=>'Y'));
        $this->db->group_by(array('colors.color_id')); 
	    $query = $this->db->get();
        return $query->result();
    }
    public function get_size_info($size = '')
    {
        $this->db->select('sizes.size_id, sizes.size_name, sizes.status, sizes.size_url');
		$this->db->from('tblblk_product_sizes AS sizes');
        $this->db->join('tblblk_product_size_color AS product_size','product_size.size_id = sizes.size_id','LEFT');
        $this->db->join('tblblk_product AS product','product.product_id = product_size.product_id','LEFT'); 
		$this->db->where(array('status'=>1,'product.product_status'=> 4));
        $this->db->group_by(array('sizes.size_id')); 
	    $query = $this->db->get();
        return $query->result();
    }
    public function get_sets_info()
    {
        $this->db->select('DISTINCT (pack_of)');
		$this->db->from('tblblk_product'); 
		$this->db->where(array('is_active'=>'Y','product_status'=> 4));        
	    $query = $this->db->get();
        if($query->num_rows() > 0)
        {
            $i = 0; $sets = array();
            foreach($query->result() AS $record)
            {
                $i++;
                $obj = new stdClass();
                $obj->set_id = $i;
                $obj->set_url = $record->pack_of;
                $obj->set_name = $record->pack_of;
                $sets[] = $obj;
            }
        }
        return $sets;
    }
    public function get_credit_days_info($credit_days)
    {
        $this->db->select('credit_day_id AS credit_days_id, credit_days AS credit_days_name,credit_days AS credit_days_url');
		$this->db->from('tblblk_credit_days'); 
		$this->db->where(array('credit_days_status'=>1));        
	    $query = $this->db->get();
        return $query->result();
    }
    Private function compiled_query($select, $category = '', $sub_category ='',$subtosub_category = '', $brand = '', $product_key_word = '', $price = '', $color = '', $size = '', $discount = '', $sets = '', $credit_days = '', $group_by = true,$sort_by = '')
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
    			$this->db->where_in('subtosub_category.subtosub_category_id', $subtosub_category);
                $this->db->or_where_in('subtosub_category.subtosub_category_url', $subtosub_category);
                //$this->db->or_where("FIND_IN_SET('".implode(',',$subtosub_category)."', search_keywords)");
            $this->db->group_end();
		}
        if($brand != '')
		{
		    $this->db->group_start();
    			$this->db->where_in('brands.brand_id', $brand);
                $this->db->or_where_in('brands.brand_url', $brand);
            $this->db->group_end();
		}
        if($color != '')
		{
		    $this->db->group_start();
    			$this->db->where_in('colors.color_id', $color);
                $this->db->or_where_in('colors.color_url', $color);
            $this->db->group_end();
		}
        if($size != '')
		{
		    $this->db->group_start();
    			$this->db->where_in('product_size.size_id',$size);
                $this->db->where_in('product_sizes.size_id',$size);
            $this->db->group_end();
		}
        
        if($discount != '')
        {
            //print_r($discount);exit;
            $this->db->group_start();
            $where = $or = '';
            for($i = 0; $i < count($discount); $i++)
            {
                list($min,$max) = explode('-',$discount[$i]);
                $where .= $or. ' FLOOR(((product.standard_price - product.selling_price) * 100) / product.standard_price) BETWEEN '.$this->db->escape($min).'+1 AND '.$this->db->escape($max);
                $or = ' OR';
            }
            $this->db->where($where);
            $this->db->group_end();
        }
        if($sets !='')
        {
			$this->db->where_in('product.pack_of',$sets);
        }
        if($credit_days !='')
        {
            $this->db->group_start();
    			$this->db->where_in('credit_days.credit_day_id',$credit_days);
                $this->db->or_where_in('credit_days.credit_days',$credit_days);
            $this->db->group_end();
            $this->db->join('tblblk_credit_days AS credit_days','credit_days.credit_day_id = product.credit_days_id','LEFT');
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
		    $url = str_replace(' ','-',strtolower($product_key_word));
		    if(preg_match('/^men/', trim($product_key_word)))
		    {
			    $this->db->or_like('brands.brand_url', $product_key_word, 'after');
			    $this->db->or_like('brands.brand_name', $product_key_word, 'after');
			    $this->db->or_like('product.product_url', $url, 'after');
			    $this->db->or_like('product.item_name', $product_key_word, 'after');
			    $this->db->or_like('sub_category.sub_category_name', $product_key_word, 'after');
			
			    $this->db->or_like('sub_category.sub_category_url', $url, 'after');
			    //$this->db->or_like('product_keywords.keyword_url', $url, 'after');
			    //$this->db->or_like('product_keywords.product_keyword', $product_key_word, 'after');
                $this->db->or_where("FIND_IN_SET('".$url."', product_keywords.keyword_url)");
                $this->db->or_where("FIND_IN_SET('".$product_key_word."', product_keywords.product_keyword)");
                $this->db->or_where("FIND_IN_SET('".$product_key_word."', category.search_keywords)");
                $this->db->or_where("FIND_IN_SET('".$product_key_word."', sub_category.search_keywords)");
                $this->db->or_where("FIND_IN_SET('".$product_key_word."', subtosub_category.search_keywords)");
			    $this->db->or_like('colors.color_url', $url, 'after');
			    $this->db->or_like('colors.color_name', $product_key_word, 'after');
			    $this->db->or_like('subtosub_category.subtosub_category_name', $product_key_word, 'after');
			    $this->db->or_like('subtosub_category.subtosub_category_url', $url, 'after');
		    }
		    else
		    {
			    $this->db->or_like('brands.brand_url', $product_key_word);
			    $this->db->or_like('brands.brand_name', $product_key_word);
			    $this->db->or_like('product.product_url', $url);
			    $this->db->or_like('product.item_name', $product_key_word);
			    $this->db->or_like('sub_category.sub_category_url', $url);
			    $this->db->or_like('sub_category.sub_category_name', $product_key_word);
			    $this->db->or_like('category.category_url', $url, 'after');
			    $this->db->or_like('category.category_name', $product_key_word,'after');
			    
			    $this->db->or_where("FIND_IN_SET('".$url."', product_keywords.keyword_url)");
                $this->db->or_where("FIND_IN_SET('".$product_key_word."', product_keywords.product_keyword)");
                $this->db->or_where("FIND_IN_SET('".$product_key_word."', category.search_keywords)");
                $this->db->or_where("FIND_IN_SET('".$product_key_word."', sub_category.search_keywords)");
                $this->db->or_where("FIND_IN_SET('".$product_key_word."', subtosub_category.search_keywords)");
			    
			    $this->db->or_like('colors.color_url', $url);
			    $this->db->or_like('colors.color_name', $product_key_word);
			    $this->db->or_like('subtosub_category.subtosub_category_url', $url);
			    $this->db->or_like('subtosub_category.subtosub_category_name', $product_key_word);
		    }
		    //$this->db->or_like("subtosub_category.search_keywords", $product_key_word,'after');
		
		    if(is_numeric($product_key_word))
		    {
			    $this->db->or_like('product.selling_price',$product_key_word);
		    }
		    $this->db->group_end();
	    }
        
        $this->db->select($select);
		$this->db->from('tblblk_product AS product');
		$this->db->join('tblblk_category AS category','category.category_id = product.category_id','LEFT');
		$this->db->join('tblblk_sub_category AS sub_category','sub_category.sub_category_id = product.sub_category_id','LEFT');
		$this->db->join('tblblk_subtosub_category AS subtosub_category','subtosub_category.subtosub_category_id = product.subtosub_category_id','LEFT');
		$this->db->join('tblblk_brands AS brands','brands.brand_id = product.brand_id','LEFT');
        
        $this->db->join('tblblk_product_size_color AS product_colors','product_colors.product_id = product.product_id','LEFT');
        $this->db->join('tblblk_colors AS colors', 'colors.color_id = product_colors.color_id','LEFT');
        $this->db->join('tblblk_product_size_color AS product_size','product_size.product_id = product.product_id','LEFT');
        $this->db->join('tblblk_product_sizes AS product_sizes', 'product_sizes.size_id = product_size.size_id','LEFT');
        
        $this->db->join('tblblk_product_keywords AS product_keywords','product_keywords.product_id = product.product_id','LEFT');
        $this->db->join('tblblk_product_images AS product_images','product_images.product_id = product.product_id','LEFT');
        
        $where = array('category.status'=>1,'sub_category.status'=>1,'subtosub_category.status'=>1,'product.is_active'=>'Y','product.product_status'=>4);
        $this->db->where($where);
        if($group_by == true)
        {
            $this->db->group_by(array('product.product_id',/*'colors.color_id',*/'product.seller_id'));
        }
        return $this->db->get_compiled_select();
    }
    public function get_product_info($product_id)
    {
        $this->db->select('product.product_id, product.item_name,product.set_description, product.product_url, category.category_url AS category, sub_category.sub_category_url AS sub_category,subtosub_category.subtosub_category_url As subtosub_category');
		$this->db->from('tblblk_product As product');
        $this->db->join('tblblk_category AS category','category.category_id = product.category_id','LEFT');
        $this->db->join('tblblk_sub_category AS sub_category','sub_category.sub_category_id = product.sub_category_id','LEFT');
		$this->db->join('tblblk_subtosub_category AS subtosub_category','subtosub_category.subtosub_category_id = product.subtosub_category_id','LEFT');
        /*$this->db->join('tblblk_product_size_color AS product_colors','product_colors.product_id = product.product_id','LEFT');
        $this->db->join('tblblk_colors AS colors', 'colors.color_id = product_colors.color_id','LEFT');*/
        $this->db->join('tblblk_product_size_color AS product_size','product_size.product_id = product.product_id','LEFT');
        $this->db->join('tblblk_product_sizes AS product_sizes', 'product_sizes.size_id = product_size.size_id','LEFT');
        
		$this->db->where('product.product_id', $product_id); 
		$query = $this->db->get();
		return $query->row_array();
    } 
	public function get_category_name($categoryUrl)
	{
		$this->db->select('category_name');
		$this->db->from('tblblk_category');
		$this->db->where('category_url',$categoryUrl);
		$query = $this->db->get();
		return ($query->num_rows() > 0)?$query->row()->category_name:'';
	}
	public function get_sub_category_name($SubCategoryUrl)
	{
		$this->db->select('sub_category_name');
		$this->db->from('tblblk_sub_category');
		$this->db->where('sub_category_url',$SubCategoryUrl);
		$query = $this->db->get();
		return ($query->num_rows() > 0)?$query->row()->sub_category_name:'';
	}
	public function get_subtosub_category_name($SubtosubCategoryUrl)
	{
		$this->db->select('subtosub_category_name');
		$this->db->from('tblblk_subtosub_category');
		$this->db->where('subtosub_category_url',$SubtosubCategoryUrl);
		$query = $this->db->get();
		return ($query->num_rows() > 0)? $query->row()->subtosub_category_name:'';
	}
} 
?>