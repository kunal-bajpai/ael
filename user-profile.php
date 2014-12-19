<?php
	require_once("includes/init.php");
	$session = Session::get_instance();
	$session->require_login();
	$user = $session->logged_in_user();
	if(sizeof($_POST)>0)
	{
		$user->get_values();
		if($_POST['password']!='')
			if($_POST['password'] == $_POST['rep_password'])
				$user->password = $_POST['password'];
			else
				$passMismatch = true;
		else
			unset($user->password);
		$user->save();
	}
?>
<!DOCTYPE html>
<html data-wf-site="538d4e3d9506504b0c0775d5">
<head>
  <meta charset="utf-8">
  <title>troubleticket - user-profile</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="generator" content="Webflow">
  <link rel="stylesheet" type="text/css" href="css/normalize.css">
  <link rel="stylesheet" type="text/css" href="css/webflow.css">
  <link rel="stylesheet" type="text/css" href="css/troubleticket.webflow.css">
  <link rel="shortcut icon" type="image/x-icon" href="https://daks2k3a4ib2z.cloudfront.net/placeholder/favicon.ico">
</head>
<body>
  <div>
    <div>
      <div class="w-container">
        <div class="w-nav" data-collapse="medium" data-animation="default" data-duration="400" data-contain="1">
          <?php include("header.php");?>
        </div>
      </div>
    </div>
  </div>
  <div class="w-row">
    <div class="w-col w-col-3"></div>
    <div class="w-col w-col-6">
      <div class="w-form">
        <form id="email-form" name="email-form" data-name="Email Form" method="post">
          <h3>Please fill in your details</h3>
          <?php echo ($passMismatch) ? "Passwords mismatch" : "";?>
          <label for="name">First Name:</label>
          <input class="w-input" id="name" type="text" placeholder="First name" value="<?php echo $user->first_name;?>" name="first_name" data-name="Name" required="required" autofocus="autofocus">
          <label for="name-2">Last Name:</label>
          <input class="w-input" id="name-2" type="text" placeholder="Last Name" name="last_name" value="<?php echo $user->last_name;?>" required="required" data-name="Name">
          <label for="number">Contact Number:</label>
          <input class="w-input" id="number" type="text" placeholder="Contact Number" name="contact" required="required" data-name="Number"  value="<?php echo $user->contact;?>">
          <label for="password">Password</label>
          <input class="w-input" id="password" type="password" placeholder="Password" name="password" data-name="Password">
          <label for="password-2">Repeat Password:</label>
          <input class="w-input" id="password-2" type="password" placeholder="Repeat password" name="rep_password" data-name="Password">
          <input class="w-button" type="submit" value="Save">
        </form>
        <div class="w-form-done">
          <p>Thank you! Your submission has been received!</p>
        </div>
        <div class="w-form-fail">
          <p>Oops! Something went wrong while submitting the form :(</p>
        </div>
      </div>
    </div>
    <div class="w-col w-col-3"></div>
  </div>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
  <script type="text/javascript" src="js/webflow.js"></script>
</body>
</html>
