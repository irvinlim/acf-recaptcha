<?php

/*
Plugin Name: Advanced Custom Fields: reCAPTCHA Field
Plugin URI: https://github.com/irvinlim/acf-recaptcha/
Description: Google reCAPTCHA Field for Advanced Custom Fields. See <a href="https://www.google.com/recaptcha/">https://www.google.com/recaptcha/</a> for an account.
Version: 1.3.2
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
 * Loads the field type.
 * Will only be called if the ACF plugin is active.
 */
function include_field_types_recaptcha($version) {
    // Only support version 5.
    if ($version !== 5) {
        add_action('admin_notices', 'acf_unsupported_admin_notice');
        add_action('admin_notices', 'acf_unsupported_disable_plugin');
        return;
    }

    // Load auxilliary classes
    include_once('includes/settings-page.php');
    include_once('includes/plugin-update.php');
    include_once('includes/script-loader.php');
    include_once('includes/field-message.php');

    // Loads the main class.
    include_once('acf-recaptcha-v5.php');
}

add_action('acf/include_field_types', 'include_field_types_recaptcha');


/**
 * Show admin notice if ACF version is not supported.
 */
function acf_unsupported_admin_notice() {
    $class = 'notice notice-error';
    $notice = __('NOTICE', 'acf-recaptcha');
    $message = __('ACF reCAPTCHA is only supported on Advanced Custom Fields 5. The plugin has been disabled.', 'acf-recaptcha');

    printf('<div class="%1$s"><p><strong>%2$s: </strong>%3$s</p></div>', esc_attr($class), $notice, esc_html($message));
}


/**
 * Disables the plugin if ACF version is not supported.
 */
function acf_unsupported_disable_plugin() {
    deactivate_plugins(ACF_RECAPTCHA_ABSPATH, true);
}
