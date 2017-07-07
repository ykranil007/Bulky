<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');
//session_start();

if (!function_exists('create_product_helper')) {
    function create_product_helper($product, $image_url)
    {
        $bulk_available = '';
        if (!empty($product->bulk_available))
            $bulk_available = 'Bulk Available';
        else
            $bulk_available = '';
            

        $html = '';
        $url = $product->category_url . '/' . $product->sub_category_url . '/' . $product->subtosub_category_url . '/' . $product->product_url . '/' . make_encrypt($product->product_id);
        $product_name = (strlen($product->item_name) > 40) ? substr(str_replace('_', ' ',ucwords($product->item_name)), 0, 40) . '...' : str_replace('_', ' ', ucwords($product->item_name));
        $html .= '<li>';
        $html .= '<div class="item_wrap" styleid = "' . $product->color_id . '">';
        $html .= '<a href = "' . $url . '">';
        $html .= '<figure><img class="lazy" alt = "'.ucwords($product->item_name).'" data-original="' . $image_url . 'listing/' . $product->product_image . '"> </figure>';
        $html .= '</a>';

        $html .= '<div class="product_info">';
        $html .= '<span class="btn_group" style_id="' . $product->product_id . '">';
        //$html .= '<span class="btn">Add To Cart</span>';
        $html .= '<span class="btn quick-view">Quick View</span>';
        $html .= '</span>';
        $html .= '<a href="' . $url . '">';

        //$html .= '<h3>'.ucwords($product->brand_name).'</h3>';
        $html .= '<small>' . $product_name . '</small>';
        $html .= '<div class="price">';
        if($product->sub_category_url == 'mens-footwear' || $product->sub_category_url == 'womens-footwear' || $product->sub_category_url == 'kids-footwear')
        {
            $html .= '<span class="standard-price" style="color: green;"> &#x20B9; ' . number_format((int)$product->selling_price) . '/Pair</span>';
        }
        else
        {
            $html .= '<span class="standard-price" style="color: green;"> &#x20B9; ' . number_format((int)$product->selling_price) . '/Piece</span>';
        }
        
        //$html .= '<span class="prev-price"> &#x20B9; ' . number_format((int)$product->standard_price) .' Piece</span>';
        //$html .= '<small style="color: #ff5722;">' . $bulk_available . '</small>';
        //$html .= ($product->offer_percentage > 0) ? '<small style="color: green;">(' . round((int)$product->offer_percentage) . ' % Off)</small>' : '';
        $html .= '</div>';
        $html .= '</a>';
        $html .= '</div>';

        $html .= '<div class="product-hover-state">';
        //$html .= '<p>Easy EMI Options Available</p>';
        $html .= ($product->set_description != '') ? '<p>' . ucwords($product->set_description) . '</p>' : '';

        $html .= '</div>';
        //a tag
        $html .= '</div>';
        $html .= '</li>';
        return $html;
    }
}

if (!function_exists('view_loader')) {
    function view_loader($view, $product_info, $page_data = array())
    {
        $CI = &get_instance();
        $CI->load->model('Product_details_model');

        if (!empty($page_data)){
            $data = array_merge($page_data);
        }

        $data['category'] = $product_info['category'];
        $data['sub_category'] = $product_info['sub_category'];
        $data['item_name'] = $product_info['product_url'];

        $product_id = $product_info['product_id'];
        $color_id = $product_info['color_id'];
        $data['image_path']['product_image'] = $product_info['image_path'];
        $url = $data['category'] . "/" . $data['sub_category'] . "/" . $product_info['subtosub_category'];
        $products_detail = $CI->Product_details_model->get_products_list($product_id, $data['item_name']);
        $data['product_images'] = $CI->Product_details_model->get_product_images($product_id);

        $stock = $CI->Product_details_model->get_product_stock($product_id);
        $stock_status = $products_detail->quantity - $stock;
        if (!empty($products_detail)) {            
            $product_sizes = $CI->Product_details_model->get_product_size($products_detail->product_id);
        } else {
            //=======================Page Not found=================
        }

        $subtosub_cat_id = $CI->Product_details_model->getSubtoSubCategory($product_id,
            $data['item_name']);
        $data['similar_products'] = Percentage_Calculate($CI->Product_details_model->getSimilarProducts($subtosub_cat_id->subtosub_category_id));
        $data['products_url']     = $url;
        $data['products_details'] = $products_detail;
        $data['stock_status']     = $stock_status;
        $data['product_size']     = $product_sizes;        
        return $CI->load->view($view, $data);
    }
}

if (!function_exists('Percentage_Calculate')) {
    function Percentage_Calculate($data)
    {
        foreach ($data as $key => $value) {
            $sell_price = $value->selling_price;
            $stand_price = $value->standard_price;
            $saving_price = $stand_price - $sell_price;
            $offer_per = round(($saving_price * 100) / $stand_price);
            $data[$key]->offer_per = $offer_per;
        }
        return $data;
    }
}
function array_sort_by_column(&$array, $col, $dir = SORT_ASC)
{
    $sort_col = array();
    foreach ($array as $key => $row) {
        $sort_col[$key] = $row->{$col};
    }
    array_multisort($sort_col, $dir, $array);
}

if (!function_exists('create_fillters')) {
    function create_fillters($records, $selet_arr, $key, $colomn_name = 'count_product', $sor_by = SORT_DESC)
    {
        if (!empty($records)) {
            $html = $more = '';
            $data = $main_array = array();
            $CI = &get_instance();
            $alfa_array = array(
                'a' => 'a',
                'b' => 'b',
                'c' => 'c',
                'd' => 'd',
                'e' => 'e',
                'f' => 'f',
                'g' => 'g',
                'h' => 'h',
                'i' => 'i',
                'j' => 'j',
                'k' => 'k',
                'l' => 'l',
                'm' => 'm',
                'n' => 'n',
                'o' => 'o',
                'p' => 'p',
                'q' => 'q',
                'r' => 'r',
                's' => 's',
                't' => 't',
                'u' => 'u',
                'v' => 'v',
                'w' => 'w',
                'x' => 'x',
                'y' => 'y',
                'z' => 'z');
            foreach ($records as $record) {
                if (in_array($record->{$key . '_name'}[0], $alfa_array)) {
                    $main_array[$record->{$key . '_name'}[0]][] = $record;
                }
            }

            array_sort_by_column($records, $colomn_name , $sor_by);
            $count = 0;
            foreach ($records as $record) {
                if ($record->count_product > 0) {
                    $count++;
                    $html .= create_checkbox_list($record, $selet_arr, $key);
                    //if($count == 5) break ;
                }
            }
            $more = '';
            /*if($count > 1)
            {
            $data['count']     = count($records);
            $data['records']   = $main_array;
            $data['selet_arr'] = $selet_arr;
            $data['key']       = $key;
            $more = $CI->load->view('shared/viw_fillter', $data, true);
            }*/
            return array('html' => $html, 'more' => $more);
        }
    }
}
if (!function_exists('create_checkbox_list')) {
    function create_checkbox_list($record, $selet_arr, $key)
    {
        $html = '';
        $select = '';
        if ($selet_arr != '') {
            if (in_array($record->{$key . '_url'}, $selet_arr) || in_array($record->{$key .
                '_id'}, $selet_arr)) {
                $select = 'checked="checked"';
            }
        }
        $count = ($key == 'color' || $key == 'size')?'':'<span>[' . $record->count_product . ']</span>'; 
        $html .= '<div class="checkbox">';
        $html .= '<label>';
        $html .= '<input type="checkbox" ' . $select . ' id="' . $key . '-' . $record->{$key . '_id'} . '" url="' . $record->{$key . '_url'} .
            '"/><i class="helper"></i>' . $record->{$key . '_name'} . $count ;
        $html .= '</label>';
        $html .= '</div>';
        return $html;
    }
}
if (!function_exists('get_discount_info')) {
    function get_discount_info()
    {
        $a = 0;
        $discount = array();
        for ($i = 10; $i <= 90; $i += 10) {
            $obj = new stdClass();
            $obj->discount_id = $a;
            $obj->discount_url = $a . '-' . $i;
            $obj->discount_name = $a . ' - ' . $i . '%';
            $discount[] = $obj;
            $a = $i;
        }
        return $discount;
    }
}

if (!function_exists('get_cart_info')) {
    function get_cart_info($user_id)
    {
        $CI = &get_instance();
        $CI->load->model('Product_cart_model');
        $product_info = $CI->cart->contents();
        //echo "<pre>";print_r($product_info);exit;
        if (!empty($product_info)) {
            foreach ($product_info as $key => $record) {
                if(!empty($record['id']))
                {
                    $pro_id_n_size_id = explode('_',$record['id']);
                }
                $cartdata = array(
                    'id' => $pro_id_n_size_id[0],
                    'qty' => $record['qty'],
                    'name' => $record['name'],
                    'size_id' => $pro_id_n_size_id[1],
                    'user_id' => $user_id);
                $CI->Product_cart_model->Save_Cart_Data($cartdata);
                $CI->cart->destroy();
            }
        }
    }
}
if (!function_exists('get_cart_total')) {
    function get_cart_total($cart_info)
    {
        $total = 0;
        if (!empty($cart_info)) {
            foreach ($cart_info as $record) {
                if (get_product_stocks(array('product_id' => $record['product_id'],
                        'product_url' => $record['product_url'])) != 0) {
                    $total = ($total + ((int)$record['price'] * (int)$record['qty']));
                }
            }
        }
        return $total;
    }
}

if (!function_exists('get_facebook_url')) {
    function get_facebook_url($login_url = false)
    {
        $CI = &get_instance();
        $CI->load->library('user_agent');
        
        require_once APPPATH . 'third_party/fbConfig.php';
        if (isset($accessToken)) 
        {
            if (isset($_SESSION['facebook_access_token'])) {
                $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
            } else {
                // Put short-lived access token in session
                $_SESSION['facebook_access_token'] = (string)$accessToken;

                //OAuth 2.0 client handler helps to manage access tokens
                $oAuth2Client = $fb->getOAuth2Client();

                // Exchanges a short-lived access token for a long-lived one
                $longLivedAccessToken = $oAuth2Client->getLongLivedAccessToken($_SESSION['facebook_access_token']);
                $_SESSION['facebook_access_token'] = (string)$longLivedAccessToken;

                // Set default access token to be used in script
                $fb->setDefaultAccessToken($_SESSION['facebook_access_token']);
            }

            // Redirect the user back to the same page if url has "code" parameter in query string
            if (isset($_GET['code'])) {
                header('Location: ./');
            }

            // Getting user facebook profile info
            try {
                $profileRequest = $fb->get('/me?fields=name,first_name,last_name,email,link,gender,locale,picture');
                $fbUserProfile = $profileRequest->getGraphNode()->asArray();
            }
            catch (FacebookResponseException $e) {
                echo 'Graph returned an error: ' . $e->getMessage();
                session_destroy();
                // Redirect user back to app login page
                header("Location: ./");
                exit;
            }
            catch (FacebookSDKException $e) {
                //echo 'Facebook SDK returned an error: ' . $e->getMessage();
                //header('location: https://www.bulknmore.com/social-login/issue');
                redirect(base_url('social-login/issue'));
                exit;
            }

            // Insert or update user data to the database
            if(isset($fbUserProfile['email']))
            {
                $fbUserData = array(
                'oauth_provider' => 'facebook',
                'oauth_uid' => $fbUserProfile['id'],
                'first_name' => $fbUserProfile['first_name'],
                'last_name' => $fbUserProfile['last_name'],
                'email' => $fbUserProfile['email'],
                'gender' => $fbUserProfile['gender'],
                'locale' => $fbUserProfile['locale'],
                'picture' => $fbUserProfile['picture']['url'],
                'link' => $fbUserProfile['link']);
                $user_data = $fbUserData;
            }
            else
            {
                $fbUserData = array(
                'oauth_provider' => 'facebook',
                'oauth_uid' => $fbUserProfile['id'],
                'first_name' => $fbUserProfile['first_name'],
                'last_name' => $fbUserProfile['last_name'],
                'gender' => $fbUserProfile['gender'],
                'email' => '',
                'locale' => $fbUserProfile['locale'],
                'picture' => $fbUserProfile['picture']['url'],
                'link' => $fbUserProfile['link']);
                $user_data = $fbUserData;
            }
            

            // Render facebook profile data
            if (!empty($user_data)) {
                 $output = array('user_data'=>$user_data,'callback_url'=>$CI->agent->referrer(),'login_process'=>true);

            } else {
                $output = array('error'=>'<h3 style="color:red">Some problem occurred, please try again.</h3>','callback_url'=>$CI->config->base_url());
            }
        } else {
            // Get login url
            $loginURL = $helper->getLoginUrl($redirectURL, $fbPermissions);

            // Render facebook login button
            $output = array('callback_url'=>htmlspecialchars($loginURL),'login_process'=>false);
        }
        return ($login_url == true)?$output['callback_url']:$output;
    }
}

if (!function_exists('get_product_stocks')) {
    function get_product_stocks($product_info)
    {
        $stock_status = 0;
        if (!empty($product_info['product_id'])) {
            $CI = &get_instance();
            $CI->load->model('Product_details_model');
            $products_detail = $CI->Product_details_model->get_products_quantity(empty($product_info['id']) ?
                $product_info['product_id'] : $product_info['id'], $product_info['product_url']);
            $stock = $CI->Product_details_model->get_product_stock(empty($product_info['id']) ?
                $product_info['product_id'] : $product_info['id']);
            $stock_status = $products_detail->quantity - $stock;
        }
        return $stock_status;
    }
}
if (!function_exists('get_tax_total')) {
    function get_tax_total($tax_percentage, $sub_total_price)
    {
        $total_tax = 0;
        if (!empty($sub_total_price) && !empty($tax_percentage)) {
            $total_tax = ($tax_percentage / 100) * $sub_total_price;
        }
        return $total_tax;
    }
}
if (!function_exists('getDeliveryCharges')) {
    function getDeliveryCharges($charge_info)
    {
        if (!empty($charge_info)) {
            $delivery_charge = (($charge_info['del_charge_per'] * $charge_info['total_amt']) /
                100);
        }

        if ($delivery_charge > 40)
            return $delivery_charge;
        else
            return 40;
    }
}

if (!function_exists('getOnlineCharges')) {
    function getOnlineCharges($online_info)
    {
        if (!empty($online_info)) {
            $online_charge = (($online_info['online_charge_per'] * $online_info['total_amt']) /
                100);
        }
        return $online_charge;
    }
}

if (!function_exists('getBNMCommissionCharges')) {
    function getBNMCommissionCharges($comm_info)
    {
        if (!empty($comm_info)) {
            $comm_charge = (($comm_info['comm_per'] * $comm_info['total_amt']) / 100);
        }
        return $comm_charge;
    }
}

if (!function_exists('getServiceTaxCharges')) {
    function getServiceTaxCharges($tax_info)
    {
        if (!empty($tax_info)) {
            $tax_charge = (($tax_info['ser_tax_per'] * $tax_info['total_amt']) / 100);
        }
        return $tax_charge;
    }
}

if (!function_exists('getKKCCharges')) {
    function getKKCCharges($kkc_info)
    {
        if (!empty($kkc_info)) {
            $kkc_charge = (($kkc_info['kkc_per'] * $kkc_info['total_amt']) / 100);
        }
        return $kkc_charge;
    }
}

if (!function_exists('getSBCharges')) {
    function getSBCharges($sb_info)
    {
        if (!empty($sb_info)) {
            $sb_charge = (($sb_info['sb_per'] * $sb_info['total_amt']) / 100);
        }
        return $sb_charge;
    }
}
if (!function_exists('checkPincodeAvailability')) {
    function checkPincodeAvailability($pincode)
    {
        $CI = &get_instance();
        $CI->load->model('Product_details_model');
        if (!empty($pincode)) {
            $pincode_status = $CI->Product_details_model->check_pincode_availability($pincode);
            if (!empty($pincode_status)) {
                return $pincode_status;
            }
        }
    }
}

/*if(!function_exists('sending_otp'))
{
function sending_otp($mobile,$msg)
{
return file_get_contents(str_replace('{otp_msg}',urlencode($msg),str_replace('{otp_mobile_no}',$mobile,OTP_URL)));            
}
}*/

if (!function_exists('sending_otp')) {
    function sending_otp($mobile, $msg)
    {
        $link = str_replace('{otp_msg}', urlencode($msg), str_replace('{otp_mobile_no}',
            $mobile, OTP_URL));
        $ch = curl_init();
        $timeout = 5;
        curl_setopt($ch, CURLOPT_URL, $link);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // if you want to follow redirects
        $data = curl_exec($ch);
        curl_close($ch);
        return $data;
    }
}
if (!function_exists('current_page_url')) {
    function current_page_url()
    {
        $pageURL = 'http';
        if (isset($_SERVER["HTTPS"]))
            if ($_SERVER["HTTPS"] == "on") {
                $pageURL .= "s";
            }
        $pageURL .= "://";
        if ($_SERVER["SERVER_PORT"] != "80") {
            $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
        } else {
            $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
        return $pageURL;
    }
}

if (!function_exists('check_login_session')) {
    function check_login_session()
    {
        $CI = &get_instance();
        if (!$CI->session->userdata("user_id")) {
            $CI->load->helper('cookie');
            $url = current_page_url();
            $CI->input->set_cookie('url', $url, 0);
            redirect(base_url() . '#login', "refresh");
        }
    }
}

if (!function_exists('generate_random_string')) {
    function generate_random_string($length = 10)
    {
        return substr(str_shuffle(str_repeat($x =
            '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length /
            strlen($x)))), 1, $length);
    }
}

// This function return encrypted value of a id
function make_encrypt($id)
{
    return encryptor("encrypt", $id);
}

// function to return decrypted id
function make_decrypt($e_id)
{
    return encryptor("decrypt", $e_id);
}
// function to encrypt and decrypt values
function encryptor($action, $string)
{
    $output = false;

    $encrypt_method = "AES-256-CBC";
    //Setting unique hashing key
    $secret_key = 'BulKnMoreSellerHub';
    $secret_iv = 'BulKnMoreSellerHub_MG';

    // hash
    $key = hash('sha256', $secret_key);

    // iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
    $iv = substr(hash('sha256', $secret_iv), 0, 16);

    //do the encyption given text/string/number
    if ($action == 'encrypt') {
        $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
        $output = base64_encode($output);
    } else
        if ($action == 'decrypt') {
            //decrypting the given text/string/number
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
        }

    return $output;
}
//---------------- create cart product listing -------------------
if (!function_exists('create_cart_product_listing')) {
    function create_cart_product_listing($products)
    {
        $arr = array();
        $CI = &get_instance();
        $CI->load->model('Product_cart_model');
        $voucher_code = '';        
        $pro_id_n_size_id = $voucher_amt = $total_set = $cart_total = $total_pieces = $total_vat = $sub_total = $i = $tot_weight = 0;        
        foreach ($products as $pro) 
        {
            if(!empty($pro['id']))
            {
                $pro_id_n_size_id = explode('_',$pro['id']);
            }            
            $obj = new stdClass();
            $obj->id = empty($pro['id']) ? $pro['product_id'] : $pro_id_n_size_id[0];
            $obj->qty = $pro['qty'];
            
            $obj->rowid = $pro['rowid'];
            $product_info = $CI->Product_cart_model->getProductInfo((empty($pro['id']) ? $pro['product_id'] : $pro_id_n_size_id[0]));
            $pro_size_info = $CI->Product_cart_model->getProductSize((empty($pro['id']) ? $pro['product_id'] : $pro_id_n_size_id[0]),(empty($pro['size_id']) ? $pro_id_n_size_id[1] : $pro['size_id'] ));
            
            $obj->voucher_code = $voucher_code = empty($pro['voucher_code']) ? $CI->session->userdata('voucher_code'): $pro['voucher_code'];//$CI->session->userdata('voucher_code');
                        
            if (!empty($product_info)) //   product_stock
            {
                $vat_total = get_tax_total($product_info->vat_class_per, (($product_info->selling_price * $product_info->pack_of) * $pro['qty']));
                $obj->image = $product_info->image_name;
                $obj->product_id = $product_info->product_id;
                $obj->price = $product_info->selling_price;
                $obj->seller_id = $product_info->seller_id;
                $obj->name = $product_info->item_name;
                $obj->standard_price = $product_info->standard_price;
                $obj->category = $product_info->category_url;
                $obj->sub_category = $product_info->sub_category_url;
                $obj->subtosub_category = $product_info->subtosub_category_url;
                $obj->product_url = $product_info->product_url;
                $obj->pack_of = $product_info->pack_of;
                $obj->set_description = $product_info->set_description;
                $obj->size_name       = empty($pro_size_info->size_name) ? '' : $pro_size_info->size_name; // Because it Set Wise Case No Size Name Found
                $obj->size_id       = empty($pro_size_info->size_id) ? '' : $pro_size_info->size_id; // Because it Set Wise Case No Size Name Found

                $obj->total_set_price = ($product_info->selling_price * $obj->pack_of);
                $obj->vat_amount = $vat_total;
                $obj->product_stock = $product_info->product_stock;
                $obj->vat_class_per = $product_info->vat_class_per;
                $obj->price_set = ($obj->qty * $obj->total_set_price);
                
                if( $product_info->product_stock > 0 && $product_info->product_status == 4 )
                {
                    $total_set += $obj->qty;
                    $total_pieces += ($product_info->pack_of * $obj->qty /*$total_set*/);
                    $total_vat += $vat_total;
                    $sub_total += $obj->price_set;
                    $tot_weight += ($product_info->pro_weight * $obj->qty);
                    $cart_total += ($vat_total + $obj->price_set);
                }
                $arr[] = $obj;
            }
        }
        $voucher_amt  = $CI->Product_cart_model->get_reedeem_voucher_amount($voucher_code,$sub_total);
        $shipping_total = 0; //getDeliveryCharges(array('del_charge_per'=>1.6,'total_amt'=>($proudct_info->selling_price * $pro['qty'])));
        if($voucher_amt > 0){
                $cart_total = (($cart_total - $voucher_amt )+ $shipping_total);
        }
        else {
            $cart_total = ($cart_total + $shipping_total);
        }
        
        return array('cart_list' => $arr, 'cart_totals' => array(
                     'total_set' => $total_set,
                     'total_pieces' => $total_pieces,
                     'cart_total' => $cart_total,
                     'voucher_total' => $voucher_amt,
                     'total_vat' => $total_vat,
                     'sub_total' => $sub_total,
                     'total_weight' => $tot_weight,
                     'shipping_total' => $shipping_total)
                    );
    }
}

if (!function_exists('get_logistic_charges')) {
    function get_logistic_charges($product_ids, $delivery_id,$qty)
    {
        $arr = array();
        $CI = &get_instance();
        $CI->load->library('common');
        $shipping_charges = $CI->common->calculate_logistic_charges($product_ids,$delivery_id,$qty);
        
        if(!empty($shipping_charges['shipping_charges'])) {            
            if ($shipping_charges['shipping_charges'] > 40){
                return $shipping_charges;
            }
            else
            {
                 $shipping_charges['shipping_charges'] = 40;
                 return $shipping_charges;
            }
        }
    }
}

if (!function_exists('get_user_info')) {
    function get_user_info($user_id)
    {
        $arr = array();
        $CI = &get_instance();
        $CI->load->model('Product_details_model');
        $user_info = $CI->Product_details_model->get_active_user_info($user_id);
        if(!empty($user_info)) {            
            return $user_info;
        }
    }
}
