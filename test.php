
<?php
// Create a DOM object from a URL
$html = file_get_html('https://www.cricbuzz.com/');
print_r($html);

// function curl_get($url,  array $options = array())
			// {
				// $defaults = array(
					// CURLOPT_URL => $url,
					// CURLOPT_HEADER => 0,
					// CURLOPT_RETURNTRANSFER => TRUE,
					// CURLOPT_TIMEOUT => 4
				// );

				// $ch = curl_init();
				// curl_setopt_array($ch, ($options + $defaults));
				// if( ! $result = curl_exec($ch))
				// {
					// trigger_error(curl_error($ch));
				// }
				// curl_close($ch);
				// return $result;
			// }
			
// if(!empty($_POST['latitude']) && !empty($_POST['longitude'])){
	
	// $lat= $_POST['latitude'];
	// $long= $_POST['longitude'];
	
	// $url="https://maps.google.com/maps/api/geocode/json?latlng=$lat,$long&key=AIzaSyBXeEpNyvOxirxB38hoys2_U7lTvQllS9g";
	
// $curl_return=curl_get($url);
// $obj=json_decode($curl_return);
// echo $obj->results[0]->formatted_address;
// }
			
?>
<!--<!DOCTYPE html>
    <html>
      <head>
        <link type="text/css" rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500">
        <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places&key=AIzaSyBXeEpNyvOxirxB38hoys2_U7lTvQllS9g"></script>
        <script>
            var autocomplete;
            function initialize() {
              autocomplete = new google.maps.places.Autocomplete(
                  /** @type {HTMLInputElement} */(document.getElementById('autocomplete')),
                  { types: ['geocode'] });
              google.maps.event.addListener(autocomplete, 'place_changed', function() {
              });
            }
        </script>
      </head>
      <body onload="initialize()">
        <div id="locationField">
          <input id="autocomplete" placeholder="Enter your address" onFocus="geolocate()" type="text"></input>
        </div>
      </body>
    </html>-->