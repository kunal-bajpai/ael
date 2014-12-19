<?php
	class Complaint extends DatabaseObject{
		protected static $tableName = 'complaint';
		
		const ALL = 0;
		const PENDING = 1;
		const RESOLVED = 2;
		
		public static function find_history_for($user)
		{
			return self::find_by_sql("SELECT *,complaint.id id FROM complaint JOIN problem ON complaint.problem = problem.id WHERE submit_user = ".$user->id);
		}
		
		public static function find_by_type($type, $base, $offset)
		{
			$session = Session::get_instance();
			if(!$session->is_logged_in())
				return;
			if($session->logged_in_user()->admin == Employee::MASTER_ADMIN)
			{
				if($type == Complaint::ALL)
					return self::find_by_sql("SELECT * FROM ".self::$tableName." ORDER BY submit_time DESC LIMIT {$base},{$offset};");
				if($type == Complaint::PENDING)
					return self::find_by_sql("SELECT * FROM ".self::$tableName." WHERE resolve_time=0 ORDER BY submit_time DESC LIMIT {$base},{$offset};");
				if($type == Complaint::RESOLVED)
					return self::find_by_sql("SELECT * FROM ".self::$tableName." WHERE resolve_time!=0 ORDER BY submit_time DESC LIMIT {$base},{$offset};");
			}
			if($session->logged_in_user()->admin != Employee::MASTER_ADMIN && $session->logged_in_user()->admin != Employee::EMPLOYEE)
			{
				if($type == Complaint::ALL)
					return self::find_by_sql("SELECT * FROM ".self::$tableName." WHERE plant={$session->logged_in_user()->admin} ORDER BY submit_time DESC LIMIT {$base},{$offset};");
				if($type == Complaint::PENDING)
					return self::find_by_sql("SELECT * FROM ".self::$tableName." WHERE resolve_time=0 AND plant={$session->logged_in_user()->admin} ORDER BY submit_time DESC LIMIT {$base},{$offset};");
				if($type == Complaint::RESOLVED)
					return self::find_by_sql("SELECT * FROM ".self::$tableName." WHERE resolve_time!=0 AND plant={$session->logged_in_user()->admin} ORDER BY submit_time DESC LIMIT {$base},{$offset};");
			}
		}
		
		public static function count($type)
		{
			$session = Session::get_instance();
			if(!$session->is_logged_in())
				return;
			$db = Database::get_instance();
			if($session->logged_in_user()->admin == Employee::MASTER_ADMIN)
			{
				if($type == Complaint::ALL)
				{
					$res = $db->fetch_array($db->query("SELECT COUNT(*) FROM complaint"));
					return $res[0][0];
				}
				if($type == Complaint::PENDING)
				{
					$res = $db->fetch_array($db->query("SELECT COUNT(*) FROM complaint WHERE resolve_time=0;"));
					return $res[0][0];
				}
				if($type == Complaint::RESOLVED)
				{
					$res = $db->fetch_array($db->query("SELECT COUNT(*) FROM complaint WHERE resolve_time!=0;"));
					return $res[0][0];
				}
			}
			if($session->logged_in_user()->admin != Employee::MASTER_ADMIN && $session->logged_in_user()->admin != Employee::EMPLOYEE)
			{
				if($type == Complaint::ALL)
				{
					$res = $db->fetch_array($db->query("SELECT COUNT(*) FROM complaint WHERE plant = {$session->logged_in_user()->admin};"));
					return $res[0][0];
				}
				if($type == Complaint::PENDING)
				{
					$res = $db->fetch_array($db->query("SELECT COUNT(*) FROM complaint WHERE plant = {$session->logged_in_user()->admin} AND resolve_time = 0;"));
					return $res[0][0];
				}
				if($type == Complaint::RESOLVED)
				{
					$res = $db->fetch_array($db->query("SELECT COUNT(*) FROM complaint WHERE plant = {$session->logged_in_user()->admin} AND resolve_time !=0;"));
					return $res[0][0];
				}
			}
		}
	}
