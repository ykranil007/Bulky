<!doctype html>
<html>
<head>
<base href="<?php echo base_url()?>">
<meta charset="utf-8">
<title>BulknMore|Facebook</title>
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
  <?php if($this->session->userdata('user_social_issue_message')){?>
  	<div class="text-center" style="color:red;"><?php echo $this->session->userdata('user_social_issue_message');?></div>
  	<?php } else {?>
    <div class="text-center" style="color:red;">The Access Token Supplied Is Invalid </div>
    <?php }?>
 </div>
</body>
<!--JS-->
<script src="assets/js/jquery-1.11.3.min.js"></script>
</html>
<script type="text/javascript">
    $( document ).ready(function() {
        setTimeout(function(){ 
            location.href = '<?php echo base_url().'#login'?>';
        }, 3000);
    });
</script>