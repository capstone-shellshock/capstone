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
	locationLatitude DECIMAL(10,8) NULL,
	locationLongitude DECIMAL(11,8) NULL,
	locationImageCloudinaryId VARCHAR(128) NULL,
	locationImageCloudinaryUrl VARCHAR(128) NULL,
	locationText VARCHAR(300) NOT NULL,
	locationTitle VARCHAR(64) NOT NULL,
	locationImdbUrl VARCHAR(255) NOT NULL,

	FOREIGN KEY(locationProfileId) REFERENCES profile(profileId),

	PRIMARY KEY(locationId)

);

CREATE TABLE `Like` (
   likeId BINARY(16) NOT NULL,
	likeLocationId BINARY(16) NOT NULL,
	likeProfileId BINARY(16) NOT NULL,

	INDEX(likeLocationId),
	INDEX(likeProfileId),

	FOREIGN KEY(likeLocationId) REFERENCES location(locationId),
	FOREIGN KEY(likeProfileId) REFERENCES  profile(profileId),

	PRIMARY KEY(likeId)
);
