<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Comman extends CI_Controller 
{	
	public function __construct()
	{
		parent::__construct();
        $this->output->set_content_type('application/json');
		$this->load->model('shared/Comman_model');
        $this->load->model('API/Home_model'); 
	}
    public function get_category()
    {
        //$menus['menus'] = $this->Comman_model->get_category();
        $menus['menus'] = $this->data['menu_bar']['all_menus'];
        echo json_encode($menus);
    }
    public function offer_list()
    {
        $offer_list['offer_list'] = $this->Home_model->get_products('', 18);
        echo json_encode($offer_list);
    }
    
    public function get_subtosub_category()
    {
        $post = $this->input->post();
        //print_r($post);exit;     
        $sub_categorys    = $this->Comman_model->get_sub_category(trim($post['category_id']));
        $page_banner = $this->Comman_model->get_category_page_banners(trim($post['category_id']), '', '', 2, 'top');
        if(!empty($sub_categorys))
        {
            foreach($sub_categorys as $sub_category)//------------For sub Category----------
            {
                $obj_sub_categorys                    = new stdClass();
                $obj_sub_categorys->sub_category_id   = $sub_category->sub_category_id;
                $obj_sub_categorys->sub_category_name = $sub_category->sub_category_name;
                $obj_sub_categorys->sub_category_url  = $sub_category->sub_category_url;
                $subtosub_categorys                   = $this->Comman_model->get_subtosub_category($sub_category->sub_category_id);
                //$obj_category->subtosub_categorys     = count($subtosub_categorys);
                if(!empty($subtosub_categorys))
                {
                    foreach($subtosub_categorys as $subtosub_category)//---------For subtosub Category------
                    {
                        $obj_subtosub_categorys                         = new stdClass();
                        $obj_subtosub_categorys->subtosub_category_id   = $subtosub_category->subtosub_category_id;
                        $obj_subtosub_categorys->subtosub_category_name = $subtosub_category->subtosub_category_name;
                        $obj_subtosub_categorys->subtosub_category_url  = $subtosub_category->subtosub_category_url;         
                        $obj_sub_categorys->subtosub_categorys[]        = $obj_subtosub_categorys;
                    }
                }
                $submenus[]  = $obj_sub_categorys;
            }
            $json['submenus']        = $submenus; 
            $json['category_banner'] = $page_banner; 
        }
        $json['category_middle_banner'] = $this->Comman_model->get_category_page_banners(trim($post['category_id']), '', '', 2, 'middle');
        $json['new_arrivals']           = $this->Home_model->get_new_product_by_category(trim($post['category_id']));       
        echo json_encode($json);
    }
    
    public function save_buyer_feedback()
    {
        $responce = array();
		if(!$this->input->post())
		{
			$responce['success'] = 0;
			$responce['message'] = "Wrong Request.";			
		}
		else
		{ 
			$post  	= $this->input->post();
            $status = $this->Home_model->save_buyer_feedback($post);
            if($status)
            {
                $responce['success'] = 1;
                $responce['message'] = "Thanks For Your Valuable Feedback.";
            }
            else
            {
                $responce['success'] = 0;
                $responce['message'] = "Not Recored! Please Once More Try.";
            }
        }
        echo json_encode($responce);
    }
}