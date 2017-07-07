<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Common 
{
	protected $ci;
	public function __construct()
	{
		$this->ci = & get_instance();
		$this->ci->load->model('Common_model');
	}
	// Function for generating random string
	public function generateRandomString($length = 10) 
	{
      return substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
	}
	public function generateRandomNo($length)
    {
        return substr(str_shuffle(str_repeat($x='0123456789', ceil($length/strlen($x)) )),1,$length);
    }
	public function GetTableRow($column_name, $tablename, $where)
	{
		return $this->ci->Common_model->GetTableRow($column_name, $tablename, $where);
	}
    
    // use for get delhivery logistic charges
    public function calculate_logistic_charges($product_ids,$delivery_id,$qty)
    {
        $all_logistic = $this->ci->Common_model->get_table_result('logistic_id, logistic_name, logistic_short_name','tblblk_logistics', array('logistic_status' => 1));
        $main_array = array();
        foreach ($all_logistic as $logistic) {
            $function_name = 'get_charges_for_' . $logistic->logistic_short_name;
            $main_array[] = array('logistic_id' => $logistic->logistic_id,'shipping_charges' => $this->$function_name($product_ids,$delivery_id,$qty));
        }
        return $main_array[0];
    }
    
    public function get_charges_for_delhivery($product_ids,$delivery_id,$qty)
    {
        $shipping_charge = 0;
        $weight = $this->get_order_weight($product_ids,$qty);        
        $zone   = $this->create_order_zone_for_B2B($delivery_id);        
        $shipping_charge = $this->calculated_shipping_charges_for_delhivery($weight, $zone);
        //echo $weight;
        return $shipping_charge;
    }
    
    public function get_order_weight($product_ids,$qty)
    {
        $tot_weight = 0;
        foreach($product_ids as $key=>$pro_id)
        {
            $order_weight_info = $this->GetTableRow('IFNULL(SUM(package_length*package_height*package_breadth),0) AS order_weight,IFNULL(SUM(package_weight),0) AS package_weight',
            'tblblk_product_additional_info',"product_id = $pro_id");
            //echo $order_weight_info->order_weight/5000;
            $order_weight = ($order_weight_info->order_weight / 5000) * 1000; // Convert KG to GM             
            $tot_weight += (($order_weight > $order_weight_info->package_weight)?$order_weight:$order_weight_info->package_weight * 1000) * $qty[$key];
        }
        return $tot_weight;
         
    }
    
    public function create_order_zone_for_B2B($delivery_id)
    {
        /*$where = "seller_id = (SELECT seller_id FROM tblblk_product WHERE product_id = ".$product_ids.")";
        $seller_pincode = $this->GetTableRow('pickup_pincode', 'tblblk_seller_kyc', $where);*/
        
        // Get user pincode
        $where = "delivery_id = ".$delivery_id." "; // (SELECT delivery_id FROM tblblk_product_orders WHERE order_id = ".$order_id.")
        $user_pincode = $this->GetTableRow('pincode', 'tblblk_users_delivery_address', $where);
        
        $where = "pincode = '".$user_pincode->pincode."'";
        $user_info = $this->GetTableRow('pincode, city_id, state_id', 'tblblk_delivery_pincode', $where);
        
        $where = "pincode = '302022'"; //'".$seller_pincode->pickup_pincode."'";
        $seller_info      = $this->GetTableRow('pincode, city_id, state_id', 'tblblk_delivery_pincode', $where);
        return $this->get_order_zone_for_delhivery($seller_info, $user_info);
    }
    
    private function get_order_zone_for_delhivery($seller_info, $user_info)
    {
        $zone = '';
        //define Zone according A B C for local, zonal, national;  
        if($seller_info->state_id == $user_info->state_id)
        {
            if($seller_info->city_id == $user_info->city_id)
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
        else if($weight>20000)
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
}
?>