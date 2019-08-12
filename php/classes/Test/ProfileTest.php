<?php

namespace shellShock\Capstone;

use

//grab the class under scrutiny
require_once(dirname(__DIR__)."/autoload.php");

//grab the UUID generator
require_once(dirname(__DIR__,2)."uuid.php");



/**
 * Unit test for the profile class
 *
 * Complete unit test, tests for all MySQL/PDO enabled methods. Tests for both valid and invalid inputs
 *
 * @see Profile
 * @author Justin Murphy <jmurphy33@cnm.edu>
 **/

class ProfileTest extends ProfileTestSetup {

	/**
	 *place holder until account activation is created
	 *
	 * @var string $VALID_ACTIVATION
	 **/

	protected $VALID_ACTIVATION;

	/**
	 * valid email for testing
	 *
	 * @var string $VALID_EMAIL
	 **/

	protected $VALID_EMAIL = "phpTest@profile.com";

	/**
	 * valid username to use
	 *
	 * @var string $VALID_USERNAME
	 **/

	protected $VALID_USERNAME = "JTown";

	/**
	 * valid hash to use
	 *
	 * @var $VALID_HASH
	 **/

	protected $VALID_HASH;




}