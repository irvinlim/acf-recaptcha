(function($) {
    if (typeof acf === 'undefined' || typeof acf.conditional_logic === 'undefined') {
        return;
    }

    var _super = $.extend({}, acf.conditional_logic);

    acf.conditional_logic = acf.conditional_logic.extend({
        calculate: function(rule, $trigger, $target) {
            var type = $trigger.data('type');

            // Return true if we are dealing with recaptcha field and it has been checked.
            if (type === 'recaptcha') {
                return $trigger.find('input[type=hidden]').val().length > 0;
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
