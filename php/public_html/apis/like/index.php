<?php

require_once dirname(__DIR__, 3) . "/vendor/autoload.php";
require_once dirname(__DIR__, 3) . "/Classes/autoload.php";
require_once("/etc/apache2/capstone-mysql/Secrets.php");
require_once dirname(__DIR__, 3) . "/lib/xsrf.php";
require_once dirname(__DIR__, 3) . "/lib/jwt.php";
require_once dirname(__DIR__, 3) . "/lib/uuid.php";
require_once("etc/apache2/capstone-mysql/Secrets.php");

use ShellShock\Capstone\{Like, Location, Profile};


/**
 * API for  Like class
 *
 * @author Lariah
 **/

//verify the session, if it is not active start it
if(session_status() !== PHP_SESSION_ACTIVE) {
	session_start();
};

//prepare an empty reply
$reply = new stdClass();
$reply->status = 200;
$reply->data = null;

try {

	$secrets = new \Secrets("etc/apache2/capstone-mysql/abqonthereel.ini");

	$pdo = $secrets->getPdoObject();

	//determine which HTTP method was used
	$method = $_SERVER["HTTP_X_HTTP_METHOD"] ?? $_SERVER["REQUEST_METHOD"];


	//sanitize the search parameters
	$likeLocationId = $id = filter_input(INPUT_GET, "likeLocationId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
	$likeProfileId = $id = filter_input(INPUT_GET, "likeProfileId", FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

	if($method === "GET") {
		//SET XSRF cookie
		setXsrfCookie();

		//gets a specific like associated based on its composite key
		if($likeLocationId !== null && $likeProfileId !== null) {
			$like = Like::getLikeByLikeLocationIdAndLikeProfileId($pdo, $likeLocationId, $likeProfileId);


			if($like !== null) {
				$reply->data = $like;
			}
			//if non of the search parameters are met throw an exception
		} else if(empty($likeLocationId) === false) {
			$reply->data = Like::getLikeByLocationId($pdo, $likeLocationId)->toArray();
			//get all the likes associated with the profileId
		} else if(empty($likeProfileId) === false) {
			$reply->data = Like::getLikeByProfileId($pdo, $likeProfileId)->toArray();
		} else {
			throw (new InvalidArgumentException("incorrect search parameters", 404));
		}

	} else if($method === "POST" || $method === "PUT") {
		//decode the response from the front end
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);


		if(empty($requestObject->likeLocationId) === true) {
			throw (new \InvalidArgumentException("No location linked to the Like", 405));
		}

		if($method === "POST") {
			//enforce that the end user has a XSRF token.
			verifyXsrf();

			//enforce the end user has a JWT token
			//validateJwtHeader();

			//enforce the user is signed in
			if(empty($_SESSION["profile"]) === true) {
				throw(new \InvalidArgumentException("you must be logged in to like home", 403));
			}

			validateJwtHeader();

			$like = new Like($requestObject->likeLocationId,$_SESSION["profile"]->getProfileId());
			$like->insert($pdo);
			$reply->message = "liked location successful";

		} else if($method === "PUT") {
			//enforce the end user has  XSRF token.
			verifyXsrf();

			//enforce the end user has a JWT token.
			validateJwtHeader();

			//grab the like by its composite key.
			$like = Like::getLikeByLikeLocationIdAndLikeProfileId($pdo, $requestObject->likeLocationId, $requestObject->likeProfileId);
			if($like === null) {
				throw(new RuntimeException("Like does not exist"));
			}

			//enforce the user is signed in and only trying to edit their own like
			if(empty($_SESSION["profile"]) === true || $_SESSION["profile"]->getProfileId() !== $like->getLikeProfileId()) {
				throw(new \InvalidArgumentException("You are not allowed to delete this like", 403));
			}

			//validateJwtHeader();

			//preform the actual delete
			$like->delete($pdo);

			//update the message
			$reply->message = "Like successfully deleted";
		}

		//if any other HTTP request is sent throw an exception
	} else {
		throw new \InvalidArgumentException("invalid http request", 400);
	}
	// catch any exception that is thrown and update the reply status and message
} catch(\Exception | \TypeError $exception) {
	$reply->status = $exception->getCode();
	$reply->message = $exception->getMessage();
}

header("Content-type: application/json");
if($reply->data === null) {
	unset($reply->data);
}

//encode and return reply to front end caller
echo json_encode($reply);