<?php if(! defined( 'BASEPATH' ) ) exit ('No direct script access allowed');
	
class Userlogin extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('LoginApi/login_model');		
        $this->load->model('shared/Comman_model');
	}	
	
	public function validate()
	{
		if(!$this->input->post())
		{
			$responce['status'] = 0;
			$responce['message'] = "Wrong Request.";
			echo json_encode($responce);
		}
		$post = $this->input->post();
		
		$accountStatus = $this->login_model->verifyAccount($post);
		if($accountStatus!=0)
		{
			$userInfo 	= $this->login_model->GetUserInfo($post['lemail']);
			$userwallet = $this->login_model->getUserWallet($userInfo->user_id);
            $cart_info = $this->Comman_model->get_cart_data($userInfo->user_id);
            $cart_list = create_cart_product_listing($cart_info);
            //print_r($cart_list['cart_list']);exit;
			$responce['success'] = 1;
			
			$responce['user_id'] 	= $userInfo->user_id;
			$responce['name'] 	 	= $userInfo->first_name;
			$responce['email'] 	 	= $userInfo->email;
			$responce['mobile']  	= $userInfo->mobile;
			$responce['gender']  	= $userInfo->gender;
			$responce['user_secret_key']  	= $userInfo->user_secret_key;
			$responce['user_wallet'] = $userwallet;
            $responce['cart_count']  = count($cart_list['cart_list']);
		}
		else
		{
			$responce['success'] = 2;
		}
		echo json_encode($responce);
	}

	/*public function userLogout()
	{ 
		//$this->session->unset_userdata($session_data);
		$this->session->sess_destroy();		
		redirect('/home');
	}*/

}