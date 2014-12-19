<?php
	require_once("includes/init.php");
	$session = Session::get_instance();
	$session->require_login();
?>
<!DOCTYPE html>
<html data-wf-site="538d4e3d9506504b0c0775d5">
<head>
  <meta charset="utf-8">
  <title>Users list</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="generator" content="Webflow">
  <link rel="stylesheet" type="text/css" href="css/normalize.css">
  <link rel="stylesheet" type="text/css" href="css/webflow.css">
  <link rel="stylesheet" type="text/css" href="css/troubleticket.webflow.css">
  <link rel="shortcut icon" type="image/x-icon" href="https://daks2k3a4ib2z.cloudfront.net/placeholder/favicon.ico">
</head>

<body style=" font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;">

<div class="w-container">
        <div class="w-nav" data-collapse="medium" data-animation="default" data-duration="400" data-contain="1">
          <?php include("header.php");?>
        </div>
      </div>
	
	<br>

	<div style="padding-left:300px; padding-right:300px;">
<?php
	$users = Employee::find_all();
	$plants = Plant::find_all();
?>
<table border="2">
<tr><td>Name</td><td>Username</td><td>Contact</td><td>Access</td></tr>
<?php
	if(is_array($users))
		foreach($users as $user)
			if($user->id != $session->logged_in_user()->id):?>
			<tr><td><?php echo $user->first_name." ".$user->last_name;?></td><td><?php echo $user->username;?></td><td><?php echo $user->contact;?></td>
			<td>
				<select class="access" data-id="<?php echo $user->id;?>">
					<option value="-1" <?php echo ($user->admin == -1)?"selected":"";?>>Employee</option>
					<option value="0" <?php echo ($user->admin == 0)?"selected":"";?>>Master admin</option>
					<?php if(is_array($plants))
						foreach($plants as $plant):?>
					<option value="<?php echo $plant->id;?>" <?php echo ($user->admin == $plant->id)?"selected":"";?>><?php echo $plant->name." admin";?></option>
						<?php endforeach;endif;?>
				</select>
			</td>
			</tr>
</table>
	</div>
</body>

<script>
	var xmlAccess = new XMLHttpRequest();
	dropDowns = document.getElementsByClassName("access");
	for(i=0;i<dropDowns.length;i++)
		dropDowns[i].onchange = function() {
			xmlAccess.open("POST","ajax/setAccess.php",true);
			xmlAccess.setRequestHeader("Content-type","application/x-www-form-urlencoded");
			xmlAccess.send("id="+this.dataset.id+"&access="+this.value);
		}
</script>

</html>
