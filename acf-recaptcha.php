<?php

/*
Plugin Name: Advanced Custom Fields: reCAPTCHA Field
Plugin URI: https://github.com/irvinlim/acf-recaptcha/
Description: Google reCAPTCHA Field for Advanced Custom Fields. See <a href="https://www.google.com/recaptcha/">https://www.google.com/recaptcha/</a> for an account.
Version: 1.1.0
Author: Irvin Lim
Author URI: https://irvinlim.com
License: MIT
License URI: https://opensource.org/licenses/MIT
*/

global $pagenow;

// Loads the text domain.
load_plugin_textdomain('acf-recaptcha', false, dirname(plugin_basename(__FILE__)) . '/lang/');

// Loads the acf_field classes.
function include_field_types_recaptcha($version) {
    include_once('acf-recaptcha-v5.php');
}

add_action('acf/include_field_types', 'include_field_types_recaptcha');

include_once('includes/plugin-update.php');

// Adds the WordPress plugin update action if we are on the plugins page.
if ('plugins.php' === $pagenow) {
    $file = basename(__FILE__);
    $folder = basename(dirname(__FILE__));
    $hook = "in_plugin_update_message-{$folder}/{$file}";
    add_action($hook, 'in_plugin_update_message_acf_recaptcha', 20, 2);
}