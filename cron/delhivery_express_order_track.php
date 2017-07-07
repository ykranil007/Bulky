<?php
/**
 * Order_track
 * 
 * @package bulk
 * @author gencyolcu
 * @copyright 2016
 * @version $Id$
 * @access public
 */
class Delhivery_express_order_track
{
	private $db        = null; //database connection variable
    private $http_call = null; //database connection variable
    private $common    = null;
    private $url       = 'https://track.delhivery.com/api/packages/json/?'; 
    private $token     = 'd1c3cf8e8fd0107f532f37542f67f2fc184dc05f';
    
	/**
	 * The constructor
	 **/ 
	public function __construct()
	{
	    include ('include.php');	
    	$this->http_call = new HttpCalls();
    	$this->db        = new Database();
    	$this->common    = new Common($this->db); 
	}
    public function index()
    {   
        $ref_nos = $this->db->get_table_row("IFNULL(GROUP_CONCAT(unique_order_id SEPARATOR ','),'0') AS orders",'tblblk_product_orders','order_delivery_type = 1 AND order_status IN(4,5)');
        if($ref_nos['orders']!='0')
        {
            $getawbarray = array(
                'token'   => $this->token,
                'format'  => 'json', 
            );
            $this->url .= http_build_query($getawbarray).'&ref_nos='.$ref_nos['orders']; 
            $result = $this->http_call->order_track($this->url, 'GET');
            //print_r($result);exit;
            $this->create_order_track($result);
        }
    }
    
    private function create_order_track($records)
    {
        if(!empty($records))
        { 
            foreach($records->ShipmentData as $record)
            {
                $reference_no = $record->Shipment->ReferenceNo; 
                $shipment     = $record->Shipment;
                $order_info   = $this->db->get_table_row("unique_order_id,order_status",'tblblk_product_orders',"`unique_order_id` = '$reference_no'");
                
                if($order_info['unique_order_id'] == $reference_no)
                {
                    if($shipment->Status->Status == 'In Transit' && $shipment->Status->StatusType == 'UD' )
                    {
                        $this->db->update_table_row(array('order_status'=>5),'tblblk_product_orders',"`unique_order_id` = '$reference_no'");
                        $arr = array(
                            'order_id'=>$reference_no,
                            'order_status'=>5,
                            'status_date'=>date('Y-m-d H:i:s',strtotime($shipment->Status->StatusDateTime))
                        );
                        $this->db->update_on_duplicate_key($arr,'tblblk_order_tracking_status');
                    }
                    else if($shipment->Status->Status == 'Pending' && $shipment->Status->StatusType == 'UD')
                    {
                        $this->db->update_table_row(array('order_status'=>5),'tblblk_product_orders',"`unique_order_id` = '$reference_no'");
                        $arr = array(
                            'order_id'=>$reference_no,
                            'order_status'=>8,
                            'status_date'=>date('Y-m-d H:i:s',strtotime($shipment->Status->StatusDateTime))
                        );
                        $this->db->update_on_duplicate_key($arr,'tblblk_order_tracking_status');
                    }
                    else if($shipment->Status->Status == 'Dispatched' && $shipment->Status->StatusType == 'UD')
                    {
                        $this->db->update_table_row(array('order_status'=>5),'tblblk_product_orders',"`unique_order_id` = '$reference_no'");
                        $arr = array(
                            'order_id'=>$reference_no,
                            'order_status'=>9,
                            'status_date'=>date('Y-m-d H:i:s',strtotime($shipment->Status->StatusDateTime))
                        );
                        $this->db->update_on_duplicate_key($arr,'tblblk_order_tracking_status');
                    }
                    else if($shipment->Status->Status == 'Delivered' && $shipment->Status->StatusType == 'DL')
                    {
                        $this->db->update_table_row(array('order_status'=>6),'tblblk_product_orders',"`unique_order_id` = '$reference_no'");
                        $arr = array(
                            'order_id'=>$reference_no,
                            'order_status'=>6,
                            'status_date'=>date('Y-m-d H:i:s',strtotime($shipment->Status->StatusDateTime))
                        );
                        $this->db->update_on_duplicate_key($arr,'tblblk_order_tracking_status');
                    }
                }
            }
        }
    }
}
    $order_track = new Delhivery_express_order_track();
    $order_track->index();
     
?>