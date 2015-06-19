(function($){

	var _root = this;
	var $el = null;
	var fieldname = "";
	
	
	if( typeof acf.add_action !== 'undefined' ) {
	
		acf.add_action('ready append', function( $body ){
			_root.$el = acf.get_field({ type : 'recaptcha'}, $body);
			_root.fieldname = _root.$el.attr("data-key");
		});
		
		acf.add_filter('validation_complete', function( json, $form ){

			var validated_error = false;

			$.each(json.errors, function(index, val) {
				if (val.input == "acf[" + _root.fieldname + "]") {
					validated_error = true;
					grecaptcha.reset();
					json.errors[index].message = "Please click the checkbox.";
				}
			});

			if (!validated_error) _root.$el.find("input[type=hidden]").remove();

			return json;
					
		});
		
		
	}


})(jQuery);

function acf_captcha_called(input) {
	jQuery("[data-type=recaptcha]").find("input[type=hidden]").val(input).change();
}