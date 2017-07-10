# <a href="https://wordpress.org/plugins/advanced-custom-fields-recaptcha-field/"><img src="https://raw.githubusercontent.com/irvinlim/acf-recaptcha/master/assets/banner-1544x500.png"></a>

**ACF reCAPTCHA** is a reCAPTCHA custom field type for **[Advanced Custom Fields v5](http://www.advancedcustomfields.com/)**, to protect frontend forms from spam by spambots.

![Google reCAPTCHA](https://www.google.com/recaptcha/intro/images/hero-recaptcha-demo.gif)

## Announcement

An important security fix was released in v1.2.0. Read more about it [here](https://github.com/irvinlim/acf-recaptcha/pull/22), and please update to v1.2.0 ASAP.

## Generating API keys

Generate your reCAPTCHA API keys for your domain at the [Google reCAPTCHA Dashboard](https://www.google.com/recaptcha/admin) first. You will need to enter the **Site Key** and **Secret Key** into the field settings later.

## Usage

1. In the ACF field group edit page, click *Add Field* to add a new field.
2. Find the *reCAPTCHA* field type under *Custom*.
3. Enter your site key and secret key that was generated previously.
4. *(optional)* Configure the theme, size and type of the reCAPTCHA widget.
5. **Important**: Scroll down to the bottom of the page, and enable *ACF reCAPTCHA Protection* for the field group.

### ACF PHP API

If you are using ACF via the [PHP API](https://www.advancedcustomfields.com/resources/register-fields-via-php/), this is an example to add a local reCAPTCHA field:

```php
acf_add_local_field(array(
    'key'           => 'field_recaptcha',
    'name'          => 'recaptcha',
    'type'          => 'recaptcha',
    'label'         => 'Prove your humanity',
    'site_key'	    => 'SOME_VALID_RECAPTCHA_KEY',
    'secret_key'    => 'SOME_VALID_RECAPTCHA_SITE_SECRET',
    're_theme'      => 'light',     // Other options: 'dark'
    're_type'       => 'image',     // Other options: 'audio'
    're_size'       => 'normal',    // Other options: 'compact'
));
```

Additionally, you have to set a `recaptcha` flag on the form or field group where you are using the field, to enable *ACF reCAPTCHA Protection*.

## ACF reCAPTCHA Protection

### What exactly is **ACF reCAPTCHA Protection**?

Prior to v1.2.0, ACF reCAPTCHA performs validation for null or invalid reCAPTCHA response values, and prevents forms from being submitted if reCAPTCHA is not fulfilled.

However, ACF v5 does not check whether all form fields marked as `required` are present in `$_POST` during validation (at `acf/validate_save_post`). Hence, spambots can easily omit the field key for the reCAPTCHA field when making a POST request, essentially bypassing the reCAPTCHA check with ease.

Unfortunately, this behaviour had existed for two years since I created the plugin in July 2015, and I only discovered it recently, also confirming the issue with Elliot Condon himself (author of ACF). It is now fixed in v1.2.0.

Hence, for forms or field groups with **ACF reCAPTCHA Protection** switched on, they must fulfil the following criteria:

1. The form must have at least one reCAPTCHA field, or at least one field group with a reCAPTCHA field
2. All reCAPTCHA fields submitted must be validated (using Google's reCAPTCHA server-side API)

Therefore, this can ensure that reCAPTCHAs cannot be bypassed in the previous manner.

Note that if a frontend form has no reCAPTCHA fields but has ACF reCAPTCHA Protection turned on, the frontend form cannot be submitted. This is because there is no way to tell apart whether a field was removed from `$_POST` or did not exist in the first place, due to the design of ACF.

### Field group setting

For field groups created using the ACF interface in `wp-admin`, simply turning on the *ACF reCAPTCHA Protection* toggle would be sufficient.

![](https://raw.githubusercontent.com/irvinlim/acf-recaptcha/v1.2/assets/screenshot-4.png)

**Note**: This will not work with field groups added to frontend forms which use a combination of *Location Rules* and `acf_form()` (without any arguments). Field group ID or key has to be explicitly specified in `acf_form($args)`.

### Using the `recaptcha` flag

Alternatively, you can use the `recaptcha` flag in both forms and field groups. As long as a form contains the flag or contains a field group with the flag, the whole form will be considered to have the flag.

#### With `acf_form()`

```php
acf_form(array(
    'fields' => array(
        'field_recaptcha',
        'field_myfield1',
        ...
    ),
    'recaptcha' => true         // <== SET THIS FLAG
));
```

#### With `acf_add_local_field_group()`

```php
acf_add_local_field_group(array(
    'key' => 'group_my_field_group',
    'fields' => array(
        array(
            'key' => 'field_recaptcha',
            'name' => 'recaptcha',
            'type' => 'recaptcha',
            ...
        )
    ),
    'recaptcha' => true         // <== SET THIS FLAG
));
```

#### With `acf_register_form`

```php
acf_register_form(array(
    'id' => 'acf-registered-recaptcha-form',
    'field_groups' => array('group_my_field_group'),
    'recaptcha' => true         // <== SET THIS FLAG
));
```

## About

Written and maintained by [Irvin Lim](https://irvinlim.com/).

If you encounter any issues, do open [one](https://github.com/irvinlim/acf-recaptcha/issues/new).

## License

MIT
