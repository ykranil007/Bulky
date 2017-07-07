<?php 	 
    date_default_timezone_set("asia/kolkata"); 
	class database
	{
		private $db;
		 
		// constructor
		public function __construct()
		{
			$this->_host     = "localhost";
			$this->_username = "root";
			$this->_password = "";
			$this->_database = "nimitjtx_bulknmore";
			
			$this->db = new mysqli($this->_host, $this->_username, $this->_password, $this->_database);
			// error handling
			if (mysqli_connect_error())
			{
				trigger_error("failed to conencto to mysql: " . mysqli_connect_error(),E_USER_ERROR);
			}
			$this->db->set_charset("utf8");
		}
		//get multiple columns value from table.
		public function get_table_row($column_name = "*", $tablename, $where)
		{
			$sql = "SELECT " . $column_name . " FROM " . $tablename . " WHERE " . $where ." limit 0,1";
            //echo $sql;exit; 
			$result = $this->db->query($sql);
			if (mysqli_num_rows($result) > 0)
			{
				$row = mysqli_fetch_array($result);
				return $row;
			}
			else  return '';
		}
		//get single column value from table.
		public function get_table_column($column_name, $tablename, $where)
		{
			$sql = "SELECT " . $column_name . " FROM " . $tablename . " WHERE " . $where ." limit 0,1";
			//echo $sql;
			$result = $this->db->query($sql);
			if ($result != '')
			{
				if (mysqli_num_rows($result) > 0)
				{
					$row = mysqli_fetch_array($result);
					return $row[$column_name];
				}
				else  return 0;
			}
            else  return 0;
		}
		//delete row from table.
		public function delete_table_row($tablename, $where)
		{
			$sql = "DELETE FROM " . $tablename . " WHERE " . $where . "";
			$this->db->query($sql);
		}
		//insert data in table
		public function insert_data($data, $table)
		{
			$fields = '';
            $fields = $this->create_string($data, ', ');
			$query = "INSERT INTO $table SET $fields ";
			if ($this->db->query($query)) return $this->db->insert_id;
 			else  return 0;
		}
		//insert and update on duplicate key
		public function update_on_duplicate_key($data, $table)
		{
			$fields = '';
            $fields = $this->create_string($data, ', ');
			$query = "INSERT INTO $table SET $fields ON DUPLICATE KEY UPDATE $fields";
			//echo $query;
			if ($this->db->query($query)) return 1;
			else  return 0;
		}
		//update table row on database
		public function update_table_row($data, $table, $where)
		{
			$fields = '';
            $fields = $this->create_string($data, ', ');
			$query = "UPDATE $table SET $fields WHERE $where";
			if ($this->db->query($query)) return 1;
			else  return 0;
		}
		public function data_table($column_names, $tablename, $join, $where)
		{
			if ($column_names == '*') $column_names = '*';
			else  $column_name = $column_names;//coloumn array
            
			$sql = "SELECT " . $column_name . " FROM " . $tablename . $join. " WHERE " . $where ."";
            //echo $sql;exit;
			$result = $this->db->query($sql);
			if (mysqli_num_rows($result) > 0)
			{
				while ($row = mysqli_fetch_object($result))
				{
					$array[] = $row;
				}
				return $array;
			}
			else  return array();
		}
		
        private function create_string($data, $seperator_value)
        {
            $fields = $seperator = '';
			foreach ($data as $column => $value)
			{
				$fields .= $seperator;
				$fields .= $column . "=" . "'" . $this->db->real_escape_string($value) ."'";
				$seperator = $seperator_value;
			}
            return $fields;
        }
        
        public function close_connection() //close the database connection
		{
			$this->db->close();
		}
	} 
?> 
