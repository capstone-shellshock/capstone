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

	/**
	 * run the default setup operation to create salt and hash
	 **/

	public final function setUp() : void {
		parent::setUp();

		$password = "abc123";
		$this -> VALID_HASH = password_hash($password, PASSWORD_ARGON2I, ["time_cost" => 384]);
		$this -> VALID_ACTIVATION = bin2hex(random_bytes(16));
	}

	/**
	 * test inserting a valid profile and verify that the actual MySQL data matches
	 **/

	public function testInsertValidProfile() : void {
		//count the number of rows and save it for later
		$numRows = $this -> getConnection() -> getRowCount("profile");

		$profileId = generateUuidV4();

		$profile = new Profile($profileId, $this -> VALID_ACTIVATION, $this -> VALID_EMAIL, $this -> VALID_USERNAME, $this -> VALID_HASH);
		$profile -> insert($this -> getPDO());

		//grab the data from MySQL and enforce the fields match our expectations
		$pdoProfile = Profile::getProfileByProfileId($this -> getPDO(), $profile -> getProfileId());
		$this -> assertEquals($numRows + 1, $this -> getConnection() -> getRowCount("profile"));
		$this -> assertEquals($pdoProfile -> getProfileId(), $profileId);
		$this -> assertEquals($pdoProfile -> getProfileActivationToken(), $this -> VALID_ACTIVATION);
	}




}