<?php 
	/*
	* GEOKERD, ERMAGHERD
	* takes a mysql table of addresses and generates latitude
	* and longitude values for each using Google Maps API.
	* I made this so I can geocode existing addresses to put in a map.
	*/

if ( $_POST['source'] == 'submit-table' ) {
	include 'config.php';
	include 'functions.php';
	$arrayOfAddresses = geocodeDatabase(); 
?>
	
<script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=<?php echo $googleMapsAPI; ?>&sensor=true"></script>
<script type="text/javascript">

/**

initialize

-- on submit --
foreach record in table
	
	codeAddress(that record)
	
/foreach

*/
	
	var geocoder;
	var geocodedArray = new Array();
	var addressesArray = <?php echo json_encode($arrayOfAddresses); ?>;
	var geocodedAddressData = JSON.stringify( codeAddress(addressesArray) );

	function initialize() {
		geocoder = new google.maps.Geocoder();
	}
	
	function codeAddress(passedArray) {
		
		initialize();
		
		var addresses = passedArray;
		var geocodedArray = new Array();
		var address, latitude, longitude;
		
		for ( var i = 0; i <= 3; i++ ) {
			
			address = addresses[i];
			
			geocoder.geocode( { 'address': address }, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					latitude = results[0].geometry.location.lat();
					longitude = results[0].geometry.location.lng();
					geocodedArray[i] = [i, latitude, longitude];
					
					var ajax = new XMLHttpRequest();
					ajax.open('POST','functions.php',true);
					ajax.setRequestHeader('Content-Type','application/json');
					ajax.onreadystatechange = function() {
						if ( ajax.readyState == 4 && ajax.status == 200 ) { 
							var response = ajax.responseText;
							console.log(response);
						}
					}  	
					ajax.send(geocodedArray[i]);
					ajax.close;
				} 
				else {
					alert('Geocoding failed: ' + status);
				}
			});	
		}
	}
	
</script>
		
<?php 
	$alert = "Congrats! Your lat/long table fields should now be populated!";
	} // end if-db-submitted 
?>

<!doctype html>
<html>
<head>
	<title>GEOKERD // generating lat/long values for your database of addresses</title>
	<style type="text/css">
		html { background: #9cc; }
		body { background: #fff; padding: 20px; font-family: Georgia, serif; color: #333; width: 600px; margin: 20px auto; }
		h1 { margin: 0; }
		#alert { background: #cc9; padding: 10px; margin-bottom: 20px; font-weight: bold; font-style: italic; text-align: center; }	
		ol li { font-weight: bold; margin-top: 30px; }
		form { text-align: center; }
		#submit { background: #ccc; padding: 10px; margin: 30px; color: #333; font-size: 2em; }
		#submit:hover { -webkit-box-shadow: 2px 2px 5px #666; -webkit-transition: all ease-out 200ms; }
	</style>
</head>

<body>

<?php if ( $alert ) { ?>
	<div id="alert">
		<?php echo $alert; ?>
	</div>
<?php } ?>

<h1>GEOKERD</h1>

<ol id="faq">
	<li>What is this?</li>
	<p>This is a script you can use to generate latitude and longitude values for addresses you already have in a database, using the Google Maps API.
	</p>
	
	<li>What do I need?</li>
	<p>You need a mysql database table of addresses, which has a field for latitude and a field for longitude. You need the credentials to modify this database table, and you also need a Google Maps API key.</p>
	
	<li>How do I make it work?</li>
	<p>Get the app from Github and add the directory to a local or remote server. Update the config.php document with all the necessary info (database creds, field names, Google Maps API key). Then go to the app in your browser and click the shiny button!</p>
	
	<li>Ok then what?</li>
	<p>Then you'll have a table of addresses *with* their latitudes and longitudes, you can use this table to do fun stuff with Google Maps or any other APIs that require geocoded data to work. Get going, press the button!</p>
</ol>

<form id="submit-table" method="post" action="">
	<input type="hidden" name="source" value="submit-table" />
	<input type="submit" id="submit" value="CLICK THIS TO GEOCODE" />
</form>

<p>[made by <a href="http://jennschiffer.com">jenn</a>]</p>

</body>
</html>