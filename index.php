<!doctype html>
<html>
<head>
	<title>GEOKERD! geocoding mysql tables of addresses with ease</title>
	<style type="text/css">
		html { background: #9cc; }
		body { background: #fff; padding: 20px; font-family: Georgia, serif; color: #333; width: 600px; margin: 20px auto; box-shadow: 0 0 5px #000; }
		h1, h2 { margin: 0; }
		h2 { font-size: 1.2em; font-style: italic; letter-spacing: .1em; }
		#alert { background: #cc9; padding: 10px; margin: 20px 0; font-weight: bold; font-style: italic; text-align: center; }	
		#alert ol { background: #eed;  text-align: left; padding: 10px; }
		#alert ol li { font-weight: normal; font-style: normal; font-size: .8em; list-style-position: inside; margin: 20px 0 0; }
		#alert ol li:first-child { margin: 0;}
		ol li { font-weight: bold; margin-top: 30px; }
		form { text-align: center; }
		#submit { background: #ccc; padding: 10px; margin: 30px; color: #333; font-size: 2em; }
		#submit:hover { -webkit-box-shadow: 2px 2px 5px #666; -webkit-transition: all ease-out 200ms; }
		#credit { font-style: italic; letter-spacing: .1em; text-align: center; }
		a { color: #c66; text-decoration: none; }
	</style>

<?php 
	// START ON-SUBMIT
	if ( isset($_POST['source']) && $_POST['source'] == 'submit-table' ) {
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
				
			geocoder.geocode( { 'address': address }, function(results, status) {
				
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
					success = true;
				} 
				else {
					console.log(status);
					window.setTimeout(function(){ codeAddress(addressArray); }, 2000);
				}	
			});	
		}
		
	<?php
		$addresses = mysql_query("SELECT * FROM $table");
		$count = mysql_num_rows($addresses);
		$alert = "Something didn't work.";
		
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
			$alert = "The latitude and longitude values of the following addresses have been updated in your table.";
		}
		else {
			$alert = 'There are no records in this table. Do you even blend, brah?';
		} 
		
		
	} // END ON-SUBMIT
?>
	</script>
</head>

<body>

	<h1>GEOKERD!</h1> 
	<h2>geocoding mysql tables of addresses with ease</h2>	
	
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
		<p>You need a mysql database table of addresses, which has a field for latitude and a field for longitude. You need the credentials to modify this database table, and you also need a Google Maps API key. <em>Note: As this is powered by Google's API, you are limited to 2500 requests per day. <a href="https://developers.google.com/maps/documentation/geocoding/" target="_blank">See the Geocoder API docs here.</a></p>
		
		<li>How do I make it work?</li>
		<p><a href="https://github.com/jennschiffer/geokerd">Get the app from Github</a> and add the directory to a local or remote server. Update the config.php document with all the necessary info (database creds, field names, Google Maps API key). Then go to the app in your browser and click the button!</p>
		
		<li>Ok then what?</li>
		<p>Then you'll have a table of addresses *with* their latitudes and longitudes. You can use this table to do fun stuff with Google Maps or any other APIs that require geocoded data to work. Get going, press the button!</p>
	</ol>
	
	<form id="submit-table" method="post" action="">
		<input type="hidden" name="source" value="submit-table" />
		<input type="hidden" name="rowID" value="" />
		<input type="submit" id="submit" value="CLICK THIS TO GEOCODE" />
	</form>
	
	<p id="credit">made with &hearts; by <a href="http://jennschiffer.com">jenn schiffer</a></p>

	<a href="https://github.com/jennschiffer/geokerd"><img style="position: absolute; top: 0; left: 0; border: 0;" src="https://s3.amazonaws.com/github/ribbons/forkme_left_gray_6d6d6d.png" alt="Fork me on GitHub"></a>
</body>
</html>
