<?php //echo "<pre>";print_r($faq_details);exit;?>
<!doctype html>
<html>
<title>BulknMore||Faq's</title>
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
       <div class="col_content">
        	<div class="content_area">
            	<h2 class="title">FAQ's</h2>
                <div class="accordion">
 <!--====================== My Account & My Orders===================================================-->               
                <h3>My Account & My Orders</h3>
                <?php foreach($faq_details as $faq):?>
                	<?php if($faq->faq_category_id==6){?>
                    <div class="accordion-section">
                     <a href="#faq<?php echo $faq->faq_id;?>" class="accordion-section-title"><?php echo $faq->faq;?></a>
                        <div id="faq<?php echo $faq->faq_id;?>" class="accordion-section-content"><?php echo $faq->answer;?></div>

                    </div>
                    <?php }?>
                    <?php endforeach;?>
<!--====================== My Account & My Orders================================================-->

<!--====================== Shiping===================================================================-->
                    <h3>Shopping</h3>
                <?php foreach($faq_details as $faq):?>
                    <?php if($faq->faq_category_id==7){?>
                    <div class="accordion-section">
                        <a href="#faq<?php echo $faq->faq_id;?>" class="accordion-section-title"><?php echo $faq->faq;?></a>
                        <div style="display:none;" id="faq<?php echo $faq->faq_id;?>" class="accordion-section-content"><?php echo $faq->answer;?></div>
                    </div>
                    <?php }?>
                    <?php endforeach;?>
<!--====================== Shiping===================================================================-->

<!--====================== Sell on Flipkart============================================================-->
                    <h3>Sell on BulknMore</h3>
                <?php foreach($faq_details as $faq):?>
                    <?php if($faq->faq_category_id==8){?>
                    <div class="accordion-section">
                        <a href="#faq<?php echo $faq->faq_id;?>" class="accordion-section-title"><?php echo $faq->faq;?></a>
                        <div style="display:none;" id="faq<?php echo $faq->faq_id;?>" class="accordion-section-content"><?php echo $faq->answer;?></div>
                    </div>
                    <?php }?>
                    <?php endforeach;?>
<!--====================== Sell on Flipkart==========================================================-->


<!--====================== Payments============================================================-->
                    <h3>Payments</h3>
                <?php foreach($faq_details as $faq):?>
                    <?php if($faq->faq_category_id==9){?>
                    <div class="accordion-section">
                        <a href="#faq<?php echo $faq->faq_id;?>" class="accordion-section-title"><?php echo $faq->faq;?></a>
                        <div style="display:none;" id="faq<?php echo $faq->faq_id;?>" class="accordion-section-content"><?php echo $faq->answer;?></div>
                    </div>
                    <?php }?>
                    <?php endforeach;?>
<!--====================== Payments==========================================================-->

<!--====================== Wallet============================================================-->
                    <!--<h3>Wallet</h3>
                <?php foreach($faq_details as $faq):?>
                    <?php if($faq->faq_category_id==10){?>
                    <div class="accordion-section">
                        <a href="#faq<?php echo $faq->faq_id;?>" class="accordion-section-title"><?php echo $faq->faq;?></a>
                        <div style="display:none;" id="faq<?php echo $faq->faq_id;?>" class="accordion-section-content"><?php echo $faq->answer;?></div>
                    </div>
                    <?php }?>
                    <?php endforeach;?>-->
<!--====================== Wallet==========================================================-->


<!--====================== Gift Voucher============================================================-->
                    <!--<h3>Gift Voucher</h3>
                <?php foreach($faq_details as $faq):?>
                    <?php if($faq->faq_category_id==11){?>
                    <div class="accordion-section">
                        <a href="#faq<?php echo $faq->faq_id;?>" class="accordion-section-title"><?php echo $faq->faq;?></a>
                        <div style="display:none;" id="faq<?php echo $faq->faq_id;?>" class="accordion-section-content"><?php echo $faq->answer;?></div>
                    </div>
                    <?php }?>
                    <?php endforeach;?>-->
<!--====================== Gift Voucher==========================================================-->

<!--====================== Store Credit============================================================-->
                    <!--<h3>Store Credit</h3>
                <?php foreach($faq_details as $faq):?>
                    <?php if($faq->faq_category_id==12){?>
                    <div class="accordion-section">
                        <a href="#faq<?php echo $faq->faq_id;?>" class="accordion-section-title"><?php echo $faq->faq;?></a>
                        <div style="display:none;" id="faq<?php echo $faq->faq_id;?>" class="accordion-section-content"><?php echo $faq->answer;?></div>
                    </div>
                    <?php }?>
                    <?php endforeach;?>-->
<!--====================== Store Credit==========================================================-->

<!--====================== Order Status Credit========================================================-->
                    <!--<h3>Order Status Credit</h3>
                <?php foreach($faq_details as $faq):?>
                    <?php if($faq->faq_category_id==13){?>
                    <div class="accordion-section">
                        <a href="#faq<?php echo $faq->faq_id;?>" class="accordion-section-title"><?php echo $faq->faq;?></a>
                        <div style="display:none;" id="faq<?php echo $faq->faq_id;?>" class="accordion-section-content"><?php echo $faq->answer;?></div>
                    </div>
                    <?php }?>
                    <?php endforeach;?>-->
<!--======================Order Status Credit=======================================================-->

<!--====================== Shipping========================================================-->
                   <!-- <h3> Shipping</h3>
                <?php foreach($faq_details as $faq):?>
                    <?php if($faq->faq_category_id==14){?>
                    <div class="accordion-section">
                        <a href="#faq<?php echo $faq->faq_id;?>" class="accordion-section-title"><?php echo $faq->faq;?></a>
                        <div  style="display:none;" id="faq<?php echo $faq->faq_id;?>" class="accordion-section-content"><?php echo $faq->answer;?></div>
                    </div>
                    <?php }?>
                    <?php endforeach;?>-->
<!--======================Shipping=======================================================-->


<!--====================== Courier========================================================-->
                    <!--<h3> Courier</h3>
                <?php foreach($faq_details as $faq):?>
                    <?php if($faq->faq_category_id==15){?>
                    <div class="accordion-section">
                        <a href="#faq<?php echo $faq->faq_id;?>" class="accordion-section-title"><?php echo $faq->faq;?></a>
                        <div style="display:none;" id="faq<?php echo $faq->faq_id;?>" class="accordion-section-content"><?php echo $faq->answer;?></div>
                    </div>
                    <?php }?>
                    <?php endforeach;?>-->
<!--======================Courier=======================================================-->

<!--====================== Cancellations & Returns=====================================================-->
                    <!--<h3> Cancellations & Returns</h3>
                <?php foreach($faq_details as $faq):?>
                    <?php if($faq->faq_category_id==16){?>
                    <div class="accordion-section">
                        <a href="#faq<?php echo $faq->faq_id;?>" class="accordion-section-title"><?php echo $faq->faq;?></a>
                        <div style="display:none;" id="faq<?php echo $faq->faq_id;?>" class="accordion-section-content"><?php echo $faq->answer;?></div>
                    </div>
                    <?php }?>
                    <?php endforeach;?>-->
<!--======================Cancellations & Returns================================================-->

<!--====================== Product Review Creation Guidelines====================================-->
                    <!--<h3> Product Review Creation Guidelines</h3>
                <?php foreach($faq_details as $faq):?>
                    <?php if($faq->faq_category_id==17){?>
                    <div class="accordion-section">
                        <a href="#faq<?php echo $faq->faq_id;?>" class="accordion-section-title"><?php echo $faq->faq;?></a>
                        <div style="display:none;" id="faq<?php echo $faq->faq_id;?>" class="accordion-section-content"><?php echo $faq->answer;?></div>
                    </div>
                    <?php }?>
                    <?php endforeach;?>-->
<!--======================Product Review Creation Guidelines===================================-->
                    
                </div>
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
