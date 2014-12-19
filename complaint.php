<?php
	require_once("includes/init.php");
	$session = Session::get_instance();
	$session->require_login();
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
  <link rel="shortcut icon" type="image/x-icon" href="https://daks2k3a4ib2z.cloudfront.net/placeholder/favicon.ico">
</head>

<body style="font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;">

	<div class="w-container">
        <div class="w-nav" data-collapse="medium" data-animation="default" data-duration="400" data-contain="1">
          <?php include("header.php");?>
        </div>
      </div>

<div style=" padding-left:300px; padding-right:300px;">
<?php

	if(isset($_GET['id']))
		$complaint = Complaint::find_by_id($_GET['id']);
	if(!isset($complaint))
		die("Invalid complaint id");
	if(isset($_POST['engineer']) && $complaint->resolve_time==0)
	{
		$complaint->engineer = $_POST['engineer'];
		$complaint->parts_rep = $_POST['parts_rep'];
		$complaint->remarks = $_POST['remarks'];
		$complaint->resolve_time = time();
		$complaint->save();
	}
?>

<table>
<tr><td>Type : </td><td><?php echo Problem::find_by_id($complaint->problem)->type;?></td></tr><br/>
<tr><td>Submit time : </td><td><?php echo strftime('%d-%m-%Y %H:%M',$complaint->submit_time);?></td></tr><br/>
<tr><td>Submitted by : </td><td><?php $user = Employee::find_by_id($complaint->submit_user); echo $user->first_name." ".$user->last_name." &lt".$user->username.", ".$user->contact."&gt";?></td></tr><br/>
<tr><td>Plant : </td><td><?php echo Plant::find_by_id($complaint->plant)->name;?></td></tr><br/>
<tr><td>Details : </td><td><?php echo $complaint->details;?></td></tr>
</table>

<?php
	if($complaint->resolve_time == 0 && ($session->logged_in_user()->admin == $complaint->plant || $session->logged_in_user()->admin == 0)):?>
<form method="post">
<table>
<tr><td>Engineer name </td><td><input type="text" name="engineer" required/></td></tr><br/>
<tr><td>Remarks </td><td><textarea name="remarks"></textarea></td></tr><br/>
<tr><td>Parts replaced </td><td><textarea name="parts_rep"></textarea></td></tr><br/>
</table>
	<input type="submit" value="Close ticket" />
</form>
<?php elseif($complaint->resolve_time != 0):?>
Resolved at : <?php echo strftime('%d-%m-%Y %H:%M',$complaint->resolve_time);?><br/>
Resolved by : <?php echo $complaint->engineer;?><br/>
Remarks : <?php echo $complaint->remarks;?><br/>
Parts replaced : <?php echo $complaint->parts_rep;?>
<?php endif;?>
</div>
</body>
