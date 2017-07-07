<?php if(! defined( 'BASEPATH' ) ) exit ('No direct script access allowed');
//session_start();
class Userlogin extends CI_Controller
{   
	public function __construct()
	 { //echo $this->session->userdata('url');exit;
		parent::__construct();
		$this->load->model('dashboard/login_model');
		$this->load->model('Home_model');
		$this->load->helper('cookie');
        $this->load->library(array('common','email'));
	}	
	public function index()
	{	
		$data = $this->data;
		$data['page_settings']->page_title  = $data['page_settings']->page_title;
		$this->load->view("dashboard/viw_login",$data);
	}
	
    public function validate()
    {
        $json = array();
        $login_data = $this->input->post();
        $id=$this->session->userdata('user_id');
        $json['user_id'] = $id;
        $accountStatus  = '';
        if($login_data['login_email'] != '' && $login_data['login_password'] != '') 
            {
                //check log in through social or bulknmore
                $check_password        = $this->login_model->check_Password($login_data);
                //echo $check_password.'rohit';
                if(!empty($check_password) == 0)
                {
                    $accountStatus = 'social_log_in';
                    $this->session->set_userdata('user_email',$login_data['login_email']);
                }
                else
                { 
                    $accountStatus = $this->login_model->verifyAccount($login_data);
                    if(!empty($accountStatus))
                    {
                        $userInfo = $this->login_model->GetUserInfo($login_data);
                        $json['type'] = $userInfo->user_type;
                        $json['email'] = $login_data['login_email'];
                        $session_data = array();
                        foreach($userInfo as $key=>$value)
                       {
                            if($key == 'password' || $key=='is_active'){}
                            else
                            $session_data[$key] = $value;
                       }
                        $accountStatus = 'activated';
                        $this->session->set_userdata($session_data);
                        get_cart_info($userInfo->user_id);                  
                    }
                    else
                    {
                        $accountStatus = 'deactivated';
                    }
                }
            }
            else
            {
                $accountStatus = 'empty';
            }

            if($this->input->cookie("url")!='')
            {
                $json['url'] = $this->input->cookie("url");
            }
            else
            {
                $json['url'] = 'home';
            }
            
            $json['accountstatus'] = $accountStatus;
            echo json_encode($json);
    }
    
    public function validate_otp_login()
    {
        $json = array();
        $login_data = $this->input->post('login_field');
        if(!empty($login_data))
        {
            $otp = rand(1111,9999);
            $otp_userid = $this->login_model->update_otp($login_data,$otp);
            $msg = $otp."-is One Time Password(OTP) for Registration Verification to BulknMore, Pls do not Share With Anyone!";
            if(is_numeric($login_data))
            {
                $this->session->set_userdata('otpuserid',$otp_userid);
                if(sending_otp($login_data,$msg))
                {
                    $json['otp_success'] = 'Your OTP Verification Code is Successfully Send!';
                }
                else
                {
                    $json['otp_failed'] = 'Something went wrong! Try Again.';
                }
            }
            else
            {
                $this->session->set_userdata('otpuserid',$otp_userid);
                $userInfo = $this->login_model->getUserDetails($otp_userid);
                $otp = rand(1111,9999);
                
				$autoemail	  = $this->common->GetTableRow('*', 'tblblk_autoemail', array('email_id' =>16));
				$subject   	  = str_replace("{member}",$userInfo->first_name,$autoemail->email_subject);
				$content   	  = $autoemail->email_description;
				
                $logo         = base_url().'assets/images/website_logo.png';
                $app_logo     = base_url().'assets/images/app-logo.png';
                $facebook     = base_url().'assets/images/facebook.png';
                $google       = base_url().'assets/images/google-plus.png';
                $linkedin     = base_url().'assets/images/linkedin.png';
                $twitter      = base_url().'assets/images/twitter.png';
                $youtube      = base_url().'assets/images/youtube.png';
                
                $message 	  = str_replace("{email}",$login_data,$content);
                $message      = str_replace("{logo}",$logo,$message);
                $message      = str_replace("{small-logo}",$app_logo,$message);
				$message      = str_replace("{facebook}",$facebook,$message);
                $message      = str_replace("{google-plus}",$google,$message);
                $message      = str_replace("{linkedin}",$linkedin,$message);
                $message      = str_replace("{twitter}",$twitter,$message);
                $message      = str_replace("{youtube}",$youtube,$message);                
				$message 	  = str_replace("{member}",$userInfo->first_name,$message);
				$message 	  = str_replace("{code}",$otp,$message);
                $message 	  = str_replace("{link}",'https://www.bulknmore.com',$message);
                
				$getemail            = $this->sendEmail($autoemail->email_from_email,$login_data,$subject,$message);
				$json['otp_success'] = 'Your OTP Code is Successfully Send on Your Email!';
				$this->login_model->update_otp($userInfo->email,$otp);
            }
            
        }
        else
        {    
            $json['otp_failed'] = 'Something went wrong! Try Again.';       
        }
        echo json_encode($json);
    }
    
    public function validate_otp_login_otp()
    {
        $json = array();
        $accountStatus  = '';
        $login_otp = $this->input->post('login_otp');
        if(!empty($login_otp))
        {
            $user_id = $this->session->userdata('otpuserid');
            $status = $this->login_model->verify_user_with_otp($user_id,$login_otp);
            //echo "fsa".$status.'ID'.$user_id;exit;
            if(!empty($status))
            {
                $userInfo = $this->login_model->getUserDetails($user_id);                
                $this->session->set_userdata('user_id',$userInfo->user_id);
                $this->login_model->update_otp($userInfo->email,'');
                get_cart_info($userInfo->user_id);
                $accountStatus = 'activated';
            }
            else
            {
                $accountStatus = 'deactivated';
            }
        }
        else
        {    
            $json['otp_failed'] = 'Something went wrong! Try Again.';       
        }
        if($this->input->cookie("url")!='')
        {
            $json['url'] = $this->input->cookie("url");
        }
        else
        {
            $json['url'] = 'home';
        }
        $json['accountstatus'] = $accountStatus;
        echo json_encode($json);
    }
    
    public function resendOTP()
    {
        $json = array();
        $user_id = $this->session->userdata('otpuserid');
        $userInfo = $this->login_model->get_User_Info($user_id);
        $otp = rand(1111,9999);
    	$message = $otp."-is One Time Password(OTP) for Registration Verification to BulknMore, Pls do not Share With Anyone!";
        if(sending_otp($userInfo->mobile,$message))
        {
            if($this->login_model->update_otp($userInfo->email,$otp))
            {
                $json['s_message'] = 'Your OTP Verification  Code is Resend Successfully on - '.substr($userInfo->mobile,0,1).'xxxxx'.substr($userInfo->mobile,7,10);    
            }
            else
            {
                $json['f_message'] = 'Opps! Sending Failed, Resend Again.';
            }            
        }
        else
        {
            $json['f_message'] = 'Opps! Sending Failed, Send Again.';
        }
        echo json_encode($json);
    }
    
	public function getSocialLoginPage()
	{
		//$data = $this->data;
        $user_id = $this->session->userdata("user_id");
        $this->load->view('social_login_upgrade_form');
    }

    public function upgradeMobile()
    { 
    	$json = array();
    	$user_id        = $this->session->userdata("user_id"); 
        $user_email     = $this->session->userdata('user_email');
        $json['mobile'] = false;
        if($user_id=='')
        {
            $user_id = $this->login_model->get_user_id($user_email);
        }
        //echo $user_email;exit;
    	$mobile = $this->input->post('verify_mobile');
        $status = $this->login_model->check_mobile($mobile); //echo $status;
        if($status==0)
        {
            $msg['msg_class'] = 'register_msg error';
            $msg['message'] = "<strong>Oh snap!</strong> Mobile Already Exist!";
            $json['status'] = true;
            $json['mobile'] = true;
        }
        else
        {
        	$otp = rand(1000,9999);
        	$message = $otp."-is One Time Password(OTP) for Registration Verification to BulknMore, Pls do not Share With Anyone!";
        	//$x = file_get_contents(str_replace('{otp_msg}',$message,str_replace('{otp_mobile_no}',$mobile,OTP_URL)));
            $x = sending_otp($mobile,$message);
        	$status = $this->login_model->upgradeUserMobile($user_id,$mobile,$otp);
        	if ($status)
        		{ 
        			$msg['msg_class'] = 'register_msg success';
                    $msg['message'] = 'Your OTP Verification Code is Successfully Send on - '.substr($mobile,0,1).'xxxxx'.substr($mobile,7,10);
                    $json['status'] = true;
                }
                else 
                	{
                		$msg['msg_class'] = 'register_msg error';
                    	$msg['message'] = "<strong>Oh snap!</strong> An error has been occured while updating Mobile...";
                    	$json['status'] = false;
                	}
        }
           
             $json['msg'] = $msg;
             $notify = $msg;
            $this->session->set_flashdata('notity', $notify);
            echo json_encode($json);
        
    }

    public function resendMobile()
    {
        $json = array();
        $user_id        = $this->session->userdata("user_id"); 
        $user_email     = $this->session->userdata('user_email');
        if($user_id=='')
        {
            $user_id = $this->login_model->get_user_id($user_email);
        }
        //echo $user_email;exit;
        $mobile = $this->input->get('mobile_number');      
        $otp = rand(1000,9999);
        $message = $otp."-is One Time Password(OTP) for Registration Verification to BulknMore, Pls do not Share With Anyone!";
        //$x = file_get_contents(str_replace('{otp_msg}',$message,str_replace('{otp_mobile_no}',$mobile,OTP_URL)));
        $x = sending_otp($mobile,$message);
        $status = $this->login_model->upgradeUserMobile($user_id,$mobile,$otp);
        if ($status)
        { 
            $msg['msg_class'] = 'register_msg success';
            $msg['message'] = 'Your OTP Verification  Code is Resend Successfully Send on - '.substr($mobile,0,1).'xxxxx'.substr($mobile,7,10);
            $json['status'] = true;
        }
        else 
        {
            $msg['msg_class'] = 'register_msg error';
            $msg['message'] = "<strong>Oh snap!</strong> An error has been occured while updating Mobile...";
            $json['status'] = false;
        }
        $json['msg'] = $msg;
        $notify = $msg;
        $this->session->set_flashdata('notity', $notify);
        echo json_encode($json);       
    }

    public function getSocialLoginIssue()
    {
        $this->load->view('social_login_issue_form');
    }

    public function upgradeOtp()
    { 
    	$json 		= array();
        $user_id 	= $this->session->userdata("user_id");
        $user_email     = $this->session->userdata('user_email');
        if($user_id=='')
        {
            $user_id = $this->login_model->get_user_id($user_email);
        }

    	$post_otp 	= $this->input->post('otpcode');
      	$get_otp 	= $this->login_model->getUserOtp($user_id);
        
        //$json['mt'] = $matchpassword->password;
        if ($post_otp==$get_otp) {
        	//remove otp value from database when otp is match
            $status = $this->login_model->updateUserOtp($user_id);
            if ($status) {
                $msg['msg_class'] = 'register_msg success';
                $msg['message'] = "Please Enter Your Password....";
                $json['status'] = true;
            } else {
                $msg['msg_class'] = 'register_msg error';
                $msg['message'] = "<strong>Oh snap!</strong> An error has been occured while checking otp...";
                $json['status'] = false;
            }
        } else {
            $msg['msg_class'] = 'register_msg error';
            $msg['message'] = "<strong>Oh snap!</strong> Your OTP  is incorrect please Re Enter.";
            $json['status'] = false;
        }
        $json['msg'] = $msg;
        echo json_encode($json);
    }

    public function upgradePassword()
    {
    	$json = array();
    	$user_id = $this->session->userdata("user_id");
        $url            = 'https://www.bulknmore.com/';//$this->session->userdata('social_redirect'); 
        $user_email     = $this->session->userdata('user_email');
        if($user_id=='')
        {
            $user_id = $this->login_model->get_user_id($user_email);
        }
    	$password = $this->input->post('password'); 
    	$status = $this->login_model->upgradeUserPassword($user_id,$password);
    	if ($status)
    		{
                $user_info = $this->login_model->get_User_Info($user_id);
                // setting value in session
                $this->setUserSessionValues($user_info); 
    			$msg['msg_class'] = 'register_msg success';
                $msg['message'] = "Your Password has been saved Successfully";
                $json['status'] = true;
                get_cart_info($user_id);
            }
            else 
            	{
            		$msg['msg_class'] = 'register_msg error';
                	$msg['message'] = "<strong>Oh snap!</strong> An error has been occured while Saving Password...";
                	$json['status'] = false;
            	}
       
         $json['msg'] = $msg;
         $json['url'] = $url;
         $notify = $msg;
        $this->session->set_flashdata('notity', $notify);
        echo json_encode($json);
    }

    public function setUserSessionValues($user_info)
    { 
        $session_data = array();
        foreach($user_info as $key=>$value)
        {
            if($key == 'password' || $key=='is_active')
            {}
            else
            $session_data[$key] = $value;
        }
        $this->session->set_userdata($session_data);
    }

	public function userLogout()
	{ 
	    $this->clear_user_vouchers_history($this->session->userdata('user_id'));
		$this->session->sess_destroy();
        //$this->session->userdata('user_id');
        //$this->session->unset_userdata('user_id');
        $this->delete_session( 'facebook_access_token' );		
		redirect();
	}
    private function delete_session( $key )
    {
        session_start();
        unset( $_SESSION[$key] );
    }
    
    public function redirect_facebook()
    {
        redirect(str_replace('amp;','',get_facebook_url(true)));
    }
    
    public function clear_user_vouchers_history($user_id)
	{
		if($this->login_model->clear_user_vouchers_history($user_id) > 0)
		{
			return 1;
		}
	}
    
    public function sendEmail($email_from,$email_to,$email_subject,$message)
	{   
		$config = array();
        $config['useragent']    = "CodeIgniter";
        $config['mailpath']     = "/usr/bin/sendmail"; // or "/usr/sbin/sendmail"
        $config['protocol']     = "smtp";
        $config['smtp_host']    = "localhost";
        $config['smtp_port']    = "25";
        $config['mailtype']     = 'html';
        $config['charset']      = 'utf-8';
        $config['newline']      = "\r\n";
        $config['wordwrap']     = TRUE;
        $this->email->initialize($config);
        $this->email->from($email_from,$email_subject);
        $this->email->to($email_to);
        $this->email->subject($email_subject);
        $this->email->message($message);
        if($this->email->send())
        {
            return 1;
        }
        else
        {
            return 0;
        }
	}
}