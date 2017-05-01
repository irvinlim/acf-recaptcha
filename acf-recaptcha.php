<?php

/*
Plugin Name: Advanced Custom Fields: reCAPTCHA Field
Plugin URI: https://github.com/irvinlim/acf-recaptcha/
Description: Google reCAPTCHA Field for Advanced Custom Fields. See <a href="https://www.google.com/recaptcha/">https://www.google.com/recaptcha/</a> for an account.
Version: 1.0.5
Author: Irvin Lim
Author URI: https://irvinlim.com
License: MIT
License URI: https://opensource.org/licenses/MIT
*/


load_plugin_textdomain('acf-recaptcha', false, dirname(plugin_basename(__FILE__)) . '/lang/');


function include_field_types_recaptcha($version) {

    include_once('acf-recaptcha-v5.php');

}

add_action('acf/include_field_types', 'include_field_types_recaptcha');
