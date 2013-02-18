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
					geocodedArray[i] = [latitude, longitude];
					
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
	$alert = "Ch-ch-check out your table, now. It should be filled with delicious latitudes and logitudes!";
	} // end if-db-submitted 
?>

<!doctype html>
<html>
<head>
	<title>GEOKERD // getting them latitude and longitude values whatwhaaaat</title>
</head>

<body>

<div id="alert"><?php echo $alert; ?></div>

<h1>GEOKERD</h1>

<p>some instructions go here, I guess ughhhhh</p>

<form id="submit-table" method="post" action="">
	<input type="hidden" name="source" value="submit-table" />
	<input type="submit" value="TIME TO GEOCODE." />
</form>

<p>[made by <a href="http://jennschiffer.com">jenn</a>]</p>

</body>
</html>