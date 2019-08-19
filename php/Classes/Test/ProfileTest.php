<?php

namespace ShellShock\Capstone\Test;

use ShellShock\Capstone\{Profile};

require_once(dirname(__DIR__)."/autoload.php");

//grab the UUID generator
require_once(dirname(__DIR__,2)."/lib/uuid.php");


/**
 * Unit Test for the profile class
 *
 * Complete unit Test, tests for all MySQL/PDO enabled methods. Tests for both valid and invalid inputs
 *
 * @see Profile
 * @author Justin Murphy <jmurphy33@cnm.edu>
 **/

class ProfileTest extends AbqOnTheReelTest {

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
	 * Test inserting a valid profile and verify that the actual MySQL data matches
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
		$this -> assertEquals($pdoProfile -> getProfileEmail(), $this -> VALID_EMAIL);
		$this -> assertEquals($pdoProfile -> getProfileUsername(), $this -> VALID_USERNAME);
		$this -> assertEquals($pdoProfile -> getProfileHash(), $this -> VALID_HASH);
	}

	/**
	 * Test inserting a profile, editing it, and then updating it
	 **/

	public function testUpdateValidProfile() {
		//count the number of rows and save it for later

		$numRows = $this -> getConnection() -> getRowCount("profile");

		//create a new profile and insert into MySQL
		$profileId = generateUuidV4();
		$profile = new Profile($profileId, $this -> VALID_ACTIVATION, $this -> VALID_EMAIL, $this -> VALID_USERNAME, $this -> VALID_HASH);
		$profile -> insert($this -> getPDO());

		//edit the profile and update it in MySQL
		$profile -> setProfileUsername($this -> VALID_USERNAME);
		$profile -> update($this -> getPDO());

		//grab the data from MySQL and enforce that the fields match the expectations
		$pdoProfile = Profile::getProfileByProfileId($this -> getPDO(), $profile -> getProfileId());
		$this -> assertEquals($numRows + 1, $this -> getConnection() -> getRowCount("profile"));
		$this -> assertEquals($pdoProfile -> getProfileId(), $profileId);
		$this -> assertEquals($pdoProfile -> getProfileActivationToken(), $this -> VALID_ACTIVATION);
		$this -> assertEquals($pdoProfile -> getProfileEmail(), $this -> VALID_EMAIL);
		$this -> assertEquals($pdoProfile -> getProfileUsername(), $this -> VALID_USERNAME);
		$this -> assertEquals($pdoProfile -> getProfileHash(), $this -> VALID_HASH);
	}

	/**
	 * Test creating a profile and then deleting it
	 **/

	public function testDeleteValidProfile() : void {
		//count the number of rows and save it for later
		$numRows = $this -> getConnection() -> getRowCount("profile");

		$profileId = generateUuidV4();
		$profile = new Profile($profileId, $this -> VALID_ACTIVATION, $this -> VALID_EMAIL, $this -> VALID_USERNAME, $this -> VALID_HASH);
		$profile -> insert($this -> getPDO());

		//delete the profile from MySQL
		$this -> assertEquals($numRows + 1, $this -> getConnection() -> getRowCount("profile"));
		$profile -> delete($this -> getPDO());

		//grab the data from MySQL and enforce the profile does not exist
		$pdoProfile = Profile::getProfileByProfileId($this -> getPDO(), $profile -> getProfileId());
		$this -> assertNull($pdoProfile);
		$this -> assertEquals($numRows, $this -> getConnection() -> getRowCount("profile"));
	}

	/**
	 * Test inserting a profile and getting it from MySQL
	 **/

	public function testGetProfileByProfileId() : void {
		//count the number of rows and save it for later
		$numRows = $this -> getConnection() -> getRowCount("profile");

		$profileId = generateUuidV4();
		$profile = new Profile($profileId,  $this -> VALID_ACTIVATION, $this -> VALID_EMAIL, $this -> VALID_USERNAME, $this -> VALID_HASH);
		$profile -> insert($this -> getPDO());

		//grab the data from MySQL and enforce that the fields match the expectations
		$pdoProfile = Profile::getProfileByProfileId($this -> getPDO(), $profile -> getProfileId());
		$this -> assertEquals($numRows + 1, $this -> getConnection() -> getRowCount("profile"));
		$this -> assertEquals($pdoProfile -> getProfileId(), $profileId);
		$this -> assertEquals($pdoProfile -> getProfileActivationToken(), $this -> VALID_ACTIVATION);
		$this -> assertEquals($pdoProfile -> getProfileEmail(), $this -> VALID_EMAIL);
		$this -> assertEquals($pdoProfile -> getProfileUsername(), $this -> VALID_USERNAME);
		$this -> assertEquals($pdoProfile -> getProfileHash(), $this -> VALID_HASH);
	}

	/**
	 * Test grabbing a profile by activation token
	 **/

	public function testGetProfileByActivationToken() : void {
		//count the number of rows and save it for later
		$numRows = $this -> getConnection() -> getRowCount("profile");

		$profileId = generateUuidV4();
		$profile = new Profile($profileId, $this -> VALID_ACTIVATION, $this -> VALID_EMAIL, $this -> VALID_USERNAME, $this -> VALID_HASH);
		$profile -> insert($this -> getPDO());

		//grab the data from MySQL and enforce that the fields match expectations
		$pdoProfile = Profile::getProfileByProfileActivationToken($this -> getPDO(), $profile -> getProfileActivationToken());
		$this -> assertEquals($numRows + 1, $this -> getConnection() -> getRowCount("profile"));
		$this -> assertEquals($pdoProfile -> getProfileId(), $profileId);
		$this -> assertEquals($pdoProfile -> getProfileActivationToken(), $this-> VALID_ACTIVATION);
		$this -> assertEquals($pdoProfile -> getProfileEmail(), $this -> VALID_EMAIL);
		$this -> assertEquals($pdoProfile -> getProfileUsername(), $this -> VALID_USERNAME);
		$this -> assertEquals($pdoProfile -> getProfileHash(), $this -> VALID_HASH);
	}

	/**
	 * Test grabbing a profile by email
	 **/

	public function testGetProfileByEmail() : void {
		//count the number of rows and save it for later
		$numRows = $this -> getConnection() -> getRowCount("profile");

		$profileId = generateUuidV4();
		$profile = new Profile($profileId, $this -> VALID_ACTIVATION, $this -> VALID_EMAIL, $this -> VALID_USERNAME, $this -> VALID_HASH);
		$profile -> insert($this -> getPDO());

		//grab the data from MySQL and enforce that the data matches expectations
		$pdoProfile = Profile::getProfileByProfileEmail($this -> getPDO(), $profile -> getProfileEmail());
		$this -> assertEquals($numRows + 1, $this -> getConnection() -> getRowCount("profile"));
		$this -> assertEquals($pdoProfile -> getProfileId(), $profileId);
		$this -> assertEquals($pdoProfile -> getProfileActivationToken(), $this-> VALID_ACTIVATION);
		$this -> assertEquals($pdoProfile -> getProfileEmail(), $this -> VALID_EMAIL);
		$this -> assertEquals($pdoProfile -> getProfileUsername(), $this -> VALID_USERNAME);
		$this -> assertEquals($pdoProfile -> getProfileHash(), $this -> VALID_HASH);
	}

	/**
	 * Test retrieving a profile by username
	 **/

	public function testGetProfileByUsername() {
		//count the number of rows and save it for later
		$numRows = $this -> getConnection() -> getRowCount("profile");

		$profileId = generateUuidV4();
		$profile = new Profile($profileId, $this -> VALID_ACTIVATION, $this -> VALID_EMAIL, $this -> VALID_USERNAME, $this -> VALID_HASH);
		$profile -> insert($this -> getPDO());

		//grab the data from MySQL
		$results = Profile::getProfileByProfileUsername($this -> getPDO(), $this -> VALID_USERNAME);
		$this -> assertEquals($numRows + 1, $this -> getConnection() -> getRowCount("profile"));

		//enforce there are no other objects bleeding into profile
		$this -> assertContainsOnlyInstancesOf("ShellShock\\Capstone\\Profile", $results);

		//enforce that the results match the expectations
		$pdoProfile = $results[0];

		$this -> assertEquals($numRows + 1, $this -> getConnection() -> getRowCount("profile"));
		$this -> assertEquals($pdoProfile -> getProfileId(), $profileId);
		$this -> assertEquals($pdoProfile -> getProfileActivationToken(), $this-> VALID_ACTIVATION);
		$this -> assertEquals($pdoProfile -> getProfileEmail(), $this -> VALID_EMAIL);
		$this -> assertEquals($pdoProfile -> getProfileUsername(), $this -> VALID_USERNAME);
		$this -> assertEquals($pdoProfile -> getProfileHash(), $this -> VALID_HASH);
	}
}