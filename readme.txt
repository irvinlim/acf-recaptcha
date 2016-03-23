=== ACF reCAPTCHA ===
Contributors: okdek88, irvinlim
Tags: acf, field, recaptcha, captcha, form, frontend
Donate link: http://unaiyecora.com
Requires at least: 3.0.1
Tested up to: 4.4.1
Stable tag: 1.2.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Reduce spam with reCAPTCHA on your Advanced Custom Fields frontend forms. Currently only supports ACF v5.

== Description ==

**Note:** This plugin is based on the *Advanced Custom Fields: reCAPTCHA Field* plugin by Irvin Lim.
Since he stopped updating and accepting pull requests, I decided to make this one with the original pull requests and I will be updating it with the help of the community.
If you want to colaborate, you can send your own pull request [here](https://github.com/UnaiYecora/acf-recaptcha).

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
= 1.2.1 =
* File end newlines removed

= 1.2.0 =
* Fixed JS error when there are no errors to loop through (by Sam Scholfield)
* More render parameters added (by puntonero)

= 1.0.2 =
* Prevent reCAPTCHA from showing up on backend

= 1.0.1 =
* Support translation of error messages
* Fix for reCAPTCHA API not being enqueued in certain themes

= 1.0.0 =
* Initial version
