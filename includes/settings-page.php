<?php
add_action('admin_menu', 'acf_recaptcha_add_admin_menu', 30);
add_action('admin_init', 'acf_recaptcha_settings_init');


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
    ?>
    <div class="wrap">
        <form action="options.php" method="post">
            <h1><?php echo __('ACF reCAPTCHA Settings', 'acf-recaptcha'); ?></h1>
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


function acf_recaptcha_add_settings_link($links)
{
    $settings_link = '<a href="options-general.php?page=acf-recaptcha">' . __('Settings', 'acf-recaptcha') . '</a>';
    array_push($links, $settings_link);
    return $links;
}

add_filter("plugin_action_links_advanced-custom-fields-recaptcha-field/acf-recaptcha.php", "acf_recaptcha_add_settings_link");
