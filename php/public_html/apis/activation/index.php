<?php


require_once dirname(__DIR__,3) . "/vendor/autoload.php";
require_once dirname(__DIR__,3) . "/Classes/autoload.php";
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
}