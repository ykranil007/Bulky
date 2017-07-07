<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Order_cancel extends BNM_Controller 
{   
    public function __construct()
    {
        parent::__construct();   
        $this->load->model('Cancel_order_model');
    }

    public function getCancellationReasonDropdown()
    {
        $list = $this->Cancel_order_model->getCancelReasonDropdown();
        if(!empty($list))
        {
            $json['status'] = 1;
            $json['reason_list'] = $list;
        }
        else
        {
            $json['status'] = 0;
            $json['message'] = 'Something! went wrong.';
        }
        echo json_encode($json);
    }

    public function Cancel_single_product()
    {
    	$data = $this->data;
    	$json = array();
    	$formdata = $this->input->post();
        $formdata['product_id'] = array($formdata['product_id']);      
    	$status = $this->Cancel_order_model->is_product_exist($formdata,$formdata['master_order_id'],$formdata['user_id']);
    	$mobile = $this->Cancel_order_model->get_user_mobile($formdata['user_id']);
        $seller_mobile = $this->Cancel_order_model->get_seller_user_mobile($formdata);
        $msg = "Cancelled! Sorry, Your Order With Order ID - BNMM".str_pad($formdata['master_order_id'],10,0,STR_PAD_LEFT)." has been cancelled. Please Check Your mail for more details.";
        $seller_msg = "Cancelled! Sorry, Your Order With Order ID - BNMM".str_pad($formdata['master_order_id'],10,0,STR_PAD_LEFT)." has been cancel by buyer. Please Check Your mail for more details.";
        $is_send = sending_otp($mobile,$msg);
        $is_sel_send = sending_otp($seller_mobile,$seller_msg);
        if($status != 0)
    	{
            $json['status'] = 1;
    		$json['cancel_success'] = 'Successfully Cancel';
    	}
    	else
    	{
            $json['status'] = 0;
    		$json['cancel_failed'] = 'Cancellation failed';
    	}
    	echo json_encode($json);
    }

    public function return_reason_list()
    {
        $json = array();
        $return_reason_list = $this->Cancel_order_model->getReturnReasonDropdown();
        $json['status'] = 1;
        $json['return_list'] = $return_reason_list;
        echo json_encode($json);
    }

    public function return_from_app_product()
    {
        $data = $this->data;
        $json = array();
        $formdata = $this->input->post();
        $product_id_array = explode(',',$formdata['product_id']);

        $result = true;//$this->order_cancellation->order_return_proccess($formdata['user_id'], $formdata['order_id'], $product_id_array);

        if($result)
        {
            $this->db->trans_start();// Starting transaction
            $r_status = $this->Cancel_order_model->insert_returns_order($formdata['user_id'],"BNMO".str_pad($formdata['master_order_id'],10,0,STR_PAD_LEFT),$formdata['master_order_id'],$formdata['reason'],$formdata['comment']);
            $o_status = $this->Cancel_order_model->update_order_details($formdata['master_order_id'],$formdata['user_id'],$product_id_array);
            $this->db->trans_complete();// transaction ends here
            if(!$this->db->trans_status()===FALSE)
            {
                $json['message'] = 'Successfully Return';
                $json['return_success'] = 1;
            }
        }
        echo json_encode($json);        
    }
}
