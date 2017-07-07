<?php
date_default_timezone_set("Asia/Kolkata");
class HttpCalls
{
    private $apiEndpoint = '';
    
    private $lastError = null;
    /**
     * Whether we are in debug mode. This is set by the constructor
     */
    private $debug = false;
    /**
     * The constructor
     **/
    public function __construct()
    {
        //$this->apiEndpoint = $apiurl;
        //$this->debug       = $debug;
    }
    /**
     * API Colling Functions 
     */
    protected function httpCall($url, $method = 'GET', $post_data = null,$headers = array())
    {
        $ch = curl_init($url);
        if ($method == 'POST') {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($ch, CURLOPT_POST, true);
        }elseif ($method != 'GET') {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        }else{
            curl_setopt($ch, CURLOPT_URL, $url);
        } 
        if(!empty($headers))
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);            
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
        //url_setopt($ch, CURLOPT_TIMEOUT, 60);
        //curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
        $response = curl_exec($ch);
        // Set HTTP error, if any
        $this->lastError = array('code'=>curl_errno($ch),'message' => curl_error($ch),'httpCode'=>curl_getinfo($ch, CURLINFO_HTTP_CODE));
        curl_close ($ch);
        //print_r($response);exit;
        return $response;     
    }
     
	 
	public function order_track($path, $method)
    {
       return json_decode(($this->httpCall($path, $method, null, array())));   
    }
}
?>