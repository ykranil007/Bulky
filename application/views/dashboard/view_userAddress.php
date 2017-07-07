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

<div class="col_content cng_address">

        	<h3>Add a New Address <button type="button" class="new_address" id="add_new_address">+ New Address</button></h3>


            <div class="form-horizontal" id="submit_new_address" style="display: none">

                <form id="addressForm" action="" method="">

                    <div class="form-group">

                    <div id="add_error_msg" class="pass-error-alert"></div>

                        <label>Name</label>

                        <div class="col-value"><input type="text" id="userName" name="userName" value="<?php echo $user_info->first_name;?> <?php echo $user_info->last_name; ?>"> <span id="userNameError" class="validation-error-msg"></span></div>

                        <span id="userNameError" class="alert-error-msg" ></span>

                    </div>

                    <div class="form-group">

                        <label>Pincode</label>

                        <div class="col-value"><input type="text" id="userPincode" name="userePincode" maxlength="6"><span id="userPincodeError" class="validation-error-msg"></span></div>

                    </div>

                    <div class="form-group">

                        <label>Address</label>

                        <div class="col-value"><textarea rows="3" id="userAddress" name="userAddress"></textarea> <span id="userAddressError" class="validation-error-msg"></span> </div>

                    </div>

                    <div class="form-group">

                        <label>City</label>

                        <div class="col-value"><input type="text" id="userCity" name="userCity"><span id="userCityError" class="validation-error-msg"></span></div>

                    </div>

                    <div class="form-group">

                        <label>State</label>

                        <div class="col-value"><input type="text" id="userState" name="userState"><span id="userStateError" class="validation-error-msg" ></span></div>

                    </div>                    

                    <div class="form-group">

                        <label>Country</label>

                        <div class="inline"><strong id="userCountry">India</strong> (Service Available Only in India)</div>

                    </div>

                    <div class="form-group">

                        <label>Phone</label>

                        <div class="col-value"><input type="tel" id="userPhone" name="userPhone" maxlength="10"> <span id="userPhoneError" class="validation-error-msg" ></span> </div>

                    </div>

                    <div class="col-offset"><button type="button" id="userAddressButton">Save Changes</button>

                    <button type="button" id="cancel_button">Cancel</button></div>

                    <div id="saveAddressSuccessMsg" class="alert-success-msg"></div>

                </form>                

            </div>

            <?php if(!empty($delivery_address)) { ?>            

            <div class="save_address">

                <h3>Your Saved Addresses</h3>

                <ul class="address_list clearfix">

                    <?php foreach ($delivery_address as $key => $address) { ?>

                        <li <?php if($address->default_status == 'Y') { ?>  class="selected" <?php } ?>>

                            <div class="detail">

                                <h4><?php echo $address->name;?></h4>

                                <div class="actbtn">
                                    <a href="javascript:void(0);" id="<?php echo $address->delivery_id;?>" class="delete_del"><i class="material-icons">delete</i></a>
                                </div>

                                <address>

                                    <p><?php echo $address->address;?><?php echo $address->default_status;?></p>

                                    <p><?php echo $address->city;?>, <?php echo $address->state;?></p>

                                    <p><?php echo $address->pincode;?></p>

                                </address>

                                <span class="tel"><?php echo $address->mobile;?></span>

                                <div class="dft_btn"><input type="radio" class="default_radio" id="<?php echo $address->delivery_id;?>" <?php if($address->default_status == 'Y') { ?>  checked="checked" <?php } ?>>Default Address</div>

                            </div>

                        </li>

                        <?php } ?>

                    </ul>

            </div>

            <?php } else { ?>

            <div class="wbox empty_cart" style="margin-top:15px;">            

                <p> No Address Add Till Now. Add Right Now! </p>

            </div>

            <?php } ?>       

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

