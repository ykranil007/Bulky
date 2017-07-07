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
        <div class="main_slider featured_slider">
        <div style="height: 325px;width: 1170px;border: 1px;"><center><img src="http://bulknmore.com/assets/images/loader1.gif" alt="" style="height: 60px;width: 60px;margin-top: 110px;"></center></div>         
        </div>
        <!--end slider-->
        <div class="discover_fashion clearfix">
            <div class="left_sidebar">            
            <?php foreach($menu_bar['all_menus'] as $menu): ?>
            <?php if($menu->category_url == $sub_cat_url) { ?>
                <div class="data_filter wbox">                
                    <?php if(!empty($menu->sub_category)) foreach($menu->sub_category as $sub_menu): ?>
                    <div class="expander">
                    <h4><a href="<?php if(!empty($menu->category_url) && !empty($sub_menu->sub_category_url )) echo base_url()."products/".$menu->category_url."/".$sub_menu->sub_category_url; ?>"><?php if(!empty($sub_menu->sub_category_name)) echo $sub_menu->sub_category_name;  ?></h4></a>
                    <ul class="expander-contracted">
                        <?php if(!empty($sub_menu->subtosub_categorys)) foreach($sub_menu->subtosub_categorys as $subtosub_menu): ?>
                        <li><a href="<?php if(!empty($menu->category_url) && !empty($sub_menu->sub_category_url) && !empty($subtosub_menu->subtosub_category_url )) echo base_url()."products/".$menu->category_url."/".$sub_menu->sub_category_url."/".$subtosub_menu->subtosub_category_url; ?>"><?php if(!empty($subtosub_menu->subtosub_category_name)) echo $subtosub_menu->subtosub_category_name;  ?></a></li>
                        <?php endforeach ?>                                       
                    </ul>
                    <a title="More" class="expander-expand-link" href="javascript:void(0)">More <i class="fa fa-chevron-down" aria-hidden="true"></i></a>
                    </div>
                    <?php endforeach ?>
                </div>            
            <?php } ?>
            <?php endforeach ?>
            </div>            
           
            <!--end left sidebar-->
            <div class="col_content">
                <h3 class="header_title">#FEATURED <span>Sunny days and sizzling looks are here again!</span></h3>
                <ul class="dis_fashion_block clearfix">
                <?php foreach($sub_cat_page_banner as $banner): ?>
                    <li>
                        <a href="javascript:void(0);">
                            <div class="card_img">                             
                              <img src="<?php echo $image_path['banner_image'];?><?php echo $banner->banner_image;?>" alt="">                              
                            </div>
                            <div class="card_text">
                                <span class="card_title"><?php echo $banner->banner_name;?></span>
                                <span class="card_subtitle"><?php echo $banner->banner_description;?></span>
                            </div>
                        </a>
                    </li>
                <?php endforeach ?>        
                </ul>
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
<script> 
    $(window).load(function() {
        var cate_url = location.href.match(/([^\/]*)\/*$/)[1];
        var slider = $('.main_slider');
        slider.data('owlCarousel').destroy();
        $.get("index.php/Home/get_sub_category_banner?category_id="+cate_url,function(content){
            slider.html(content)
            var amountHeaderImages = slider.find('img').length;
             slider.owlCarousel({
                animateOut: 'fadeOut',
                animateIn: 'fadeIn',
                items:1,
                loop :(amountHeaderImages > 1)?true:false,
                nav:'true',
                autoplay:'true',
                autoplayTimeout:2000,
                singleItem: true
            });
        });       
     });
    </script>
</html>
