# ACF reCAPTCHA Field

**[Advanced Custom Fields](http://www.advancedcustomfields.com/)** custom field type for Google's No-CAPTCHA reCAPTCHA, to be used on frontend forms.

![Google reCAPTCHA](https://www.google.com/recaptcha/intro/images/hero-recaptcha-demo.gif)

## About No-CAPTCHA reCAPTCHA

You can find more information on reCAPTCHA [here](https://www.google.com/recaptcha/intro/index.html). Google introduced a new form of CAPTCHA in 2013 that proved to be easier to solve by humans yet considerably harder for bots to bypass, by introducing a simple checkbox which is all that's needed for humans to pass the verification.

## Instructions

### Generating an API Key

If you haven't already, [generate your API keys first](https://www.google.com/recaptcha/admin). You will need the **site key** and **secret key**.

### Installation and Usage

1. Copy the `acf-recaptcha` folder into your `wp-content/plugins` folder
2. Activate the plugin via the plugins admin page
3. Create a new field via ACF and select the reCAPTCHA type (under Custom)
4. Enter you site key and secret key into ACF options.

That's it! It should work out of the box, as long as your API keys are correct and valid for your domain.

### ACF PHP API

If you are using ACF via the [PHP API](https://www.advancedcustomfields.com/resources/register-fields-via-php/), this is an example to add the reCAPTCHA field:

```php
acf_add_local_field(array(
  'key' => 'field_recaptcha',
  'name' => 'recaptcha',
  'type' => 'recaptcha',
  'label' => 'Prove your humanity',
  'site_key'	=> 'SOME_VALID_RECAPTCHA_KEY',
  'secret_key'	=> 'SOME_VALID_RECAPTCHA_SITE_SECRET',
  're_theme' => 'light', // Other options: 'dark'
  're_type'	=> 'image', // Other options: 'audio'
  're_size'	=> 'normal', // Other options: 'compact'
));
```

## About

Version 1.0.8

Written by Irvin Lim. If you encounter any issues, do open [one](https://github.com/irvinlim/acf-recaptcha/issues/new).

If you have any other questions, contact me [here](https://irvinlim.com/contact/)!

## Contributors
- **[Irvin Lim](https://irvinlim.com)**: Plugin author
- **[Sam Scholfield](https://github.com/samscholfield)**: Bugfix ([#4](https://github.com/irvinlim/acf-recaptcha/pull/4))
- **[Matteo Tagliatti](https://github.com/puntonero)**: Added more render parameters ([#5](https://github.com/irvinlim/acf-recaptcha/pull/5))
- **[Ramon Fincken](https://github.com/ramonfincken)**: `wp_remote_post` support added (v1.0.5)
