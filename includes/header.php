<?php

include('vendor/autoload.php');

function get_content($url)
{

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_TIMEOUT, 60);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $string = curl_exec($ch);
    curl_close($ch);

    if (empty($string))
    {
        return FALSE;
    }

    return $string;

}

if (!empty($SERVER['HTTPS']))
{
    $api_url = 'https';
}
else {
    $api_url = 'http';
}
$api_url .= '://';
$api_url .= $_SERVER['SERVER_NAME'];
define('API_URL', $api_url);
