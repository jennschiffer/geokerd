<?php 

/**
* HERE IS WHERE SOME OF THE PHP MAGIC HAPPENS. PAWS OFF, PALS.
**/

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
		die('Looks like you didn\'t enter your database table credentials in /config.php properly!<p><em>' . mysql_error() . '</em></p>');
	}
	$locationDatabase = mysql_select_db($database, $link);
	if (!$locationDatabase) {
		die('Looks like you didn\'t enter your database table credentials in /config.php properly!<p><em>' . mysql_error() . '</em></p>');
	}
}


// save latitude and longitude to the database
function saveLatLng($latLngArray) {
	
	include 'config.php';
	connectToDatabase();

	$geocodedToSave = explode(",",$latLngArray);
	
	$result = mysql_query('UPDATE ' . $table . 
						 ' SET `' . $latitude . '`=' . $geocodedToSave[2] . ', `' . $longitude . '`=' . $geocodedToSave[3] . 
						 ' WHERE `' . $index . '`=' . $geocodedToSave[0] . ';');
						 
	return 'ROWID: ' . $geocodedToSave[0] . 
			', ADDRESS: ' . $geocodedToSave[1] . 
			', LATITUDE: ' . $geocodedToSave[2] . 
			', LONGITUDE: ' . $geocodedToSave[3];
			
}

?>