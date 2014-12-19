<?php

class DatabaseObject{

public $id,$fields;

public function __construct()
{
if(!isset($this->fields))
  $this->populate_fields();
}

public function get_values() //get all post and get variables in the object
{
  if(isset($_POST))
    foreach($_POST as $key=>$value)
      $this->$key=$value;
  if(isset($_GET))
    foreach($_GET as $key=>$value)
      $this->$key=$value;
}

public function populate_fields() //save all db fields of this object as an array
{
  $db=Database::get_instance();
  $result=$db->query("SHOW FIELDS FROM ". static::$tableName);
  $result_array=$db->fetch_array($result);
  if(is_array($result_array))
      foreach($result_array as $row)
        if($row['Field']!='id')
          $this->fields[]=$row['Field'];
}

public static function find_by_id($id)
{
  $result_object = self::find_by_sql("SELECT * FROM ".static::$tableName." WHERE id={$id}");
  return $result_object[0];
}

public static function find_all() //return an array of all objects
{
  return self::find_by_sql("SELECT * FROM ".static::$tableName);
}

public static function find_by_sql($query) //return an array of found objects
{
  $db=Database::get_instance();
  $result=$db->query($query);
  if($db->num_rows($result)>0)
    return static::instantiate($result);
  else 
    return NULL;
}

public static function instantiate($result) //take a result set and return an array of objects with values from result set as its attributes
{
  $db=Database::get_instance();
  $result_set=$db->fetch_array($result);
  foreach($result_set as $row)
  {
    $obj=new static;
    foreach($row as $key=>$value)
      $obj->$key=$value;
    $result_objects[]=$obj;
  }  
  return $result_objects;
}

public function create() //insert the object into database with each attribute inserted in fields
{
  $db=Database::get_instance();
  $sql="INSERT INTO ".static::$tableName." (";
  if(is_array($this->fields))
	  foreach($this->fields as $field)
      if(isset($this->$field))
	       $sql.=$field.",";
  $sql=substr($sql,0,strlen($sql)-1);
  $sql.=") values (";
  if(is_array($this->fields))
	  foreach($this->fields as $field)
      if(isset($this->$field))
	       $sql.="'".$db->escape_value($this->$field)."',";
  $sql=substr($sql,0,strlen($sql)-1);
  $sql.=");";
  if($db->query($sql))
  {
    $this->id=$db->insert_id();
    Log::log_action("New entry in ".static::$tableName." (id=".$this->id.")");
    return true;
  }
  else
    return false;
}

public function update() //update existing record
{
  $db=Database::get_instance();
  $sql="UPDATE ".static::$tableName." SET ";
  foreach($this->fields as $field)
    if(isset($this->$field))
          $sql.=$field." = '".$db->escape_value($this->$field)."',";
  $sql=substr($sql,0,strlen($sql)-1);
  $sql.=" WHERE id='".$this->id."'";
  if($db->query($sql))
  {
    Log::log_action("Entry updated in ".static::$tableName." (id=".$this->id.")");
    return true;
  }
  else
    return false;
}

public function save() //if a record exists then update it else create it
{
  if(isset($this->id))
    return $this->update();
  else
    return $this->create();
}

public function delete() //delete a record from the db
{
  $db=Database::get_instance();
  $sql="DELETE FROM ".static::$tableName.' WHERE id='.$this->id;
  if($db->query($sql))
  {
    Log::log_action("Entry deleted from ".static::$tableName." (id=".$this->id.")");
    return true;
  }
  else
    return false;
}
}
