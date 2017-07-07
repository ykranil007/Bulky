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
    	<div class="wbox forgot_password">
        	<div class="col-6">
            	<div class="login">
                        <h3>Forgot Password?</h3>                                             
                        <!--============= Message Area ===================-->
                        <div class="register_msg success" id="register_msg_success" style="display:none"><i class="fa fa-close close_notification"></i></div>
                        <div class="register_msg error" id="register_msg_error" style="display:none"><i class="fa fa-close close_notification"></i></div>
                        <!--========== End of Message Area ==============-->
                      <form name="forgotemail" id="forgotemail" method="post" action="" autocomplete="off">
                        <p>Don't Worry! Enter Your Email Address And We will Send You a OTP To Reset Your Password.</p>
                        <div class="form-group" id="forgotemailid">
                          <input type="email" name="forgot_email" id="forgot_email" required/>
                          <label for="input" class="control-label">Email</label><i class="bar"></i>
                           <span class="validation_error" id="forgot_email_error" ></span>
                        </div>           
                        <!--<div class="form-radio" id="forgotradio" style="display:none" >
                          <div class="radio">
                            <label>
                              <input type="radio" name="radiomail" checked="checked" value="email"/><i class="helper" ></i><h1 id="emailid"></h1>
                            </label>
                          </div>
                          <div class="radio">
                            <label>
                              <input type="radio" name="mobileno" value="mobile"/><i class="helper"></i><h1 id="mobileno"></h1>
                            </label>
                          </div>
                        </div><br> -->
                       <button type="button" name="forgot_pass_btn" id="forgot_pass_btn" >SEND</button>
                      </form>
                      <form name="forgototpcode" id="forgototpcode" method="post" action="" style="display:none">
                        <div class="form-group">
                          <input type="text" name="verification" id="verification_otp" maxlength="4" required/>
                          <label for="input" class="control-label">OTP</label><i class="bar"></i>
                          <span class="validation_error" id="forgot_verification_error"></span>
                        </div>
                        <button type="button" name="forgot_otp_btn" id="forgot_otp_btn">VERIFY</button>
                      </form>
                      <form name="forgotnewpassword" id="forgotnewpassword" method="post" action="" style="display:none" autocomplete="off">
                        <div class="form-group">                                      
                          <input type="password" name="newpassword" id="newpassword" required/>
                          <label for="input" class="control-label">Set New Password</label><i class="bar"></i>
                           <span class="validation_error" id="create_password_error"></span>
                        </div>
                        <button type="button" name="forgot_newpass_btn" id="forgot_newpass_btn">SUBMIT</button>                        
                      </form>
              </div>
            </div>
            <div class="col-6">
            	<div class="social_login">
                	<h3>Social login</h3>
                    <p>one-click sign in to Bulk via your social account</p>
                    <a href="<?php echo base_url('social-login/facebook');?>" class="fb"><i class="fa fa-facebook" aria-hidden="true"></i>Sign in with Facebook</a>
                    <a href="<?php echo base_url('social-login/Google');?>" class="gplus"><i class="fa fa-google-plus" aria-hidden="true"></i>Sign in with Google</a>
                </div>
            </div>
        </div>
    </div>
</section>
<!--end middle-->
<script>
 function checkEnter(e){
 e = e || event;
 var txtArea = /textarea/i.test((e.target || e.srcElement).tagName);
 return txtArea || (e.keyCode || e.which || e.charCode || 0) !== 13;
}
document.getElementById('forgotemail').onkeypress = checkEnter;
document.getElementById('forgototpcode').onkeypress = checkEnter;
document.getElementById('forgotnewpassword').onkeypress = checkEnter;
</script>
<!--=============footer Start=============-->
<?php $this->load->view('shared/viw_footer');?>
<!--==============end footer=================-->
</body>
<!--=============footer Start=============-->
<?php $this->load->view('shared/viw_links');?>
<!--==============end footer=================-->
</html>