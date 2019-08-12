<?php

namespace shellShock\Capstone;

require_once("autoload.php");
require_once(dirname(__DIR__)."/classes/autoload.php");

use Ramsey\Uuid\Uuid;

/**
 * Creates an object for the profile. Inserts profileId, profileActivationToken, profileEmail, profileUsername,
 * and profileHash.
 *
 * @author Justin Murphy <jmurphy33@cnm.edu>
 **/

class Profile implements \JsonSerializable {
	use ValidateDate;
	use ValidateUuid;

	/**
	 * Primary key === profileId
	 **/

	private $profileId;

	/**
	 * Activation token for profile
	 **/

	private $profileActivationToken;

	/**
	 * profile email
	 **/

	private $profileEmail;

	/**
	 * profile username
	 **/

	private $profileUsername;

	/**
	 * profile password hash
	 **/

	private $profileHash;

	/**
	 * constructor
	 *
	 * @param string | Uuid $profileId id for profile
	 * @param string $newProfileActivationToken for security
	 * @param string $newProfileEmail for email storage
	 * @param string $newProfileUsername for username storage
	 * @param string $newProfileHash for hashed password storage
	 * @throws \RangeException if entries are too long
	 * @throws \InvalidArgumentException if email address format is incorrect
	 * @throws \TypeError if data entered does not meet type requirements
	 * @throws \Exception if any other exception is found
	 **/

	public function __construct($newProfileId, $newProfileActivationToken, $newProfileEmail, $newProfileUsername, string $newProfileHash) {
		try {
			$this->setProfileId($newProfileId);
			$this->setProfileActivationToken($newProfileActivationToken);
			$this->setProfileEmail($newProfileEmail);
			$this->setProfileUsername($newProfileUsername);
			$this->setProfileHash($newProfileHash);
		} catch(\RangeException | \InvalidArgumentException | \TypeError | \Exception $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * accessor method for profileId
	 *
	 * @return Uuid for profile
	 **/

	public function getProfileId() : Uuid {
		return($this->profileId);
	}

	/**
	 *mutator method for profileId
	 *
	 * @param Uuid | string $newProfileId new value of profile Id
	 * @throws \TypeError if $newProfileId is not a UUID or string
	 * @throws \Exception if any other error is found
	 **/

	public function setProfileId($newProfileId) : void {
		try {
			$newUuid = self::validateUuid($newProfileId);
		} catch(\TypeError |\Exception $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		//stores new uuid
		$this->profileId = $newUuid;
	}

	/**
	 * accessor method for profile activation token
	 *
	 * @return string for token
	 **/

	public function getProfileActivationToken() : ?string {
		return ($this->profileActivationToken);
	}

	/**
	 * mutator for the profile activation token
	 *
	 * @param string $newProfileActivationToken new value of activation token
	 * @throws \RangeException if string entered does not meet field requirements
	 * @throws \Exception if any other error is found
	 **/

	public function setProfileActivationToken(?string $newProfileActivationToken) : void {

		if($newProfileActivationToken === null) {
			$this->profileActivationToken = null;
			return;
		}

		$newProfileActivationToken = strtolower(trim($newProfileActivationToken));
		if(ctype_xdigit($newProfileActivationToken) === false) {
			throw(new \RangeException("Activation token not valid"));
		}

		if(strlen($newProfileActivationToken) !== 32) {
			throw(new \RangeException("Activation token must be 32 characters"));
		}

		$this->profileActivationToken = $newProfileActivationToken;
	}

	/**
	 * accessor for profile email
	 *
	 * @return string for email
	 **/

	public function getProfileEmail() : string {
		return ($this -> profileEmail);
	}

	/**
	 * mutator for profile email
	 *
	 * @param string new value of email
	 * @throws \InvalidArgumentException if $newProfileEmail is not a valid email
	 * @throws \RangeException if email is too long
	 * @throws \Exception if any other error is found
	 **/

	public function setProfileEmail(string $newProfileEmail) : void {

		//verifies email security
		$newProfileEmail = trim($newProfileEmail);
		$newProfileEmail = filter_var($newProfileEmail, FILTER_VALIDATE_EMAIL);
		if(empty($newProfileEmail) === true) {
			throw(new \InvalidArgumentException("Email is insecure or invalid"));
		}

		//checks if email is too large for database
		if(strlen($newProfileEmail) > 128) {
			throw(new \RangeException("Email is too large"));
		}

		$this -> profileEmail = $newProfileEmail;
	}

	/**
	 * accessor profile username
	 *
	 * @returns string for profile username
	 **/

	public function getProfileUsername() : string {
		return ($this->profileUsername);
	}

	/**
	 * mutator for profile username
	 *
	 * @param string $newProfileUsername
	 * @throws \InvalidArgumentException if username is not a string
	 * @throws \RangeException if new username is longer than 32 characters
	 * @throws \TypeError if username is not a string
	 **/

	public function setProfileUsername(string $newProfileUsername) : void {

		$newProfileUsername = trim($newProfileUsername);
		$newProfileUsername = filter_var($newProfileUsername, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newProfileUsername) === true) {
			throw(new \InvalidArgumentException("Username is empty or taken"));
		}

		//checks length of username
		if(strlen($newProfileUsername) > 32) {
			throw(new \RangeException("Username is too long"));
		}

		$this->profileUsername = $newProfileUsername;
	}

	/**
	 * accessor method for profileHash
	 *
	 * @return string of hash
	 **/

	public function getProfileHash() : string {

		return ($this->profileHash);
	}

	/**
	 * mutator method for profileHash
	 *
	 * @param string $newProfileHash
	 * @throws \InvalidArgumentException if hash is not secure
	 * @throws \RangeException if hash is not proper length
	 * @throws \TypeError if hash is not a string
	 **/

	public function setProfileHash(string $newProfileHash) : void {

		$newProfileHash = trim($newProfileHash);
		if(empty($newProfileHash) === true) {
			throw(new \InvalidArgumentException("Hash field is empty or insecure"));

		}

		$hashInfo = password_get_info($newProfileHash);
		if($hashInfo["algoName"] !== "argon2i") {
			throw(new \InvalidArgumentException("Profile has is not valid"));

		}

		if (strlen($newProfileHash) !== 97) {
			throw(new \RangeException("Profile hash must be 97 characters"));

		}

		$this -> profileHash = $newProfileHash;
	}


	/**
	 * inserts profile into the table
	 *
	 * @param \PDO $pdo PDO Connection object
	 * @throws \PDOException when MySQL related error occurs
	 * @throws \TypeError if $pdo is not a PDO connection
	 **/

	public function insert(\PDO $pdo) : void {

		//create a query template
		$query = "INSERT INTO profile(profileId, profileActivationToken, profileEmail, profileUsername, profileHash)
							VALUES(:profileId, :profileActivationToken, :profileEmail, :profileUsername, :profileHash)";
		$statement = $pdo -> prepare($query);

		$parameters = ["profileId" => $this -> profileId -> getBytes(), "profileActivationToken" => $this -> profileActivationToken,
							"profileEmail" => $this -> profileEmail, "profileUsername" => $this -> profileUsername, "profileHash" => $this -> profileHash];
		$statement -> execute($parameters);
	}

	/**
	 * Removes this profile from MySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when a MySQL related error occurs
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/

	public function delete(\PDO $pdo) : void {

		//create query template
		$query = "DELETE FROM profile WHERE profileId = :profileId";
		$statement = $pdo -> prepare($query);

		//Bind the member variables to the place holder in the template
		$parameters = ["profileId" => $this -> profileId -> getBytes()];
		$statement -> execute($parameters);
	}

	/**
	 * updates the profile in MySQL
	 *
	 * @param \PDO $pdo PDO connection object
	 * @throws \PDOException when MySQL related error occurs
	 * @throws \TypeError if $pdo is not a PDO connection object
	 **/

	public function update(\PDO $pdo) : void {

		//create query template
		$query = "UPDATE profile SET profileActivationToken = :profileActivationToken, profileEmail = :profileEmail,
						profileUsername = :profileUsername, profileHash = :profileHash WHERE profileId = :profileId";
		$statement = $pdo -> prepare($query);

		//binds the member variables to the place holders in the template
		$parameters = ["profileId" => $this -> profileId -> getBytes(), "profileActivationToken" => $this -> profileActivationToken,
								"profileEmail" => $this -> profileEmail, "profileUsername" => $this -> profileUsername, "profileHash" => $this -> profileHash];
		$statement -> execute($parameters);
	}

	/**
	 * Gets the profile by profileId
	 *
	 * @param \PDO $pdo PDO connection object
	 * @param $profileId profile Id to search for
	 * @return profile|null author or null if not related
	 * @throws \PDOException when MySQL related error occurs
	 * @throws \TypeError when a variable is not the correct data type
	 **/

	public static function getProfileByProfileId(\PDO $pdo, $profileId) : ?Profile {

		//cleans profile Id before searching
		try{
			$profileId = self::validateUuid($profileId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			throw(new \PDOException($exception -> getMessage(), 0, $exception));
		}

		//create query template
		$query = "SELECT profileId, profileActivationToken, profileEmail, profileUsername, profileHash FROM profile
						WHERE profileId = :profileId";
		$statement = $pdo -> prepare($query);

		//binds the profile Id to the place holder
	}

	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() : array {

		$fields = get_object_vars($this);
		$fields["profileId"] = $this ->profileId->toString();
		unset($fields["profileActivationToken"]);
		unset($fields["profileHash"]);
		return ($fields);
	}

}
