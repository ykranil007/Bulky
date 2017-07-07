<?php if(! defined( 'BASEPATH' ) ) exit ('No direct script access allowed');
class AnilApp extends CI_Controller
{
	var $data	=	array();
	public function __construct()
	{
		parent::__construct();
		
		//$this->load->model('AnilApp_Register_model');
	}
	public function index()
	{
		$response = array();
		$post = $this->input->post();
	
		if(!empty($post))
        {
            $response['success'] = 1;
        }			
		else
        {
            $response['success'] = 0;
        }   
            
		echo json_encode($response);
	}
}