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

<div class="col_content cng_password">
        	<h3>Change Password</h3>            
            <form  action="" method="post" id="changePasswordForm" class="form-horizontal">                
                <div class="form-group">                
                 <!--<div id="pass_error_msg" class="pass-error-alert"></div>-->
                    <label>Old Password</label>
                    <div class="col-value"><input type="password" id="oldPassword" name="oldPassword" value="" ></span></div>
                </div>
                <div class="form-group">
                    <label>New Password</label>
                    <div  class="col-value"><input type="password" id="newPassword" name="newPassword" value=""><span class="validation_error" id="newPassword"></div>
                </div>
                <div class="form-group">
                    <label>Confirm New Password</label>
                    <div  class="col-value"><input type="password" id="retypePassword" name="retypePassword" value=""><span class="validation_error" id="retypePassword"></div>
                </div>
                <div id="changePasswordButton" class="col-offset"><button type="button">Save Changes</button></div>
                <div class="password_error" id="pass_error_msg"  style="display:none;"></div>
            </form>
        </div>
</section> 

<!--=============footer Start=============-->
<?php $this->load->view('shared/viw_footer');?>
<!--==============end footer=================-->
</body>
<!--=============footer Start=============-->
<?php $this->load->view('shared/viw_links');?>
<!--==============end footer=================-->
</html>