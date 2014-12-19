<?php
	require_once("includes/init.php");
	$session = Session::get_instance();
	if($session->is_logged_in())
		header("location:profile.php");
?>
<!DOCTYPE html>
<html data-wf-site="538d4e3d9506504b0c0775d5">
<head>
  <meta charset="utf-8">
  <title>troubleticket</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="generator" content="Webflow">
  <link rel="stylesheet" type="text/css" href="css/normalize.css">
  <link rel="stylesheet" type="text/css" href="css/webflow.css">
  <link rel="stylesheet" type="text/css" href="css/troubleticket.webflow.css">
  <link rel="shortcut icon" type="image/x-icon" href="https://daks2k3a4ib2z.cloudfront.net/placeholder/favicon.ico">
</head>
<body>
  <div>
	<div class="w-container">
	  <div class="w-row">
		<div class="w-col w-col-2">
		  <h2>Sign In</h2>
		</div>
		
		<br>
		
		<div class="w-col w-col-4">
		  <div class="w-form">
			<form id="email-form" name="email-form" data-name="Email Form" method="post">
			  <input class="w-input" id="name" type="text" placeholder="Username" name="login_username" data-name="Name" required autofocus="autofocus">
			</form>
			<?php
				if(isset($_POST['login_username']) && isset($_POST['login_password']))
				{
					$user = Employee::find_by_username($_POST['login_username']);
					if(isset($user))
					{
						$user->password = $_POST['login_password'];
						if($user->authenticate())
						{
							$session->login($user);
							if(isset($_GET['fwd']))
								header("location:".urldecode($_GET['fwd']));
							else
								header("location:profile.php");
						}
						else
							echo "Invalid password";
					}
					else
						echo "User unregistered";
				}
			?>
			<div class="w-form-done">
			  <p>Thank you! Your submission has been received!</p>
			</div>
			<div class="w-form-fail">
			  <p>Oops! Something went wrong while submitting the form :(</p>
			</div>
		  </div>
		</div>
		<div class="w-col w-col-4">
		  <div class="w-form">
			  <input class="w-input" id="password" type="password" placeholder="Password" name="login_password" data-name="Password" required="required" form="email-form">
		  </div>
		</div>
		<div class="w-col w-col-2">
		  <div class="w-form">
			<input class="w-button submit" form="email-form" type="submit" value="Sign in" data-wait="Please wait...">
			<div class="w-form-done">
			  <p>Thank you! Your submission has been received!</p>
			</div>
			<div class="w-form-fail">
			  <p>Oops! Something went wrong while submitting the form :(</p>
			</div>
		  </div>
		</div>
	  </div>
	</div>
  </div>
  <div></div>
  
  <br><hr><br>
  
  <div class="w-container">
	<div class="w-row">
	  <div class="w-col w-col-3"></div>
	  <div class="w-col w-col-6">
		<h3>Not a member yet? Register now.</h3> 
		
		<br><br>
<?php
	if(isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['username']) && isset($_POST['contact']) && isset($_POST['password']) && isset($_POST['repPass']))
	{
		$user = Employee::find_by_username($_POST['username']);
		if(!isset($user))
		{
				if($_POST['password'] == $_POST['repPass'])
				{
					$user = new Employee();
					$user->get_values();
					$user->save();
					echo "Registered successfully. You may now login.";
				}
				else
					echo "Passwords mismatch.";
		}
		else
			echo "User already registered";
	}
?>		
		<div class="w-form">
		  <form id="email-form-4" method="post">
			<label for="name-3">First name:</label>
			<input class="w-input" id="name-3" type="text" placeholder="Enter your first name" name="first_name" required="required" data-name="name">
			<label for="name-4">Last name:</label>
			<input class="w-input" id="name-4" type="text" placeholder="Enter your last name" name="last_name" required="required" data-name="name">
			<label for="name-5">Contact number:</label>
			<input class="w-input" id="name-5" type="text" placeholder="Enter your contact number" name="contact" required="required" data-name="name">
			<label for="name-2">Username:</label>
			<input class="w-input" id="name-2" type="text" placeholder="username" name="username" data-name="Name 2" required="required">
			<label for="pass-2">Password:</label>
			<input class="w-input" id="pass-2" type="password" placeholder="Password" name="password" data-name="pass 2" required="required">
			<label for="pass-3">Repeat Password:</label>
			<input class="w-input" id="pass-3" type="password" placeholder="Repeat password" name="repPass" data-name="pass 2" required="required">
			<br><br>
			<input class="w-button" type="submit" value="Register" data-wait="Please wait...">
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
  </div>
  <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
</body>
</html>
