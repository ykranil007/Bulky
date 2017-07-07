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
<!--end header-->
<section class="mid mid_space">
	<div class="container">
    <div class="breadcrumbs clearfix">
         <ul>
             <li><a href="<?php echo base_url();?>">Home</a></li>
             <?php if(!empty($category_name)) { ?>
             <li><a href="<?php echo base_url('products/'.$category);?>"><?php echo $category_name; ?></a></li>
             <?php } if(!empty($sub_category_name)) { ?>
             <li><a href="<?php echo base_url('products/'.$category.'/'.$sub_category);?>"><?php echo $sub_category_name; ?></a></li>
             <?php } if(!empty($subtosub_category_name)) { ?>
              <li><?php echo $subtosub_category_name; ?></li>
              <?php } ?>
                <!-- <li>My Account</li> -->
            </ul>
        </div>
    	<aside class="left_sidebar">
        	<!--=============Top left side bar =============-->
            <?php $this->load->view('shared/viw_left_sidebar');?>
            <!--==============end left side bar=================-->
        </aside>
        <div class="col_content">
        	<div class="main_slider">                 
            </div>
            <div class="catalog_product_header clearfix">
            	<a href="javascript:void(0);" class="filter_btn"><i class="material-icons">filter_list</i> Filters</a>
            	<h2 id="type"></h2>
                <div class="sorting_select">
                	<span>Sort By</span>
                    <select id="sort_by" class="sort_by">
                    	<!--<option>Popularity</option>-->
                        <option value="new">What's new</option>
                        <option value="desc">Price: High to Low</option>
                        <option value="asc">Price: Low to High</option>
                        <option value="low_percentage">Offer Percentage: Low to High</option>
                        <option value="high_percentage">Offer Percentage: High to Low</option>
                    </select>
                </div>
            </div>
            <div class="product_list">
                <div class="loader"></div>
            	<!--======== create response here ================-->
            </div>
            <div class="pagination_container"></div>
        </div>
    </div>
</section>
<!--end middle-->
<!--=============footer Start=============-->
<?php $this->load->view('shared/viw_footer');?>
<!--==============end footer=================-->
<div id="quick-demo" class="quick_view_popup"></div>
</body>
<!--=============footer Start Links============= product_listing-->
<?php $this->load->view('shared/viw_links');?>
 
<script src="assets/js/product_listing.js?v=0.20"></script>
<!--==============end footer Links=================-->
</html>