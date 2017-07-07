<!doctype html>
<html>
<!--=============Top Header=============-->
<?php $this->load->view('shared/viw_header');?>
<!--==============end header=================-->
<body>
<!--=============Top navigaion bar =============-->
<?php $this->load->view('shared/viw_navigaion_bar');?>
<!--==============end navigaion bar=================-->
<section>
    <div class="col_content cng_profile">
        	<h3>Profile Settings</h3>
            <div class="bm-alert-user"><strong>Please Note:</strong> Your profile name can be changed only once after registration</div>
            <div class="form-horizontal">
                 <form>
                    <div class="form-group">
                        <label>Profile Name</label>
                        <div class="col-value"><input type="text" value="ram-9744"></div>
                    </div>
                    <div class="form-group">
                        <label>Make your wishlist public?</label>
                        <div class="col-value">
                        	<div class="form-radio">
                                <div class="radio">
                                  <label>
                                    <input type="radio" name="radio" checked="checked"/><i class="helper"></i>No
                                  </label>
                                </div>
                                <div class="radio">
                                  <label>
                                    <input type="radio" name="radio"/><i class="helper"></i>Yes
                                  </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Your Public Wishlist</label>
                        <div class="col-value">www.bulknmore.com/wishlist/ram-9744 </div>
                    </div>
                    <div class="form-group">
                        <label>Your Public Profile</label>
                        <div class="col-value"> www.bulknmore.com/user-profiles/ram-9744 </div>
                    </div>
                    <div class="col-offset"><button type="button">Save Changes</button></div>
                </form>
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