=== Advanced Custom Fields: reCAPTCHA Field ===
Contributors: irvinlim
Tags: acf, field, recaptcha, captcha, form, frontend
Donate link: https://irvinlim.com/contact/
Requires at least: 3.0.1
Tested up to: 4.8
Stable tag: 1.1.1
License: MIT
License URI: https://github.com/irvinlim/acf-recaptcha/blob/master/LICENSE

Reduce spam with reCAPTCHA on your Advanced Custom Fields frontend forms. Currently only supports ACF v5.

== Description ==

ACF reCAPTCHA brings Google reCAPTCHA to [Advanced Custom Fields](http://wordpress.org/plugins/advanced-custom-fields/), to be used on frontend forms.

Features include:

* Complete frontend and backend validation to prevent spambots from bypassing reCAPTCHA requirement
* ACF-compliant frontend validation, which prompts the user if the reCAPTCHA has not been passed
* Customizable reCAPTCHA options, including theme (light/dark), type (image/audio) and size (normal/compact)
* Compatible with ACF Conditional Logic feature, which allows hiding/showing certain fields until the reCAPTCHA has been passed

= ACF Frontend Forms =

ACF reCAPTCHA is meant to be used **only on frontend forms**, in order to protect them from spam by spambots.

To create a frontend form programmatically, please view the official documentation on the ACF website [here](https://www.advancedcustomfields.com/resources/create-a-front-end-form/).

Alternatively, you can set a fixed location for the field group to appear in, using custom location rules. For example, if you wish to create a contact page with ACF and ACF reCAPTCHA, you would set the location rule to match your "Contact Us" page, and the field groups will appear on the frontend.

For more information, view the official documentation on the ACF website [here](https://www.advancedcustomfields.com/resources/custom-location-rules/).

= ACF Compatibility =

ACF reCAPTCHA is only compatible with ACF v5 at the moment. If you urgently require/would like to help add support for v4, do reach out to me on the GitHub repository.

= GitHub =

[https://github.com/irvinlim/acf-recaptcha](https://github.com/irvinlim/acf-recaptcha)

== Installation ==
If you haven't already, [generate your API keys for Google reCAPTCHA](https://www.google.com/recaptcha/admin) first. You will need the **site key** and **secret key**.

1. Copy the `acf-recaptcha` folder into your `wp-content/plugins` folder.
2. Activate the plugin via the plugins admin page.
3. Create a new field via ACF and select the reCAPTCHA type (grouped under Custom)
4. Enter your site key and secret key into the fields options.

That's it! It should work out of the box, as long as your API keys are correct and valid for your domain.

== Screenshots ==
1. ACF reCAPTCHA customization in the ACF backend page. Enter your site and secret keys here.
2. Example frontend form with ACF reCAPTCHA and conditional logic applied to the text field.
3. Example frontend form with ACF reCAPTCHA that is solved. The conditional logic had hidden the previous field and displayed a new field here.

== Changelog ==
= 1.1.1 =
* Added an Upgrade Notice box for future important upgrade notices
* Make Google reCAPTCHA API JS load asynchronously to prevent possible race conditions

= 1.1 =
* Bump to version 1.1 (no changes since 1.0.8), as the plugin was not following semantic versioning earlier. Revamped the README and added a fresh new icon to celebrate! :)
* Summary of new features since 1.0:
    * Removed requirement for cURL to be used
    * Add support for ACF conditional logic
    * Numerous other bugfixes

= 1.0.8 =
* Fixes bug in not allowing Options Page to save.

= 1.0.7 =
* Fix regression caused in 1.0.6.

= 1.0.6 =
* Add support for ACF conditional logic.

= 1.0.5 =
* Uses `wp_remote_post` method for the reCAPTCHA POST request. This removes the need for cURL on your server.

= 1.0.4 =
* Fix WSOD errors

= 1.0.3 =
* Added fixes for some issues

= 1.0.2 =
* Prevent reCAPTCHA from showing up on backend

= 1.0.1 =
* Support translation of error messages
* Fix for reCAPTCHA API not being enqueued in certain themes

= 1.0 =
* Initial version
