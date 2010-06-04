<?php 
/*
 * DIT Alumni Club Membership Management
 * Object-Relational Mapper class
 * 
 * Stelios Karabasakis
 */

class Studies_Undergr {
	
	// Table and Data
	private $table = "memberinfo_studies_undergr";
	private $data = array (
		'entryyear'			=> NULL,
		'graduationyear'	=> NULL
	);

	
	// Variables
	private $user_id;
	
	
	function __construct($is_new_record, $user_id, $request_array = null)
	{
		global $db, $db_table_prefix;
		
		$this->user_id = $user_id;
		
		if ($is_new_record) {
			foreach ($this->data as $key => $value) {
				$this->data[$key] = $request_array[$key];
			}
		}
		else
		{
			$sql = "SELECT * FROM `".$db_table_prefix.$table."` WHERE `User_ID` = ".$user_id;
			$result = $db->sql_query($sql);
			$row = $db->sql_fetchrow($result);
			
			foreach ($this->data as $key => $value) {
				$this->data[$key] = $row[$key];
			}
		}
	}
	
	function insert() {
		global $db, $db_table_prefix; 
		
		$fields = "`User_ID`";
		$values = "'.$this->user_id.'";
		foreach($this->data as $field => $value) {
			$fields .= ", `".$field."`";
			$values .= ", '".$db->sql_escape($value)."'"; 
		}
		
		$sql  = "INSERT INTO `".$db_table_prefix.$this->table."` (".$fields.") VALUES (".$values.")";

		return $db->sql_query($sql);
	}
	
	function update_all() {
		global $db, $db_table_prefix; 
		
		$pairs = "";
		foreach($this->data as $field => $value) {
			$pairs .= "`".$field."`='".$db->sql_escape($value)."'";
			$pairs_no_trailing_comma = $pairs;
			$pairs .=", ";
		}
		
		$sql  = "UPDATE `".$db_table_prefix.$this->table."` SET ".$pairs_no_trailing_comma;
		$sql .= " WHERE `User_ID` = '".$this->user_id."'";
		
		return $db->sql_query($sql);
	}
}




?>