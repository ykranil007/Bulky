<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$controller = 'Product_details|Product_listing';

$route['404_override']         = ''.$controller.'/show_404_page';
$route['translate_uri_dashes'] = FALSE;
$category			= 'mens|womens|kids|home-decor|festive-decor|footwear';//womens-clothing
$route['default_controller'] = 'Home';
//$route['login'] 			= 'Register';
$route['validate'] 			= 'Register/registerUser';
$route['thank-you'] 		= "Register/register_thankYou";
$route['otp-validate']  	= "Register/validateOTP";
$route['resend-otp'] 		= 'Register/ResendBulkOTP';
$route['validate-login'] 	= "Userlogin/validate";
$route['validate-otp-login'] = "Userlogin/validate_otp_login";
$route['validate-otp-login-otp'] = 'Userlogin/validate_otp_login_otp';
$route['resend-login-otp'] = 'Userlogin/resendOTP';

$route['user-logout'] 		= "Userlogin/userLogout";
$route['forgot-password'] 	= "Register/forgotPassword";
$route['new-otp-validate']  = 'Register/validateNewOTP';
$route['upload-bulk-user-documents'] = 'Register/UploadBulkUserDocuments';
//=======for download app============
$route['send-applink']		= "Home/send_link_todownload_app";
$route['downloads/(:any)']	= "Home/download_app/$1";
//=======END for download app============
// Start  Products Routes
$route['view-all-products/(:any)'] = 'Home/view_all_home_products/$1';
$route['get-all-products'] = 'Home/get_all_products';
//$route['(mens-clothing)/(:any)/(:any)/(:any)/(:any)'] = 'Product_details/$1/$2/$3/$4/$5';
$route['('.$category.')/([a-z|-]+)/([a-z|-]+)/([a-z0-9|-]+)/(:any)'] = 'Product_details/index';

$route['products/('.$category.')']               = 'Product_listing/index/$1';
$route['products/('.$category.')/(:any)']        = 'Product_listing/index/$1/$2';
$route['products/('.$category.')/(:any)/(:any)'] = 'Product_listing/index/$1/$2/$3';
$route['products']                               = 'Product_listing/index';
$route['bulkshop/('.$category.')'] = 'Home/show_Sub_Categorys';
// End Products Routes
//Forgot Section
$route['user-id/(:num)']        = 'Register/UserId/$1';
$route['forgot-otp-validate']   = "Register/validateForgotOTP";
$route['change-password']  	    = "Register/ChangePassword";
// Admin authentication Area
$route['admin'] 		        = 	"admin/userlogin";
$route['validate-user'] 	    =	"admin/userlogin/validate";
$route['admin-dashboard'] 	    = 	"admin/dashboard";
$route['update-member-profile'] = 	'dashboard/dashboard/getProfile';
$route['logout'] 		        = 	"admin/userlogin/logout";
// End Admin authentication Area
//Products Cart
$route['add-to-cart']      		  = 'ProductCart/Products';
$route['remove-cart-item-(:any)'] = 'ProductCart/Delete/$1';
$route['updatecart']              = 'ProductCart/update';
$route['cart']                    = 'ProductCart';
$route['update-quantity']		  = "ProductCart/quantity_update";

//END Products Cart
// Product Checkout User Login
$route['checkout-login'] 			= 'Checkout/login_checkout_user';
$route['validate-checkout-login'] 	= 'Checkout/Validate_Checkout_User';
$route['register-checkout-user'] 	= 'Checkout/register_checkout_user';
$route['verify-checkout-user'] 		= 'Checkout/verify_checkout_user';
$route['update-checkout-user-password'] = 'Checkout/update_checkout_user_password';
// Product Checkout
$route['CheckoutHome']               = 'Checkout/index';
$route['checkout']                   = 'Checkout/ProductCheckout';
$route['edit-delvry-address/(:any)'] = 'Checkout/EditDeliveryAddress/$1';
$route['update-ads/(:any)']          = 'Checkout/UpdateDeliveryAddress/$1';
$route['add_delvry_ads']             = 'Checkout/AddNewDeliveryAddress';
$route['delete-delvry-ads/(:any)']   = 'Checkout/DeleteDeliveryAddress/$1';
$route['delvry_ads']                 = 'Checkout/GetDeliveryAddress';
$route['checkout-order-summary']     = 'Checkout/CheckoutProducts';
$route['validate-order']    = 'Checkout/ValidateOrderOTP';
$route['verify-order']      = 'Checkout/VerifyProductOrderOTP';
$route['place-order']       = 'Checkout/PlaceOrder';
$route['order/thank-you/(:any)']      = 'Checkout/PlaceOrderDetails/$1';
$route['pincode-details']   = 'Checkout/PincodeDetails';
//END Product Checkout
$route['admin/test']    = 'Product_details/checkPincode';
// for user account  dashbord
$route['account']            = "dashboard/Dashboard/dashboard_User_Account";
$route['update-account']     = "dashboard/Dashboard/editDashboardPersonalinfo";
$route['changepassword']     = "dashboard/Dashboard/dashboard_User_Change_Password";
$route['address']            = "dashboard/Dashboard/user_Address";
$route['profilesettings']    = "dashboard/Dashboard/userProfileSetting";
$route['accountemailupdate'] = "dashboard/Dashboard/viewUserEmailMobile";
$route['accountdeactivate']  = "dashboard/Dashboard/view_Deactivate_Account";
$route['user-wallet'] 		 = 'dashboard/Dashboard/userWallet';
$route['add-wallet-money'] = 'dashboard/Dashboard/addWalletMoney';
/*--------------ANIL Code-------------------*/
$route['user-wishlist']		= 'dashboard/Dashboard/Wish_List';
$route['show-buyer-orders'] = 'dashboard/Dashboard/show_user_orders';
$route['orders']			= 'dashboard/Dashboard/User_Orders';
$route['order-details?(:any)']	= 'dashboard/Dashboard/User_Order_Details/$1';
/*------------------End ANIL Code---------*/
//today rohit work
//$route['about-us']        				= 'Page/Page/aboutUs';
$route['about']        				= 'Page/Page/aboutUs';
//$route['WhoWeAre']        				= 'Page/Page/aboutUs';
//$route['faqs']        					= 'Page/Page/faqs';
$route['help']        					= 'Page/Page/faqs';
$route['careers']        				= 'Page/Page/careers';
$route['stories']        				= 'Page/Page/stories';
$route['privacy']    					= 'Page/Page/privacyPolicy';
$route['press ']    					= 'Page/Page/press';

$route['advertise-with-us']    			= 'Page/Page/Advertise_with_Us';

$route['selles-on-domain']    			= 'Page/Page/sellOnDemand';
$route['return-policy']    				= 'Page/Page/returnPolicy';
$route['refund-policy']    				= 'Page/Page/refundPolicy';
//$route['Refunds']    					= 'Page/Page/refundPolicy';
$route['refunds']    					= 'Page/Page/refundPolicy';
$route['shipping-policy']    			= 'Page/Page/shippingPolicy';
//$route['terms-of-use']    				= 'Page/Page/termOfUse';
$route['terms']    						= 'Page/Page/termOfUse';
$route['promotions']					= 'Page/Page/promotions';
$route['payments']						= 'Page/Page/payments';
//$route['saved-cards']					= 'Page/Page/savedCards';
$route['savedcard']						= 'Page/Page/savedCards';
$route['shipping']						= 'Page/Page/shipping';
$route['cancellation-returns']			= 'Page/Page/cancellationReturns';
$route['report-infringement']			= 'Page/Page/reportInfringement';
//$route['Contact']						= 'Page/Page/contactUs';
$route['contact']						= 'Page/Page/contactUs';
/*-----------------------App Web Service Route--------------- */
/*=======================Start for Order manage ===========================*/
$route['admin/view-orders']              = "admin/Orders/viewOrders";
$route['admin/view-orders/(:num)']       = "admin/Orders/viewOrders/$1";
$route['admin/delete-order/(:num)']      = "admin/Orders/deleteOrders/$1";
$route['admin/view-orderdetails/(:num)'] = "admin/Orders/getOrderDetails/$1";
/*=======================ENd for Order manage ===========================*/
//---- For social login
$social_login = "Google|Facebook";
//$fb_login     = "Facebook";
$route['social-login/('.$social_login.')'] = "Hauth/doLogin/$1";
//$route['social-fb-login/('.$fb_login.')'] 	   = "Hauth/doLogin/$1";
//$route['social-login/Facebook'] 		   = 'Welcome';
$route['social-login/facebook']     = "Userlogin/redirect_facebook";

$route['welcome'] 		   = 'Welcome';
$route['dashboard/order/listing']   = "dashboard/Dashboard/order_pagination";
$route['upgrade/form']              = "Userlogin/getSocialLoginPage";

$route['social-login/issue']              = "Userlogin/getSocialLoginIssue";

$route['upgrade-form/mobile']       = "Userlogin/upgradeMobile";
$route['resend-otp']				= "Register/resendOTP";

$route['upgrade-form/otp']          = "Userlogin/upgradeOtp"; 
$route['upgrade-form/password']           = "Userlogin/upgradePassword";  
//=========================saved cards ==================================
$route['offers']                            = 'dashboard/Dashboard/Offers_Vouchers';
$route['card-details']						= "dashboard/Dashboard/get_saved_cards";
$route['save-carddetails']					= "dashboard/Dashboard/save_card_details";
$route['delete-carddetails']				= "dashboard/Dashboard/remove_saved_card";
$route['social-login']           	 		= "social_login_api/Social_login/socialLogin";  
$route['bulk-selling-product-price']    = 'product/Products/bulkSellingPrice';
$route['cancel-product'] = 'Order_cancel/Cancel_single_product';
$route['check-mobile'] 	 = 'Register/check_mobile_exist';
$route['get-cancel-order-list'] = 'Order_cancel/get_order_details';
$route['bulk-price'] = 'Product_details/calculate_bulk_price_on_radio';
$route['update-bulk-price'] = 'Product_details/update_bulk_price';
$route['return-product'] = 'Order_cancel/return_product';
/*=========for update email/mob============*/

$route['user-exist']			= 'dashboard/Dashboard/check_user_exist';
$route['send-otp-mobupdate']	= 'dashboard/Dashboard/send_otp_to_users';
$route['get-userotp']			= 'dashboard/Dashboard/get_user_otp';
$route['get-userpass']			= 'dashboard/Dashboard/get_user_pass';
$route['update-usermobile']		= 'dashboard/Dashboard/save_user_mobile';
$route['get-existingemail']		= 'dashboard/Dashboard/get_existing_email';
$route['get-emailotp']			= 'dashboard/Dashboard/get_user_email_otp';
$route['get-emailpass']			= 'dashboard/Dashboard/get_user_pass_foremail';
$route['update-useremail']		= 'dashboard/Dashboard/save_user_email';
$route['refund-bankdetails']	= 'dashboard/Dashboard/view_refund_bank_details';
$route['save-bankdetails']		= 'dashboard/Dashboard/save_bank_details';
$route['delete-bank-account']   = 'dashboard/Dashboard/delete_bank_details';

$route['voucher_or_coupan_reedeem'] = 'ProductCart/reedeem_voucher';
/*========= END for update email/mob======*/
/**********************************Android app Route******************************/
$route['app/register-users'] 			= 'RegistrationApi/Register/registerUser';
$route['app/otp_verify']     			= 'RegistrationApi/Register/validateOTP';
$route['app/validate-login-user']  		= 'LoginApi/Userlogin/validate';
$route['app/home']          			= 'API/Home/index';
$route['app/get_recentView_products']   = 'API/Home/get_recentView_products';
$route['app/product-listing']   		= 'API/Product_listing/get_product_list_by_ajax';
$route['app/get-category']  			= 'API/Comman/get_category';
$route['app/offer-list']  			    = 'API/Comman/offer_list';
$route['app/get-sub-category']  		= 'API/Comman/get_subtosub_category';
$route['app/product-details']   		= 'API/Product_details/index';
$route['app/check-pincode']   			= 'API/Product_details/checkPincode';
$route['app/save-product-enquiry']      = 'API/Product_details/save_product_enquiry';
$route['app/delivery_address'] 			= 'API/Checkout/Delivery_Address';
$route['app/pincode_details'] 			= 'API/Checkout/PincodeDetails';
$route['app/remove-delivery-address'] 	= 'API/Checkout/delete_delivery_address';
$route['app/update-user-profile']	  	= 'API/Checkout/updateUserProfile';
$route['app/change-user-password'] 		= 'API/Checkout/changePassword';
$route['app/add-wishlist'] 				= 'API/Checkout/Add_Wishlist';
$route['app/get-wishlist'] 				= 'API/Checkout/get_Wish_List';
$route['app/delete-wishlist'] 			= 'API/Checkout/deleteWishList';
$route['app/add-to-cart'] 				= 'API/ProductCart/Products';
$route['app/remove-cart-item']  		= 'API/ProductCart/removeCartData';
$route['app/add-wallet-money']  		= 'API/Dashboard/addWalletMoney';
$route['app/address']					= 'API/Dashboard/user_Address';
$route['app/deactive-account'] 			= 'API/Dashboard/deactivateAccount';
$route['app/save-user-cards'] 			= 'API/Dashboard/SaveCreditCards';
$route['app/delete-user-card'] 			= 'API/Dashboard/DeleteCreditCards';
$route['app/voucher-offer-list'] 		= 'API/Dashboard/Offers_Vouchers';
$route['app/get-delivery-tax-charge'] 	= 'API/Checkout/getDeliveryANDTaxAmount';
$route['app/get-tax-amount'] 			= 'API/ProductCart/getProductTotalVat';
$route['app/cart-quantity-update'] 		= 'API/ProductCart/cart_quantity_update';
$route['app/social-login']           	= "social_login_api/Social_login/socialLogin";
$route['app/forgot-password'] 			= 'RegistrationApi/Register/ForgotPassword'; 
$route['app/validate-forgot-password'] 	= 'RegistrationApi/Register/validateForgotOTP'; 
$route['app/change-password'] 			= 'RegistrationApi/Register/ChangePassword'; 
$route['app/verify-orders'] 			= 'API/Checkout/ValidateOrderOTP';
$route['app/verify-order-otp'] 			= 'API/Checkout/VerifyProductOrderOTP';
$route['app/place-order'] 				= 'API/Checkout/PlaceOrder';
$route['app/shipping-charges'] 			= 'API/Checkout/get_logistic_charge';
$route['app/about-us']        			= 'API/Page/Page/aboutUs'; // New
$route['app/faqs']        				= 'API/Page/Page/faqs'; // New
$route['app/privacy-policy']    		= 'API/Page/Page/privacyPolicy'; // New
$route['app/contact-us']				= 'API/Page/Page/contactUs'; // New
$route['app/get_user_order_list'] 		= 'API/Dashboard/get_app_user_Orders';
$route['app/user_order_details'] 		= 'API/Dashboard/user_app_order_details';
$route['app/get-razor-key'] 			= 'API/Checkout/getRazorKey'; 
$route['app/online-banking'] 			= 'API/Checkout/online_banking';
$route['app/check-stock'] 				= 'API/ProductCart/check_stock';
$route['app/reedeem_voucher']           = 'API/ProductCart/reedeem_voucher';
$route['app/cancel-list'] 				= 'API/Order_cancel/getCancellationReasonDropdown';
$route['app/cancel-product'] 			= 'API/Order_cancel/Cancel_single_product';
$route['app/return-reason-list'] 		= 'API/Order_cancel/return_reason_list';
$route['app/return-product']			= 'API/Order_cancel/return_from_app_product';
$route['app/resend-otp']				= 'RegistrationApi/Register/resend_otp';
$route['app/save-bank-account']         = 'API/Dashboard/save_bank_details';
$route['app/get-bank-account']          = 'API/Dashboard/get_bank_details';
$route['app/delete-bank-account']       = 'API/Dashboard/delete_bank_details';
$route['app/buyer_notification']        = 'API/Notification';
$route['app/save-users-feedback']       = 'API/Comman/save_buyer_feedback';

/***************************** ANIL Android App Route **************/
$route['facebook/login']                = 'User_Authentication/rohit';
$route['anilapp/user_register']         = "AnilApp";
$route['save-franchise-data']			= 'Home/save_franchise_data';