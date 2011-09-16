<?php
/*
+-----------------------------------------------------------------------------
|   VS FRAMEWORK 3.0.0
|	Author: BabyWolf
|	Homepage: http://vietsol.net
|	If you use this code, please don't delete these comment line!
|	Start Date: 21/09/2004
|	Finish Date: 22/09/2004
|	Modified Start Date: 07/02/2007
|	Modified Finish Date: 10/02/2007
+-----------------------------------------------------------------------------
*/

class db_driver {
	
	var $obj = array ("sql_database" => "vsf", "sql_user" => "root", "sql_pass" => "", "sql_host" => "localhost", "sql_port" => "", "persistent" => "0", "sql_tbl_prefix" => "vsf_", "cached_queries" => array (), 'shutdown_queries' => array (), 'debug' => 0, 'use_shutdown' => 1, 'query_cache_file' => '' );
	
	var $query_id = "";
	var $connection_id = "";
	var $query_count = 0;
	var $record_row = array ();
	var $return_die = 0;
	var $error = "";
	var $failed = 0;
	var $sql = "";
	var $cur_query = "";
	var $manual_addslashes = 0;
	var $is_shutdown = 0;
	var $loaded_classes = array ();
	var $prefix_changed = 0;
	var $require = 0;

	function __construct() {
	
	}

	/*========================================================================*/
	// Connect to the database  
	/*========================================================================*/
	
	function connect() {
		//--------------------------
		// If debug, no shutdown....
		//--------------------------
		

		if ($this->obj ['debug'])
			$this->obj ['use_shutdown'] = 0;
		
		//--------------------------
		// Done SQL prefix yet?
		//--------------------------
		

		if (! defined ( 'SQL_PREFIX' )) {
			$this->obj ['sql_tbl_prefix'] = $this->obj ['sql_tbl_prefix'] ? $this->obj ['sql_tbl_prefix'] : 'vsf_';
			define ( 'SQL_PREFIX', $this->obj ['sql_tbl_prefix'] );
		}
		
		//--------------------------
		// Load query file
		//--------------------------
		

		if ($this->obj ['query_cache_file']) {
			require_once ($this->obj ['query_cache_file']);
			$this->sql = new sql_queries ( &$this );
		}
		
		//--------------------------
		// Connect
		//--------------------------
		

		if ($this->obj ['persistent']) {
			$this->connection_id = mysql_pconnect ( $this->obj ['sql_host'], $this->obj ['sql_user'], $this->obj ['sql_pass'] );
		} else {
			$this->connection_id = mysql_connect ( $this->obj ['sql_host'], $this->obj ['sql_user'], $this->obj ['sql_pass'] );
		}
		
		if (! $this->connection_id) {
			$this->fatal_error ();
			return FALSE;
		}
		
		if (! mysql_select_db ( $this->obj ['sql_database'], $this->connection_id )) {
			$this->fatal_error ();
			return FALSE;
		}
		
		return TRUE;
	}

	/*========================================================================*/
	// Load extra query cache file
	/*========================================================================*/
	
	function load_cache_file($filepath, $classname = 'sql_extra_queries') {
		if (! file_exists ( $filepath )) {
			print "Cannot locate $filepath - exiting!";
			exit ();
		} else {
			require_once ($filepath);
			{
				$this->$classname = new $classname ( &$this );
				$this->loaded_classes [] = $classname;
			}
		}
	}

	/*========================================================================*/
	// Process cached query
	/*========================================================================*/
	
	function cache_add_query($q = "", $args = array(), $method = 'sql_queries') {
		if ($this->obj ['query_cache_file'] and $method == 'sql_queries')
			$this->cur_query .= $this->sql->$q ( $args );
		else if (in_array ( $method, $this->loaded_classes ))
			$this->cur_query .= $this->$method->$q ( $args );
	}

	function cache_exec_query() {
		return $this->simple_exec ();
	}

	function cache_shutdown_exec() {
		if (! $this->obj ['use_shutdown']) {
			$this->is_shutdown = 1;
			return $this->simple_exec ();
		} else {
			$this->obj ['shutdown_queries'] [] = $this->cur_query;
			$this->cur_query = "";
		}
	}

	/*========================================================================*/
	// Quick function
	/*========================================================================*/
	
	function do_update($tbl, $arr, $where = "") {
		$dba = $this->compile_db_update_string ( $arr );
		$query = "UPDATE " . SQL_PREFIX . "$tbl SET $dba";
		
		if ($where)
			$query .= " WHERE " . $where;
		
		$ci = $this->query ( $query );
		return $ci;
	
	}

	function do_insert($tbl, $arr) {
		$dba = $this->compile_db_insert_string ( $arr );
		$ci = $this->query ( "INSERT INTO " . SQL_PREFIX . "$tbl ({$dba['FIELD_NAMES']}) VALUES({$dba['FIELD_VALUES']})" );
		return $ci;
	}

	function insert_multi_record($tbl, $arrayRecord) {
		foreach ( $arrayRecord as $arr ) {
			$temp = $this->compile_db_insert_string ( $arr );
			$tempValue .= "(" . $temp ['FIELD_VALUES'] . "),";
		}
		
		$query = "INSERT INTO " . SQL_PREFIX . $tbl . "(" . $temp ['FIELD_NAMES'] . ") VALUES " . substr ( $tempValue, 0, strlen ( $tempValue ) - 1 ) . ";";
		$ci = $this->query ( $query );
		
		return $ci;
	}

	/*========================================================================*/
	// Shutdown update / insert
	/*========================================================================*/
	
	//------------------------------------
	// SHUTDOWN UPDATE
	//------------------------------------
	

	function do_shutdown_update($tbl, $arr, $where = "") {
		$dba = $this->compile_db_update_string ( $arr );
		
		$query = "UPDATE " . SQL_PREFIX . "$tbl SET $dba";
		
		if ($where)
			$query .= " WHERE " . $where;
		
		if (! $this->obj ['use_shutdown']) {
			$this->is_shutdown = 1;
			return $this->query ( $query );
		} else {
			$this->obj ['shutdown_queries'] [] = $query;
			$this->cur_query = "";
		}
	}

	//------------------------------------
	// SHUTDOWN INSERT
	//------------------------------------
	

	function do_shutdown_insert($tbl, $arr) {
		$dba = $this->compile_db_insert_string ( $arr );
		
		$query = "INSERT INTO " . SQL_PREFIX . "$tbl ({$dba['FIELD_NAMES']}) VALUES({$dba['FIELD_VALUES']})";
		
		if (! $this->obj ['use_shutdown']) {
			$this->is_shutdown = 1;
			return $this->query ( $query );
		} else {
			$this->obj ['shutdown_queries'] [] = $query;
			$this->cur_query = "";
		}
	}

	//------------------------------------
	// SHUTDOWN STORE QUERY
	//------------------------------------
	

	function simple_shutdown_exec() {
		if (! $this->obj ['use_shutdown']) {
			$this->is_shutdown = 1;
			return $this->simple_exec ();
		} else {
			$this->obj ['shutdown_queries'] [] = $this->cur_query;
			$this->cur_query = "";
		}
	}

	/*========================================================================*/
	// Simple elements
	/*========================================================================*/
	
	function simple_construct($a) {
		if ($a ['select']) {
			$a ['where'] = isset ( $a ['where'] ) ? $a ['where'] : "";
			$this->simple_select ( $a ['select'], $a ['from'], $a ['where'] );
		}
		if (isset ( $a ['update'] ) && $a ['update'])
			$this->simple_update ( $a ['update'], $a ['set'], $a ['where'], $a ['lowpro'] );
		
		if (isset ( $a ['delete'] ) && $a ['delete'])
			$this->simple_delete ( $a ['delete'], $a ['where'] );
		
		if (isset ( $a ['groupby'] )) {
			$this->simple_groupby ( $a ['groupby'] );
			if ($a ['having'])
				$this->simple_having ( $a ['having'] );
		}
		if (isset ( $a ['order'] ) && $a ['order'])
			$this->simple_order ( $a ['order'] );
		if (isset ( $a ['limit'] ) && is_array ( $a ['limit'] ))
			$this->simple_limit ( $a ['limit'] [0], $a ['limit'] [1] );
		if ($this->show_query)
			print $this->cur_query;
	}

	//------------------------------------
	// UPDATE
	//------------------------------------
	

	function simple_update($tbl, $set, $where, $low_pro) {
		if ($low_pro)
			$low_pro = ' LOW_PRIORITY ';
		$this->cur_query .= "UPDATE " . $low_pro . SQL_PREFIX . "$tbl SET $set";
		if ($where)
			$this->cur_query .= " WHERE $where";
	}

	//------------------------------------
	// DELETE
	//------------------------------------
	

	function simple_delete($tbl, $where) {
		$this->cur_query .= "DELETE FROM " . SQL_PREFIX . "$tbl";
		if ($where)
			$this->cur_query .= " WHERE $where";
	}

	//------------------------------------
	// EXEC QUERY
	//------------------------------------
	

	function simple_exec() {
		try {
			if ($this->cur_query != "")
				$ci = $this->query ( $this->cur_query );
			
			$this->cur_query = "";
			$this->is_shutdown = 0;
			
			return $ci;
		} catch ( Exception $e ) {
			$this->cur_query = "";
			$this->is_shutdown = 0;
			print $e;
			return;
		}
	}

	//------------------------------------
	// Exec and return simple row
	//------------------------------------
	

	function simple_exec_query($a) {
		$this->simple_construct ( $a );
		$ci = $this->simple_exec ();
		
		if ($a ['select'])
			return $this->fetch_row ( $ci );
	}

	//------------------------------------
	// ORDER
	//------------------------------------
	

	function simple_order($a) {
		if ($a)
			$this->cur_query .= ' ORDER BY ' . $a;
	}

	//------------------------------------
	// GROUP BY
	//------------------------------------
	function simple_groupby($a) {
		if ($a)
			$this->cur_query .= ' GROUP BY ' . $a;
	}

	//------------------------------------
	// HAVING
	//------------------------------------
	function simple_having($a) {
		if ($a)
			$this->cur_query .= ' HAVING ' . $a;
	}

	//------------------------------------
	// LIMIT
	//------------------------------------
	

	function simple_limit_with_check($offset, $limit = "") {
		if (! preg_match ( "#LIMIT\s+?\d+,#i", $this->cur_query ))
			$this->simple_limit ( $offset, $limit );
	}

	function simple_limit($offset, $limit = "") {
		if ($limit)
			$this->cur_query .= ' LIMIT ' . $offset . ',' . $limit;
		else
			$this->cur_query .= ' LIMIT ' . $offset;
	}

	/**
	 * Simple select query function
	 *
	 * @param string $get
	 * @param string $table
	 * @param string $where
	 */
	function simple_select($get, $table, $where = "") {
		
		$this->cur_query .= "SELECT $get FROM " . SQL_PREFIX . "$table";
		
		if ($where != "")
			$this->cur_query .= " WHERE " . $where;
	}

	/*========================================================================*/
	// Process a manual query
	/*========================================================================*/
	
	function query($the_query, $bypass = 0) {
		//--------------------------------------
		// Change the table prefix if needed
		//--------------------------------------
		

		if ($bypass != 1 && $this->obj ['sql_tbl_prefix'] != "rsf_" and ! $this->prefix_changed)
			$the_query = preg_replace ( "/rsf_(\S+?)([\s\.,]|$)/", $this->obj ['sql_tbl_prefix'] . "\\1\\2", $the_query );
		
		if ($this->obj ['debug']) {
			global $Debug, $bw;
			
			$Debug->startTimer ();
		}
		
		$this->query_id = mysql_query ( $the_query, $this->connection_id );
		
		if (! $this->query_id) {
			$this->fatal_error ( "mySQL query error: $the_query" );
			$this->cur_query = "";
			throw new Exception ( "mySQL query error: " . $this->error );
		}
		
		//--------------------------------------
		// Debug?
		//--------------------------------------
		

		if ($this->obj ['debug']) {
			$endtime = $Debug->endTimer ();
			
			$shutdown = $this->is_shutdown ? 'SHUTDOWN QUERY: ' : '';
			
			if (preg_match ( "/^select/i", $the_query )) {
				$eid = mysql_query ( "EXPLAIN $the_query", $this->connection_id );
				
				$bw->debug_html .= "<table width='95%' border='1' cellpadding='6' cellspacing='0' bgcolor='#FFE8F3' align='center'>
										   <tr>
										   	 <td colspan='8' style='font-size:14px' bgcolor='#FFC5Cb'><b>{$shutdown}Select Query</b></td>
										   </tr>
										   <tr>
										    <td colspan='8' style='font-family:courier, monaco, arial;font-size:14px;color:black'>$the_query</td>
										   </tr>
										   <tr bgcolor='#FFC5Cb'>
											 <td><b>table</b></td><td><b>type</b></td><td><b>possible_keys</b></td>
											 <td><b>key</b></td><td><b>key_len</b></td><td><b>ref</b></td>
											 <td><b>rows</b></td><td><b>Extra</b></td>
										   </tr>\n";
				while ( $array = mysql_fetch_array ( $eid ) ) {
					$type_col = '#FFFFFF';
					
					if ($array ['type'] == 'ref' or $array ['type'] == 'eq_ref' or $array ['type'] == 'const') {
						$type_col = '#D8FFD4';
					} else if ($array ['type'] == 'ALL') {
						$type_col = '#FFEEBA';
					}
					
					$bw->debug_html .= "<tr bgcolor='#FFFFFF'>
											 <td>$array[table]&nbsp;</td>
											 <td bgcolor='$type_col'>$array[type]&nbsp;</td>
											 <td>$array[possible_keys]&nbsp;</td>
											 <td>$array[key]&nbsp;</td>
											 <td>$array[key_len]&nbsp;</td>
											 <td>$array[ref]&nbsp;</td>
											 <td>$array[rows]&nbsp;</td>
											 <td>$array[Extra]&nbsp;</td>
										   </tr>\n";
				}
				
				if ($endtime > 0.1) {
					$endtime = "<span style='color:red'><b>$endtime</b></span>";
				}
				
				$bw->debug_html .= "<tr>
										  <td colspan='8' bgcolor='#FFD6DC' style='font-size:14px'><b>MySQL time</b>: $endtime</b></td>
										  </tr>
										  </table>\n<br />\n";
			} else {
				$bw->debug_html .= "<table width='95%' border='1' cellpadding='6' cellspacing='0' bgcolor='#FEFEFE'  align='center'>
										 <tr>
										  <td style='font-size:14px' bgcolor='#EFEFEF'><b>{$shutdown}Non Select Query</b></td>
										 </tr>
										 <tr>
										  <td style='font-family:courier, monaco, arial;font-size:14px'>$the_query</td>
										 </tr>
										 <tr>
										  <td style='font-size:14px' bgcolor='#EFEFEF'><b>MySQL time</b>: $endtime</span></td>
										 </tr>
										</table><br />\n\n";
			}
			
			$this->sql_time += $endtime;
		}
		
		$this->query_count ++;
		
		$this->obj ['cached_queries'] [] = $the_query;
		
		return $this->query_id;
	}

	function field_exists($field, $table) {
		$columns = mysql_query ( "show columns from " . SQL_PREFIX . "$table" );
		while ( $c = mysql_fetch_assoc ( $columns ) ) {
			if ($c ['Field'] == $field) {
				return true;
			}
		}
		
		return false;
	}

	/*========================================================================*/
	// Fetch a row based on the last query
	/*========================================================================*/
	
	function fetch_row($query_id = "") {
		if ($query_id == "")
			$query_id = $this->query_id;
		
		if (! $query_id)
			throw new Exception ( "Resource is not valid!" );
		
		$this->record_row = mysql_fetch_array ( $query_id, MYSQL_ASSOC );
		return $this->record_row;
	}

	/*========================================================================*/
	// DROP TABLE
	/*========================================================================*/
	
	function sql_drop_table($table) {
		$this->query ( "DROP TABLE if exists " . SQL_PREFIX . "{$table}" );
	}

	/*========================================================================*/
	// DROP FIELD
	/*========================================================================*/
	
	function sql_drop_field($table, $field) {
		$this->query ( "ALTER TABLE " . SQL_PREFIX . "{$table} DROP $field" );
	}

	/*========================================================================*/
	// ADD FIELD
	/*========================================================================*/
	
	function sql_add_field($table, $field_name, $field_type, $field_default = "") {
		$query = "ALTER TABLE " . SQL_PREFIX . "{$table} ADD $field_name $field_type ";
		if (! empty ( $field_default ))
			$query .= " default {$field_default}";
		$this->query ( $query );
	
	}

	/*========================================================================*/
	// TRUNCATE TABLE
	/*========================================================================*/
	
	function sql_empty_table($table) {
		$this->query ( "TRUNCATE TABLE " . SQL_PREFIX . $table );
	}

	/*========================================================================*/
	// OPTIMIZE FIELD
	/*========================================================================*/
	
	function sql_optimize_table($table) {
		$this->query ( "OPTIMIZE TABLE " . SQL_PREFIX . "{$table}" );
	}

	/*========================================================================*/
	// ADD FULLTEXT INDEX
	/*========================================================================*/
	
	function sql_add_fulltext_index($table, $field) {
		$this->query ( "alter table " . SQL_PREFIX . "{$table} ADD FULLTEXT({$field})" );
	}

	/*========================================================================*/
	// GET TABLE SCHEMATIC
	/*========================================================================*/
	
	function sql_get_table_schematic($table) {
		$this->query ( "SHOW CREATE TABLE " . SQL_PREFIX . "{$table}" );
		return $this->fetch_row ();
	}

	/*========================================================================*/
	// IS ALREADY TABLE FULLTEXT?
	/*========================================================================*/
	
	function sql_is_currently_fulltext($table) {
		$result = $this->sql_get_table_schematic ( $table );
		
		if (preg_match ( "/FULLTEXT KEY/i", $result ['Create Table'] ))
			return TRUE;
		return FALSE;
	}

	/*========================================================================*/
	// Return the version number of the SQL server
	// Should return 'true' version string (ie: 3.23.0)
	// And formatted string (ie: 3230 )
	/*========================================================================*/
	
	function sql_get_version() {
		if (! $this->mysql_version and ! $this->true_version) {
			$this->query ( "SELECT VERSION() AS version" );
			
			if (! $row = $this->fetch_row ()) {
				$this->query ( "SHOW VARIABLES LIKE 'version'" );
				$row = $this->fetch_row ();
			}
			
			$this->true_version = $row ['version'];
			
			$no_array = explode ( '.', preg_replace ( "/^(.+?)[-_]?/", "\\1", $row ['version'] ) );
			
			$one = (! isset ( $no_array ) || ! isset ( $no_array [0] )) ? 3 : $no_array [0];
			$two = (! isset ( $no_array [1] )) ? 21 : $no_array [1];
			$three = (! isset ( $no_array [2] )) ? 0 : $no_array [2];
			
			$this->mysql_version = ( int ) sprintf ( '%d%02d%02d', $one, $two, intval ( $three ) );
		}
	}

	/*========================================================================*/
	// Flushes the in memory query string
	/*========================================================================*/
	
	function flush_query() {
		$this->cur_query = "";
	}

	/*========================================================================*/
	// Fetch the number of rows affected by the last query
	/*========================================================================*/
	
	function get_affected_rows() {
		return mysql_affected_rows ( $this->connection_id );
	}

	/*========================================================================*/
	// Fetch the number of rows in a result set
	/*========================================================================*/
	
	function get_num_rows() {
		return mysql_num_rows ( $this->query_id );
	}

	/*========================================================================*/
	// Fetch the last insert id from an sql autoincrement
	/*========================================================================*/
	
	function get_insert_id() {
		return mysql_insert_id ( $this->connection_id );
	}

	/*========================================================================*/
	// Return the amount of queries used
	/*========================================================================*/
	
	function get_query_cnt() {
		return $this->query_count;
	}

	/*========================================================================*/
	// Free the result set from mySQLs memory
	/*========================================================================*/
	
	function free_result($query_id = "") {
		
		if ($query_id == "") {
			$query_id = $this->query_id;
		}
		
		@mysql_free_result ( $query_id );
	}

	/*========================================================================*/
	// Shut down the database
	/*========================================================================*/
	
	function close_db() {
		if ($this->connection_id) {
			return @mysql_close ( $this->connection_id );
		}
	}

	/*========================================================================*/
	// Return an array of tables
	/*========================================================================*/
	
	function get_table_names() {
		
		$result = mysql_list_tables ( $this->obj ['sql_database'] );
		$num_tables = @mysql_numrows ( $result );
		for($i = 0; $i < $num_tables; $i ++) {
			$tables [] = mysql_tablename ( $result, $i );
		}
		mysql_free_result ( $result );
		
		return $tables;
	}

	/*========================================================================*/
	// Basic error handler
	/*========================================================================*/
	
	function fatal_error($the_error = "") {
		// Are we simply returning the error?
		global $vsStd, $bw;
		
		if ($this->return_die == 1) {
			$this->error = str_replace ( SQL_PREFIX, "", mysql_error () );
			$this->error_no = mysql_errno ();
			$this->failed = 1;
			return;
		}
		
		$the_error .= "\n\nmySQL error: " . mysql_error () . "\n";
		$the_error .= "mySQL error code: " . $this->error_no . "\n";
		$the_error .= "Date: " . date ( "l dS of F Y h:i:s A" );
		
		if ($this->require)
			$vsStd->boink_it ( $bw->vars ['board_url'] );
		else if ($bw->input ['json']) {
			$out = json_encode ( array ("error" => htmlspecialchars ( $the_error ) ) );
		} else {
			$out = "<html><head><title>REDSUN Database Error</title>
    		   <style>P,BODY{ font-family:arial,sans-serif; font-size:11px; }</style></head><body>
    		   &nbsp;<br><br><blockquote><b>There appears to be an error with the database.</b><br>
    		   You can try to refresh the page by clicking <a href=\"javascript:window.location=window.location;\">here</a>.
    		   <br><br><b>Error Returned</b><br>
    		   <form name='mysql'><textarea rows=\"15\" cols=\"60\">" . htmlspecialchars ( $the_error ) . "</textarea></form><br>We apologise for any inconvenience</blockquote></body></html>";
		
		}
		echo ($out);
		die ( "" );
	}

	/*========================================================================*/
	// Create an array from a multidimensional array returning formatted
	// strings ready to use in an INSERT query, saves having to manually format
	// the (INSERT INTO table) ('field', 'field', 'field') VALUES ('val', 'val')
	/*========================================================================*/
	
	function compile_db_insert_string($data) {
		
		$field_names = "";
		$field_values = "";
		
		foreach ( $data as $k => $v ) {
			if (! $this->manual_addslashes)
				$v = preg_replace ( "/'/", "\\'", $v );
			
			$field_names .= "$k,";
			
			if (is_numeric ( $v ) and intval ( $v ) == $v)
				$field_values .= $v . ",";
			else
				$field_values .= "'$v',";
		}
		
		$field_names = preg_replace ( "/,$/", "", $field_names );
		$field_values = preg_replace ( "/,$/", "", $field_values );
		
		return array ('FIELD_NAMES' => $field_names, 'FIELD_VALUES' => $field_values );
	}

	/*========================================================================*/
	// Create an array from a multidimensional array returning a formatted
	// string ready to use in an UPDATE query, saves having to manually format
	// the FIELD='val', FIELD='val', FIELD='val'
	/*========================================================================*/
	
	function compile_db_update_string($data) {
		
		$return_string = "";
		
		foreach ( $data as $k => $v ) {
			if (! $this->manual_addslashes)
				$v = preg_replace ( "/'/", "\\'", $v );
			
			if (is_numeric ( $v ) and intval ( $v ) == $v)
				$return_string .= $k . "=" . $v . ",";
			else
				$return_string .= $k . "='" . $v . "',";
		}
		
		$return_string = preg_replace ( "/,$/", "", $return_string );
		
		return $return_string;
	}
}
?>