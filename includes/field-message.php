<?php

/**
 * Renders a customized message to be displayed in the field settings when managing field groups in wp-admin.
 *
 * @return string
 */
function render_field_message() {
    ob_start();

    ?>
    <div class="acf-recaptcha-field-group-message">
        <div class="message-img">
            <img src="https://raw.githubusercontent.com/irvinlim/acf-recaptcha/master/assets/icon-128x128.png">
        </div>

        <div class="message-text">
            <h1>ACF reCAPTCHA</h1>
            <p>
                <strong><?php echo __('Instructions', 'acf-recaptcha'); ?></strong>:
                <?php echo __('Go to the <a href="https://www.google.com/recaptcha/admin">Google reCAPTCHA Dashboard</a> to generate your Site Key and Secret Key.', 'acf-recaptcha'); ?>
            </p>
            <p>
                <?php echo __('To protect this field group from spam, you must turn on <strong>"ACF reCAPTCHA Protection"</strong> under the settings at the bottom of the page, and specify the field group ID explicitly in your frontend form code.', 'acf-recaptcha'); ?>
                <?php echo __('Read more <a href="https://github.com/irvinlim/acf-recaptcha#acf-recaptcha-protection">here</a>.', 'acf-recaptcha'); ?>
            </p>
        </div>
    </div>
    <?php

    return ob_get_clean();
}
