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

// get data from the database we want to geocode
function geocodeDatabase() {
	
	include 'config.php';
	$link = mysql_connect($host, $username, $password);
	if (!$link) {
		die('Could not connect to mysql: ' . mysql_error());
	}
	$locationDatabase = mysql_select_db($database, $link);
	if (!$locationDatabase) {
		die('Could not reach database: ' . mysql_error());
	}
	
	$addresses = mysql_query("SELECT * FROM $table");
	$count = mysql_num_rows($addresses);
	if ($count > 0) { 
		$addressArray = array();
		while ( $row = mysql_fetch_array($addresses)) {
			if ( $fullAddress ) { 
				$address = $row[$fullAddress];
			}
			else {
				$address = $row[$street] . ' ' . $row[$city] . ', ' . $row[$state] . ' ' . $row[$zip];
			}
			$addressArray[] = $address;
		}
	}
	else {
		echo 'There are no records in this table, what gives?';
	}
	return $addressArray;
}

// save latitude and longitude to the database
function saveLatLng($latLngArray) {

	$thisGeocoded = explode(",",$latLngArray);
	return "row: " . $thisGeocode[0] . ", latitude: " . $thisGeocoded[1] . ", longitude: " . $thisGeocoded[2];
	
}

?>