<section class="mid mid_space ac_pages">
    <div class="container">
        <div class="breadcrumbs clearfix">
            <ul>
                <li><a href="#">Home</a></li>
                <li>My Account</li>
            </ul>
        </div>
        <aside class="left_sidebar">
            <div class="wbox ac_navbar">
                <h4>My Account <i class="material-icons">expand_more</i></h4>
                <div class="navouter">
                    <div class="nav_section">
                        <h5>Orders</h5>
                        <?php if($this->uri->segment(1)=='show-buyer-orders' || $this->uri->segment(1)=='order-details') {?>
                        <a href="<?php echo base_url('show-buyer-orders');?>" class="active">My Orders</a>
                        <?php } else {?>
                        <a href="<?php echo base_url('show-buyer-orders');?>">My Orders</a>
                        <?php }?>
                        <!--<a href="orders">My Orders</a>-->
                    </div>
                    <!--<div class="nav_section">
                        <h5>Payments</h5>
                        <a href="javascript:void(0)">Gift Card</a>
                        <a href="javascript:void(0)">Wallet</a>
                        <a href="javascript:void(0)">My Saved Cards</a>
                    </div>-->
                    <div class="nav_section">
                        <h5>My Stuff</h5>
                        <?php if($this->uri->segment(1)=='user-wishlist'){?>
                        <a href="<?php echo base_url('user-wishlist');?>" class="active">My Wishlist</a>
                        <?php } else {?>
                        <a href="<?php echo base_url('user-wishlist');?>">My Wishlist</a>
                        <?php }?>
                        <!--<a href="user-wishlist">My Wishlist </a>-->
                    </div>
                    <div class="nav_section">
                        <h5>Settings</h5>
                        <?php if($this->uri->segment(1)=='account'){?>
                        <a href="<?php echo base_url('account');?>" class="active">Personal Information</a>
                        <?php } else {?>
                        <a href="<?php echo base_url('account');?>">Personal Information</a>
                        <?php }?>
                        <?php if($this->uri->segment(1)=='changepassword'){?>
                        <a href="<?php echo base_url('changepassword');?>" class="active">Change Password</a>
                        <?php } else {?>
                        <a href="<?php echo base_url('changepassword');?>">Change Password</a>
                        <?php }?>
                        <?php if($this->uri->segment(1)=='address'){?>
                        <a href="<?php echo base_url('address');?>" class="active">Addresses</a>
                        <?php } else {?>
                        <a href="<?php echo base_url('address');?>">Addresses</a>
                        <?php }?>
                        <?php if($this->uri->segment(1)=='accountdeactivate'){?>
                        <a href="<?php echo base_url('accountdeactivate');?>" class="active">Deactivate Account</a>
                        <?php } else {?>
                        <a href="<?php echo base_url('accountdeactivate');?>">Deactivate Account</a>
                        <?php }?>
                        <a href="<?php echo base_url('accountemailupdate');?>" <?php if($this->uri->segment(1)=='accountemailupdate') { ?> class="active"<?php } ?>>Update Email/Mobile</a>
                        <!--<a  href="account">Personal Information</a>
                        <a  href="changepassword">Change Password</a>
                        <a  href="address">Addresses</a>
                        <a  href="accountdeactivate">Deactivate Account</a>-->
                    </div>
                    <div class="nav_section">
                        <h5>Payments</h5>
                        <?php if($this->uri->segment(1)=='user-wallet'){?>
                        <a href="<?php echo base_url('user-wallet');?>" class="active">Bulk Wallet</a>
                        <?php } else {?>
                        <a href="<?php echo base_url('user-wallet');?>">Bulk Wallet</a>
                        <?php }?>
                        <?php if($this->uri->segment(1) == 'card-details') { ?>
                        <a href="<?php echo base_url('card-details');?>" class="active">My Saved Cards</a>
                        <?php } else { ?>
                        <a href="<?php echo base_url('card-details');?>">My Saved Cards</a>
                        <?php } ?>
                        <?php if($this->uri->segment(1) == 'refund-bankdetails') { ?>
                        <a href="<?php echo base_url('refund-bankdetails');?>" class="active">Bank Details</a>
                        <?php } else { ?>
                        <a href="<?php echo base_url('refund-bankdetails');?>">Bank Details</a>
                        <?php } ?>
                    </div>
                    <div class="nav_section">
                        <h5>Offers</h5>
                        <?php if($this->uri->segment(1)=='offers'){?>
                        <a href="<?php echo base_url('offers');?>" class="active">Offers & Vouchers</a>
                        <?php } else {?>
                        <a href="<?php echo base_url('offers');?>">Offers & Vouchers</a>
                        <?php }?>
                    </div>
                </div>
            </div>
        </aside>
       
<!--end middle-->