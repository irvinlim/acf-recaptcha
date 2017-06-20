# <a href="https://wordpress.org/plugins/advanced-custom-fields-recaptcha-field/"><img src="https://raw.githubusercontent.com/irvinlim/acf-recaptcha/master/assets/banner-1544x500.png"></a>

**ACF reCAPTCHA** is a reCAPTCHA custom field type for **[Advanced Custom Fields](http://www.advancedcustomfields.com/)**, to be used on frontend forms.

![Google reCAPTCHA](https://www.google.com/recaptcha/intro/images/hero-recaptcha-demo.gif)

## Instructions

### Generating an API Key

If you haven't already, [generate your reCAPTCHA API keys](https://www.google.com/recaptcha/admin) first. You will need the **site key** and **secret key**.

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

Current stable version: 1.1

Written by [Irvin Lim](https://irvinlim.com). If you encounter any issues, do open [one](https://github.com/irvinlim/acf-recaptcha/issues/new).

## License

MIT
