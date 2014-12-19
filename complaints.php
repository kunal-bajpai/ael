
<!DOCTYPE html>
<html data-wf-site="538d4e3d9506504b0c0775d5">
<head>
  <meta charset="utf-8">
  <title>Complaints</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="generator" content="Webflow">
  <link rel="stylesheet" type="text/css" href="css/normalize.css">
  <link rel="stylesheet" type="text/css" href="css/complaints-webflow.css">
  <link rel="stylesheet" type="text/css" href="css/troubleticket.webflow.css">
	<link rel="stylesheet" type="text/css" href="css/jquery.datetimepicker.css"/ >
  <link rel="shortcut icon" type="image/x-icon" href="https://daks2k3a4ib2z.cloudfront.net/placeholder/favicon.ico">
</head>

<?php
	require_once("includes/init.php");
	$session = Session::get_instance();
	$session->require_login();
	if($session->logged_in_user()->admin == -1)
		header("location:profile.php");
?>

<body>

<div class="w-container">
        <div class="w-nav" data-collapse="medium" data-animation="default" data-duration="400" data-contain="1">
          <?php include("header.php");?>
        </div>
      </div>

<div  style=" padding-left:170px;">

<div id='pagerHeader'><h5>Export to Excel file</h5>
	Start <input id="startTime" type="text" > End <input id="endTime" type="text" > <button onclick="exportExcel()">Export to .xls</button><br/>
	<input type='radio' class='typeRadio' name="type1" id='allRadio1' value='0' checked/><label for='allRadio1'>All</label>
	<input type='radio' class='typeRadio' name="type1" id='pendRadio1' value='1'/><label for='pendRadio1'>Pending</label>
	<input type='radio' class='typeRadio' name="type1" id='resRadio1' value='2'/><label for='resRadio1'>Resolved</label><br/><br/><br/>
	<button onclick="prev()" class='pagbuttons'>&lt; Prev</button> <span class='startPage'></span> Page <input type='text' size="3" class='currentPage'> of <span class='endPage'></span> <button onclick="next()" class='pagbuttons'>Next &gt;</button></br>
	<br>Results per page : <select class="resultPerPage"><option selected>3</option><option>5</option><option>10</option><option>20</option></select><br/><br/>
</div>
<table border="2" id="complaintsTable">
	<tr id="rowHeader"><td>ID</td><td>Type</td><td>Plant</td><td>User</td><td>Submitted on</td><td>Resolved on</td><td>Time taken to resolve</td><td></td></tr>
	<tr id="dummyDiv" style="display:none"><td class="id"></td><td class="type"></td><td class="plant"></td><td class="user"></td><td class="submitted_on"></td><td class="resolved_on"><td class="gap"></td><td class="view_btn"><a>View</a></td></tr>
</table>
<div id='pagerFooter'><br/>
	<input type='radio' class='typeRadio' name='type2' id='allRadio2' value='0' checked/><label for='allRadio2'>All</label>
	<input type='radio' class='typeRadio' name='type2' id='pendRadio2' value='1'/><label for='pendRadio2'>Pending</label>
	<input type='radio' class='typeRadio' name='type2' id='resRadio2' value='2'/><label for='resRadio2'>Resolved</label><br/>
	<button onclick="prev()" class='pagbuttons'> Prev</button> <span class='startPage'></span> Page <input type='text' size="3" class='currentPage'> of <span class='endPage' style="clear:both"></span> <button class='pagbuttons' onclick="next()">Next ></button></br>	
	<br>Results per page : <select class="resultPerPage"><option selected>3</option><option>5</option><option>10</option><option>20</option></select><br/>
</div>
</div>
</body>
<script src="js/complaints.js"></script>
<script src="js/jquery.js"></script>
<script src="js/jquery.datetimepicker.js"></script>
<script>
	jQuery('#startTime').datetimepicker({format:'d-m-Y H:i', closeOnDateSelect:true});
	jQuery('#endTime').datetimepicker({format:'d-m-Y H:i', closeOnDateSelect:true});
	function exportExcel()
	{
		window.open("export.php?"+encodeURI("startTime="+document.getElementById("startTime").value+"&endTime="+document.getElementById("endTime").value+"&type="+type));
	}
</script>
</html>
