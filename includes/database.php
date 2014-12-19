<?php

class Database{
private $con,$lastQuery;
private static $db;

function __construct()
{
  $this->open_connection();
}

public static function get_instance()
{
	if(!isset(self::$db))
		self::$db = new Database();
	return self::$db;
}

public function last_query() //return last executed query
{
  if(isset($this->lastQuery))
    return $this->lastQuery;
  else
    return false;
}

public function open_connection() //open a connection and select database
{
	if(!isset($this->con))
	{
	  $this->con=mysql_connect(HOST,USER,PASS) or die($this->last_query()."Could not connect : ".mysql_error());
  mysql_select_db(DB) or die($this->last_query()."Could not connect : ".mysql_error());
  	}
}

public function query($query)  //execute a query
{
  $this->lastQuery="<pre><br/>{$query}<br/></pre>";
  if($result=mysql_query($query))
    return $result;
  else
    die($this->last_query()."SQL Error : ".mysql_error());
}

public function fetch_array($result_set)  //accept result set and return it as a 2 dimensional array
{
  while($row=mysql_fetch_array($result_set))
    $result_array[]=$row;
  if(isset($result_array))
    return $result_array;
  else
    return NULL;
}

public function insert_id()  //return id last inserted in the database
{
  return mysql_insert_id();
}

public function num_rows($result)  //return number of rows in result set
{
  return mysql_num_rows($result);
}

public function escape_value($value)  //escape dangerous characters to prevent injection
{
  $magicQuotesActive=get_magic_quotes_gpc();  //check if magic quotes os on
  if(function_exists('mysql_real_escape_string')) //check if php is new enough
  {
    if($magicQuotesActive)
      stripslashes($value);
    return mysql_real_escape_string(trim($value));
  }
  elseif(!$magicQuotesActive)
    return addslashes(trim($value));
  return trim($value);
}
}
