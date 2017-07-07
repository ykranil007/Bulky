<!doctype html>
<html>
<!--=============Top Header=============-->
<?php $this->load->view('shared/viw_header');?>
<!--==============end header=================-->
<body>
<!--=============Top navigaion bar =============-->
<?php $this->load->view('shared/viw_navigaion_bar');?>
<!--==============end navigaion bar=================-->
<section class="mid mid_space thnakyou_wrap">
	<div class="container">
    	<div class="wbox order_info">
        	<div class="util_icons">
            	<!--<a href="javascript:void(0)"><i class="fa fa-file-text" aria-hidden="true"></i>Email Invoice</a>
                <span>|</span>-->
                <a href="<?php echo base_url('contact-us');?>"><i class="fa fa-envelope" aria-hidden="true"></i>Contact Us</a>
                <span>|</span>
                <button onclick="window.print()"><i class="fa fa-print"></i> Print</button>
                <!--<button onclick="window.print()><a href="javascript:void(0)"><i class="fa fa-print" aria-hidden="true"></i>Print</a></button>-->
            </div>
            <div class="row clearfix">
                
            	<div class="thanks_msg">
                	<h3>Thank you for your order!</h3>
                    <p>Your order has been placed and is being processed. When the item(s)are shipped, you will receive an email with the details. You can track this order through <a href="<?php echo base_url('show-buyer-orders');?>">My order</a> page.</p>
                    <span class="bold_text"><i class="fa fa-check" aria-hidden="true"></i> &#x20B9 <?php echo $total_price;?></span>
                </div>
                <div class="address_info">
                    <?php if(!empty($delivery_address)) ?>
                	<h3><?php echo $delivery_address->name; ?><span> <?php echo $delivery_address->mobile; ?></span></h3>
                    <address><?php echo $delivery_address->address;?> <?php echo $delivery_address->city; ?> <?php echo $delivery_address->state; ?> - <?php echo $delivery_address->pincode; ?></address>
                    <div class="bm-alert-user"><i class="fa fa-truck" aria-hidden="true"></i>Your Complete Order Will be delivered by <?php echo date('d/M/Y',strtotime($shipping_time)); ?></div>                
                </div>
            </div>
            <a href="<?php echo base_url('show-buyer-orders');?>" class="infograph">You can now <span class="inf-track"><i class="fa fa-map-marker" aria-hidden="true"></i>Track</span>,<span class="inf-cancel"><i class="fa fa-times" aria-hidden="true"></i>Cancel</span>,<span class="inf-return"><i class="fa fa-arrow-down" aria-hidden="true"></i>Return</span>  ordered items from <span class="btn">My Order</span></a>
        </div>
        <!--end orderinfo-->
        <div class="wbox order_summary">            
        	<h3> YOUR ORDER SUMMARY <span><?php echo count($order_products); ?> Items</span></h3>
            <?php if(!empty($order_products)) ?>
            <?php foreach ($order_products as $key=>$valuee): ?>           
            <table>                
            	<thead>
                    <tr scope="col">
                        <th colspan="4">Order ID <a href="<?php echo base_url('show-buyer-orders');?>"><?php echo $valuee; ?></a></th>
                    </tr>
                </thead>    
            </table>
            <?php endforeach ?>
            
            <div class="bottom_summary clearfix">
            	<a href="<?php echo base_url();?>" class="btn">Continue Shopping</a>
                <div class="total">
                    Total <strong> &#x20B9 <?php echo $total_price; ?></strong>
                </div>
            </div>        
        </div>
    </div>
</section>
<!--end middle-->
<!--=============footer Start=============-->
<?php $this->load->view('shared/viw_footer');?>
<!--==============end footer=================-->
</body>
<!--=============footer Start=============-->
<?php $this->load->view('shared/viw_links');?>
<!--==============end footer=================-->
<script src="assets/js/order_cancel.js?v=1"></script>
</html>