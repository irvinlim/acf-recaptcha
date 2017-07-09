<?php

// Load Google's ReCaptcha library
require("lib/autoload.php");

// Local custom RequestMethod
require('WP_requestmethod.php');

class acf_field_recaptcha extends acf_field {


    /*
    *  __construct
    *
    *  This function will setup the field type data
    *
    *  @type	function
    *  @date	5/03/2014
    *  @since	5.0.0
    *
    *  @param	n/a
    *  @return	n/a
    */

    function __construct() {

        /**
         *  name (string) Single word, no spaces. Underscores allowed
         */

        $this->name = 'recaptcha';


        /**
         * label (string) Multiple words, can include spaces, visible when selecting a field type
         */

        $this->label = __('reCAPTCHA', 'acf-recaptcha');


        /**
         * category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
         */

        $this->category = 'Custom';


        /**
         * defaults (array) Array of default settings which are merged into the field object. These are used later in settings
         */

        $this->defaults = array(
            'site_key' => '',
            'secret_key' => '',
            're_theme' => 'light',
            're_type' => 'image',
            're_size' => 'normal',
        );

        $this->l10n = array(
            'error' => __('Please click the checkbox.', 'acf-recaptcha'),
        );

        /**
         * Adds a filter to validate forms with the 'recaptcha' flag switched on.
         */
        add_filter('acf/validate_save_post', array($this, 'validate_save_recaptcha_post'), 10, 0);

        /**
         * Adds an action to append a 'recaptcha' flag when editing a field group.
         */
        add_action('acf/render_field_group_settings', array($this, 'render_field_group_recaptcha_flag_setting'), 10, 1);


        parent::__construct();

    }


    /*
    *  render_field_settings()
    *
    *  Create extra settings for your field. These are visible when editing a field
    *
    *  @type	action
    *  @since	3.6
    *  @date	23/01/13
    *
    *  @param	$field (array) the $field being edited
    *  @return	n/a
    */

    function render_field_settings($field) {

        /*
        *  acf_render_field_setting
        *
        *  This function will create a setting for your field. Simply pass the $field parameter and an array of field settings.
        *  The array of settings does not require a `value` or `prefix`; These settings are found from the $field array.
        *
        *  More than one setting can be added by copy/paste the above code.
        *  Please note that you must also have a matching $defaults value for the field name (font_size)
        */

        acf_render_field_setting($field, array(
            'label' => __('Site Key', 'acf-recaptcha'),
            'instructions' => __('Enter your site key from Google reCAPTCHA.', 'acf-recaptcha'),
            'name' => 'site_key',
            'required' => true,
        ));

        acf_render_field_setting($field, array(
            'label' => __('Secret Key', 'acf-recaptcha'),
            'instructions' => __('Enter your secret key from Google reCAPTCHA.', 'acf-recaptcha'),
            'name' => 'secret_key',
            'required' => true,
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


    /*
    *  render_field()
    *
    *  Create the HTML interface for your field
    *
    *  @param	$field (array) the $field being rendered
    *
    *  @type	action
    *  @since	3.6
    *  @date	23/01/13
    *
    *  @param	$field (array) the $field being edited
    *  @return	n/a
    */

    function render_field($field) {
        if (is_admin()) {
            return;
        }

        if ($field['site_key'] && $field['secret_key']): ?>
            <div class="g-recaptcha" data-sitekey="<?php echo $field['site_key']; ?>"
                 data-theme="<?php echo $field['re_theme']; ?>" data-type="<?php echo $field['re_type']; ?>"
                 data-size="<?php echo $field['re_size']; ?>" data-callback="acf_captcha_called"></div>
            <input type="hidden" name="<?php echo $field['name'] ?>">
        <?php else :
            echo "Please enter your site key and secret key first.";
        endif;
    }


    /*
    *  input_admin_enqueue_scripts()
    *
    *  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
    *  Use this action to add CSS + JavaScript to assist your render_field() action.
    *
    *  @type	action (admin_enqueue_scripts)
    *  @since	3.6
    *  @date	23/01/13
    *
    *  @param	n/a
    *  @return	n/a
    */


    function input_admin_enqueue_scripts() {

        $dir = plugin_dir_url(__FILE__);

        // Register necessary scripts.
        wp_register_script('acf-recaptcha-input', "{$dir}js/input.js", array("acf-input"));
        wp_register_script('acf-recaptcha-field-group', "{$dir}js/field-group.js", array("acf-field-group"));
        wp_register_script('acf-recaptcha-grecaptcha-api', 'https://www.google.com/recaptcha/api.js#asyncdefer', array("acf-recaptcha-input"));

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
            }
        }

    }


    /*
    *  input_admin_head()
    *
    *  This action is called in the admin_head action on the edit screen where your field is created.
    *  Use this action to add CSS and JavaScript to assist your render_field() action.
    *
    *  @type	action (admin_head)
    *  @since	3.6
    *  @date	23/01/13
    *
    *  @param	n/a
    *  @return	n/a
    */

    function input_admin_head() {

    }


    /*
    *  validate_value()
    *
    *  This filter is used to perform validation on the value prior to saving.
    *  All values are validated regardless of the field's required setting. This allows you to validate and return
    *  messages to the user if the value is not correct
    *
    *  @type	filter
    *  @date	11/02/2014
    *  @since	5.0.0
    *
    *  @param	$valid (boolean) validation status based on the value and the field's required setting
    *  @param	$value (mixed) the $_POST value
    *  @param	$field (array) the field array holding all the field options
    *  @param	$input (string) the corresponding input name for $_POST value
    *  @return	$valid
    */
    function validate_value($valid, $value, $field, $input) {
        if (!strlen($value)) {
            return false;
        }

        $api = new \ReCaptcha\ReCaptcha($field['secret_key'], new \ReCaptcha\RequestMethod\WP_remote());
        $response = $api->verify($value, $_SERVER['REMOTE_ADDR']);

        if ($response->isSuccess()) {
            return $valid;
        }

        $errors = $response->getErrorCodes();

        if (empty($errors)) {
            return $valid;
        }

        $valid = 'Invalid reCaptcha value ' . $value . ' response isSuccess(): ' .
            ($response->isSuccess() ? 'true' : 'false') . ' errors: ' . json_encode($errors);
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

        // Don't handle forms without the flag set.
        if (!$form_requires_recaptcha) {
            return;
        }

        // TODO: Find all fields with type=recaptcha in $_POST, and call Google's reCAPTCHA API to validate the value.

        // Fail if any of the fields whose type is recaptcha have an invalid value, or if we didn't find any such fields.
        acf_add_validation_error('', __('reCAPTCHA value is invalid.', 'acf-recaptcha'));
    }

    /**
     * Adds a third-party field group setting in the Field Group edit page.
     * Allows users to toggle whether a field group should require reCAPTCHA
     * when the post is submitted.
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
                __('If you use this option, you have to explicitly specify the field group ID when using ' .
                    '<code>acf_form</code>. ', 'acf-recaptcha') .
                __('Using location rules will not ensure your front end form will be protected.', 'acf-recaptcha'),
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

        if (isset($form['recaptcha']) && $form['recaptcha'] === true) {
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
