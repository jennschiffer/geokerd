<!doctype html>
<html>
<head>
	<title>GEOKERD // generating lat/long values for your database of addresses</title>
	<style type="text/css">
		html { background: #9cc; }
		body { background: #fff; padding: 20px; font-family: Georgia, serif; color: #333; width: 600px; margin: 20px auto; }
		h1 { margin: 0; }
		#alert { background: #cc9; padding: 10px; margin-bottom: 20px; font-weight: bold; font-style: italic; text-align: center; }	
		#alert ol { background: white;  text-align: left; padding: 10px; }
		#alert ol li { font-weight: normal; font-style: normal; font-size: .8em; list-style-position: inside; margin: 20px 0 0; }
		#alert ol li:first-child { margin: 0;}
		ol li { font-weight: bold; margin-top: 30px; }
		form { text-align: center; }
		#submit { background: #ccc; padding: 10px; margin: 30px; color: #333; font-size: 2em; }
		#submit:hover { -webkit-box-shadow: 2px 2px 5px #666; -webkit-transition: all ease-out 200ms; }
	</style>

<?php 
	// START ON-SUBMIT
	if ( $_POST['source'] == 'submit-table' ) {
		include 'config.php';
		include 'functions.php';
		connectToDatabase();
?>

	<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo $googleMapsAPI; ?>&sensor=true"></script>
	<script type="text/javascript">
		
		function codeAddress(addressArray) {
			var geocoder = new google.maps.Geocoder();
			var geocodedAddress = new Array();
			var addressID = addressArray['id'];
			var address = addressArray['address'];			
						
			geocoder.geocode( { 'address': address }, test_geocode = function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					latitude = results[0].geometry.location.lat();
					longitude = results[0].geometry.location.lng();
					geocodedAddress = [addressID, address.replace(/,/g,''), latitude, longitude];
					
					var ajax = new XMLHttpRequest();
					ajax.open('POST','functions.php',true);
					ajax.setRequestHeader('Content-Type','application/json');
					ajax.onreadystatechange = function() {
						if ( ajax.readyState == 4 && ajax.status == 200 ) { 
							var response = ajax.responseText;							
							var responseItem = document.createElement("li");
							var responseText = document.createTextNode(response);
							responseItem.appendChild(responseText);
     						document.getElementById('address-success').appendChild(responseItem); 
						}
					}  	
					ajax.send(geocodedAddress);
					ajax.close;
				} 
				else {
					console.log('Geocoding failed: ' + status);
				}	

			});	
		}
		
	<?php
		$addresses = mysql_query("SELECT * FROM $table");
		$count = mysql_num_rows($addresses);
		
		if ( $count > 0 ) { 
			while ( $row = mysql_fetch_array($addresses) ) {
				$addressID = $row[$index];
						
				if ( $fullAddress ) { 
					$address = $row[$fullAddress];
				}
				else {
					$address = $row[$street] . ' ' . $row[$city] . ', ' . $row[$state] . ' ' . $row[$zip];
				}
				
				$addressArray = Array( 'id' => $addressID, 'address' => $address );
				
				echo 'var addressToGeocode = ' . json_encode($addressArray) . ';' . 
					 'var ajaxAddress = codeAddress( addressToGeocode );';	 
			}
		}
		else {
			$alert = 'There are no records in this table. Do you even blend, brah?';
		}
		
		$alert = "Success! The latitude and longitude values of the following addresses have been updated in your table.";
		
	} // END ON-SUBMIT
?>
	</script>
	
</head>

<body>


	<h1>GEOKERD</h1>
	
	<?php 
		if ( $alert ) { 
			echo '<div id="alert">' . $alert . '<ol id="address-success"></ol></div>'; 
		} 
	?>
	
	<ol id="faq">
		<li>What is this?</li>
		<p>This is a script you can use to generate latitude and longitude values for addresses you already have in a database, using the Google Maps API.
		</p>
		
		<li>What do I need?</li>
		<p>You need a mysql database table of addresses, which has a field for latitude and a field for longitude. You need the credentials to modify this database table, and you also need a Google Maps API key. <em>Note: As this is powered by Google's API, you are limited to 2500 requests/address lookups per day. <a href="https://developers.google.com/maps/documentation/geocoding/" target="_blank">See the Geocoder API docs here.</a></p>
		
		<li>How do I make it work?</li>
		<p>Get the app from Github and add the directory to a local or remote server. Update the config.php document with all the necessary info (database creds, field names, Google Maps API key). Then go to the app in your browser and click the shiny button!</p>
		
		<li>Ok then what?</li>
		<p>Then you'll have a table of addresses *with* their latitudes and longitudes, you can use this table to do fun stuff with Google Maps or any other APIs that require geocoded data to work. Get going, press the button!</p>
	</ol>
	
	<form id="submit-table" method="post" action="">
		<input type="hidden" name="source" value="submit-table" />
		<input type="hidden" name="rowID" value="" />
		<input type="submit" id="submit" value="CLICK THIS TO GEOCODE" />
	</form>
	
	<p>[<a href="http://jennschiffer.com">made by jenn schiffer, with &hearts;</a>]</p>

</body>
</html>