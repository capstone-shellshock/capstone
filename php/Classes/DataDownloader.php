<?php

namespace ShellShock\Capstone;

require_once ("autoload.php");
require_once (dirname(__DIR__) . "/vendor/autoload.php");
require_once ("/etc/apache2/capstone-mysql/Secrets.php");
require_once (dirname(__DIR__,1) . "/lib/uuid.php");

class DataDownloader {

	public static function pullLocations() {

		$newLocations = null;
		$urlBase = "http://coagisweb.cabq.gov/arcgis/rest/services/public/FilmLocations/MapServer/0/query?where=1%3D1&text=&objectIds=&time=&geometry=&geometryType=esriGeometryEnvelope&inSR=&spatialRel=esriSpatialRelIntersects&relationParam=&outFields=*&returnGeometry=true&maxAllowableOffset=&geometryPrecision=&outSR=&returnIdsOnly=false&returnCountOnly=false&orderByFields=&groupByFieldsForStatistics=&outStatistics=&returnZ=false&returnM=false&gdbVersion=&f=pjson";
		$secrets = new \Secrets("/etc/apache2/capstone-mysql/abqonthereel.ini");
		$pdo = $secrets -> getPdoObject();

		public static function readDataJson($url, $secret) {

			$context = stream_context_create(["http" => ["ignore_errors" => true, "method" => "GET", "header" => "Authorization: Bearer $secret -> apiKey"]]);

			try {

				//file-get-contents returns file in string context
				if(($jsonData = file_get_contents($url, null, $context)) === false) {
					throw(new \RuntimeException("URL does not produce results"));
				}

				//decode the json file
				$jsonConverted = json_decode($jsonData);

				//format
				if(empty($jsonConverted -> locations) === false) {
					$jsonFeatures = $jsonConverted -> locations;
				}
				var_dump($jsonFeatures);
				$newLocations = \SplFixedArray::fromArray($jsonFeatures);
			} catch(\Exception $exception) {
				throw(new \PDOException($exception -> getMessage(), 0, $exception));
			}
			return ($newLocations);
		}
	}
}

echo DataDownloader::pullLocations() . PHP_EOL;