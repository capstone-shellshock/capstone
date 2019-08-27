<?php

require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/Classes/autoload.php";
require_once("/etc/apache2/capstone-mysql/Secrets.php");
require_once dirname(__DIR__, 3) . "/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/lib/jwt.php";
require_once dirname(__DIR__, 3) . "/lib/uuid.php";
require_once("/etc/apache2/capstone-mysql/Secrets.php");

use ShellShock\Capstone\{Profile, Location, Like};

/**
 * API for the Location class
 *
 */

//verify the session start if not active
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;
try {

	$secrets = new \Secrets("/etc/apache2/capstone-mysql/abqonthereel.ini");
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

	//handel GET request - if id is present, that location is returned, otherwise all locations are returned
	if($method === "GET") {
		//set XSRF cookie
		setXsrfCookie();

		//get a specific location or all locations and update reply
		if(empty(id) === false) {
			$reply->data = Location::getLocationByLocationId($pdo, $id);
		} else if(empty($locationProfileId) === false) {
			// if the user is logged in grap all the locations by that used based on who is logged in
			$reply->data = Location::getLocationByLocationProfileId($pdo, $locationProfileId);
		} else if(empty($locationAddress) === false) {
			$reply->data = Location::getLocationByLocationAddress($pdo, $locationAddress)->toArray();
		} else if(empty($locationImdbUrl === false)) {
			$reply->data = Location::getLocationByLocationImdbUrl($pdo, $locationImdbUrl);
		} else if(empty($locationText) === false) {
			$reply->data = Location::getLocationByLocationTitle($pdo, $locationTitle)->toArray();
		} else if(empty($locationTitle) === false) {
			$reply->data = Location::getLocationByLocationTitle($pdo, $locationTitle)->toArray();
		} else {
			$reply->data = Location::getAllLocations($pdo)->toArray();
		}
	} else if($method === "PUT" || $method === "POST") {
		// enforce the user has a XSRF
		verifyxsrf();

		//enforce the user is signed in
		if(empty($_session["profile"]) === true) {
			throw(new \InvalidArgumentException("you must be logged in to post locations", 401));
		}
		//retrieves the JSON that the front end sent, and stores it in $requestContent, here we are using file_get_content ("php//input") o get the request from the front end. file_get_contents() is a PHP function that reads a file into a string. The argument for the function, here, is "php://input". This is a read only stream that allows raw data to be read from the front end request which is, in this case, a JSON package.
		$requestContent = file_get_contents("php://input");

		//this line then decodes the JSON packages and stores that result in $requestObject
		$requestObject = json_decode($requestContent);

		//make sure location Imdb is available (required field))
		if(empty($requestObject->locationImdb) === true) {
			throw(new \InvalidArgumentException("no Imdb for location", 405));
		}

		//make sure location Title is available (required field))
		if(empty($requestObject->locationTitle) === true) {
			throw(new \InvalidArgumentException("no title for location", 405));
		}

		//make sure location Text is available (required field))
		if(empty($requestObject->locationText) === true) {
			throw(new \InvalidArgumentException("no text for location", 405));
		}


		
		//perform the actual put or post
		if($method === "PUT") {

			//retrieve the location to update
			$location = Location::getLocationByLocationProfileId($pdo, $id);
			if ($location = null) {
				throw(new RuntimeException("location does not exist", 404));
			}

			//enforce the end user has an JWT token

			//enforce the user is signed in and only trying to edit their own Location
			if (empty($_SESSION["profile"]) === true || $_SESSION["profile"]->getProfileId()->toString() !== $location->getLocationProfileId()->toString()) {
				throw(new \InvalidArgumentException("you mussed be logged in to post Locations". 403));
			}

			vaildateJwtHeader();

			//update all attributes
			$location->setLocationText($requestObject->locationText);
			$location->update($pdo);

			//update reply
			$reply->message = "Location update ok";

		} else if ($method === "POST") {

			//enforce the user is sign in
			if(empty($_SESSION["profile"])=== true) {
				throw(new \InvalidArgumentException("you must be logged in to post locations", 403));
			}

			//enforce the end user has JWT token
			validateJwtHeader();

			//create new location and insert it into the database
			$location = new Location(generateUuidV4(), $_SESSION["profile"]->getProfileId(),null , null,null,null,null,null, $requestObject->locationText, $requestObject->locationTitle, $requestObject->locationImdbUrl() );
		$location->insert($pdo);

		//update reply
			$reply->message = "location created ok";
		}

	}else if($method === "DELETE") {

		//enforce that the end user has an XSRF token
		verifyXsrf();

		//retrieve the Location to be deleted
		$location = Location::getLocationByLocationId($pdo, $id);
		if($location === null) {
			throw(new RuntimeException("Location does not exist", 404));
		}
		//enforce the user is signed in and only trying to edit there on Location
		if(empty($_SESSION["profile"]) === true || $_SESSION["profile"]->getProfileId()->toString() !== $location->getLocationProfileId()->toString()) {
			throw(new \InvalidArgumentException("you are not allowed to delete this Location", 403));
		}

		//enforce the end has a JWT token
		validateJwtHeader();

		//delete Location
		$location->delete($pdo);

		//update reple
		$reply->message = "location deleted ok";
	}else {
		throw(new \InvalidArgumentException("invalid HTTP method request",418));
	}

	//update the reply
	}catch (\Exception | \TypeError $exception) {
		$reply->status = $exception->getCode();
		$reply->message = $exception->getMessage();
}

//encode and return reply to the front end caller
header("Content-type: application/Json");
echo json_encode($reply);

