<!doctype html>
<html>
<title><?php echo $page_details->page_title;?></title>
<!--=============Top Header=============-->
<?php $this->load->view('shared/viw_header');?>
<!--==============end header=================-->
<body>
<!--=============Top navigaion bar =============-->
<?php $this->load->view('shared/viw_navigaion_bar');?>
<!--==============end navigaion bar=================-->
<!--===================Content Section Here -->
<section class="mid mid_space content_wrap">
    <div class="container">
        <!-- ====================Quick Link===================-->
        <?php $this->load->view('shared/viw_page_quick_link');?>
         <!-- ====================End Quick Link===================-->
        <!--  ======= page content ==== -->
        <?php echo $page_details->page_content;?>
        <!--=====end content==-->
    </div>
</section>
<!--===================Content Section Here -->
<!--=============footer Start=============-->
<?php $this->load->view('shared/viw_footer');?>
<!--==============end footer=================-->
</body>
<!--=============footer Start=============-->
<?php $this->load->view('shared/viw_links');?>
<!--==============end footer=================-->
</html>
