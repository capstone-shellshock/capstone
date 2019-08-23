<?php

require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/Classes/autoload.php";
require_once("/etc/apache2/capstone-mysql/Secrets.php");
require_once dirname(__DIR__, 3) . "/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/lib/jwt.php";
require_once dirname(__DIR__, 3) . "/lib/uuid.php";
require_once("/etc/apache2/capstone-mysql/Secrets.php");

use ShellShock\Capstone\{Like, Profile, Location};

/**
 * API for the Location class
 *
 */

//verify the session start if not active
if(session_start() !== PHP_SESSION_ACTIVE) {
	session_start();
}

//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;
try {

	$secrets = new ("/etc/apache2/capstone-mysql/Secrets.php");
	$pdo = $secrets->getPdoObject();


	//determine which http type was used
	$method = $_SERVER[HTTP_X_HTTP_METHOD] ?? $_SERVER["REQUEST_METHOD"];

	//sanitize input
	$id = filter_input(INPUT_GET, id, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$locationProfileId = filter_input(INPUT_GET, "locationProfileId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$locationAddress = filter_input(INPUT_GET, "locationAddress", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$locationImdbUrl = filter_input(INPUT_GET, "locationImdbUrl", FILTER_SANITIZE_STRING, FILTER_VALIDATE_URL);
	$locationText = filter_input(INPUT_GET, "locationText", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$locationTitle = filter_input(INPUT_GET, "locationTitle", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	//make sure id is valid for the methods that require it
	if(($method === "DELETE" || $method === "PUT") && (empty(id) === true)) {
		throw(new InvalidArgumentException("id cannot be empty or negative", 402));
	}

	//hande GET request - if id is present, that location is returned, otherwise all locations are returned
	if($method === "GET") {
		//set XSRF cookie
		setXsrfCookie();

		//get a specific location or all locations and update reply
		if (empty(id) === false) {
			$reply->data = Location::getLocationByLocationId($pdo, $id);
		} else if (empty($locationProfileId) === false) {
			// if the user is logged in grap all thwe locations by that used based on who is logged in
			$reply->data = Location::getLocationByLocationProfileId($pdo, $locationProfileId);
		}  else if (empty($locationAddress)=== false) {
		$reply->data = Location::getLocationByLocationAddress($pdo, $locationAddress)->toArray();
		} else if(empty($locationText) === false) {
			$reply->data = Location::getLocationByLocationTitle($pdo, $locationTitle)->toArray();

	}
}