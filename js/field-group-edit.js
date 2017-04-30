(function($) {
    $(document).ready(function() {
        var $fieldSettings = $("#acf-field-group-fields").find("[data-type=recaptcha]");

        // Hide 'choices' field settings row.
        $fieldSettings.find("tr.acf-field[data-name=choices][data-setting=recaptcha]").hide();

        // Set 'data-type' of the field to be 'radio', just for this page.
        // Allows other fields to apply conditional logic to this field.
        $fieldSettings.attr("data-type", "radio");
    });
})(jQuery);
