<?php 
/*****************************************************************
* GEOKERD, ERMAGHERD
* takes a mysql table of addresses and generates latitude
* and longitude values for each using Google Maps API.
* I made this so I can geocode existing addresses to put in a map.
******************************************************************/
?>

<!doctype html>
<html>
<head>
	<title>GEOKERD // getting them latitude and longitude values whatwhaaaat</title>
</head>

<body>

<?php
	if ( $_GET['source'] == 'db' ) {
			$arrayOfAddresses = geocodeDatabase(); 
		
		//HERE IS WHERE THE JAVASCRIPT FUN STUFF BEGINS. NO TOUCHY, TOUCHY!
?>
		<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo $googleMapsAPI; ?>&sensor=true"></script>
		<script type="text/javascript">
			
			var addressesArray = <?php echo json_encode($arrayOfAddresses); ?>;
			var geocoder;
			
			codeAddress(addressesArray);
			
			function initialize() {
				geocoder = new google.maps.Geocoder();
			}
			
			function codeAddress(passedArray) {
				
				initialize();
				var addresses = passedArray;
				var geocodedArray = new Array();
				
				for ( var i = 0; i <= 2; i++ ) {
					
					var address = addresses[i];
				
					geocoder.geocode( { 'address': address}, function(results, status) {
						if (status == google.maps.GeocoderStatus.OK) {
							var latitude = results[0].geometry.location.lat();
							var longitude = results[0].geometry.location.lng();
							geocodedArray[i] = [latitude, longitude];
							$.ajax({ data : { geocodedAddresses : geocodedArray } });
							
							var xmlhttp;
							if (window.XMLHttpRequest) {
								xmlhttp=new XMLHttpRequest();
							}
							else {
								xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
							}
							xmlhttp.onreadystatechange = function(){
								if ( xmlhttp.readyState == 4 && xmlhttp.status == 200) {
									document.getElementById("myDiv").innerHTML=xmlhttp.responseText;
								}
							}
							xmlhttp.open("GET","ajax_info.txt",true);
							xmlhttp.send();
						} 
						else {
							alert("Geocoding failed: " + status);
						}
					});					
				}
			}
		</script>

<?php
	} else { 
	
?>

<div id="alert"><?php echo $alert; ?></div>

<h1>GEOKERD</h1>


<form id="db" method="get" action="">
	
	<input type="hidden" name="source" value="db" />
	<input type="submit" value="LET'S GET SOME GEOCODE HAPPENING UP IN HERE, YES" />

</form>

<?php } // show main form page ?>


<h3>[made by <a href="http://jennschiffer.com">jenn</a>]</h3>

</body>
</html>


<?php 

// HERE IS WHERE THE PHP MAGIC HAPPENS. KEEP YOUR PAWS OFF, PALS.

function geocodeDatabase() {
	
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
	
		$addressArray = array();
		while ( $row = mysql_fetch_array($addresses)) {
			
			if ( $fullAddress ) { 
				// if you uncommented $fullAddress, use that as the whole address
				$address = $row[$fullAddress];
			}
			else {
				// we need to generate a $fullAddress with your $address, $city, $state, and $zip fields
				$address = $row[$street] . " " . $row[$city] . ", " . $row[$state] . " " . $row[$zip];
			}

			// set #fullAddress value to be $address, then call codeAddress() geocode function
			$addressArray[] = $address;
		}
	}
	else echo "There are no records in this table, what gives?";
	
	return $addressArray;
}

?>