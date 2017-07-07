<?php if(! defined( 'BASEPATH' ) ) exit ('No direct script access allowed');
class ProductCart extends BNM_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('Product_cart_model');
        $this->load->model('shared/Comman_model');
        $this->output->set_content_type('application/json');
	}
	public function Products()
	{
		$data = $this->data;
		$json = array();
        $post = $this->input->post();
        //print_r($post);exit;
        
        if(!empty($post['product_id']))
        {
            $product_id = explode(',',$post['product_id']); 
            for($i = 0; $i < count($product_id); $i++)
            {   
            	$bulk_qty = 1;	
                $product         = $this->Product_cart_model->ProductsCartData($product_id[$i]);
                //================== For Bulk User=====================================					
				if(!empty($post['bulk_qty']) && $post['bulk_qty'] > 1){
					$bulk_qty = $post['bulk_qty'];					
				}
		        //================== For Bulk User=====================================
        		$cartdata 	   = array(
                    'id'        => $product->product_id,
                    'user_id'   => $post['user_id'],
                    'qty'       => $bulk_qty,
                    'name'      => (strlen($product->item_name)> 10)?  substr(str_replace('_',' ',ucwords($product->item_name)),0,10).'...':str_replace('_',' ',ucwords($product->item_name)),
        		);
        		if(!empty($cartdata) && !empty($post['user_id']))
        		{
        			if(!empty($post['wishlist_id']))
        			{
        				$status = $this->Product_cart_model->remove_app_wishlist_data($post['wishlist_id']);
        				if($status)
                            $responce['message'] = 'Moved Successfully!';
                        else
                            $responce['message'] = 'Try Again!';
        			}
        			$status = $this->Product_cart_model->Save_Cart_Data($cartdata);
        		}	
            }
        }
        $cart_info = $this->Comman_model->get_cart_data($post['user_id']);
        //print_r($cart_info);exit;
        $cart_list = create_cart_product_listing($cart_info);
		if(!empty($cart_list))
		{
	        $responce['status'] = 1;
            $responce['cart_total']     = round($cart_list['cart_totals']['cart_total']);
            $responce['total_set']      = $cart_list['cart_totals']['total_set'];
            $responce['total_pieces']   = $cart_list['cart_totals']['total_pieces'];   
            $responce['total_vat']      = $cart_list['cart_totals']['total_vat'];
            $responce['sub_total']      = $cart_list['cart_totals']['sub_total'];
            $responce['shipping_total'] = $cart_list['cart_totals']['shipping_total'];
            $responce['voucher_total'] = $cart_list['cart_totals']['voucher_total'];
			$responce 	 	            = array_merge($responce,$cart_list);
			echo json_encode($responce);
		}
        else
		{	
			$responce['status'] = 0;
            $responce['no_cart_img'] = 'assets/images/app_images/empty_bag.png';
			echo json_encode($responce);
		}	
	}

	public function removeCartData()
	{
		$data   = $this->data;
		$json   = array();
		$post = $this->input->post();
		$info   = array('user_id' => $post['user_id'],'product_id' => $post['product_id']);
		if(!empty($post))
		{	
			$status = $this->Product_cart_model->remove_cart_data($info,$post['product_id']);			
			if($status)
			{	
				$responce['message'] = "Deleted Successfully.";
				$responce['status'] = 1;
			}
			else{
				$responce['message']  = "Try Again.";
				$responce['status'] = 0;
			}
			echo json_encode($responce);
		}
	}

	public function getProductTotalVat()
	{
		$json = array();
		$post = $this->input->post();

		if(!empty($post))
		{
			$vat_amt = $this->get_tax_total($post['vat_per'],$post['amount']);
			if(!empty($vat_amt))
			{
				$responce['status'] = 1;
				$responce['total_tax'] = $vat_amt;
			}
			else
			{
				$responce['status'] = 0;
				$responce['message'] = 'Oops! Something went wrong!';
			}
			echo json_encode($responce);
		}
	}

	public function cart_quantity_update()
	{	
		$data = $this->data;
		$responce		= array();
		$product_id		= $this->input->post('product_id');	
		$item_quantity	= $this->input->post('quantity');
		$user_id	    = $this->input->post('user_id');
		$total_stock 	= $this->Product_cart_model->getProductStockQuantity($product_id);
		$sold_quantity 	= $this->Product_cart_model->getProductSoldQty($product_id);
		if(isset($sold_quantity->tot_sold))
		{
			$sold_qty = $sold_quantity->tot_sold;
		}
		else
		{
			$sold_qty = 0;
		}
		$current_stock			= $total_stock - $sold_qty;
		if($item_quantity < 1)
		{
			$responce['status'] = 0;
			$responce['message'] = 'Please Select Valid Quantity';
		}
		else if($item_quantity > $current_stock)
		{
			$responce['status'] = 2;
			$responce['message'] = 'Sorry! We have limited quantity!';
		}
		else if ($item_quantity >= 1 && $item_quantity <= $current_stock)
		{
			$status	= $this->Product_cart_model->updateCartQuantity($product_id,$item_quantity,$user_id);
			$cart_info = $this->Comman_model->get_cart_data($user_id);
            $cart_list = create_cart_product_listing($cart_info);
			if(!empty($cart_list))
    		{
                $responce['message']        = 'Successfully Updated';
    	        $responce['status']         = 1;
                $responce['cart_total']     = round($cart_list['cart_totals']['cart_total']);
                $responce['total_set']      = $cart_list['cart_totals']['total_set'];
                $responce['total_pieces']   = $cart_list['cart_totals']['total_pieces'];
                $responce['total_vat']      = $cart_list['cart_totals']['total_vat'];
                $responce['sub_total']      = $cart_list['cart_totals']['sub_total'];
                $responce['shipping_total'] = $cart_list['cart_totals']['shipping_total']; 
    			$responce 	 	            = array_merge($responce,$cart_list);    			
    		}
            else
    		{	
    			$responce['status'] = 0;
    		}			
		}
		echo json_encode($responce);
	}
	public function check_stock()
	{	
		$product_id = $this->input->post('product_id');
		$product_url = $this->input->post('product_url');
		if(!empty($product_id) && !empty($product_url))
		{
			$stock_array = array();
			$pid  = explode(',', $product_id);
			$purl = explode(',', $product_url);
			foreach ($pid as $key => $value) {
				$pro_id = $value;
				$stock_array[$pro_id] = get_product_stocks(array('product_id'=>$pro_id,'product_url'=>$purl[$key]));
			}		
			$responce['status'] = 1;
			$responce['product_stock'] = $stock_array;
		}
		else
		{	
			$responce['status'] = 0;
		}		
		echo json_encode($responce);
	}
    public function reedeem_voucher()
    {
        $json   = array();
        $post = $this->input->post();
        $status = $this->Product_cart_model->validate_voucher_code($post['voucher_code'],$post['user_id']);
        
        if($status > 0) {
            $json['status'] = 1;
            $json['voucher_value'] = $status;            
            $json['message'] = 'Voucher Successfully Reedeem.';    
        } elseif($status == 'nothing'){
            $json['status'] = 0;
            $json['message'] = 'Sorry! No Voucher Found.';
        }else{
            $json['status'] = 0;
            $json['message'] = 'Sorry! Voucher Has Been Expired.';
        }
        echo json_encode($json);
    }
}