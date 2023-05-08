<?php
require_once "classes/database.php";
require_once "classes/secret.php";

$format = isset($_GET['format']) ? $_GET['format'] : 'json';

if ($format === 'json') {    
    require "endpoints_json.php";
} elseif ($format === 'xml') {    
    require "endpoints_xml.php";
} else {
    http_response_code(400);
    exit();
}


