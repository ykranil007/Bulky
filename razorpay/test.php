<?php
require('razorpay-php/Razorpay.php');
//use Razorpay\Api\Api;
$api = new Api('rzp_test_l6Iidcs8QJmpx6', 'vsYxmjgQyCRIBzqmKazPb2VO');
$payment = $api->payment->fetch('pay_6XJofEgdREpCaJ');
print_r($payment); 
?>