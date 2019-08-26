<?php

require_once dirname(__DIR__,3) . "/vendor/autoload.php";
require_once dirname(__DIR__,3) . "/Classes/autoload.php";
require_once ("/etc/apache2/capstone-mysql/Secrets.php");
require_once dirname(__DIR__,3) . "/lib/xsrf.php";
require_once dirname(__DIR__,3) . "/lib/uuid.php";
require_once ("/etc/apache2/capstone-mysql/Secrets.php");

use ShellShock\Capstone\Profile;

/**
 * api for signing up to ABQ on the reel
 *
 * @author Justin Murphy <jmurphy33@cnm.edu>
 **/

//verify the session, start if not active
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

	//determine which http method was used

	$method = array_key_exists("HTTP_X_HTTP_METHOD", $_SERVER) ? $_SERVER["HTTP_X_HTTP_METHOD"] : $_SERVER["REQUEST_METHOD"];


	if($method === "POST") {

		//decode the json and turn it into a php object
		$requestContent = file_get_contents("php://input");
		$requestObject = json_decode($requestContent);

		//profile username is a required field
		if(empty($requestObject -> profileUsername) === true) {
			throw(new \InvalidArgumentException("No profile username present", 405));
		}

		//profile email is a required field
		if(empty($requestObject -> profileEmail) === true) {
			throw(new \InvalidArgumentException("No profile email present", 405));
		}

		//verify that the profile password is present
		if(empty($requestObject -> profilePassword) === true) {
			throw(new \InvalidArgumentException("Must input a password", 405));
		}

		//verify that the confirm password is present
		if(empty($requestObject -> profilePasswordConfirm) === true) {
			throw(new \InvalidArgumentException("Must input a password", 405));
		}

		//verify that password and confirm passwords match
		if($requestObject -> profilePassword !== $requestObject -> profilePasswordConfirm) {
			throw(new \InvalidArgumentException("Passwords do not match"));
		}

		$hash = password_hash($requestObject -> profilePassword, PASSWORD_ARGON2I, ["time_cost" => 384]);

		$profileActivationToken = bin2hex(random_bytes(16));

		//create the profile object and prepare to insert into the database
		$profile = new Profile(generateUuidV4(), $profileActivationToken, $requestObject -> profileEmail, $hash, $requestObject -> profileUsername);

		//insert the profile into the database
		$profile -> insert($pdo);

		//compose the email message to send with the activation token
		$messageSubject = "You are so close -- Activate your account";

		//building the activation link that can travel to another server and still work. This is the link that will be clicked to activate the account
		//make sure URL is /public_html/api/activation/$activation
		$basePath = dirname($_SERVER["SCRIPT_NAME"], 3);

		//create path
		$urlGlue = $basePath . "/api/activation/?activation=" . $profileActivationToken;

		//create the redirect link
		$confirmLink = "https://" . $_SERVER["SERVER_NAME"] . $urlGlue;


		//compose message to send with email
		$message = <<< EOF
<h3>Welcome to ABQ On The Reel!</h3>
<p>To start making scenes, please confirm your account </p>
<p><a href="$confirmLink">$confirmLink</a></p>
EOF;

		//create swift email
		$swiftMessage = new Swift_Message();

		//attach the sender to the message
		//this takes the form of an associative array where the email is the key to a real name
		$swiftMessage -> setFrom(["jmurphy33@cnm.edu" => "JMurphy"]);

		/**
		 * attach recipients to the message
		 * this is an array that can include or omit the recipient's name
		 * use the recipients real name where possible
		 * this reduces the possibility that the email is marked as spam
		 **/

		//define who the recipient is
		$recipients = [$requestObject -> profileEmail];

		//set the recipient to the swift message
		$swiftMessage -> setTo($recipients);

		//attach the subject line to the email message
		$swiftMessage -> setSubject($messageSubject);

		/**
		 * attach the message to the email
		 * set two versions: an html formatted version and a filter_var()ed version of the message, plain text
		 * notice the tactic used is to display the entire $confirmLink to plain text
		 * this lets users who are not viewing the html content to still access the link
		 **/

		//attach the html version to the message
		$swiftMessage -> setBody($message, "text/html");

		//attach the plain text version of the message
		$swiftMessage -> addPart(html_entity_decode($message), "text/plain");

		//send the mail via SMTP
		//setup SMTP
		$smtp = new Swift_SmtpTransport(
			"localhost", 25);
		$mailer = new Swift_Mailer($smtp);

		//send the message
		$numSent = $mailer -> send($swiftMessage, $failedRecipients);

		/**
		 * the send method returns the number of recipients that accepted the email
		 * so, if the number attempted is not the number accepted, this is an Exception
		 **/

		if($numSent !== count($recipients)) {
			//the $failedRecipients parameter passed in the send() method now contains an array of the email that falied
			throw(new RuntimeException("unable to send email", 400));
		}

		//update reply
		$reply -> message = "Thank you for creating a profile with ABQ On The Reel";
	} else {
		throw(new \InvalidArgumentException("Invalid HTTP request"));
	}

} catch (\Exception | \TypeError $exception) {
	$reply -> status = $exception -> getCode();
	$reply -> message = $exception -> getMessage();
	$reply -> trace = $exception -> getTraceAsString();
}

header("Content-type: application/json");
echo json_encode($reply);