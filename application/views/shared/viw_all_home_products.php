<!doctype html>
<html>
<!--=============Top Header=============-->
<?php $this->load->view('shared/viw_header');?>
<!--==============end header=================-->
<body>
<!--=============Top navigaion bar =============-->
<?php $this->load->view('shared/viw_navigaion_bar');?>
<!--==============end navigaion bar=================-->

<section class="mid mid_space products">
	<div class="container">
        <div class="view_all_product">
            <h2 id="product_title"></h2>
            <div class="product_list">

            </div>
        </div>
        <div class="no_more" data-type="false" style="text-align: center;">
        </div>
    </div>
	<div style="width: 100%;">
    	<div class="loader_slider"><img src="http://bulknmore.com/assets/images/b_loading.gif" alt=""></div>
    </div>
 </section>

<!--end middle-->
<!--=============footer Start=============-->
<?php $this->load->view('shared/viw_footer');?>
<!--==============end footer=================-->
</body>
<!--=============footer Start=============-->
<?php $this->load->view('shared/viw_links');?>
<script src="assets/js/home.js?ver=2"></script>
<!--==============end footer=================-->
</html>