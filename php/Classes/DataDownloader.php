<?php

namespace ShellShock\Capstone;

require_once("autoload.php");
require_once(dirname(__DIR__) . "/vendor/autoload.php");
require_once("/etc/apache2/capstone-mysql/Secrets.php");
require_once(dirname(__DIR__, 1) . "/lib/uuid.php");

//use \Ds\Vector;

class DataDownloader {

	public static function pullLocations() {

		$newLocations = null;
		$urlBase = "http://coagisweb.cabq.gov/arcgis/rest/services/public/FilmLocations/MapServer/0/query?where=1%3D1&text=&objectIds=&time=&geometry=&geometryType=esriGeometryEnvelope&inSR=&spatialRel=esriSpatialRelIntersects&relationParam=&outFields=*&returnGeometry=true&maxAllowableOffset=&geometryPrecision=&outSR=4326&returnIdsOnly=false&returnCountOnly=false&orderByFields=&groupByFieldsForStatistics=&outStatistics=&returnZ=false&returnM=false&gdbVersion=&returnDistinctValues=false&f=pjson";
		$secrets = new \Secrets("/etc/apache2/capstone-mysql/abqonthereel.ini");
		$pdo = $secrets->getPdoObject();

		$locations = self::readDataJson($urlBase);
		$filteredResults = removeUselessEntries($locations);
		foreach($filteredResults as $location) {
			var_dump($location);
			$location = new Location(generateUuidV4(), "58aa0068-b4a9-4cfd-9dea-777e7a3fb85d", $location["locationAddress"], null, $location["locationY"], $location["locationX"], null, null, null, $location["locationTitle"] , $location["locationImdbUrl"]);

				$location->insert($pdo);
		}
//		var_dump($filteredResults);
	}

	public static function readDataJson($url) {

		$context = stream_context_create(["http" => ["ignore_errors" => true, "method" => "GET"]]);

		try {

			//file-get-contents returns file in string context
			if(($jsonData = file_get_contents($url, null, $context)) === false) {
				throw(new \RuntimeException("URL does not produce results"));
			}

			//decode the json file
			$jsonConverted = json_decode($jsonData);

			//format
			if(empty($jsonConverted->features) === false) {
				$jsonFeatures = $jsonConverted->features;
			}
			$newLocations = \SplFixedArray::fromArray($jsonFeatures);
		} catch(\Exception $exception) {
			throw(new \PDOException($exception->getMessage(), 0, $exception));
		}
		return ($newLocations);
	}
}

function removeUselessEntries($locations) {

$newLocations = [];
	foreach($locations as $location) {
		if($location -> attributes -> Title !== "0000" && $location -> attributes -> IMDbLink !== "na" && $location -> geometry -> x !== "NaN" && $location -> geometry -> y !== "NaN") {

		$locationTitle = trim($location -> attributes -> Title) . " at " . trim($location -> attributes -> Site);
		$locationImdbUrl = trim($location -> attributes -> IMDbLink);
		$locationAddress = trim($location -> attributes -> Address);
		$locationDate = trim($location -> attributes -> ShootDate);
		$locationX = $location -> geometry -> x;
		$locationY = $location -> geometry -> y;

		$newLocations = $newLocations + [$locationTitle => ["locationTitle" => trim($location -> attributes -> Title), "locationImdbUrl" => $locationImdbUrl, "locationDate" => $locationDate, "locationAddress" => $locationAddress, "locationX" => $locationX, "locationY" => $locationY]];
		}
	}

	return($newLocations);
}



echo DataDownloader::pullLocations() . PHP_EOL;