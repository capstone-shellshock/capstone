ALTER DATABASE abqonthereel CHARACTER SET utf8 COLLATE utf8_unicode_ci;

DROP TABLE IF EXISTS `like`;
DROP TABLE IF EXISTS location;
DROP TABLE IF EXISTS `profile`;

CREATE TABLE `profile` (
	profileId BINARY(16) NOT NULL,
	profileActivationToken CHAR(32),
	profileEmail VARCHAR(128) NOT NULL,
	profileUsername VARCHAR(64) NOT NULL,
	profileHash CHAR(97) NOT NULL,

	UNIQUE(profileEmail),
	UNIQUE(profileUsername),

	PRIMARY KEY(profileId)

);

CREATE TABLE location (
	locationId BINARY(16) NOT NULL,
	locationProfileId BINARY(16) NOT NULL,
	locationAddress BLOB(256),
	locationDate DATETIME(6) NOT NULL,
	locationLatitude DECIMAL(10,8),
	locationLongitude DECIMAL(11,8),
	locationImageCloudinaryId VARCHAR(128),
	locationImageCloudinaryUrl VARCHAR(255),
	locationText VARCHAR(300) NOT NULL,
	locationTitle VARCHAR(128) NOT NULL,
	locationImdbUrl VARCHAR(255) NOT NULL,

	FOREIGN KEY(locationProfileId) REFERENCES profile(profileId),

	PRIMARY KEY(locationId)

);

CREATE TABLE `like` (

	likeLocationId BINARY(16) NOT NULL,
	likeProfileId BINARY(16) NOT NULL,

	INDEX(likeLocationId),
	INDEX(likeProfileId),

	FOREIGN KEY(likeLocationId) REFERENCES location(locationId),
	FOREIGN KEY(likeProfileId) REFERENCES  profile(profileId),

	PRIMARY KEY(likeLocationId, likeProfileId)
);