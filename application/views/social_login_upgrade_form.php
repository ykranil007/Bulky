<!doctype html>
<html>
<head>
<base href="<?php echo base_url()?>">
<meta charset="utf-8">
<link rel="shortcut icon" type="image/x-icon" href="assets/images/title.png">
<title>BulknMore Social Login</title>

<!--[if lt IE 9]>
   <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
   <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->

<!--CSS-->
<link href="assets/css/reset.css" type="text/css" rel="stylesheet">
<link href="assets/css/style.css" type="text/css" rel="stylesheet">

</head>

<body>
<div class="upaccount">
  <div class="logo"><img src="assets/images/logo.png" alt=""></div>
    <div class="text-center">Verify your mobile number to upgrade to BulknMore account </div>
    
   
<!--=============================Mobile Verify===========================================-->
    <div id="mobile_verify">
    <div class="alert alert-success" id="mobile_notify_match" role="alert" style="display:none;"></div>
 
      <form id="upgrade_form_mobile" name="upgrade_form_mobile" method="post" action="">
      
      <div class="form-group">
          <input type="text" id="verify_mobile" onkeypress='return event.charCode >= 48 && event.charCode <= 57'  name="verify_mobile" maxlength="10" required/>
          <label for="input" class="control-label">Mobile</label><i class="bar"></i>
          <span id="mobile_number_msg" class="validation_error"></span> 
          <span id="mobile_number_msg2" class="validation_error"></span> 
          <span id="mobile_number_msg3" class="validation_error"></span> 
        </div>
        <div class="col-md-12">
            <div><button type="button" id="btn_upgrade_mobile" class="btn btn-success">Continue</button></div>
            <div class="agree">By clicking on continue i agree to <a href="javasript:;">Terms and Conditions</a></div>
        </div>
    </form>
    </div>
<!--=============================Mobile Verify===========================================-->    
 

 <!--=============================OTP Verify===========================================-->
 <div id="verified_otp" style="display:none;">
 <div class="alert alert-success" id="mobile_notify" role="alert" style="display:none;"></div>
 <div class="alert alert-success" id="otp_notify" role="alert" style="display:none;"></div>
  <form id="upgrade_form_otp" name="upgrade_form_otp" method="post" action="">
      <div class="form-group">
          <input type="text" name="otpcode" id="otpcode" onkeypress='return event.charCode >= 48 && event.charCode <= 57' maxlength="4" required/>
          <label for="input" class="control-label">Enter OTP</label><i class="bar"></i>
        </div>
        <span id="otp_number_msg" class="validation_error"></span> 
        <div class="col-md-12">
          <div><button type="button" id="btn_upgrade_otp" class="btn btn-success">Continue</button>
            <div class="agree"><a href="javascript:;" id="resend_mobile_otp">Resend OTP</a></div>
          </div>
          
            <div class="agree">By clicking on continue i agree to <a href="javasript:;">Terms and Conditions</a></div>
        </div>
    </form>
 </div>
<!--=============================OTp Verify===========================================-->


<!--=============================OTP Verify===========================================-->
 <div id="password_verify" style="display:none;">
 <div class="alert alert-success" id="password_notify" role="alert" style="display:none;"></div>
 <div class="alert alert-success" id="success_notify" role="alert" style="display:none;"></div>
  <form id="upgrade_form_password" name="upgrade_form_password" method="post" action="">
      <div class="form-group">
          <input type="password" name="password" id="password"  required/>
          <label for="input" class="control-label">Enter Password</label><i class="bar"></i>
        </div>
        <span id="password_number_msg" class="validation_error"></span> 
        <div class="col-md-12">
          <div><button type="button" id="btn_upgrade_password" class="btn btn-success">Continue</button></div>
            <div class="agree">By clicking on continue i agree to <a href="javasript:;">Terms and Conditions</a></div>
        </div>
    </form>
 </div>
<!--=============================Mobile Verify===========================================-->
 

 </div>
</body>

<!--JS-->
<script src="assets/js/jquery-1.11.3.min.js"></script>
<!--<script src="assets/js/custom.js"></script>-->
</html>

<script>
 function checkEnter(e){
 e = e || event;
 var txtArea = /textarea/i.test((e.target || e.srcElement).tagName);
 return txtArea || (e.keyCode || e.which || e.charCode || 0) !== 13;
}
document.getElementById('upgrade_form_mobile').onkeypress = checkEnter;
document.getElementById('upgrade_form_otp').onkeypress = checkEnter;
document.getElementById('upgrade_form_password').onkeypress = checkEnter;
 </script>

<script type="text/javascript">

$("#verify_mobile").keypress(function(e) {
    if(e.which == 13) { 
    $("#btn_upgrade_mobile").trigger("click");
    }
});


$("#otpcode").keypress(function(e) {
    if(e.which == 13) { 
    $("#btn_upgrade_otp").trigger("click");
    }
});

$("#password").keypress(function(e) {
    if(e.which == 13) { 
    $("#btn_upgrade_password").trigger("click");
    }
});


$('#resend_mobile_otp').click(function()
  { 
    var mobile_number = $('#verify_mobile').val(); 
    $.ajax({
      url:'resend-otp',
      type:'get',
      dataType:'json',
      data:{mobile_number:mobile_number},
      success:function(json) {
       

        $('#mobile_notify').css('display','block');
        $('#mobile_notify').addClass(json.msg['msg_class']);
        $('#mobile_notify').html(json.msg['message']);
      },
       error: function(data)
      {
          console.log(data);
      }
      
     });  
  });

  //===================Change Password=========================
$('#btn_upgrade_mobile').click(function()
 {
  var error_flag = false; 
  var value = $('#verify_mobile').val();
  var count_degit = $('#verify_mobile').val().length;
  var first_latter = (value.charAt(0)); 
  if(first_latter<7 && $('#verify_mobile').val() != null && $('#verify_mobile').val() != '' )
  {
    $('#mobile_number_msg3').html('Please Enter  Valid Mobile  Number.');
    //$( "#mobile_number_msg2" ).remove();
      error_flag = true;
  }
  /*else
    $('#mobile_number_msg3').html('');*/
  if(count_degit!=10 && first_latter>6 && $('#verify_mobile').val() != null && $('#verify_mobile').val() != ''  )
  { 
     $('#mobile_number_msg2').html('Please Enter  Valid Mobile  Number.');
     $( "#mobile_number_msg3" ).remove();
      error_flag = true;
    
  }
  /*else
    $('#mobile_number_msg2').html('');*/
  //-------------------------------------------------------------
  if($('#verify_mobile').val() == null || $('#verify_mobile').val() == '')
  {
      $('#mobile_number_msg').html('Please Enter  Mobile Number.');

      error_flag = true;
  }
  else
     $('#mobile_number_msg').html('');
  //--------------------------------------------------------------
  
  //--------------------------------------------------------------
  if(error_flag)
    return false;
  else
  { 
   $.ajax({
    url      : "upgrade-form/mobile",
    type     : 'POST',
      data     : $('#upgrade_form_mobile').serialize(),
    dataType   : 'json',
    beforeSend : function() {
        $("#btn_upgrade_mobile").html(" Sending OTP... <img src='assets/images/load.gif' width='22' style='margin-top:6px; margin-right:4px;' align='left' />");
    },
    success : function(json) 
    { 
        $("#btn_upgrade_mobile").html('Continue');
        if(json.status == true && json.mobile == false)
          {
            $('#mobile_verify').css('display','none');
            $('#verified_otp').css('display','block');
            $('#password_verify').css('display','none');
            $('#mobile_notify').css('display','block');
            $('#mobile_notify').addClass(json.msg['msg_class']);
            $('#mobile_notify').removeClass('alert-danger').addClass('alert-success');
            $('#mobile_notify').html(json.msg['message']);
            //$('#upgrade_form_mobile').trigger("reset");
        //window.location.href = "upgrade/form";
      }

      else if(json.status == true && json.mobile == true)
      { 
              //$('#mobile_verify').css('display','block');
              //$('#verified_otp').css('display','none');
              //$('#password_verify').css('display','none');
              $('#mobile_notify_match').css('display','block');
              $('#mobile_notify_match').addClass(json.msg['msg_class']);
              $('#mobile_notify_match').removeClass('alert-success').addClass('alert-danger');
              $('#mobile_notify_match').html(json.msg['message']);
      }
          else
          {
              $('#mobile_verify').css('display','block');
              $('#verified_otp').css('display','none');
              $('#password_verify').css('display','none');
              $('#mobile_notify').css('display','block');
              $('#mobile_notify').addClass(json.msg['msg_class']);
              $('#mobile_notify').removeClass('alert-success').addClass('alert-danger');
              $('#mobile_notify').html(json.msg['message']);
          }
      /*window.setTimeout(function(){
        $('#password_notify').fadeOut();
        $('#password_notify').removeClass(json.msg['msg_class']);
      }, 3000);*/
      },
      error: function(data)
      {
          console.log(data);
      }
    });
  }
});



//===================Change Password=========================
$('#btn_upgrade_otp').click(function()
 { 
  var error_flag = false; 
  //-------------------------------------------------------------
  if($('#otpcode').val() == null || $('#otpcode').val() == '')
  {
      $('#otp_number_msg').html('Please Number OTP.');
      error_flag = true;
  }
  else
     $('#otp_number_msg').html('');
  //--------------------------------------------------------------
  
  //--------------------------------------------------------------
  if(error_flag)
    return false;
  else
  { 
   $.ajax({
    url      : "upgrade-form/otp",
    type     : 'POST',
      data     : $('#upgrade_form_otp').serialize(),
    dataType   : 'json',
    beforeSend : function() {
        $("#btn_upgrade_otp").html(" Verify OTP...<img src='assets/images/load.gif' width='22' style='margin-top:6px; margin-right:4px;' align='left' />");
    },
    success : function(json) 
    { 
        $("#btn_upgrade_otp").html('Continue');
        if(json.status == true)
          { 
            $('#mobile_verify').css('display','none');
            $('#verified_otp').css('display','none');
            $('#password_verify').css('display','block');
            $('#mobile_notify').css('display','none');
            $('#password_notify').css('display','block');
            $('#password_notify').addClass(json.msg['msg_class']);
            $('#password_notify').removeClass('alert-danger').addClass('alert-success');
            $('#password_notify').html(json.msg['message']);
            $('#upgrade_form_otp').trigger("reset");
      }
          else
          {
              $('#mobile_verify').css('display','none');
              $('#verified_otp').css('display','block');
              $('#password_verify').css('display','none');
              $('#mobile_notify').css('display','none');
              $('#otp_notify').css('display','block');
              $('#otp_notify').addClass(json.msg['msg_class']);
              $('#otp_notify').removeClass('alert-success').addClass('alert-danger');
              $('#otp_notify').html(json.msg['message']);
          }
      /*window.setTimeout(function(){
        $('#password_notify').fadeOut();
        $('#password_notify').removeClass(json.msg['msg_class']);
      }, 3000);*/
      },
      error: function(data)
      {
          console.log(data);
      }
    });
  }
});


//===================Change Password=========================
$('#btn_upgrade_password').click(function()
 { 
  var error_flag = false; 
  //-------------------------------------------------------------
  if($('#password').val() == null || $('#password').val() == '')
  {
      $('#password_number_msg').html('Please Enter Password.');
      error_flag = true;
  }
  else
     $('#password_number_msg').html('');
  //--------------------------------------------------------------
  
  //--------------------------------------------------------------
  if(error_flag)
    return false;
  else
  { 
   $.ajax({
    url      : "upgrade-form/password",
    type     : 'POST',
      data     : $('#upgrade_form_password').serialize(),
    dataType   : 'json',
    beforeSend : function() {
        $("#btn_upgrade_password").html(" Saving Password...  <img src='assets/images/load.gif' width='22' style='margin-top:6px; margin-right:4px;' align='left' />");
    },
    success : function(json) 
    { 
        $("#btn_upgrade_password").html('Continue');
        if(json.status == true)
          { 
            $('#mobile_verify').css('display','none');
            $('#verified_otp').css('display','none');
            $('#password_verify').css('display','block');
            $('#mobile_notify').css('display','none');
            $('#password_notify').css('display','none');
            $('#password_notify').css('display','block');
            $('#success_notify').removeClass('alert-danger').addClass('alert-success');
            $('#success_notify').html(json.msg['message']);
            $('#upgrade_form_otp').trigger("reset");
             window.setTimeout(function(){
        $('#success_notify').fadeOut();
        $('#success_notify').removeClass(json.msg['msg_class']);
      }, 3000);
            //window.location.href="home";
            window.location.href = json['url'];
      }
          else
          {
              $('#mobile_verify').css('display','none');
              $('#verified_otp').css('display','none');
              $('#password_verify').css('display','block');
              $('#mobile_notify').css('display','none');
              $('#otp_notify').css('display','none');
              $('#password_notify').css('display','none');
              $('#success_notify').addClass(json.msg['msg_class']);
              $('#success_notify').removeClass('alert-success').addClass('alert-danger');
              $('#success_notify').html(json.msg['message']);
          }
     
      },
      error: function(data)
      {
          console.log(data);
      }
    });
  }
});

</script>


