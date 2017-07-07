<?php if(! defined( 'BASEPATH' ) ) exit ('No direct script access allowed');
class Register extends CI_Controller
{
	var $data	=	array();
	public function __construct()
	{
		parent::__construct();
        $this->output->set_content_type('application/json');			
		$this->load->model('RegistrationApi/Register_model');
		$this->load->model('LoginApi/login_model');
        $this->load->library('Common');
        $this->load->library('email');			
	}
 public function registerUser() // To register new user
 {
	if(!$this->input->post())
	{
		$responce['status'] = 0;
		$responce['message'] = "Wrong Request.";
		echo json_encode($responce);
	}
	$post = $this->input->post();
	//print_r($post);exit;
	$status = 1;
	$mobile_status = 1;
	$user_secret_key = '';
	if(!empty($post))
	{
		if(!$this->input->post('is_social'))
		{
			$status	= $this->Register_model->checkEmail($post['email']);
			$mobile_status	= $this->Register_model->checkMobile($post['mobile']);
			$user_secret_key = "BNMAPP".generate_random_string(15).date('d-m-Y').generate_random_string(5);
		}
		if($status == 0)
		{
			$responce['success'] = 0;
			$responce['message'] = "Email Already Registered";				
		}
		else if($mobile_status == 0)
		{
			$responce['success'] = 0;
			$responce['message'] = "Mobile Already Registered";
		}
		else
		{
			$mobile = $post['mobile'];
			$otp = rand(1000,9999);
			$msg = 'Hello '.$otp.' is your OTP code for BulknMore. Please do not share with any one.';
			$x = sending_otp($mobile,$msg);			
			if($x)
			{
				$responce['success'] = 1;			
                $post['user_secret_key'] = $user_secret_key;
				$userid                  = $this->Register_model->registerUsers($post,$otp);										
				$userwallet              = $this->login_model->getUserWallet($userid);
				$userInfo 	             = $this->login_model->GetUserInfo($post['email']);
				$responce['user_id'] 	 = $userInfo->user_id;
				$responce['name'] 	 	 = $userInfo->first_name;
				$responce['email'] 	 	 = $userInfo->email;
				$responce['mobile']  	 = $userInfo->mobile;
				$responce['gender']  	 = $userInfo->gender;
				$responce['user_secret_key'] = $userInfo->user_secret_key;
				$responce['user_wallet'] 	= $userwallet;	
			}
			else
			{
				$responce['success'] = 2;
			}
		}
		echo json_encode($responce);
	}		
 }
	public function validateOTP()
	{
		if(!$this->input->post())
		{
			$responce['status'] = 0;
			$responce['message'] = "Wrong Request.";
			echo json_encode($responce);
		}
		$post   = $this->input->post();		
		$status = $this->Register_model->verifyOTP($post['userid'],$post['rotp']);			
		if (!empty($status))
		{
		    $user_info 	= $this->Register_model->GetUserDetails($post['userid']);
            if($this->Register_model->verifyUser($post['userid']) > 0)
            {
                $this->Register_model->ResetOTP($post['userid']);
                
                $autoemail	  = $this->common->GetTableRow('*', 'tblblk_autoemail', array('email_id' =>11));
                $subject   	  = $autoemail->email_subject;
                $content   	  = $autoemail->email_description;
                
                $logo         = base_url().'assets/images/website_logo.png';
                $app_logo     = base_url().'assets/images/app-logo.png';
                $facebook     = base_url().'assets/images/facebook.png';
                $google       = base_url().'assets/images/google-plus.png';
                $linkedin     = base_url().'assets/images/linkedin.png';
                $twitter      = base_url().'assets/images/twitter.png';
                $youtube      = base_url().'assets/images/youtube.png';
                
                $message 	  = str_replace("{email}",$user_info->email,$content);
                $message      = str_replace("{logo}",$logo,$message);
                $message      = str_replace("{small-logo}",$app_logo,$message);
                $message      = str_replace("{facebook}",$facebook,$message);
                $message      = str_replace("{google-plus}",$google,$message);
                $message      = str_replace("{linkedin}",$linkedin,$message);
                $message      = str_replace("{twitter}",$twitter,$message);
                $message      = str_replace("{youtube}",$youtube,$message);
                           
                $message 	  = str_replace("{member}",$user_info->first_name,$message);
                $message 	  = str_replace("{email}",$user_info->email,$message);
                $message 	  = str_replace("{link}",'https:www.bulknmore.com',$message);
                                    
                $this->sendEmail($autoemail->email_from_email,$user_info->email,$subject,$message);
                
                $responce['success'] = 1;
                $responce['message'] = "Yaayyyy!! Welcome to our world. You have successfully registered yourself with us!!"; 
                
                
            }
            else
            {
                $responce['failed'] = 'Something went wrong, please resend OTP!';
            }
		}
		else 
		{
			$responce['success'] = 2;
		}
		echo json_encode($responce);
	}
	public function ForgotPassword()
	{   
        try
        {
            if(!$this->input->post())
            {
               throw new Exception('whats_its_name has nothing to process');
            }
            $json	=	array();
            $post	=	$this->input->post();
            $user_email = $this->Register_model->checkEmail($post['email']);
    	    if($user_email == 0)
    	    {
                $user_info	= $this->Register_model->getUserData($post['email']);
                $otp = rand(1000,9999);
                $autoemail	 		 = $this->common->GetTableRow('*', 'tblblk_autoemail', array('email_id' =>13));
    			$subject   			 = $autoemail->email_subject;
    			$content   			 = $autoemail->email_description;    			
    			$headers    		 = $autoemail->email_from_email;    				
    			$emailcontent 		 = str_replace("{email}",$user_info->email,$content);
    			$emailcontent 		 = str_replace("{member}",$user_info->first_name,$emailcontent);
    			$emailcontent 		 = str_replace("{otp}",$otp,$emailcontent);
    			$getemail            = $this->sendEmail($autoemail->email_from_email,$user_info->email,$subject,$emailcontent);
    			if($getemail)
                {
                    $responce['status']  = 1;
        			$responce['message'] = 'Your OTP Code is Successfully Send on Your Registered Email ID!';
        			$this->Register_model->UpdateOTP($user_info->user_id,$otp);
                }
                else
                {
                    $responce['status']  = 0;
        			$responce['message'] = 'Sorry! Mail Sending Failed. Try Again.';
                }
    		}
    		else
    		{
    			$responce['status'] = 0;
    			$responce['message'] = 'You are not register with us!';
    		}
            echo json_encode($responce);
        }
        catch (Exception $e) 
        {
            show_404();
        }
	}
	public function sendEmail($from_email,$email,$subject,$emailcontent)
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
        
        $this->email->from($from_email,$subject);
        $this->email->to($email);
        $this->email->subject($subject);
        $this->email->message($emailcontent);
        
        if($this->email->send())
        {
            return 1;
        }
        else
        {
            return 0;
        }
	}
	public function validateForgotOTP()
	{
		$data       = $this->data;
		$json	     =	array();
	    	// Getting Form Data-------------------
		$post	=	$this->input->post();
		if(isset($post))
		{
			$userotp = $post['forgot_otp'];
			$otp = $this->Register_model->verifyForgotOTP($post['email'],$userotp);			
			if (!empty($otp))
			{
				$this->Register_model->ResetForgotOTP($post['email']);
				$responce['status'] = 1;
				$responce['message'] = "You Have Successfully Verify OTP! Please Choose New Password. ";			
			}
			else 
			{
				$responce['status'] = 0;
				$responce['message'] = 'Sorry, You Entered Wrong OTP!';
			}
		}
		echo json_encode($responce);
	}	
	public function ChangePassword()
	{
		$data       = $this->data;
		$json	     =	array();
	    $formStatus =	true;
	    	// Getting Form Data-------------------
		$post	=	$this->input->post();
		$password 	=	md5($post['newpassword']);		
		if(!empty($post))
		{
			$responce['status'] = 1;
			$responce['message'] = "Congratulations! You Update Successfully Your Password.";
			$this->Register_model->UpdateUserPassword($post['email'],$password);
			$this->Register_model->ResetForgotOTP($post['email']);
		}
		else
		{
			$responce['status'] = 0;
			$responce['message'] = 'Sorry,Something went wrong!';
		}
		echo json_encode($responce);
	}
	public function ResendOTP()
	{
		$data       = $this->data;
		$json	     =	array();
	    $formStatus =	true;
	    if($formStatus)
		{
			$json['otpsuccess'] = 'Your OTP Verification Code is Successfully Send!';
		}
		else
		{
			$json['otpfailed'] = 'Oops! Something went wrong, Please Try Again!';
		}
		echo json_encode($json);
	}
	//---------------RESEND otp for use --------------
	public function resend_otp()
	{
		$responce	= array();
		$post	=	$this->input->post();
		if(empty($post))
		{
			$responce['status'] = 0;
			$responce['message'] = "Wrong Request.";
		}
		else
		{
			$otp 		= rand(1000,9999);
			$mobile 	= $post['mobile'];
			$msg 		= 'Hello '.$otp.' is your OTP code for BulknMore. Please do not share with any one.';
			//$x = file_get_contents(str_replace('{otp_msg}',$msg,str_replace('{otp_mobile_no}',$mobile,OTP_URL)));	
			$status = $this->Register_model->update_resend_otp($mobile,$otp);		
			if($status > 0)
			{
				 sending_otp($mobile,$msg);
				 $responce['status'] = 1;
				 $responce['message'] = 'Your OTP Verification Code is Successfully Send!';
			}
			else
			{
				$responce['status'] = 0;
				$responce['otpsuccess'] = 'Oops! Something went wrong, Please Try Again!';
			}
		}
		echo json_encode($responce);
	}
}
?>