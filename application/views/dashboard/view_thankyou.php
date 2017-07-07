<!doctype html>
<html>
<!--=============Top Header=============-->
<?php $this->load->view('shared/viw_header');?>
<!--==============end header=================-->
<body>
<!--=============Top navigaion bar =============-->
<?php $this->load->view('shared/viw_navigaion_bar');?>
<!--==============end navigaion bar=================-->
<div class="container">
  <div class="thankyou_section wbox">
  	<h3> <?php if(isset($page_settings->page_heading))echo $page_settings->page_heading; ?></h3>
  
		<div class="thankyou_continue">
			<?php if(isset($page_settings->page_content)) echo $page_settings->page_content; ?>
			<a href="<?php echo base_url(); ?>"><button type="button" class="continue_btn">continue</button></a>
		</div>  
  </div>
</div>
<!--=============footer Start=============-->
<?php $this->load->view('shared/viw_footer');?>
<!--==============end footer=================-->
</body>
<!--=============footer Start=============-->
<?php $this->load->view('shared/viw_links');?>
<!--==============end footer=================-->
</html>