<?php 
class Register_model extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}
	public function registerUsers($data,$otp)
	{
		$name = explode(' ',$data['name']);
		$firstname = $name[0];
		if(!empty($name[1]))
			$lastname  = $name[1];
		else
			$lastname = '';
		if(!empty($data['is_bulk_user']))
		{
			$insert = array('user_type'=>2,'first_name'=>$firstname,'last_name'=>$lastname,'email'=>$data['email'],'mobile'=>$data['mobile'],'password'=>md5($data['password']),'gender'=>$data['gender'],'otpcode'=>$otp,'terms_condition'=>1,'is_bulk_user'=>'Y','user_secret_key'=>$data['user_secret_key']);
			$sql = $this->db->insert_string('tblblk_users', $insert) . " ON DUPLICATE KEY UPDATE first_name = '".$firstname."', last_name = '".$lastname."', email = '".$data['email']."', mobile = '".$data['mobile']."',password = '".md5($data['password'])."', gender = '".$data['gender']."', otpcode = '".$otp."', is_bulk_user = 'Y',user_secret_key = '".$data['user_secret_key']."', dateadded = '".date('Y-m-d h:i:s')."'";
		}
		else
		{
			$insert = array('user_type'=>2,'first_name'=>$firstname,'last_name'=>$lastname,'email'=>$data['email'],'mobile'=>$data['mobile'],'password'=>md5($data['password']),'gender'=>$data['gender'],'otpcode'=>$otp,'terms_condition'=>1,'is_bulk_user'=>'N','user_secret_key'=>$data['user_secret_key'],'dateadded'=>date('Y-m-d H:i:s'));
			$sql = $this->db->insert_string('tblblk_users', $insert) . " ON DUPLICATE KEY UPDATE first_name = '".$firstname."', last_name = '".$lastname."', email = '".$data['email']."', mobile = '".$data['mobile']."',password = '".md5($data['password'])."', gender = '".$data['gender']."', otpcode = '".$otp."', is_bulk_user = 'N', user_secret_key = '".$data['user_secret_key']."', dateadded = '".date('Y-m-d h:i:s')."'";
		}			
    	$this->db->query($sql);
    	$inserted_id = $this->db->insert_id();
    	if($this->db->insert_id() > 0)		  
	  		return $inserted_id;
    	else
	  		return '';
	}		
	public function checkEmail($email)
	{
		$this->db->select('email');
		$this->db->where('email',$email);
		$this->db->where(array('user_type'=>2,'is_active'=>'Y'));
		$query = $this->db->get('tblblk_users');
        //echo $this->db->last_query();exit;
		if($query->num_rows() > 0)
            return 0;
        else
            return 1;        	
	}
	public function checkMobile($mobile)
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
	public function getUserData($email)
	{
		$this->db->select('*');
		$this->db->from('tblblk_users');
		$this->db->where(array('email'=>$email,'user_type'=>2));
		$query = $this->db->get();  
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
 	public function verifyUser($userid)
 	{
 		$query = $this->db->query("UPDATE tblblk_users SET 
									is_active='Y',
									status = '1'
									WHERE user_id = " . $userid . " AND user_type = '2' ");
        if ($this->db->trans_status() === false){
            return 0;
        } else {
            if($this->insert_user_log($userid) > 0){
                return 1;
            } else {
                return 0;
            }
        }
 	}
    
    public function GetUserDetails($user_id)
    {
    	$this->db->select('first_name,mobile,is_bulk_user,email,password');
    	$this->db->where(array("user_id"=>$user_id,'user_type'=>2));
       	$query = $this->db->get("tblblk_users");
       	return $query->row();
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
    
    public function ResetOTP($userid)
	{
    	$query = $this->db->query("UPDATE tblblk_users SET 
									otpcode= ' '
									WHERE user_id = ".$userid." AND user_type = '2' ");
        if ($this->db->trans_status() === false) 
        	return 0;
        else
         	return 1;
	}
	public function UpdateOTP($userid,$otp)
	{
	   $query = $this->db->query("UPDATE tblblk_users SET 
										otpcode= ".$otp."
										WHERE user_id = " . $userid . " AND user_type = '2' ");
    	if ($this->db->trans_status() === false) 
    		return 0;
    	else
     		return 1;
	}
	public function verifyForgotOTP($email,$otp)
 	{
 		$this->db->select('otpcode');
 		$this->db->from('tblblk_users');
 		$this->db->where(array('email'=>$email,'otpcode'=>$otp,'user_type'=>2,'is_active'=>'Y','status'=>1));
 		$query = $this->db->get();     		   		
 		return $query->row();
 	}
 	public function ResetForgotOTP($email)
	{
		$update = array('otpcode'=>'');
    	$this->db->where(array('email'=>$email,'user_type'=>2,'is_active'=>'Y','status'=>1));
    	$this->db->update('tblblk_users',$update);
    	if ($this->db->trans_status() === false) 
        	return 0;
        else
         	return 1;
	}
    public function UpdateUserPassword($email,$newpass)
    {
    	$update = array('password'=>$newpass);
    	$this->db->where(array('email'=>$email,'user_type'=>2,'is_active'=>'Y','status'=>1));
    	$this->db->update('tblblk_users',$update);
    	if ($this->db->trans_status() === false) 
        	return 0;
        else
         	return 1;
    }
	//----------update resend otp-------------
	public function update_resend_otp($mobile,$otp)
		{
			$update_otp = array('otpcode'=> $otp);
			$this->db->where('user_type','2');
			$this->db->where('mobile',$mobile);
			$this->db->update('tblblk_users',$update_otp);
			if($this->db->trans_status() === false)
			return 0;
		else
			return 1;
		}
}