<?php
date_default_timezone_set("asia/kolkata");
class Common
{
	private $db = null; //database connection variable
    public $tax_rate = 0;
	
	/**
	 * The constructor
	 **/
	public function __construct($connection)
	{
		$this->db = $connection;
        $this->tax_rate  = 5;
	}
    
    public function rto_order_status_tracking_delhivery($order_response, $order_info)
    {
        $rto_charges  = $this->rto_charges_for_delhivery($this->create_order_zone_by_order_id($order_info['order_id']), $this->get_return_order_weight($order_info['order_id'],3));
        
        $update_array = array('rto_charges'=>$rto_charges);
        $this->db->update_table_row($update_array,'tblblk_order_charges',"`order_id` = '".$order_info['order_id']."'");
    }
    
    public function create_order_zone_by_order_id($order_id)
    {
        $where = "seller_id = (SELECT seller_id FROM tblblk_product_orders WHERE order_id = ".$order_id.")";
        $seller_pincode = $this->get_table_column('pickup_pincode', 'tblblk_seller_kyc', $where);
        
        // Get user pincode
        $where = "delivery_id = (SELECT delivery_id FROM tblblk_product_orders WHERE order_id = ".$order_id.")";
        $user_pincode = $this->get_table_column('pincode', 'tblblk_users_delivery_address', $where);
        
         
        $where = "pincode = '".$user_pincode."'";
        $user_info = $this->db->get_table_row('pincode, city_id, state_id', 'tblblk_delivery_pincode_demo', $where);
        
        $where = "pincode = '".$seller_pincode."'";
        $seller_info  = $this->db->get_table_row('pincode, city_id, state_id', 'tblblk_delivery_pincode_demo', $where);
        return $this->get_order_zone_for_delhivery($seller_info, $user_info);
    } 
    
    public function get_return_order_weight($order_id, $order_item_status)
    {
        $order_weight = $this->db->get_table_row('IFNULL(SUM(package_length*package_height*package_breadth),0) AS order_weight',
        'tblblk_product_additional_info',"product_id IN(SELECT product_id FROM tblblk_order_items WHERE order_id = $order_id AND order_item_status = $order_item_status )");
        return ($order_weight['order_weight']);//  /4500
    }
    private function get_order_zone_for_delhivery($seller_info, $user_info)
    {
        $zone = '';
        //define Zone according A B C for local, zonal, national;  
        if($seller_info['state_id'] == $user_info['state_id'])
        {
            if($seller_info['city_id'] == $user_info['city_id'])
            {
                $zone = 'A';
            }
            else
                $zone = 'B';
        }
        else
        {
            $zone = 'C';
        }
        return $zone;
    }
    
    private function calculated_shipping_charges_for_delhivery($weight, $zone)
    {
        $zone = strtoupper($zone);
        
        if($weight <= 500)
        {
            $shipping_fee_local     = 30;
            $shipping_fee_zonal     = 40;
            $shipping_fee_national  = 45;
        }
        
        else if($weight > 500 && $weight <= 3000)
        {
            $modulus  = ($weight)%(500);
            $reminder = floor(($weight)/(500));
            
            if($modulus == 0)
            {
                $reminder  = ($reminder)-1;          
            }
            else
            {
                $reminder = $reminder;
            } 
        
            $shipping_fee_local    = ((28)*($reminder))+(30);
            $shipping_fee_zonal    = ((35)*($reminder))+(40);
            $shipping_fee_national = ((40)*($reminder))+(45);
            
        }
        else if($weight > 3000 && $weight <= 20000)
        {  
            $weight    = (($weight)-3000);
            $modulus   = ($weight)%(1000);    
            $reminder  = floor(($weight)/(1000));
            
            if($modulus == 0)
            {
                $reminder  = $reminder;          
            }
            else
            {
                $reminder  = $reminder+1;
            } 

            $shipping_fee_local    = ((23)*($reminder))+(140);
            $shipping_fee_zonal    = ((30)*($reminder))+(175);
            $shipping_fee_national = ((35)*($reminder))+(200);
        }
        else if($weight >20000)
        {
            $weight     = (($weight)-20000);
            $modulus    = ($weight)%(1000);    
            $reminder   = floor(($weight)/(1000));
            
            if($modulus == 0)
            {
                $reminder = $reminder;          
            }
            else
            {
                $reminder = $reminder+1;
            } 

            $shipping_fee_local      = ((6)*($reminder))+(531);
            $shipping_fee_zonal      = ((12)*($reminder))+(685);
            $shipping_fee_national   = ((15)*($reminder))+(795);
        }
        
        switch ($zone) 
        {
            case "A"://local
                return $shipping_fee_local;
                break;
            case "B"://zonal
                return $shipping_fee_zonal;
                break;
            case "C"://national
                 return $shipping_fee_national;
                break;
            default:       
        }
    }
    
    private function rto_charges_for_delhivery($order_zone, $order_weight)
    {
        $order_zone = strtoupper($order_zone);
        $weight     = number_format($order_weight / 1000, 2);
        $weight     = ($weight <= 1) ? 1 : $weight;
        $order_rto_charges = 0;
       
        switch ($order_zone) 
        {
            case "A":
                 $order_rto_charges = ($weight*20);
                break;
            case "B":
                 $order_rto_charges = ($weight*25);
                break;
            case "C":
                 $order_rto_charges = ($weight*30);
                break;
            default:       
        }
        /*$tax   = ($order_rto_charges * $this->tax_rate)/100;
        $total_rto_charges = ($order_rto_charges + $tax);*/
        return $order_rto_charges;
    }
} 
?>