 
                            	<div class="product_gallery">
                                    <div class="large_view">
                                        <img class="cloudzoom" alt ="" id ="detail-zoom" src="<?php echo $image_path['product_image'];?>/<?php echo $product_images[0]->image_name; ?>" data-cloudzoom='zoomSizeMode: "image", tintColor:"#000", tintOpacity:0.75, maxMagnification:4, autoInside:768'>
                                    </div>
                                    <div class="thumbs">
                                        <?php foreach($product_images as $key=>$image) {  ?>
                                        <img class="<?php echo ($key==0) ? "cloudzoom-gallery cloudzoom-gallery-active" : "cloudzoom-gallery" ?>" width="64" alt="<?php echo ucwords($products_details->item_name)?>" src="<?php echo $image_path['product_image']; ?><?php echo $image->image_name; ?>" alt ="" data-cloudzoom='useZoom:"#detail-zoom", image:"<?php echo $image_path['product_image']; ?>/<?php echo $image->image_name; ?>"'>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="product_detail">
                                	<h3><?php echo str_replace('_',' ',$products_details->item_name);?></h3>
                                    <div class="rating_price_wrap clearfix">
                                        <div class="price">
                                            <span class="selling-price" style="color: green;">&#x20B9; <?php echo number_format($products_details->selling_price);?> / Piece</span>                                            
                                        </div>                   
                                    </div>
                                    <div class="color_size_wrap clearfix">
                                    	  <div class="select_color clearfix">
                                        	<span>Set Description: <?php echo $products_details->set_description; ?></span>
                                         </div>
                                    </div>
                                    <?php if(!empty($product_size)) { ?>
                                    <div class="color_size_wrap clearfix">
                                        <div class="select_size clearfix">
                                            <span>Select Size</span>
                                              <?php foreach($product_size as $size): ?>                   
                                              <a  class="active" href="javascript:void(0);"><?php if(!empty($size->size_name)) echo $size->size_name; ?></a>
                                              <?php endforeach ?>                                            
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <!--rating / price wrap end
                                    <div class="color_size_wrap clearfix">
                                    	  <div class="select_color clearfix">
                                        	<span>Select Color</span>
                                            <?php //foreach($product_color as $color): ?>
                                            <a <?php //if($products_details->color_id == $color->color_id) { ?> class="active" <?php //} ?>  href="<?php //echo $products_url;?>/<?php //echo $color->product_url;?>/<?php //echo base64_encode($color->product_id);?>/<?php //echo base64_encode($color->color_id);?>"><img src="<?php //echo $image_path['product_image'];?>details/small/<?php //echo $color->image_name; ?>" alt="<?php //echo $color->image_name; ?>"></a>
                                            <?php //endforeach ?>         
                                          </div>
                                          <?php //if(!empty($product_size[0]->size_name)): ?>                                      
                                          <div class="select_size clearfix">
                                          	<span>Select Size</span>
                                              <?php //foreach($product_size as $size): ?>                   
                                              <a <?php //if($products_details->product_id == $size->product_id) { ?> class="active" <?php //} ?> href="<?php //echo $products_url;?>/<?php //echo $size->product_url;?>/<?php //echo base64_encode($size->product_id); ?>/<?php //echo base64_encode($size->color_id); ?>"><?php //if(!empty($size->size_name)) echo $size->size_name; ?></a>
                                              <?php //endforeach ?>                                            
                                          </div>
                                          <?php //endif; ?>          
                                    </div>
                                    color size end-->
                                    <?php if($stock_status == 0) { ?>
                                    <div class="soldout clearfix">
                                        <strong>Sold Out</strong>
                                        <p>This item is currently out of stock</p>
                                        <div class="form-group">
                                          <input required="" type="email" name="sold_out" id="sold_item"/>
                                          <label for="input" class="control-label">Enter email to get notified</label><i class="bar"></i>
                                          <span class="validation_error" id="soldout_error"></span>
                                        </div>
                                        <button type="button" id="sold_item_btn" class="btn">Notify Me</button>
                                    </div>
                                    <?php } ?>
                                    <!--soldout end-->
                                    <div class="sortinfo">
                                        <div class="sort_specifications clearfix">
                                            <ul class="clearfix">
                                                <?php if(!empty($products_details->pattern_type)) { ?><li><strong>Pattern:</strong> <?php  echo $products_details->pattern_type; }?></li>
                                                <?php if(!empty($products_details->product_feature_1)) { ?><li><strong>Feature:</strong> <?php echo $products_details->product_feature_1; }?></li>
                                                <?php if(!empty($products_details->product_feature_2)) { ?><li><strong>Color:</strong> <?php echo ucfirst($products_details->product_feature_2); }?></li>
                                                <?php if(!empty($products_details->product_feature_3)){ ?><li><strong>Sleeve:</strong> <?php echo $products_details->product_feature_3; }?></li>
                                                <!--<li><strong>Sold By:</strong> <?php //echo $products_details->merchant_name;?></li>-->
                                            </ul>
                                        </div>
                                    </div>
                                    <!--sort specifications end-->
                                    <?php if($stock_status != 0) { ?>
                                    <div class="btn_wrap_bottom clearfix">
                                        <a href="<?php echo $products_url;?>/<?php echo $products_details->product_url; ?>/<?php echo make_encrypt($products_details->product_id);?>" class="btn add_to_cart"><i class="material-icons"></i>View Details</a>
                                        <a href="javascript:void(0)" class="btn buy_now" id="<?php echo make_encrypt($products_details->product_id); ?>">Buy Now</a>
                                    </div>
                                    <?php } ?>
                                </div>                                
                                <script>
                                CloudZoom.quickStart();
                                $('#sold_item_btn').click(function()
                                    {
                                        if($('#sold_item').val() == '')
                                        {
                                            $('#soldout_error').text('Please Enter Email ID.').fadeIn('slow');
                                        }
                                    });
                                </script>
                                <script type="text/javascript" src="assets/js/cart.js"></script>