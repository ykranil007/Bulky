<!doctype html>
<html>
<!--=============Top Header=============-->
<?php $this->load->view('shared/viw_header');?>
<!--==============end header=================-->
<body>
<!--=============Top navigaion bar =============-->
<?php $this->load->view('shared/viw_navigaion_bar');?>

<!--==============end navigaion bar=================-->
<section class="mid mid_space">
	<div class="container">
    	<div class="wbox login_signup">
        	<div class="col-6">
            <form name="user_login" id="user_login" method="post" action="" autocomplete="off"> 
            	<div class="login">                
                    <h3>Login to your account</h3>
                    <p>We are happy to see you return! Please log in to continue.</p>
                    <div class="form-group">
                      <input type="email" name="login_email" id="login_email" required/>
                      <label for="input" class="control-label">Email</label><i class="bar"></i>
                      <span class="validation_error" id="login_email_error" style="display:none">Please Enter a Valid Email-ID</span>                      
                    </div>
                    <div class="form-group">
                      <input type="password" name="login_password" id="login_password" minlength="4" required/>
                      <label for="input" class="control-label">Password</label><i class="bar"></i>
                      <span class="validation_error" id="login_password_error" style="display:none">Please Enter Your Password</span>
                    </div>
                    <button type="button" name="user_login_btn" id="user_login_btn">Login</button>                    
                    <a href="forgot-password" class="forgot_btn">Forgot Password?</a>                                                          
                </div>
            </form>
            <div class="login_failure" id="login_failure"  style="display:none;"></div>
            <div class="login_success" id="login_success" style="display:none;"></div>
            
            <span class="seperator"><small>or</small></span>
            <form name="socialsignin" id="socialsignin">
                <div class="social_login">
                	<h3>Social login</h3>
                    <p>One-click sign in to Bulk via your social account</p>
                    <a href="<?php $login_url;?>" class="fb"><i class="fa fa-facebook" aria-hidden="true"></i>Sign in with Facebook</a>
                    <a href="<?php echo base_url('social-login/Google');?>" class="gplus"><i class="fa fa-google-plus" aria-hidden="true"></i>Sign in with Google</a>
                </div>
            </form>
            </div>
            <div class="col-6">
            	<div class="signup">
                  <!--============= Message Area ===================-->
                  <div class="register_msg success" id="register_success" style="display:none"><i class="fa fa-close close_notification"></i></div>
                  <div class="register_msg error" id="register_error" style="display:none"><i class="fa fa-close close_notification"></i></div>
                  <!--========== End of Message Area ==============-->
                  <form name="otp_verification" id="otp_verification" method="post" action="" style="display:none" autocomplete="off">
                    <div class="form-group">
                      <input type="text" name="verification" id="verification" maxlength="4" required/>
                      <label for="input" class="control-label">OTP</label><i class="bar"></i>
                      <span class="validation_error" id="verification_error"></span>
                    </div>
                     <button type="button" name="user_otp_btn" id="user_otp_btn" >Verify</button> 
                  </form>
                  <form name="user_registration" id="user_registration" method="post" action="">
                    <h3>Not a user yet?</h3>
                    <p>Create an account! It's quick, free and gives you access to special features.</p>                    
                    <div class="form-group">
                      <input type="text" name="firstname" id="firstname" required/>
                      <label for="input" class="control-label">First Name</label><i class="bar"></i>
                      <span class="validation_error" id="firstname_error"></span>
                    </div>
                    <div class="form-group">
                      <input type="text" name="lastname" id="lastname"  required/>
                      <label for="input" class="control-label">Last Name</label><i class="bar"></i>
                      <span class="validation_error" id="lastname_error"></span>
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
                      <label for="input" class="control-label">Conform Password</label><i class="bar"></i>
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
                    <div class="checkbox">
                      <label>
                        <input type="checkbox" name="terms" id="terms" checked="checked" /><i class="helper"></i>By Signing up you will agree <a href="privacy-policy">Privacy Policy</a> and <a href="terms-of-use">Terms of Conditions</a>
                        <span class="validation_error terms_error" id="terms_error"></span>
                      </label>
                    </div>
                    <button type="button" name="btn_create_user" id="btn_create_user">Sign Up</button>
                </form>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
 function checkEnter(e){
 e = e || event;
 var txtArea = /textarea/i.test((e.target || e.srcElement).tagName);
 return txtArea || (e.keyCode || e.which || e.charCode || 0) !== 13;
}
document.getElementById('otp_verification').onkeypress = checkEnter;
 </script>
<!--end middle-->
<!--=============footer Start=============-->
<?php $this->load->view('shared/viw_footer');?>
<!--==============end footer=================-->
</body>
<!--=============footer Start=============-->
<?php $this->load->view('shared/viw_links');?>
<!--==============end footer=================-->
</html>
