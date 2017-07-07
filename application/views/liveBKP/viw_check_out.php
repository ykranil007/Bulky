<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="shortcut icon" type="image/x-icon" href="<?php echo base_url();?>assets/images/title.png">
<title>BulkNmore | Secure Payment</title>

<!--[if lt IE 9]>
   <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
   <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

<!--CSS-->
<link href="<?php echo base_url();?>assets/css/reset.css" type="text/css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/css/style.css" type="text/css" rel="stylesheet">
<link href="<?php echo base_url();?>assets/css/responsive.css" type="text/css" rel="stylesheet">

</head>

<body class="checkout">
<header id="header">
	<div class="header_topbar">
    	<div class="container">
           
        </div>
    </div>
    <!--end header topbar-->
    <div class="header_mid clearfix">
    	<div class="container">
            <h1><a href="<?php echo base_url();?>"><img src="<?php echo $image_path['admin_image']?><?php echo $site_settings->logo;?>"></a></h1>            
        </div>
    </div>    
</header>
<!--end header-->
<section class="mid mid_space">
	<div class="container">
        <div class="checkout_cotent clearfix">
            <section class="checkout_step_container step1">
            	<?php if(!empty($user_info->user_id)) { ?>
                <div class="checkout_step_head" id="LoggedinIDHead">                    
                   
                    <h4><i class="material-icons">done</i> Your Login ID - <span><u><?php echo $user_info->email; ?></u></span></h4>
                    <div class="edit_step edit_left">
                        <button id="change_login_btn" type="button">Change Login</button>
                    </div>          
                </div>
                <div class="checkout_step_data" id="checkout_logged_in_data" style="display:none">
                     <div class="already_login">
                    	<p>You are already logged in as <span><u><?php echo $user_info->email; ?></u></span>, please <a href="user-logout">Click here</a> to Logout</p>
                    	<p style="color:red;">Please note that upon clicking "Logout" you will lose items in your cart and will be redirected to BulknMore Home Page.</p>
                    	<button type="button" id="logged_in_btn">Continue</button>
                    </div>
                </div>
                <?php } else { ?>
                <div class="checkout_step_head" id="LoginIDHead"> 
                	<h4>1. BEFORE YOU PLACE YOUR ORDER! > Sign In</h4>
                </div>                   
                <div class="checkout_step_data" id="checkout_login_data">
                        
                        <div class="login checkout_email">
                        <form name="socialsignin" id="socialsignin">
                            <div class="social_login">
                                <a href="<?php  echo get_facebook_url(true); ?>" class="fb"><i class="fa fa-facebook" aria-hidden="true"></i>Sign in with Facebook</a>
                                <a href="<?php echo base_url('social-login/Google');?>" class="gplus"><i class="fa fa-google-plus" aria-hidden="true"></i>Sign in with Google</a>
                            </div>
                        </form>
                             <!--============= Message Area ===================-->
                            <div class="register_msg success" id="login_success"  style="display:none"></div>
                            <div class="register_msg error" id="login_failure"  style="display:none"></div>
                            <!--========== End of Message Area ==============--> 
                            <h3>Get Started With Your Login Flow!</h3>                            
                            <div class="form-group" id="checkout_email">
                              <input type="text" name="login_email" id="login_email" autocomplete="off" required>
                              <label for="input" class="control-label">Email Or Mobile</label><i class="bar"></i>
                              <span class="validation_error" id="login_email_error" style="display:none">Please Enter a Valid Email-ID</span>
                            </div>
                            <button name="checkout_login_btn" id="checkout_login_btn" type="button">Continue</button>
                            <div class="form-group" id="checkout_password" style="display:none">
                              <input type="password" name="login_password" id="login_password" required>
                              <label for="input" class="control-label">Password</label><i class="bar"></i>
                              <span class="validation_error" id="login_password_error" style="display:none">Please Enter Your Password</span>
                            </div>
                            <button name="checkout_login_btn" id="checkout_signin_btn" type="button" style="display:none">SIGN IN</button>
                            <div class="form-group" id="checkout_user_mobile" style="display:none">
                                <input type="text" name="login_mobile" id="login_mobile" maxlength="10" required>
                                <label for="input" class="control-label">Mobile</label><i class="bar"></i>
                                <span class="validation_error" id="login_mobile_error" style="display:none">Please Enter Your Mobile</span>
                                <!--<div class="checkbox" >
                                  <label>
                                    <input type="checkbox" name="is_bulk_user" id="is_bulk_user"><i class="helper"></i>Signup as a Bulk User?
                                  </label>
                                </div>-->
                            </div>                            
                            <button name="checkout_login_btn" id="checkout_otp_btn" type="button" style="display:none">Send OTP</button>
                            <div class="form-group" id="checkout_user_otp" style="display:none">
                              <input type="text" name="login_otp" id="login_otp" maxlength="4" required>
                              <label for="input" class="control-label">OTP</label><i class="bar"></i>
                              <span class="validation_error" id="login_otp_error" style="display:none">Please Enter Your OTP</span>
                              <a href="javascript:void(0)" id="resend_checkout_otp" style="float: right;"><span>Resend OTP</span></a>
                            </div>
                            <button name="checkout_login_btn" id="checkout_verify_otp_btn" type="button" style="display:none">Verify</button>
                            <div class="form-group" id="checkout_new_password_form" style="display:none">
                              <input type="password" name="checkout_new_password" id="checkout_new_password" required>
                              <label for="input" class="control-label">Password</label><i class="bar"></i>
                              <span class="validation_error" id="checkout_new_password_error" style="display:none">Please Enter Your Password</span>
                            </div>
                            <div class="form-group" id="checkout_cnfm_password" style="display:none">
                              <input type="password" name="checkout_confirm_password" id="checkout_confirm_password" required>
                              <label for="input" class="control-label">Confirm Password</label><i class="bar"></i>
                              <span class="validation_error" id="checkout_cnfm_password_error" style="display:none">Please Enter Your Confirm Password</span>
                            </div>                          
                            <button name="checkout_register_btn" id="checkout_register_btn" type="button" style="display:none">Register</button>                      
                        </div>

                        <div class="seperator"><small>&</small></div>
                        <div class="advantage">
                            <h3>Advantages of Sign Up</h3>
                            <ul>
                                <li>
                                    <i class="fa fa-truck" aria-hidden="true"></i>
                                    <h4>Manage your Orders</h4>
                                    Easily Track Orders, Hassle free Returns
                                </li>                                
                                <li>
                                    <i class="fa fa-bell" aria-hidden="true"></i>
                                    <h4>Make Informed Decisions</h4>
                                    Get Relevant Alerts and Recommendations
                                </li>
                                <li>
                                    <i class="fa fa-heart" aria-hidden="true"></i>
                                    <h4>Engage Socially</h4>
                                    With wishlists, Reviews, Ratings
                                </li>
                            </ul>
                        </div>                   
                </div>
                <?php } ?>
            </section>    
            <!--end step1-->
            <section class="checkout_step_container step2">
                <?php if(!empty($delvry_ads)) { ?>
                <div class="checkout_step_head"  id="dlvry_ads">
                    <h4>2. Select Address</h4>                    
                </div>
                <?php } else if (!empty($edit_delvry)) { ?>
                <div class="checkout_step_head">
                    <h4>2. Edit Delivery Address</h4>                    
                </div>
                <?php } else { ?>
                <div class="checkout_step_head">
                    <h4>2. Delivery Address</h4>                    
                </div>
                <?php } ?>
                <div class="checkout_step_head" id="addrs_id" style="display:none">
                    <h4 id="checkoutaddrsid" ></h4>
                    <div class="edit_step edit_left" id="change_address_btn" style="display:none">
                        <button id="change_ads_btn" type="button">Change Address</button>
                    </div>                   
                </div>               
                <!--end trigger-->
                <?php if (!empty($user_info->user_id)) { ?>
                <div class="checkout_step_data" id="checkout_address_data">
                <?php } else { ?>
                <div class="checkout_step_data" id="checkout_address_data" style="display:none">
                <?php } ?>
                <?php if(!empty($delvry_ads)) { ?>
                    <div class="alert alert-success" style="display:none">
                        <div class="alertTextbtnClick" style="margin-left:40%;color:red;">Sorry! delivery are not possible to the pincode provided.</div>
                    </div>
                <?php $delivery_id = 0; foreach($delvry_ads as $user) {  $delivery_id = $user->delivery_id;?>                    
                    <ul class="address_list clearfix">
                        <?php if($user->default_status == "Y") { ?>                      
                        <li class="selected">
                        <?php } else { ?>
                        <li>
                        <?php } ?>
                            <div class="detail" id="parent_<?php echo $user->delivery_id; ?>">                                                           
                                <h4><i class="material-icons">done</i><b><?php echo strtoupper($user->name);?></b></h4>
                                <div class="actbtn">
                                    <a href="<?php echo base_url('delete-delvry-ads/'.make_encrypt($user->delivery_id))?>"><i class="material-icons">delete</i></a>
                                    <a href="<?php echo base_url('edit-delvry-address/'.make_encrypt($user->delivery_id))?>" id="edit_<?php echo make_encrypt($user->delivery_id); ?>"><i class="material-icons">edit</i></a>
                                </div>
                                <address>
                                    <p><?php echo ucfirst($user->address); ?></p>
                                    <p><?php echo $user->pincode; ?>, <?php echo $user->city; ?></p>
                                    <p><?php echo ucfirst($user->state); ?></p>
                                    <?php if(!empty($user->landmark)) { ?>
                                    <p><?php echo ucfirst($user->landmark); ?></p>
                                    <?php } ?>                                  
                                </address>
                                <span class="tel"><b><?php echo $user->mobile; ?></b></span>
                                <?php if($user->default_status == "Y") { ?> 
                                <button class="delvry_address_id" id="<?php echo $user->delivery_id; ?>" name="continue_ads_button" type="button">Continue</button>
                                <?php } else { ?>
                                <button class="delvry_address_id" id="<?php echo $user->delivery_id; ?>" name="here_ads_button" type="button">Deliver Here</button>
                                <?php } ?>                           
                            </div>                                                       
                        </li>
                         <?php } ?>                       
                    </ul>      
                    <a href="#add_address" class="btn addnew_btn fancybox">+ Add New Address</a>
                    <div id="add_address">
                        <h5>Enter New Shipping Address</h5>
                        <form action="add_delvry_ads" method="post">                            
                            <p>
                            <label>Name</label>
                            <input type="text" name="name" required>
                            </p>
                            <p>
                            <label>Pincode</label>
                            <input type="text" name="pincode" id="pincodee" required>
                            </p>
                            <p>
                            <label>Address</label>
                            <textarea rows="3" name="address" minlength="15" required></textarea>
                            </p>
                            <p>
                            <label>City</label>
                            <input type="text" name="city" id="city_name" required>
                            </p>
                            <p>
                                <label>State</label>
                                <input type="text" name="state" id="state_name" required>
                            </p>
                            <p>
                                <label>Country</label>
                                <div class="inline"><strong>India</strong> (Service available only in India)</div>
                            </p>
                            <p>
                                <label>Phone</label>
                                <input type="tel" name="mobile" id="delivery_mobile" maxlength="10" minlength="10" required>
                            </p>
                            <p>
                                <label>Landmark</label>
                                <input type="text" name="landmark" placeholder="Optional">
                            </p>
                            <p class="checkbox">
                              <label>
                                <input type="checkbox" name="default" id="default" checked="checked" /><i class="helper"></i>Make This Your Default Address. 
                              </label>
                            </p>
                            <button type="submit" class="btn">Save & Continue</button>
                        </form>
                    </div>          
                    <?php } else if (!empty($edit)) { ?>
                    <!-- address form-->                  
                    <div class="col-6">
                        <div class="form-horizontal">
                             <form action="<?php echo base_url('update-ads').'/'.make_encrypt($edit->delivery_id);?>" method="post">
                                <div class="form-group">
                                    <label>Name</label>
                                    <div class="col-value"><input type="text" name="name" value="<?php echo $edit->name; ?>"></div>
                                </div>
                                <div class="form-group">
                                    <label>Pincode</label>
                                    <div class="col-value"><input type="text" name="pincode" value="<?php echo $edit->pincode; ?>"></div>
                                </div>
                                <div class="form-group">
                                    <label>Address</label>
                                    <div class="col-value"><textarea rows="3" name="address" minlength="15"><?php echo $edit->address; ?></textarea></div>
                                </div>
                                <div class="form-group">
                                    <label>City</label>
                                    <div class="col-value"><input type="text" name="city" value="<?php echo $edit->city; ?>"></div>
                                </div>
                                <div class="form-group">
                                    <label>State</label>
                                    <div class="col-value"><input type="text" name="state" value="<?php echo $edit->state; ?>"></div>
                                    <!--<select>
                                        <option><?php echo $edit->state; ?></option>
                                        <option>etc</option>
                                    </select>-->
                                </div>
                                <div class="form-group">
                                    <label>Country</label>
                                    <div class="inline"><strong>India</strong> (Service available only in India)</div>
                                </div>
                                <div class="form-group">
                                <label>Mobile</label>
                                    <div class="col-value"><input type="tel" name="mobile" id="delivery_mobile" maxlength="10" minlength="10" value="<?php echo $edit->mobile; ?>"></div>
                                </div>
                                <div class="form-group">
                                <label>Landmark</label>
                                    <div class="col-value"><input type="text" name="landmark" placeholder="Optional" value="<?php echo $edit->landmark; ?>"></div>
                                </div>
                                <div class="checkbox">
                                  <label>
                                    <input type="checkbox" name="default" id="default" checked="checked" /><i class="helper"></i>Make This Your Default Address. 
                                  </label>
                                </div>
                                <div class="col-offset"><button type="submit" class="btn">Save & Continue</button></div>
                            </form>
                        </div>
                    </div>             
                    <?php } else if(empty($delvry_ads)) { ?>
                        <div class="col-6" id="add-address">
                        <div class="form-horizontal">
                             <form action="add_delvry_ads" method="post">
                                <div class="form-group">
                                    <label>Name</label>
                                    <div class="col-value"><input type="text" name="name" required></div>
                                </div>
                                <div class="form-group">
                                    <label>Pincode</label>
                                    <div class="col-value"><input type="text" name="pincode" id="pincodee" required></div>
                                </div>
                                <div class="form-group">
                                    <label>Address</label>
                                    <div class="col-value"><textarea rows="3" name="address" minlength="15" required></textarea></div>
                                </div>
                                <div class="form-group">
                                    <label>City</label>
                                    <div class="col-value"><input type="text" name="city" id="city_name" required></div>
                                </div>
                                <div class="form-group">
                                    <label>State</label>
                                    <div class="col-value"><input type="text" name="state" id="state_name" required></div>
                                    <!--<select>
                                        <option><?php echo $edit->state; ?></option>
                                        <option>etc</option>
                                    </select>-->
                                </div>
                                <div class="form-group">
                                    <label>Country</label>
                                    <div class="inline"><strong>India</strong> (Service available only in India)</div>
                                </div>
                                <div class="form-group">
                                <label>Mobile</label>
                                    <div class="col-value"><input type="tel" name="mobile" id="delivery_mobile" maxlength="10" minlength="10" required></div>
                                </div>
                                <div class="form-group">
                                <label>Landmark</label>
                                    <div class="col-value"><input type="text" name="landmark" placeholder="Optional"></div>
                                </div>
                                <div class="checkbox">
                                  <label>
                                    <input type="checkbox" name="default" id="default" checked="checked" /><i class="helper"></i>Make This Your Default Address. 
                                  </label>
                                </div>
                                <div class="col-offset"><button type="submit" class="btn">Save & Continue</button></div>
                            </form>
                        </div>
                    </div>          
                    <?php }  ?>
                    <!--end address form-->
                </div>
                <!--end data-->
            </section>    
            <section class="checkout_step_container step3">
                <?php if(!empty($cart)) { ?>
                <div class="checkout_step_head" >
                    <h4 id="done_summary" style="display:none"><i class="material-icons">done</i> Order Summary - &nbsp<span><?php echo count($cart) ?> Items </span><span> &nbsp Total : &#8377; <b><?php echo $cart_totals['cart_total']; ?></b></span></h4>
                    <h4 id="undone_summary">3. Order Summary - &nbsp<span><?php echo count($cart) ?> Items </span><span> &nbsp Total : &#8377; <b><?php echo $cart_totals['cart_total']; ?></b></span></h4>
                    <div class="edit_step edit_left" id="change_btn" style="display:none">
                        <button id="review_order_btn" type="button">Review Order</button>
                    </div>
                </div>
                <?php } ?>
                <?php if(!empty($cart)) { //echo "<pre>"; print_r($cart); exit; ?>
                <div class="checkout_step_data" id="checkout_order_data" style="display:none">
                    <div class="cart">
                        <table>
                            <thead>
                                <tr>
                                    <th scope="col">&nbsp;</th>
                                    <th scope="col">Item</th>
                                    <th scope="col">Set Qty</th>
                                    <th scope="col">Pieces</th>
                                    <th scope="col">Price/Piece</th>
                                    <!--<th scope="col">Delivery Details</th>-->
                                    <th scope="col">SubTotal (Ex Tax)</th>
                                    <th scope="col">Tax Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $charges=0; 
                                      foreach ($cart as $cartitem) 
                                      { 
                                        if(get_product_stocks(array('product_id'=>$cartitem->product_id,'product_url'=>$cartitem->product_url)) > 0) 
                                        {
                                            $charges += get_logistic_charges($cartitem->product_id,$delivery_id,$cartitem->qty)['shipping_charges'];
                                ?>
                                <tr>
                                    <td data-label="Product Image"><figure class="proimg mobile"><img src="<?php echo $image_path['product_image'];?><?php echo $cartitem->image;?>" alt=""></figure></td>
                                    <td data-label="Item">
                                        <div class="detail">                                            
                                            <h4><?php echo ucwords($cartitem->name);?></h4>
                                            <p><?php echo ucwords($cartitem->set_description);?></p>
                                        </div>
                                    </td>
                                    <td data-label="Qty">
                                        <input type="number" name="qty" value="<?php echo $cartitem->qty; ?>" disabled>
                                    </td>
                                    <td><?php echo ($cartitem->pack_of*$cartitem->qty);?></td>
                                    <td data-label="Price">
                                        <p class="standard-price">&#8377; <?php echo $cartitem->price; ?></p>
                                        <p class="selling-price">&#8377; <?php echo $cartitem->standard_price; ?></p>
                                        <p class="offer-save">Savings: &#8377; <?php echo $cartitem->standard_price - $cartitem->price; ?></p>
                                    </td>
                                    <td data-label="SubTotal"><div class="sub_total">&#8377; <?php echo $cartitem->price_set; ?></div></td>
                                    <td><?php echo $cartitem->vat_amount;?><a href="javascript:void(0)" id="<?php echo make_encrypt($cartitem->product_id);?>" class="remove"><i class="material-icons">cancel</i></a></td>
                                </tr>
                                <?php   } else { ?>                                     
                                    <td colspan="7" style="color: red; text-align:center"> Your Cart Item <span><i><?php echo ucwords($cartitem->name);?></span></i>   is Getting Out Of Stock !</td>
                                <?php  } } ?>                                
                            </tbody>
                        </table>
                    </div>
                    <!--end cart-->
                    <div class="cart_footer">
                        <div class="wbox">                            
                            <!--<div class="send_mail">Order Confirmation will be Sent to <strong><input type="text" value="9928728743"></strong></div>-->
                            <?php if($cart_totals['cart_total'] > 0) { ?> <button id="checkout_order_btn" type="button">Continue</button> <?php } ?>
                            <div class="text_right">
                            <div class="total">Total Amount: <span>&#8377; <?php echo $cart_totals['cart_total']; ?></span></div>
                            </div>
                        </div>
                    </div>
                    <!--end cart footer-->
                </div>
                <?php } ?>
                <!--end data-->
            </section> 
            <section class="checkout_step_container step4">
                <div class="checkout_step_head">
                    <h4><?php echo (empty($user_info->user_id))?"3":"4" ?>. Make Payment</h4>
                </div>
                <!--end trigger-->
                <div class="checkout_step_data clearfix" id="payment_data" style="display:none">
                    <div class="step4_left clearfix">
                    	<ul class="pay_tabs">               
                            <?php if(!empty($user_wallet)) { ?> <li><a href="#blkwlt">Bulk Wallet</a></li> <?php } ?>
                            <?php if($cancel_count <= 3) { ?>
                                <li id="codtab" <?php if(!empty($user_wallet)) { ?> style="display: none" <?php } ?> ><a href="#cod">Cash on Delivery</a></li>
                            <?php }?>                         
                            <li id="onpaytab"<?php if(!empty($user_wallet)) { ?> style="display: none" <?php } ?>><a href="#onpay">Online Payment</a></li>                         
                        </ul>
                        <div class="paytab_content">
                            <!-- By ANIL -->
                            <input type="hidden" id="user_id" value="<?php echo $user_info->user_id; ?>">  
                            <?php if(!empty($user_wallet)) { ?>
                            <div id="blkwlt" class="payinfo">                                  
                                <input type="hidden" id="total_price" value="<?php echo round($cart_totals['cart_total']); ?>"/>
                                <input type="hidden" id="wallet_price" value="<?php echo round($user_wallet); ?>"/>
                                <!--============= Message Area ===================-->
                                  <div class="register_msg success" id="otp_success"></div>
                                  <div class="register_msg error" id="otp_error" ></div>
                                  <!--========== End of Message Area ==============-->
                                <div class="bulk_wallet">
                                    <h5>Pay Through Bulk Wallet</h5>
                                    <div class="form-radio" id="wallet_radio">
                                        <div class="radio" >
                                          <label>
                                            <input type="radio" name="radio" class="wallet_radio"  value="yes"/><i class="helper"></i>Yes
                                          </label>                                          
                                        </div>
                                        <div class="radio">
                                          <label>
                                            <input type="radio" name="radio" id="wallet_radio_first" class="wallet_radio" checked="checked" value="no"/><i class="helper"></i>No
                                          </label>                                          
                                        </div>
                                    </div>
                                    <p>Available Balance: &#8377; <strong><?php  echo floor($user_wallet); ?></strong></p>
                                    <button type="button" id="wallet_pay_btn" value="">Pay <?php  echo $cart_totals['cart_total'] - $user_wallet; ?></button>
                                    <div class="note">Please note: You will be redirected to a secure payment gateway. By placing this order, you agree to the <a href="<?php echo base_url('terms'); ?>" target="_blank">Terms Of Use</a> and <a href="<?php echo base_url('privacy'); ?>" target="_blank">Privacy Policy</a> of BulknMore.com</div>
                                </div>                                
                                    <form id="verify_wallet_otp" class="verify_wallet_otp" method="post" style="display:none;">
                                    <div id="captcha_form" class="clearfix">
                                        <div class="verify_form clearfix">                                        
                                            <div>
                                                <h3>OTP</h3>
                                                <input type="text" name="order_otp_number" id="wallet_order_otp_number" placeholder="Enter OTP Code " maxlength="4">
                                                <span class="validation_error" id="wallet_otp_error"></span>
                                            </div>                                       
                                        </div>
                                        <div class="verify_text">
                                            <h4>Verify Order</h4>
                                            <p>Type the otp you receive on your mobile number.</p>                                        
                                        </div>
                                    </div>
                                        <button id="verify_wallet_otp_btn" type="button" style="margin-top:10px;">Verify & Place Order</button>
                                    </form>
                                    <form action="place-order" method="post" id="cod_order">
                                    <div id="place_wallet_order"  class="clearfix" style="display:none;">                                    
                                        <div class="verify_text">
                                            <h4>Confirm Order</h4>                                        
                                        </div>
                                        <input type="hidden" name="cod_type" value="5" >
                                        <input type="hidden" name="cod_delivery_id" id="cod_delivery_id" value="" >
                                        <input type="hidden" name="wallet_radio_status" id="wallet_radio_status" value="" >
                                        <button id="wallet_order_btn" type="submit" class="btn">Place Order</button>
                                    </div>
                                    </form>
                            </div>
                            <?php } ?>                          
                            <!-- By ANIL END-->
                            <div id="cod" value="cod" class="payinfo">
                              <!--============= Message Area ===================-->
                              <div class="register_msg success" id="register_success"></div>
                              <div class="register_msg error" id="register_error" ></div>
                              <!--========== End of Message Area ==============-->                              
                                <form id="order_otp" class="order_otp" action="" method="post">
                                <div id="captcha_form" class="clearfix">
                                    <div class="verify_form clearfix">
                                        <div>
                                            <h3>Mobile No.</h3>
                                            <input type="text" name="checkout_mobile" id="checkout_mobile" placeholder="Enter Mobile No." maxlength="10" />
                                            <span class="validation_error" id="checkout_mobile_error"></span>
                                        </div>                                                                              
                                    </div>
                                    <div class="verify_text">
                                        <h4>Verify Order</h4>
                                        <p>Enter 10-Digit mobile number for verify order.</p>
                                    </div>
                                </div>
                                    <button id="checkout_mobile_btn" type="button">Send OTP</button>
                                </form>
                                <form id="verify_order_otp" class="verify_order_otp" method="post" style="display:none;">
                                <div id="captcha_form" class="clearfix">
                                    <div class="verify_form clearfix">                                        
                                        <div>
                                            <h3>OTP</h3>
                                            <input type="text" name="order_otp_number" id="order_otp_number" placeholder="Enter OTP " maxlength="4">                                                       
                                            <span class="validation_error" id="checkout_otp_error"></span>
                                        </div>                                       
                                    </div>
                                    <div class="verify_text">
                                        <h4>Verify Order</h4>
                                        <p>Type the otp you receive on your mobile number.</p>                                        
                                    </div>
                                </div>
                                    <button id="verify_order_btn" type="button">Verify & Place Order</button>
                                </form>
                                <form action="place-order" method="post" id="cod_order">
                                <div id="place_order"  class="clearfix" style="display:none;">                                    
                                    <div class="verify_text">
                                        <h4>Confirm Order</h4>                                        
                                    </div>
                                    <input type="hidden" name="cod_type" value="1" >
                                    <input type="hidden" name="delivery_id" id="delivery_id" value="" >
                                    <button id="cod_order_btn" type="submit" class="btn">Place Order</button>
                                </div>
                                </form>                             
                            </div>
                            
                            <div id="onpay" class="payinfo">
                            	<button id="rzp-button1" type="button" style="margin-top:42px">Pay Now</button>
                                <p id="cod_alert" style="color: red;margin-top: 5%; display: none;">Sorry! COD delivery are not possible above 5000 to the choosing pincode by you.</p>
                            </div>                            
                        </div>
                    </div>
                    <div class="step4_right">
                    	<div class="cart_item_summary">
                            <?php if(!empty($cart_totals['cart_total'])) { ?>
                            <div class="wbox">
                                <dl>
                                  <dt>Sub Total:</dt>
                                  <dd><b>&#8377; <?php echo $cart_totals['sub_total']; ?> /-</b></dd>                                
                                  <?php if($cart_totals['total_vat'] > 0) {?>
                                      <dt>Total Taxes:</dt>
                                      <dd>&#8377; <?php echo number_format((float)$cart_totals['total_vat'], 2, '.', ''); ?></dd>
                                  <?php } ?>
                                  <?php if($charges > 0) {?>
                                      <dt>Delivery Charges:</dt>
                                      <input type="hidden" id="delivery_charge" value="<?php echo number_format((float)$charges, 2, '.', ''); ?>">
                                      <dd>&#8377; <?php echo number_format((float)$charges, 2, '.', ''); ?></dd>
                                  <?php } ?>                                
                                  <?php if($cart_totals['voucher_total'] > 0) {?>
                                      <dt>Voucher Discount:</dt>
                                      <dd>&#8377; -<?php echo $cart_totals['voucher_total']; ?></dd>
                                  <?php } ?>
                                  <?php if(round($cart_totals['cart_total']) - $cart_totals['cart_total'] > 0) {?>                         
                                      <dt>Rounded Off</dt>
                                      <dd>&#8377; <?php echo number_format((float)round($cart_totals['cart_total']) - $cart_totals['cart_total'],2,'.','')?></dd>
                                  <?php } ?>
                                  <dt>Amount Payable:</dt>
                                  <dd> &#8377; <b id="pro_final_price"><?php if(empty($user_wallet)) { 
                                                    echo number_format((float)round($cart_totals['cart_total']) + $charges, 2, '.', ''); 
                                                } else {
                                                  if($cart_totals['cart_total'] - $user_wallet > 0) { 
                                                    echo number_format(round($cart_totals['cart_total']) + $charges - $user_wallet, 2, '.', ''); 
                                                  } else { 
                                                    echo '0'; 
                                                  } 
                                                }?></b></dd>
                                </dl>
                                <div class="verify_form clearfix" style="display: none;margin-top: 5%;">
                                    <h7 class="verify_pan_text" style="margin-left: 20%;">Please provide PAN No for above 50k amount.</h7>                                    
                                    <div>
                                        <input type="text" name="bulk_user_pan" id="bulk_user_pan" placeholder="e.g. XXXX7811X" maxlength="10"/>
                                        <span class="validation_error" id="bulk_user_pan_error"></span>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
                <!--end data-->
            </section>   
        </div>
    </div>
</section>
<script>
 function checkEnter(e){
 e = e || event;
 var txtArea = /textarea/i.test((e.target || e.srcElement).tagName);
 return txtArea || (e.keyCode || e.which || e.charCode || 0) !== 13;
}
document.getElementById('verify_order_otp').onkeypress = checkEnter;
document.getElementById('verify_wallet_otp').onkeypress = checkEnter;
    document.getElementById('order_otp').onkeypress = checkEnter;
    
 </script>
<!--end middle-->
<footer id="footer">
	<div class="footer_top">
    	<div class="container">
            <div class="col-3">
                <h3>Quick <span>Links</span></h3>
                <ul class="bottom_nav">
                    <li><a href="#">Contact Us</a></li>
                    <li><a href="#">About Us</a></li>
                    <li><a href="#">Careers</a></li>
                    <li><a href="#">Stories</a></li>
                    <li><a href="#">Press</a></li>
                    <li><a href="#">Sell on Domain</a></li>
                </ul>
            </div>
            <div class="col-3">
                <h3>Our <span>Policies</span></h3>
                <ul class="bottom_nav">
                    <li><a href="#">Return Policy</a></li>
                    <li><a href="#">Refund Policy</a></li>
                    <li><a href="#">Shipping Policy</a></li>
                    <li><a href="#">Privacy Policy</a></li>
                    <li><a href="#">Terms Of Use</a></li>
                    <li><a href="#">Promotions - T&amp;C</a></li>
                </ul>
            </div>
            <div class="col-3">
                <h3>Help <span>Center</span></h3>
                <ul class="bottom_nav">
                    <li><a href="#">Payments</a></li>
                    <li><a href="#">Saved Cards</a></li>
                    <li><a href="#">Shipping</a></li>
                    <li><a href="#">Cancellation &amp; Returns</a></li>
                    <li><a href="#">FAQ</a></li>
                    <li><a href="#">Report Infringement</a></li>
                </ul>
            </div>
            <div class="col-3">
                <h3>Special <span>Offers</span> <small>Sign up to access our special offers</small></h3>
                <input type="text" value="" placeholder="Email Address">
                <input type="submit" value="Subscribe">
                <h3>Keep in <span>Touch</span></h3>
                <ul class="social">
                	<li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                    <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                    <li><a href="#"><i class="fa fa-youtube" aria-hidden="true"></i></a></li>
                    <li><a href="#"><i class="fa fa-google-plus" aria-hidden="true"></i></a></li>
                </ul>
                <a href="#" class="gplay"><img src="assets/images/google-play.png" alt=""></a>
                <a href="#" class="app_store"><img src="assets/images/app-store.png" alt=""></a>
            </div>
        </div>
    </div>
    <!--end footer top-->
    <div class="footer_mid">
    	<div class="container">
        	<div class="copyright">&copy; 2016 - 2017 Bulknmore.com</div>
            <!--<div class="pay_logos">
                <strong>Pay By:</strong>
                <img src="assets/images/visa.png" alt="">
                <img src="assets/images/master-card.png" alt="">
                <img src="assets/images/maestro.png" alt="">
                <img src="assets/images/american-express.png" alt="">
                <img src="assets/images/discover.png" alt="">
                <img src="assets/images/rupay.png" alt="">
                <img src="assets/images/net-banking.png" alt="">
                <img src="assets/images/cash-on-delivery.png" alt="">
                <img src="assets/images/easy-emi-option.png" alt="">
            </div>-->
            <div class="pay_logos">
                <strong> Policies:</strong>
                <a href="<?php echo base_url('return-policy');?>" target="_blank">Returns Policy </a>|
                <a href="<?php echo base_url('terms');?>" target="_blank">Terms of use </a>| 
                <a href="<?php echo base_url('privacy');?>" target="_blank">Privacy</a> |
                <strong>Need Help?</strong>
                <a href="<?php echo base_url('contact'); ?>" target="_blank">Contact Us</a>
            </div>
        </div>
    </div>
    <!--end footer mid-->
    
</footer>
<!--end footer-->
</body>
<!--JS-->
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script src="assets/js/jquery-1.11.3.min.js"></script>
<script src="assets/js/owl.carousel.js"></script>
<script src="assets/js/jquery.fancybox.js"></script>
<script src="assets/js/custom.js"></script>
<script src="assets/js/checkout.js?ver=1.4"></script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-85343888-1', 'auto');
  ga('send', 'pageview');

</script>
 </html>
 
