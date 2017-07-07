<?php defined('BASEPATH') or exit('No direct script access allowed');
class Welcome extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        //$this->load->library('facebook');
        $this->load->model('Common_model');
        $this->load->helper('cookie');
        $this->load->library('session');
        $this->load->library('user_agent');
        //$this->input->set_cookie('url', $url,0);
        //$this->session->set_userdata('url',$url);
    }
    public function index()
    {
         $user_data = get_facebook_url();
         
         $array              = array_values($user_data);
         $first_name         = $array[0]['first_name'];
         $last_name          = $array[0]['last_name'];
         $email              = $array[0]['email'];
         $gender             = $array[0]['gender'];
        


         if($email=='')
         { 
            redirect('social-login/issue');
         }
         else
         {
            $status = $this->Common_model->isUserExists(trim($email)); //echo $status;exit;
            
            
            if ($status == 0) // if user is not registered with us then registering user
            {
                $user_id = $this->Common_model->registerNewUserFromSocialAccount($first_name,
                    $last_name, $email, $phone = '', ucfirst($gender));
                $user_info = $this->Common_model->getUserInfo($user_id);
                // setting value in session
                $this->setUserSessionValues($user_info);
                //$this->session->set_userdata('url', $url);
                //add cart info
                get_cart_info($user_info->user_id);
                redirect('upgrade/form');
            } else {
                $user_info = $this->Common_model->getUserInfo($email);
               
                if (!empty($user_info) && $user_info->status==1) {
                    get_cart_info($user_info->user_id);
                    $this->setUserSessionValues($user_info);
                    if ($user_info->password != '') {
                        $url = 'home';
                        redirect($url);
                    } else {
                        redirect('upgrade/form');
                    }
                }

                elseif(!empty($user_info) && $user_info->status==2 || $user_info->status==3 )
                {
                    $this->session->set_userdata('user_social_issue_message','Currently you are not eligible  to access you bulknmore account please contact bulknmore support team');
                	redirect('social-login/issue');
                } 
                else {
                    redirect('home');
                }
            }
         }
         
        
        
    }
    /*public function flogin()
    { echo "Rohit";exit;
        $user = "";
        $userId = $this->facebook->getUser(); echo $userId;
        if ($userId) {
            try {
                $user = $this->facebook->api('/me?fields=id,first_name,last_name,email,gender,locale,picture');
            }
            catch (FacebookApiException $e) {
                $user = "";
            }
        } else {
            echo 'Please try again.';
            exit;
        } //echo "<pre>";print_r($user['email']);exit;
        //if($user!="") :
        if (trim($user['email']) != '') {
            $status = $this->Common_model->isUserExists(trim($user['email'])); //echo $status;exit;
            if ($status == 0) // if user is not registered with us then registering user
                {
                $user_id = $this->Common_model->registerNewUserFromSocialAccount($user['first_name'],
                    $user['last_name'], $user['email'], $user['phone'] = '', ucfirst($user['gender']));
                $user_info = $this->Common_model->getUserInfo($user_id);
                // setting value in session
                $this->setUserSessionValues($user_info);
                $this->session->set_userdata('url', $url);
                //add cart info
                get_cart_info($user_info->user_id);
                redirect('upgrade/form');
            } else {
                $user_info = $this->Common_model->getUserInfo($user['email']);
                if (!empty($user_info)) {
                    get_cart_info($user_info->user_id);
                    $this->setUserSessionValues($user_info);
                    if ($user_info->password != '') {
                        $url = $this->session->userdata('social_redirect');
                        redirect($url);
                    } else {
                        redirect('upgrade/form');
                    }
                } else {
                    redirect('home');
                }
            }
        }
    }*/
    public function setUserSessionValues($user_info)
    {
        $session_data = array();
        foreach ($user_info as $key => $value) {
            if ($key == 'password' || $key == 'is_active') {
            } else
                $session_data[$key] = $value;
        }
        $this->session->set_userdata($session_data);
    }
}
