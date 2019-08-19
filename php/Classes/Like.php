<?php
namespace ShellShock\Capstone;

require_once ("autoload.php");
require_once (dirname(__DIR__)."/vendor/autoload.php");

use Ramsey\Uuid\Uuid;

	/**
	 * Creates a class for the like of location where films are being located
	 *
	 * This is  a class for a profile to like someones filming location
	 **/

	class Like implements \JsonSerializable {
		use ValidateDate;
		use ValidateUuid;

		/**
		 * id of the location being liked
		 * @var Uuid $likeLocationId
		 **/
		private $likeLocationId ;

		/**
		 * id of the profile that is liking the location
		 * @var Uuid $likeProfileId
		 **/
		private $likeProfileId ;

		/**
		 * constructor for this Like
		 *
		 * @param string|Uuid $newLikeLocationId id of the parent Location
		 * @param string|Uuid $newLikeProfileId id of the parent Profile
		 * @throws \InvalidArgumentException if data types are not InvalidArgumentException
		 * @throws \RangeException if data values are out of bounds (e.g., strings too  long, negative integer)
		 * @throws \TypeError if data types violate type hints
		 * @throws \Exception if some other exception is thrown
		 **/

		public function __construct($newLikeLocationId,$newLikeProfileId) {
			//use the utator methods to do the work fo us!
			try {
				$this->setLikeLocationId($newLikeLocationId);
				$this->setLikeProfileId($newLikeProfileId);
			} catch (\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
				// determine what exception type was thrown
				$exceptionType = get_class($exception);
				throw (new $exception($exception->getMessage(), 0, $exception));
			}
		}

		/**
		 * accessor method for like location id
		 *
		 * @return Uuid value of like location id
		 **/
		public function  getLikeLocationId($newLikeLocationid) : Uuid {
			return ($this->likeLocationId);
		}

		/**
		 * mutator method for like location id
		 *
		 * @param string $newLikeLocationId new value of like id
		 * @throws \RangeException if $newLikeLocationId is not positive
		 * @throws \TypeError if $newLikeLocationId is not an integer
		 **/
		public function setLikeLocationId($newLikeLocationId) : void {
			try {
				$uuid = self::validateUuid($newLikeLocationId);
			} catch (\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
				$exceptionType = get_class($exception);
				throw (new $exceptionType($exception->getMessage(), 0, $exception));
			}
			// convert and store the like location id
			$this->likeLocationId = $uuid;
		}
		/**
		 * accessor method for like profile id
		 *
		 * @return Uuid value of like profile id
		 **/
		public function  getLikeProfileId($newLikeProfileId) : Uuid {
			return ($this->likeProfileIdId);
		}

		/**
		 * mutator method for like profile id
		 *
		 * @param string $newLikeProfileId new value of like id
		 * @throws \RangeException if $newLikeProfileId is not positive
		 * @throws \TypeError if $newLikeProfile Id is not an integer
		 **/
		public function setLikeProfileId($newLikeProfileId) : void {
			try {
				$uuid = self::validateUuid($newLikeProfileId);
			} catch (\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
				$exceptionType = get_class($exception);
				throw (new $exceptionType($exception->getMessage(), 0, $exception));
			}
			// convert and store the like location id
			$this->likeProfileId = $uuid;
		}

		/**
		 * inserts this Like into mySQL
		 *
		 * @param \PDO $pdo PDO connection object
		 * @throws \PDOException when mySQL related errors occur
		 **/
		public function insert (\PDO $pdo) : void {
			// create query template
			$query = "INSERT INTO `like`(likeLocationId, likeProfileId) VALUES(:likeLocationId, :likeProfileId)";
			$statement = $pdo->prepare($query);
		}

		/**
		 * deletes this Like from mySQL
		 *
		 * @param \PDO $pdo PDO connection object
		 * @throws \PDOException when mySQL related errors occur
		 **/
		public function delete (\PDO $pdo) : void {
			// create query template
			$query = "DELETE FROM `like` WHERE likeLocationId = :likeLocationId AND likeProfileId = :likeProfileId";
			$statement = $pdo->prepare($query);

			//bind the member variables to the placeholders in the template
			$parameters = ["likeLocationId"=>$this->likeLocationId->getBytes(), "likeProfileId"=>$this->likeProfileId->getBytes()];
			$statement->execute($parameters);
		}

		/**
		 * gets the Like by location id and profile id
		 *
		 * @param \PDO $pdo PDO connection object
		 * @param string $likeLocationId location id to search for
		 * @param string $likeProfileId profile id to search for
		 * @return Like|null Like found or null if not found
		 **/
		public static function getLikeByLikeLocationIdAndLikeProfileId(\PDO $pdo, string $likeLocationId, string $likeProfileId) : ?Like {
			try {
				$likeLocationId = self::validateUuid($likeLocationId);
			} catch (\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
				throw(new \PDOException($exception->getMessage(), 0, $exception));
			}

			//create queary template
			$query = "SELECT likeLocationId, likeProfileId FROM `like` WHERE likeLocationId = :likeLocationId AND likeProfileId = :likeProfileId";
			$statement = $pdo->prepare($query);

			// bind the location id and profile id to the place holder in the template
			$parameteres = ["likeLocationId"=> $likeLocationId->getbytes(), "likeProfileId" => $likeProfileId->getBytes()];
			$statement->execute($parameteres);

			//grab the like from mySQL
			try {
				$like = null;
				$statement->setFetchMode(\PDO::FETCH_ASSOC);
				$row = $statement->fetch();
				if(row !==false) {
					$like = new Like ($row["likeLocationId"], $row["likeProfileId"]);
				}
			} catch (\Exception $exception) {
				//if the row couldn't be converted, rethrow it
				throw (new \PDOException($exception->getMessage(),0, $exception));
			}
			return ($like);
		}

		/**
		 * gets the like by location id
		 *
		 * @param \PDO $pdo PDO connection object
		 * @param string $likeLocationId location id to search for
		 * @return \SplFixedArray SplFixedArray of Likes found or null if not found
		 * @throws \PDOException when mySQL related errors occur
		 **/
		public static function getLikebyLocationId(\PDO $pdo, string $likeLocationId) : \SplFixedArray {
			try {
				$likeLocationId = self::validateUuid($likeLocationId);
			} catch (\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
				throw (new \PDOException($exception->getMessage(), 0, $exception));
			}

			// create query template
			$query = "SELECT likeLocationId, likeProfileId FROM `like` WHERE likeLocationId = :likeLocationId";
			$statement = $pdo->prepare($query);

			//bind the member variables to the place holders in the template
			$parameters = ["likeLocationId"=> $likeLocationId->getBytes()];
			$statement->execute($parameters);

			//build an array of likes
			$likes = new \SplFixedArray($statement->rowCount());
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			while(($row = $statement->fetch()) !== false) {
				try {
					$like = new Like ($row["likeLocationId"], $row["likeProfileId"]);
					$likes[$likes->key()] = $like;
					$likes->next();
				} catch (\Exception $exception) {
					//if the row couldn't be converted, rethrow it
					throw(new \PDOException($exception->getMessage(), 0, $exception));
				}
			}
			return ($likes);
		}


		/**
		 * gets the like by profile id
		 *
		 * @param \PDO $pdo PDO connection object
		 * @param string $likeProfileId profile id to search for
		 * @return \SplFixedArray SplFixedArray of Likes found or null if not found
		 * @throws \PDOException when mySQL related errors occur
		 **/
		public static function getLikebyProfileId(\PDO $pdo, string $likeProfileId) : \SplFixedArray {
			try {
				$likeProfileId = self::validateUuid($likeProfileId);
			} catch (\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
				throw (new \PDOException($exception->getMessage(), 0, $exception));
			}

			// create query template
			$query = "SELECT likeLocationId, likeProfileId FROM `like` WHERE likeLocationId = :likeLocationId";
			$statement = $pdo->prepare($query);

			//bind the member variables to the place holders in the template
			$parameters = ["likeLocationId"=> $likeProfileId->getBytes()];
			$statement->execute($parameters);

			//build an array of likes
			$likes = new \SplFixedArray($statement->rowCount());
			$statement->setFetchMode(\PDO::FETCH_ASSOC);
			while(($row = $statement->fetch()) !== false) {
				try {
					$like = new Like ($row["likeLocationId"], $row["likeProfileId"]);
					$likes[$likes->key()] = $like;
					$likes->next();
				} catch (\Exception $exception) {
					//if the row couldn't be converted, rethrow it
					throw(new \PDOException($exception->getMessage(), 0, $exception));
				}
			}
			return ($likes);
		}

		/**
		 * formats the state variables for JSON serialization
		 *
		 * @return array resulting state variables to serialize
		 **/
		public function jsonSerialize() {
			$fields = get_object_vars($this);

			//format the date so that the front end can consume it
			$fields["likeLocationId"] = $this->likeLocationId;
			$fields["likeProfileId"] = $this->likeProfileId;

			return ($fields);
		}

	}