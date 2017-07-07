<?php

class login_model extends CI_Model
{
	function __construct()
    {
        parent::__construct();
    }

    public function verifyAccount($data)
	{
		$this->db->select('user_id,email,password');
		$this->db->from('tblblk_users');
		$this->db->where(array('password'=>md5($data['lpassword']),'user_type'=>2,'is_active'=>'Y','status'=>1));
		$this->db->group_start();
			$this->db->where('email',$data['lemail']);		
			$this->db->or_where('mobile',$data['lemail']);
		$this->db->group_end();	
		$query = $this->db->get();
		if(count($query->row())>0)
		{
			$user_secret_key = "BNMAPP".generate_random_string(15).date('d-m-Y').generate_random_string(5);
			$update = array('last_login'=>date('Y-m-d H:i:s'),'user_secret_key'=>$user_secret_key);
			$this->db->group_start();
				$this->db->where(array('email'=>$data['lemail'],'password'=>$data['lpassword'],'user_type'=>2));		
				$this->db->or_where(array('mobile'=>$data['lemail'],'password'=>$data['lpassword'],'user_type'=>2));
			$this->db->group_end();
			$this->db->update('tblblk_users',$update);
			return 1;
		}
		else
		{
			return 0;
		}


		/*$this->db->select('user.email,user.password,user.is_active,user.status');
		$this->db->where(array('email'=>$email,'password'=>$password,'user_type'=>2));
		$this->db->from('tblblk_users As user');
 		$query = $this->db->get();
		
		if(!count($query->row())>0)
			return "not found";
		if($query->row()->is_active=='Y' && $query->row()->status == 1)
		{
			
		}
		else if($query->row()->is_active=='N' && $query->row()->status == 2)
		{
		   
		}*/		  
	}

	public function GetUserInfo($value)
	{
		$this->db->select('user.user_id,user.email,user.first_name,user.mobile,user.user_secret_key,user.gender');
		$this->db->group_start();
			$this->db->where('email',$value);		
			$this->db->or_where('mobile',$value);
		$this->db->group_end();
        $this->db->where('user_type',2);
		$this->db->from('tblblk_users As user');
		$query = $this->db->get();        
		return $query->row();
	}
    public function getUserDetails($id)
	{
		$query=$this->db->query("SELECT * FROM tblblk_users WHERE user_id='$id'");
		return $query->row();
	}

	public function getUserWallet($userid)
	{
		$this->db->select('user_id,
           IFNULL(SUM(CASE WHEN transaction_type="debit" THEN amount ELSE 0 END),0) As Debit,
           IFNULL(SUM(CASE WHEN transaction_type="credit" THEN amount ELSE 0 END),0) As Credit');
		$this->db->from('tblblk_user_wallet as wallet');
		$this->db->where(array('wallet.user_id'=>$userid));
		$query = $this->db->get();
		if($query->row() != '')
			return $query->row()->Credit - $query->row()->Debit;
		else
			return array('Debit'=>0,'Credit'=>0);
	}
}