<?php 
	
  class Register_model extends CI_Model
  	{
		public function __construct()
		{
			parent::__construct();
		}
	
	    public function registerUsers($data,$otp)
		{
			if(!empty($data['is_bulk_user']))
			{
				$insert = array('user_type'=>2,'first_name'=>$data['firstname'],'email'=>$data['email'],'mobile'=>$data['mobile'],'password'=>md5($data['password']),'gender'=>$data['gender'],'otpcode'=>$otp,'terms_condition'=>1,'is_bulk_user'=>'Y');
				$sql = $this->db->insert_string('tblblk_users', $insert) . " ON DUPLICATE KEY UPDATE first_name = '".$data['firstname']."', email = '".$data['email']."', mobile = '".$data['mobile']."',password = '".md5($data['password'])."', gender = '".$data['gender']."', otpcode = '".$otp."', is_bulk_user = 'Y', dateadded = '".date('Y-m-d h:i:s')."'";
			}
			else
			{
				$insert = array('user_type'=>2,'first_name'=>$data['firstname'],'email'=>$data['email'],'mobile'=>$data['mobile'],'password'=>md5($data['password']),'gender'=>$data['gender'],'otpcode'=>$otp,'terms_condition'=>1,'is_bulk_user'=>'N','dateadded'=>date('Y-m-d H:i:s'));
				$sql = $this->db->insert_string('tblblk_users', $insert) . " ON DUPLICATE KEY UPDATE first_name = '".$data['firstname']."', email = '".$data['email']."', mobile = '".$data['mobile']."',password = '".md5($data['password'])."', gender = '".$data['gender']."', otpcode = '".$otp."', is_bulk_user = 'N', dateadded = '".date('Y-m-d h:i:s')."'";
			}			
        	$this->db->query($sql);
        	$inserted_id = $this->db->insert_id();

        	if($this->db->insert_id() > 0)		  
		  		return $inserted_id;
        	else
		  		return '';
		}

		public function checkemailexist($email)
    	{
			$this->db->select('email');
			$this->db->where('email',$email);
			$this->db->where(array('user_type'=>2,'is_active'=>'Y'));
			$query = $this->db->get('tblblk_users');
			
			if($query->num_rows() > 0)
	            return 0;
	        else
	            return 1;		
		}

		public function check_mobile($mobile)
		{
			$this->db->select('mobile');
			$this->db->where('mobile',$mobile);
			$this->db->where(array('user_type'=>2,'is_active'=>'Y'));
			$query = $this->db->get('tblblk_users');
			if($query->num_rows() > 0)
	            return 0;
	        else
	            return 1;
		}

		public function getPageElements($pageid)
	    {
	       $this->db->where("page_id", $pageid);
	       $query = $this->db->get("tblblk_page");
	       return $query->row();
	    }

	    public function generateVerificationCode($user_id,$type)
       	{
	        $verification_code = $this->generateRandomString(rand(30, 40));
	        $this->db->query("INSERT INTO tblblk_email_links 
								SET 
									link_type 	= ".$this->db->escape($type).", 
									email_link  = ".$this->db->escape($verification_code).", 
									user_id 	= ".$user_id." ");
	          	if ($this->db->insert_id() > 0)
			    	return $verification_code;
	          	else
			    	return '';
       	}

       	public function getAutoEmail($id)
     	{
	        $query = $this->db->query("select * from tblblk_autoemail where email_id=".$id." ");
			
	        return $query->row();
     	}

     	public function verifyOTP($userid,$otp)
     	{
     		$this->db->select('otpcode');
     		$this->db->from('tblblk_users');
     		$this->db->where(array('user_id'=>$userid,'otpcode'=>$otp));
     		$query = $this->db->get();     		   		
     		return $query->row();
     	}

     	public function verifyForgotOTP($useremail,$otp)
     	{
     		$this->db->select('otpcode');
     		$this->db->from('tblblk_users');
     		$this->db->where(array('email'=>$useremail,'otpcode'=>$otp,'is_active'=>'Y','status'=>1));
     		$query = $this->db->get();     		   		
     		return $query->row();
     	}

     	public function verifyUser($userid)
     	{
     		$query = $this->db->query("UPDATE tblblk_users SET 
										is_active='Y',
										status = '1'
										WHERE user_id = " . $userid . " AND user_type = '2' ");
            if ($this->db->trans_status() === false){
                return 0;
            } else {
                if($this->insert_user_log($userid) > 0)
                {
                    return 1;    
                } else {
                    return 0;
                }
            }
        }
        
        public function insert_user_log($userid)
        {
            $this->db->trans_start();
            $insert = array('user_id'=>$userid,'key'=>'Register','message'=>'User has been register successfully','log_date'=>date('Y-m-d H:i:s'));
            $this->db->insert('tblblk_user_log',$insert);
            $this->db->trans_complete();
            if ($this->db->trans_status() === false){
        	   return 0;
        	}
            else {
                return 1;
            }
        }
        
     	public function getUserData($email)
	    {
	       $this->db->where(array("email"=>$email,'user_type'=>2,'is_active'=>'Y','status'=>1));
	       $query = $this->db->get("tblblk_users");
	       return $query->row();
	    }

	    public function UpdateOTP($userid,$otp)
	    {
	        $update = array('otpcode'=>$otp);
	    	$this->db->where(array('user_id'=>$userid,'user_type'=>2));
            $this->db->update('tblblk_users',$update);
            
            if($this->db->trans_status() === false) 
            	return 0;
            else
             	return 1;
     	
	    }

	    public function UpdateUserPassword($usercode,$newpass)
	    {
            $this->db->trans_start();
	        $update = array('password'=>$newpass);
	    	$this->db->where(array('otpcode'=>$usercode,'user_type'=>2));
	    	$this->db->update('tblblk_users',$update);
            $this->db->trans_complete();
            if ($this->db->trans_status() === false) 
            	return 0;
            else
             	return 1;
     	
	    }

	    public function ResetOTP($userid)
	    {
            $this->db->trans_start();
	        $update = array('otpcode'=>' ');
	    	$this->db->where(array('user_id'=>$userid,'user_type'=>2));
	    	$this->db->update('tblblk_users',$update);
            $this->db->trans_complete();
	    	if($this->db->trans_status() === false) 
	        	return 0;
	        else
	         	return 1;
	    }

	    public function GetUserDetails($user_id)
	    {
	    	$this->db->select('*');
	    	$this->db->where(array("user_id"=>$user_id,'user_type'=>2));
	       	$query = $this->db->get("tblblk_users");
	       	return $query->row();
	    }

	    public function UpdateMobile($mobile,$otp,$userid)
	    {
	        $this->db->trans_start();
	    	$update = array('mobile'=>$mobile,'otpcode'=>$otp);
	    	$this->db->where(array('user_id'=>$userid,'user_type'=>2));
	    	$this->db->update('tblblk_users',$update);
            $this->db->trans_complete();
	    	if($this->db->trans_status() === false) 
	        	return 0;
	        else
	         	return 1;
	    }
	    
	    public function uploadBulkUserDocuments($image_name,$userid)
		{
			$insert['document_name'] = $image_name;
			$insert['user_id'] = $userid;
			$this->db->insert('tblblk_bulkuser_documents',$insert);
			$this->verifyUser($userid);
			$this->ResetOTP($userid);
		}

	}