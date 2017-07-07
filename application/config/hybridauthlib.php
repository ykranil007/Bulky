<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*!
* HybridAuth
* http://hybridauth.sourceforge.net | http://github.com/hybridauth/hybridauth
* (c) 2009-2012, HybridAuth authors | http://hybridauth.sourceforge.net/licenses.html
*/

// ----------------------------------------------------------------------------------------
//	HybridAuth Config file: http://hybridauth.sourceforge.net/userguide/Configuration.html
// ----------------------------------------------------------------------------------------

$config =
	array(
		// set on "base_url" the relative url that point to HybridAuth Endpoint
		'base_url' => '/hauth/endpoint',

		"providers" => array (
			// openid providers
			"OpenID" => array (
				"enabled" => true
			),

			"Yahoo" => array (
				"enabled" => true,
				"keys"    => array ( "id" => "", "secret" => "" ),
			),

			"AOL"  => array (
				"enabled" => true
			),

			"Google" => array (
				"enabled" => true,
				//"keys"    => array("id"=>"400660562265-sfq733199vei652dv66b5kcn84rdvebr.apps.googleusercontent.com", "secret" => "74ALEHDSq3AM_Mbw6GqRuQK6" ),
				"keys"    => array("id"=>"400660562265-sfq733199vei652dv66b5kcn84rdvebr.apps.googleusercontent.com", "secret" => "jIDju4oyMXgRemFDUCeddcCN" ),
			),
			"Facebook" => array (
				"enabled" => true,
				//"keys"    => array ( "id" => "1681808715464955", "secret" => "a3e110d680bab6d43bb258a394bf757d" ), //rohitash
				"keys"    => array ( "id" => "1000021226774551", "secret" => "9ef5a6f2fdbc8d862d135ba84cd8eb53" ), // mukesh
				'scope'   => 'email',
                
			),

			"Twitter" => array (
				"enabled" => true,
				"keys"    => array ( "key" => "", "secret" => "" )
			),

			// windows live
			"Live" => array (
				"enabled" => true,
				"keys"    => array ( "id" => "", "secret" => "" )
			),

			"MySpace" => array (
				"enabled" => true,
				"keys"    => array ( "key" => "", "secret" => "" )
			),

			"LinkedIn" => array (
				"enabled" => true,
				"keys"    => array ( "key" => "", "secret" => "" )
			),

			"Foursquare" => array (
				"enabled" => true,
				"keys"    => array ( "id" => "", "secret" => "" )
			),
		),

		// if you want to enable logging, set 'debug_mode' to true  then provide a writable file by the web server on "debug_file"
		"debug_mode" => (ENVIRONMENT == 'development'),

		"debug_file" => APPPATH.'logs/hybridauth.log',
	);


/* End of file hybridauthlib.php */
/* Location: ./application/config/hybridauthlib.php */
