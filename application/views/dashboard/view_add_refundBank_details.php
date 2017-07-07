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
	<!-- start for milddle content -->
    <div class="col_content refund_ac">
        	<h3>Your Bank Accounts</h3>
            <?php $notify=$this->session->flashdata('notity');  if($notify!='') : ?>
                <div class="<?php echo $notify['msg_class'];?> fade in"> <i class="icon-remove close" data-dismiss="alert"></i> <?php echo $notify['message'];?> </div>
            <?php endif;  ?>
            <div class="table_responsive">
            <table class="refund_table">
            	<thead>
                	<tr>
                    	<th>Saved Bank Accounts</th>
                        <th>Bank Account Number</th>
                        <th>IFSC</th>
                        <th>&nbsp;</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($return_bank_details)) { foreach($return_bank_details as $detail): ?>
                	<tr>
                    	<td><?php echo $detail->first_name;?> <?php echo $detail->last_name;?></td>
                        <td>*********<?php if(strlen($detail->account_number) <= 11 ) { echo substr($detail->account_number,9); echo strlen($detail->account_number); } else { echo substr($detail->account_number,10); } ?></td>
                        <td><?php echo $detail->ifsc_code; ?></td>
                        <td><a href="javascript:void(0);" id="<?php echo make_encrypt($detail->bank_detail_id); ?>" class="btn delete_account">Delete this account</a></td>
                    </tr>
                    <?php endforeach; } else { ?>
                    <tr>
                        <td class="red_text" colspan="4" style="text-align: center;"> NO ACCOUNT FOUND </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
            </div>
            <div class="add_account clearfix">
            	<div class="title"><button type="button" id="add_refund_bank_details" class="btn btn-orange"> Add a Bank Account</button></div> <!-- <input name="" type="radio" value=""> -->                
                <div id="refund_details" class="add_newcard" style="display:none">
                <form class="form-horizontal" id="form_save_bank_details" method="post" action="<?php echo base_url('save-bankdetails'); ?>">
                	<p>Enter New Bank Account Information</p>
                    
                    <!-- <p>
                    	<label>Do you know your IFSC Code :</label>
                        <span class="value">
                        	<input name="yes" type="radio" value="Yes"> Yes
                            <input name="no" type="radio" value="No"> No
                        </span>
                    </p> 
                    <div id="yes" style="display:none">
                    	<div class="form-group">
                            <label>Enter IFSC Code :</label>
                            <div class="col-value">
                                <input type="text">
                            </div>
                        </div>
                        <div class="col-offset">
                        	<button type="button">Confirm IFSC Code</button>
                        </div>
                    </div>-->
                    <div id="n12o">
                    	<div class="form-group">
                            <label>Enter Bank Name :</label>
                            <div class="col-value">
                                <input type="text" class="Required" name="bank_name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Enter City Name :</label>
                            <div class="col-value">
                                <input type="text" class="Required" name="city_name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Enter Branch Name :</label>
                            <div class="col-value">
                              <input type="text" class="Required" name="city_name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>IFSC Code :</label>
                            <div class="col-value">
                                <!-- <span class="value">BARB0BANGAW</span> --> 
                                <input type="text" class="Required" name="ifsccode" id="ifsccode">
                        <i id="ifscdetails" style=" display:none; color: red; margin-top: -20px; position: absolute; right: 94px;" class="fa fa-exclamation-circle" aria-hidden="true" ></i>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Bank Account Number :</label>
                            <div class="col-value">
                                <input type="text" class="Required" name="account_number" id="account_number" >
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Reconfirm Bank Account Number :</label>
                            <div class="col-value">
                                <input type="text" class="Required" name="confirm_accountnumber" id="confirm_accountnumber" >
                            <i id="recnfrm" style=" display:none; color: red; margin-top: -20px; position: absolute; right: 94px;" class="fa fa-exclamation-circle" aria-hidden="true" ></i>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Account Type :</label>
                            <div class="col-value">
                                <select class="Required" name="account_type">
                                	<option value="">Select Type</option>
                                    <option value="1">Saving</option>
                                    <option value="2">Current</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-offset">
                        	<button type="button" id="save_bank_details">Save & Add Bank</button>
                        </div>
                    </div>
                </form>
                </div>
            </div>
        </div>
    <!-- End of middle content -->

	</section>
<!--=============footer Start=============-->
<?php $this->load->view('shared/viw_footer');?>
<!--==============end footer=================-->
</body>
<!--=============footer Start=============-->

<?php $this->load->view('shared/viw_links');?>
<script src="assets/js/bank_refund.js?ver=1"></script>
<!--==============end footer=================-->
</html>