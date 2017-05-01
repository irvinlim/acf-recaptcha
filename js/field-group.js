(function($) {

    /**
     * NOTE: This is pretty hack-ish to trick ACF into displaying recaptcha field types as an option for
     * Conditional Logic in the Field Group settings.
     *
     * Sets the 'data-type' of the field to be 'true_false', only on the Field Group Edit page.
     * Allows other fields to apply conditional logic to this field.
     */
    $(document).ready(function() {
        $("#acf-field-group-fields").find("[data-type=recaptcha]").attr("data-type", "true_false");
    });

    if (typeof acf !== 'undefined' && typeof acf.add_action !== 'undefined') {

        acf.add_action('change_field_type/type=recaptcha duplicate_field/type=recaptcha', function($el) {
            $el.attr("data-type", "true_false");
        });

    }

})(jQuery);
