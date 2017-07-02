<?php

/** 
 * Displays a prominent notification at the plugins page for WordPress plugin Upgrade Notices.
 * Credits: https://andidittrich.de/2015/05/howto-upgrade-notice-for-wordpress-plugins.html
 *
 * @since v1.1.1
 */

global $pagenow;

/**
 * WordPress hook. Appends an upgrade notification block underneath the plugin description, at the plugins page.
 * @param array $plugin_data    Array of plugin data.
 * @param array $r              Array of metadata about the plugin update.
 */
function in_plugin_update_message_acf_recaptcha($plugin_data, $r) { 
    if (isset($r->upgrade_notice) && strlen(trim($r->upgrade_notice)) > 0) {

        // Sanitize the text, only allow links.
        $notice = wp_kses($r->upgrade_notice, array(
            'a' => array(
                'href' => array(), 
                'title' => array()
            )
        ));

        ?>

        <span class="acf-recaptcha-upgrade-notice">
            <strong>Upgrade Notice:</strong>
            <?php echo $notice; ?>
        </span>

        <?php
    }
}

/**
 * WordPress hook. Enqueues required plugin upgrade CSS.
 */
function admin_enqueue_scripts_acf_recaptcha_plugin_update() {
    wp_enqueue_style('acf-recaptcha-css-plugin-update', plugins_url('css/plugin-update.css', ACF_RECAPTCHA_ABSPATH));
}

if ('plugins.php' === $pagenow) {
    $file = basename(ACF_RECAPTCHA_ABSPATH);
    $folder = basename(dirname(ACF_RECAPTCHA_ABSPATH));
    $hook = "in_plugin_update_message-{$folder}/{$file}";
    
    // Add action to display the notification block.
    add_action($hook, 'in_plugin_update_message_acf_recaptcha', 20, 2);

    // Add action to enqueue required CSS.
    add_action('admin_enqueue_scripts', 'admin_enqueue_scripts_acf_recaptcha_plugin_update');
}
