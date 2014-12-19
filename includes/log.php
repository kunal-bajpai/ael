<?php
class Log{
protected static $logFile="log";
public static function log_action($action)
{
	$session=Session::get_instance();
	$user=$session->logged_in_user();
	//file_put_contents(static::$logFile,strftime("%Y/%d/%m %H:%M:%S",time()+TIME_DIFF)." ".$user->username.": ".$action."<br/><br/>\r\n",FILE_APPEND);
}

public static function show_logs()
{
	return file_get_contents(static::$logFile);
}

public static function clear_logs()
{
	$session=Session::get_instance();
	$user=$session->logged_in_user();
	//file_put_contents(static::$logFile,strftime("%Y/%d/%m %H:%M:%S",time()+TIME_DIFF)." ".$user->username.": Logs cleared.<br/><br/>\r\n");
}

}
