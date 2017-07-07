<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Display Debug backtrace
|--------------------------------------------------------------------------
|
| If set to TRUE, a backtrace will be displayed along with php errors. If
| error_reporting is disabled, the backtrace will not display, regardless
| of this setting
|
*/
defined('SHOW_DEBUG_BACKTRACE') OR define('SHOW_DEBUG_BACKTRACE', TRUE);

/*
|--------------------------------------------------------------------------
| File and Directory Modes
|--------------------------------------------------------------------------
|
| These prefs are used when checking and setting modes when working
| with the file system.  The defaults are fine on servers with proper
| security, but you may wish (or even need) to change the values in
| certain environments (Apache running a separate process for each
| user, PHP under CGI with Apache suEXEC, etc.).  Octal values should
| always be used to set the mode correctly.
|
*/
defined('FILE_READ_MODE')  OR define('FILE_READ_MODE', 0644);
defined('FILE_WRITE_MODE') OR define('FILE_WRITE_MODE', 0666);
defined('DIR_READ_MODE')   OR define('DIR_READ_MODE', 0755);
defined('DIR_WRITE_MODE')  OR define('DIR_WRITE_MODE', 0755);

/*
|--------------------------------------------------------------------------
| File Stream Modes
|--------------------------------------------------------------------------
|
| These modes are used when working with fopen()/popen()
|
*/
defined('FOPEN_READ')                           OR define('FOPEN_READ', 'rb');
defined('FOPEN_READ_WRITE')                     OR define('FOPEN_READ_WRITE', 'r+b');
defined('FOPEN_WRITE_CREATE_DESTRUCTIVE')       OR define('FOPEN_WRITE_CREATE_DESTRUCTIVE', 'wb'); // truncates existing file data, use with care
defined('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE')  OR define('FOPEN_READ_WRITE_CREATE_DESTRUCTIVE', 'w+b'); // truncates existing file data, use with care
defined('FOPEN_WRITE_CREATE')                   OR define('FOPEN_WRITE_CREATE', 'ab');
defined('FOPEN_READ_WRITE_CREATE')              OR define('FOPEN_READ_WRITE_CREATE', 'a+b');
defined('FOPEN_WRITE_CREATE_STRICT')            OR define('FOPEN_WRITE_CREATE_STRICT', 'xb');
defined('FOPEN_READ_WRITE_CREATE_STRICT')       OR define('FOPEN_READ_WRITE_CREATE_STRICT', 'x+b');

/*
|--------------------------------------------------------------------------
| Exit Status Codes
|--------------------------------------------------------------------------
|
| Used to indicate the conditions under which the script is exit()ing.
| While there is no universal standard for error codes, there are some
| broad conventions.  Three such conventions are mentioned below, for
| those who wish to make use of them.  The CodeIgniter defaults were
| chosen for the least overlap with these conventions, while still
| leaving room for others to be defined in future versions and user
| applications.
|
| The three main conventions used for determining exit status codes
| are as follows:
|
|    Standard C/C++ Library (stdlibc):
|       http://www.gnu.org/software/libc/manual/html_node/Exit-Status.html
|       (This link also contains other GNU-specific conventions)
|    BSD sysexits.h:
|       http://www.gsp.com/cgi-bin/man.cgi?section=3&topic=sysexits
|    Bash scripting:
|       http://tldp.org/LDP/abs/html/exitcodes.html
|
*/
defined('EXIT_SUCCESS')        OR define('EXIT_SUCCESS', 0); // no errors
defined('EXIT_ERROR')          OR define('EXIT_ERROR', 1); // generic error
defined('EXIT_CONFIG')         OR define('EXIT_CONFIG', 3); // configuration error
defined('EXIT_UNKNOWN_FILE')   OR define('EXIT_UNKNOWN_FILE', 4); // file not found
defined('EXIT_UNKNOWN_CLASS')  OR define('EXIT_UNKNOWN_CLASS', 5); // unknown class
defined('EXIT_UNKNOWN_METHOD') OR define('EXIT_UNKNOWN_METHOD', 6); // unknown class member
defined('EXIT_USER_INPUT')     OR define('EXIT_USER_INPUT', 7); // invalid user input
defined('EXIT_DATABASE')       OR define('EXIT_DATABASE', 8); // database error
defined('EXIT__AUTO_MIN')      OR define('EXIT__AUTO_MIN', 9); // lowest automatically-assigned error code
defined('EXIT__AUTO_MAX')      OR define('EXIT__AUTO_MAX', 125); // highest automatically-assigned error code
//----- Custom Constants 


defined('OTP_URL') OR define('OTP_URL', 'http://sms.bulknmore.com/index.php/smsapi/httpapi/?uname=bulknmore&password=nimitta_bnm&sender=BnMore&receiver={otp_mobile_no}&route=TA&msgtype=1&sms={otp_msg}');

// OLD URL http://sms.bulknmore.com/api/send_http.php?authkey=e0f19ca50ee119f68686e52745086c44&mobiles={otp_mobile_no}&message={otp_msg}&sender=BnMore&route=4
// NEW URL http://sms.bulknmore.com/index.php/smsapi/httpapi/?uname=username&password=password&sender=senderid&receiver=mobno&route=route&msgtype=messagetype&sms=test
// For BULKNMORE http://sms.bulknmore.com/index.php/smsapi/httpapi/?uname=nimitta&password=nimitta&sender=BnMore&receiver=8824124124&route=TA&msgtype=1&sms=Testing

defined('RAZORPAY_API')        OR define('RAZORPAY_API', 'rzp_live_cOxLPh7Ikt7b7G');  // rzp_live_cOxLPh7Ikt7b7G LIVE // rzp_test_3SKN9AzPR78PKw DEMO
defined('RAZORPAY_PASS')       OR define('RAZORPAY_PASS', 'P6MIhMWbxBiHCg2kroCUHhp1');//P6MIhMWbxBiHCg2kroCUHhp1 LIVE // VdoKk0A7jadwe4nhLa4M9gsC DEMO
defined('ADMIN_BASE_URL')      OR define('ADMIN_BASE_URL','http://admin.bulknmore.com/');
//logistics Constant
define("DELHIVERY_LOGISTIC_URL","https://track.delhivery.com/");
define("DELHIVERY_LIVE_LOGISTIC_URL","https://track.delhivery.com/");


defined('FACEBOOK_APP_ID') OR define('FACEBOOK_APP_ID','1681808715464955');
defined('FACEBOOK_APP_SECRET') OR define('FACEBOOK_APP_SECRET','a3e110d680bab6d43bb258a394bf757d');

defined('BUYER_NOTIFICATION_FIREBASE_API_KEY') OR define('BUYER_NOTIFICATION_FIREBASE_API_KEY','AAAAZqse_j0:APA91bEqE2ObVY22rqw-YhnA7zkbQujspbjwx61pdGFYGlWkvzDV_DZ_blC3pNzckeJpR4Y7CCSzbXAsb9pzGXug0FCyYv0fLBXXfM2nu07WmcM4ejZxLlkKVKZFuSrofa8eQogorbRb');