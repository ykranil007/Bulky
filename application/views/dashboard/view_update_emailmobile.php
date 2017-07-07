<!doctype html>
<html>
<!--=============Top Header=============-->
<?php $this->load->view('shared/viw_header');?>
<!--==============end header=================-->
<body>
<!--=============Top navigaion bar =============-->
<?php $this->load->view('shared/viw_navigaion_bar');?>
<!--==============end navigaion bar=================-->
<!--=============Top Side bar =============-->
<?php $this->load->view('dashboard/view_userAccount_sidebar');?>
<!--==============end Side bar=================-->


    <div class="col_content cng_email_mob">
        	<h3>Profile Settings</h3>
            <p>Enter the new Email ID / Mobile number you wish to associate with your BulknMore account. </p>
            <div class="form-horizontal">
            <label id="error_reporter" style="display:none; color:red;" ></label>
             <label id="success_reporter" style="display:none; color:green;" ></label>
                 <form  action="" method="" >
                    <div class="form-group">
                        <label>Email Address</label>
                        <div class="col-value">
                        	<input type="text" id="UserUpdateEmail" name="userUpdateEmail" style="display:none;"value="<?php  echo $user_details->email; ?>">
                        	<span class="value" id="email_span_val" style="display:inline-block;"><?php echo $user_details->email; ?></span>
                            <button type="button" class="edit_email_field_btn"  style="display:inline-block;">Edit</button>
                            <button type="button" class="email_add_cancel_btn" id="email_add_btn"style="display:none">Add</button>
                            <button type="button"  class="email_add_cancel_btn" id="email_cancel_btn" style="display:none" >Cancel</button>
                            <div  class="clear"></div>
                            <span id="email_add_error" name="email_add_error" style="display:none; color:red;"></span>
                      <!--- ========= for otp and password box ---->   
                     <div class="form-group">
                        <div class="col-value">
                        	<input type="text" id="user_email_otp" name="user_email_otp" style="display:none;" value="" placeholder="Enter verification code." maxlength="4">
                             <div class="clear"></div>
                            <span id="email_otp_error" style="display:none; color:red;"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-value">
                        	<input type="password" id="user_email_pass" name="user_mobile_pass" style="display:none;" value="" placeholder="Enter password.">
                            <span id="email_pass_error" style="display:none; color:red;"></span>
                        </div>
                    </div>
                    <!--- ========= End for otp and password box ---->        
                        </div>
                        <span id="userUpdateEmailError" class="validation-error-msg" style="display:none;"></span>
                    </div>
                    <div class="form-group">
                        <label>Mobile Number</label>
                        <div class="col-value">
                        	<input type="text" id="userupdateMobile" name="userupdateMobile" style="display:none;" value="<?php echo $user_details->mobile; ?>" maxlength="10">
                            
                        	<span class="value" id="mob_span_val" style="display:inline-block;"><?php echo $user_details->mobile; ?></span>
                            
                            <button type="button" class="edit_mob_field_btn" style="display:inline-block;">Edit</button>
                            <button type="button" class="cancel_mob_add_btn" id="mob_add_btn" style="display:none">Add</button>
                            <button type="button" class="cancel_mob_add_btn" id="mob_cancel_btn" style="display:none">Cancel</button>
                            <div  class="clear"></div>
                            <span id="mob_add_error" style="display:none; color:red;"></span>
                      <div class="form-group">
                        <div class="col-value">
                        	<input type="text" id="user_mobile_otp" name="user_mobile_otp" style="display:none;" value="" placeholder="Enter verification code." maxlength="4">
                             <div class="clear"></div>
                            <span id="otp_error" style="display:none; color:red;"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-value">
                        	<input type="text" id="user_mobile_pass" name="user_mobile_pass" style="display:none;" value="" placeholder="Enter password.">
                            <label id="pass_error" style="display:none; color:red;"></label>
                        </div>
                    </div>
                        </div>
                        <!-- <label id="mob_add_error" style="display:none; color:red;"></label>-->
                         <span id="userUpdateMobileError" class="validation-error-msg"></span>
                    </div>
                    <!--========= for otp and password field=============-->
                   
                     <!-- End for otp and password field------------>
                    <div class="col-offset"><button type="button" id="updateUserEmailMobile">Save Changes</button></div>
                </form>
            </div>
</section>

<!--=============footer Start=============-->
<?php $this->load->view('shared/viw_footer');?>
<!--==============end footer=================-->
</body>
<!--=============footer Start=============-->
<?php $this->load->view('shared/viw_links');?>
<script language="javascript" src="assets/js/update_email_mobile.js"></script>
<!--==============end footer=================-->
</html>