<?php

/**
 * Adds a settings page for configuring global ACF reCAPTCHA settings, which will be displayed
 * under the ACF settings menu.
 *
 * @since 1.3.0
 */


/**
 * This action adds a submenu page under the "Field Groups" menu item.
 *
 * @type    action 'admin_menu'
 * @since   1.3.0
 */
function acf_recaptcha_add_admin_menu()
{
    add_submenu_page(
        'edit.php?post_type=acf-field-group',
        __('ACF reCAPTCHA', 'acf-recaptcha'),
        __('ACF reCAPTCHA', 'acf-recaptcha'),
        'manage_options',
        'acf-recaptcha',
        'acf_recaptcha_options_page'
    );
}

add_action('admin_menu', 'acf_recaptcha_add_admin_menu', 30);


/**
 * This action initializes the settings for the settings page.
 *
 * @type    action 'admin_init'
 * @since   1.3.0
 */
function acf_recaptcha_settings_init()
{
    register_setting('acf_recaptcha_settings', 'acf_recaptcha');

    add_settings_section(
        'acf_recaptcha_settings_section',
        __('Google reCAPTCHA Default Keys', 'acf-recaptcha'),
        'acf_recaptcha_settings_section_callback',
        'acf_recaptcha_settings'
    );

    add_settings_field(
        'site_key',
        __('Site key', 'acf-recaptcha'),
        'site_key_render',
        'acf_recaptcha_settings',
        'acf_recaptcha_settings_section'
    );

    add_settings_field(
        'secret_key',
        __('Secret key', 'acf-recaptcha'),
        'secret_key_render',
        'acf_recaptcha_settings',
        'acf_recaptcha_settings_section'
    );

}

add_action('admin_init', 'acf_recaptcha_settings_init');


/**
 * This action enqueues additional CSS and JS for the settings page.
 *
 * @type    action 'admin_enqueue_scripts'
 * @since   1.3.0
 */
function acf_recaptcha_settings_enqueue_scripts()
{
    $dir = plugin_dir_url(ACF_RECAPTCHA_ABSPATH);

    // Adds CSS for the settings page.
    wp_register_style('acf-recaptcha-settings-page', "${dir}/css/settings-page.css");
    wp_enqueue_style('acf-recaptcha-settings-page');
}

add_action('admin_enqueue_scripts', 'acf_recaptcha_settings_enqueue_scripts');


/**
 * This filter appends a settings link for this plugin.
 *
 * @type    filter 'plugin_action_links_advanced-custom-fields-recaptcha-field/acf-recaptcha.php'
 * @since   1.3.0
 *
 * @param array
 * @return array
 */
function acf_recaptcha_add_settings_link($links)
{
    $settings_link = sprintf('<a href="%s">%s</a>', admin_url('/edit.php?post_type=acf-field-group&page=acf-recaptcha'), __('Settings', 'acf-recaptcha'));
    array_push($links, $settings_link);
    return $links;
}

add_filter('plugin_action_links_advanced-custom-fields-recaptcha-field/acf-recaptcha.php', 'acf_recaptcha_add_settings_link');


function site_key_render()
{
    $options = (array) get_option('acf_recaptcha');
    ?>
    <input type="text" class="regular-text code" name="acf_recaptcha[site_key]" value="<?php echo $options['site_key']; ?>">
    <?php
}


function secret_key_render()
{
    $options = (array) get_option('acf_recaptcha');
    ?>
    <input type="text" class="regular-text code" name="acf_recaptcha[secret_key]" value="<?php echo $options['secret_key']; ?>">
    <?php
}


function acf_recaptcha_settings_section_callback()
{
    ?>
    <p class="description">
        <?php echo __("These keys will be used if you don't provide them to the field itself."); ?>
        <?php $link = '<a href="' . admin_url("/edit.php?post_type=acf-field-group") .'">' . __('ACF field groups') . '</a>'; ?>
        <?php printf(__("You can override the keys for individual fields in your %s."), $link); ?>
    </p>
    <p class="description">
        <?php $link = '<a href="https://www.google.com/recaptcha/admin" target="_blank">' . __('Google reCAPTCHA Dashboard') . '</a>'; ?>
        <?php printf(__('Go to the %s to generate your keys.'), $link); ?>
    </p>
    <?php
}


function acf_recaptcha_options_page()
{
    $plugin_data = get_plugin_data(ACF_RECAPTCHA_ABSPATH);

    ?>
    <div class="wrap">
        <form action="options.php" method="post">
            <div class="acf-recaptcha-field-group-message acf-recaptcha-settings-info-box">
                <div class="message-img">
                    <img src="https://raw.githubusercontent.com/irvinlim/acf-recaptcha/master/assets/icon-128x128.png">
                </div>
                <div class="message-text">
                    <h1><?php echo __('ACF reCAPTCHA', 'acf-recaptcha'); ?></h1>
                    <p class="description">
                        <?php echo __('Advanced Custom Fields plugin for adding Google reCAPTCHA on frontend forms'); ?>
                    </p>
                    <p>
                        <strong><?php echo __('Plugin Homepage'); ?></strong>: <a href="https://github.com/irvinlim/acf-recaptcha">GitHub</a><br>
                        <strong><?php echo __('Plugin Author'); ?></strong>: <a href="https://github.com/irvinlim">Irvin Lim</a><br>
                        <strong><?php echo __('Plugin Version'); ?></strong>: <?php echo $plugin_data['Version']; ?>
                    </p>
                </div>
            </div>

            <h1><?php echo __('Settings', 'acf-recaptcha'); ?></h1>
            <p>
                <?php echo __('Configure global settings for ACF reCAPTCHA here, which will apply across all reCAPTCHA fields on your site.'); ?>
            </p>
            <?php settings_fields('acf_recaptcha_settings'); ?>
            <?php do_settings_sections('acf_recaptcha_settings'); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}


