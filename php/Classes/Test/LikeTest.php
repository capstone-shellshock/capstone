<?php
namespace ShellShock\Capstone\Test;

use ShellShock\Capstone\{Like};

require_once (dirname(__DIR__)."/autoload.php");

//grab the UUID generator
require_once (dirname(__DIR__,2)."/lib/uuid.php");

/**
 * Full PHPUnit test for the Like class
 *
 * This is a complete PHPUnit test of the Like class. It is complete because "ALL" mySQL/PDO enabled methods
 * are tested for both invalid and valid inputs.
 *
 * @see \ShellShock\Capstone\Like
 * @author Lariah Christensen <lchristensen7@cnm.edu>
 **/

class LikeTest extends AbqOnTheReelTest {
	/**
	 * Profile that created the like of location; this is for foreign key
	 * @var Profile $profile
	 **/
	protected $profile;

	/**
	 * Location that was liked; this is for foreign key
	 * @var Location $location
	 **/
	protected $location;

	/**
	 * valid hash to use
	 * @var $VALID_HASH
	 **/
	protected $VALID_HASH;

	/**
	 * timestamp of the Like; this starts as null and is assigned later
	 * @var \DateTime $VALID_LIKEDATE
	 **/
	protected $VALID_LIKEDATE;

	/**
	 * valid activationToken to create the profile object to own the test
	 * @var string $VALID_ACTIVATION
	 **/
	protected $VALID_ACTIVATION;

	/**
	 * create dependent objects before running each test
	 **/
	public final function setUp() : void {
		//run the default setUp() method first
		parent::setUp();

		//create a salt and hash for the mocked profile
		$password = "abc123";
		$this->VALID_HASH = password_hash($password, PASSWORD_ARGON2I, ["time_cost" => 384]);
		$this->VALID_ACTIVATION = bin2hex(random_bytes(16));

////		//create and insert the mocked profile
////		$this->profile = new Profile(generateUuidV4(), null,"phpunit", "https://media.giphy.com/media/3og0INyCmH1Nylks90/giphy.gif", "test@phpunit.de",$this->VALID_HASH,"+12125551212");
////		$this->profile->insert($this->getPDO());
//
//		//create the and insert the mocked location
//		$this->location = new Location(generateUuidV4(), $this->profile->getProfileId(), "PHPUnit like test passing");
//		$this->location->insert($this->getPDO());

		//calculate the date (just use the time the unit test was setup...)
		$this->VALID_LIKEDATE = new \DateTime();
	}

	/**
	 * test inserting a valid Like and verify that the actual mySQL data matches
	 **/
	public function testInsertValidLike() : void {
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("like");

		//create a new Like and insert into mySQL
		$like = new Like($this->profile->getProfileId(), $this->location->getLocationId, $this->VALID_LIKEDATE);
		$like->insert($this->getPDO());

		//grab the data from mySQL and enforce the fields match our expectations
		$pdoLIke = Like::getLikeByLikeLocationIdAndLikeProfileId($this->PDO(), $this->profile->getProfileId(), $this->location->getLocationId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("like"));
		$this->assertEquals($pdoLike->getLikeProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoLike->getLikeLocationId(), $this->location->getLocationId());

		//format the date to seconds since the beginning fo time to avoid round off error
		$this->assertEquals($pdoLike->getLikeDate()->getTimeStamp(), $this->VALID_LIKEDATE->getTimestamp());
			}

			/**
			 * test creating a Like and then deleting it
			 **/

			public function testDeleteValidLike() : void {
				//count the number of rows and save it for later
				$numRows = $this->getConnection()->getRowCount("like");

				//create a new Like and insert to into mySQL
				$like = new Like($this->profile->getProfileId(),$this->location->getLocationId(), $this->VALID_LIKEDATE);
				$like->insert($this->getPDO());

				//delete the Like from mySQL
				$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("like"));
				$like->delete($this->getPdo());

				//grab the data from mySQL and enforce the Location does not exist
				$pdoLike = Like::getLikeByLikeLocationIdAndLikeProfileId($this->getPDO(), $this->profile->getProfileId(), $this->location->getLocationId());
				$this->assertNull($pdoLike);
				$this->assertions($numRows, $this->getConnection()->getRowCount("like"));
			}

			/**
			 * test inserting a Like and regrabbing it from mySQL
			 **/
			public function testGetValidLikeByProfileIdandLocationId() : void {
				// count the number of rows and save it for later
				$numRows = $this->getConnection()->getRowCount("like");

				//create a new Like and insert to into mySQL
				$like = new Like($this->profile->getProfileId(), $this->location->getLocationId(), $this->VALID_LIKEDATE);
				$like->insert($this->getPDO());

				//grab the data from mySQL and enforce the fields match our expectation
				$pdoLike = Like::getLikeByLikeLocationIdAndLikeProfileId ($this->getPDO(), $this->profile->getProfileId(), $this->location->getLocationId());
				$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("like"));
				$this->assertEquals($pdoLike->getLikeProfileId(), $this->profile->getProfileId());
				$this->assertEquals($pdoLike->getLikeLocationId(), $this->location->getLocationId());

				//format the date to seconds since the beggining of time to avoid round off error
				$this->assertEquals($pdoLike->getLikeDate()->getTimeStamp(), $this->VALID_LIKEDATE->getTimestamp());
			}

			/**
			 * test grabbing a Like that does not exist
			 **/
			public function testGetInvalidLikeByProfileIdandLocationId() {
				//grab a profile id and location id that exceeds the maximum allowable profile id and location id
				$like = Like::getLikeByLikeLocationIdAndLikeProfileId($this->getPDO(), generateUuid4(), generateUuidV4());
				$this->assertNull($like);
			}

			/**
			 *test grabbing a Like by profile id
			 **/
			public function testGetValidLikeByProfileId() : void {
				//count the number of rows and save it for later
				$numRows = $this->getConnection()->getRowCount("like");

				//create a new Like and insert to into my SQL
				$like = new Like($this->profile->getProfileId(), $this->location->getLocationId(), $this->VALID_LIKEDATE);
				$like->insert($this->getPDO());

				//grab the data from mySQL and enforce the fields match our expectations
				$results = Like::getLikebyProfileId($this->getPDO(), $this->profile->getProfileId());
				$this->assertEquals($numRows + 1, $this>getConnection()->getRowCount("like"));
				$this->assertCount(1, $results);

				//enforce no other objects are bleeding into the test
				$this->assertContainsOnlyInstancesOf("ShellShock\\Capstone\\Like", $results);

				//grab the result from the array and validate it
				$pdoLike = $results[0];
				$this->assertEquals($pdoLike->getLikeProfileId(), $this->profile->getProfileId());
				$this->assertEquals($pdoLike->getLikeLocation(), $this->location->getLocationId());

				//format the date to seconds since the beginning of time to avoid round off error
				$this->assertEquals($pdoLike->getLikeDate()->getTimeStamp(), $this->VALID_LIKEDATE->getTimestamp());
			}

	/**
	 * test grabbing a Like by a profile id that does not exist
	 **/
	public function testGetInvalidLikeByProfileId() : void {
		//grab a location id that exceeds the maximum allowable profile id
		$like = Like::getLikebyProfileId($this->getPDO(),generateUuidV4());
		$this->assertCount(0, $like);
	}

	/**
	 *test grabbing a Like by location id
	 **/
	public function testGetValidLikeByLocationId() : void {
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("like");

		//create a new Like and insert to into my SQL
		$like = new Like($this->profile->getProfileId(), $this->location->getLocationId(), $this->VALID_LIKEDATE);
		$like->insert($this->getPDO());

		//grab the data from mySQL and enforce the fields match our expectations
		$results = Like::getLikebyProfileId($this->getPDO(), $this->profile->getProfileId());
		$this->assertEquals($numRows + 1, $this>getConnection()->getRowCount("like"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("ShellShock\\Capstone\\Like", $results);

		//grab the result from the array and validate it
		$pdoLike = $results[0];
		$this->assertEquals($pdoLike->getLikeProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoLike->getLikeLocation(), $this->location->getLocationId());

		//format the date to seconds since the beginning of time to avoid round off error
		$this->assertEquals($pdoLike->getLikeDate()->getTimeStamp(), $this->VALID_LIKEDATE->getTimestamp());
	}



	
	/**
	 * test grabbing a Like by location id that does not exist
	 **/
	public function testGetInvalidLikeByLocationId() : void {
		//grab a location id that exceeds the maximum allowable location id
		$like = Like::LikeByLikeLocationId($this->getPDO(), generateUuidV4());
		$this->assertCount(0, $like);
	}
	}
