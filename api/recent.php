<?php

$database = new Database;
$db = $database->connect();

if (!$db)
{
    header(SERVER_PROTOCOL . " 500 Internal Server Error", true, 500);
    echo json_encode('Error');
    exit;
}

/*
 * Get a list of the last 100 businesses incorporated
 */
$business = new Business;
$business->db = $db;
$business->query = $query;
$results = $business->recent();

if (!is_array($results))
{
    header(SERVER_PROTOCOL . " 404 Not Found", true, 404);
    echo json_encode('Error');
    exit;
}

echo json_encode($results);
