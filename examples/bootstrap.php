<?php

/**
 * Bootstrap the library
 */
require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Setup error reporting
 */
error_reporting(E_ALL);
ini_set('display_errors', 1);

/**
 * Setup the timezone
 */
ini_set('date.timezone', 'America/Denver');

/**
 * Load the credential for the different services
 */
 //use \Session;
require_once __DIR__ . '/credentials.php';
require_once __DIR__ . '/Shuffler.php';

$spotify = new Shuffler($servicesCredentials);
$spotify->loginAndGetTracks();

function dd($value) {
  die(var_dump($value));
}
