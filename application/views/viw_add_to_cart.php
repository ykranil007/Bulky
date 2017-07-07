<!doctype html>
<html>
<!--=============Top Header=============-->
<?php $this->load->view('shared/viw_header');?>
<!--==============end header=================-->
<body>
<!--=============Top navigaion bar =============-->
<?php $this->load->view('shared/viw_navigaion_bar');?>
<!--==============end navigaion bar=================-->
<section class="mid mid_space">
    <div class="container">
        <div class="cart clearfix">
            <h3><i class="material-icons">shopping_cart</i>Cart </h3>
           <div id="update_error_msg" style="color:red; display:none;"></div>
            <?php //$cart = $this->cart->contents(); ?>
            <?php if( !empty($cart)) { ?>                   
            <div class="wbox">             
                <table>                                 
                    <thead>
                        <tr>
                            <th scope="col">&nbsp;</th>
                            <th scope="col">Item (Total Weight: <?php echo $cart_totals['total_weight'] / 10000; ?> KG)</th>
                            <th scope="col">Set Qty</th>
                             <th scope="col">Pieces</th>
                            <th scope="col">Price/Piece</th>
                            <th scope="col">Price/Set</th>
                           <!-- <th scope="col">Delivery Details</th>-->
                            <th scope="col">Amount (Ex. Tax)</th>
                            <th scope="col">Tax Price</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php $total = $vat_total = $shipping_total = 0;
                        $i = 1; 
                        
                    foreach ($cart as $pro): //echo "<pre>"; print_r($pro);exit;?>
                    <tr>                            
                        <td data-label="Product Image"><figure class="proimg mobile"><a href="<?php echo base_url().$pro->category."/".$pro->sub_category."/".$pro->subtosub_category."/".$pro->product_url."/".make_encrypt($pro->id);?>" target="_blank"><img src="<?php echo $image_path['product_image'];?>cart/<?php echo $pro->image;?>" alt="<?php echo str_replace('_',' ',$pro->name);?>"></a></figure></td>
                        <td data-label="Item">
                            <div class="detail">
                                <h4><?php echo str_replace('_',' ',$pro->name);?></h4>
                                <p><?php echo str_replace('_',' ',$pro->set_description);?></p>
                                <?php if(!empty($pro->size_name)) { ?>
                                <p>Size: <?php echo ucfirst($pro->size_name);?></p>
                                <?php } ?>
                            </div>
                            <div class="other_detail">
                                <!--<div class="offer-ttp-info"><span>Offers:</span> 1 applied</div>-->
                                <div class="return-policy">
                                    <p><i class="material-icons">loyalty</i> 3 Days Replacement <a href="javascript:void(0)"><!--<i class="material-icons">help</i>--></a></p>
                                </div>
                                <?php if(!empty($user_info->user_id)) { ?>
                                    <a href="javascript:void(0)" id="<?php echo make_encrypt($pro->product_id); ?>" sid="<?php echo make_encrypt($pro->size_id); ?>" class="wishlist"><i class="material-icons">favorite</i>Move to Wishlist</a>
                                    <a href="javascript:void(0)" id="<?php echo make_encrypt($pro->product_id); ?>" sid="<?php echo make_encrypt($pro->size_id); ?>" class="remove"><i class="material-icons">clear</i>Remove</a>
                                <?php }else{ ?>
                                <a href="remove-cart-item-<?php echo $pro->rowid;?>" class="remove"><i class="material-icons">clear</i>Remove</a>
                                <?php }?>
                            </div>
                        </td>
                        <?php if(get_product_stocks(array('product_id'=>$pro->product_id,'product_url'=>$pro->product_url)) > 0) { ?>
                        <td data-label="Qty">
                        <?php if(!empty($user_info->user_id)) { ?>
                            <input type="number" min="1" id="cart_saved_item_quantity" data-cart_saved_item="<?php echo make_encrypt($pro->product_id); ?>" name="qty<?php echo $i; ?>" sid="<?php echo make_encrypt($pro->size_id); ?>" value="<?php echo $pro->qty; ?>">                                             
                        <?php }else{ ?>
                            <input type="number" min="1" name="session_cart" id="<?php echo $pro->rowid;?>_<?php echo $pro->product_id;?>" value="<?php echo $pro->qty;?>">
                        <?php }?>
                        </td> 
                        <td data-label="Pieces"><?php echo ($pro->pack_of*$pro->qty);?></td>
                        <td data-label="Price/Piece">&#8377; <?php echo floor($pro->price);?></td>                        
                        <td data-label="Price/Set">                         
                            <!--<p class="standard-price">&#8377; <?php //echo $pro->price*$pro->pack_of; ?></p>-->
                            <p class="selling-price offer-save">&#8377; <?php echo $pro->total_set_price; ?></p>                            
                            <!--<p class="offer-save">Offer Savings: &#8377; <?php //echo $pro->standard_price - $pro->price; ?></p>-->
                        </td>

                       <?php /*?><td data-label="Delivery Details"><div class="delivery_cell">Standard delivery by-<b><?php //echo date("d-M-Y", strtotime($pro['shipping_time'])); ?></b></div></td><?php */?>
                        <td data-label="Amount (Ex. Tax)"><div class="sub_total">&#8377; <?php echo $pro->price_set; ?></div></td>                           
                        <td data-label="Tax Price">&#8377; <?php echo $pro->vat_amount;?></td>
                        <?php } else { ?>
                        <td data-label=" " class="display_cart_in_stock"><p style="color: red;position: absolute;padding: 24px 28px; font-size: 18px; margin-left: 8%;">Out Of Stock</p></td>                    
                        <?php }?>
                    </tr>
                    <?php $i++; ?>                                        
                    <?php endforeach; ?>
                        </tbody>                                   
                </table>           
            </div>
            <div class="cart_noti out_of_stock_popup" style="display: none;">
                 <p><i class="fa fa-check-circle" aria-hidden="true"></i> Sorry We Have Limited Quantity. <!--<a href="user-wishlist">Go to wishlist</a>--></p>
            </div>
            <div class="cart_noti wishlist_path" style="display: none;">
                 <p><i class="fa fa-check-circle" aria-hidden="true"></i> Moved Successfully. <a href="user-wishlist">Go to wishlist</a></p>
            </div>
            <div class="coupon_box">
             <h4>Have Voucher or Coupon ? <a href="javascript:void(0);" id="coupan_apply">Apply Here.</a></h4>
                <form action="" id="coupan_form" style="display: none;">
                 <div class="form-group">
                        <input type="text" name="voucher_code" id="voucher_code" required=""/>
                        <label for="input" class="control-label">Enter Coupon or Voucher Code</label><i class="bar"></i>
                        <span class="error coupan_error" style="display: none;"></span>
                    </div>
                    <button class="btn" id="coupan_apply_btn">Apply</button>
                </form>
            </div>
            <div class="cart_item_summary">
                <div class="wbox">
                      <dt>Total Price:</dt>
                      <dd><b>&#8377; <?php echo number_format((float)$cart_totals['sub_total'], 2, '.', ''); ?></b></dd>
                      <?php if($cart_totals['total_vat'] > 0) {?>
                          <dt>Total Taxes:</dt>
                          <dd>&#8377; <?php echo number_format((float)$cart_totals['total_vat'], 2, '.', ''); ?></dd>
                      <?php } ?>
                      <?php if($cart_totals['voucher_total'] > 0) { ?>
                        <dt id="voucher_discount">Voucher Discount:</dt>
                        <dd id="voucher_value">&#8377; <?php echo $cart_totals['voucher_total']; ?></dd>
                      <?php } ?>
                      <dt>Amount Payable:</dt>
                      <dd>&#8377; <?php echo number_format((float)$cart_totals['cart_total'], 2, '.', '');?></dd>           
                    </dl>
                   <a href="home" class="btn">Continue Shopping</a>
                   <a href="<?php if($cart_totals['cart_total'] > 0)  { echo base_url('checkout'); } else { echo 'javascript:void(0)'; }?>" class="btn">Place Order</a>
                </div>
            </div>
            <?php } else { ?>
            <div class="wbox empty_cart">            
                <p>Your Cart is Empty.</p>
                <a href="home" class="btn">Shop Now</a>
            </div>
            <?php  } ?>
        </div>        
    </div>
    <!-- =========for loader ============-->
    <div id="dv_loader" class="loader" style="display:none;"></div>
    <!-- =========== end of loader ======== -->
</section>
<!--end middle-->
<!--=============footer Start=============-->
<?php $this->load->view('shared/viw_footer');?>
<!--==============end footer=================-->
</body>
<!--=============footer Start=============-->
<?php $this->load->view('shared/viw_links');?>
<!--==============end footer=================-->
<script src="assets/js/cart.js?v=0.3"></script>
</html>