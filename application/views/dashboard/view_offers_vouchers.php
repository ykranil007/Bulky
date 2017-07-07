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

<?php if(!empty($offer_list)) { ?>
<div class="col_content voucher">
	<h3>Shopping Vouchers</h3>
    <div class="voucher_table">
    <?php foreach($offer_list as $list): ?>
    	<div class="row">
        	<div class="v_label">Voucher Code</div>
            <div class="v_code"><?php echo $list->voucher_code; ?></div>
            <div class="v_amount">&#8377; <?php echo $list->voucher_value; ?> <small>Valid up to <?php echo $list->voucher_expiry; ?></small></div>
        </div>
    <?php endforeach; ?>
    </div>
</div>
<?php } else { ?>
<div style="text-align: center; margin-top: 20%;font-size: 20px;">
    <span>Sorry! You do not have any voucher right now. Come by later.</span>
</div>
<?php } ?>



</section>
<!--=============footer Start=============-->
<?php $this->load->view('shared/viw_footer');?>
<!--==============end footer=================-->
</body>
<!--=============footer Start=============-->
<?php $this->load->view('shared/viw_links');?>
<!--==============end footer=================-->
</html>