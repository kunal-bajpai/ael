<?php
	class User extends DatabaseObject {
		protected static $usernameField='username',$passwordField='password'; 
 
		public function authenticate()
		{
			$db=Database::get_instance(); 
			$pwdField = static::$passwordField;
			$usernameField = static::$usernameField;
			$pass=$db->fetch_array($db->query("SELECT ".static::$passwordField." FROM ".static::$tableName." WHERE ".static::$usernameField."='".$this->$usernameField."' LIMIT 1"));
			if($this->$pwdField==$pass[0][0])
			{
			$result_id=$db->fetch_array($db->query("SELECT ID FROM ".static::$tableName." WHERE ".static::$usernameField."='".$this->user."' LIMIT 1"));
			$this->id=$result_id[0][0];
			return true;
			}
			else
			return false;
		}
		
		public static function find_by_username($username)
		{
			$result_object= static::find_by_sql("SELECT * FROM ".static::$tableName." WHERE ".static::$usernameField."='{$username}';"); 
			return $result_object[0];
		}
	}
