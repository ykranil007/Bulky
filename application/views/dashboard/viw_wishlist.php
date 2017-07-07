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
<!--end header-->
        <div class="col_content wishlist">
        	<h3>My Wishlist <span>( <?php echo count($wishlist); ?> Items)</span></h3>            
            <?php foreach($wishlist as $list): ?>
            <div class="wishlist_prod clearfix" id="<?php echo $list->wishlist_id; ?>">                
            	<div class="img_box"><img src="<?php echo $image_path['product_image'];?><?php echo $list->image_name;?>" alt="<?php echo str_replace('_',' ',ucfirst($list->item_name)); ?>"></div>
                <div class="info">
                	<h4><a href="<?php echo $list->category_url.'/'.$list->sub_category_url.'/'.$list->subtosub_category_url.'/'.$list->product_url.'/'.make_encrypt($list->product_id);?>"><?php echo str_replace('_',' ',ucfirst($list->item_name)); ?></a></h4>
                    <div class="discount">
                    	<span class="old-price">Rs. <?php echo $list->standard_price; ?></span>
                        <span class="green_text"><i><?php echo $list->offer_per;?>% OFF</i></span>
                    </div>
                    <div class="more_info clearfix">
                    	<div class="left_info">
                        	<span class="price">&#8377; <?php echo $list->selling_price; ?>/Piece</span>
                            <div class="status">
                            <?php if(get_product_stocks(array('product_id'=>$list->product_id,'product_url'=>$list->product_url)) != 0) { ?>
                            	<span class="green_text">In Stock.</span>
                            <?php } else { ?>
                                <span class="red_text">Out Of Stock.</span>
                            <?php } ?>
                                <!--<p>Delivered in 5-6 business days.</p>-->
                            </div>
                        </div>
                        <div class="short_des">
                        	<ul>
                            	<li>Set Description: <?php echo ucfirst($list->set_description); ?></li>
                                <?php if(!empty($list->size_name)) { ?><li>Size: <?php echo ucfirst($list->size_name); ?></li> <?php } ?>
                            </ul>
                        </div>
                    </div>
                    <?php if(get_product_stocks(array('product_id'=>$list->product_id,'product_url'=>$list->product_url)) != 0) { ?>
                        <a href="javascript:void(0);" pid="<?php echo make_encrypt($list->product_id); ?>" sid="<?php echo make_encrypt($list->size_id)?>" wid="<?php echo $list->wishlist_id; ?>" class="btn add-to-cart">Add To Cart</a>
                    <?php } else { ?>
                        <a href="javascript:void(0);" class="btn" title="Item Out Of Stock">Out Of Stock</a>
                    <?php } ?>
                    <a href="javascript:void(0)" class="btn-remore" id="btn-delete-<?php echo $list->wishlist_id; ?>" >Remove from List</a>
                </div>
            </div>
            <?php endforeach ?>
            <!--end wishlist wrap-->
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
<script type="text/javascript" src="assets/js/wishlist.js?ver=1.0"></script>
</html>
