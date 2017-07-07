<div class="filter_outer">
<h3><i class="material-icons close_btn">clear</i> Choose Filters</h3>
<div class="scroll_content">
               <?php if($product_key_word == ''){?> 
            	<div class="data_filter wbox">
                	<h4>Categories<i class="material-icons">expand_more</i></h4>
                    <div class="data_fliter_content">
                    <?php foreach($menu_bar['all_menus'] as $category_info){
                        if($category_info->category_url == $category)
                        {
                        ?>
                        <ul class="categories_nav">
                        	<li<?php echo ($category_info->category_url == $category)? ' class="selected"':''?>><a id="<?php echo $category_info->category_url ?>"  href="javascript:;"><i class="material-icons">expand_more</i> <?php echo $category_info->category_name?></a> 
                            <?php if($category_info->sub_category_count > 0 && ($category == $category_info->category_url)){
                                foreach($category_info->sub_category as $sub_category_data){   
                            ?>
                                	<ul>
                                    	<li<?php echo ($sub_category_data->sub_category_url == $sub_category)? ' class="active"':''?>><a url="<?php echo $sub_category_data->sub_category_url?>" href="products/<?php echo $category_info->category_url.'/'.$sub_category_data->sub_category_url?>"><?php echo $sub_category_data->sub_category_name?></a></li>
                                    </ul>
                                <?php }}?>
                            </li>
                        </ul>
                        <?php }}?>
                    </div>
                </div>
                <?php }?>
                <div class="data_filter wbox">
                	<h4>Filter By <i class="material-icons">expand_more</i></h4>
                    <!--<div class="search_brand">
                        <input type="search" value="" placeholder="Search by Brand"/>
                        <button type="button"><i class="material-icons search_icon">search</i></button>
                    </div>-->
                    <div class="data_fliter_content sub_tosub_categorylist">
                    <input type="hidden" name="sub_tosub_category" id="sub_tosub_category" value="<?php echo $sub_tosub_category?>" />
                    </div>
                </div>
                <!--end filter categories-->
                <?php /*?><div class="data_filter wbox">
                	<h4>Top Brands<i class="material-icons">expand_more</i></h4>
                    <!--<div class="search_brand">
                        <input type="search" value="" placeholder="Search by Brand"/>
                        <button type="button"><i class="material-icons search_icon">search</i></button>
                    </div>-->
                    <div class="data_fliter_content brand_info">
                    </div>
                </div><?php */?>
                <!--color filter start-->
                <div class="data_filter wbox color_filter">
                	<h4>Color<i class="material-icons">expand_more</i></h4>
                    <div class="data_fliter_content color_info">
                    </div>
                </div>
                <!--end filter color-->
                <!--size filter start-->
                <div class="data_filter wbox size_filter">
                	<h4>Size<i class="material-icons">expand_more</i></h4>
                    <div class="data_fliter_content size_info">
                    </div>
                </div>
                <!--end filter size-->
                <div class="data_filter wbox">
                	<h4>Price<i class="material-icons">expand_more</i></h4>
                    <div class="data_fliter_content">
                    	<div class="range_wrap">
                        	<div class="range_slider" id="slider-snap" starts="<?php echo (!empty($price_info))?$price_info->min_price:'0'?>" ends="<?php echo (!empty($price_info))?$price_info->max_price:'0'?>"></div>
                            <span lower ="<?php echo (!empty($price_info))?$price_info->min_price:'0';?>"  id="slider-snap-value-lower"></span>
                            <span upper ="<?php echo (!empty($price_info))?$price_info->max_price:'0';?>" id="slider-snap-value-upper"></span>
                        </div>
                        <!--<div class="price_range clearfix">
                        	<label>Enter a Price Range</label>
                            <input type="text" placeholder="195">
                            <span class="subtract-icon">-</span>
                            <input type="text" placeholder="27490">
                            <input type="submit" value="Go">
                        </div>-->
                    </div>
                </div>
                <div class="data_filter wbox sets_filter">
                	<h4>Pieces in Set<i class="material-icons">expand_more</i></h4>
                    <div class="data_fliter_content sets_info">
                        
                    </div>
                </div>
               <?php /*?> <div class="data_filter wbox discount_filter">
                	<h4>Discount %<i class="material-icons">expand_more</i></h4>
                    <div class="data_fliter_content discount_info">
                        
                    </div>
                </div><?php */?>
            </div>
</div>