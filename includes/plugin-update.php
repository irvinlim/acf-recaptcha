<?php

/** 
 * Adds the WordPress plugin update action if we are on the plugins page.
 *
 * @since v1.1.1
 */

global $pagenow;

/**
 * WordPress action for setting the upgrade notification message at the plugins page.
 * @param array $plugin_data    Array of plugin data.
 * @param array $r              Array of metadata about the plugin update.
 */
function in_plugin_update_message_acf_recaptcha($plugin_data, $r) { 
    if (isset($r->upgrade_notice) && strlen(trim($r->upgrade_notice)) > 0) {
        ?>

        <span style="display: block; background-color: #d54e21; padding: 10px; color: #f9f9f9; margin: 10px 0">
            <strong>Upgrade Notice:</strong>
            <?php echo esc_html($r->upgrade_notice); ?>
        </span>

        <?php
    }
}

if ('plugins.php' === $pagenow) {
    $file = ACF_RECAPTCHA_BASENAME;
    $folder = basename(ACF_RECAPTCHA_ABSPATH);
    $hook = "in_plugin_update_message-{$folder}/{$file}";
    
    add_action($hook, 'in_plugin_update_message_acf_recaptcha', 20, 2);
}
