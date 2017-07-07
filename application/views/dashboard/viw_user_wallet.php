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
    
    <div class="col_content wallet">
            <h3>BulknMore Wallet</h3>
            <div class="wallet_card">
                <div class="title">
                    <div class="balance">&#8377; <?php if(!empty($tot_money)) { echo $tot_money; } else { echo "0"; }?></div>
                    <div>Gift Cards</div>
                </div>
                <div class="btn_group">
                    <p>You have no Gift Card linked to your account.</p>
                    <button type="button" class="btn btn-orange" id="add_gift_card">Add Gift Card</button>
                    <button type="button" class="btn">View active Gift Card</button>
                </div>
                <div class="collapsible personal_info" style="display:none">
                    <p>Add a Gift Card to your account. For voucher ID & PIN please check your email.</p>
                    <form class="form-horizontal" id="load_wallet"autocomplete="off">
                        <div class="form-group">
                            <label>Gift Card Number</label>
                            <div class="col-value">
                                <input type="text" name="voucher_code" id="voucher_code" placeholder="e.g. BVC64212XHGDS56" value="" maxlength="15">
                                <span class="validation_error" id="voucher_id_error"></span>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>PIN</label>
                            <div class="col-value">
                                <input type="text" name="voucher_pin" id="voucher_pin" placeholder="Please enter 10 digit PIN" value="" maxlength="10">
                                <span class="validation_error" id="voucher_pin_error"></span>
                            </div>
                        </div>
                        <div class="col-offset">
                        <button type="button" id="confirm_gift_card">Confirm</button>
                        <button type="button" class="cancel_btn" id="cancel_gift_card">Cancel</button>
                        </div>
                    </form>
                </div>
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
</html>