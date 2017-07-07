<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Notification extends CI_Controller 
{   
    private $title;
    private $message;
    private $image;
    private $data;
    // in background when push is recevied
    private $is_background;
    
    public function __construct()
    {
        parent::__construct();
    }
    
    public function index()
    {
        // optional payload
        $payload = array();
        $payload['team'] = 'India';
        $payload['score'] = '5.6';
        // getting parameter for notification from admin panel
        $post = $this->input->post();        
        $title = isset($post['title']) ? $post['title'] : ''; // notification message title
        $message = isset($post['message']) ? $post['message'] : '';   // notification message        
        $push_type = 'individual'; //isset($post['push_type']) ? $post['push_type'] : ''; // push type - single user or multiple user
        $include_image = isset($post['include_image']) ? TRUE : FALSE; // whether to include to image or not        
        
        $this->setTitle($title);
        $this->setMessage($message);
        
        if ($include_image) {
            $this->setImage('http://api.androidhive.info/images/minion.jpg');
        } else {
            $this->setImage('');
        }
        
        $this->setIsBackground(FALSE);
        $this->setPayload($payload);
        
        $json = $response = '';
                
        if ($push_type == 'topic') {
            $json = $this->getPush();
            $response = $this->sendToTopic('global', $json);
        } else if ($push_type == 'individual') {
            $json = $this->getPush();
            $regId = isset($post['regId']) ? $post['regId'] : '';
            //echo $regId;exit;
            $response = $this->send($regId, $json);
        }
    }    
    
    public function setTitle($title) {
        $this->title = $title;
    }
 
    public function setMessage($message) {
        $this->message = $message;
    }
 
    public function setImage($imageUrl) {
        $this->image = $imageUrl;
    } 
 
    public function setIsBackground($is_background) {
        $this->is_background = $is_background;
    }
    
    public function setPayload($data) {
        $this->data = $data;
    }
    
    public function getPush() {
        $res = array();
        $res['data']['title'] = $this->title;
        $res['data']['is_background'] = $this->is_background;
        $res['data']['message'] = $this->message;
        $res['data']['image'] = $this->image;
        $res['data']['payload'] = $this->data;
        $res['data']['timestamp'] = date('Y-m-d H:i:s');
        return $res;
    }
    
    // sending push message to single user by firebase reg id
    public function send($to, $message) {
        $fields = array(
            'to' => $to,
            'data' => $message,
        );
        return $this->sendPushNotification($fields);
    }
 
    // Sending message to a topic by topic name
    public function sendToTopic($to, $message) {
        $fields = array(
            'to' => '/topics/' . $to,
            'data' => $message,
        );
        return $this->sendPushNotification($fields);
    }
 
    // sending push message to multiple users by firebase registration ids
    public function sendMultiple($registration_ids, $message) {
        $fields = array(
            'to' => $registration_ids,
            'data' => $message,
        );
 
        return $this->sendPushNotification($fields);
    }
 
    // function makes curl request to firebase servers
    private function sendPushNotification($fields) {
        
        // Set POST variables
        $url = 'https://fcm.googleapis.com/fcm/send';
 
        $headers = array(
            'Authorization: key=' . BUYER_NOTIFICATION_FIREBASE_API_KEY,
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();
 
        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $url);
 
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
 
        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
 
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
 
        // Execute post
        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        } 
        // Close connection
        curl_close($ch);
        return $result;
    }
}