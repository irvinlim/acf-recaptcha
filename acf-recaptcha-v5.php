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


}


new acf_field_recaptcha();
