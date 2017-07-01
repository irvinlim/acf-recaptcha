/* global grecaptcha */

(function($) {
    if (typeof acf === 'undefined' || typeof acf.conditional_logic === 'undefined') {
        return;
    }

    /** Superclass for acf.conditional_logic. */
    var _super = $.extend({}, acf.conditional_logic);

    acf.conditional_logic = $.extend(acf.conditional_logic, {

        /**
         * Calculates if a field should be conditionally displayed based on conditional logic rules, defined in
         * the ACF field group settings.
         *
         * Overrides ACF native method to include functionality for recaptcha field types.
         *
         * @since 1.0.6
         *
         * @override
         * @param {Object} rule - Rule that was configured in the ACF field group settings.
         * @param {string} rule.field - Target field whose value is to be matched against.
         * @param {string} rule.operator - Either "==" or "!=".
         * @param {string} rule.value - Value to match the field's value against, using the operator defined.
         * @param {jQuery} $trigger - Element that triggered a potential display change.
         * @param {jQuery} $target - Target element whose display is dependent on conditional logic.
         * @returns {boolean}   Returns true if the target should be displayed based on the trigger and conditional
         *                      logic rule for the target.
         */
        calculate: function(rule, $trigger, $target) {
            var type = $trigger.data('type');

            // Return true if we are dealing with recaptcha field and it has been checked.
            if (type === 'recaptcha') {
                var is_operator_equals = rule.operator === "==";
                var is_value_1 = parseInt(rule.value) === 1;
                var show_when_checked = is_operator_equals === is_value_1; // Treat as XNOR
                var is_field_checked = $trigger.find('input[type=hidden]').val().length > 0;

                return show_when_checked === is_field_checked; // Treat as XNOR
            }

            // Otherwise, fallback to native ACF calculate().
            return _super.calculate(rule, $trigger, $target);
        }

    });
})(jQuery);

(function($) {

    /** ACF field element. */
    var $el = null;

    /** Field name. */
    var fieldname = "";

    if (typeof acf.add_action !== 'undefined') {

        /**
         * When the recaptcha field is added to the front end form, we save the element and fieldname.
         */
        acf.add_action('ready append', function($body) {
            if ($el = acf.get_field({ type: 'recaptcha' }, $body)) {
                fieldname = $el.attr("data-key");
            }
        });

        /**
         * Handle validation for recaptcha field type.
         */
        acf.add_filter('validation_complete', function(json) {

            // Guard against no grecaptcha or recaptcha field.
            if (typeof(grecaptcha) === "undefined" || !$el) {
                return json;
            }

            var has_validation_error = false;

            // Set error message for any recaptcha field errors.
            if (json.errors) {
                $.each(json.errors, function(index, val) {
                    if (val.input === "acf[" + fieldname + "]") {
                        has_validation_error = true;
                        grecaptcha.reset();
                        json.errors[index].message = acf.l10n.recaptcha.error;
                    }
                });
            }

            // Removes the hidden field used by grecaptcha, in order to not save it as a field to the database.
            if (!has_validation_error) {
                $el.find("input[type=hidden]").remove();
            }

            return json;
        });

    }

})(jQuery);

/**
 * Callback function when the Google reCAPTCHA API is loaded.
 * Used to invoke grecaptcha.render() on the relevant fields.
 */
function recaptcha_onload() {
    (function($) {
        $.each(acf.get_fields('recaptcha'), function(idx, field) {
            // Find placeholder element in field div.
            var $placeholder = $(field).find('.g-recaptcha');

            // Call the render() method in Google reCAPTCHA's API.
            grecaptcha.render($placeholder[0], {
                /**
                 * Google reCAPTCHA site key.
                 */
                sitekey: $placeholder.data('sitekey'),

                /**
                 * Google reCAPTCHA customisation options.
                 */
                theme: $placeholder.data('theme'),
                type: $placeholder.data('type'),
                size: $placeholder.data('size'),

                /**
                 * Callback function invoked by Google reCAPTCHA API.
                 * Update the value in the field div to pass to ACF validator.
                 *
                 * @param captchaValue reCaptcha session value passed by callback.
                 */
                callback: function(captchaValue) {
                    $(field).find('input').val(captchaValue).change();
                }
            });
        });
    })(jQuery);
}
