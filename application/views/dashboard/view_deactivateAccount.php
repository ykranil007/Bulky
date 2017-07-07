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
    <div class="col_content deactivate_ac">
    	<h3>Deactivate Account</h3>
        <div class="form-horizontal">
             <form action="" method="post">
                <div class="form-group">
                  <div id="deactivate_error_msg" class="pass-error-alert"></div>
                  <div id="deactivateSuccessMsg" class="alert-success-msg"></div>
                    <label>Email Address</label>
                    <div class="col-value">
                    	<p><?php echo $user_info->email; ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label>Mobile Number</label>
                    <div class="col-value">
                    	<p><?php echo $user_info->mobile; ?></p>
                    </div>
                </div>
                <div class="form-group">
                    <label>Password</label>
                    <div class="col-value">
                    	<input type="password" id="deactivate_account_password" name="deactivate_account_password" value="" placeholder="Enter Your Password" autocomplete="off" >
                    </div>
                </div>
                <div class="col-offset"><button id="deactivateAccountButton" type="button" class="btn">Confirm Deactivation</button></div>
                <!-- <div id="deactivateSuccessMsg" class="alert-success-msg"></div> -->
                <br>
            </form>
        </div>
      <div class="deactive_info">
       	<h4>When you deactivate your account</h4>
          <ul>
           	  <li>You are logged out of your BulknMore Account</li>
           	  <li>Your public profile on BulknMore is no longer visible</li>
           	  <li>Your reviews/ratings are still visible, while your profile information is shown as ‘unavailable’ as a result of deactivation.</li>
           	  <li>Your wishlist items are no longer accessible through the associated public hyperlink. Wishlist is shown as ‘unavailable’ as a result of deactivation</li>
           	  <li>You will be unsubscribed from receiving promotional emails from BulknMore</li>
           	  <li>Your account data is retained and is restored in case you choose to reactivate your account</li>
          </ul>
          <h4>How do I reactivate my BulknMore account?</h4>
          <p>Reactivation is easy.</p>
          <p>Simply login with your registered email id or mobile number and password combination used prior to deactivation. Your account data is fully restored. Default settings are applied and you will be subscribed to receive promotional emails from BulknMore.</p>
          <p>BulknMore retains your account data for you to conveniently start off from where you left, if you decide to reactivate your account.</p>
        </div>
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