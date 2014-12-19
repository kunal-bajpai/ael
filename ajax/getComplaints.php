<?php
	/*	USED ON:List of new projects for editors
		ACCEPTS:POST(type of project ie basic or advanced, base and offset for pagination)
		ACTION:Checks if an editor is logged in
		RESPONSE:Array of projects, current page number and last page number to update requesting page with in case any change in db is made {projects:[Project{}],currentPage:int, endPage:int}*/
	require_once("../includes/init.php");
	if(isset($_POST['type']) && isset($_POST['base']) && isset($_POST['offset']))
	{
		$complaints = Complaint::find_by_type($_POST['type'], $_POST['base'], $_POST['offset']);
		if(is_array($complaints))
			foreach($complaints as $complaint)
			{
				$obj = new stdClass();
				$obj->id = $complaint->id;
				$obj->problem = Problem::find_by_id($complaint->problem)->type;
				$obj->submit_user = Employee::find_by_id($complaint->submit_user)->username;
				$obj->plant = Plant::find_by_id($complaint->plant)->name;
				$obj->submit_time = strftime('%d-%m-%Y %H:%M',$complaint->submit_time);
				if($complaint->resolve_time != 0)
				{
					$obj->resolve_time = strftime('%d-%m-%Y %H:%M',$complaint->resolve_time);
					$obj->gap = floor(($complaint->resolve_time - $complaint->submit_time) / (60 * 60 * 24)) . " days, " . floor((($complaint->resolve_time - $complaint->submit_time) % (60 * 60 * 24)) / (60 * 60)) . " hours, " . ceil((($complaint->resolve_time - $complaint->submit_time) % (60 * 60)) / (60)) . " minutes";
				}
				else
				{
					$obj->resolve_time = 0;
					$obj->gap = "NA";
				}
				$objs[] = $obj;
			}
		$result['complaints']=$objs;
		$result['endPage']=ceil(Complaint::count($_POST['type']) / $_POST['offset']);
		if($result['endPage'] == 0)
			$result['endPage'] = 1;
		$result['currentPage'] = ceil(($_POST['base'] + 1) / $_POST['offset']);
		echo give_json($result);
	}
?>
