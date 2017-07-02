<?php

/**
 * Adds a filter for enqueuing JavaScript files with async and defer.
 *
 * @since v1.1.1
 */


/**
 * Adds async and defer attributes to the script tag to our enqueued scripts
 * by hooking onto the 'script_loader_tag' hook.
 *
 * Matches only handles which start with 'acf-recaptcha-' and has a '#asyncdefer'
 * anchor at the end of the source URL.
 *
 * @param string $tag       The HTML markup for enqueuing a script tag.
 * @param string $handle    The script's registered handle.
 * @param string $src       The source URL.
 * @return string           The complete script tag after processing.
 */
function acf_recaptcha_script_loader_async_defer($tag, $handle, $src) {

    // Guard if it's not our script.
    if (strpos($handle, 'acf-recaptcha-') !== 0) {
        return $tag;
    }

    // Guard if we didn't indicate for it to be async defer.
    if (strrpos($src, '#asyncdefer') !== strlen($src) - strlen('#asyncdefer')) {
        return $tag;
    }

    // Add the 'async defer' attributes.
    return str_replace('<script', '<script async defer', $tag);

}

add_filter('script_loader_tag', 'acf_recaptcha_script_loader_async_defer', 10, 3);
