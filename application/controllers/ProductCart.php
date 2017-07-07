<?php if(! defined( 'BASEPATH' ) ) exit ('No direct script access allowed');
class ProductCart extends BNM_Controller
{
	public function __construct()
	{
		parent::__construct();		
		//$this->load->model('Home_model');
		$this->load->model('Product_cart_model');
	}		
	public function index()
	{ 
		$data = $this->data;			
		$data['page_settings']  = $this->Comman_model->get_page_setting(37);
        //print_r($data['cart_info']);exit;
		$product_array 			= (empty($data['user_info']))? $this->cart->contents():$data['cart_info']; 
		$cart_info 				= create_cart_product_listing($product_array);
        $data['voucher_total'] = $cart_info['cart_totals']['voucher_total'];
		$data['cart_totals'] = $cart_info['cart_totals'];
		$data['cart']   	 = $cart_info['cart_list'];
		$this->load->view('viw_add_to_cart',$data);
	}
	public function Products()
	{
		$data       = $this->data;		
		$post 		= $this->input->post();
		$pro_id 	= make_decrypt($post['product_id']);
		$product    = $this->Product_cart_model->ProductsCartData($pro_id);		
		      
		$cartdata 		= array(
        'id'      		=> $product->product_id.'_'.make_decrypt($post['size_id']),
        'qty'     		=> 1,
        'price'   		=> $product->selling_price,
        'name'    		=> (strlen($product->item_name)> 10)?  substr(str_replace('_',' ',ucwords($product->item_name)),0,2).'...':str_replace('_',' ',ucwords($product->item_name))
		);
        $this->cart->insert($cartdata);
		if(isset($data['user_info']->user_id))
		{
            get_cart_info($data['user_info']->user_id);
		}
        
		if(!$this->input->post()) { // checking whether called through ajax or not, if not then redirecting
			redirect('cart');
		}
		else{
			echo json_encode(array('status'=>1));	
		}
	}

	public function Delete($rowid)
	{	
		$data = $this->data;			
		$this->cart->update(array('rowid'=> $rowid, 'qty' =>0));		
		redirect('cart');
	}
	public function update()
	{	
		$data = $this->data;
		$json = array();
        $sold_qty = 0;
        $total_stock = 0;
        $rowid = '';
		$qty = $this->input->post('quantity');
        $id_n_rowid = $this->input->post('rowid');
        $ids = explode('_',$id_n_rowid);
        $this->cart->update(array('rowid'=> $rowid, 'qty' =>$qty));
		
		$total_stock 	= $this->Product_cart_model->getProductStockQuantity($ids[1]);
		$sold_quantity 	= $this->Product_cart_model->getProductSoldQty($ids[1]);
		if(isset($sold_quantity->tot_sold))
			$sold_qty = $sold_quantity->tot_sold;			
		
        $current_stock	= $total_stock - $sold_qty;
		if($qty >= 1 && $qty <= $current_stock)
		{
			$this->cart->update(array('rowid' => $ids[0], 'qty' => $qty));			
		}
		else if($qty > $current_stock)
		{
			$json['out_stock'] = 'Sorry! We have limited quantity!';
		}
		echo json_encode($json);
	}
	public function quantity_update()
	{	
		$data = $this->data;
		$json			= array();
        $user_id        = $this->session->userdata('user_id');
		$product_id		= make_decrypt($this->input->post('productid'));	
		$item_quantity	= $this->input->post('quantity');
		$size_id 		= make_decrypt($this->input->post('size_id'));
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
			$json['unvalid_qty'] = 'Please Select Valid Quantity';
		}
		else if($item_quantity > $current_stock)
		{
			$json['out_stock'] = 'Sorry! We have limited quantity!';
		}
		else if ($item_quantity >= 1 && $item_quantity <= $current_stock)
		{
			$status	= $this->Product_cart_model->updateCartQuantity($product_id,$item_quantity,$user_id,$size_id);
			$json['success'] = 'Successfully Updated';
		}
		echo json_encode($json);
	}

	public function Add_Wishlist()
	{
		$data   = $this->data;
		$json   = array();
		$userid = $this->session->userdata('user_id');
        if(empty($userid))
        {
            $json['login_url']  = "https://www.bulknmore.com/#login";
        }
        else
        {
            $product_id = make_decrypt($this->input->post('product_id'));
            $size_id = make_decrypt($this->input->post('size_id'));
    		
    		$info   = array('user_id' => $userid,'product_id' => $product_id,'size_id'=>$size_id);
    		//print_r($info);exit;
    		if(!empty($product_id))
    		{	
    			$status = $this->Product_cart_model->add_Wishlist($info,$product_id);			
    			if($status)
    				$json['success'] = "Added Successfully.";
    			else
    				$json['failed']  = "Try Again.";
    		}
        }
						
		echo json_encode($json);
	}

	public function removeCartData()
	{
		$data   = $this->data;
		$json   = array();
		$userid = $this->session->userdata('user_id');
		$product_id = make_decrypt($this->input->post('product_id'));
		$size_id = make_decrypt($this->input->post('size_id'));

		$info   = array('user_id' => $userid,'product_id' => $product_id,'size_id'=>$size_id);
		if(!empty($product_id))
		{	
			$status = $this->Product_cart_model->remove_cart_data($info,$product_id);			
			if($status)
			{	
				if(count($data['cart_info']) >= 1)
				{
					$json['have_success'] = "Deleted Successfully.";
					$json['cart_success'] = "Deleted Successfully.";
				}
				else
				{
					$json['success'] = "Deleted Successfully.";
				}
			}
			else{
				$json['failed']  = "Try Again.";
			}
		}				
		echo json_encode($json);
	}
    
    public function reedeem_voucher()
    {
        $json   = array();
        $post = $this->input->post();
        $user_id = ($this->session->userdata('user_id')?$this->session->userdata('user_id'):'');
        $status = $this->Product_cart_model->validate_voucher_code($post['voucher_code'],$user_id);
        
        if(!$this->session->userdata('user_id'))
	        $this->session->set_userdata('voucher_code',$post['voucher_code']);
        
        if($status > 0) {
            $json['value'] = $status;
            $json['success'] = 'Successfully Reedeem.';    
        } elseif($status == 'nothing'){
            $json['no_found'] = 'Sorry! No Voucher Found.';
        }else{
            $json['exipre'] = 'Sorry! Voucher has been expired.';
        }
        echo json_encode($json);
    }
}