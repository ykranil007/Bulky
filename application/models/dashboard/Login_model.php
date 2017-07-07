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
        $this->db->where(array(
            'password' => md5($data['login_password']),
            'user_type' => 2,
            'is_active' => 'Y',
            'status' => 1));
        $this->db->group_start();
        $this->db->where('email', $data['login_email']);
        $this->db->or_where('mobile', $data['login_email']);
        $this->db->group_end();
        $query = $this->db->get();
        return $query->row();
    }

    public function GetUserInfo($data)
    {
        $this->db->query("UPDATE tblblk_users set last_login=now() WHERE (email=" . $this->
            db->escape($data['login_email']) . " OR mobile= " . $this->db->escape($data['login_email']) .
            ") AND user_type = 2 ");

        $this->db->select('*');
        $this->db->from('tblblk_users');
        $this->db->group_start();
        $this->db->where('email', $data['login_email']);
        $this->db->or_where('mobile', $data['login_email']);
        $this->db->group_end();
        $this->db->where('user_type', 2);
        $query = $this->db->get();
        return $query->row();
    }

    public function getUserDetails($id)
    {
        $query = $this->db->query("SELECT * FROM tblblk_users WHERE user_id='$id'");
        return $query->row();
    }

    public function upgradeUserMobile($user_id, $mobile, $otp)
    {
        $query = $this->db->query("UPDATE tblblk_users SET mobile=" . $this->db->escape
            ($mobile) . ",	otpcode=" . $this->db->escape($otp) . " WHERE user_id='$user_id' ");
        if ($this->db->affected_rows() >= 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getUserOtp($user_id)
    {
        $this->db->select('otpcode');
        $this->db->where('user_id', $user_id);
        $query = $this->db->get('tblblk_users');
        if (count($query->row()) > 0)
            return $query->row()->otpcode;
        else
            return 0;
    }

    public function updateUserOtp($user_id)
    {
        /*$query=$this->db->query("UPDATE tblblk_users SET otpcode='' WHERE user_id='$user_id' ");
        if($this->db->affected_rows()>=0)
        return true;
        else
        return false;*/

        $data = array(
            'otpcode' => '',
            'is_active' => 'Y',
            'status' => 1);
        $this->db->where('user_id', $user_id);
        if ($this->db->update('tblblk_users', $data))
            return true;
        else
            return false;
    }

    public function upgradeUserPassword($user_id, $password)
    {
        $query = $this->db->query("UPDATE tblblk_users SET password=" . $this->db->
            escape(md5($password)) . " WHERE user_id='$user_id' ");
        if ($this->db->affected_rows() >= 0)
            return true;
        else
            return false;
    }
    //--- Function for checking whether user exists or not
    public function check_Password($data)
    {
        $this->db->select('password');
        $this->db->from('tblblk_users');
        $this->db->group_start();
        $this->db->where(array(
            'email' => $data['login_email'],
            'user_type' => 2,
            'is_social' => 'Y'));
        $this->db->or_where(array(
            'mobile' => $data['login_email'],
            'user_type' => 2,
            'is_social' => 'Y'));
        $this->db->group_end();
        $query = $this->db->get();
        if (count($query->row()) > 0)
            return $query->row()->password;
        else
            return 0;
    }

    public function get_user_id($useremail)
    {
        $this->db->select('user_id');
        $this->db->where('email', $useremail);
        $this->db->where('user_type', 2);
        $query = $this->db->get('tblblk_users');
        if (count($query->row()) > 0)
            return $query->row()->user_id;
        else
            return 0;
    }

    public function get_User_Info($user_id)
    {
        $this->db->select('*');
        $this->db->from('tblblk_users');
        $this->db->where('user_id', $user_id);
        $this->db->where('user_type', 2);
        $query = $this->db->get();
        return $query->row();
    }

    public function check_mobile($mobile)
    {
        $this->db->select('mobile');
        $this->db->where('mobile', $mobile);
        $this->db->where(array('user_type' => 2, 'is_active' => 'Y'));
        $query = $this->db->get('tblblk_users');
        if ($query->num_rows() > 0)
            return 0;
        else
            return 1;
    }

    public function update_otp($login_data, $otp)
    {
        $update = array('otpcode' => $otp);
        $this->db->where(array(
            'user_type' => 2,
            'is_active' => 'Y',
            'status' => 1));
        $this->db->group_start();
        $this->db->or_where(array('mobile' => $login_data, 'email' => $login_data));
        $this->db->group_end();
        if ($this->db->update('tblblk_users', $update)) {
            return $this->get_userid($login_data);
        } else {
            return 0;
        }
    }

    public function get_userid($login_data)
    {
        $this->db->select('user_id');
        $this->db->where(array(
            'user_type' => 2,
            'is_active' => 'Y',
            'status' => 1));
        $this->db->group_start();
        $this->db->or_where(array('mobile' => $login_data, 'email' => $login_data));
        $this->db->group_end();
        $this->db->from('tblblk_users');
        $query = $this->db->get();
        return $query->row()->user_id;
    }

    public function verify_user_with_otp($user_id, $otp)
    {
        $this->db->select('otpcode');
        $this->db->where(array(
            'user_id' => $user_id,
            'otpcode' => $otp,
            'is_active' => 'Y',
            'status' => 1,
            'user_type' => 2));
        $this->db->from('tblblk_users');
        $query = $this->db->get();
        //echo $this->db->last_query();exit;
        return $query->row();
    }
    
    public function clear_user_vouchers_history($user_id)
    {
    	$update = array('voucher_code'=>'');
    	$this->db->where('user_id',$user_id);
    	if($this->db->update('tblblk_product_cart',$update)){
	    	return 1;
	    }
	    else{
    		return 0;
	    }
    }
}
