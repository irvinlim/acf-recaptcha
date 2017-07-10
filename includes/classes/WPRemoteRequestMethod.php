<?php

namespace ReCaptcha\RequestMethod;

use ReCaptcha\RequestMethod;
use ReCaptcha\RequestParameters;

/**
 * Sends POST requests to the reCAPTCHA service using the WP remote POST method
 */
class WPRemoteRequestMethod implements RequestMethod {
    /**
     * URL to which requests are POSTed.
     * @const string
     */
    const SITE_VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';

    /**
     * Submit the POST request with the specified parameters.
     *
     * @param RequestParameters $params Request parameters
     * @return string Body of the reCAPTCHA response
     */
    public function submit(RequestParameters $params) {
        $response = wp_remote_post(self::SITE_VERIFY_URL, array(
            'method' => 'POST',
            'body' => $params->toQueryString(),
        ));

        if (!is_wp_error($response)) {
            return wp_remote_retrieve_body($response);
        }

        // Alltough we try to decode json we will throw the error
        throw $response->get_error_message();
    }
}
