<!doctype html>
<html>
<title>BulknMore|| Contact Us</title>
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
        <?php //echo $page_details->page_content;?>
        <div class="col_content">
        	<div class="content_area contact-wrap">
            	<h2 class="title">Our Help Center</h2>
                <div class="accordion-wrap">
                  <h3><i class="fa fa-envelope" aria-hidden="true"></i>Contact Us</h3>                  
                  <div class="pane">
                    <div class="accordion-wrap sub">
                        <h3>Email: care@bulknmore.com</h3>
                    </div>
                  </div>
                  <h3><i class="fa fa-truck" aria-hidden="true"></i>Order</h3>                  
                  <div class="pane">
                  <?php foreach($contact_page_setting_details as $order):?>
                    <div class="accordion-wrap sub">
                    	<?php if($order->contact_category_id==1){?>
                      <h3><?php echo $order->question;?></h3>
                      <div class="pane"><?php echo $order->answer;?></div>
                      <?php }?>
                    </div>
                 <?php endforeach;?>                 
                 </div>
                  <!--end 1st que-->

                 <!-- =================Cancellations and Returns===============================--> 
                  <h3><i class="fa fa-share" aria-hidden="share"></i>Cancellations and Returns</h3>
                  
                  <div class="pane">
                  <?php foreach($contact_page_setting_details as $order):?>
                    <div class="accordion-wrap sub">
                      <?php if($order->contact_category_id==2){?>
                      <h3><?php echo $order->question;?></h3>
                      <div class="pane"><?php echo $order->answer;?></div>
                      <?php }?>
                        </div>
               <?php endforeach;?>
                 
                 </div>
           <!-- =================Cancellations and Returns===============================--> 
                  
                 
            <!-- =================Payment===============================--> 
                  <h3><i class="fa fa-inr" aria-hidden="true"></i>Payment</h3>
                  
                  <div class="pane">
                  <?php foreach($contact_page_setting_details as $order):?>
                    <div class="accordion-wrap sub">
                      <?php if($order->contact_category_id==3){?>
                      <h3><?php echo $order->question;?></h3>
                      <div class="pane"><?php echo $order->answer;?></div>
                      <?php }?>
                        </div>
               <?php endforeach;?>
                 
                 </div>
           <!-- =================Payment ===============================--> 

            <!-- =================Shopping===============================--> 
                  <!--<h3><i class="fa fa-shopping-bag" aria-hidden="true"></i>Shopping</h3>
                  
                  <div class="pane">
                  <?php //foreach($contact_page_setting_details as $order):?>
                    <div class="accordion-wrap sub">
                      <?php //if($order->contact_category_id==4){?>
                      <h3><?php //echo $order->question;?></h3>
                      <div class="pane"><?php //echo $order->answer;?></div>
                      <?php //}?>
                        </div>
               <?php //endforeach;?>
                 
                 </div>-->
           <!-- =================Shopping ===============================--> 


          
                </div>
                <!--end accordian-->
            </div>
        </div>
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
