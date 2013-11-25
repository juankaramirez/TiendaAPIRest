<?php
require_once 'vendor/autoload.php';
require_once 'bootstrap.php';
\Stormpath\Client::$apiKeyFileLocation =  'stormpath/apiKey.properties';
include 'app/server.php';