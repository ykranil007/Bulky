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
		$data = $this->data;
        $data['page_settings']  = $this->Comman_model->get_page_setting(1);
		$data['page_details']   = $this->Comman_model->get_page_setting(5);
        //$data['page_banner']  	= $this->Comman_model->get_page_banners(1);
        //$data['offer_products'] = $this->home_model->get_bestOffers_Products();
		$this->load->view('Page/about_us',$data);
        
	}

	public function faqs()
	{
		$data = $this->data;
		$data['page_settings']  = $this->Comman_model->get_page_setting(1);
		$data['faq_details']    = $this->Comman_model->get_faq_details();
		$this->load->view('Page/faq',$data);
	}

	public function careers()
	{
		$data = $this->data;
		$data['page_settings']  = $this->Comman_model->get_page_setting(1);
		$data['page_details']   = $this->Comman_model->get_page_setting(6);
		$this->load->view('Page/careers',$data);
	}

	public function stories()
	{
		$data = $this->data;
		$data['page_settings']  = $this->Comman_model->get_page_setting(1);
		$data['page_details']   = $this->Comman_model->get_page_setting(7);
		$this->load->view('Page/stories',$data);
	}

	public function privacyPolicy()
	{
		$data = $this->data;
		$data['page_settings']  = $this->Comman_model->get_page_setting(1);
		$data['page_details']   = $this->Comman_model->get_page_setting(8);
		$this->load->view('Page/privacy_policy',$data);
	}

	public function press()
	{
		$data = $this->data;
		$data['page_settings']  = $this->Comman_model->get_page_setting(1);
		$data['page_details']   = $this->Comman_model->get_page_setting(9);
		$this->load->view('Page/press',$data);
	}

	public function sellOnDemand()
	{
		$data = $this->data;
		$data['page_settings']  = $this->Comman_model->get_page_setting(1);
		$data['page_details']   = $this->Comman_model->get_page_setting(10);
		$this->load->view('Page/sell_on_demand',$data);
	}

	public function returnPolicy()
	{
		$data = $this->data;
		$data['page_settings']  = $this->Comman_model->get_page_setting(1);
		$data['page_details']   = $this->Comman_model->get_page_setting(11);
		$this->load->view('Page/return_policy',$data);
	}

	public function refundPolicy()
	{
		$data = $this->data;
		$data['page_settings']  = $this->Comman_model->get_page_setting(1);
		$data['page_details']   = $this->Comman_model->get_page_setting(12);
		$this->load->view('Page/refund_policy',$data);
	}

	public function shippingPolicy()
	{
		$data = $this->data;
		$data['page_settings']  = $this->Comman_model->get_page_setting(1);
		$data['page_details']   = $this->Comman_model->get_page_setting(13);
		$this->load->view('Page/shipping_policy',$data);
	}

	public function termOfUse()
	{
		$data = $this->data;
		$data['page_settings']  = $this->Comman_model->get_page_setting(1);
		$data['page_details']   = $this->Comman_model->get_page_setting(15);
		$this->load->view('Page/terms_of_use',$data);
	}

	public function promotions()
	{
		$data = $this->data;
		$data['page_settings']  = $this->Comman_model->get_page_setting(1);
		$data['page_details']   = $this->Comman_model->get_page_setting(16);
		$this->load->view('Page/promotions',$data);
	}

	public function payments()
	{
		$data = $this->data;
		$data['page_settings']  = $this->Comman_model->get_page_setting(1);
		$data['page_details']   = $this->Comman_model->get_page_setting(17);
		$this->load->view('Page/payments',$data);
	}

	public function savedCards()
	{
		$data = $this->data;
		$data['page_settings']  = $this->Comman_model->get_page_setting(1);
		$data['page_details']   = $this->Comman_model->get_page_setting(18);
		$this->load->view('Page/saved_card',$data);
	}

	public function shipping()
	{
		$data = $this->data;
		$data['page_settings']  = $this->Comman_model->get_page_setting(1);
		$data['page_details']   = $this->Comman_model->get_page_setting(19);
		$this->load->view('Page/shipping',$data);
	}

	public function cancellationReturns()
	{
		$data = $this->data;
		$data['page_settings']  = $this->Comman_model->get_page_setting(1);
		$data['page_details']   = $this->Comman_model->get_page_setting(20);
		$this->load->view('Page/cancellation_returns',$data);
	}

	public function reportInfringement()
	{
		$data = $this->data;
		$data['page_settings']  = $this->Comman_model->get_page_setting(1);
		$data['page_details']   = $this->Comman_model->get_page_setting(21);
		$this->load->view('Page/report_infringement',$data);
	}

	public function Advertise_with_Us()
	{
		$data = $this->data;
		$data['page_settings']  = $this->Comman_model->get_page_setting(1);
		$data['page_details']   = $this->Comman_model->get_page_setting(41);
		$this->load->view('Page/advertise_with_us',$data);
	}

	public function contactUs()
	{
		$data = $this->data;
		$data['page_settings']  = $this->Comman_model->get_page_setting(1);
		$data['contact_page_setting_details']   	= $this->Comman_model->getContactPageSetting();
		//echo "<pre>";print_r($data['contact_page_setting_details']);exit;
		$this->load->view('Page/contact_us',$data);
	}
}