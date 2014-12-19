<!DOCTYPE html>
<html data-wf-site="538d4e3d9506504b0c0775d5">
<head>
  <meta charset="utf-8">
  <title>Manage the website</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="generator" content="Webflow">
  <link rel="stylesheet" type="text/css" href="css/normalize.css">
  <link rel="stylesheet" type="text/css" href="css/webflow.css">
  <link rel="stylesheet" type="text/css" href="css/troubleticket.webflow.css">
  <link rel="shortcut icon" type="image/x-icon" href="https://daks2k3a4ib2z.cloudfront.net/placeholder/favicon.ico">
</head>

<?php
	require_once("includes/init.php");
	$session = Session::get_instance();
	$db = Database::get_instance();
	$session->require_login();
	if($session->logged_in_user()->admin != 0)
		header("location:profile.php");
?>

<body style=" font-family: Arial, 'Helvetica Neue', Helvetica, sans-serif;">

<div class="w-container">
        <div class="w-nav" data-collapse="medium" data-animation="default" data-duration="400" data-contain="1">
          <?php include("header.php");?>
        </div>
      </div>

<?php
	if($_POST['type']==1)
	{
		if(is_array($_POST['problem']))
			foreach($_POST['problem'] as $problemId)
			{
				$problem = Problem::find_by_id($problemId);
				$problem->delete();
			}
		if(str_replace(" ","",$_POST['newProblem'])!='')
		{
			$problem = new Problem();
			$problem->type = $_POST['newProblem'];
			$problem->save();
		}
	}
	
	if($_POST['type']==2)
	{
		if(is_array($_POST['plant']))
			foreach($_POST['plant'] as $plantId)
			{
				$plant = Plant::find_by_id($plantId);
				$db->query("UPDATE employee SET admin = -1 WHERE admin = ".$plant->id);
				$plant->delete();
			}
		if(str_replace(" ","",$_POST['newPlant'])!='')
		{
			$plant = new Plant();
			$plant->name = $_POST['newPlant'];
			$plant->save();
		}
	}
	
	if($_POST['type']==3)
	{
		if(is_array($_POST['department']))
			foreach($_POST['department'] as $departmentId)
			{
				$department = Department::find_by_id($departmentId);
				$department->delete();
			}
		if(str_replace(" ","",$_POST['newDepartment'])!='')
		{
			$department = new Department();
			$department->name = $_POST['newDepartment'];
			$department->save();
		}
	}
	$problems = Problem::find_all();
	$plants = Plant::find_all();
	$departments = Department::find_all();
?>

<div style=" padding-left:300px; padding-right:300px;">
<form method="post">
	<h4>Check problems to delete and then save</h4>
	<input type="hidden" name="type" value="1"/>
	<?php
		if(is_array($problems))
			foreach($problems as $problem):?>
			<input type="checkbox" name="problem[]" value="<?php echo $problem->id;?>" id="problem<?php echo $problem->id;?>"/><label for="problem<?php echo $problem->id;?>"><?php echo $problem->type;?></label>
			<?php endforeach;?>
	<input type="text" placeholder="Enter new problem to add" name="newProblem"/><br/><br/>
	<input type="submit" value="Delete selected and/or Add new"/>
	<hr>
</form>

<form method="post">
	<h4>Check plants to delete and then save</h4>
	<input type="hidden" name="type" value="2"/>
	<?php
		if(is_array($plants))
			foreach($plants as $plant):?>
			<input type="checkbox" name="plant[]" value="<?php echo $plant->id;?>" id="plant<?php echo $plant->id;?>"/><label for="plant<?php echo $plant->id;?>"><?php echo $plant->name;?></label>
			<?php endforeach;?>
	<input type="text" placeholder="Enter new plant to add" name="newPlant"/><br/>
	<br>
	<input type="submit" value="Delete selected and/or Add new"/>
	<hr>
</form>

<form method="post">
	<h4>Check departments to delete and then save</h4>
	<input type="hidden" name="type" value="3"/>
	<?php
		if(is_array($departments))
			foreach($departments as $department):?>
			<input type="checkbox" name="department[]" value="<?php echo $department->id;?>" id="department<?php echo $department->id;?>"/><label for="department<?php echo $department->id;?>"><?php echo $department->name;?></label>
			<?php endforeach;?>
	<input type="text" placeholder="Enter new department to add" name="newDepartment"/><br/><br/>
	<input type="submit" value="Delete selected and/or Add new"/>
</form>
</div>

</body>
</html>
