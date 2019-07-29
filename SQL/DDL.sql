ALTER DATABASE [place-holder] CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS userLike;
DROP TABLE IF EXISTS location;
DROP TABLE IF EXISTS `profile`;

CREATE TABLE `profile` (
   profileId BINARY(16) NOT NULL,
   profileActivationToken CHAR(32),
   profileEmail VARCHAR(128) NOT NULL,
   profileUsername VARCHAR(32) NOT NULL,
   profileHash CHAR(97) NOT NULL,

   UNIQUE(profileEmail),
   UNIQUE(profileUsername),

   PRIMARY KEY(profileId)

);

CREATE TABLE location (
	locationId BINARY(16) NOT NULL,
	locationProfileId BINARY(16) NOT NULL,
	locationAddress VARCHAR(64) NULL,
	locationDate DATETIME(6) NOT NULL,
	locationLatitude VARCHAR(12) NULL,
	locationLongitude VARCHAR(12) NULL,
	locationText VARCHAR(300) NOT NULL,
	locationTitle VARCHAR(64) NOT NULL,
	locationImdbUrl VARCHAR(255) NOT NULL,

	FOREIGN KEY(locationProfileId) REFERENCES profile(profileId),

	PRIMARY KEY(locationId)

);

CREATE TABLE userLike (
	userLikeLocationId BINARY(16) NOT NULL,
	userLikeProfileId BINARY(16) NOT NULL,

	INDEX(userLikeLocationId),
	INDEX(userLikeProfileId),

	FOREIGN KEY(userLikeLocationId) REFERENCES location(locationId),
	FOREIGN KEY(userLikeProfileId) REFERENCES  profile(profileId)
);
