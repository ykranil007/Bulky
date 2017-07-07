<header id="header">
	<div class="header_topbar">
    	<div class="container">
            <div class="top_left">
                <ul>
                    <!--<li><a href="javascript:void(0)">Bazaar</a></li>-->
                    <!--<li><a href="javascript:void(0)">Shopping Offers</a></li>-->
                    <li><a href="<?php echo base_url('show-buyer-orders');?>">Track Order</a></li>
                    <li><a href="<?php echo base_url('contact');?>"> Customer Care</a></li>
                    <li><a href="http://seller.bulknmore.com/" target="_blank"> Sell On BulknMore</a></li>
                    <li><a href="javascript:void(0)" id="download_app" ><i class="material-icons">get_app</i>Download App</a></li>
                    <li><a href="#financial_signup_popup" class="financial_signup"><i class="fa fa-inr" aria-hidden="true"></i> BNM Finance</a></li>
                    <li><a href="https://www.gst.gov.in/" class="blink" target="_blank"><strong>GST REGISTRATION</strong></a></li>
                    <li><a href="javascript:void()" id="reg_franchise"><strong>FRANCHISE ENQUIRY</strong></a></li>
                    <!--<li><a href="javascript:void(0)">Collect Payments</a></li>  contact-us-->
                </ul>
            </div>
            <div class="top_right">
                <ul>
                    <li><a href="javascript:void(0)" id="bulknmore_email" ><i class="material-icons">email</i><?php echo $site_settings->email; ?></a></li>
                    <li><a href="tel:<?php echo $site_settings->phone; ?>"><i class="material-icons">call</i><?php echo $site_settings->phone; ?></a></li>                    
                    <li><a href="<?php echo base_url('user-wishlist'); ?>"><i class="material-icons">favorite</i>Wish List</a></li>
                    <?php if($user_info) { ?>
                    <li><a href="<?php echo base_url('account'); ?>"><i class="material-icons">person</i>Welcome <?php echo empty($user_info->first_name)?'Guest':ucfirst($user_info->first_name);?></a>
                        <ul class="submenu">                          
                          <li> <a href="<?php echo base_url('account'); ?>"> <i class="fa fa-cogs"></i> Your Account </a></li>
                          <li> <a href="show-buyer-orders"> <i class="fa fa-cube"></i> Your Order </a></li>                          
                          <li> <a href="user-wishlist"> <i class="fa fa-heart"></i> Wish List </a></li>
                          <li> <a href="user-wallet"> <i class="fa fa-money"></i> Wallet </a></li>
                          <li> <a href="<?php echo base_url('changepassword'); ?>"> <i class="fa fa-lock"></i> Change Password </a></li>
                          <li> <a href="<?php echo base_url('user-logout'); ?>"> <i class="fa fa-sign-out"></i> Logout </a></li>
                        </ul>
                    </li>
                    <?php } else { ?>
                    <li><a href="#login_signup_popup" id="login_signup" class="logsign"><i class="material-icons">person</i>Log In / Sign Up</a></li>
                    <?php } ?>
                </ul>
                <!--Financial Login popup-->
                	<div id="financial_signup_popup" class="financ_signup">
                    	<div class="get_start"><a href="javascript:void(0);" id="fn_get_started"><img src="<?php echo base_url().'admin/assets/images/'?>finance-getstart.jpg" /></a></div>
                        <div id="user_section" style="display:none;">
                            <div class="get_start_step2 buyer_started">
                	            <a href="http://www.fin.bulknmore.com" target="_blank"><img src="<?php echo base_url().'admin/assets/images/'?>retailer.jpg" /></a>
                            </div>
                            <div class="get_start_step2 seller_started">
                                <a href="http://www.seller.bulknmore.com" target="_blank"><img src="<?php echo base_url().'admin/assets/images/'?>wholesaler.jpg" /></a>
                            </div>
                        </div>                        
                        <!--<div class="financ_tab">
                            <ul class="fic_tab">
                                <li><a href="#buyer_login">Buyer Login</a></li>
                                <li><a href="#seller_login">Seller Login</a></li>
                            </ul>
                            <div class="financ_content">
                            	<div id="buyer_login" class="ficinfo">
                                	<form>
                                    	<div class="form-group">
                                          <input type="text" name="fic_email" required/>
                                          <label for="input" class="control-label">Email or Mobile</label><i class="bar"></i>
                                        </div>
                                        <div class="form-group">
                                          <input type="password" name="fic_password" minlength="4" required/>
                                          <label for="input" class="control-label">Password</label><i class="bar"></i>
                                        </div>
                                        <a href="forgot-password" class="forgot_btn">Forgot Password?</a>
                                        <button type="button" name="user_login_btn">Login</button>
                                    </form>
                                </div>
                                <div id="seller_login" class="ficinfo">ssdsd</div>
                            </div>
                        </div>-->
                    </div>
                <!--end Financial Login popup-->
                <!--login signup popup-->
                <div id="login_signup_popup" class="login_signup">
                    <div class="outer">
                        <div class="colleft">
                            <div class="space_wrap">
                                <h3>Login/Sign Up</h3>
                                <div class="login_register_modal">
                                    <ul>
                                        <li>
                                            <img src="assets/images/logsign-1.png">
                                            <strong>Manage Your Orders</strong>
                                            Track orders, manage cancellations & returns.
                                        </li>
                                        <li>
                                            <img src="assets/images/logsign-2.png">
                                            <strong>Shortlist Items You Love</strong>
                                            Keep items you love on a watchlist.
                                        </li>
                                        <li>
                                            <img src="assets/images/logsign-3.png">
                                            <strong>Awesome Offers Updates For You</strong>
                                            Be first to know about great offers and save.
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <!--end colleft-->
                        <div class="colmid">
                            <div class="login">
                                <div class="space_wrap">
                                    <div class="login_failure" id="login_failure"  style="display:none;"></div>
                                    <div class="login_success" id="login_success" style="display:none;"></div>
                                    <form name="user_login" id="user_login" method="post" action="" autocomplete="off">
                                        <div class="form-group">
                                          <input type="text" name="login_email" id="login_email" required/>
                                          <label for="input" class="control-label">Email or Mobile</label><i class="bar"></i>
                                          <span class="validation_error" id="login_email_error" style="display:none">Please Enter a Valid Email-ID</span>
                                        </div>
                                        <div class="form-group">
                                          <input type="password" name="login_password" id="login_password" minlength="4" required/>
                                          <label for="input" class="control-label">Password</label><i class="bar"></i>
                                          <span class="validation_error" id="login_password_error" style="display:none">Please Enter Your Password</span>
                                        </div>
                                        <a href="javascript:void(0);" class="otp_login_btn">Login With OTP</a>
                                        <a href="forgot-password" class="forgot_btn">Forgot Password?</a>
                                        <button type="button" name="user_login_btn" id="user_login_btn">Login</button>
                                        <button type="button" class="not_user_btn">Not a user yet? Sign up</button>
                                    </form>
                                    <div class="otp_login" style="display: none;">
                                        <div class="form-group">
                                              <input type="text" name="otp_login_email" id="otp_login_email" required/>
                                              <label for="input" class="control-label">Email or Mobile</label><i class="bar"></i>
                                              <span class="validation_error" id="otp_login_email_error" style="display:none">Please Enter a Valid Email-ID</span>
                                        </div>
                                        <a href="javascript:void(0);" class="forgot_btn login_email_or_mobile">Login With Email/Mobile</a>
                                        <button type="button" name="send_otp_login_btn" id="send_otp_login_btn">Send OTP</button>
                                        <button type="button" class="not_user_btn">Not a user yet? Sign up</button>
                                    </div>
                                    <div class="otp_login_verify" style="display: none;">
                                        <div class="form-group">
                                              <input type="text" name="otp_login_otp" id="otp_login_otp" maxlength="4" required/>
                                              <label for="input" class="control-label">OTP (4 digit)</label><i class="bar"></i>
                                              <span class="validation_error" id="otp_login_otp_error" style="display:none"></span>
                                        </div>
                                        <a href="javascript:void(0);" class="forgot_btn otp_login_resend_otp">Resend OTP?</a>
                                        <button type="button" name="validate_otp_login_btn" id="validate_otp_login_btn">Validate OTP</button>
                                    </div>                                  
                                    <span class="seperator"><small>or</small></span>
                                    <div class="social_login">
                                        <h3>Social login</h3>
                                        <p>one-click sign in to Bulk via your social account</p>
                                        <a href="<?php echo get_facebook_url(true);?>" class="fb"><i class="fa fa-facebook" aria-hidden="true"></i>Sign in with Facebook</a>
                                        <a href="<?php echo base_url('social-login/Google');?>" class="gplus"><i class="fa fa-google-plus" aria-hidden="true"></i>Sign in with Google</a>
                                    </div>
                                </div>
                            </div>
                            <!--end login-->
                            <div class="signup" style="display:none">
                                <div class="space_wrap">
                                    <!--============= Message Area ===================-->
                                    <div class="register_msg success" id="register_success" style="display:none"><i class="fa fa-close close_notification"></i></div>
                                    <div class="register_msg error" id="register_error" style="display:none"><i class="fa fa-close close_notification"></i></div>
                                    <!--========== End of Message Area ==============-->
                                    <form name="otp_verification" id="otp_verification" method="post" action="" style="display:none;" autocomplete="off">
                                        <div class="form-group">
                                          <input type="text" name="verification" id="verification" maxlength="4" required/>
                                          <label for="input" class="control-label">OTP</label><i class="bar"></i>
                                          <span class="validation_error" id="verification_error"></span>
                                        </div>
                                    <button type="button" name="user_otp_btn" id="user_otp_btn" >Verify</button> 
                                    <div class="text-center">
                                      <p>Did not receive a verfication code?</p>
                                      <a href="javascript:void(0)" id="resend_otp" >Re-send code</a>
                                      <span>|</span>
                                    <a href="javascript:void(0)" id="change_mobile_number" >Change mobile number</a>
                                    </div>
                                    </form>
                                    <div class="register_box" id="change_mobile" style="display:none;">
                                        <h3>Verify Your Mobile No</h3>
                                        <form name="otp_verify" id="new_otp_verify" method="post" action="" >
                                        <div class="row">                
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                  <input type="tel" id="new_mobile" name="new_mobile" maxlength="10" required/>
                                                  <label for="input" class="control-label">Mobile Number</label><i class="bar"></i>
                                                  <span class="validation_error" id="new_mobile_error"></span>
                                                </div>
                                            </div>
                                            <div class="col-md-12"><button type="button" id="send_new_otp_btn" class="btn register_now">Send OTP</button></div>
                                            <div class="clear"></div>  
                                        </div>
                                        </form>
                                    </div>
                                    <div id="upload_documents" style="display: none;">
                                      <h5>For Becoming Bulk User Upload PAN/TAN/UID.</h5>
                                      <form id="bulk_documnents" method="post">
                                      <div class="row">
                                          <div class="col-md-12">
                                              <div class="form-group">
                                                  <input type="file" name="bulk_user_document" id="bulk_user_document" required/>
                                                  <span class="validation_error" id="bulk_user_document_error"></span>
                                              </div>
                                          </div>
                                          <button type="submit" class="btn" id="upload_document" >Upload</button>
                                      </div>
                                      </form>
                                    </div>                          
                                    <form name="user_registration" id="user_registration" method="post" action="">
                                        <div class="form-group">
                                          <input type="text" name="firstname" id="firstname" required/>
                                          <label for="input" class="control-label">Name</label><i class="bar"></i>
                                          <span class="validation_error" id="firstname_error"></span>
                                        </div>
                                        <div class="form-group">
                                          <input type="email" name="email" id="email" required/>
                                          <label for="input" class="control-label">Email</label><i class="bar"></i>
                                          <span class="validation_error" id="email_error"></span>
                                        </div>
                                        <div class="form-group">
                                          <input type="password" name="password" id="password" required/>
                                          <label for="input" class="control-label">Password</label><i class="bar"></i>
                                          <span class="validation_error" id="password_error"></span>
                                        </div>
                                        <div class="form-group">
                                          <input type="password" name="confirmpassword" id="confirmpassword" required/>
                                          <label for="input" class="control-label">Confirm Password</label><i class="bar"></i>
                                          <span class="validation_error" id="confirmpassword_error"></span>
                                        </div>
                                        <div class="form-group">
                                          <input type="tel" name="mobile" id="mobile" maxlength="10" required/>
                                          <label for="input" class="control-label">Mobile</label><i class="bar"></i>
                                          <span class="validation_error" id="mobile_error"></span>
                                        </div>
                                        <div class="form-radio">
                                            <div class="radio">
                                              <label>
                                                <input type="radio" name="gender" checked="checked" value="male"/><i class="helper"></i>Male
                                              </label>
                                            </div>
                                            <div class="radio">
                                              <label>
                                                <input type="radio" name="gender" value="female"/><i class="helper"></i>Female
                                              </label>
                                            </div>
                                        </div>
                                        <!--<div class="checkbox">
                                          <label>
                                            <input type="checkbox" name="is_bulk_user" id="is_bulk_user"><i class="helper"></i>Signup as a Bulk User?
                                          </label>
                                        </div>-->
                                        <div class="checkbox">
                                          <label>
                                            <input type="checkbox" name="terms" id="terms" checked="checked" /><i class="helper"></i>By Signing up you will agree <a href="<?php echo base_url('privacy'); ?>" target="_blank">Privacy Policy</a> and <a href="<?php echo base_url('terms'); ?>" target="_blank">Terms of Conditions</a>
                                            <span class="validation_error terms_error" id="terms_error"></span>
                                          </label>
                                        </div>
                                        <button type="button" name="btn_create_user" id="btn_create_user">Sign Up</button>
                                        <button type="button" class="already_user_btn">Already User? Login</button>
                                    </form>
                                </div>
                            </div>
                            <!--end signup-->
                        </div>
                        <!--end colmid-->
                    </div>
                </div>
                <!--end login signup popup-->
            </div>
        </div>
    </div>
<script>
 function checkEnter(e){
 e = e || event;
 var txtArea = /textarea/i.test((e.target || e.srcElement).tagName);
 return txtArea || (e.keyCode || e.which || e.charCode || 0) !== 13;
}
document.getElementById('otp_verification').onkeypress = checkEnter;
</script>
    <!--end header topbar-->
    <div class="header_mid clearfix">
    	<div class="container">
            <h1><a href="<?php echo base_url();?>"><img src="<?php echo base_url().'assets/images/'.$site_settings->logo;?>" alt="bulknmore<?php echo $site_settings->logo;?>"></a></h1>
            <a href="javascript:void(0);" class="nav_trigger"><i class="material-icons">menu</i></a>
            <nav class="navigation">
    	<div class="container">
        	<ul>           
                <?php foreach($menu_bar['all_menus'] as $menu): ?>
            	<li class="mega_menu"><a href="<?php if(!empty($menu->category_url)) echo base_url().'products/'.$menu->category_url; ?>"><?php if(!empty($menu->category_name)) echo $menu->category_name;?><i class="material-icons">expand_more</i></a>                
                	<ul class="sub-menu">
                    <?php if(!empty($menu->sub_category)) {                        
                        foreach($menu->sub_category as $sub_menu):             
                         ?>
                    	<li><a href="<?php if(!empty($menu->category_url) && !empty($sub_menu->sub_category_url)) echo base_url().'products/'.$menu->category_url."/".$sub_menu->sub_category_url; ?>"><?php if(!empty($sub_menu->sub_category_name)) echo $sub_menu->sub_category_name;  ?></a>
                        	<ul>
                            <?php $limited_subtosub = array(); ?>
                            <?php if(!empty($sub_menu->subtosub_categorys)) { $limited_subtosub = array_slice($sub_menu->subtosub_categorys,0,13); 
                                foreach($limited_subtosub as $subtosub_menu):                            ?>
                            	<li><a href="<?php if(!empty($menu->category_url) && !empty($sub_menu->sub_category_url) && !empty($subtosub_menu->subtosub_category_url)) echo base_url().'products/'.$menu->category_url."/".$sub_menu->sub_category_url."/".$subtosub_menu->subtosub_category_url; ?>"><?php if(!empty($subtosub_menu->subtosub_category_name)) echo $subtosub_menu->subtosub_category_name;  ?></a></li>
                            <?php endforeach; } ?>
                            <?php if(count($limited_subtosub) >= 13) { ?>
                            <li><a class="more" href="<?php if(!empty($menu->category_url)) echo base_url().'products/'.$menu->category_url; ?>">More</a></li>
                            <?php } ?>                            
                            </ul>
                    <?php endforeach; } ?>
                        </li>                        
                    </ul>
                <?php endforeach; ?>
                </li>                
            </ul>
        </div>
    </nav>
    		<!--end main navigation desktop-->
            <div class="search_box">
                <input type="search" value="" name="keywords" id="keywords" placeholder="Search for a Product , Brand or Category"/>
                <button type="button" id="btn_keywords" value=""><i class="material-icons search_icon">search</i></button>
            </div>
            <a href="cart" class="cart_btn"><i class="material-icons">shopping_cart</i><small>Cart</small> <span><?php $count = $this->cart->contents(); if(!empty($count)) { echo count($this->cart->contents()); } else if(!empty($cart_info)) { echo count($cart_info); } else { echo "0"; }?></span></a>
        </div>
    </div>
    <!--end header mid-->
    <!-- for download app open fancy box -->
 <div id="download_app_btn">
 		<div class="send_link_wrap">
        <form id="send_link_form" method="post" action="<?php echo base_url('');?>">
      <div class="col-md-3">
      <input type="text"  class="form-control" maxlength="10" id="mobile_no" placeholder="e.g. 98XXXXXXXX" >
      <span style="display:none;color:red;" id="app_mobile_error"></span>
      <span style="display:none;color:green;" id="send_success"></span> 
      </div>
      <input type="button" id="send_app_link"value="Send Link">
     </form>
     </div>
 </div>
 <!-- END for download app open fancy box -->
 <!-- END for download app open fancy box -->
  <!-- =====for franchise=========== -->
  <div id="franchise_reg_btn" >
   <div class="alert alert-success" id="success_msg_franchise" style="display:none,color:green"></div>
  		<form id="submit-franchise-form" method="post" action="<?php //echo base_url('');?>">
        <div class="form-group">
          <input class="required" type="text" name="reg_name" id="reg_name" required>
          <label for="input" class="control-label">Name</label><i class="bar"></i>
        </div>
        <div class="form-group">
          <input class="required" type="text" name="reg_email" id="reg_email" required>
          <label for="input" class="control-label">Email</label><i class="bar"></i>
        </div>
     	<div class="form-group">
          <input class="required" type="tel" name="reg_mobile" id="reg_mobile" required>
          <label for="input" class="control-label">Mobile</label><i class="bar"></i>
        </div>
        <button type="button" class="btn btn-success" id="submit-franchise-btn">Submit</button>
        <span style="display:none;color:green;" id="send_success"></span> 
   	 </form>
 </div>
 <!-- ======end for franchise ===== -->
</header>
<!--mobile navigation-->
    <div class="offcanvas_nav">
        <a href="javascript:void(0);" class="close_menu"><i class="material-icons">clear</i><span>Menu</span></a>
        <div class="nav_outer">
            <ul class="acc_menu">
            <?php foreach($menu_bar['all_menus'] as $menu): ?>
                <li class="level-1"><a href="javascript:void(0);"><?php if(!empty($menu->category_name)) echo $menu->category_name;?><i class="material-icons">expand_more</i></a>
                    <ul class="sub_menu">
                    <?php if(!empty($menu->sub_category)) { foreach($menu->sub_category as $sub_menu): ?>
                        <li><a href="javascript:void(0);"><?php if(!empty($sub_menu->sub_category_name)) echo $sub_menu->sub_category_name;?><i class="material-icons">expand_more</i></a>
                            <ul class="sub_menu">
                            <?php $limited_subtosub = array(); ?>
                            <?php if(!empty($sub_menu->subtosub_categorys)) { $limited_subtosub = array_slice($sub_menu->subtosub_categorys,0,13); foreach($limited_subtosub as $subtosub_menu): ?>
                            	<li><a href="<?php if(!empty($menu->category_url) && !empty($sub_menu->sub_category_url) && !empty($subtosub_menu->subtosub_category_url)) echo base_url().'products/'.$menu->category_url."/".$sub_menu->sub_category_url."/".$subtosub_menu->subtosub_category_url; ?>"><?php if(!empty($subtosub_menu->subtosub_category_name)) echo $subtosub_menu->subtosub_category_name;  ?></a></li>
                            <?php endforeach; } ?>
                            <?php if(count($limited_subtosub) >= 13) { ?>
                            <li><a class="more" href="<?php if(!empty($menu->category_url)) echo base_url().'bulkshop/'.$menu->category_url; ?>">More</a></li>
                            <?php } ?>
                            </ul>
                        </li>
                        <?php endforeach;}?>
                    </ul>
                </li>
                <?php endforeach;?>
            </ul>
            <div class="more_link">
            	<h3>More Links</h3>
                <ul>
                	<li><a href="<?php echo base_url('show-buyer-orders');?>">Track Order</a></li>
                    <li><a href="<?php echo base_url('contact');?>"> Customer Care</a></li>
                    <li><a href="http://seller.bulknmore.com/" target="_blank"> Sell On BulknMore</a></li>
                    <li><a href="javascript:void(0)" id="download_app" ><i class="fa fa-download" aria-hidden="true"></i> Download App</a></li>
                    <li><a href="#financial_signup_popup" class="financial_signup"><i class="fa fa-inr" aria-hidden="true"></i> BNM Finance</a></li>
                    <li><a href="https://www.gst.gov.in/" target="_blank">GST Registration</a></li>
                    <li><a href="javascript:void()" id="mob_reg_franchise">FRANCHISE ENQUIRY</a></li>
                </ul>
            </div>
            <div class="user_acinfo">
            	<h3>Account Settings</h3>
                <ul>
                	<?php if($user_info) { ?>
                	<li><a href="<?php echo base_url('user-logout'); ?>"><i class="fa fa-user" aria-hidden="true"></i> <?php echo empty($user_info->first_name)?'Guest':ucfirst($user_info->first_name);?> <span>Signout</span></a></li>
                    <li><a href="<?php echo base_url('account'); ?>"><i class="fa fa-cog" aria-hidden="true"></i> Your Account <span>Manage</span></a></li>
                    <li><a href="show-buyer-orders"> <i class="fa fa-cube" aria-hidden="true"></i> Your Order <span>Manage</span></a></li>
                    <li><a href="user-wishlist"><i class="fa fa-heart" aria-hidden="true"></i> Wish List <span>Manage</span></a></li>
                    <li><a href="user-wallet"><i class="fa fa-money" aria-hidden="true"></i> Wallet <span>Manage</span></a></li>
                    <li><a href="<?php echo base_url('changepassword'); ?>"><i class="fa fa-lock" aria-hidden="true"></i> Change Password <span>Change</span></a></li>
                    <?php } else { ?>
                    <li><a href="javascript:void(0);" id="login_signup" class="login_signup_popup"><i class="fa fa-user" aria-hidden="true"></i> Log In / Sign Up <span>Login</span></a></li>
                    	<a href="#login_signup_popup" class="logsign" id="hidden"></a>
                    <?php } ?>
                </ul>
            </div>
        </div>
    </div>
<!--end mobile navigation-->