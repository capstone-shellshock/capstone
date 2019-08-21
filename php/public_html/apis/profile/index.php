<?php

require_once dirname(__DIR__, 3) . "vendor/autoload.php";
require_once dirname(__DIR__, 3) . "Classes/autoload.php";
require_once("/etc/apache2/capstone-mysql/Secrets.php");
require_once dirname(__DIR__, 3) . "/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/lib/jwt.php";
require_once dirname(__DIR__, 3) . "/lib/uuid.php";
require_once ("etc/apache2/capstone-mysql/Secrets.php");

use ShellShock\Capstone\{Profile};

/**
 * API for profile
 *
 * @author Justin Murphy
 * @version 1.0
 **/

//verify the session, if it is not active - start it
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
}

//prepare an empty reply
$reply = new stdClass();
$reply -> status = 200;
$reply -> data = null;

try {

	//grab the MySQL connection
	$secrets = new \Secrets("etc/apache2/capstone-mysql/abqonthereel.ini");
	$pdo = $secrets -> getPdoObject();

	//determine which HTTP method was used
	$method = $_SERVER["HTTP_X_HTTP_METHOD"] ?? $_SERVER["REQUEST_METHOD"];

	//sanitize input
	$id = filter_input(INPUT_GET, "id", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$profileUsername = filter_input(INPUT_GET, "profileUsername", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$profileEmail = filter_input(INPUT_GET, "profileEmail", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	//make sure id is valid for methods that require it
	if(($method === "DELETE" || $method === "PUT") && (empty($id) === true)) {
		throw(new InvalidArgumentException("ID cannot be empty or negative", 405));
	}

	if($method === "GET") {
		//set xsrf cookie
		setXsrfCookie();

		//gets a post by content
		if(empty($id) === false) {

			$reply -> data = Profile::getProfileByProfileId($pdo, $id);
		} else if(empty($profileUsername) === false) {
			$reply -> data = Profile::getProfileByProfileUsername($pdo, $profileUsername);
		} else if
	}
}