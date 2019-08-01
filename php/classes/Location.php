<?php

namespace shellShock\Capstone;

require_once("autoload.php");
require_once(dirname(__DIR__) . "/vendor/autoload.php");

use http\Exception\BadQueryStringException;
use Ramsey\Uuid\Uuid;

/**
 * This is a class for the Production Location of movies being filmed in ABQ
 *
 * This class will let people add and store the data of the movie and its filming location. this is a low level entity
 * that is held by the entity profile
 */

class Location {
	use validateUuid;
	use ValidaterDate;

	/**
	 * id for this location; this is the primary key
	 *
	 * @var Uuid $locationId
	 */
	private $locationId;

	/**
	 * the profile that made this location; this is a foreign key
	 *
	 * @var Uuid $locationProfileId
	 */
	private $locationProfileId;

	/**
	 * address of the filming location
	 *
	 * @var string $locationAddress
	 */
	private $locationAddress;

	/**
	 * date that the profile entered this location
	 *
	 * @var \DateTime $LocationDate
	 */
	private $locationDate;

	/**
	 * latitude of the filming location
	 *
	 * @var int $lacationLatitude
	 */
	private $locationLatitude;

	/**
	 *longitude of the filming location
	 *
	 * @var int $locationLatitude
	 */
	private $locationLongitude;

	/**
	 *cloudinarys id for the location image
	 *
	 * @var string $locationImageCloudinaryId
	 */
	private $locationImageCloudinaryId;

	/**
	 * Url from cloudinary for the location image
	 *
	 * @var string $locationImageCloudinaryUrl
	 */
	private $locationImageCloudinaryUrl;

	/**
	 * text to describe what the user saw at the filming location
	 *
	 * @var string $locationText
	 */
	private $locationText;

	/**
	 * title of the production (i.e. film or tv) thats being filmed
	 *
	 * @var string $locationTitle;
	 */
	private $locationTitle;

	/**
	 * Url to the productions IMDB page (a way for us to verify that this production is not fake)
	 *
	 * @var string $locationImdbUrl
	 */
	private $locationImdbUrl;

	/**
	 * cunstructor for this Location
	 *
	 * @param string|Uuid $newLocationId id of this location or null if a new location
	 * @param string|Uuid $newLocationProfileId id of the profile that made this location
	 * @param string $newLocationAddress string containing the address of the location or null if no address was added
	 * @param \DateTime|string|null $newLocationDate date and time location was added or null if set to current date and time
	 * @param integer $newLocationLatitude
	 * @param integer $newLocationLongitude
	 * @param string $newLocationImageCloudinaryId string cloudinary id for the image of the location or null if no image is uploaded
	 * @param string $newLocationImageCloudinaryUrl string cloudinary Url for the image of the location or null if no image was uploaded
	 * @param string $newLocationText string the text details of the location
	 * @param string $newLocationTitle string the title of the production being filmed at the location
	 * @param string $newLocationImdbUrl string Url to the Imdb page for the production being filmed at the location
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 *
	 */
	public function __construct($newLocationId, $newLocationProfileId, $newLocationAddress, $newLocationDate, $newLocationLatitude, $newLocationLongitude, $newLocationImageCloudinaryId, $newLocationImageCloudinaryUrl, $newLocationText, $newLocationTitle, $newLocationImdbUrl) {
		try {
			$this->setLocationId($newLocationId);
			$this->setLocationProfileId($newLocationProfileId);
			$this->setLocationAddress($newLocationAddress);
			$this->setLocationDate($newLocationDate);
			$this->setLocationLatitude($newLocationLatitude);
			$this->setLocationLongitude($newLocationLongitude);
			$this->setLocationImageCloudinaryId($newLocationImageCloudinaryId);
			$this->setLocationImageCloudinaryUrl($newLocationImageCloudinaryUrl);
			$this->setLocationText($newLocationText);
			$this->setLocationTitle($newLocationTitle);
			$this->setLocationImdbUrl($newLocationImdbUrl);
		}

		//determine what exception type was thrown
		catch (\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for location id
	 *
	 * @return Uuid value of location Id
	 */
	public function getLocationId() : uuid {
		return($this->locationId);
	}

	/**
	 * mutator method for location id
	 *
	 * @param Uuid| string $newLocationId new value of tweet id
	 * @throws \RangeException if %newLocationId is not positive
	 * @throws |\TypeError if $newLocationId is not a Uuid or string
	 *
	 */
	public function setLocationId($newLocationId) : void {
		try {
			$uuid = self::validateUuid($newLocationId);
		} catch(\InvalidArgumentException | \RangeException | \TypeError | \Exception $exception ) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}

		//convert and store the location id
		$this->locationId = $uuid;
	}

	
}