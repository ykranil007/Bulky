<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Page extends BNM_Controller 
{	
	public function __construct()
	{
		parent::__construct();
		$this->load->model('home_model'); 
	}
	public function aboutUs()
	{
		$json					= array();
		$json['about_us']   = $this->Comman_model->get_page_setting(5);
		
		$json['refund_cancellation']    = $this->Comman_model->get_page_setting(12);
		$json['return_policy']   		= $this->Comman_model->get_page_setting(11);
		$json['terms_of_use']   		= $this->Comman_model->get_page_setting(15);
		$json['privacy_policy']   		= $this->Comman_model->get_page_setting(8);
		echo  json_encode($json);
	}
	public function faqs()
	{
		$json					= array();
		$json['faq_details']    = $this->Comman_model->get_faq_details();
		echo json_encode($json);
	}
	public function privacyPolicy()
	{
		$json							  = array();
		$json['privacy_policy_details']   = $this->Comman_model->get_page_setting(8);
		echo json_encode($json);
	}
	public function contactUs()
	{
		$json							= array();
		$json['contact_details']   		= $this->Comman_model->getContactPageSetting();
		echo json_encode($json);
	}
}