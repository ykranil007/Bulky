<?php if(! defined( 'BASEPATH' ) ) exit ('No direct script access allowed');
	class Register extends BNM_Controller
	{
		var $data	=	array();
		public function __construct()
		{
			parent::__construct();
			if($this->session->userdata("user_id"))
			{
				redirect("home");
			}
			$this->load->model('Register_model');			
			$this->load->model('home_model');
			//$this->load->helper('create_product');
			$this->load->library('email');
			$this->load->library('Common');
		}
	public function index()
	{
		$data = $this->data;
		$data['page_settings']  = $this->Comman_model->get_page_setting(1);
		$data['page_settings']->page_title = $data['page_settings']->page_title.ucfirst('| Login');		
		$this->load->view('dashboard/viw_login',$data);			
	}
	public function FacebookLogin()
	{ 
		if($this->user)
		{
			try{
				$me = $this->facebook->api('/me');
				$this->session->set_userdata('facebook',$me['id']);
			} catch(FacebookApiException $e)
			{
				$this->user = "NULL";
			}
			$logout = $this->facebook->getLogoutUrl(array("next"=>base_url().'Userlogin/userLogout'));
			echo "<a href='$logout'>Logout</a>";
			//echo $logout;
		}
		else
		{
			die("<script>top.location'".$this->facebook->getLoginUrl(array("scope"=>"email",
				                                                            "redirect_url"=>site_url("Register")))."'</script>");
		}
	}
	 public function registerUser() // To register new user
	 {
		$data       = $this->data;
		$json	     =	array();
  	    $formStatus =	true;
  	   	$flag= '';
	       // Getting Form Data-------------------
			$formdata	=	$this->input->post();
			$status = $this->Register_model->check_mobile($formdata['mobile']);
			if(!empty($status) != 0)
			{
				$json['Available'] = '<span style="color:green">Available Continue.</span>';
			}
			else
			{
				$json['exist'] = 'Mobile Already Exist!';
				$formStatus = false;
			}		
			// Required Fields ---------------------------------
			if(empty($formdata['terms']))
			{
				$json['terms_error'] = 'Please Agree our Terms & Conditions.';
				$formStatus = false;
			}
			else
			{
				$json['terms_error'] = ' ';
			}
			if(!empty($formdata['is_bulk_user']))
			{
				$json['is_bulk_user'] = 'Bulk User';
			}
	// End of Required Fields -------------------
	        if($formStatus) 
			{
				$mobile = $formdata['mobile'];
				$otp = rand(1000,9999);
				$msg = 'Hello '.$otp.' is your OTP code for BulknMore. Please do not share with any one.';
				$x = sending_otp($mobile,$msg);
				if($x)
				{
					$json['otpsuccess'] = 'Your OTP Verification Code is Successfully Send!';
					$userid = $this->Register_model->registerUsers($formdata,$otp);					
					$this->session->set_userdata('reg_user_id',$userid);
				}
				else
				{
					$json['otpfailed'] = 'Oops! Something went wrong, Please Try Again!';
				}
			}
			echo json_encode($json);
	 }
	public function validateOTP()
	{
		$data       = $this->data;
		$json	     =	array();
	    	$formStatus =	true;
	    	// Getting Form Data-------------------
		$formdata	=	$this->input->post();
		$userid = $this->session->userdata("reg_user_id");		
		// Required Fields ---------------------------------
		if($formdata['verification']=='')
		{
			$json['verification_error'] = 'Please Enter OTP Code.';
			$formStatus = false;
		}
		else
		{
			$json['verification_error'] = ' ';
		}
		if($formStatus)
		{
			$usercode = $formdata['verification'];
			$status = $this->Register_model->verifyOTP($userid,$usercode);
			$user_info 	= $this->Register_model->GetUserDetails($userid);			
			if (!empty($status))
			{
				if($user_info->is_bulk_user == 'Y')
				{
					$json['is_bulk_user'] = 'Successfully Verified! Please Upload Documents.';
				}
				else
				{
					if($this->Register_model->verifyUser($userid) > 0)
                    {
                        $this->Register_model->ResetOTP($userid);
                        
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
                        $json['success'] = "Yaayyyy!! Welcome to our world. You have successfully registered yourself with us!!"; 
                        
                        
                    }
                    else
                    {
                        $json['failed'] = 'Something went wrong, please resend OTP!';
                    }
					
                    
				}
			}
			else 
			{
				$json['failed'] = 'Sorry, You Entered Wrong OTP!';
			}
		}
		echo json_encode($json);
	}
	public function checkEmail()
	{
		$data	=	$this->data;
		$json	=	array();
		$formdata	=	$this->input->post();
		if(isset($formdata['email']))
		{
			$email = $formdata['email'];
		}
		else
		{
			$email = $formdata['forgot_email'];
		}
		if(!empty($email))
		{
			$status	=	$this->Register_model->checkemailexist($email);
			if(!empty($status))
			{
				$json['forgotfailed'] = 'This Email is Not Registered With Us!';
				$json['success'] = '<span style="color:green">The Email is Available. Please Continue.</span>';
			} 
			else
			{
				$json['forgotsuccess'] = '<span style="color:green">The Email is Available. Please Continue.</span>';
				$json['failed'] = 'This Email is Not Availabe! Try with Another.';
			}
		}
		echo json_encode($json);
	}
	public function check_mobile_exist()
	{
		$json =	array();
		$post = $this->input->post();
		$status = $this->Register_model->check_mobile($post['mobile']);
		if(!empty($status) != 0)
		{
			$json['Available'] = '<span style="color:green">Available Continue.</span>';
		}
		else
		{
			$json['exist'] = 'Mobile Already Exist!';
			return false;
		}
		echo json_encode($json);
	}
	public function register_thankYou()
	{
		$data = $this->data;
		$data['page_settings'] = $this->Register_model->getPageElements(3);
		//echo "<pre>";print_r($data['pageInfo']);exit;
		$this->load->view('dashboard/view_thankyou',$data);
	}
	public function forgotPassword()
	{
	    $data = $this->data;
	    $data['page_settings'] = $this->Register_model->getPageElements(35);
	    $this->data['login_url'] = ''; //$this->facebook->getLoginUrl(array('redirect_uri' => site_url('welcome/flogin'),'scope' => array("email"))); 
  		$data['login_url']      = $this->data['login_url'];
	    $this->load->view('dashboard/view_forgot_password',$data);
	}
	public function ChooseForgotMethod()
	{
		$data       = 	$this->data;
		$json	    =	array();
		$formStatus =	true;
		$formdata	=	$this->input->post();
		$user_email = $formdata['forgot_email'];
		$status	=	$this->Register_model->checkemailexist($user_email);
		if($status != 1)
		{			
			$user_info	= $this->Register_model->getUserData($formdata['forgot_email']);
			if($formdata['forgot_email']=='')
			{
				$json['email_error'] = 'Please Enter Email-ID.';
				$formStatus = false;
			}
			else
			{
				$json['email_error'] = ' ';
			}		
			if($formStatus && !empty($user_info))
			{	
				$otp = rand(1000,9999);
                
				$autoemail	 		 = $this->common->GetTableRow('*', 'tblblk_autoemail', array('email_id' =>13));
				$subject   			 = $autoemail->email_subject;
				$content   			 = $autoemail->email_description;
				
                $logo         = base_url().'assets/images/website_logo.png';
                $app_logo     = base_url().'assets/images/app-logo.png';
                $facebook     = base_url().'assets/images/facebook.png';
                $google       = base_url().'assets/images/google-plus.png';
                $linkedin     = base_url().'assets/images/linkedin.png';
                $twitter      = base_url().'assets/images/twitter.png';
                $youtube      = base_url().'assets/images/youtube.png';
                
                $message 		 = str_replace("{email}",$user_email,$content);
                $message         = str_replace("{logo}",$logo,$message);
                $message         = str_replace("{small-logo}",$app_logo,$message);
				$message         = str_replace("{facebook}",$facebook,$message);
                $message         = str_replace("{google-plus}",$google,$message);
                $message         = str_replace("{linkedin}",$linkedin,$message);
                $message         = str_replace("{twitter}",$twitter,$message);
                $message         = str_replace("{youtube}",$youtube,$message);                
				$message 		 = str_replace("{member}",$user_info->first_name,$message);
				$message 		 = str_replace("{otp}",$otp,$message);
                
				$getemail            = $this->sendEmail($autoemail->email_from_email,$user_email,$subject,$message);
				$json['otpsuccess'] = 'Your OTP Code is Successfully Send on Your Email ID!';
				$this->Register_model->UpdateOTP($user_info->user_id,$otp);
				$this->session->set_userdata('user_email',$user_email);
			}
		}
		else
		{
			$json['forgotfailed'] = 'This Email is Not Registered With Us!';
		}
		echo json_encode($json);
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
	public function validateForgotOTP()
	{
		$data       = $this->data;
		$json	     =	array();
	    $formStatus =	true;
	    	// Getting Form Data-------------------
		$formdata	=	$this->input->post();
		$email = $this->session->userdata('user_email');
		// Required Fields ---------------------------------
		if($formdata['verification']=='')
		{
			$json['verification_error'] = 'Please Enter OTP Code.';
			$formStatus = false;
		}
		else
		{
			$json['verification_error'] = ' ';
		}
		if($formStatus)
		{
			$usercode = $formdata['verification'];
			$otp = $this->Register_model->verifyForgotOTP($email,$usercode);			
			if (!empty($otp))
			{
				$json['success'] = "You Have Successfully Verify OTP! Please Choose New Password. ";
				$json['usercode'] = $usercode;			
			}
			else 
			{
				$json['failed'] = 'Sorry, You Entered Wrong OTP!';
			}
		}
		echo json_encode($json);
	}	
	public function ChangePassword()
	{
		$data       = $this->data;
		$json	     =	array();
	    $formStatus =	true;
	    	// Getting Form Data-------------------
		$formdata	=	$this->input->post();
		$usercode 	=	$formdata['user_code'];
		$password 	=	md5($formdata['newpassword']);
		// Required Fields ---------------------------------
		if($formdata['newpassword']=='')
		{
			$json['create_password_error'] = 'Please Enter Password.';
			$formStatus = false;
		}
		else
		{
			$json['create_password_error'] = ' ';
		}
		if($formStatus)
		{
			$json['success'] = "Congratulations! You Update Successfully Your Password.";
			$this->Register_model->UpdateUserPassword($usercode,$password);
			$this->Register_model->ResetOTP($usercode);
			$json['url'] = base_url();
		}
		echo json_encode($json);
	}
	
	public function resendOTP()
	{
		$data	=	$this->data;
		$json	=	array();
		$formStatus =	true;
		$user_id = $this->session->userdata('reg_user_id');
		$user = $this->Register_model->GetUserDetails($user_id);
		$otp = rand(1000,9999);
		$msg = 'Hello '.$otp.' is your OTP code for BulknMore. Please do not share with any one.';
		$x = sending_otp($user->mobile,$msg);
        if($x)
        {
			$status = $this->Register_model->UpdateOTP($user_id,$otp);
		}
		else
		{
			$status= false;
		}
		if($status)
		{
			$json['success'] = 'Your OTP Verification Code is Successfully Re-Send on - '.substr($user->mobile,0,1).'xxxxx'.substr($user->mobile,7,10); 
		}
		else
		{
			$json['failed'] = 'Oops! Something went wrong, Please Try Again!';
		}
		echo json_encode($json);
	}
	public function validateNewOTP()
	{
		$data       = $this->data;
		$json	     =	array();
	    $formStatus =	true;
	    // Getting Form Data-------------------
		$formdata	=	$this->input->post();
		$user_id = $this->session->userdata('reg_user_id');
        $status = $this->Register_model->check_mobile($formdata['new_mobile']);
		// Required Fields ---------------------------------
		if($formdata['new_mobile']=='')
		{
			$json['new_mobile_error'] = 'Please Enter Mobile No.';
			$formStatus = false;
		}
        if(!empty($status) != 0)
        {
            $json['new_mobile_error'] = ' ';           
        }
		else
		{		  
            $json['new_mobile_error'] = 'Mobile Already Exist';
            $formStatus = false;			
		}
		if($formStatus) 
		{
			$mobile = $formdata['new_mobile'];
			$otp = rand(1000,9999);
			$msg = 'Hello '.$otp.' is your OTP code for BulknMore. Please do not share with any one.';
			$x = sending_otp($mobile,$msg);
			if($x)
			{
				$json['otpsuccess'] = 'Your OTP Verification Code is Successfully Send on - '.substr($mobile,0,1).'xxxxx'.substr($mobile,7,10);
				$this->Register_model->UpdateMobile($mobile,$otp,$user_id);
			}
			else
			{
				$json['otpfailed'] = 'Oops! Something went wrong, Please Try Again!';
			}
		}
		echo json_encode($json);
	}
	public function UploadBulkUserDocuments()
	{
		$data      	= $this->data;
		$json	    =	array();
	    $formStatus =	true;
	    // Getting Form Data-------------------
		$post	=	$this->input->post();
		$user_id = $this->session->userdata('reg_user_id');
		//---- Upload Image Section Starts Here 
		$image_path 			 = 'assets/bulk_user_documents/';
		$config['upload_path']   = $image_path;
		$config["allowed_types"] = 'jpg|jpeg|png|gif';
		$config["max_size"] 	 = 10240;
		$config['remove_spaces'] = TRUE;
		$config['detect_mime']	 = TRUE;
		$config['overwrite']	 = FALSE;
		$this->load->library('upload', $config);
		if($this->upload->do_upload('bulk_user_document'))
		{
			$file_name 	= $this->upload->data('file_name');
			$image_src 	= $image_path.$file_name;
			$encrpt_img = rand(100000,999999).$user_id.'_'.$file_name;
			// Read image path, convert to base64 encoding
			$imgData['image'] = base64_encode(file_get_contents($image_src));
			$imgData['image_name'] = $encrpt_img;
			$dest_src =  ADMIN_BASE_URL.'api/save_image';
			//$string = array('admin'=>2,'user'=>3);
			$test = $this->Curl_execution($dest_src,$imgData);
			if($test->status == 1)
			{
				$json['status'] = 1;
				$this->Register_model->uploadBulkUserDocuments($encrpt_img,$user_id);
			}
			else
			{
				$json['status'] = 0;
			}
			echo json_encode($json);
		}
	}
	private function Curl_execution($dest_src,$imgData)
	{
		//open connection
        $ch = curl_init();
        //set the url, number of POST vars, POST data
        curl_setopt($ch,CURLOPT_URL,$dest_src);
        curl_setopt($ch,CURLOPT_POST,true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$imgData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return json_decode($result);
	}
}
?>