<?php
//echo $_SERVER["REQUEST_URI"]; die();
$matches = [];

// esto separa la url y la pone en variables
//  /  \/([^\/]+)  \/([^\/]+)  \/([^\/]+)  /


// para 3 segmentos
if (preg_match('/\/([^\/]+)\/([^\/]+)\/([^\/]+)/', $_SERVER["REQUEST_URI"], $matches)) {
    $_GET['resource_type'] = $matches[2];
    $_GET['resource_id'] = $matches[3];

    error_log( print_r($matches, 2) );
    require 'server-sinbase.php';

// para 2 segmentos
} elseif (preg_match('/\/([^\/]+)\/([^\/]+)/', $_SERVER["REQUEST_URI"], $matches)) {
    $_GET['resource_type'] = $matches[2];
    error_log( print_r($matches, 2) );

    require 'server-sinbase.php';

// para menos de 2 da error
} else {

    error_log('No matches');
    http_response_code( 404 );
}