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
 * API for the cloudinary image
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
	$cloudinary = $secrets->getSecret("cloudinary");


	//determine which HTTP method is being used
	$method = $_SERVER["HTTP_X_HTTP_METHOD"] ?? $_SERVER["REQUEST_METHOD"];

	$locationId = filter_input(INPUT_GET, "locationImageCloudinaryId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$profileId =  filter_input( INPUT_GET, "profileId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);


	\Cloudinary::config(["cloud_name" => $cloudinary->cloudName, "api_key" => $cloudinary->apiKey, "api_secret" =>$cloudinary->apiSecret]);

	//process GET requests
	if($method === "GET") {
      //set XRRF token
		setXsrfCookie();
		$reply->data = Location:: getAllLocations($pdo)->toArray;
	}


	else if($method === "PUT" || $method === "POST") {

		//set XRRF token
		setXsrfCookie();

		// enforce the user has a XSRF token
		verifyXsrf();

		// enforce the user is signed in
		if(empty($_SESSION["profile"]) === true) {
			throw(new \InvalidArgumentException("you must be logged in to post Scene", 401));
		}
		$requestContent = file_get_contents("php://input");
		// Retrieves the JSON package that the front end sent, and stores it in $requestContent. Here we are using file_get_contents("php://input") to get the request from the front end. file_get_contents() is a PHP function that reads a file into a string. The argument for the function, here, is "php://input". This is a read only stream that allows raw data to be read from the front end request which is, in this case, a JSON package.
		$requestObject = json_decode($requestContent);
		// This Line Then decodes the JSON package and stores that result in $requestObject
		//make sure tweet content is available (required field)
		if(empty($requestObject->locationImageImdUrl) === true) {
			throw(new \InvalidArgumentException ("No content for Scene.", 405));
		}

//} catch (Exception ) {
//		$reply->status = $exception->getCode();
//		$reply->message = $exception->getMessage();
//}
//
//header("Content-Type: application/json");
//// encode and return reply to front end caller
//echo json_encode($reply);