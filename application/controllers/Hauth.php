<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
class HAuth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('session');
        $this->load->library('user_agent');
        
        //$this->input->set_cookie('url', $url,0);
        //$this->session->set_userdata('url',$url);
    }

    public function index()
    {
        $this->load->view('hauth/home');
    }
	
	public function doLogin($provider)
	{ 
		//==================after login redication core =============================
			$value = $this->session->userdata('social_redirect');
			if($value=='')
			{
				$previous_url = $this->agent->referrer();
				if (preg_match("/bulknmore.com/", $previous_url)) 
				{
					$this->session->set_userdata('social_redirect',$previous_url);
				}
				elseif($this->session->userdata('social_redirect')=='')
				{ 
					redirect('home');
				}
			}
			$this->login($provider);
		//==================after login redication core =============================
	}
	
    public function login($provider)
    { 
        
        $this->load->model('Common_model');
        log_message('debug', "controllers.HAuth.login($provider) called");
        try
        {
            log_message('debug', 'controllers.HAuth.login: loading HybridAuthLib');
            $this->load->library('HybridAuthLib');

			if ($this->hybridauthlib->providerEnabled($provider))
            { 
                log_message('debug', "controllers.HAuth.login: service $provider enabled, trying to authenticate.");
                $service = $this->hybridauthlib->authenticate($provider);
				
                if ($service->isUserConnected())
                {
                    log_message('debug', 'controller.HAuth.login: user authenticated.');
                    $user_profile = $service->getUserProfile();
                	
					if($provider=="Google" && trim($user_profile->email)!='')
					{
						$status = $this->Common_model->isUserExists(trim($user_profile->email));
						if($status == 0) // if user is not registered with us then registering user
						{
							$user_id = $this->Common_model->registerNewUserFromSocialAccount($user_profile->firstName,$user_profile->lastName,$user_profile->email,$user_profile->phone,ucfirst($user_profile->gender));
							
							$user_info = $this->Common_model->getUserInfo($user_id);
                            //add cart info 
                            get_cart_info($user_info->user_id);
							// setting value in session
							$this->setUserSessionValues($user_info);
							 redirect('upgrade/form');
						}
                        else
                        {
                            $user_info = $this->Common_model->getUserInfo($user_profile->email);
                            if(!empty($user_info))
                            {
                                 //add cart info 
                                get_cart_info($user_info->user_id);
                               $this->setUserSessionValues($user_info);
                                if($user_info->password!='')
                                {

                                redirect($this->session->userdata('social_redirect'));
                                }
                                else
                                {
                                    redirect('upgrade/form');
                                } 
                            }
                            else
                            {
                                redirect('home');
                            }
                            
                        }
					}
                    /*else
                    {

                    }*/
					/*else if(trim($user_profile->email)!=''){
						$user_info = $this->Common_model->getUserInfo($user_profile->email);
						$this->setUserSessionValues($user_info);
						redirect('login');
					}*/
					else{
						 redirect('upgrade/form');
					}	
						
						
                    log_message('info', 'controllers.HAuth.login: user profile:' . PHP_EOL . print_r($user_profile, true));
										$access_token = $this->hybridauthlib->getSessionData();


                }
                else // Cannot authenticate user
                {
                    show_error('Cannot authenticate user');
                }
            }
            else // This service is not enabled.
            {
                log_message('error', 'controllers.HAuth.login: This provider is not enabled (' .
                    $provider . ')');
                show_404($_SERVER['REQUEST_URI']);
            }
        }
        catch (exception $e)
        {
            $error = 'Unexpected error';
            switch ($e->getCode())
            {
                case 0:
                    $error = 'Unspecified error.';
                    break;
                case 1:
                    $error = 'Hybriauth configuration error.';
                    break;
                case 2:
                    $error = 'Provider not properly configured.';
                    break;
                case 3:
                    $error = 'Unknown or disabled provider.';
                    break;
                case 4:
                    $error = 'Missing provider application credentials.';
                    break;
                case 5:
                    log_message('debug',
                        'controllers.HAuth.login: Authentification failed. The user has canceled the authentication or the provider refused the connection.');
                    //redirect();
                    if (isset($service))
                    {
                        log_message('debug', 'controllers.HAuth.login: logging out from service.');
                        $service->logout();
                    }
                    show_error('User has cancelled the authentication or the provider refused the connection.');
                    break;
                case 6:
                    $error = 'User profile request failed. Most likely the user is not connected to the provider and he should to authenticate again.';
                    break;
                case 7:
                    $error = 'User not connected to the provider.';
                    break;
            }
            if (isset($service))
            {
                $service->logout();
            }
            log_message('error', 'controllers.HAuth.login: ' . $error);
            show_error('Error authenticating user.' . $e->getMessage());
        }

    }
    public function endpoint()
    {
        log_message('debug', 'controllers.HAuth.endpoint called.');
        log_message('info', 'controllers.HAuth.endpoint: $_REQUEST: ' . print_r($_REQUEST, true));
        if ($_SERVER['REQUEST_METHOD'] === 'GET')
        {
            log_message('debug',
                'controllers.HAuth.endpoint: the request method is GET, copying REQUEST array into GET array.');
            $_GET = $_REQUEST;
        }
        log_message('debug',
            'controllers.HAuth.endpoint: loading the original HybridAuth endpoint script.');
        require_once APPPATH . '/third_party/hybridauth/index.php';
    }
	
		public function setUserSessionValues($user_info)
		{
			$session_data = array();
			foreach($user_info as $key=>$value)
			{
				if($key == 'password' || $key=='is_active'){}
				else
				$session_data[$key] = $value;
			}
			$this->session->set_userdata($session_data);
		}


}
/* End of file hauth.php */
/* Location: ./application/controllers/hauth.php */
