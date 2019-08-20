<?php
namespace ShellShock\Capstone\Test;
use ShellShock\Capstone\{Location, Profile};
//grab the class under scrutiny
require_once(dirname(__DIR__)."/autoload.php");
//grab the UUID generator
require_once(dirname(__DIR__,2)."/lib/uuid.php");


/**
 * Full PHPUnit Test for the Location class
 *
 * This is a complete PHPUnit Test of the Location class. It is complete because *ALL* mySQL/PDO enabled methods
 * are tested for both invalid and valid inputs.
 *
 * @see Location
 **/
class LocationTest extends AbqOnTheReelTest {

	/**
	 * Profile that created the Location
	 *
	 * @var Profile profile
	 */
	protected $profile = null;

	/**
	 * valid profile hash to create the profile object to own the Test
	 * @var $VALID_HASH
	 */
	protected $VALID_PROFILE_HASH;

	/**
	 * address for the Location
	 *
	 * @var string $VALID_ADDRESS
	 */
	protected $VALID_LOCATIONADDRESS = "20 1st Plaza Center NW, #200, Albuquerque, NM 87102";

	/**
	 * address for updated Location
	 *
	 * @var string $VALID_LOCATIONDATE
	 */

	protected $VALID_LOCATIONDATE;

	/**
	 * Valid Timestamp to use as a sunriseLocationDate
	 */
	protected $VALID_SUNRISEDATE;

	/**
	 * Valid Timestamp to use as a sunsetLocationDate
	 */
	protected $VALID_SUNSETDATE;

	/**
	 *cloudinary id for the Location Image
	 *
	 * @var $VALID_LOCATIONIMAGECLOUDINARYID
	 */
	protected $VALID_LOCATIONIMAGECLOUDINARYID = "WHHAHSHHS kind of string";

	/**
	 *cloudinary id for the Location Image
	 *
	 * @var $VALID_LOCATIONIMAGECLOUDINARYID
	 */
	protected $VALID_LOCATIONIMAGECLOUDINARYID2 = "also some kind of string";

	/**
	 * updated cloudinary URL for the location Image
	 *
	 * @var $VALID_LOCATIONIMAGECLOUDINARYURL
	 */
	protected $VALID_LOCATIONIMAGECLOUDINARYURL = "bootcamp-coders.cnm.edu";

	/**
	 * imdb url for the production thats being filmed at the location
	 *
	 * @var $VALID_LOCATIONIMDB
	 */
	protected $VALID_LOCATIONIMDBURL = "bootcamp-coders.cnm.edu";

	/**
	 * latitude of the Location
	 *
	 * @var float $VALID_LOCATIONLATITUDE
	 */
	protected $VALID_LOCATIONLATITUDE = 50.53445;

	/**
	 *longitude of the location
	 *
	 * @var float $VALID_LOCATIONLONGITUDE
	 */
	protected $VALID_LOCATIONLONGITUDE = 135.343568;

	/**
	 * text about the Location
	 *
	 * @var $VALID_LOCATIONTEXT
	 */
	protected $VALID_LOCATIONTEXT = "wow what a cool filmimg location. I saw Spongebob he's such a dream boat";

	/**
	 * updated text about the Location
	 *
	 * @var $VALID_LOCATIONTEXT
	 */
	protected $VALID_LOCATIONTEXT2 = "wow what a cool filmimg location. I saw Spongebob he's such a dork";

	/**
	 *title of the production thats being filmed at the location
	 *
	 * @var $VALID_LOCATIONTITLE
	 */
	protected $VALID_LOCATIONTITLE = "Some Movie Title";

	/**
	 *updated title of the production thats being filmed at the location
	 *
	 * @var $VALID_LOCATIONTITLE
	 */
	protected $VALID_LOCATIONTITLE2 = "Some Movie Title";


	/**
	 * create dependent objects before running each Test
	 */
	public final function setUp() : void {
		// run the default setUp() method first
		parent::setUp();
		$password = "abc123";
		$this->VALID_PROFILE_HASH = password_hash($password, PASSWORD_ARGON2I, ["time_cost" => 384]);


		//create and insert a Profile to own the Test Location
		$this->profile = new Profile(generateUuidV4(), null, "ajaramillo208@cnm.edu", "ajaramillo208", $this->VALID_PROFILE_HASH);
		$this->profile->insert($this->getPDO());

		// calculate the date (just use the time the unit test was setup...)
		$this->VALID_LOCATIONDATE = new \DateTime();

		//format the sunrise date to use for testing
		$this->VALID_SUNRISEDATE = new \DateTime();
		$this->VALID_SUNRISEDATE->sub(new \DateInterval("P10D"));

		//format the sunset date to use for testing
		$this->VALID_SUNSETDATE = new\DateTime();
		$this->VALID_SUNSETDATE->add(new \DateInterval("P10D"));

	}

	/**
	 * Test inserting a valid location and verify that the actual mySQL data matches
	 */
	public function testInsertValidLocation() : void {
		//count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("location");

		// create a new location and insert into mySQL
		$locationId = generateUuidV4();
		$location = new Location($locationId, $this->profile->getProfileId(), $this->VALID_LOCATIONADDRESS, $this->VALID_LOCATIONDATE, $this->VALID_LOCATIONLATITUDE, $this->VALID_LOCATIONLONGITUDE, $this->VALID_LOCATIONIMAGECLOUDINARYID, $this->VALID_LOCATIONIMAGECLOUDINARYURL, $this->VALID_LOCATIONTEXT, $this->VALID_LOCATIONTITLE, $this->VALID_LOCATIONIMDBURL);
		$location->insert($this->getPDO());

		//grab the data from mySQL and enforce the fields match our expectations
		$pdoLocation = Location::getLocationByLocationId($this->getPDO(), $location->getLocationId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("location"));
		$this->assertEquals($pdoLocation->getLocationId(), $locationId);
		$this->assertEquals($pdoLocation->getLocationProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoLocation->getLocationAddress(), $this->VALID_LOCATIONADDRESS);
		//format the date too seconds since the beginning of time to avoid round off errors
		$this->assertEquals($pdoLocation->getLocationDate()->getTimestamp(), $this->VALID_LOCATIONDATE->getTimestamp());
		$this->assertEquals($pdoLocation->getLocationLatitude(), $this->VALID_LOCATIONLATITUDE);
		$this->assertEquals($pdoLocation->getLocationLongitude(), $this->VALID_LOCATIONLONGITUDE);
		$this->assertEquals($pdoLocation->getLocationImageCloudinaryId(), $this->VALID_LOCATIONIMAGECLOUDINARYID);
		$this->assertEquals($pdoLocation->getLocationImageCloudinaryUrl(), $this->VALID_LOCATIONIMAGECLOUDINARYURL);
		$this->assertEquals($pdoLocation->getLocationText(), $this->VALID_LOCATIONTEXT);
		$this->assertEquals($pdoLocation->getLocationTitle(), $this->VALID_LOCATIONTITLE);
		$this->assertEquals($pdoLocation->getLocationImdbUrl(), $this->VALID_LOCATIONIMDBURL);
	}

	/**
	 * Test inserting a Location editing it and then updating it
	 */
	public function testUpdateValidLocation() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("location");

		// create a new location and insert into mySQL
		$locationId = generateUuidV4();
		$location = new Location($locationId, $this->profile->getProfileId(), $this->VALID_LOCATIONADDRESS, $this->VALID_LOCATIONDATE, $this->VALID_LOCATIONLATITUDE, $this->VALID_LOCATIONLONGITUDE, $this->VALID_LOCATIONIMAGECLOUDINARYID, $this->VALID_LOCATIONIMAGECLOUDINARYURL, $this->VALID_LOCATIONTEXT, $this->VALID_LOCATIONTITLE, $this->VALID_LOCATIONIMDBURL);
		$location->insert($this->getPDO());

		//edit the Location and update it in mySQL
		$location->setLocationText($this->VALID_LOCATIONTEXT2);
		$location->update($this->getPDO());

		//grab the data from mySQL and enforce the fields match our expectations
		$pdoLocation = Location::getLocationByLocationId($this->getPDO(), $location->getLocationId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("location"));
		$this->assertEquals($pdoLocation->getLocationId(), $locationId);
		$this->assertEquals($pdoLocation->getLocationProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoLocation->getLocationAddress(), $this->VALID_LOCATIONADDRESS);
		//format the result from the array and validate it
		$this->assertEquals($pdoLocation->getLocationDate()->getTimestamp(), $this->VALID_LOCATIONDATE->getTimestamp());
		$this->assertEquals($pdoLocation->getLocationLatitude(), $this->VALID_LOCATIONLATITUDE);
		$this->assertEquals($pdoLocation->getLocationLongitude(), $this->VALID_LOCATIONLONGITUDE);
		$this->assertEquals($pdoLocation->getLocationImageCloudinaryId(), $this->VALID_LOCATIONIMAGECLOUDINARYID);
		$this->assertEquals($pdoLocation->getLocationImageCloudinaryUrl(), $this->VALID_LOCATIONIMAGECLOUDINARYURL);
		$this->assertEquals($pdoLocation->getLocationText(), $this->VALID_LOCATIONTEXT2);
		$this->assertEquals($pdoLocation->getLocationTitle(), $this->VALID_LOCATIONTITLE);
		$this->assertEquals($pdoLocation->getLocationImdbUrl(), $this->VALID_LOCATIONIMDBURL);

	}

	/**
	 * Test creating a location and then deleting it
	 */
	public function testDeleteValidLocation() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("location");

		// create a new location and insert into mySQL
		$locationId = generateUuidV4();
		$location = new Location($locationId, $this->profile->getProfileId(), $this->VALID_LOCATIONADDRESS, $this->VALID_LOCATIONDATE, $this->VALID_LOCATIONLATITUDE, $this->VALID_LOCATIONLONGITUDE, $this->VALID_LOCATIONIMAGECLOUDINARYID, $this->VALID_LOCATIONIMAGECLOUDINARYURL, $this->VALID_LOCATIONTEXT, $this->VALID_LOCATIONTITLE, $this->VALID_LOCATIONIMDBURL);
		$location->insert($this->getPDO());

		//delete the Location from mySQL
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("location"));
		$location->delete($this->getPDO());

		//grab the daata from mySQL and enforce the Location does not exist
		$pdoLocation = Location::getLocationByLocationId($this->getPDO(), $location->getLocationId());
		$this->assertNull($pdoLocation);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount("location"));

	}

	/**
	 *Test inserting a location and grabbing it from mySql
	 */
	public function testGetValidLocationByLocationId() {
		//count the number of rows and sva it for later
		$numRows = $this->getConnection()->getRowCount("location");

		//create a new location and insert it into mySQL
		$locationId = generateUuidV4();
		$location = new Location($locationId, $this->profile->getProfileId(), $this->VALID_LOCATIONADDRESS, $this->VALID_LOCATIONDATE, $this->VALID_LOCATIONLATITUDE, $this->VALID_LOCATIONLONGITUDE, $this->VALID_LOCATIONIMAGECLOUDINARYID, $this->VALID_LOCATIONIMAGECLOUDINARYURL, $this->VALID_LOCATIONTEXT, $this->VALID_LOCATIONTITLE, $this->VALID_LOCATIONIMDBURL);
		$location->insert($this->getPDO());

		//grab the data from mySQL and enforce the fields match our expectations
		$pdoLocation = Location::getLocationByLocationId($this->getPDO(), $location->getLocationId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("location"));
		$this->assertEquals($pdoLocation->getLocationId(), $locationId);
		$this->assertEquals($pdoLocation->getLocationProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoLocation->getLocationAddress(), $this->VALID_LOCATIONADDRESS);
		//format the result from the array and validate it
		$this->assertEquals($pdoLocation->getLocationDate()->getTimestamp(), $this->VALID_LOCATIONDATE->getTimestamp());
		$this->assertEquals($pdoLocation->getLocationLatitude(), $this->VALID_LOCATIONLATITUDE);
		$this->assertEquals($pdoLocation->getLocationLongitude(), $this->VALID_LOCATIONLONGITUDE);
		$this->assertEquals($pdoLocation->getLocationImageCloudinaryId(), $this->VALID_LOCATIONIMAGECLOUDINARYID);
		$this->assertEquals($pdoLocation->getLocationImageCloudinaryUrl(), $this->VALID_LOCATIONIMAGECLOUDINARYURL);
		$this->assertEquals($pdoLocation->getLocationText(), $this->VALID_LOCATIONTEXT);
		$this->assertEquals($pdoLocation->getLocationTitle(), $this->VALID_LOCATIONTITLE);
		$this->assertEquals($pdoLocation->getLocationImdbUrl(), $this->VALID_LOCATIONIMDBURL);

	}

	/**
	 * Test grabbing a location by location profile Id
	 */
	public function testGetValidLocationByLocationProfileId() {
		//count the number of rows and sva it for later
		$numRows = $this->getConnection()->getRowCount("location");

		//create a new location and insert it into mySQL
		$locationId = generateUuidV4();
		$location = new Location($locationId, $this->profile->getProfileId(), $this->VALID_LOCATIONADDRESS, $this->VALID_LOCATIONDATE, $this->VALID_LOCATIONLATITUDE, $this->VALID_LOCATIONLONGITUDE, $this->VALID_LOCATIONIMAGECLOUDINARYID, $this->VALID_LOCATIONIMAGECLOUDINARYURL, $this->VALID_LOCATIONTEXT, $this->VALID_LOCATIONTITLE, $this->VALID_LOCATIONIMDBURL);
		$location->insert($this->getPDO());

		//grab the data from mySQL and enforce the fields match our expectations
		$results = Location::getLocationByLocationProfileId($this->getPDO(), $location->getLocationProfileId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("location"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("ShellShock\\Capstone\\Location", $results);

		//grab the result from the array and validate it
		$pdoLocation = $results[0];

		$this->assertEquals($pdoLocation->getLocationId(), $locationId);
		$this->assertEquals($pdoLocation->getLocationProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoLocation->getLocationAddress(), $this->VALID_LOCATIONADDRESS);
		//format the date too secnds since the beginning of time to avoid round off errors
		$this->assertEquals($pdoLocation->getLocationDate()->getTimestamp(), $this->VALID_LOCATIONDATE->getTimestamp());
		$this->assertEquals($pdoLocation->getLocationLatitude(), $this->VALID_LOCATIONLATITUDE);
		$this->assertEquals($pdoLocation->getLocationLongitude(), $this->VALID_LOCATIONLONGITUDE);
		$this->assertEquals($pdoLocation->getLocationImageCloudinaryId(), $this->VALID_LOCATIONIMAGECLOUDINARYID);
		$this->assertEquals($pdoLocation->getLocationImageCloudinaryUrl(), $this->VALID_LOCATIONIMAGECLOUDINARYURL);
		$this->assertEquals($pdoLocation->getLocationText(), $this->VALID_LOCATIONTEXT);
		$this->assertEquals($pdoLocation->getLocationTitle(), $this->VALID_LOCATIONTITLE);
		$this->assertEquals($pdoLocation->getLocationImdbUrl(), $this->VALID_LOCATIONIMDBURL);

	}

	/**
	 * Test grabbing a location by location address
	 */
	public function testGetValidLocationByLocationAddress() {
		//count the number of rows and sva it for later
		$numRows = $this->getConnection()->getRowCount("location");

		//create a new location and insert it into mySQL
		$locationId = generateUuidV4();
		$location = new Location($locationId, $this->profile->getProfileId(), $this->VALID_LOCATIONADDRESS, $this->VALID_LOCATIONDATE, $this->VALID_LOCATIONLATITUDE, $this->VALID_LOCATIONLONGITUDE, $this->VALID_LOCATIONIMAGECLOUDINARYID, $this->VALID_LOCATIONIMAGECLOUDINARYURL, $this->VALID_LOCATIONTEXT, $this->VALID_LOCATIONTITLE, $this->VALID_LOCATIONIMDBURL);
		$location->insert($this->getPDO());

		//grab the data from mySQL and enforce the fields match our expectations
		$results = Location::getLocationByLocationAddress($this->getPDO(), $location->getLocationAddress());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("location"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("ShellShock\\Capstone\\Location", $results);

		//grab the result from the array and validate it
		$pdoLocation = $results[0];

		$this->assertEquals($pdoLocation->getLocationId(), $locationId);
		$this->assertEquals($pdoLocation->getLocationProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoLocation->getLocationAddress(), $this->VALID_LOCATIONADDRESS);
		//format the date too secnds since the beginning of time to avoid round off errors
		$this->assertEquals($pdoLocation->getLocationDate()->getTimestamp(), $this->VALID_LOCATIONDATE->getTimestamp());
		$this->assertEquals($pdoLocation->getLocationLatitude(), $this->VALID_LOCATIONLATITUDE);
		$this->assertEquals($pdoLocation->getLocationLongitude(), $this->VALID_LOCATIONLONGITUDE);
		$this->assertEquals($pdoLocation->getLocationImageCloudinaryId(), $this->VALID_LOCATIONIMAGECLOUDINARYID);
		$this->assertEquals($pdoLocation->getLocationImageCloudinaryUrl(), $this->VALID_LOCATIONIMAGECLOUDINARYURL);
		$this->assertEquals($pdoLocation->getLocationText(), $this->VALID_LOCATIONTEXT);
		$this->assertEquals($pdoLocation->getLocationTitle(), $this->VALID_LOCATIONTITLE);
		$this->assertEquals($pdoLocation->getLocationImdbUrl(), $this->VALID_LOCATIONIMDBURL);

	}
	/**
	 * Test grabbing the location by locationTitle
	 */
	public function testGetValidLocationByLocationTitle() {
		//count the number of rows and sva it for later
		$numRows = $this->getConnection()->getRowCount("location");

		//create a new location and insert it into mySQL
		$locationId = generateUuidV4();
		$location = new Location($locationId, $this->profile->getProfileId(), $this->VALID_LOCATIONADDRESS, $this->VALID_LOCATIONDATE, $this->VALID_LOCATIONLATITUDE, $this->VALID_LOCATIONLONGITUDE, $this->VALID_LOCATIONIMAGECLOUDINARYID, $this->VALID_LOCATIONIMAGECLOUDINARYURL, $this->VALID_LOCATIONTEXT, $this->VALID_LOCATIONTITLE, $this->VALID_LOCATIONIMDBURL);
		$location->insert($this->getPDO());

		//grab the data from mySQL and enforce the fields match our expectations
		$results = Location::getLocationByLocationTitle($this->getPDO(), $location->getLocationTitle());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("location"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("ShellShock\\Capstone\\Location", $results);

		//grab the result from the array and validate it
		$pdoLocation = $results[0];

		$this->assertEquals($pdoLocation->getLocationId(), $locationId);
		$this->assertEquals($pdoLocation->getLocationProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoLocation->getLocationAddress(), $this->VALID_LOCATIONADDRESS);
		//format the date too secnds since the beginning of time to avoid round off errors
		$this->assertEquals($pdoLocation->getLocationDate()->getTimestamp(), $this->VALID_LOCATIONDATE->getTimestamp());
		$this->assertEquals($pdoLocation->getLocationLatitude(), $this->VALID_LOCATIONLATITUDE);
		$this->assertEquals($pdoLocation->getLocationLongitude(), $this->VALID_LOCATIONLONGITUDE);
		$this->assertEquals($pdoLocation->getLocationImageCloudinaryId(), $this->VALID_LOCATIONIMAGECLOUDINARYID);
		$this->assertEquals($pdoLocation->getLocationImageCloudinaryUrl(), $this->VALID_LOCATIONIMAGECLOUDINARYURL);
		$this->assertEquals($pdoLocation->getLocationText(), $this->VALID_LOCATIONTEXT);
		$this->assertEquals($pdoLocation->getLocationTitle(), $this->VALID_LOCATIONTITLE);
		$this->assertEquals($pdoLocation->getLocationImdbUrl(), $this->VALID_LOCATIONIMDBURL);

	}

	/**
	 * Test grabbing all locations
	 */
	public function testGetAllValidLocations() {
		//count the number of rows and sva it for later
		$numRows = $this->getConnection()->getRowCount("location");

		//create a new location and insert it into mySQL
		$locationId = generateUuidV4();
		$location = new Location($locationId, $this->profile->getProfileId(), $this->VALID_LOCATIONADDRESS, $this->VALID_LOCATIONDATE, $this->VALID_LOCATIONLATITUDE, $this->VALID_LOCATIONLONGITUDE, $this->VALID_LOCATIONIMAGECLOUDINARYID, $this->VALID_LOCATIONIMAGECLOUDINARYURL, $this->VALID_LOCATIONTEXT, $this->VALID_LOCATIONTITLE, $this->VALID_LOCATIONIMDBURL);
		$location->insert($this->getPDO());

		//grab the data from mySQL and enforce the fields match our expectations
		$results = Location::getALLLocations($this->getPDO());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("location"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("ShellShock\\Capstone\\Location", $results);

		//grab the result from the array and validate it
		$pdoLocation = $results[0];

		$this->assertEquals($pdoLocation->getLocationId(), $locationId);
		$this->assertEquals($pdoLocation->getLocationProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoLocation->getLocationAddress(), $this->VALID_LOCATIONADDRESS);
		//format the date too secnds since the beginning of time to avoid round off errors
		$this->assertEquals($pdoLocation->getLocationDate()->getTimestamp(), $this->VALID_LOCATIONDATE->getTimestamp());
		$this->assertEquals($pdoLocation->getLocationLatitude(), $this->VALID_LOCATIONLATITUDE);
		$this->assertEquals($pdoLocation->getLocationLongitude(), $this->VALID_LOCATIONLONGITUDE);
		$this->assertEquals($pdoLocation->getLocationImageCloudinaryId(), $this->VALID_LOCATIONIMAGECLOUDINARYID);
		$this->assertEquals($pdoLocation->getLocationImageCloudinaryUrl(), $this->VALID_LOCATIONIMAGECLOUDINARYURL);
		$this->assertEquals($pdoLocation->getLocationText(), $this->VALID_LOCATIONTEXT);
		$this->assertEquals($pdoLocation->getLocationTitle(), $this->VALID_LOCATIONTITLE);
		$this->assertEquals($pdoLocation->getLocationImdbUrl(), $this->VALID_LOCATIONIMDBURL);

	}
}