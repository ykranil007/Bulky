<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Razorpay
* 
* Author: Mohan Sharma
* 	 	  mohan.sharma@nimittasoft.com
*          
* Created:  10.21.2016  
* Description:  This is a Codeigniter library which allows you to communicate checkout.razorpay.com
* 
*/

class Razorpay {		
	public function __construct() {
		
		require_once APPPATH.'third_party/razorpay/Razorpay.php';
        //use Razorpay\Api\Api;
		$api = new Api(RAZORPAY_API, RAZORPAY_PASS);
		$CI  = & get_instance();
		$CI->razorpay = $api;
	}
}
?>
<?php
/*require('razorpay/Razorpay.php');
use Razorpay\Api\Api;
$api = new Api('rzp_test_l6Iidcs8QJmpx6', 'vsYxmjgQyCRIBzqmKazPb2VO');
$payment = $api->payment->fetch('pay_6XJofEgdREpCaJ');
print_r($payment);*/ 
?>