<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// Function for httpCall through CURL
function http_call($url, $method = 'GET', $post_data = null, $headers = array())
{
    //echo $url;exit;
	$ch = curl_init($url);
	if ($method == 'POST') {
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_POST, true);
	} elseif ($method != 'GET') {
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
	} else {
		curl_setopt($ch, CURLOPT_URL, $url);
	}
	if (!empty($headers))
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
	//url_setopt($ch, CURLOPT_TIMEOUT, 60);
	//curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
	$response = curl_exec($ch);
    //print_r($response);exit;
	// Set HTTP error, if any
	/*$lastError = array(
						'code' => curl_errno($ch),
						'message' => curl_error($ch),
						'httpCode' => curl_getinfo($ch, CURLINFO_HTTP_CODE)
					  );*/
		//print_r($this->lastError);exit;
	curl_close($ch);
	return $response;
}

function get_payment_type_by_payment_status($payment_status,$logistic_abbr_name)
{
	if(trim($logistic_abbr_name)=='delhivery')
	{
		$payment_status_arr = array('1'=>'COD','2'=>'Prepaid','3'=>'Prepaid','4'=>'Prepaid','5'=>'Prepaid');
		return $payment_status_arr[$payment_status];
	}
}

function create_cart_order($cart_info,$user_id, $formdata)
{
	$CI = &get_instance();
	$seller_array = array();
	foreach ($cart_info as $cart_item)
    {
        
        $seller_array[]  = $cart_item['seller_id'];
    }
    $seller_array = array_unique($seller_array);
    $seller_product_array = array();    
    foreach ($seller_array as $seller) 
    {
        $package_length       = 0.0;
        $package_breadth      = 0.0;
        $package_height       = 0.0;
        $order_total          = 0;

        $main_order_array = array();
        foreach ($cart_info as $cart_item) 
        {
            if($seller == $cart_item['seller_id'])
            {
                $package_length   += $cart_item['package_length'];
                $package_breadth  += $cart_item['package_breadth'];
                $package_height   += $cart_item['package_height'];
                $order_total      += ($cart_item['price'] * $cart_item['qty']);
            }
        }
        $obj = new stdClass();
        $package_weight           = ((($package_length * $package_breadth * $package_height) / 5000) * 1000);
        $obj = new stdClass();
        $obj->package_weight      = $package_weight;
        $obj->amount              = $order_total;
        $obj->seller_id           = $seller;
        $obj->payment_type        = $formdata['payment_mode'];
        $obj->seller_pincode      = $CI->common->GetTableRow('pickup_pincode','tblblk_seller_kyc',array('seller_id'=>$seller))->pickup_pincode;
        $obj->delivery_pincode    = $CI->common->GetTableRow('pincode','tblblk_users_delivery_address',array('delivery_id'=>$formdata['delivery_id'],'user_id'=>$user_id))->pincode;
        $seller_product_array[]   = $obj;
    }
    return $seller_product_array;

}

function create_cart_payment($seller_product_array, $cart_info)
{
	$CI = &get_instance();
	$json = $shipping_logistic = array();
	foreach ($seller_product_array as $record) 
    {
        $shipping_logistic[] = $CI->common->calculate_logistic_charges($record); 
    }
    $json['total_price']     = round(get_cart_total($cart_info));
    $json['shipping_charge'] = $shipping_logistic[0]['logistic_charges']['shipping_charge'];
    $json['cod_charge']      = round($shipping_logistic[0]['logistic_charges']['cod_charge']);
    $json['total_payable']   = round(get_cart_total($cart_info) + $shipping_logistic[0]['logistic_charges']['shipping_charge']);
    return $json;
}
 
?>