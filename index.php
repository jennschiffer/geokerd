<!doctype html>
<html>
<head>
	<title>getting them latitude and longitude values whaaaat</title>
	
<?php 
	if ( $_GET['source'] == 'db' ) {
		// you clicked the best button!
		geocodeDatabase();
		echo '<p>Congratulations, you have populated your table with awesome lat/lng data!</p>
				<p><a href="">Start all over again!</a></p>';
	}
	else { 
	
		$alert = ''; 
		
		// then we're going to show the main page...
?>
	
</head>

<body onload="initialize()">

<div id="alert"><?php echo $alert; ?></div>

<h1>LAT/LONG GENERATOR</h1>

<h2>You have a table of addresses, but no latitude and longitude data required to put into a Google Map? I had the same problem once; it was tragic for real. I fixed it by building this. Now you, too, can come correct.

<h3>DATABASE</h3>

<p>Your addresses are in a mysql database, yeah? Create two columns for latitude and longitude in the same table, and I'll fill them up. Update the <em>geocodeDatabase()</em> function on the bottom of this file with your info. Yeehaw.

<form id="db" method="get" action="">
	
	<input type="hidden" name="source" value="db" />
	<input type="submit" value="LET'S GET SOME GEOCODE HAPPENING UP IN HERE, YES" />

</form>

<input type="hidden" name="full-address" id="full-address" value="nope" />


<h3>.csv</h3>

<p>coming soon - import and export via csv file</p>

<h3>[made by <a href="http://jennschiffer.com">jenn</a>]</h3>

<script type="text/javascript"
      src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC12eZOT9Lb3Z2P3ZWIDZh9mhuT0qeMYNc&sensor=true">
</script>
<script type="text/javascript">
	var geocoder;
	function initialize() {
		geocoder = new google.maps.Geocoder();
	}
	function codeAddress() {
		var address = document.getElementById("full-address").value;
		geocoder.geocode( { 'address': address}, function(results, status) {
			if (status == google.maps.GeocoderStatus.OK) {
				var latitude = results[0].geometry.location.lat();
				var longitude = results[0].geometry.location.lng();
				alert(address);
			} else {
			alert("Geocoding failed: " + status);
			}
		});
	}
</script>
</body>
</html>

<?php } // show main page ?>

<?php 

/**
* Geocode Functions
* -- because why put them on a separate page?
* -- That would be, like, organized and stuff. 
*/

function geocodeDatabase() {
	
	/**********************************************************
	* YOU SHOULDN'T EDIT ANYTHING HERE.
	* BUT YOU *SHOULD* EDIT THE CONFIG.PHP FILE VARIABLES
	* AND THAT'S IT.
	* PLZ.
	**********************************************************/
	
	include "config.php";

	// connect to the database
	$link = mysql_connect($host, $username, $password);
	if (!$link) {
		die('Could not connect to mysql: ' . mysql_error());
	}
	$locationDatabase = mysql_select_db($database, $link);
	if (!$locationDatabase) {
		die('Could not reach database: ' . mysql_error());
	}
	
	// gonna get all the address records in $table
	$addresses = mysql_query("SELECT * FROM $table");
	$count = mysql_num_rows($addresses);
	if ($count > 0) { 
		while ( $row = mysql_fetch_array($addresses)) {
			
			if ( $fullAddress ) { 
				// if you uncommented $fullAddress, use that as the whole address
				$address = $row[$fullAddress];
			}
			else {
				// we need to generate a $fullAddress with your $address, $city, $state, and $zip fields
				$address = $row[$street] . " " . $row[$city] . ", " . $row[$state] . " " . $row[$zip];
			}
			
			/* set #fullAddress value to be $address, 
			   then call codeAddress() geocode function */
			echo '<script>
					document.getElementById("full-address").value = "' . $address . '";
					codeAddress();
				  </script>';

		}

	}
	else echo "There are no records in this table, what gives?";

}

?>