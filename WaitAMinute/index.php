<?php
session_start();

//$name = "";
//if ($_SERVER["REQUEST_METHOD"] == "POST") {
//    $name = $_POST["test"];
//}
// set location
//$address = $_POST["test"];

//set map api url

$url = "https://maps.googleapis.com/maps/api/geocode/json?address=7315+199+Street++Langley+British+Columbia+V2Y+1R9%0D%0A&key=AIzaSyAiFt7-IlOjTC98doxhR-F1uf_QWnmWtoM";
//$url = "https://maps.googleapis.com/maps/api/geocode/json?address=1600+Amphitheatre+Parkway,+Mountain+View,+CA&key=AIzaSyAiFt7-IlOjTC98doxhR-F1uf_QWnmWtoM";

//call api

?>
<!DOCTYPE html>
<html>
<head>
    <title>Dynamic Form</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
        #map {
            height: 400px;
            width: 100%;
        }
    </style>
    <!-- jquery -->
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
            integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
            crossorigin="anonymous"></script>

    <!-- bootstrap -->
    <script type="text/javascript" src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/js/bootstrap.min.js"></script>

    <!-- handlebars -->
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/handlebars.js/3.0.3/handlebars.js"></script>

    <!-- survey js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
</head>
<body>

<form role="form" method="post" action="process.php">
    <div class="form-row">
        <div class="form-group col-md-6">

            <label for="inputName">Name</label>
            <input name="name" type="text" class="form-control" id="inputName" placeholder="Name">
        </div>
    </div>
    <div class="form-group">
        <label for="inputAddress">Address</label>
        <input name="address" type="text" class="form-control" id="inputAddress" placeholder="1234 Main St">
    </div>
    <div class="form-group">
        <label for="inputAddress2">Address 2</label>
        <input name="address2" type="text" class="form-control" id="inputAddress2"
               placeholder="Apartment, studio, or floor">
    </div>
    <div class="form-row">
        <div class="form-group col-md-6">
            <label for="inputCity">City</label>
            <input name="city" type="text" class="form-control" id="inputCity">
        </div>
        <div class="form-group col-md-4">
            <label for="inputProvince">Province</label>
            <select name="province" id="inputProvince" class="form-control">
                <option selected>Choose...</option>
                <option>Alberta</option>
                <option>British Columbia</option>
                <option>Manitoba</option>
            </select>
        </div>
        <div class="form-group col-md-2">
            <label for="inputZip">Zip</label>
            <input name="zip" type="text" class="form-control" id="inputZip">
        </div>
    </div>
    <button type="submit" class="btn btn-success">Submit</button>
</form>
<button type="submit" id="update" class="btn btn-primary">Update</button>
<div class="list-group">
    <?php
    $lineItems = array();
    $addressID = 0;
    $handle = fopen("testfile.txt", 'r');
    if ($handle) {
        while (($line = fgets($handle)) !== false) {
            $entry = '<button class="list-group-item list-group-item-action flex-column align-items-start">
          						<div id="tst' . $addressID++ . '"class="d-flex w-100 justify-content-between">
            					<h5 class="mb-1">';
            $lineItems = explode(" break ", $line);
            $entry .= $lineItems[0] . '</h5>';
            $entry .= '</div><p class="mb-1">' . $lineItems[1];
            $entry .= '</p>';
            $entry .= '</button>';

            echo $entry;
        }
        fclose($handle);
    } else {
        echo "<p>Error opening file.</p>";
    }
    session_destroy();
    ?>
</div>
<div id="right-panel">
    <div>
        <strong>Results</strong>
    </div>
    <div id="output"></div>
</div>
<div id="map"></div>

<script>
    var link;
    $("#tst0").click(function(){
        link = 0;
    });
    $("#tst1").click(function(){
        link = 1;
    });
    $("#tst2").click(function(){
        link = 2;
    });
    $("#tst3").click(function(){
        link = 3;
    });
    $("#tst4").click(function(){
        link = 4;
    });
    $("#tst4").click(function(){
        link = 5;
    });
    $("#tst5").click(function(){
        link = 6;
    });</script>
    <script type="text/javascript">
    function initMap() {
        
        var bounds = new google.maps.LatLngBounds;
        var markersArray = [];
        //
        var map, infoWindow;

        map = new google.maps.Map(document.getElementById('map'), {
            center: {lat: -34.397, lng: 150.644},
            zoom: 6
        });


        infoWindow = new google.maps.InfoWindow;

        // Try HTML5 geolocation.
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function (position) {
                var pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };

                infoWindow.setPosition(pos);
                infoWindow.setContent('Location found.');
                infoWindow.open(map);
                map.setCenter(pos);
            }, function () {
                handleLocationError(true, infoWindow, map.getCenter());
            });

            navigator.geolocation.getCurrentPosition(function (position) {
                //user location
                var origin1 = {lat: position.coords.latitude, lng: position.coords.longitude};
                var destinationA = 'Vancouver, Canada';

                var destinationIcon = 'https://chart.googleapis.com/chart?' +
                    'chst=d_map_pin_letter&chld=D|FF0000|000000';
                var originIcon = 'https://chart.googleapis.com/chart?' +
                    'chst=d_map_pin_letter&chld=O|FFFF00|000000';

                var geocoder = new google.maps.Geocoder;
                var testarr = [];

                //waypoints
                var directionsService = new google.maps.DirectionsService;
                var directionsDisplay = new google.maps.DirectionsRenderer;

                // console.log(document.getElementById('tst').innerText);

                function calculateAndDisplayRoute(directionsService, directionsDisplay) {
                    directionsService.route({
                        origin: origin1,
                        destination: testarr[link],
                        optimizeWaypoints: true,
                        travelMode: 'DRIVING'
                    }, function (response, status) {
                        if (status === 'OK') {
                            directionsDisplay.setDirections(response);
                            var route = response.routes[0];
                        } else {
                            window.alert('Directions request failed due to ' + status);
                        }
                    });
                }

                directionsDisplay.setMap(map);
                document.getElementById('update').addEventListener('click', function () {
                    calculateAndDisplayRoute(directionsService, directionsDisplay);
                });

                <?php
                $lineItems = array();
                $handle = fopen("testfile.txt", 'r');
                if ($handle) {
                    while (($line = fgets($handle)) !== false) {
                        $lineItems = explode(" break ", $line);
                        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=" . urlencode($lineItems[1]) . "&key=AIzaSyAiFt7-IlOjTC98doxhR-F1uf_QWnmWtoM";
                        $json = file_get_contents($url);
                        $json = json_decode($json);
                        $lat = $json->results[0]->geometry->location->lat;
                        $lng = $json->results[0]->geometry->location->lng;
                        echo "testarr.push({lat:" . $lat . ",lng:" . $lng . "});";

                    }
                    fclose($handle);
                } else {
                    echo "<p>Error opening file.</p>";
                }
                ?>
                //distance matrix
                var service = new google.maps.DistanceMatrixService;
                service.getDistanceMatrix({
                    origins: [origin1],
                    destinations: testarr[link],
                    travelMode: 'DRIVING',
                    unitSystem: google.maps.UnitSystem.METRIC,
                    avoidHighways: false,
                    avoidTolls: false
                }, function (response, status) {
                    if (status !== 'OK') {
                        alert('Error was: ' + status);
                    } else {
                        var originList = response.originAddresses;
                        var destinationList = response.destinationAddresses;
                        var outputDiv = document.getElementById('output');
                        outputDiv.innerHTML = '';
                        deleteMarkers(markersArray);

                        var showGeocodedAddressOnMap = function (asDestination) {
                            var icon = asDestination ? destinationIcon : originIcon;
                            return function (results, status) {
                                if (status === 'OK') {
                                    map.fitBounds(bounds.extend(results[0].geometry.location));
                                    markersArray.push(new google.maps.Marker({
                                        map: map,
                                        position: results[0].geometry.location,
                                        icon: icon
                                    }));
                                } else {
                                    alert('Geocode was not successful due to: ' + status);
                                }
                            };
                        };

                        for (var i = 0; i < originList.length; i++) {
                            var results = response.rows[i].elements;
                            geocoder.geocode({'address': originList[i]},
                                showGeocodedAddressOnMap(false));
                            for (var j = 0; j < results.length; j++) {
                                geocoder.geocode({'address': destinationList[j]},
                                    showGeocodedAddressOnMap(true));
                                outputDiv.innerHTML += originList[i] + ' to ' + destinationList[j] +
                                    ': ' + results[j].distance.text + ' in ' +
                                    results[j].duration.text + '<br>';
                            }
                        }
                    }
                });

            }, function () {
                handleLocationError(true, infoWindow, map.getCenter());
            });
        } else {
            // Browser doesn't support Geolocation
            handleLocationError(false, infoWindow, map.getCenter());
        }

    }

    function deleteMarkers(markersArray) {
        for (var i = 0; i < markersArray.length; i++) {
            markersArray[i].setMap(null);
        }
        markersArray = [];
    }

    function handleLocationError(browserHasGeolocation, infoWindow, pos) {
        infoWindow.setPosition(pos);
        infoWindow.setContent(browserHasGeolocation ?
            'Error: The Geolocation service failed.' :
            'Error: Your browser doesn\'t support geolocation.');
        infoWindow.open(map);
    }
    


</script>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q"
        crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl"
        crossorigin="anonymous"></script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAiFt7-IlOjTC98doxhR-F1uf_QWnmWtoM&callback=initMap">
</script>
</body>
</html>