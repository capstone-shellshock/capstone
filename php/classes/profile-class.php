<?php

namespace shellShock\Capstone;

require_once("autoload.php");
require_once(dirname(__DIR__)."/vendor/autoload.php");
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

	

}
