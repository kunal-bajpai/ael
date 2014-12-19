<?php
	function give_json($obj)
	{
	  return json_encode($obj);
	}

	function random_double()
	{
	  $num=0;
	  for($i=6;$i>0;$i--)
	  	$num=($num*10)+rand(1,9);
	  return $num;
	}	
	
	function hashText($text)
	{
		return md5($text);
	}
	
	function verify_email($email)
	{
		return preg_match('/^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/',$email);
	}
	
	function verify_username($username)
	{
		return preg_match('/^(?=[^\._]+[\._]?[^\._]+$)[\w\.]{6,15}$/',$username);
	}
