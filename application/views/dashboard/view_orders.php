<?php //echo "<pre>";print_r($orders);exit;?>
<!doctype html>
<html>
<!--=============Top Header=============-->
<?php $this->load->view('shared/viw_header');?>
<!--==============end header=================-->
<body>
<!--=============Top navigaion bar =============-->
<?php $this->load->view('shared/viw_navigaion_bar');?>
<!--==============end navigaion bar=================-->
<!--end header-->

<!--=============Top Side bar =============-->
<?php $this->load->view('dashboard/view_userAccount_sidebar');?>
<!--==============end Side bar=================-->
        <div class="col_content orders" id="dummy">
            <h3>My Orders</h3>
            <div class="order_main">
            </div>
            <div class="no_more" data-type="true">
            </div>
            <div class="loading-info"><img src="assets/images/b_loading.gif" style="width: 50px; height: 50px;" /></div>
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
<script src="assets/js/jquery.fancybox.js"></script>
<script src="assets/js/buyer_account.js?ver=1"></script>
<script src="assets/js/order_cancel.js?ver=1.2"></script>
<script src="assets/js/pagination_plugins.js" type="text/javascript"></script>

</html>