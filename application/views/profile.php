<html>
<head>
<title>CodeIgniter : Login Facebook via Oauth 2.0</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>css/style.css">
<link href='http://fonts.googleapis.com/css?family=Source+Sans+Pro|Open+Sans+Condensed:300|Raleway' rel='stylesheet' type='text/css'>
</head>
<body>
<div id="main">
<div id="login">
<h2> <?php echo "<a href=".$user_profile['link']." target='_blank' ><img class='fb_profile' src="."https://graph.facebook.com/".$user_profile['id']."/picture".">"."</a>"."<p class='profile_name'>Welcome ! <em>".$user_profile['name']."</em></p>";
echo "<a class='logout' href='$logout_url'>Logout</a>";
?></h2>
<hr/>
<h3><u>Profile</u></h3>
<?php
echo "<p>First Name : ".$user_profile['first_name']."</p>";
echo "<p>Last Name : ".$user_profile['last_name']."</p>";
echo "<p>Gender : ".$user_profile['gender']."</p>";
echo "<p>Facebook URL : "."<a href=".$user_profile['link']." target='_blank'"."> https://www.facebook.com/".$user_profile['id']."</a></p>";
?>
</div>
</div>
</body>
</html>