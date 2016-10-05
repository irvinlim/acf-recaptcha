=== Advanced Custom Fields: reCAPTCHA Field ===
Contributors: irvinlim
Tags: acf, field, recaptcha, captcha, form, frontend
Donate link: https://irvinlim.com/contact?subject=Donations
Requires at least: 3.0.1
Tested up to: 4.6.1
Stable tag: 1.0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Reduce spam with reCAPTCHA on your Advanced Custom Fields frontend forms. Currently only supports ACF v5.

== Description ==
This is an add-on for the [Advanced Custom Fields](http://wordpress.org/extend/plugins/advanced-custom-fields/) WordPress plugin. This field allows you to create a reCAPTCHA field for frontend forms.

You can find more information on Google's No-CAPTCHA reCAPTCHA [here](https://www.google.com/recaptcha/intro/index.html).

Requires cURL to be enabled on your server.

= Compatibility =

This ACF field type is compatible with:

* ACF 5

== Installation ==
If you haven't already, [generate your API keys first](https://www.google.com/recaptcha/admin). You will need the **site key** and **secret key**.

This plugin requires cURL to be enabled on your server, as it makes use of Google's [PHP library](https://github.com/google/recaptcha).

1. Copy the `acf-recaptcha` folder into your `wp-content/plugins` folder.
2. Activate the plugin via the plugins admin page.
3. Create a new field via ACF and select the reCAPTCHA type (grouped under Custom)
4. Enter your site key and secret key into the fields options.

That's it! It should work out of the box, as long as your API keys are correct and valid for your domain.

== Screenshots ==
1. Frontend look

== Changelog ==
= 1.0.3 =
* Added fixes for some issues

= 1.0.2 =
* Prevent reCAPTCHA from showing up on backend

= 1.0.1 =
* Support translation of error messages
* Fix for reCAPTCHA API not being enqueued in certain themes

= 1.0.0 =
* Initial version