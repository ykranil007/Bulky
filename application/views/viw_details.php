<?php //print_r($product_color);exit;?>
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
    	<div class="breadcrumbs clearfix">
        	<ul>
            	<li><a href="home">Home</a></li>
                <li><a href="<?php echo "products/".$category; ?>"><?php echo ucfirst($category); ?></a></li>
                <!--<li><a href="javascript:void(0)"><?php //echo ucfirst($sub_category); ?></a></li>-->
                <li><?php echo ucfirst($sub_category); ?></li>
            </ul>
        </div>         
        <div class="product_gallery">
            <div class="large_view">
				<a href="javascript:void(0)" id="add_wishlist_<?php echo $products_details->product_id; ?>" class="wish_link"><i class="material-icons">favorite</i></a>
                <img class="cloudzoom" alt ="<?php echo $products_details->item_name;?>" id ="detail-zoom" src="<?php echo $image_path['product_image'];?>/<?php echo $product_images[0]->image_name; ?>" data-cloudzoom='zoomSizeMode: "image", tintColor:"#000", tintOpacity:0.25, maxMagnification:4, autoInside:768'>
            </div>
            <div class="thumbs">
                <?php foreach($product_images as $key=>$image) {  ?>
                <img class="<?php echo ($key==0) ? "cloudzoom-gallery cloudzoom-gallery-active" : "cloudzoom-gallery" ?>" width="64" src="<?php echo $image_path['product_image'].$image->image_name; ?>" alt ="<?php echo $products_details->item_name;?>" data-cloudzoom='useZoom:"#detail-zoom", image:"<?php echo $image_path['product_image'].$image->image_name; ?>"'>
                <?php } ?>
            </div>
        </div>
        <!--end gallery-->        
        <div class="product_detail">
            <div class="wbox">
                <div class="bar-5"></div>
                <div class="rating_price_wrap clearfix">
                    <div class="price">
                        <span class="selling-price"><div class="bar-12"></div></span>
                    </div>
                </div>
                <div class="clearfix">
                    <div class="sortinfo">
                        <div class="sort_specifications clearfix">                            
                            <div class="spec clearfix">            
                            <div class="label"><div class="bar-4"></div></div>
                                <div class="list">
                                    <ul>
                                        <li><div class="bar-5"></div></li>
                                        <li><div class="bar-5"></div></li>
                                        <li><div class="bar-5"></div></li>
                                        <li><div class="bar-5"></div></li>
                                        <li><div class="bar-5"></div></li>
                                    </ul>
                                </div>
                                <div class="set_list">
                                    <ul>
                                        <li><div class="bar-5"></div></li>
                                        <li><div class="bar-5"></div></li>
                                        <li><div class="bar-5"></div></li>
                                        <li><div class="bar-5"></div></li>
                                    </ul>
                                </div>
                            </div>
                        </div>                        
                    </div>
                    <div class="detail_right">
                        <div class="pincode-widget clearfix">
                            <label><div class="bar-5"></div></label>  
                            <div class="bar-5"></div>
                        </div>
                        <div class="seller_badge">
                            <div class="delivery_info_wrap">
                                <div class="cash_on_delivery">
                                    <h5><div class="bar-3"></div></h5>
                                </div>
                                <div class="bar-5"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end wbox-->
        </div>
        <!--end product detail-->
        <div style="clear:both;"></div>
        <div class="similar_pro wbox">
        	<div class="owl-carousel">
                <div class="item">
                    <div class="item_wrap">
                        <a href="javascript:void(0);">
                        	<div class="blank_group">
                                <img src="<?php echo base_url('assets/images/img-icon-main-page.jpg'); ?>"/>
                                <div class="bar-12"></div>
                                <div class="bar-4"></div>                                
                            </div>
                        </a>
                    </div>
                </div>
                <div class="item">
                    <div class="item_wrap">
                        <a href="javascript:void(0);">
                        	<div class="blank_group">
                                <img src="<?php echo base_url('assets/images/img-icon-main-page.jpg'); ?>"/>
                                <div class="bar-12"></div>
                                <div class="bar-4"></div>                                
                            </div>
                        </a>
                    </div>
                </div>
                <div class="item">
                    <div class="item_wrap">
                        <a href="javascript:void(0);">
                        	<div class="blank_group">
                                <img src="<?php echo base_url('assets/images/img-icon-main-page.jpg'); ?>"/>
                                <div class="bar-12"></div>
                                <div class="bar-4"></div>                                
                            </div>
                        </a>
                    </div>
                </div>
                <div class="item">
                    <div class="item_wrap">
                        <a href="javascript:void(0);">
                        	<div class="blank_group">
                                <img src="<?php echo base_url('assets/images/img-icon-main-page.jpg'); ?>"/>
                                <div class="bar-12"></div>
                                <div class="bar-4"></div>                                
                            </div>
                        </a>
                    </div>
                </div>
                <div class="item">
                    <div class="item_wrap">
                        <a href="javascript:void(0);">
                        	<div class="blank_group">
                                <img src="<?php echo base_url('assets/images/img-icon-main-page.jpg'); ?>"/>
                                <div class="bar-12"></div>
                                <div class="bar-4"></div>                                
                            </div>
                        </a>
                    </div>
                </div>
                <div class="item">
                    <div class="item_wrap">
                        <a href="javascript:void(0);">
                        	<div class="blank_group">
                                <img src="<?php echo base_url('assets/images/img-icon-main-page.jpg'); ?>"/>
                                <div class="bar-12"></div>
                                <div class="bar-4"></div>                                
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Recent View Products-->
        <div class="similar_pro wbox recent_pro">
        	
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
<script type="text/javascript" src="assets/js/cart.js?v=2"></script>
<script type="text/javascript" src="assets/js/buy_bulk_price.js?v=1.3"></script>
<script src="assets/js/pincode.js?v=1"></script>
<script type="text/javascript">
$(document).on('keyup', '#pincode_availability', function (event){
        if(event.keyCode == 13){
           $("#btn_pincode_availability").trigger("click");
           }
    });
</script>
<!--==============end footer=================-->
</html>