<?php

public function getLikeId() : Uuid {
	return($this->likeId);
}

/**
 * mutator method for like id
 *
 * @param Uuid|string $new LikeId new value of like id
 * @throws \RangeException if $newLikeId is not positive
 * @throws \TypeError if $newLikeId is not a uuid or string
 **/

public function setLikeID ($newLikeId) : void {
	try {
		$uuid = self::validateUuid($newLikeId);
	} catch (\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
		$exceptionType = get_class($exception);
		throw(new $exception($exception->getMessage(), 0, $exception));
	}

	//convert and store the like id
	$this->likeId = $uuid;
}

/**
 * accessor mehtod for like profile id
 *
 * @return Uuid value of like profile id
 **/

public function getLikeProfileId() : Uuid {
	return ($this->likeProfileId);
}

/**
 * mutator method for like profile id
 *
 * @param string | Uuid $newLikeProfileId new value of like profile id
 * @throws \RangeException if $newLikeId is not positive
 * @throws \TypeError if $newLikeProfileId is not an integer
 **/

public function setLikeProfileId( $newLikeProfileId) : void {

}