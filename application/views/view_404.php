<!doctype html>
<html>
<!--=============Top Header=============-->
<?php $this->load->view('shared/viw_header');?>
<?php $this->load->view('shared/viw_seo');?>
<!--==============end header=================-->
<body>
<!--=============Top navigaion bar =============-->
<?php $this->load->view('shared/viw_navigaion_bar');?>
<!--==============end navigaion bar=================-->

<section class="mid mid_space">
	<div class="container">
    	<div class="wrap_404">
        	<img src="<?php echo base_url();?>/assets/images/warning-error.png" alt="">
            <h2>Oops!</h2>
            <h3>404 Not Found</h3>
            <p>Sorry, an error has occured, Requested page not found! </p>
            <a href="<?php echo base_url(); ?>" class="btn"><i class="fa fa-home" aria-hidden="true"></i> Back to Home</a>
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