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

	protected $VAILID_LOCATIONDATE = null;

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
	protected $VALID_LOCATIONIMAGECLOUDINARYID = null;

	/**
	 * cloudinary URL for the location Image
	 *
	 * @var $VALIDE_LOCATIONIMAGECLOUDINARYURL
	 */
	protected $VALID_LOCATIONIMAGECLOUDINARYURL = "bootcamp-coders.cnm.edu";

	/**
	 * text about the Location
	 *
	 * @var $VALID_LOCATIONTEXT
	 */
	protected $VALID_LOCATIONTEXT = "wow what a cool filmimg location i saw spongebob hes such a dream boat";

	/**
	 *
	 */


}