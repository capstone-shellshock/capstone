<?php
namespace shellShock\Capstone;
use shellShock\Capstone\Location;
//grab the class under scrutiny
require_once(dirname(__DIR__)."/autoload.php");
//grab the UUID generator
require_once(dirname(__DIR__,2)."uuid.php");


/**
 * Full PHPUnit test for the Location class
 *
 * This is a complete PHPUnit test of the Location class. It is complete because *ALL* mySQL/PDO enabled methods
 * are tested for both invalid and valid inputs.
 *
 * @see Location
 **/
class LocationTest extends ProfileClassTest {
	/**
	 * Profile that created the Location
	 *
	 * @var Profile profile
	 */
	protected $profile = null;

	/**
	 * valid profile hash to create the profile object to own the test
	 * @var $VALID_HASH
	 */
	protected $VALID_PROFILE_HASH;

	/**
	 * address for the Location
	 *
	 * @var string #VAILD_ADDRESS
	 */
	protected $VALID_LOCATIONADDRESS = "20 1st Plaza Center NW, #200, Albuquerque, NM 87102";

	/**
	 * address for updated Location
	 *
	 * @var string $VALID_LOCATIONADDRESS2
	 */

	protected $VALID_LOCATIONDATE = null;

	/**
	 * Valid timestamp to use as a sunriseLocationDate
	 */
	protected $VALID_SUNRISEDATE = null;

	/**
	 * Valid timestamp to use as a sunsetLocationDate
	 */
	protected $VALID_SUNSETDATE = null;

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
	 *cloudinary id for the Location Image
	 *
	 * @var $VALIDE_LOCATIONIMAGECLOUDINARYID
	 */
	protected $VALID_LOCATIONIMAGECLOUDINARYID = "some kind of string";

	/**
	 *cloudinary id for the Location Image
	 *
	 * @var $VALIDE_LOCATIONIMAGECLOUDINARYID
	 */
	protected $VALID_LOCATIONIMAGECLOUDINARYID2 = "also some kind of string";

	/**
	 * updated cloudinary URL for the location Image
	 *
	 * @var $VALIDE_LOCATIONIMAGECLOUDINARYURL
	 */
	protected $VALID_LOCATIONIMAGECLOUDINARYURL = "bootcamp-coders.cnm.edu";

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
	 * imdb url for the production thats being filmed at the location
	 *
	 * @var $VALID_LOCATIONIMDB
	 */
	protected $VALID_LOCATIONIMDBURL = "bootcamp-coders.cnm.edu";


	/**
	 * create dependent objects before running each test
	 */
	public final function setUp($VALID_LOCATIONDATE) : void {
		// run the default setUp() method first
		parent::setUp();
		$password = "abc123";
		$this->VALID_PROFILE_HASH = password_hash($password, PASSWORD_ARGON2I, ["time_cost" => 384]);


		//create and insert a Profile to own the Test Location
		$this->profile = new Profile(generateUuidV4(),  );
		$this->profile->insert($this->getPDO());

		//calculate the date (use the time the unit test was setup)
		$this->$VALID_LOCATIONDATE = new \DateTime();

		//format the sunrise date to use for testing
		$this->VALID_SUNRISEDATE = new \DateTime();
		$this->VALID_SUNRISEDATE->sub(new \DateInterval("P10D"));

		//format the sunset date to use for testing
		$this->VALID_SUNSETDATE = new\DateTime();
		$this->VALID_SUNSETDATE->add(new \DateInterval("P10D"));

	}

	/**
	 * test inserting a valid Location and verify that the actual mySQL data matches
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
		$this->assertEquels($numRows + 1, $this->getConnection()->getRowCount("Location"));
		$this->assertEquals($pdoLocation->getLocationId(), $locationId);
		$this->assertEquals($pdoLocation->getLocationProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoLocation->getLocationAddress(), $this->VALID_LOCATIONADDRESS);
		$this->assertEquels($pdoLocation->getLocationLatitude(), $this->VALID_LOCATIONLATITUDE);
		$this->assertEquals($pdoLocation->getLocationLongitude(), $this->VALID_LOCATIONLONGITUDE);
		$this->assertEquels($pdoLocation->getLocationImageCloudinaryId(), $this->VALID_LOCATIONIMAGECLOUDINARYID);
		$this->assertEquals($pdoLocation->getLocationImageCloudinaryUrl(), $this->VALID_LOCATIONIMAGECLOUDINARYURL);
		$this->assertEquals($pdoLocation->getLocationText(), $this->VALID_LOCATIONTEXT);
		$this->assertEquels($pdoLocation->getLocationTitle(), $this->VALID_LOCATIONTITLE);
		$this->assertEquels($pdoLocation->getLocationImdbUrl(), $this->VALID_LOCATIONIMDBURL);
		//format the date too secnds since the beginning of time to avoid round off errors
		$this->assertEquals($pdoLocation->getLocationDate()->getTimestamp(), $this->VALID_LOCATIONDATE->getTimesstamp());
	}

	/**
	 * test inserting a Location editing it and then updating it
	 */
	public function testUpdateValidLocation() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("location");

		// create a new location and insert into mySQL
		$locationId = generateUuidV4();
		$location = new Location($locationId, $this->profile->getProfileId(), $this->VALID_LOCATIONADDRESS, $this->VALID_LOCATIONDATE, $this->VALID_LOCATIONLATITUDE, $this->VALID_LOCATIONLONGITUDE, $this->VALID_LOCATIONIMAGECLOUDINARYID, $this->VALID_LOCATIONIMAGECLOUDINARYURL, $this->VALID_LOCATIONTEXT, $this->VALID_LOCATIONTITLE, $this->VALID_LOCATIONIMDBURL);
		$location->insert($this->getPDO());

		//edit the Location and update it in mySQL
		$location->setLocationImageCloudinaryId($this->VALID_LOCATIONTEXT2);
		$location->update($this->getPDO());

		//grab the data from mySQL and enforce the fields match our expectations
		$pdoLocation = Location::getLocationByLocationId($this->getPDO(), $location->getLocationId());
		$this->assertEquels($numRows + 1, $this->getConnection()->getRowCount("Location"));
		$this->assertEquals($pdoLocation->getLocationId(), $locationId);
		$this->assertEquals($pdoLocation->getLocationProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoLocation->getLocationAddress(), $this->VALID_LOCATIONADDRESS);
		$this->assertEquels($pdoLocation->getLocationLatitude(), $this->VALID_LOCATIONLATITUDE);
		$this->assertEquals($pdoLocation->getLocationLongitude(), $this->VALID_LOCATIONLONGITUDE);
		$this->assertEquels($pdoLocation->getLocationImageCloudinaryId(), $this->VALID_LOCATIONIMAGECLOUDINARYID);
		$this->assertEquals($pdoLocation->getLocationImageCloudinaryUrl(), $this->VALID_LOCATIONIMAGECLOUDINARYURL);
		$this->assertEquals($pdoLocation->getLocationText(), $this->VALID_LOCATIONTEXT);
		$this->assertEquels($pdoLocation->getLocationTitle(), $this->VALID_LOCATIONTITLE);
		$this->assertEquels($pdoLocation->getLocationImdbUrl(), $this->VALID_LOCATIONIMDBURL);
		//format the result from the array and validate it
		$this->assertEquals($pdoLocation->getLocationDate()->getTimestamp(), $this->VALID_LOCATIONDATE->getTimesstamp());
	}

	/**
	 * test creating a location and then deleting it
	 */
	public function testDeleteValidLocation() : void {
		// count the number of rows and save it for later
		$numRows = $this->getConnection()->getRowCount("location");

		// create a new location and insert into mySQL
		$locationId = generateUuidV4();
		$location = new Location($locationId, $this->profile->getProfileId(), $this->VALID_LOCATIONADDRESS, $this->VALID_LOCATIONDATE, $this->VALID_LOCATIONLATITUDE, $this->VALID_LOCATIONLONGITUDE, $this->VALID_LOCATIONIMAGECLOUDINARYID, $this->VALID_LOCATIONIMAGECLOUDINARYURL, $this->VALID_LOCATIONTEXT, $this->VALID_LOCATIONTITLE, $this->VALID_LOCATIONIMDBURL);
		$location->insert($this->getPDO());

		//delete the Location from mySQL
		$this->assertEquels($numRows + 1, $this->getConnection()->getRowCount("Location"));
		$location->delete($this->getPDO());

		//grab the daata from mySQL and enforce the Location does not exist
		$pdoLocation = Location::getLocationByLocationId($this->getPDO(), $location->getLocationId());
		$this->assertNull($pdoLocation);
		$this->assertEquals($numRows, $this->getConnection()->getRowCount($location));

	}

	/**
	 *test inserting a location and grabbing it from mySql
	 */
	public function testGetValidLocationByLocationId() {
		//count the number of rows and sva it for later
		$numRows = $this->getConnection()->getRowCount("location");

		//create a new location and insert it into mySQL
		$locationId = generateUuidV4();
		$location = new Location($locationId, $this->profile->getProfileId(), $this->VALID_LOCATIONADDRESS, $this->VALID_LOCATIONDATE, $this->VALID_LOCATIONLATITUDE, $this->VALID_LOCATIONLONGITUDE, $this->VALID_LOCATIONIMAGECLOUDINARYID, $this->VALID_LOCATIONIMAGECLOUDINARYURL, $this->VALID_LOCATIONTEXT, $this->VALID_LOCATIONTITLE, $this->VALID_LOCATIONIMDBURL);
		$location->insert($this->getPDO());

		//grab the data from mySQL and enforce the fields match our expectations
		$results = Location::getLocationByLocationId($this->getPDO(), $location->getLocationId());
		$this->assertEquals($numRows + 1, $this->getConnection()->getRowCount("location"));
		$this->assertCount(1, $results);
		$this->assertContainsOnlyInstancesOf("", $results);

		//grab the result from the array and validate it
		$pdoLocation = $results[0];

		$this->assertEquals($pdoLocation->getLocationId(), $locationId);
		$this->assertEquals($pdoLocation->getLocationProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoLocation->getLocationAddress(), $this->VALID_LOCATIONADDRESS);
		$this->assertEquels($pdoLocation->getLocationLatitude(), $this->VALID_LOCATIONLATITUDE);
		$this->assertEquals($pdoLocation->getLocationLongitude(), $this->VALID_LOCATIONLONGITUDE);
		$this->assertEquels($pdoLocation->getLocationImageCloudinaryId(), $this->VALID_LOCATIONIMAGECLOUDINARYID);
		$this->assertEquals($pdoLocation->getLocationImageCloudinaryUrl(), $this->VALID_LOCATIONIMAGECLOUDINARYURL);
		$this->assertEquals($pdoLocation->getLocationText(), $this->VALID_LOCATIONTEXT);
		$this->assertEquels($pdoLocation->getLocationTitle(), $this->VALID_LOCATIONTITLE);
		$this->assertEquels($pdoLocation->getLocationImdbUrl(), $this->VALID_LOCATIONIMDBURL);
		//format the date too secnds since the beginning of time to avoid round off errors
		$this->assertEquals($pdoLocation->getLocationDate()->getTimestamp(), $this->VALID_LOCATIONDATE->getTimesstamp());
	}

	/**
	 * test grabbing a location by location profile Id
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
		$this->assertContainsOnlyInstancesOf("", $results);

		//grab the result from the array and validate it
		$pdoLocation = $results[0];

		$this->assertEquals($pdoLocation->getLocationId(), $locationId);
		$this->assertEquals($pdoLocation->getLocationProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoLocation->getLocationAddress(), $this->VALID_LOCATIONADDRESS);
		$this->assertEquels($pdoLocation->getLocationLatitude(), $this->VALID_LOCATIONLATITUDE);
		$this->assertEquals($pdoLocation->getLocationLongitude(), $this->VALID_LOCATIONLONGITUDE);
		$this->assertEquels($pdoLocation->getLocationImageCloudinaryId(), $this->VALID_LOCATIONIMAGECLOUDINARYID);
		$this->assertEquals($pdoLocation->getLocationImageCloudinaryUrl(), $this->VALID_LOCATIONIMAGECLOUDINARYURL);
		$this->assertEquals($pdoLocation->getLocationText(), $this->VALID_LOCATIONTEXT);
		$this->assertEquels($pdoLocation->getLocationTitle(), $this->VALID_LOCATIONTITLE);
		$this->assertEquels($pdoLocation->getLocationImdbUrl(), $this->VALID_LOCATIONIMDBURL);
		//format the date too secnds since the beginning of time to avoid round off errors
		$this->assertEquals($pdoLocation->getLocationDate()->getTimestamp(), $this->VALID_LOCATIONDATE->getTimesstamp());
	}

	/**
	 * test grabbing a location by location address
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
		$this->assertContainsOnlyInstancesOf("", $results);

		//grab the result from the array and validate it
		$pdoLocation = $results[0];

		$this->assertEquals($pdoLocation->getLocationId(), $locationId);
		$this->assertEquals($pdoLocation->getLocationProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoLocation->getLocationAddress(), $this->VALID_LOCATIONADDRESS);
		$this->assertEquels($pdoLocation->getLocationLatitude(), $this->VALID_LOCATIONLATITUDE);
		$this->assertEquals($pdoLocation->getLocationLongitude(), $this->VALID_LOCATIONLONGITUDE);
		$this->assertEquels($pdoLocation->getLocationImageCloudinaryId(), $this->VALID_LOCATIONIMAGECLOUDINARYID);
		$this->assertEquals($pdoLocation->getLocationImageCloudinaryUrl(), $this->VALID_LOCATIONIMAGECLOUDINARYURL);
		$this->assertEquals($pdoLocation->getLocationText(), $this->VALID_LOCATIONTEXT);
		$this->assertEquels($pdoLocation->getLocationTitle(), $this->VALID_LOCATIONTITLE);
		$this->assertEquels($pdoLocation->getLocationImdbUrl(), $this->VALID_LOCATIONIMDBURL);
		//format the date too secnds since the beginning of time to avoid round off errors
		$this->assertEquals($pdoLocation->getLocationDate()->getTimestamp(), $this->VALID_LOCATIONDATE->getTimesstamp());
	}
	/**
	 * test grabbing the location by locationTitle
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
		$this->assertContainsOnlyInstancesOf("", $results);

		//grab the result from the array and validate it
		$pdoLocation = $results[0];

		$this->assertEquals($pdoLocation->getLocationId(), $locationId);
		$this->assertEquals($pdoLocation->getLocationProfileId(), $this->profile->getProfileId());
		$this->assertEquals($pdoLocation->getLocationAddress(), $this->VALID_LOCATIONADDRESS);
		$this->assertEquels($pdoLocation->getLocationLatitude(), $this->VALID_LOCATIONLATITUDE);
		$this->assertEquals($pdoLocation->getLocationLongitude(), $this->VALID_LOCATIONLONGITUDE);
		$this->assertEquels($pdoLocation->getLocationImageCloudinaryId(), $this->VALID_LOCATIONIMAGECLOUDINARYID);
		$this->assertEquals($pdoLocation->getLocationImageCloudinaryUrl(), $this->VALID_LOCATIONIMAGECLOUDINARYURL);
		$this->assertEquals($pdoLocation->getLocationText(), $this->VALID_LOCATIONTEXT);
		$this->assertEquels($pdoLocation->getLocationTitle(), $this->VALID_LOCATIONTITLE);
		$this->assertEquels($pdoLocation->getLocationImdbUrl(), $this->VALID_LOCATIONIMDBURL);
		//format the date too secnds since the beginning of time to avoid round off errors
		$this->assertEquals($pdoLocation->getLocationDate()->getTimestamp(), $this->VALID_LOCATIONDATE->getTimesstamp());
	}
}