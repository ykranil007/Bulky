<?php if(!defined('BASEPATH')) exit ('No direct script access allowed');
class Social_login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Common_model');
        $this->load->model('shared/Comman_model');
        $this->load->model('LoginApi/login_model');
    }
    //-------- Function to send record which shows according to sub to sub category
    public function socialLogin()
    {
        if(!$this->input->post())
        {
            $responce['status'] = 0;
            $responce['message'] = "Wrong Request.";
        }
        else
        {
            $post = $this->input->post();
            if(trim($post['email'])!='')
            {
                $status = $this->Common_model->isUserExists(trim($post['email'])); //echo $status;exit;
                if($status == 0) // if user is not registered with us then registering user
                {
                    $user_id = $this->Common_model->registerNewUserFromSocialAccount($post['f_name'],$post['last_name']='',$post['email'],$post['phone']='',ucfirst($post['gender']));
                    $user_info              = $this->Common_model->getUserInfo($user_id);
                    $responce['message']    = 1;
                    $responce['f_name']     = $post['f_name'];
                    $responce['email']      = $post['email'];
                    $responce['gender']     = $post['gender'];
                    $responce['is_social']  = $user_info->is_social;
                    if($user_info->mobile!='')
                    {
                        $responce['mobile']     = $user_info->mobile;
                    }
                    else
                    {
                        $responce['mobile']     = '';
                    }
                    $responce['user_id']     = $user_info->user_id;
                }
                else
                {
                        $user_info = $this->Common_model->getUserInfo($post['email']);
                        $userwallet = $this->login_model->getUserWallet($user_info->user_id);
                        $cart_info = $this->Comman_model->get_cart_data($user_info->user_id);
                        $cart_list = create_cart_product_listing($cart_info);
                        if(!empty($user_info))
                        {
                            if($user_info->password!='' && $user_info->is_active == 'Y' && $user_info->status ==1)
                            {

                                $responce['message']            = 2;
                                $responce['f_name']             = $user_info->first_name;
                                $responce['email']              = $user_info->email;
                                $responce['gender']             = $user_info->gender;
                                $responce['is_social']          = $user_info->is_social;
                                $responce['user_secret_key']    = $user_info->user_secret_key;
                                $responce['user_wallet']        = $userwallet;
                                $responce['cart_count']  = count($cart_list['cart_list']);
                                if($user_info->mobile!='')
                                {
                                    $responce['mobile']     = $user_info->mobile;
                                }
                                else
                                {
                                    $responce['mobile']     = '';
                                }
                            }
                            else
                            {
                                $responce['message']    = 1;
                                $responce['f_name']     = $post['f_name'];
                                $responce['email']      = $post['email'];
                                $responce['gender']     = $post['gender'];
                                $responce['is_social']  = $user_info->is_social;
                                $responce['user_secret_key']    = $user_info->user_secret_key;
                                $responce['cart_count']  = count($cart_list['cart_list']);
                                if($user_info->mobile!='')
                                {
                                    $responce['mobile']     = $user_info->mobile;
                                }
                                else
                                {
                                    $responce['mobile']     = '';
                                }
                            }
                            $responce['user_id']    = $user_info->user_id;
                        }
                        else
                        {
                            $responce['message']                = 3;
                            $responce['deactivate_message']     = "Your acoount hass been deactivate Please Contact BulknMore Technical Team";
                        }
                }
            }
            else
            {
                $responce['message'] = 'Email Must be require.';
                $responce['status'] = 0;
            }
        }// main else ends here
        echo json_encode($responce);
    }
}
?>