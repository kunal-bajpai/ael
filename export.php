<?PHP
	require_once("includes/init.php");
	$session = Session::get_instance();
	if(!isset($_GET['startTime']) || !isset($_GET['endTime']) || !isset($_GET['type']) || !$session->is_logged_in() || $session->logged_in_user()->admin == Employee::EMPLOYEE)
		die;
	$type = '';
	if($_GET['type'] == 1)
		$type = "resolve_time = 0 AND ";
	if($_GET['type'] == 2)
		$type = "resolve_time != 0 AND ";
	if($session->logged_in_user()->admin == 0)
		$complaints = Complaint::find_by_sql("SELECT * FROM complaint WHERE ".$type."submit_time >= ".strtotime(urldecode($_GET['startTime']))." AND submit_time <= ".strtotime(urldecode($_GET['endTime'])));
	else
		$complaints = Complaint::find_by_sql("SELECT * FROM complaint WHERE ".$type."submit_time >= ".strtotime(urldecode($_GET['startTime']))." AND submit_time <= ".strtotime(urldecode($_GET['endTime']))." AND plant = ".$session->logged_in_user()->admin);
	if(is_array($complaints))
		foreach($complaints as $complaint)
		{
			$obj = array();
			$user = Employee::find_by_id($complaint->submit_user);
			$obj['ID'] = $complaint->id;
			$obj['Problem'] = Problem::find_by_id($complaint->problem)->type;
			$obj['Submitted_by'] = $user->first_name." ".$user->last_name." <".$user->username.", ".$user->contact.">";
			$obj['Plant'] = Plant::find_by_id($complaint->plant)->name;
			$obj['Submit_time'] = strftime('%d-%m-%Y %H:%M',$complaint->submit_time);
			if($complaint->resolve_time != 0)
			{
				$obj['Resolve_time'] = strftime('%d-%m-%Y %H:%M',$complaint->resolve_time);
				$obj['Gap'] = floor(($complaint->resolve_time - $complaint->submit_time) / (60 * 60 * 24)) . " days, " . floor((($complaint->resolve_time - $complaint->submit_time) % (60 * 60 * 24)) / (60 * 60)) . " hours, " . ceil((($complaint->resolve_time - $complaint->submit_time) % (60 * 60)) / (60)) . " minutes";
			}
			else
			{
				$obj['Resolve_time'] = "NA";
				$obj['Gap'] = "NA";
			}
			$obj['Details'] = $complaint->details;
			$obj['Engineer'] = $complaint->engineer;
			$obj['Remarks'] = $complaint->remarks;
			$obj['Parts_replaced'] = $complaint->parts_rep;
			$data[] = $obj;
		}
	function cleanData(&$str)
	{
		$str = preg_replace("/\t/", "\\t", $str);
		$str = preg_replace("/\r?\n/", "\\n", $str);
		if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
	}

	// filename for download
	if($session->logged_in_user()->admin>0)
		$plant = " for ".Plant::find_by_id($session->logged_in_user()->admin)->name;
	if($_GET['type'] == 0)
		$filename = "All complaints".$plant." from ".$_GET['startTime']." to ".$_GET['endTime'].".xls";
	if($_GET['type'] == 1)
		$filename = "Pending complaints".$plant." from ".$_GET['startTime']." to ".$_GET['endTime'].".xls";
	if($_GET['type'] == 2)
		$filename = "Resolved complaints".$plant." from ".$_GET['startTime']." to ".$_GET['endTime'].".xls";
	header("Content-Disposition: attachment; filename=\"$filename\"");
	header("Content-Type: application/vnd.ms-excel");

	$flag = false;
	
	if(is_array($data))
	foreach($data as $row) {
		if(!$flag) {
			// display field/column names as first row
			echo implode("\t", array_keys($row)) . "\r\n";
			$flag = true;
		}
		array_walk($row, 'cleanData');
		echo implode("\t", array_values($row)) . "\r\n";
	}
	else
	{
		if($_GET['type']==0)
			echo "No complaints submitted between ".$_GET['startTime']." and ".$_GET['endTime'];
		if($_GET['type']==0)
			echo "No pending complaints submitted between ".$_GET['startTime']." and ".$_GET['endTime'];
		if($_GET['type']==0)
			echo "No resolved complaints submitted between ".$_GET['startTime']." and ".$_GET['endTime'];
	}
	exit;
?>
