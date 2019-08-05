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
	public function __construct( $newLocationId, $newLocationProfileId, ?string $newLocationAddress, $newLocationDate, $newLocationLatitude, $newLocationLongitude, ?string $newLocationImageCloudinaryId, ?string $newLocationImageCloudinaryUrl, $newLocationText, $newLocationTitle, $newLocationImdbUrl) {
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
	 * @param Uuid| string $newLocationId new value of location id
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

	/**
	 * accessor method for location profile id
	 *
	 * @return uuid value of location profile id
	 */

	public function getLocationProfileId(): Uuid {
		return $this->locationProfileId;
	}

	/**
	 * mutator method for location profile id
	 *
	 * @param uuid | string $newLocationProfileId new value of location profile id
	 * @throws \RangeException if %newLocationProfileId is not positive
	 * @throws |\TypeError if $newLocationProfileId is not a integer
	 */
	public function setLocationProfileId($newLocationProfileId) : void {
		try {
			$uuid = self::validateUuid($newLocationProfileId);
		} catch(\InvalidArgumentException | \RangeException | \TypeError | \Exception $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		// convert and store the location profile id
		$this->locationProfileId = $uuid;
	}

	/**
	 * accessor method for location Address
	 *
	 * @return string
	 */
	public function getLocationAddress(): string {
		return $this->locationAddress;
	}

	/**
	 * mutator method for location address
	 *
	 *
	 */
	public function setLocationAddress($newLocationAddress) : void {

	}


	/**
	 * accessor method for location date
	 *
	 * @return \DateTime value of location date
	 */
	public function getLocationDate(): \DateTime {
		return $this->locationDate;
	}

	/**
	 * mutator method for location date
	 *
	 * @param \DateTime|string|null $newLocationDate string location date as a DateTime object pr string (or null to load the current time)
	 * @throws \InvalidArgumentException if $newLocationDate is not a valid object or string
	 * @throws \RangeException if $newLocationDate is a date that does not exist
	 */
	public function setLocationDate($newLocationDate): void {
		// base case: if the date is null, use the current date and time
		if($newLocationDate === null) {
			$this->locationDate = $newLocationDate;
			return;
		}
			//store the location date using the ValidateDate trait
			try {
				$newLocationDate = self::validateDateTime($newLocationDate);
			} catch(\InvalidArgumentException | \RangeException $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
			}
		$this->locationId = $newLocationDate;
	}

	/**
	 * accessor method for location latitude
	 *
	 * @return int for location latitude
	 */
	public function getLocationLatitude(): int {
		return $this->locationLatitude;
	}

	/**
	 * mutator method for location latitude
	 *
	 * @param int $locationLatitude
	 */
	public function setLocationLatitude(int $locationLatitude): void {
		$this->locationLatitude = $locationLatitude;
	}

	/**
	 * accessor method for location longitude
	 *
	 * @return float for location latitude
	 */
	public function getLocationLongitude(): int {
		return $this->locationLatitude;
	}

	/**
	 * mutator method for location longitude
	 *
	 * @param float $locationLongitude
	 */
	public function setLocationLongitude(int $locationLongitude): void {
		$this->locationLongitude = $locationLongitude;
	}

	/**
	 * accessor method for location image cloudinary id
	 *
	 * @return string|null value for location image cloudinary id
	 */
	public function getLocationImageCloudinaryId(): string {
		return $this->locationImageCloudinaryId;
	}

	/**
	 * mutator method for location image cloudinary id
	 *
	 *
	 */
	/**
	 * @param string $locationImageCloudinaryId
	 */
	public function setLocationImageCloudinaryId(string $locationImageCloudinaryId): void {
		$this->locationImageCloudinaryId = $locationImageCloudinaryId;
	}


	/**
	 * accessor method for location image cloudinary url
	 *
	 * @return string|null value for location image cloudinary url
	 */
	public function getLocationImageCloudinaryUrl(): string {
		return $this->locationImageCloudinaryUrl;
	}

	/**
	 * mutator method for location Image Cloudinary Url
	 *
	 * @param string $ new value of location image cloudinary url
	 * @throws \InvalidArgumentException if $locationImageCloudinaryUrl is not a string or insecure
	 * @throws \RangeException if $locationImageCloudinaryUrl is < 64 characters
	 * @throws \TypeError if $locationImageCloudinaryUrl is not a string
	 */
	public function setLocationImageCloudinaryUrl(?string $newLocationImageCloudinaryUrl): void {
		if($newLocationImageCloudinaryUrl === NULL) {
			$this->locationImageCloudinaryUrl = null;
			return;
		}
		//verify image is secure
		$newLocationImageCloudinaryUrl = trim($newLocationImageCloudinaryUrl);
		$newLocationImageCloudinaryUrl = filter_var($newLocationImageCloudinaryUrl, FILTER_SANITIZE_STRING);

		//make sure image cloudinary url is not empty
		if(empty($newLocationImageCloudinaryUrl) === true) {
			throw(new \InvalidArgumentException("location image is either empty or insecure"));
		}
		//make sure the image cloudinary url will fit in the database
		if(strlen($newLocationImageCloudinaryUrl) > 128) {
			throw( \RangeException("location image must be 128 characters or less"));
		}
		// store the location image cloudinary url
		$this->locationImageCloudinaryUrl = $newLocationImageCloudinaryUrl;
	}

	/**
	 * accessor method for location text
	 *
	 * @return string value of text
	 */
	public function getLocationText(): string {
		return $this->locationText;
	}

	/**
	 * mutator method for location text
	 *
	 * @param string $newLocationText string actual text about the location
	 * @throws \InvalidArgumentException if $newLocationText is not a string or insecure
	 * @throws \RangeException if $newLocationText is < 300 characters
	 * @throws \TypeError if $newLocationText is not a string
	 */
	/**
	 * @param string $locationText
	 */
	public function setLocationText(string $newLocationText): void {
		//verify the text is secure
		$newLocationText = trim($newLocationText);
		$newLocationText = filter_var($newLocationText, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

		if(empty($newLocationText) === true) {
			throw(new \InvalidArgumentException("text is empty please tell us what you saw at this location"));
		}
		//verify the text will fit in the database
		if(strlen($newLocationText) === true) {
			throw(new\RangeException("text must be under 300 characters"));
		}
		$this->locationText = $newLocationText;
	}

	/**
	 * accessor method for location Title
	 *
	 * @return string value of title
	 */
	public function getLocationTitle(): string {
		return $this->locationTitle;
	}

	/**
	 * @param string $locationTitle
	 */
	public function setLocationTitle(string $newLocationTitle): void {
		// verify the title is secure
		$newLocationTitle = trim($newLocationTitle);
		$newLocationTitle = filter_var($newLocationTitle, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

		//verify the title is not null
		if(empty($newLocationTitle) === true) {
			throw(new \InvalidArgumentException("Title is empty or insecure"));
		}

		//verify the title will fit in the database
		if(strlen($newLocationTitle) > 128) {
			throw(new\RangeException("Title is to large"));
		}
		$this->locationTitle = $newLocationTitle;
	}

}