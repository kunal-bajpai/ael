<?php
	require_once("includes/init.php");
	$session = Session::get_instance();
	$session->require_login();
?>
<!DOCTYPE html>
<html data-wf-site="538d4e3d9506504b0c0775d5">
<head>
  <meta charset="utf-8">
  <title>Home</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="generator" content="Webflow">
  <link rel="stylesheet" type="text/css" href="css/normalize.css">
  <link rel="stylesheet" type="text/css" href="css/webflow.css">
  <link rel="stylesheet" type="text/css" href="css/troubleticket.webflow.css">
  <script type="text/javascript" src="js/modernizr.js"></script>
  <link rel="shortcut icon" type="image/x-icon" href="https://daks2k3a4ib2z.cloudfront.net/placeholder/favicon.ico">
</head>
<body>
  <div>
	<div class="w-container">
	  <div>
		<div class="w-nav" data-collapse="medium" data-animation="default" data-duration="400" data-contain="1">
		  <?php include("header.php");?>
		</div>
	  </div>
	  
	  <br><br><br>
	  
	  <div class="w-tabs" data-duration-in="300" data-duration-out="100">
		<div class="w-tab-menu">
		  <a class="w-tab-link w--current w-inline-block" data-tab="complaint_tab">
			<div>Complaint Form</div>
		  </a>
		  <a class="w-tab-link w-inline-block" data-tab="history_tab">
			<div>History</div>
		  </a>
		</div>
		
		<br>
		
		<div class="w-tab-content">
		  <div class="w-tab-pane w--tab-active" id="complaint_tab" data-w-tab="Tab 1">
			<div class="w-row">
			  <div class="w-col w-col-6">
			  <?php
				  if(isset($_POST['plant']) && isset($_POST['dept']) && isset($_POST['problem']))
					{
						$complaint = new Complaint();
						$complaint->get_values();
						$complaint->submit_user = $session->logged_in_user()->id;
						$complaint->submit_time = time();
						$complaint->save();
						echo "Complaint #".$complaint->id." lodged. Please check history for details.";
					}
				?>				<div class="w-form">
				  <form id="email-form" name="email-form" data-name="Email Form" method="post">
					<label for="field">Plant:</label>
					<select class="w-select" id="field" name="plant" required="required">
					  <option value="">Select one...</option>
					  <?php
					  	$plants = Plant::find_all();
					  	if(is_array($plants))
					  		foreach($plants as $plant):
					  ?>
					  <option value="<?php echo $plant->id;?>"><?php echo $plant->name;?></option>
					  <?php
					  	endforeach;
					  ?>
					</select>
					<label for="field-2">Department:</label>
					<select class="w-select" id="field-2" name="dept" required="required">
					  <option value="">Select one...</option>
					  <?php
					  	$depts = Department::find_all();
					  	if(is_array($depts))
					  		foreach($depts as $dept):
					  ?>
					  <option value="<?php echo $dept->id;?>"><?php echo $dept->name;?></option>
					  <?php
					  	endforeach;
					  ?>
					</select>
					<label for="field-3">Type of problem:</label>
					<select class="w-select" id="field-3" name="problem" required="required">
					  <option value="">Select one...</option>
					  <?php
					  	$problems = Problem::find_all();
					  	if(is_array($problems))
					  		foreach($problems as $problem):
					  ?>
					  <option value="<?php echo $problem->id;?>"><?php echo $problem->type;?></option>
					  <?php
					  	endforeach;
					  ?>
					</select>
					<label for="field-4">Details of problem:</label>
					<textarea class="w-input" id="field-4" placeholder="Problem details:" name="details"></textarea>
					<input class="w-button" type="submit" value="Submit" data-wait="Please wait...">
				  </form>
				  <div class="w-form-done">
					<p>Thank you! Your submission has been received!</p>
				  </div>
				  <div class="w-form-fail">
					<p>Oops! Something went wrong while submitting the form :(</p>
				  </div>
				</div>
			  </div>
			  <div class="w-col w-col-6"></div>
			</div>
		  </div>
		  <div id="history_tab" class="w-tab-pane" data-w-tab="Tab 2">
		  <table style="width:800px">
			<tr>
				<th>ID</th>
			  <th>Title</th>
			  <th>Submitted on</th>		
			  <th>Status</th>
			  <th></th>
			</tr>
			<?php
				$complaints = Complaint::find_history_for($session->logged_in_user());
				if(is_array($complaints))
					foreach($complaints as $complaint):
			?>
			<tr>
				<td><?php echo $complaint->id;?></td>
				<td><?php echo $complaint->type;?></td>
				<td><?php echo strftime("%Y/%d/%m %H:%M:%S",$complaint->submit_time);?></td>
				<td><?php echo ($complaint->resolve_time == 0) ? "Pending" : "Resolved on ".strftime("%Y/%d/%m %H:%M:%S",$complaint->resolve_time);?> </td>
				<td><a href="complaint.php?id=<?php echo $complaint->id;?>">View</a></td>
			</tr>
			<?php endforeach;?>
			</table>
		  </div>
		</div>
	  </div>
	</div>
  </div>
  <script>
  	function addClass(elem,className)
	{
		elem.setAttribute('class',elem.className.replace(' '+className,''));;
		elem.setAttribute('class',elem.className+' '+className);
	}

	function removeClass(elem,className)
	{
		elem.setAttribute('class',elem.className.replace(' '+className,''));
	}
	
	tabButtons = document.getElementsByClassName("w-tab-link");
	for(i=0;i<tabButtons.length;i++)
	{
		tabButtons[i].onclick = function() {
			buttons = document.getElementsByClassName("w-tab-link");
			for(i=0;i<buttons.length;i++)
			{
				removeClass(buttons[i],"w--current");
				removeClass(document.getElementById(buttons[i].dataset.tab),"w--tab-active");
			}
			addClass(document.getElementById(this.dataset.tab),"w--tab-active");
			addClass(this,"w--current");
		}
	}
  </script>
  <script type="text/javascript" src="../pp/js/jquery-1.11.0.min.js"></script>
</body>
</html>
