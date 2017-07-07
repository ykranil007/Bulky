<?php
/**
 * track_return_order_status
 * 
 * @package bulk
 * @author Mohan Sharma
 * @copyright 20-01-2017
 * @version $id$
 * @access public
 */
class track_return_order_status
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
	    //include ('/include.php');
        $base = dirname(dirname(__FILE__));
        include_once($base . '/include.php');
         	
    	$this->http_call = new httpcalls();
    	$this->db        = new database();
    	$this->common    = new common($this->db); 
	}
    public function index()
    {
        $order_info    = $this->get_return_orders();
        
        $logistic_array = array();
        if(!empty($order_info))
        {
            $logistic_info = $this->data_table('logistic_id,logistic_name,logistic_short_name,surface_logistic_user_name,express_logistic_user_name,surface_token,express_token',
                                            'tblblk_logistics','','logistic_status = 1');                                   
            foreach($logistic_info as $logistic)
            {
                $logistic_array[$logistic->logistic_short_name] = array('logistic_info'=>$logistic,'order_info'=>'');
            }//set logistic array
                                        
            foreach($order_info as $order_details)
            {
                $logistic_array[$order_details->logistic_short_name]['order_info'] = $order_details;    
            }//set order according logistic
            
            foreach($logistic_array as $logistic_name => $logistic_order_info)
            {
                $function_name = 'treck_return_order_for_'.$logistic_name;
                $result        = $this->$function_name($logistic_order_info['logistic_info'], $logistic_order_info['order_info']);
            }
        }
    }
    
    private function get_return_orders()
    {
        $select = '`product_orders`.`order_id`, `product_orders`.`seller_id`, `product_orders`.`unique_order_id`, `product_orders`.`order_date`, `product_orders`.`payment_type`, 
                   `logistics`.`logistic_name`, `logistics`.`logistic_short_name`, `logistics`.`surface_logistic_user_name`, `logistics`.`express_logistic_user_name`, `logistics`.`surface_token`, `logistics`.`express_token`, 
                   `payment_type`.`delhivery_payment_mode`, `invoice`.`waybill`, `invoice`.`invoice_no`, `invoice`.`date_added` AS `invoice_date`';
                   
        $from   = "`tblblk_product_orders` `product_orders`";
        
        $join   = "LEFT JOIN `tblblk_logistics` `logistics` ON `logistics`.`logistic_id` = `product_orders`.`logistic_id`
                   LEFT JOIN `tblblk_orders_payment_type` `payment_type` ON `payment_type`.`order_status_id` = `product_orders`.`payment_type`
                   LEFT JOIN `tblblk_invoice` `invoice` ON `invoice`.`order_id` = `product_orders`.`order_id`
                   LEFT JOIN `tblblk_order_returns` `order_returns` ON `order_returns`.`order_id` = `product_orders`.`order_id`
                  "; 
        $where  = "`product_orders`.`status` = 10 AND `order_returns`.return_status != 4 GROUP BY `product_orders`.`order_id`";
        return $this->db->data_table($select, $from, $join, $where);                   
    }
    
    private function treck_return_order_for_delhivery($logistic_info,$orders_info)
    {
        $this->url = 'https://track.delhivery.com/api/packages/json/?';
        $token = $logistic_info->surface_token;
        
        $ref_nos   = array_column($orders_info, 'unique_order_id');
        $order_ids = array_column($orders_info, 'order_id');
        
        $getawbarray = array(
                              'token'   => $token,
                              'format'  => 'json', 
                            );
        $this->url .= http_build_query($getawbarray).'&ref_nos='.implode(",",$ref_nos); 
        $result     = $this->http_call->order_track($this->url, 'GET');
        $this->delhivery_order_tracking($result, array($order_ids, $ref_nos));
    }
    
    private function delhivery_order_tracking($records, $order_details)
    {
        if(!empty($records))
        {            
            $order_ids = $order_details[0];
            $ref_nos   = $order_details[1];
            foreach($records->shipmentdata as $record)
            {
                $reference_no = $record->shipment->referenceno; 
                $order_id     = $order_ids[$ref_nos[array_search($reference_no, $ref_nos)]];
                
                $shipment     = $record->shipment;
                //$order_info   = $this->db->get_table_row("unique_order_id,order_status",'tblblk_product_orders',"`unique_order_id` = '$reference_no'");
                
                if(strtolower($shipment->status->status) == 'pickup' && strtoupper($shipment->status->statustype) == 'PP' && $shipment->OrderType == 'Pickup')
                {
                    $this->db->update_table_row(array('return_status'=>3),'tblblk_order_returns',"`order_id` = '".$order_id."'");//for In-Process Showing only
                    $arr = array(
                        'order_id'=>$order_id,
                        'unique_order_id'=>$reference_no,
                        'order_status'=>3,
                        'status_date'=>date('y-m-d h:i:s',strtotime($shipment->status->statusdatetime))
                    );
                    $this->db->update_on_duplicate_key($arr, 'tblblk_order_return_tracking_status');
                }
                else if(strtolower($shipment->status->status) == 'in transit' && strtoupper($shipment->status->statustype) == 'PP' && $shipment->OrderType == 'Pickup')
                {
                    //$this->db->update_table_row(array('order_status'=>4),'tblblk_product_orders',"`unique_order_id` = '$reference_no'");
                    $arr = array(
                        'order_id'=>$order_id,
                        'unique_order_id'=>$reference_no,
                        'order_status'=>5,
                        'status_date'=>date('y-m-d h:i:s',strtotime($shipment->status->statusdatetime))
                    );
                    $this->db->update_on_duplicate_key($arr,'tblblk_order_return_tracking_status');
                }
                else if(strtolower($shipment->status->status) == 'pending' && strtoupper($shipment->status->statustype) == 'PP' && $shipment->OrderType == 'Pickup')
                {
                    //$this->db->update_table_row(array('order_status'=>4),'tblblk_product_orders',"`unique_order_id` = '$reference_no'");
                    $arr = array(
                        'order_id'=>$order_id,
                        'unique_order_id'=>$reference_no,
                        'order_status'=>6,
                        'status_date'=>date('y-m-d h:i:s', strtotime($shipment->status->statusdatetime))
                    );
                    $this->db->update_on_duplicate_key($arr,'tblblk_order_return_tracking_status');
                }
                else if(strtolower($shipment->status->status) == 'dispatched' && strtoupper($shipment->status->statustype) == 'PP' && $shipment->OrderType == 'Pickup')
                {
                    //$this->db->update_table_row(array('order_status'=>5),'tblblk_product_orders',"`unique_order_id` = '$reference_no'");
                    $arr = array(
                        'order_id'=>$order_id,
                        'unique_order_id'=>$reference_no,
                        'order_status'=>7,
                        'status_date'=>date('y-m-d h:i:s', strtotime($shipment->status->statusdatetime))
                    );
                    $this->db->update_on_duplicate_key($arr,'tblblk_order_return_tracking_status');
                }
                else if(strtolower($shipment->status->status) == 'dto' && strtoupper($shipment->status->statustype) == 'DL' && $shipment->OrderType == 'Pickup')
                {
                    $this->db->update_table_row(array('return_status'=>4),'tblblk_order_returns',"`order_id` = '".$order_id."'");
                    $arr = array(
                        'order_id'=>$order_id,
                        'unique_order_id'=>$reference_no,
                        'order_status'=>4,
                        'status_date'=>date('y-m-d h:i:s',strtotime($shipment->status->statusdatetime))
                    );
                    $this->db->update_on_duplicate_key($arr,'tblblk_order_return_tracking_status');
                }
                //End DTO  (Order return from buyer to seeler proccess)   
                
                //============== start Return procces for RTO Status==========================
                
                else if(strtolower($shipment->status->status) == 'In Transit' && strtoupper($shipment->status->statustype) == 'RT' )
                {
                    //$this->db->update_table_row(array('return_status'=>3),'tblblk_order_returns',"`order_id` = '".$order_id."'");//for In-Process Showing only
                    $arr = array(
                        'order_id'=>$order_id,
                        'unique_order_id'=>$reference_no,
                        'order_status'=>5,
                        'status_date'=>date('y-m-d h:i:s',strtotime($shipment->status->statusdatetime))
                    );
                    $this->db->update_on_duplicate_key($arr, 'tblblk_order_return_tracking_status');
                }
                
                else if(strtolower($shipment->status->status) == 'Pending' && strtoupper($shipment->status->statustype) == 'RT' )
                {
                    $arr = array(
                        'order_id'=>$order_id,
                        'unique_order_id'=>$reference_no,
                        'order_status'=>6,
                        'status_date'=>date('y-m-d h:i:s',strtotime($shipment->status->statusdatetime))
                    );
                    $this->db->update_on_duplicate_key($arr, 'tblblk_order_return_tracking_status');
                }
                
                else if(strtolower($shipment->status->status) == 'Dispatched' && strtoupper($shipment->status->statustype) == 'RT' )
                {
                    $arr = array(
                        'order_id'=>$order_id,
                        'unique_order_id'=>$reference_no,
                        'order_status'=>7,
                        'status_date'=>date('y-m-d h:i:s',strtotime($shipment->status->statusdatetime))
                    );
                    $this->db->update_on_duplicate_key($arr, 'tblblk_order_return_tracking_status');
                }
                else if(strtolower($shipment->status->status) == 'RTO' && strtoupper($shipment->status->statustype) == 'DL' )
                {
                    $this->db->update_table_row(array('return_status'=>4),'tblblk_order_returns',"`order_id` = '".$order_id."'");
                    $arr = array(
                        'order_id'=>$order_id,
                        'unique_order_id'=>$reference_no,
                        'order_status'=>4,
                        'status_date'=>date('y-m-d h:i:s',strtotime($shipment->status->statusdatetime))
                    );
                    $this->db->update_on_duplicate_key($arr, 'tblblk_order_return_tracking_status');
                }
                //============== End Return procces for RTO Status==========================
            }
        }
    }
}
    $order_track = new track_return_order_status();
    $order_track->index(); 
?>