<?php

/**
 * WordPress action for setting the upgrade notification message at the plugins page.
 * @param array $plugin_data    Array of plugin data.
 * @param array $r              Array of metadata about the plugin update.
 */
function in_plugin_update_message($plugin_data, $r) { 
    if (isset($r->upgrade_notice) && strlen(trim($r->upgrade_notice)) > 0) {
        ?>

        <p style="background-color: #d54e21; padding: 10px; color: #f9f9f9; margin-top: 10px">
            <strong>Upgrade Notice:</strong>
            <?php echo esc_html($r->upgrade_notice); ?>
        </p>

        <?php
    }
}
