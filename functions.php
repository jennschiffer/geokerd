<?php 

/**********************************************************
* mysql-table-geocode functions file
* HERE IS WHERE THE PHP MAGIC HAPPENS. PAWS OFF, PALS.
**********************************************************/

// check ajax call for geocoded data
if ( isset($GLOBALS['HTTP_RAW_POST_DATA'] ))
{
	$result = saveLatLng($GLOBALS['HTTP_RAW_POST_DATA']);	
	print $result;
}

// get table of addresses
function connectToDatabase() {

	include 'config.php';
	$link = mysql_connect($host, $username, $password);
	if (!$link) {
		die('Could not connect to mysql: ' . mysql_error());
	}
	$locationDatabase = mysql_select_db($database, $link);
	if (!$locationDatabase) {
		die('Could not reach database: ' . mysql_error());
	}
	
}


// save latitude and longitude to the database
function saveLatLng($latLngArray) {

	$thisGeocoded = explode(",",$latLngArray);
	return "row: " . $thisGeocoded[0] . ", latitude: " . $thisGeocoded[1] . ", longitude: " . $thisGeocoded[2];
}

?>