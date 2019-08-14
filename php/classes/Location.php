<?php

namespace shellShock\Capstone;

require_once("autoload.php");
require_once(dirname(__DIR__) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;

/**
 * This is a class for the Production Location of movies being filmed in ABQ
 *
 * This class will let people add and store the data of the movie and its filming location. this is a low level entity
 * that is held by the entity profile
 */

class Location implements \JsonSerializable {
	use validateUuid;
	use ValidateDate;

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
	 * @param float $newLocationLatitude
	 * @param float $newLocationLongitude
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
	public function __construct( $newLocationId, $newLocationProfileId, ?string $newLocationAddress, $newLocationDate, ?float $newLocationLatitude, ?float $newLocationLongitude, ?string $newLocationImageCloudinaryId, ?string $newLocationImageCloudinaryUrl, $newLocationText, $newLocationTitle, $newLocationImdbUrl) {
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
	 * @param string new value of $newLocationAddress
	 * @throws \InvalidArgumentException if $newLocationAddress is not a blob or insecure
	 * @throws \RangeException if $newLocationAddress is < 3000 characters
	 * @throws \TypeError if $newLocationAddress string is not a string
	 */
	public function setLocationAddress($newLocationAddress) : void {
		if($newLocationAddress === NULL) {
			$this->locationAddress = null;
			return;
		}
		//make sure new location address is secure
		$newLocationAddress = trim($newLocationAddress);
		$newLocationAddress = filter_var($newLocationAddress, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

		//make sure new location address is not empty
		if(empty($newLocationAddress) === true) {
			throw(new \InvalidArgumentException ("address is either empty or insecure"));
		}
			//make sure address will fit in the database
			if(strlen($newLocationAddress) > 3000) {
				throw(new \RangeException("address must be 3000 characters or less"));
			}
			//Store address in the database
		$this->locationAddress = $newLocationAddress;
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
	 * @throws \Exception
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
	 * @return float for location latitude
	 */
	public function getLocationLatitude(): float {
		return $this->locationLatitude;
	}

	/**
	 * mutator method for location latitude
	 *
	 * @param float $newLocationLatitude value of latitude
	 * @throws \InvalidArgumentException if $newLocationLatitude is not a float or insecure
	 * @throws \RangeException if $newLocationLatitude is not between -90 and 90
	 */
	public function setLocationLatitude(float $newLocationLatitude): void {
		if($newLocationLatitude === NULL) {
			$this->locationLatitude =$newLocationLatitude;
			return;
		}

		//make sure latitude is in range
		if(floatval($newLocationLatitude) < -90) {
			throw(new\RangeException("latitude is not between -90 and 90"));
		}
		if(floatval($newLocationLatitude) > 90) {
			throw(new\RangeException("latitude is not between -90 and 90"));
		}

		//store latitude in the database
		$this->locationLatitude = $newLocationLatitude;
	}

	/**
	 * accessor method for location longitude
	 *
	 * @return float for location latitude
	 */
	public function getLocationLongitude(): float {
		return $this->locationLatitude;
	}

	/**
	 * mutator method for location longitude
	 *
	 * @param float $newLocationLongitude value of longitude
	 * @throws \InvalidArgumentException if $newLocationLongitude is not a float or insecure
	 * @throws \RangeException if $newLocationLongitude is not between -90 and 90
	 */
	public function setLocationLongitude(float $newLocationLongitude): void {
		if($newLocationLongitude === NULL) {
			$this->locationLatitude =$newLocationLongitude;
			return;
		}

		//make sure latitude is in range
		if(floatval($newLocationLongitude) < -180) {
			throw(new\RangeException("latitude is not between -180 and 180"));
		}
		if(floatval($newLocationLongitude) > 180) {
			throw(new\RangeException("latitude is not between -180 and 180"));
		}
		//store longitude in the database
		$this->locationLongitude = $newLocationLongitude;
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
	 *@param string $newLocationImageCloudinaryId
	 *@throws \InvalidArgumentException if $newLocationImageCloudinaryId is not a string or insecure
	 *@throws \RangeException if $newLocationImageCloudinaryId is < 128 characters
	 *@throws \TypeError if $newLocationImageCloudinaryId is not a string
	 */
	public function setLocationImageCloudinaryId(?string $newLocationImageCloudinaryId): void {
		if ($newLocationImageCloudinaryId === NULL) {
			$this->locationImageCloudinaryUrl = null;
			return;
		}
		//verify the cloudinary image id is secure
		$newLocationImageCloudinaryId = trim($newLocationImageCloudinaryId);
		$newLocationImageCloudinaryId = filter_var($newLocationImageCloudinaryId, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

		//make sure image cloudinary id is not empty
		if(empty($newLocationImageCloudinaryId)=== true) {
			throw(new\InvalidArgumentException ("cloudinary id is either empty or insecure"));
		}
		//make sure the cloudinary id will fit in the database
		if(strlen($newLocationImageCloudinaryId) > 128)
			throw(new \RangeException("cloudinary id must be 128 characters or less"));

		//store image cloudinary id
		$this->locationImageCloudinaryId = $newLocationImageCloudinaryId;
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
	 * @param string $newLocationImageCloudinaryUrl new value of location image cloudinary url
	 * @throws \InvalidArgumentException if $newLocationImageCloudinaryUrl is not a string or insecure
	 * @throws \RangeException if $newLocationImageCloudinaryUrl is < 255 characters
	 * @throws \TypeError if $newLocationImageCloudinaryUrl is not a string
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
		if(strlen($newLocationImageCloudinaryUrl) > 255) {
			throw(new\RangeException("location image must be 128 characters or less"));
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
		//convert and store location text
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
	 * mutator method for location title
	 *
	 * @param string $newLocationTitle
	 * @throws \InvalidArgumentException if $newLocationTitle is not a string or insecure
	 * @throws \RangeException if $newLocationTitle is < 128 characters
	 * @throws \TypeError if $newLocationTitle is not a string
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
		//convert and store location title
		$this->locationTitle = $newLocationTitle;
	}

	/**
	 * accessor method for imdb Url
	 *
	 * @return string value of Imdb Url
	 */
	public function getLocationImdbUrl(): string {
		return $this->locationImdbUrl;
	}

	/**
	 * mutator method Imdb Url
	 *
	 * @param string $newLocationImdbUrl new value of location Imdb Url
	 * @throws \InvalidArgumentException if $newLocationImdbUrl is not a string or insecure
	 * @throws \RangeException if $newLocationImdbUrl is > 128 characters
	 * @throws \TypeError if  $newLocationImdbUrl is not a string
	 */
	public function setLocationImdbUrl(string $newLocationImdbUrl): void {
		// verify the title is secure
		$newLocationImdbUrl = trim($newLocationImdbUrl);
		$newLocationImdbUrl = filter_var($newLocationImdbUrl,FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

		//verify the imdb url is not null
		if(empty($newLocationImdbUrl) === true) {
			throw(new\InvalidArgumentException("Imdb Url is empty or insecure"));
		}

		//verify the title will fit in the database
		if(strlen($newLocationImdbUrl) > 255) {
			throw(new\RangeException("Url is to large"));
		}

		//convert and store Imdb Url
		$this->locationImdbUrl = $newLocationImdbUrl;
	}


	/**
	 * inserts this location into mySQL
	 *
	 * @param \PDO $pdo connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 */
	public function insert(\PDO $pdo) : void {

		//create query template
		$query = "INSERT INTO location(locationId, locationProfileId, locationAddress, locationDate, locationLatitude, locationLongitude, locationImageCloudinaryId, locationImageCloudinaryUrl, locationText, locationTitle, locationImdbUrl) VALUES (:locationId, :locationProfileID, :locationAddress, :locationDate, :locationLatitude, :locationLongitude, :locationImageCloudinaryId, :locationImageCloudinaryUrl, :locationText, :locationTitle, :locationImdbUrl)";
		$statement = $pdo->prepare($query);

		//bind the member variables to the place holders in the template
		$formattedDate = $this->locationDate->format("Y-m-d H:i:s.u");
		$parameters = ["locationId" => $this->locationId->getBytes(), "locationProfileId" => $this->locationProfileId->getBytes(), "locationAddress" => $this->locationAddress, "locationDate" => $formattedDate, "locationLatitude" => $this->locationLatitude, "locationLongitude" => $this->locationLongitude, "locationImageCloudinaryId" => $this->locationImageCloudinaryId, "locationImageCloudinaryUrl" => $this->locationImageCloudinaryUrl, "locationText" => $this->locationText, "locationTitle" => $this->locationTitle, "locationImdbUrl" => $this->locationImdbUrl];
		$statement->execute($parameters);
	}

	/**
	 * deletes this location from mySQL
	 *
	 * @params \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError if PDO is not a PDO exception object
	 *
	 **/
	public function delete(\PDO $pdo) : void {

		//create query template
		$query = "DELETE FROM location WHERE locationId = :locationId";
		$statement = $pdo->prepare($query);

		//bind the member variables to the place holder in the template
		$parameters = ["locationId" => $this->locationId->getBytes()];
		$statement->execute($parameters);
	}

	/**
	 * updates this location in mySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when mySQL errors occur
	 * @throws \TypeError if $pdo is not a PDO connection object
	 */
	public function update (\PDO $pdo) : void {

		//create query template
		$query = "UPDATE location SET locationId = :locationId, locationProfileId = :locationProfileId, locationAddress = :locationAddress, locationDate = :locationDate , locationLatitude = :locationLatitude, locationLongitude = :locationLongitude, locationImageCloudinaryId = :locationImageCloudinaryId, locationImageCloudinaryUrl = :locationImageCloudinaryUrl, locationText = :locationText, locationTitle = :locationTitle, locationImdbUrl = :locationImdbUrl WHERE locationID = :locationId";
		$statement = $pdo->prepare($query);

		//bind the member variables to the place holders in the template
		$formattedDate = $this->locationDate->format("Y-m-d H:i:s.u");
		$parameters = ["locationId" => $this->locationId->getBytes(), "locationProfileId" => $this->locationProfileId->getBytes(), "locationAddress" => $this->locationAddress, "locationDate" => $formattedDate, "locationLatitude" => $this->locationLatitude, "locationLongitude" => $this->locationLongitude, "locationImageCloudinaryId" => $this->locationImageCloudinaryId, "locationImageCloudinaryUrl" => $this->locationImageCloudinaryUrl, "locationText" => $this->locationText, "locationTitle" => $this->locationTitle, "locationImdbUrl" => $this->locationImdbUrl];
		$statement->execute($parameters);
	}

	/**
	 * gets the location by location Id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $locationId location address to search for
	 * @return Location
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 * */

	public static function getLocationByLocationId (\PDO $pdo, string $locationId) : \SplFixedArray {
		//sanitize the description before searching
		$locationId = trim($locationId);
		$locationId = filter_var($locationId, FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($locationId) === true) {
			throw(new \PDOException("location Id is invalid"));
		}

		//escape any mySQL wild cards
		$locationId = str_replace("_","\\_", str_replace("%", "\\%", $locationId));

		//create query template
		$query = "SELECT locationId, locationProfileID, locationAddress, locationDate, locationLatitude, locationLongitude, locationImageCloudinaryId, locationImageCloudinaryUrl, locationText, locationTitle, locationImdbUrl FROM location WHERE locationId LIKE :locationId ";
		$statement = $pdo->prepare($query);

		//bind the location to the place holder in the template
		$locationId = "%$locationId%";
		$parameters = ["locationId" => $locationId];
		$statement->execute($parameters);

		//build an array of locations
		$locations = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row =$statement->fetch()) !== false) {
			try {
				$location = new Location ($row["locationId"], $row["locationProfileId"], $row["locationAddress"], $row["locationDate"], $row["locationLatitude"], $row["locationLongitude"], $row["locationImageCloudinaryId"], $row["locationImageCloudinaryUrl"], $row["locationText"], $row["locationTitle"], $row["locationImdbUrl"]);
				$locations[$locations->key()] = $location;
				$locations->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($locations);
	}

	/**
	 *  gets the location by location profile id
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $locationProfileId location address to search for
	 * @return Location
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 */
	public static function getLocationByLocationProfileId (\PDO $pdo, string $locationProfileId) : \SplFixedArray {
		//sanitize the description before searching
		$locationProfileId = trim($locationProfileId);
		$locationProfileId = filter_var($locationProfileId, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($locationProfileId) === true) {
			throw(new \PDOException("location Profile Id is invalid"));
		}

		//escape any mySQL wild cards
		$locationProfileId = str_replace("_", "\\_", str_replace("%", "\\%", $locationProfileId));

		//create query template
		$query = "SELECT locationId, locationProfileID, locationAddress, locationDate, locationLatitude, locationLongitude, locationImageCloudinaryId, locationImageCloudinaryUrl, locationText, locationTitle, locationImdbUrl FROM location WHERE locationProfileId LIKE :locationProfileId ";
		$statement = $pdo->prepare($query);

		//bind the location to the place holder in the template
		$locationProfileId = "%$locationProfileId%";
		$parameters = ["locationProfileId" => $locationProfileId];
		$statement->execute($parameters);

		//build an array of locations
		$locations = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row =$statement->fetch()) !== false) {
			try {
				$location = new Location ($row["locationId"], $row["locationProfileId"], $row["locationAddress"], $row["locationDate"], $row["locationLatitude"], $row["locationLongitude"], $row["locationImageCloudinaryId"], $row["locationImageCloudinaryUrl"], $row["locationText"], $row["locationTitle"], $row["locationImdbUrl"]);
				$locations[$locations->key()] = $location;
				$locations->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($locations);
	}
	/**
	 * gets the location by location address
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $locationAddress location address to search for
	 * @return Location
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 */
	public static function getLocationByLocationAddress(\PDO $pdo, string $locationAddress) : \SplFixedArray {
		//sanitize the description before searching
		$locationAddress = trim($locationAddress);
		$locationAddress = filter_var($locationAddress, FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($locationAddress) === true) {
			throw(new \PDOException("location address is invalid"));
		}

		//escape any mySQL wild cards
		$locationAddress = str_replace("_","\\_", str_replace("%", "\\%", $locationAddress));

		//create query template
		$query = "SELECT locationId, locationProfileID, locationAddress, locationDate, locationLatitude, locationLongitude, locationImageCloudinaryId, locationImageCloudinaryUrl, locationText, locationTitle, locationImdbUrl FROM location WHERE locationAddress LIKE :locationAddress ";
		$statement = $pdo->prepare($query);

		//bind the location to the place holder in the template
		$locationAddress = "%$locationAddress%";
		$parameters = ["locationAddress" => $locationAddress];
		$statement->execute($parameters);

		//build an array of locations
		$locations = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row =$statement->fetch()) !== false) {
			try {
				$location = new Location ($row["locationId"], $row["locationProfileId"], $row["locationAddress"], $row["locationDate"], $row["locationLatitude"], $row["locationLongitude"], $row["locationImageCloudinaryId"], $row["locationImageCloudinaryUrl"], $row["locationText"], $row["locationTitle"], $row["locationImdbUrl"]);
				$locations[$locations->key()] = $location;
				$locations->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($locations);
	}

		/**
		 * gets the location by location Title
		 *
		 * @param \PDO $pdo PDO connection object
		 * @param string $locationTitle location title to search for
		 * @return Location
		 * @throws \PDOException when mySQL related errors occur
		 * @throws \TypeError when variables are not the correct data type
		 **/
	public static function getLocationByLocationTitle (\PDO $pdo, string $locationTitle) : \SplFixedArray {
		//sanitize the description before searching
		$locationTitle = trim($locationTitle);
		$locationTitle = filter_var($locationTitle, FILTER_SANITIZE_STRING,FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($locationTitle) === true) {
			throw(new \PDOException("location title is invalid"));
		}

		//escape any mySQL wild cards
		$locationTitle = str_replace("_","\\_", str_replace("%", "\\%", $locationTitle));

		//create query template
		$query = "SELECT locationId, locationProfileID, locationAddress, locationDate, locationLatitude, locationLongitude, locationImageCloudinaryId, locationImageCloudinaryUrl, locationText, locationTitle, locationImdbUrl FROM location WHERE locationTitle LIKE :locationTitle ";
		$statement = $pdo->prepare($query);

		//bind the location to the place holder in the template
		$locationTitle = "%$locationTitle%";
		$parameters = ["locationTitle" => $locationTitle];
		$statement->execute($parameters);

		//build an array of locations
		$locations = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row =$statement->fetch()) !== false) {
			try {
				$location = new Location ($row["locationId"],$row["locationProfileId"], $row["locationAddress"], $row["locationDate"], $row["locationLatitude"], $row["locationLongitude"], $row["locationImageCloudinaryId"], $row["locationImageCloudinaryUrl"], $row["locationText"], $row["locationTitle"], $row["locationImdbUrl"]);
				$locations[$locations->key()] = $location;
				$locations->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($locations);
	}

	/**
	 * gets the location by location Imdb Url
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param string $locationImdbUrl location title to search for
	 * @return Location
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 **/
	public static function getLocationByLocationImdbUrl (\PDO $pdo, string $locationImdbUrl) : \SplFixedArray {
		//sanitize the description before searching
		$locationImdbUrl = trim($locationImdbUrl);
		$locationImdbUrl = filter_var($locationImdbUrl, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($locationImdbUrl) === true) {
			throw(new \PDOException("location Profile Imdb Url is invalid"));
		}

		//escape any mySQL wild cards
		$locationImdbUrl = str_replace("_", "\\_", str_replace("%", "\\%", $locationImdbUrl));

		//create query template
		$query = "SELECT locationId, locationProfileID, locationAddress, locationDate, locationLatitude, locationLongitude, locationImageCloudinaryId, locationImageCloudinaryUrl, locationText, locationTitle, locationImdbUrl FROM location WHERE locationImdbUrl LIKE :locationImdbUrl ";
		$statement = $pdo->prepare($query);

		//bind the location to the place holder in the template
		$locationImdbUrl = "%$locationImdbUrl%";
		$parameters = ["locationImdbUrl" => $locationImdbUrl];
		$statement->execute($parameters);

		//build an array of locations
		$locations = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row =$statement->fetch()) !== false) {
			try {
				$location = new Location ($row["locationId"], $row["locationProfileId"], $row["locationAddress"], $row["locationDate"], $row["locationLatitude"], $row["locationLongitude"], $row["locationImageCloudinaryId"], $row["locationImageCloudinaryUrl"], $row["locationText"], $row["locationTitle"], $row["locationImdbUrl"]);
				$locations[$locations->key()] = $location;
				$locations->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($locations);
	}

	/**
	 *  gets the all locations
	 *
	 * @param \PDO $pdo PDO connection object
	 * @return Location
	 * @throws \PDOException when mySQL related errors occur
	 * @throws \TypeError when variables are not the correct data type
	 * **/
	public static function getAllLocations (\PDO $pdo) : \SplFixedArray {
		//create query template
		$query = "SELECT locationId, locationProfileID, locationAddress, locationDate, locationLatitude, locationLongitude, locationImageCloudinaryId, locationImageCloudinaryUrl, locationText, locationTitle, locationImdbUrl FROM location";
		$statement = $pdo->prepare($query);
		$statement->execute();

		//build an array of locations
		$locations = new \SplFixedArray($statement->rowCount());
		$statement->setFetchMode(\PDO::FETCH_ASSOC);
		while(($row =$statement->fetch()) !== false) {
			try {
				$location = new Location ($row["locationId"], $row["locationProfileId"], $row["locationAddress"], $row["locationDate"], $row["locationLatitude"], $row["locationLongitude"], $row["locationImageCloudinaryId"], $row["locationImageCloudinaryUrl"], $row["locationText"], $row["locationTitle"], $row["locationImdbUrl"]);
				$locations[$locations->key()] = $location;
				$locations->next();
			} catch(\Exception $exception) {
				// if the row couldn't be converted rethrow it
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}
		}
		return($locations);
	}

	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() : array {
		$fields = get_object_vars($this);

		$fields["locationId"] = $this->locationId->toString();
		$fields["locationProfileId"] =$this->locationProfileId->toString();
		return($fields);
	}

}