<?php

/**********************************************************
* mysql-table-geocode config file
* THIS IS THE ONLY FILE YOU SHOULD EDIT
* EDITING OTHER FILES MAY LEAD TO DANGERRRRR
**********************************************************/

/* update info to connect to database */
$host = "localhost";
$username = "root";
$password = "root";
$database = "hackjersey";
$table = "facilities";

/* enter the field names from $table 
	which correspond to the address info */
$street = "address";
$city = "city";
$state = "state";
$zip = "zip";

/* if you have one field for the full address, then 
	uncomment the following line and replace the address
	with the name of that column in your table */
// $fullAddress = "123 Mockingbird Lane New York, NY 90210";

/* if you do not have latitude or longitude 
	columns in your table, create them duh */
$latitude = "lat";
$longitude = "lng";

/* Enter your own Google Maps API here
	for more info, go here:  */
$googleMapsAPI = "AIzaSyC12eZOT9Lb3Z2P3ZWIDZh9mhuT0qeMYNc";

?>