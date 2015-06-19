# ACF reCAPTCHA Field

**[Advanced Custom Fields](http://www.advancedcustomfields.com/)** custom field type for Google's reCAPTCHA, to be used on frontend forms.

![Google reCAPTCHA]
(https://www.google.com/recaptcha/intro/images/hero-recaptcha-demo.gif)

Support for ACF v4 coming soon.

-----------------------

## Instructions

### Generating an API Key

If you haven't already, [generate your API keys first](https://www.google.com/recaptcha/admin). You will need the **site key** and **secret key**.

### Installation and Usage

This plugin requires cURL to be enabled on your server, as it makes use of Google's [PHP reCAPTCHA library](https://github.com/google/recaptcha).

1. Copy the `acf-recaptcha` folder into your `wp-content/plugins` folder
2. Activate the plugin via the plugins admin page
3. Create a new field via ACF and select the reCAPTCHA type (under Custom)
4. Enter you site key and secret key into ACF options.

That's it! It should work out of the box, as long as your API keys are correct and valid for your domain.

-----------------------

## About

Version 1.0

Written by Irvin Lim. If you encounter any issues, do open [one](https://github.com/irvinlim/acf-recaptcha/issues/new).

If you have questions, contact me [here](http://services.irvinlim.com/contact.php)!