<?php

class Common_model extends CI_Model
{
	function __construct()
    {
        parent::__construct();
    }

    public function getallcategories()
	{
		$query = $this->db->query("SELECT * FROM tblblk_category");
		return $query->result();
	}

	public function getSubcate($categoryid)
	{
		$query = $this->db->query("SELECT * FROM tblblk_sub_category WHERE category_id =".$categoryid." ");
		//echo $this->db->last_query();
		return $query->result();
	}

	public function getSubtoSubcate($subcatid)
	{
		$query = $this->db->query("SELECT * FROM tblblk_subtosub_category WHERE sub_category_id =".$subcatid." ");
		//echo $this->db->last_query();
		return $query->result();
	}
	public function GetTableRow($column_name,$tablename,$where)
	{
		$this->db->select($column_name);
		$query=$this->db->get_where($tablename,$where); //echo $this->db->last_query();exit;
		return $query->row();
	}    
    public function get_table_result($column_name,$tablename,$where)
	{
		$this->db->select($column_name);
		$query=$this->db->get_where($tablename,$where);
		return $query->result();
	}

	//--- Function for checking whether user exists or not
	public function isUserExists($email)
	{
		$this->db->select('user_id,user_type,status');
		$this->db->from('tblblk_users');
		$this->db->where('email',$email);
		$this->db->where(array('user_type'=>2));
		$query = $this->db->get();
		if($query->num_rows()>0)
			return true;
		else
			return false;	
	}
	public function registerNewUserFromSocialAccount($first_name,$last_name,$email,$phone='',$gender='')
	{
		$user_secret_key = "BNMAPP".generate_random_string(15).date('d-m-Y').generate_random_string(5);
		//echo $user_secret_key;exit;
		$inser_array = array(
								'first_name' 			 => $first_name,
								'last_name'	 			 => $last_name,
								'email'		 			 => $email,
								'gender'	 			 => $gender,
								'mobile'	 			 => $phone,
								'user_secret_key'	 	 => $user_secret_key,
								'user_type'  			 => 2,
								//'is_active'	 			 => 'Y',
								'is_social'  			 => 'Y',
								//'status'	 			 => 1
							);
		$this->db->insert('tblblk_users',$inser_array);	
		return $this->db->insert_id();				
		
	}
	
	public function getUserInfo($user_id_or_email)
	{
		$this->db->select('*');
		$this->db->from('tblblk_users');
		$this->db->where('user_id',$user_id_or_email);
		$this->db->or_where('email',$user_id_or_email);
		$this->db->where('user_type',2);
		$query = $this->db->get();
		return $query->row();
	}
    
    // Function for inserting record array in any table
	public function insert_record($table_name,$insert_array)
	{
		$this->db->insert($table_name,$insert_array);
        //echo $this->db->last_query();exit;
		return $this->db->insert_id();
	}
	// Function for inserting records in batch
	public function insert_record_in_batch($table_name,$insert_array)
	{
		if($this->db->insert_batch($table_name,$insert_array))
			return true;
		else
			return false;
	}
	public function insert_analytics_record($table_name,$insert_array,$on_duplicate)
	{
		$sql = $this->db->insert_string($table_name, $insert_array) . " ON DUPLICATE KEY UPDATE ".$on_duplicate;
		if($this->db->query($sql))
			return true;
		else
			return false;
	}
	public function update_table_row($table_name,$update_array,$where_array,$or_where=array())
	{
		$this->db->where($where_array);
		if(!empty($or_where)){
			$this->db->group_start();
				$this->db->or_where($or_where);
			$this->db->group_end();
		}
		if($this->db->update($table_name,$update_array)){
			return true;
		}else{
			return false;
		}
	}
}