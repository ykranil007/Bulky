<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Product_details extends BNM_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Product_details_model');        
    }
    public function index()
    {
        if(!$this->input->post())
		{
			$responce['status'] = 0;
			$responce['message'] = "Wrong Request.";
			echo json_encode($responce);
		}
        $data                  = $this->data;
        $json                  = array();
        $post                  = $this->input->post();
        //print_r($post);exit;
        $json['category']      = $post['category'];
        $json['sub_category']  = $post['sub_category'];
        $json['item_name']     = $post['item_name'];
        $product_id            = base64_decode($post['product_id']);
        $url                   = $json['category'] . "/" . $json['sub_category'] . "/" . $json['item_name'];
        $products_detail       = $this->Product_details_model->get_products_list($product_id,$json['item_name']);
        $json['product_images']= $this->Product_details_model->get_product_images($product_id);
        /*************************** Calculating Bulk Price *********************************/
        $bulk_price = $this->Product_details_model->get_bulk_prices($product_id);
        $bulk_price_array = array();
        $bulk_qty = 0;
        foreach ($bulk_price as $key => $prices) {
            $obj = new stdClass();
            $obj->bulk_per = number_format((float)((($products_detail->standard_price - $prices->price) / $products_detail->standard_price ) *100), 2, '.', '');
            $obj->bulk_range = $prices->price_range;
            $obj->bulk_price = $prices->price;
            $bulk_price_array[] = $obj;
        }
        /*************************** Calculating Bulk Price *********************************/
        $this->Product_details_model->Product_Buyer_Visitor($post['ip'], $product_id);//--------for recent view
        $stock = get_product_stocks(array('product_id'=>$product_id,'product_url'=>$post['item_name']));
        $subtosub_cat_id = $this->Product_details_model->getSubtoSubCategory($product_id,$json['item_name']);
        $json['products_details'] = $products_detail;
        $json['products_stock']   = $stock;
        $json['products_url']     = $url;
        $json['similar_products'] = Percentage_Calculate($this->Product_details_model->getSimilarProducts($subtosub_cat_id->subtosub_category_id));
        
        if(!empty($bulk_price_array)){
            $json['status'] = 1;
            $json['bulk_price']       = $bulk_price_array;
        }
        else{
            $json['status'] = 2;
        }
        echo json_encode($json);
    }

    public function checkPincode()
    {
        $json = array();
        $data = $this->data;
        //print_r($data['user_info']);exit;
        $post = $this->input->post();
        
        if(empty($post['pincode'])) 
        {
            $json['pincode_error'] = 'Please Enter Pincode.';
        } 
        else 
        {
            $pincode_status = checkPincodeAvailability($post['pincode']);
            if (empty($pincode_status)) 
            {
                $json['pincode_error'] = 'No Delivery Available in Your Area.';
            }
            else 
            {
                if(!empty($post['user_id']))
                {
                    $active_user_info = get_user_info($post['user_id']);
                    if(!empty($active_user_info))
                    {
                        if(!empty($post['delivery_id']) && !empty($post['product_ids']) && !empty($post['qty']))
                        {
                            $ship_charge = 0;
                            //$arr = explode(',',str_replace(']',' ',str_replace('[',' ',$post['product_ids'])));
                            $arr1 = json_decode($post['product_ids'],true);
                            $arr2 = json_decode($post['qty'],true);
                            
                            foreach($arr1 as $key=>$pro_id)
                            {
                                $ship_charge += get_logistic_charges($pro_id,$post['delivery_id'],$arr2[$key])['shipping_charges'];
                                
                            }
                            $json['shipping_charge'] = $ship_charge;                    
                        }                        
                    }
                    else
                    {
                        $json['status'] = 2;
                        $json['message'] = 'Account Deactivated. Contact Admin.';
                    }
                }                    
                $json['pincode_success'] = 'Delivery Available in Your Area.';
            }
        }
        
        
        
        
        
        echo json_encode($json);
    }
    
    public function save_product_enquiry()
    {
        $data   = $this->data;
		$response   = array(); 
		$post   = $this->input->post();
		$status = $this->Product_details_model->save_product_query($post);
		if($status)
		{
			$response['status'] = 1;
            $response['message'] = 'Thanks ! We will get back to you shortaly.';	
		}
		else
		{
            $response['status'] = 0;
			$response['message'] = 'Failed ! Please Try Again.';
		}
		echo json_encode($response);
    }
}
