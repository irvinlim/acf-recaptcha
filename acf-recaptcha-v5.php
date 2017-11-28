<?php

// Load Google's reCaptcha PHP API.
require("lib/autoload.php");

// Local custom RequestMethod.
require('includes/classes/WPRemoteRequestMethod.php');

class acf_field_recaptcha extends acf_field {
    public $settings = array();

    function __construct() {
        $settings = (array) get_option('acf_recaptcha');
        $this->settings['site_key'] = $settings['site_key'];
        $this->settings['secret_key'] = $settings['secret_key'];

        /**
         * Unique identifier for the field type.
         */
        $this->name = 'recaptcha';

        /**
         * Label for the field type, shown when editing a field group.
         */
        $this->label = __('reCAPTCHA', 'acf-recaptcha');

        /**
         * Category for the field type, shown when editing a field group.
         */
        $this->category = 'Custom';

        /**
         * Array of default settings which are merged into the field object.
         */

        $this->defaults = array(
            're_theme' => 'light',
            're_type' => 'image',
            're_size' => 'normal',
        );

        // Adds a filter to validate forms with reCAPTCHA protection switched on.
        add_filter('acf/validate_save_post', array($this, 'validate_save_recaptcha_post'), 10, 0);

        // Adds an action to append a field group toggle to enable reCAPTCHA protection for the field group.
        add_action('acf/render_field_group_settings', array($this, 'render_field_group_recaptcha_flag_setting'), 10, 1);


        parent::__construct();

    }

    /**
     * Renders additional field settings for ACF reCAPTCHA field type.
     *
     * @type    action 'acf/render_field/type=recaptcha'
     * @date    10/07/2015
     * @since   1.0.0
     *
     * @param $field array      Array of field settings. Pass to {@link acf_render_field_setting}.
     */
    function render_field_settings($field) {

        $default_keys_link = sprintf('<a href="%s">%s</a>', admin_url('/edit.php?post_type=acf-field-group&page=acf-recaptcha'), __('default keys'));

        acf_render_field_setting($field, array(
            'label' => __('Notice', 'acf-recaptcha'),
            'message' => render_field_message(),
            'type' => 'message',
            'new_lines' => false
        ));

        acf_render_field_setting($field, array(
            'label' => __('Site Key', 'acf-recaptcha'),
            'instructions' =>
                __('Enter your site key from Google reCAPTCHA.', 'acf-recaptcha') .
                '<br>' .
                sprintf(__('If left blank, the %s will be used.', 'acf-recaptcha'), $default_keys_link),
            'name' => 'site_key',
            'required' => empty($this->settings['site_key']),
            'class' => 'code',
        ));

        acf_render_field_setting($field, array(
            'label' => __('Secret Key', 'acf-recaptcha'),
            'instructions' =>
                __('Enter your secret key from Google reCAPTCHA.', 'acf-recaptcha') .
                '<br>' .
                sprintf(__('If left blank, the %s will be used.', 'acf-recaptcha'), $default_keys_link),
            'name' => 'secret_key',
            'required' => empty($this->settings['secret_key']),
            'class' => 'code',
        ));

        acf_render_field_setting($field, array(
            'label' => __('Theme', 'acf-recaptcha'),
            'type' => 'radio',
            'name' => 're_theme',
            'layout' => 'horizontal',
            'choices' => array(
                'light' => __('light'),
                'dark' => __('dark'),
            ),
        ));

        acf_render_field_setting($field, array(
            'label' => __('Type', 'acf-recaptcha'),
            'type' => 'radio',
            'name' => 're_type',
            'layout' => 'horizontal',
            'choices' => array(
                'image' => __('image'),
                'audio' => __('audio'),
            ),
        ));

        acf_render_field_setting($field, array(
            'label' => __('Size', 'acf-recaptcha'),
            'type' => 'radio',
            'name' => 're_size',
            'layout' => 'horizontal',
            'choices' => array(
                'normal' => __('normal'),
                'compact' => __('compact'),
            ),
        ));
    }

    /**
     * Renders the field on front end forms.
     *
     * @type    action 'acf/render_field/type=recaptcha'
     * @date    10/07/2015
     * @since   1.0.0
     *
     * @param $field array      Array of field settings.
     */
    function render_field($field) {
        if (is_admin()) {
            return;
        }

        // Use the keys from the field options first.
        $site_key = $field['site_key'];
        $secret_key = $field['secret_key'];

        // Fall back on keys from settings.
        if (empty($site_key) || empty($secret_key)) {
            $site_key = $this->settings['site_key'];
            $secret_key = $this->settings['secret_key'];
        }

        // If we don't have both keys, then show an error message.
        if (empty($site_key) || empty($secret_key)) {
            echo "Please enter your site key and secret key first.";
            return;
        }

        ?>
        <div class="g-recaptcha" data-sitekey="<?php echo $site_key; ?>"
                data-theme="<?php echo $field['re_theme']; ?>" data-type="<?php echo $field['re_type']; ?>"
                data-size="<?php echo $field['re_size']; ?>"></div>
        <input type="hidden" name="<?php echo $field['name'] ?>">
        <?php
    }

    /**
     * Enqueues CSS and JS for ACF reCAPTCHA.
     *
     * @type    action
     * @date    10/07/2015
     * @since   1.0.0
     */
    function input_admin_enqueue_scripts() {

        $dir = plugin_dir_url(__FILE__);

        // Register necessary scripts.
        wp_register_script('acf-recaptcha-input', "{$dir}js/input.js", array("acf-input"));
        wp_register_script('acf-recaptcha-field-group', "{$dir}js/field-group.js", array("acf-field-group"));
        wp_register_script('acf-recaptcha-grecaptcha-api', 'https://www.google.com/recaptcha/api.js?onload=recaptcha_onload&render=explicit#asyncdefer', array("acf-recaptcha-input"));

        // Register styles.
        wp_register_style('acf-recaptcha-field-group', "${dir}css/field-group.css");

        // Enqueue frontend scripts for acf-recaptcha field.
        if (!is_admin()) {
            // Enqueue input script for frontend acf-recaptcha field validation and conditional logic.
            wp_enqueue_script('acf-recaptcha-input');

            // Enqueue Google's reCAPTCHA API script in the frontend.
            wp_enqueue_script('acf-recaptcha-grecaptcha-api');
        }

        // Enqueue 'field-group' script for editing field group.
        if (is_admin()) {
            $screen = get_current_screen();

            // Only enqueue script for 'edit' and 'post-new' when `post-type` is 'acf-field-group'.
            if ($screen->post_type == 'acf-field-group') {
                wp_enqueue_script('acf-recaptcha-field-group');
                wp_enqueue_style('acf-recaptcha-field-group');
            }
        }

    }

    /**
     * This filter is used to perform validation on the value prior to saving.
     * All values are validated regardless of the field's required setting. This allows you to validate and return
     * messages to the user if the value is not correct.
     *
     * @type    filter 'acf/validate_value/type=recaptcha'
     * @date    10/07/2015
     * @since   1.0.0
     *
     * @param $valid boolean    Validation status based on the value and the field's required setting.
     * @param $value mixed      The $_POST value.
     * @param $field array      The field array holding all the field options.
     * @param $input string     The corresponding input name for $_POST value.
     * @return boolean          Return true if the value is valid.
     */
    function validate_value($valid, $value, $field, $input) {
        // Only process AJAX client-side validation requests.
        if (!is_admin()) {
            return $valid;
        }

        // All reCAPTCHA fields should be required by default.
        if (!strlen($value)) {
            return __('Please click the checkbox.', 'acf-recaptcha');
        }

        // When the field expires, we change the input's value to 'expired', so that we can show the correct validation error.
        if (strtolower(trim($value)) == 'expired') {
            return __('Verification expired. Please click the checkbox again.', 'acf-recaptcha');
        }

        return $valid;
    }

    /**
     * Performs custom server-side validation for forms marked with the 'recaptcha' flag in the field group or form
     * settings. Makes sure that such forms have at least one reCAPTCHA field, and that all reCAPTCHA fields have a
     * valid value (as validated by the Google reCAPTCHA PHP API).
     *
     * This filter is invoked in acf_form_head(), and will give rise to the "Validation failed" error page after post
     * submission.
     *
     * @type    filter 'acf/validate_save_post' 10
     * @date    08/07/2017
     * @since   1.2.0
     */
    function validate_save_recaptcha_post() {
        // Don't handle AJAX validation here.
        if (is_admin()) {
            return;
        }

        // Decrypt _acfform early (at validation stage) because we need form args.
        $form = @json_decode(acf_decrypt($_POST['_acfform']), true);

        // Determine if the form has the 'recaptcha' flag.
        $form_requires_recaptcha = $this->check_if_form_requires_recaptcha($form);

        // Determine if the form contains any 'recaptcha' field types.
        $form_contains_recaptcha = $this->check_if_form_has_recaptcha_field($form);

        // Don't handle forms without the flag or any recaptcha fields.
        if (!$form_requires_recaptcha && !$form_contains_recaptcha) {
            return;
        }

        // Validate the reCAPTCHA-protected form.
        if (!$this->validate_recaptcha_form($_POST['acf'])) {
            acf_add_validation_error('', __('reCAPTCHA value is invalid or expired. Please try again.', 'acf-recaptcha'));
        }

        // Remove all reCAPTCHA fields from $_POST data prior to saving the post to the database.
        $this->remove_recaptcha_fields_from_postdata();
    }

    /**
     * Adds a third-party field group setting in the Field Group edit page.
     * Allows users to toggle whether a field group should require reCAPTCHA when the post is submitted.
     *
     * @type    action 'acf/render_field_group_settings' 10
     * @date    08/07/2017
     * @since   1.2.0
     *
     * @param  $field_group array   Field group settings for current field group being edited.
     */
    function render_field_group_recaptcha_flag_setting($field_group) {
        acf_render_field_wrap(array(
            'label' => __('ACF reCAPTCHA Protection', 'acf-recaptcha'),
            'instructions' =>
                __('Switch on if this field group should be protected by reCAPTCHA.', 'acf-recaptcha') .
                '<br /><br />' .
                '<strong>' . __('Note: ', 'acf-recaptcha') . '</strong>' .
                __('If the field group has no reCAPTCHA fields, turn this off because your form will not validate. Turning this on will prevent bots from bypassing reCAPTCHA to submit forms without reCAPTCHA values. ', 'acf-recaptcha') .
                __('Read more <a href="https://github.com/irvinlim/acf-recaptcha#acf-recaptcha-protection">here</a>.', 'acf-recaptcha') .
                '<br /><br />' .
                __('Alternatively, use <code>"recaptcha" => true</code> in <code>acf_form()</code> to protect the form instead of the field group.', 'acf-recaptcha'),
            'type' => 'true_false',
            'name' => 'recaptcha',
            'prefix' => 'acf_field_group',
            'value' => $field_group['recaptcha'],
            'ui' => 1,
            'ui_on_text' => __('On', 'acf-recaptcha'),
            'ui_off_text' => __('Off', 'acf-recaptcha'),
        ));
    }

    /**
     * Validates a reCAPTCHA-protected form. This means that there must be at least one reCAPTCHA field in the posted
     * form data, and all reCAPTCHA values posted must be valid.
     *
     * @date    08/07/2017
     * @since   1.2.0
     *
     * @param $form_values array    Array of form values submitted, retrieved from $_POST.
     * @return bool                 Returns true if the above conditions hold true.
     */
    function validate_recaptcha_form($form_values) {
        // Maintain a flag for whether we have found a reCAPTCHA field in $_POST.
        $has_found_recaptcha = false;

        // Form must fail if no fields are even present.
        if (empty($form_values)) {
            return false;
        }

        // Validate the value of every reCAPTCHA field in $_POST.
        foreach ($form_values as $field_key => $value) {
            $field = acf_get_field($field_key);

            if ($field['type'] == 'recaptcha') {
                $has_found_recaptcha = true;

                if (!$this->validate_recaptcha_value($field, $value)) {
                    return false;
                }
            }
        }

        // Fail validation if we didn't find any reCAPTCHA fields.
        if (!$has_found_recaptcha) {
            return false;
        }

        return true;
    }

    /**
     * Checks if any fields from $_POST is a recaptcha field type.
     *
     * @date    01/11/2017
     * @since   1.2.1
     */
    function check_if_form_has_recaptcha_field() {
        foreach ($_POST['acf'] as $field_key => $value) {
            $field = acf_get_field($field_key);

            if ($field['type'] === 'recaptcha') {
                return true;
            }
        }

        return false;
    }

    /**
     * Unsets all recaptcha fields from $_POST data prior to saving the form.
     *
     * @date    08/07/2017
     * @since   1.2.0
     */
    function remove_recaptcha_fields_from_postdata() {
        foreach ($_POST['acf'] as $field_key => $value) {
            $field = acf_get_field($field_key);

            if ($field['type'] === 'recaptcha') {
                unset($_POST['acf'][$field_key]);
            }
        }
    }

    /**
     * Validates a given reCAPTCHA value with the Google reCAPTCHA PHP API.
     *
     * This method is not idempotent - Google's API will only validate a value once, and fail subsequent validations
     * with the same reCAPTCHA value.
     *
     * @date    08/07/2017
     * @since   1.2.0
     *
     * @param $field array      Array of field settings for a reCAPTCHA field.
     * @param $value string     Posted reCAPTCHA value.
     * @return boolean          Returns true if the value is valid.
     */
    function validate_recaptcha_value($field, $value) {
        // Fallback on secret key from settings.
        $secret_key = $field['secret_key'] ?: $this->settings['secret_key'];

        // Prepare the API.
        $api = new \ReCaptcha\ReCaptcha($secret_key, new \ReCaptcha\RequestMethod\WPRemoteRequestMethod());

        // Verify the value, with the IP address of the visitor (if server is not behind a proxy).
        $response = $api->verify($value, $_SERVER['REMOTE_ADDR']);

        return $response->isSuccess();
    }

    /**
     * Checks for the 'recaptcha' flag prior to saving a form.
     *
     * This can be set either at the form or field group level, and will attempt to coalesce the value for the
     * 'recaptcha' flag into the form at the point of invocation of this filter.
     *
     * The only exception (discovered so far) is using `acf_form()` in conjunction with Location Rules, which will
     * render front end field groups without specifying the field group ID explicitly. This is a known limitation
     * and hence ACF reCAPTCHA users should avoid using this.
     *
     * @date    08/07/2017
     * @since   1.2.0
     *
     * @param $form array   Form settings, i.e. $args in acf_form($args)
     * @return boolean      Returns true if the form has the 'recaptcha' flag set.
     */
    function check_if_form_requires_recaptcha($form) {
        /*
         * First check if the flag is set in the form $args.
         *
         * This method will always work, regardless which of the two methods used:
         *   - `acf_form($args)`
         *   - `acf_register_form($args)`
         */

        if (isset($form['recaptcha']) && ($form['recaptcha'] === true || $form['recaptcha'] === 'true')) {
            return true;
        }



        /*
         * If not, determine if any of the field groups has the 'recaptcha' flag set.
         *
         * NOTE: This will only work if the field group ID is passed to `$args['field_groups']` explicitly in `acf_form($args)`.
         * This will not work for field groups set using location rules, and rendered using `acf_form()` (without arguments).
         */

        if (!empty($form['field_groups'])) {
            foreach ($form['field_groups'] as $group_name) {
                $group = acf_get_field_group($group_name);

                if (isset($group['recaptcha']) && $group['recaptcha'] === true) {
                    return true;
                }
            }
        }

        return false;
    }

}

new acf_field_recaptcha();
