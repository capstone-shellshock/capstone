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
 * api for handling sign in
 */

//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;
try {

	//start session
	if(session_status() !== PHP_SESSION_ACTIVE) {
		session_start();
	}

	//grab mySQL statement
	$secrets = new ("/etc/apache2/capstone-mysql/Secrets.php");
	$pdo = $secrets->getPdoObject();

	//determine whicj HTTP method is being used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SESSION["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	// if method is post handle the sign in logic
	if($method === "POST") {

		//make sure the XSRF token is valid
		verifyXsrf();

		//process the request content and decode the json object into the php object
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		//check to make sure the password and email field is not empty
		if(empty($requestObject->profileEmail) ===true ) {
			throw(new \InvalidArgumentException("email address is not provided", 401));

		}else {
			$profileEmail = filter_var($requestObject->profileEmail, FILTER_SANITIZE_EMAIL);
		}

		if(empty($requestObject->profilePassword) ===true) {
			throw(new\InvalidArgumentException("must enter a password", 401));
		} else {
			$profilePassword = $requestObject->profilePassword;
		}

		//grab the profile from the database by the email provided
		$profile = Profile::getProfileByProfileEmail($pdo,$profileEmail);
		if(empty($profile) === true) {
			throw(new InvalidArgumentException("invalid Email", 401));
		}

		$profile->setProfileActivationToken(null);
		$profile->update($pdo);

		//verify hash is correct
		if(password_verify($requestObject->profilePassword, $profile->getProfileHash()) === false) {
			throw(new \InvalidArgumentException("Password or email is incorrect", 402));
		}

		//grab profile from the database and put into the session
		$profile = Profile::getProfileByProfileId($pdo, $profile->getProfileId());

		$_SESSION["profile"] = $profile;

		//create the aith payload
		$authObject = (object) [
			"profileId"=>$profile->getProfileId(),
			"profileUsername"=> $profile->getProfileUsername()
		];

		//create and set the JWT token
		setJwtAndAuthHeader("auth", $authObject);

		$reply->message = "Sign in was successful.";
	} else {
		throe(new \InvalidArgumentException("Invalid HTTP method request", 418));
	}

	//if an exception throw an update
} catch(Exception | TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}
header("Content-type: application/json");
echo json_encode($reply);