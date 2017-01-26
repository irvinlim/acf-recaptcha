<?php

/*
Plugin Name: Advanced Custom Fields: reCAPTCHA Field
Plugin URI: https://github.com/irvinlim/acf-recaptcha/
Description: Google reCAPTCHA Field for Advanced Custom Fields. See <a href="https://www.google.com/recaptcha/">https://www.google.com/recaptcha/</a> for an account.
Version: 1.0.5
Author: Irvin Lim
Author URI: http://irvinlim.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/




load_plugin_textdomain( 'acf-recaptcha', false, dirname( plugin_basename(__FILE__) ) . '/lang/' ); 



function include_field_types_recaptcha( $version ) {
	
	include_once('acf-recaptcha-v5.php');
	
}

add_action('acf/include_field_types', 'include_field_types_recaptcha');	




/* 
 * ACF 4 not supported yet!
 */

/*function register_fields_recaptcha() {
	
	include_once('acf-recaptcha-v4.php');
	
}

add_action('acf/register_fields', 'register_fields_recaptcha');	*/
