<?php defined('BASEPATH') or exit('No direct script access allowed');
class Order_cancellation
{
    protected $ci;
    public function __construct()
    {
        $this->ci = &get_instance();
        $this->ci->load->model('Common_model');
        $this->ci->load->model('Cancel_order_model');
    }

    // use for get delhivery logistic charges
    public function calculate_logistic_charges($order_info)
    {
        $all_logistic = $this->ci->Common_model->get_table_result('logistic_id, logistic_name, logistic_user_name, surface_token, express_token, logistic_short_name',
            'tblblk_logistics', array('logistic_status' => 1));
        $main_array = array();
        //print_r($all_logistic);exit;
        foreach ($all_logistic as $logistic) {
            $function_name = 'get_charges_for_' . $logistic->logistic_short_name;
            $main_array[] = array('logistic_id' => $logistic->logistic_id,
                    'logistic_charges' => $this->$function_name($logistic, $order_info));
        }
        return $main_array[0];
        //print_r($main_array);exit;
    }

    public function get_charges_for_delhivery($logistic_info, $order_info)
    {
        $url = DELHIVERY_LIVE_LOGISTIC_URL . 'kinko/api/invoice/charges/json/?';
        $parameter_array = array(
            'token' => $logistic_info->express_token, //($order_info->delivery_mode == 'E')?$logistic_info->express_token:$logistic_info->logistic_token,
            'gm' => $order_info->package_weight,
            /*'cod'   => '1000',*/
            'cl' => $logistic_info->logistic_user_name,
            'pt' => $order_info->payment_type,
            'o_pin' => $order_info->seller_pincode,
            'd_pin' => $order_info->delivery_pincode,
            'md' => 'E');
        if ($order_info->payment_type == 'cod' && !empty($order_info)) {
            $parameter_array['cod'] = $order_info->amount;
        }

        $url    .= http_build_query($parameter_array);
        $result = json_decode(http_call($url, 'GET', null, array()));
        $amount = $result->response->all_charges;
        return array(
            'shipping_charge' => ($amount->DL + $this->get_fuel_percentages($amount->DL, $amount->FS)),
            'cod_charge' => $amount->COD,
            'delivery_charge' => $result->response->delivery_charges);
    }
    private function get_fuel_percentages($delivery_charges, $percentages)
    {
        return ($delivery_charges * $percentages) / 100;
    }
    //order cancalletion in logistic after order is pack
    public function order_canlletion_by_logistic($order_info)
    {
        $logistic = $this->GetTableRow('logistic_id, logistic_name, logistic_short_name, surface_logistic_user_name, express_logistic_user_name, surface_token, express_token','tblblk_logistics', array('logistic_status' => 1, 'logistic_id' => $order_info->logistic_id));
        $function_name = 'canlletion_order_for_' . $logistic->logistic_short_name;
        return $this->$function_name($logistic, $order_info);
    }

    private function canlletion_order_for_delivery($order_info, $logistic_info)
    {
        $url = DELHIVERY_LIVE_LOGISTIC_URL . 'api/p/edit/';
        $parameter_array = array('waybill' => $order_info->waybill, 'cancellation' =>'true');

        $headers = array("authorization: Token " . ($order_info->delivery_mode == '2') ? $logistic_info->express_token : $logistic_info->surface_token); //"content-type: application/json"
        $post   = json_encode($parameter_array);
        $result = json_decode(http_call($url, 'POST', $post, $header));
        return $result;
    }
    
    //========================order return proccess start From Here================================
    public function order_return_proccess($user_id, $order_id, $product_id)
    {
        $order_info = $this->ci->Cancel_order_model->get_order_info($order_id, $user_id, $product_id);
        //print_r($order_info);exit;
        return $this->create_logistic_order($order_info, $user_id);
    }
    public function create_logistic_order($order_info, $user_id)
	{
	    $success_order_id_array = array(); 
		$final_order_info_array = array();
		$extra_info	            = array();
        $logistic_id   		    = $order_info['order_info']->logistic_id; 
		$logistic_short_name    = $order_info['order_info']->logistic_short_name; 
		$function_name          = "create_order_api_".$logistic_short_name;
		$order_id 	   		    = $order_info['order_info']->order_id;	
		$order_details 		    = $order_info['order_info'];
		$item_details  		    = $order_info['order_details'];
		return $this->$function_name($order_details, $item_details, $user_id);
	}// create logistic order function ends here
    
    public function create_order_api_delhivery($order_details, $item_details, $user_id)
	{
	   $logistic_user_name = ($order_details->order_delivery_type == 1)? $order_details->express_logistic_user_name: $order_details->surface_logistic_user_name;
	   $token           = ($order_details->order_delivery_type==1)? $order_details->express_token : $order_details->surface_token;
	   $url             = DELHIVERY_LOGISTIC_URL.'cmu/push/json/?token='.$token; // Making url from constant and token from database
	   $return_array    = array();
	   $order_id		= $order_details->order_id;
	   $params 	  	    = array(); // this will contain request meta and the package feed
	   $package_data 	= array(); // package data feed
	   $payment_mode    = trim($order_details->delhivery_payment_mode); // Getting payment mode for logistic
	   //--- Start: building the package feed
	   $total_price 	= $this->get_ordered_product_total_price($order_details, $item_details); // Getting total price of order
	   $waybill_number	= $this->ci->Cancel_order_model->get_waybill($order_details->logistic_id, $order_details->order_id);//Getting Waybill for order
	   $shipment[] = array(
	   					'waybill' 		  => $waybill_number, // Waybill number
	   					'name'	  		  => ucwords($order_details->Name), // consignee name
	   					'order'   		  => trim($order_details->unique_order_id), // client order number
	   					'products_desc'   => str_replace("'","",$this->get_ordered_product_name($item_details)),
	   					'order_date'   	  => date('c',strtotime($order_details->order_date)), //ToDo: ISO Format
	   					'payment_mode'    => $payment_mode,
	   					'total_amount'    => $total_price, // in INR
	   					'cod_amount'   	  => (strtolower($payment_mode)=='cod') ? $total_price : 0,//Amount to be collected, required for COD
	   					'add'   		  => $order_details->Address,  // consignee address
	   					'city'   		  => $order_details->City,
	   					'state'   		  => $order_details->State,
	   					'country'   	  => $order_details->Country,
	   					'phone'   		  => $order_details->Mobile,
	   					'pin'   		  => $order_details->Pincode,
	   					'quantity'   	  => $this->get_ordered_product_qty($item_details), //Total items Quanitity //quantity of goods, positive integer
	   					'seller_name'     => $order_details->merchant_name, //Name of seller
	   					'seller_add'   	  => $order_details->pickup_address,//$order_details->Merchant_Address, //Add of seller
	   					'seller_cst'      => $order_details->CST_NO, //CST Number of Seller
	   					'seller_tin'   	  => $order_details->seller_tin_number, //TIN number of seller
                        
	   					// Return fields where the package has to be returned in case not delivered
	   					'return_add'	  => $order_details->pickup_address,
	   					'return_city'     => $order_details->pickup_city,
	   					'return_country'  => $order_details->pickup_country,
	   					'return_name'     => $order_details->merchant_name,
						'return_phone'	  => $order_details->pickup_mobile,
	   					'return_pin'	  => $order_details->pickup_pincode,
	   					'return_state'	  => $order_details->pickup_state,
                        'package_type'    => 'pickup',
                        
	   				 );
	   // making pickup location array		
	   $pickup_location = array(
	   							'add' 	  => trim($order_details->pickup_address),
	   							'city' 	  => trim($order_details->pickup_city),
	   							'country' => trim($order_details->pickup_country), 
	   							'name'    => $logistic_user_name.'-JPR-'.$order_details->merchant_name, // Use client warehouse name
	   							'phone'   => $order_details->pickup_mobile,
	   							'pin' 	  => trim($order_details->pickup_pincode),
	   							'state'   => trim($order_details->pickup_state), 
	   						);
	   $package_data['shipments'] 		 = $shipment;
	   $package_data['pickup_location']  = $pickup_location;
	   $params['format'] 				 = 'json';
	   $params['data'] 				     = json_encode($package_data);
       //$params['package_type']         = 'pickup'; 
       //echo  (json_encode($package_data));exit;
	   $post_headers = array('Content-Type: application/x-www-form-urlencoded'); // Header for CURL
	   $post_data	 = http_build_query($params); // Data to be post via CURL
	   $result_json  = http_call($url, 'POST', $post_data, $post_headers); // Calling CURL for posting data on Delhivery
       print_r($result);
       $result = json_decode($result_json);
	   $return_status= false;
       $obj = new stdClass(); 
	   if(!empty($result))
	   {
            if(trim(strtolower($result->packages[0]->status)) == "success")
            {
                $return_status = true;
            }
            else
            {
                $return_status = false; // Status to be returned to create_logistic_order() function
				$obj->rmk	   = $result->rmk;
				$obj->comment  = "Failed: Error occured in Delhivery Return Order Creation API.";
            }
            
            $obj->order_id		   = $order_id;
			$obj->upload_wbn   	   = $result->upload_wbn;
			$obj->cod_amount_pkg   = $result->cod_amount;
			$obj->prepaid_count	   = $result->prepaid_count;
			$obj->pickups_count	   = $result->pickups_count;
			$obj->waybill	   	   = $result->packages[0]->waybill; 
			$obj->refnum	   	   = $result->packages[0]->refnum;
			$obj->remarks	  	   = $result->packages[0]->remarks;
			$obj->cod_amount	   = $result->packages[0]->cod_amount;
			$obj->payment	   	   = $result->packages[0]->payment; 
			$obj->full_response    = $result_json;
			$obj->date_added	   = date('Y-m-d H:i:s');
			$insert_response_array = $obj;
			$this->ci->Common_model->insert_record('tblblk_order_return_response', $insert_response_array); // Inserting response result			
		}
		return $return_status;
	}
    private function get_ordered_product_total_price($order_details, $item_details)
    {
    	$total_price = 0;
    	foreach($item_details as $key=>$row)
        {
    		$total_price = $total_price + (($row->ordered_qty * $row->order_item_price) + $row->vat_amt);
    	}
    	//$total_price += $order_details->delivery_charge; //Adding Delivery Charges
    	return $total_price;
    }
    private function get_ordered_product_name($item_details)
    {
    	$item_name = '';
    	foreach($item_details as $key=>$row)
        {
    		$item_name .= ($key==0) ? $row->item_name : ', '.$row->item_name;
    	}
    	return $item_name;
    }
    private function get_ordered_product_qty($item_details)
    {
    	$qty = 0;
    	foreach($item_details as $key=>$row)
        {
    		$qty = $qty + $row->ordered_qty;
    	}
    	return $qty;
    } 
    //========================End order return proccess Here=======================================
}
?>