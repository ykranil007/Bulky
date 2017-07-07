<?php if (!defined('BASEPATH'))

    exit('No direct script access allowed');



class Order_cancel extends BNM_Controller 

{   

    public function __construct()

    {

        parent::__construct();   

        $this->load->model('Cancel_order_model');

        $this->load->library('Order_cancellation');

    }



    public function Cancel_single_product()
    {
    	$data      = $this->data;
    	$json      = array();
    	$formdata  = $this->input->post();
    	$user_id   = $this->session->userdata('user_id');
    	$status    = $this->Cancel_order_model->is_product_exist($formdata,'',$user_id);
        $mobile    = $this->Cancel_order_model->get_user_mobile($user_id);
        $seller_mobile = $this->Cancel_order_model->get_seller_user_mobile($formdata);
        $msg = "Cancelled! Sorry, Your Order With Order ID - BNMM".str_pad(make_decrypt($formdata['master_order_id']),10,0,STR_PAD_LEFT)." has been cancelled. Please Check Your mail for more details.";
        $seller_msg = "Cancelled! Sorry, Your Order With Order ID - BNMM".str_pad(make_decrypt($formdata['master_order_id']),10,0,STR_PAD_LEFT)." has been cancel by buyer. Please Check Your mail for more details.";
        $is_send = 1;//sending_otp($mobile,$msg);
        $is_sel_send = 1;//sending_otp($seller_mobile,$seller_msg);
    	if($status != 0)
    	{
    		$json['cancel_success'] = 'Successfully Cancel';
    	}
    	else
    	{
    		$json['cancel_failed'] = 'Cancellation failed';
    	}
    	echo json_encode($json);
    }    

    public function get_order_details()
    {
        $data = $this->data;
        $json = array();
        $formdata = $this->input->post();
        $user_id  = $this->session->userdata('user_id');
        $list = $this->Cancel_order_model->get_order_details($formdata,$user_id);    

        $order_data = $this->Cancel_order_model->check_order_exist(make_decrypt($formdata['order_id']),$user_id);        

        $reason_list = $this->Cancel_order_model->getCancellationReasonDropdown();

        $return_reason_list = $this->Cancel_order_model->getReturnReasonDropdown();

        if(!empty($list) && !empty($order_data))

        {

            $html = '';

            foreach ($list as $key => $value){}                

            if($value->order_status == 2 || $value->order_status == 1)

            {

                $html .=    '<h3>Request Cancellation</h3>

                                    <div class="table-responsive">
									<table>

                                    <thead>

                                    <tr>

                                        <th>BNMO'.str_pad(make_decrypt($formdata['order_id']),10,0,STR_PAD_LEFT).'</th>

                                        <th>Item Details</th>

                                        <th>Select</th>

                                        <th>Set</th>

                                        <th>Qty</th>

                                        <th>Sub total</th>

                                    </tr>

                            </thead>';

                foreach ($list as $key => $value) 

                {

                    

                    $html .= '<tbody>
                                    <tr>
                                        <td><img src="'.$data["image_path"]["product_image"].'seller_listing/'.$value->image_name.'" alt=""></td>
                                        <td>
                                            <a href="javascript:void(0)">'.ucfirst(str_replace("_"," ",$value->item_name)).'</a>
                                            <p>Set Discription: '.ucfirst($value->set_description).'</p>';
                                            if(!empty($value->size_name)) {
                    $html .=                '<p>Size: '.ucfirst($value->size_name).'</p>';
                                            }
                    $html .=           '</td>
                                        <td><input type="checkbox" name="product_checkbox" value="'.make_encrypt($value->item_id).'"></td>
                                        <td>'.$value->quantity.'</td>
                                        <td>'.($value->quantity * $value->piece_per_set).'</td>
                                        <td>&#x20B9 '.($value->quantity * $value->piece_per_set) * $value->price.'</td>
                                    </tr>
                                </tbody>';                

                }

                    $html .=   '</table></div>

                                    <div class="reason_form">

                                            <form class="form-horizontal">

                                                <input type="hidden" name="item_order_id" value="'.make_encrypt($order_data->order_id).'">

                                                <div class="form-group">

                                                    <label>Reason for cancellation <span>*</span></label>

                                                    <div class="col-value">

                                                        <select class="reason_dropdown" name="cancel_reason">

                                                            <option  value="">Select Reason</option>';

                                                            foreach($reason_list as $reason) {

                        $html .=                        '<option value="'.$reason->reason_id.'"> '.$reason->reason_comment.'</option>';

                                                            }

                        $html .=                       '</select>

                                                    </div>

                                                </div>

                                                <div class="form-group">

                                                    <label>Comments</label>

                                                    <div class="col-value">

                                                        <textarea name="cancel_comment"></textarea>

                                                    </div>

                                                </div>

                                                <p>Note: There will be no refund as this order is placed as Cash-On-Delivery </p>

                                                <div class="btn_group cancellation_btn">

                                                    <a href="javascript:parent.jQuery.fancybox.close();" class="btn close_btn">Close</a>

                                                    <a href="javascript:;" name="confirm_cancellation" class="btn confirm_cancellation">Confirm Cancellation</a>

                                                </div>

                                            </form>

                                    </div>';

            }

            else if($value->order_status == 6)

            {

               $html .=    '<h3>Request Return</h3>

                            <table>

                                <thead>

                                    <tr>

                                        <th>BNMO'.str_pad(make_decrypt($formdata['order_id']),10,0,STR_PAD_LEFT).'</th>

                                        <th>Item Details</th>

                                        <th>Select</th>

                                        <th>Set</th>

                                        <th>Qty</th>

                                        <th>Sub Total</th>

                                    </tr>

                                </thead>';

                foreach ($list as $key => $value) 

                {

                    

                    $html .=    '<tbody>

                                        <tr>

                                            <td><img src="'.$data["image_path"]["product_image"].'seller_listing/'.$value->image_name.'" alt=""></td>

                                            <td>

                                                <a href="javascript:void(0)">'.ucfirst(str_replace("_"," ",$value->item_name)).'</a>

                                                <p>Set Discription: '.ucfirst($value->set_description).'</p>

                                            </td>

                                            <td><input type="checkbox" name="product_checkbox" value="'.make_encrypt($value->item_id).'"></td>

                                            <td>'.$value->quantity.'</td>

                                            <td>'.($value->quantity * $value->piece_per_set).'</td>

                                            <td>&#x20B9 '.($value->quantity * $value->piece_per_set) * $value->price.'</td>

                                        </tr>

                                    </tbody>';                

                }

                $html .=    '</table>

                            <div class="reason_form">

                                    <form class="form-horizontal">                                        

                                        <input type="hidden" name="item_order_id" value="'.make_encrypt($order_data->order_id).'">

                                        <div class="form-group">

                                            <label>Reason for return <span>*</span></label>

                                            <div class="col-value">

                                                <select class="reason_dropdown" name="return_reason">

                                                    <option  value="">Select Reason</option>';

                                                    foreach($return_reason_list as $reason) {

                $html .=                        '<option value="'.$reason->reason_id.'"> '.$reason->reason_comment.'</option>';

                                                    }

                $html .=                       '</select>

                                            </div>

                                        </div>

                                        <div class="form-group">

                                            <label>Comments</label>

                                            <div class="col-value">

                                                <textarea name="return_comment"></textarea>

                                            </div>

                                        </div>

                                        <p>Note: There will be no refund as this order is placed as Cash-On-Delivery </p>

                                        <div class="btn_group cancellation_btn">

                                            <a href="javascript:parent.jQuery.fancybox.close();" class="btn close_btn">Close</a>

                                            <a href="javascript:;" name="confirm_return" class="btn confirm_return">Confirm Return</a>

                                        </div>

                                    </form>

                            </div>';

            }

            $json['html'] = $html;

        }

        else

        {

            $json['failed'] = 'Something! went wrong';

        }

        echo json_encode($json);

    }



    public function return_product()
    {
        $data = $this->data;
        $json = array();
        $formdata = $this->input->post();
        $master_order_id = make_decrypt($formdata['order_id']);
        $user_id = $this->session->userdata('user_id');
        $item_id_arr = array();
        foreach ($formdata['item_id'] as $key => $value) {
            try {
                $pro_ids = make_decrypt($value);
                if($pro_ids != '' && $pro_ids > 0)
                    $item_id_arr[] = $pro_ids;
            } catch(Exception $e) {}            
        }

        $result = true;//$this->order_cancellation->order_return_proccess($user_id, $formdata['order_id'], $product_id);

        if($result)
        {
            $this->db->trans_start();// Starting transaction
            $r_status = $this->Cancel_order_model->insert_returns_order($user_id,"BNMM".str_pad($master_order_id,10,0,STR_PAD_LEFT),$master_order_id,$formdata['reason'],$formdata['comment']);
            $o_status = $this->Cancel_order_model->update_order_details($master_order_id,$user_id,$item_id_arr);
            $this->db->trans_complete();// transaction ends here
            if(!$this->db->trans_status()===FALSE)
                $json['return_success'] = 'Success';
        }
        echo json_encode($json);        
    }
}

