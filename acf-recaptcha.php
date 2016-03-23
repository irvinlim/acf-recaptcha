<?php

/*
Plugin Name: ACF reCAPTCHA
Plugin URI: https://github.com/UnaiYecora/acf-recaptcha
Description: Reduce spam with reCAPTCHA on your Advanced Custom Fields frontend forms. Currently only supports ACF v5.
Version: 1.2
Author: Unai Yécora
Author URI: http://unaiyecora.com
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
Original Plugin Name: Advanced Custom Fields: reCAPTCHA Field
Original Author: Irvin Lim
Original Author URI: http://irvinlim.com
Original Plugin URI: https://github.com/irvinlim/acf-recaptcha/
*/




load_plugin_textdomain( 'acf-recaptcha', false, dirname( plugin_basename(__FILE__) ) . '/lang/' );



function include_field_types_recaptcha( $version ) {

	include_once('acf-recaptcha-v5.php');

}

add_action('acf/include_field_types', 'include_field_types_recaptcha');




/*
 * ACF 4 support coming soon!
 */

/*function register_fields_recaptcha() {

	include_once('acf-recaptcha-v4.php');

}

add_action('acf/register_fields', 'register_fields_recaptcha');	*/




?>