<?php


require_once dirname(__DIR__,3) . "/vendor/autoload.php";
require_once dirname(__DIR__,3) . "/Classes/autoload.php";
require_once dirname(__DIR__,3) . "/lib/xsrf.php";
require_once ("/etc/apache2/capstone-mysql/Secrets.php");

use ShellShock\Capstone\Profile;

/**
 * API to check profile activation status
 *
 * @author JMurphy
 **/

//check the session, start if not already active
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

//prepare an empty reply
$reply = new stdClass();
$reply -> status = 200;
$reply -> data = null;
try {
	//grab the MySQL connection

	$secrets = new \Secrets("/etc/apache2/capstone-mysql/abqonthereel.ini");
	$pdo = $secrets -> getPdoObject();

	//check th eHTTP method being used
	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];

	//sanitize input (never trust the end use)
	$activation = filter_input(INPUT_GET, "activation", FILTER_SANITIZE_STRING);

	//make sure the activation token is the correct size
	if(strlen($activation) !== 32) {
		throw(new InvalidArgumentException("Activation token length is incorrect", 405));
	}

	//verify that the activation token is a string value of a hexadecimal
	if(ctype_xdigit($activation) === false) {
		throw (new \InvalidArgumentException("Activation token is empty or has invalid contents", 405));
	}

	//handle the GET HTTP request
	if($method === "GET") {

		//set xsrf cookie
		setXsrfCookie();

		//find profile associated with the activation token
		$profile = Profile::getProfileByProfileActivationToken($pdo, $activation);

		//verify the profile is not null
		if($profile !== null) {

			//make sure the activation token matches
			if($activation === $profile -> getProfileActivationToken()) {

				//set activation to null
				$profile -> setProfileActivationToken(null);

				//update the profile in the database
				$profile -> update($pdo);

				//set the reply for the end user
				$reply -> data = "Thank you for activating your account! You will be redirected to your profile very soon.";
			}
		} else {

			//throw an exception if the activation token does not exist
			throw(new RuntimeException("Profile with this activation code does not exist.", 404));
		}
	} else {

		//throw an exception if the HTTP request is not a GET
		throw(new InvalidArgumentException("Invalid HTTP method request", 403));
	}

	//update the reply objects status and message state variables if an exception or type exception was thrown
} catch (Exception $exception) {
	$reply -> status = $exception -> getCode();
	$reply -> message = $exception -> getMessage();
} catch(TypeError $typeError) {
	$reply -> status = $typeError -> getCode();
	$reply -> message = $typeError -> getMessage();
}

//prepare and send the reply
header("Content-type: application/json");
if($reply -> data === null) {
	unset($reply -> data);
}

echo json_encode($reply);