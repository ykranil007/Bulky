<!doctype html>
<html>
<title><?php echo $page_settings->page_title; ?></title>
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
<div class="col_content">
        	<h3>My Saved Cards</h3>
            <div class="save_card clearfix">
             <?php foreach($details as $result) { ?>
            	<div class="wbox">
               
                	<div class="card_top clearfix">
                    	<div class="valid_dates">Valid Date: <?php echo $result->exp_month; ?>/<?php echo $result->exp_year; ?></div>
                        <a href="javascript:;" class="remove" data-cardId="<?php echo $result->card_id; ?>" id="romove_card_btn" title="Remove"><i class="material-icons">clear</i></a>
                    </div>
                    <div class="card_info clearfix">
                    	<?php if($result->card_type == 'amex') { ?>
                        <div class="card_img"><img src="assets/images/card_type/amex.png" alt=""></div>
                        <?php } else if ($result->card_type == 'discover') { ?>
                        <div class="card_img"><img src="assets/images/card_type/discover.png" alt=""></div>
                        <?php } else if($result->card_type == 'maestro') { ?>
                        <div class="card_img"><img src="assets/images/card_type/maestro.png" alt=""></div>
                        <?php } else if($result->card_type == 'mastercard') { ?>
                        <div class="card_img"><img src="assets/images/card_type/maestro.png" alt=""></div>
                        <?php } else if($result->card_type == 'visa'){ ?>
                        <div class="card_img"><img src="assets/images/card_type/visa.png" alt=""></div>
                        <?php } else if($result->card_type == 'visa_electron'){ ?>
                        <div class="card_img"><img src="assets/images/card_type/visa_electron.png" alt=""></div>
                        <?php } else if($result->card_type == 'jcb'){ ?>
                        <div class="card_img"><img src="assets/images/card_type/jcb.png" alt=""></div>
                        <?php } else { ?>
                        <div class="card_img"><img src="assets/images/card_type/no_logo.png" alt=""></div>
                        <?php } ?>
                        
                        <div class="card_number"><?php echo substr_replace($result->card_no, str_repeat("X", 10), 2, 10); ?></div> <!-- 52xx xxxx xxxx 4875 -->
                        <div class="bank_name"><?php echo $result->card_label; ?></div>
                        
                    </div>
                    
                </div>
                <?php } ?>
            </div> <!-- end save_card clearfix -->
            <div class="col-offset">
            <button type="button" id="add_card_btn" class="btn btn-orange">Add New Card</button>
           </div>
      <!--=============== Add card section ============================ -->
            <div class="add_newcard" id="add_cards_div" style="display:none;">
            	<h4>Add New Card</h4>
                <div><label id="error_msg" style="display:none; color:red" ></label></div>
                <form class="form-horizontal">
                    <div class="form-group">
                        <label>Card Number</label>
                        <div class="col-value"><input value="" type="text"  class="Required" id="cardNo"></div>
                    </div>
                    <div class="form-group">
                        <label>Name on Card</label>
                        <div class="col-value"><input value="" type="text" class="Required" id="card_name"></div>
                    </div>
                    <div class="form-group">
                        <label>Expiry Date</label>
                        <div class="col-value">
                            <select class="Required" id="card_month">
                                <option value="0">Month</option>
                                <option>01</option>
                                <option>02</option>
                                <option>03</option>
                                <option>04</option>
                                <option>05</option>
                                <option>06</option>
                                <option>07</option>
                                <option>08</option>
                                <option>09</option>
                                <option>10</option>
                                <option>11</option>
                                <option>12</option>
                                
                            </select>
                            <span class="divider">/</span>
                            <select class="Required" id="card_year">
                                <option value="0">Year</option>
                                <option>2015</option>
                                <option>2016</option>
                                <option>2017</option>
                                <option>2018</option>
                                <option>2019</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Card Label</label>
                        <div class="col-value"><input value="" type="text" placeholder="Eg:My Card" id="card_label"></div>
                    </div>
                    <div class="col-offset">
                        <button type="button" id="save_cards_details">Save Card</button>
                        <button type="button" id="cancel_add_card_btn" class="cancel_btn">Cancel</button>
                   	</div>
                </form>
            </div>
      <!-- ==============End of Add Cards ==============================-->
      <p class="log"></p>
	 </div><!-- end col_content -->
    </div>
</section>
<!--=============footer Start=============-->
<?php $this->load->view('shared/viw_footer');?>
<!--==============end footer=================-->
</body>
<!--=============footer Start=============-->
<?php $this->load->view('shared/viw_links');?>
<script src="assets/js/jquery.creditCardValidator.js"></script>
<!--==============end footer=================-->
</html>
<script>
   /* $(function() {
        $('#cardNo').validateCreditCard(function(result) {
			console.log(result);
			$('.log').html('<strong>Card type: </strong>' + (result.card_type == null ? '-' : result.card_type.name)
			 + '<br><strong>Valid: </strong>' + result.valid
			 + '<br><strong>Length valid: </strong>' + result.length_valid
			 + '<br><strong>Luhn valid: </strong>' + result.luhn_valid);
            if(result.card_type == null)
            {
                $('#cardNo').removeClass();
            }
            else
            {
                $('#cardNo').addClass(result.card_type.name);
            }
            
            if(!result.valid)
            {
                $('#cardNo').removeClass("valid");
            }
            else
            {
                $('#cardNo').addClass("valid");
            }
            
        });
    });*/
</script>