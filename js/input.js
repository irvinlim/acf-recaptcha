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

    var _root = this;
    var $el = null;
    var fieldname = "";

    if (typeof acf.add_action !== 'undefined') {

        acf.add_action('ready append', function($body) {
            _root.$el = acf.get_field({ type: 'recaptcha' }, $body);

            if (_root.$el) {
                _root.fieldname = _root.$el.attr("data-key");
            }
        });

        acf.add_filter('validation_complete', function(json, $form) {

            if (typeof(grecaptcha) === "undefined") {
                return json;
            }

            if (!_root.$el) {
                return json;
            }

            var validated_error = false;

            if (json.errors) {
                $.each(json.errors, function(index, val) {
                    if (val.input === "acf[" + _root.fieldname + "]") {
                        validated_error = true;
                        grecaptcha.reset();
                        json.errors[index].message = acf.l10n.recaptcha.error;
                    }
                });
            }

            if (!validated_error) {
                _root.$el.find("input[type=hidden]").remove();
            }

            return json;

        });

    }

})(jQuery);

function acf_captcha_called(input) {
    jQuery("[data-type=recaptcha]").find("input[type=hidden]").val(input).change();
}
