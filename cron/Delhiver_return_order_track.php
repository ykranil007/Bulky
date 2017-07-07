<?php
/**
 * delhiver_return_order_track
 * 
 * @package bulk
 * @author Mohan Sharma
 * @copyright 2017
 * @version $id$
 * @access public
 */
class delhiver_return_order_track
{
	private $db        = null; //database connection variable
    private $http_call = null; //database connection variable
    private $common    = null;
    private $url       = 'https://track.delhivery.com/api/packages/json/?'; 
    private $token     = 'd1c3cf8e8fd0107f532f37542f67f2fc184dc05f';
    
	/**
	 * the constructor
	 **/ 
	public function __construct()
	{
	    include ('include.php');	
    	$this->http_call = new httpcalls();
    	$this->db        = new database();
    	$this->common    = new common($this->db); 
	}
    public function index()
    {
        $select = "`product_orders`.`order_id`, `product_orders`.`seller_id`, `product_orders`.`unique_order_id`, `product_orders`.`order_date`, `product_orders`.`payment_type`, 
                   `logistics`.`logistic_name`, `logistics`.`logistic_short_name`, `logistics`.`surface_logistic_user_name`, `logistics`.`express_logistic_user_name`, `logistics`.`surface_token`, `logistics`.`express_token`, 
                   `payment_type`.`delhivery_payment_mode`, `invoice`.`waybill`, `invoice`.`invoice_no`, `invoice`.`date_added` AS `invoice_date`";
        $from   = "`tblblk_product_orders` `product_orders`";
        $join   = "LEFT JOIN `tblblk_logistics` `logistics` ON `logistics`.`logistic_id` = `product_orders`.`logistic_id`
                   LEFT JOIN `tblblk_orders_payment_type` `payment_type` ON `payment_type`.`order_status_id` = `product_orders`.`payment_type`
                   LEFT JOIN `tblblk_invoice` `invoice` ON `invoice`.`order_id` = `product_orders`.`order_id`
                   LEFT JOIN `tblblk_order_returns` `order_returns` ON `order_returns`.`order_id` = `product_orders`.`order_id`
                  "; 
        $where  = "`product_orders`.`status` = 10 AND `order_returns`.`status` = 10 GROUP BY `product_orders`.`order_id`";
        $order_info = $this->db->data_table($select, $from, $join, $where);
        if(! empty($order_info))
        {
            foreach($order_info as $order_details)
            {
                $function_name = 'create_treck_return_order_for_'.$order_details->logistic_short_name;
                $result        = $this->$function_name($order_details);   
            }
        }
    }
    
    private function create_treck_return_order_for_delhivery($order_details)
    {
        $logistic_user_name = ($order_details->order_delivery_type == 1)? $order_details->express_logistic_user_name: $order_details->surface_logistic_user_name;
	    $token              = ($order_details->order_delivery_type == 1)? $order_details->express_token : $order_details->surface_token;
        $getawbarray = array(
                               'token'   => $token,
                               'format'  => 'json', 
                            );
        $this->url .= http_build_query($getawbarray).'&ref_nos='.$order_details->unique_order_id; 
        $result     = $this->http_call->order_track($this->url, 'GET');
        $this->create_order_track($result,$order_details);
    }
    
    private function create_order_track($records)
    {
        if(!empty($records))
        { 
            foreach($records->shipmentdata as $record)
            {
                $reference_no = $record->shipment->referenceno; 
                $shipment     = $record->shipment;
                //$order_info = $this->db->get_table_row("unique_order_id,order_status",'tblblk_product_orders',"`unique_order_id` = '$reference_no'");
                
                if($order_details->unique_order_id == $reference_no)
                {
                    if(strtolower($shipment->status->status) == 'picked up' && strtoupper($shipment->status->statustype) == 'PU' )
                    {
                        $this->db->update_table_row(array('return_status'=>3),'tblblk_order_returns',"`order_id` = '".$order_details->order_id."'");
                        $arr = array(
                            'order_id'=>$order_details->order_id,
                            'unique_order_id'=>$reference_no,
                            'order_status'=>3,
                            'status_date'=>date('y-m-d h:i:s',strtotime($shipment->status->statusdatetime))
                        );
                        $this->db->update_on_duplicate_key($arr, 'tblblk_order_return_tracking_status');
                    }
                    else if(strtolower($shipment->status->status) == 'in transit' && strtoupper($shipment->status->statustype) == 'PU')
                    {
                        //$this->db->update_table_row(array('order_status'=>4),'tblblk_product_orders',"`unique_order_id` = '$reference_no'");
                        $arr = array(
                            'order_id'=>$order_details->order_id,
                            'unique_order_id'=>$reference_no,
                            'order_status'=>4,
                            'status_date'=>date('y-m-d h:i:s', strtotime($shipment->status->statusdatetime))
                        );
                        $this->db->update_on_duplicate_key($arr, 'tblblk_order_return_tracking_status');
                    }
                    else if(strtolower($shipment->status->status) == 'pending' && strtoupper($shipment->status->statustype) == 'PU')
                    {
                        //$this->db->update_table_row(array('order_status'=>4),'tblblk_product_orders',"`unique_order_id` = '$reference_no'");
                        $arr = array(
                            'order_id'=>$order_details->order_id,
                            'unique_order_id'=>$reference_no,
                            'order_status'=>5,
                            'status_date'=>date('y-m-d h:i:s',strtotime($shipment->status->statusdatetime))
                        );
                        $this->db->update_on_duplicate_key($arr,'tblblk_order_return_tracking_status');
                    }
                    else if(strtolower($shipment->status->status) == 'dispatched' && strtoupper($shipment->status->statustype) == 'PU')
                    {
                        //$this->db->update_table_row(array('order_status'=>5),'tblblk_product_orders',"`unique_order_id` = '$reference_no'");
                        $arr = array(
                            'order_id'=>$order_details->order_id,
                            'unique_order_id'=>$reference_no,
                            'order_status'=>6,
                            'status_date'=>date('y-m-d h:i:s',strtotime($shipment->status->statusdatetime))
                        );
                        $this->db->update_on_duplicate_key($arr, 'tblblk_order_return_tracking_status');
                    }
                    else if(strtolower($shipment->status->status) == 'dto' && strtoupper($shipment->status->statustype) == 'DL')
                    {
                        $this->db->update_table_row(array('return_status'=>7),'tblblk_order_returns',"`order_id` = '".$order_details->order_id."'");
                        $arr = array(
                            'order_id'=>$order_details->order_id,
                            'unique_order_id'=>$reference_no,
                            'order_status'=>7,
                            'status_date'=>date('y-m-d h:i:s',strtotime($shipment->status->statusdatetime))
                        );
                        $this->db->update_on_duplicate_key($arr,'tblblk_order_return_tracking_status');
                    }
                }
            }
        }
    }
}
    $order_track = new delhiver_return_order_track();
    $order_track->index(); 
?>