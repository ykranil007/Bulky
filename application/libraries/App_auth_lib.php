<?php defined('BASEPATH') OR exit('No direct script access allowed');
class App_auth_lib 
{
	protected $ci;
	public  $user_id;
	public $user_secret_key;
	public function __construct()
	{
		$this->ci = & get_instance();
		//$this->ci->load->library('Common');
		$this->ci->load->model('Common_model');    
	}
	 
	public function get_user_details()
    {
    	$post = $this->ci->input->post();
        if(!empty($post['user_id']) && !empty($post['user_secret_key']))
		{
            $user_info  = $this->ci->Common_model->GetTableRow('user_id,user_secret_key',"tblblk_users",array('user_secret_key'=>trim($post['user_secret_key']),'user_id'=>$post['user_id']));
            if(!empty($user_info))
            {
            	return $user_info;
            }
            else
            {
            	//NO USER FOUND
            }            
		}
    }
	
}
?>