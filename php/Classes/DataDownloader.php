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

			$context = stream_context_create(["http"] => ["ignore_errors" => true, "method" => "GET", "header" => "Authorization: Bearer $secret -> apiKey"])
		}


	}
}