<?php

add_action('rest_api_init', function () {
    register_rest_route('jwt-auth/v1', '/get', array(
        'methods' => 'GET',
        'callback' => 'custom_favicon_api_get_favicon',
    ));
});

function custom_favicon_api_get_favicon($request) {
 if (!isset($_GET['url'])) {
        return new WP_Error('404', 'Missing URL parameter');
    }

    $url = $_GET['url'];

    $get_php_file = dirname(__FILE__) . '/get.php';

    if (!file_exists($get_php_file)) {
        return new WP_Error('404', 'get.php file is missing');
    }

    // Change current directory to the location of get.php
    chdir(dirname($get_php_file));

    // Include the get.php file
    ob_start();
    require_once $get_php_file;
    ob_end_clean();

    return '';
}
