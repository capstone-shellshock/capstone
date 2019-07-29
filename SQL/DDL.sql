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
