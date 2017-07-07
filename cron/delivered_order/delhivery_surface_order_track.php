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
class delhivery_surface_order_track
{
	private $db        = null; //database connection variable
    private $http_call = null; //database connection variable
    private $common    = null;
    private $url       = 'https://track.delhivery.com/api/packages/json/?'; 
    private $token     = '73f25dedf9a2e5aeef946ecd74a44c8c25773b2d';
    
	/**
	 * The constructor
	 **/ 
	public function __construct()
	{
	    include ('/include.php');	
    	$this->http_call = new HttpCalls();
    	$this->db        = new Database();
    	$this->common    = new Common($this->db); 
	}
    public function index()
    {   
        $ref_nos = $this->db->get_table_row("IFNULL(GROUP_CONCAT(master_unique_id SEPARATOR ','),'0') AS orders",'tblblk_master_orders','order_delivery_type = 2 AND order_status IN(4,5)');
        //echo $ref_nos['orders'];exit; 
        if($ref_nos['orders']!='0')
        {
            $getawbarray = array(
                'token'   => $this->token,
                'format'  => 'json', 
            );
            $this->url .= http_build_query($getawbarray).'&ref_nos='.$ref_nos['orders']; 
            $result = $this->http_call->order_track($this->url, 'GET');
            //print_r($result);exit;
            $this->delhivery_order_tracking($result);
        }
    }
    
    private function delhivery_order_tracking($records)
    {
        if(!empty($records))
        { 
            foreach($records->ShipmentData as $record)
            {
                $reference_no = $record->Shipment->ReferenceNo; 
                $shipment     = $record->Shipment;
                //print_r($record);exit;
                
                $order_info   = $this->db->get_table_row("master_order_id, master_unique_id,order_status",'tblblk_master_orders',"`master_unique_id` = '$reference_no'");
                $master_order_id = $order_info['master_order_id']; 
                
                if($order_info['master_unique_id'] == $reference_no)
                {
                    if($shipment->Status->Status == 'In Transit' && $shipment->Status->StatusType == 'UD' )
                    {
                        $this->db->update_table_row(array('order_status'=>5),'tblblk_master_orders',"`master_unique_id` = '$reference_no'");
                        $arr = array(
                            'master_order_id'=>$master_order_id,
                            'order_status'=>5,
                            'status_date'=>date('Y-m-d H:i:s',strtotime($shipment->Status->StatusDateTime))
                        );
                        $this->db->update_on_duplicate_key($arr,'tblblk_buyer_order_tracking');
                    }
                    else if($shipment->Status->Status == 'Pending' && $shipment->Status->StatusType == 'UD')
                    {
                        //$this->db->update_table_row(array('order_status'=>5),'tblblk_master_orders',"`master_unique_id` = '$reference_no'");
                        $arr = array(
                            'master_order_id'=>$master_order_id,
                            'order_status'=>8,
                            'status_date'=>date('Y-m-d H:i:s',strtotime($shipment->Status->StatusDateTime))
                        );
                        $this->db->update_on_duplicate_key($arr,'tblblk_buyer_order_tracking');
                    }
                    else if($shipment->Status->Status == 'Dispatched' && $shipment->Status->StatusType == 'UD')
                    {
                        //$this->db->update_table_row(array('order_status'=>5),'tblblk_master_orders',"`master_unique_id` = '$reference_no'");
                        $arr = array(
                            'master_order_id'=>$master_order_id,
                            'order_status'=>9,
                            'status_date'=>date('Y-m-d H:i:s',strtotime($shipment->Status->StatusDateTime))
                        );
                        $this->db->update_on_duplicate_key($arr,'tblblk_buyer_order_tracking');
                    }
                    else if($shipment->Status->Status == 'Delivered' && $shipment->Status->StatusType == 'DL')//For Order delivered 
                    {
                        /*$weight = $this->common->get_return_order_weight($order_info['master_order_id'], '1');
                        $zone   = $this->common->create_order_zone_by_order_id($order_info['master_order_id']);
                        $shipping_charge = $this->common->calculated_shipping_charges_for_delhivery($weight, $zone);
                        $this->db->update_table_row(array('shipping_charge'=>$shipping_charge),'tblblk_order_charges',"`master_order_id` = '".$order_info['master_order_id']."'");*/
                        
                        $this->db->update_table_row(array('order_status'=>6),'tblblk_master_orders',"`master_unique_id` = '$reference_no' AND master_order_id = $master_order_id");
                        $arr = array(
                            'order_id'=>$master_order_id,
                            'order_status'=>6,
                            'status_date'=>date('Y-m-d H:i:s',strtotime($shipment->Status->StatusDateTime))
                        );
                        $this->db->update_on_duplicate_key($arr,'tblblk_buyer_order_tracking');
                    }
                    else if($shipment->Status->Status == 'RT' && $shipment->Status->StatusType == 'In Transit')
                    {
                        $this->db->update_table_row(array('order_status'=>10),'tblblk_master_orders',"`master_order_id` = '".$order_info['master_order_id']."'");//update order status
                        $this->db->update_table_row(array('order_item_status'=>3),'tblblk_order_items',"`master_order_id` = '".$order_info['master_order_id']."'");//update order item status   
                        //$this->comman->tracking_rto_order_status_for_delhivery($shipment, $order_info);
                    }
                }
            }
        }
    }
}
    $order_track = new delhivery_surface_order_track();
    $order_track->index();
     
?>