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
 <div class="col_content orders">
        	<h3>Orders Detail</h3>
            <?php if(!empty($orders)) ?>
            <div class="order-dashboard-block">
            	<div class="order-dashboard-header clearfix">
                	<div class="order-id-block">
                    	<h4>order id <span><?php echo $order_details->order_id; ?></span></h4>
                        <p class="order-placed">Placed on <span><?php echo date('d-M-Y',strtotime($order_details->order_date)); ?></span></p>
                    </div>
                    <div class="order-amount">Total amount:<span> &#x20B9; <?php echo $order_details->total_price; ?></span> <a href="#track-items" class="btn track_btn fancybox">Track Item</a></div>
                    
                </div>
                <!--end order header-->
                <?php foreach($orders as $order): ?>
                <div class="product-info-wrapper clearfix">
                	<div class="thumb"><img src="<?php echo $image_path['product_image']?>seller_listing/<?php echo $order->image_name; ?>" alt=""></div>
                    <div class="product-desc">
                    	<h4><a href="<?php echo $order->category_url.'/'.$order->sub_category_url.'/'.$order->subtosub_category_url.'/'.$order->product_url.'/'.make_encrypt($order->product_id); ?>"><?php echo ucfirst(str_replace('_',' ', $order->item_name)); ?></a></h4>
                        <div class="size-block">
                        	<span class="color-block"><span class="light">Set Description:</span> <?php echo ucwords($order->set_description);?></span>
                            <?php if(!empty($order->size_name)) { ?>
                            <span class="divider">|</span>
                            <span class="color-block"><span class="light">Size:</span> <?php echo $order->size_name; ?></span>
                            <?php } ?>
                            <span class="divider">|</span>
                            <span class="color-block"><span class="light">Set:</span> <?php echo $order->quantity; ?></span>
                            <span class="divider">|</span>
                            <span class="color-block"><span class="light">Qty:</span> <?php echo ($order->quantity * $order->pack_of);?></span>
                            <span class="divider">|</span>
                            <span class="color-block"><span class="light">&#x20B9</span> <?php echo $order->price; ?></span>
                        </div>
                    </div>
                    <div class="deliver-time">
                        <?php foreach($order_details->track_status as $track) {}?>
                        <?php foreach($order_details->return_track_status as $return_track) {}?>
                        <?php if($order->order_item_status != 2) { ?>
                        <h6 class="green_text"><?php if($track->order_status == 1) { echo "PLACED";} else if($track->order_status == 2 ) { echo "CONFIRMED"; } else if($track->order_status == 3 ) { echo "PACKED"; } else if($track->order_status == 4) { echo "HAND-OVER"; } else if($track->order_status == 5) { echo "IN-TRANSIT"; } else if($track->order_status == 6 && $order->order_item_status != 3) { echo "DELIVERED"; } else if($track->order_status == 8) { echo "REACHED AT HUB"; } else if($track->order_status == 9) { echo "OUT FOR DELIVERY"; } else if($return_track->order_status == 1 && $order->order_item_status == 3) { echo "RETURN REQUESTED"; } else if($return_track->order_status == 2) { echo "RETURN CONFIRMED"; } else if($return_track->order_status == 3) { echo "RETURN IN-TRANSIT"; } else if($return_track->order_status == 4) { echo "RETURN COMPLETED"; }?></h6>
                        <?php if($order->order_item_status != 3 && $track->order_status != 7) { ?>
                        <p>on <?php echo date('D',strtotime($track->status_date));?> | <?php echo date('d-M-Y H:i',strtotime($track->status_date)); ?></p>
                        <?php } else { ?>
                        <p>on <?php echo date('D',strtotime($return_track->status_date));?> | <?php echo date('d-M-Y H:i',strtotime($return_track->status_date)); ?></p>
                        <?php } ?>
                        <?php } else { ?>
                        <h6 class="red_text"><strike><?php echo "CANCELLED"; ?></strike></h6>
                        <?php } ?>
                    </div>
                    <!--popup start-->
                    <div class="track-popup" id="track-items">
                    	<h4>Item tracking</h4>
                        <div class="tracking-header-block clearfix">
                        	<div>
                                <p>Item Name : <?php echo str_replace('_',' ', $order->item_name); ?></p>
                                <p>Set Description : <?php echo $order->set_description; ?></p>
                            </div>
                            <div>
                                <p>Order ID: <?php echo $order_details->order_id; ?></p>
                                <p>Placed on: <?php echo date('d-M-Y',strtotime($order_details->order_date)); ?></p>
                            </div>
                        </div>
                        <div class="schudule-wrapper">
                        	<div class="schudule-header clearfix">
                            	<div><p><strong>Delivery Status</strong></p></div>
                                <div><p><strong>Delivery Estimate:</strong> <?php echo date('d-M-Y',strtotime($order->exp_order_date)); ?></p></div>
                            </div>
                            <div class="tracking-status-block clearfix">
                            	<div class="product-track">
                                    <div class="tracking-arrow"><i class="fa fa-check-circle" aria-hidden="true"></i></div>
                                    <div class="order-detail">
                                        <p><span><?php echo date('d-M-Y H:i:s',strtotime($order_details->order_date)); ?></span></p>
                                        <p class="green_text">PLACED</p>
                                    </div>
                                </div>
                                <?php if($order_details->order_status == 7) { ?>
                                <div class="product-track">
                                    <div class="cancel-tracking-arrow"><i class="fa fa-check-circle" aria-hidden="true"></i></div>
                                    <div class="order-detail">
                                        <p><span><?php echo date('d-M-Y H:i:s',strtotime($order->cancel_date)); ?></span></p>
                                        <strike><p class="red_text">CANCELLED</p></strike>
                                    </div>
                                </div>
                                <?php } else { ?>
                                <?php foreach($order_details->track_status as $track) { ?>
                                <?php if($track->order_status == 6) { ?>
                                <div class="product-track">
                                    <div class="tracking-arrow"><i class="fa fa-check-circle" aria-hidden="true"></i></div>
                                    <div class="order-detail">
                                        <p><span><?php echo date('d-M-Y H:i:s',strtotime($track->status_date)); ?></span></p>
                                        <p class="green_text">DELIVERED</p>
                                    </div>
                                </div>
                                <?php } ?>
                                <?php if($track->order_status == 9) { ?>
                                <div class="product-track">
                                    <div class="tracking-arrow"><i class="fa fa-check-circle" aria-hidden="true"></i></div>
                                    <div class="order-detail">
                                        <p><span><?php echo date('d-M-Y H:i:s',strtotime($track->status_date)); ?></span></p>
                                        <p class="green_text">OUT FOR DELIVERY</p>
                                    </div>
                                </div>
                                <?php } ?>
                                <?php if($track->order_status == 8) { ?>
                                <div class="product-track">
                                    <div class="tracking-arrow"><i class="fa fa-check-circle" aria-hidden="true"></i></div>
                                    <div class="order-detail">
                                        <p><span><?php echo date('d-M-Y H:i:s',strtotime($track->status_date)); ?></span></p>
                                        <p class="green_text">REACHED AT HUB</p>
                                    </div>
                                </div>
                                <?php } ?>
                                <?php if($track->order_status == 5) { ?>
                                <div class="product-track">
                                    <div class="tracking-arrow"><i class="fa fa-check-circle" aria-hidden="true"></i></div>
                                    <div class="order-detail">
                                        <p><span><?php echo date('d-M-Y H:i:s',strtotime($track->status_date)); ?></span></p>
                                        <p class="green_text">IN-TRANSIT</p>
                                    </div>
                                </div>
                                <?php } ?>
                                <?php if($track->order_status == 4) { ?>
                                <div class="product-track">
                                    <div class="tracking-arrow"><i class="fa fa-check-circle" aria-hidden="true"></i></div>
                                    <div class="order-detail">
                                        <p><span><?php echo date('d-M-Y H:i:s',strtotime($track->status_date)); ?></span></p>
                                        <p class="green_text">HANDOVERED</p>
                                    </div>
                                </div>
                                <?php } ?>
                                <?php if($track->order_status == 3) { ?>
                                <div class="product-track">
                                    <div class="tracking-arrow"><i class="fa fa-check-circle" aria-hidden="true"></i></div>
                                    <div class="order-detail">
                                        <p><span><?php echo date('d-M-Y H:i:s',strtotime($track->status_date)); ?></span></p>
                                        <p class="green_text">PACKED</p>
                                    </div>
                                </div>
                                <?php } ?>
                                <?php if($track->order_status == 2) { ?>
                                <div class="product-track">
                                    <div class="tracking-arrow"><i class="fa fa-check-circle" aria-hidden="true"></i></div>
                                    <div class="order-detail">
                                        <p><span><?php echo date('d-M-Y H:i:s',strtotime($track->status_date)); ?></span></p>
                                        <p class="green_text">CONFIRMED</p>
                                    </div>
                                </div>
                                <?php } ?>
                                <?php } } ?> 
                            </div>
                            <div class="shipping-details-block clearfix">
                                <div class="shipping-address">
                                    <h5>Shipping to:</h5>
                                    <p class="tracking-details"><?php echo $order_details->name; ?><br><?php echo $order_details->address; ?>,<br><?php echo $order_details->city; ?>, <?php echo $order_details->state; ?>-<?php echo $order_details->pincode; ?></p>
                                </div>
                                <div class="shipping-items-track">
                                    <h5>Track item through SMS:</h5>
                                    <p class="tracking-details">You will receive SMS on the status of your item on<br>+91-<?php echo $order_details->mobile; ?></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--end popup-->
                </div>
                <?php endforeach ?>
                <!--end detail-->
                <div class="final-detials-block clearfix">
                  <div class="amount-block">
                    <h4 class="final-detials-heading"><span class="standard-price">&#x20B9; <?php echo $order_details->total_price; ?></span><span>Paid Amount</span></h4>
                    <ul class="price-details">
                      <li><span class="amount">&#x20B9; <?php echo $order_details->total_price - ($order_details->total_tax + $order_details->shipping_charge) + $order_details->voucher_amount; ?></span><span>Sub Total</span></li>
                      <li><span class="amount">&#x20B9; <?php echo $order_details->total_tax; ?></span><span>Total Tax</span></li>
                      <li><span class="amount">&#x20B9; <?php echo $order_details->shipping_charge; ?></span><span>Total Shipping Charge</span></li>
                      <?php if($order_details->voucher_amount > 0) { ?>
                        <li><span class="amount">&#x20B9; - <?php echo $order_details->voucher_amount; ?></span><span>Voucher Discount</span></li>
                      <?php } ?>
                      <li><span class="amount">FREE</span><span>Handling Charges</span></li>
                    </ul>
                  </div>
                  <div class="shipping-address-block">
                    <h4 class="final-detials-heading">SHIPPING  ADDRESS</h4>
                    <strong><?php echo $order_details->name; ?> <?php echo $order_details->mobile; ?></strong><br>
                    <?php echo $order_details->address; ?><br>
                    <?php echo $order_details->city; ?>, <?php echo $order_details->state; ?>-<?php echo $order_details->pincode; ?><br>
                   </div>
                  <div class="payment-mode-block">
                    <h4 class="final-detials-heading">PAYMENT MODE</h4>
                    <p><?php if($order_details->payment_type == 1) { echo "Cash on Delivery"; } elseif($order_details->payment_type == 2) { echo "Net Banking"; } elseif($order_details->payment_type == 3) { echo " Credit Card"; } elseif($order_details->payment_type == 4) { echo "Debit Card"; } elseif($order_details->payment_type == 5) { echo "Wallet"; } else { echo "EMI"; }  ?></p>
                  </div>
                </div>
                <!--end final detail-->
            </div>
            <!--end block-->
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
<!--=============footer Start=============-->