<?php

require_once $_SERVER['DOCUMENT_ROOT'] . '/includes/autoloader.php';

$database = new Database;
$db = $database->connect();

if (!$db)
{
    header($_SERVER["SERVER_PROTOCOL"]." 500 Internal Server Error", true, 500);
    echo json_encode('Error');
    exit;
}

/*
 * Get all businesses created in the past week
 */
$business = new Business;
$business->db = $db;
$business->query = $query;
$results = $business->recent();

if (!is_array($results))
{
    header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
    echo json_encode('Error');
    exit;
}

echo json_encode($results);
