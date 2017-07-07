<aside class="left_sidebar">
            <div class="scroll_content">
                <div class="data_filter wbox">
                    <h4>Quick Links</h4>
                    <ul>
                        <?php if($this->uri->segment(1)=='about'){?>
                        <li class="active"><a href="<?php echo base_url('about');?>">About BulknMore</a></li>
                        <?php } else {?>
                             <li><a href="<?php echo base_url('about');?>">About BulknMore</a></li>
                        <?php }?>
                        <?php if($this->uri->segment(1)=='contact'){?>
                        <li class="active"><a href="<?php echo base_url('contact');?>">Contact</a></li>
                        <?php } else{?>
                        <li><a href="<?php echo base_url('contact');?>">Contact</a></li>
                        <?php }?>
                        <?php if($this->uri->segment(1)=='help'){?>
                        <li class="active"><a href="<?php echo base_url('help');?>">FAQ's</a></li>
                        <?php } else{?>
                        <li><a href="<?php echo base_url('help');?>">FAQ's</a></li>
                        <?php }?>
                         <?php if($this->uri->segment(1)=='payments'){?>
                         <li class="active"><a href="<?php echo base_url('payments');?>">Payments</a></li>
                        <?php } else{?>
                         <li><a href="<?php echo base_url('payments');?>">Payments</a></li>
                        <?php }?>
                         
                         <?php /*if($this->uri->segment(1)=='savedcard'){?>
                         <li class="active"><a href="javascript:;<?php //echo base_url('savedcard');?>">Save Cards</a></li>
                        <?php } else{?>
                          <li><a href="javascript:;<?php //echo base_url('savedcard');?>">Save Cards</a></li>
                        <?php }*/?>

                         <?php if($this->uri->segment(1)=='privacy'){?>
                          <li class="active"><a href="<?php echo base_url('privacy');?>">Privacy Policy</a></li>
                        <?php } else{?>
                           <li><a href="<?php echo base_url('privacy');?>">Privacy Policy</a></li>
                        <?php }?>
                         <?php if($this->uri->segment(1)=='terms'){?>
                          <li class="active"><a href="<?php echo base_url('terms');?>">Terms &amp; Conditions</a></li>
                        <?php } else{?>
                           <li><a href="<?php echo base_url('terms');?>">Terms &amp; Conditions</a></li>
                        <?php }?>
                    </ul>
                </div>
                <!--end filter-->
            </div>
        </aside>