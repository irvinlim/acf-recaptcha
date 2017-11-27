<?php

/*
Plugin Name: Advanced Custom Fields: reCAPTCHA Field
Plugin URI: https://github.com/irvinlim/acf-recaptcha/
Description: Google reCAPTCHA Field for Advanced Custom Fields. See <a href="https://www.google.com/recaptcha/">https://www.google.com/recaptcha/</a> for an account.
Version: 1.2.1
Author: Irvin Lim
Author URI: https://irvinlim.com
License: MIT
License URI: https://opensource.org/licenses/MIT
*/


define('ACF_RECAPTCHA_ABSPATH', __FILE__);


/**
 * Loads the text domain.
 */
load_plugin_textdomain('acf-recaptcha', false, dirname(plugin_basename(__FILE__)) . '/lang/');


/**
 * Loads any auxiliary files.
 */
include_once('includes/settings-page.php');
include_once('includes/plugin-update.php');
include_once('includes/script-loader.php');
include_once('includes/field-message.php');


/**
 * Loads the acf_field classes.
 */
function include_field_types_recaptcha($version) {
    include_once('acf-recaptcha-v5.php');
}

add_action('acf/include_field_types', 'include_field_types_recaptcha');

