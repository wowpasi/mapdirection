<!DOCTYPE html>
<html>

<head>
    <title>Distance Calculator</title>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAb1h8lJvbq0BVs7-qijDrGitMBi7vMBsc"></script>
    <script>
        var map;
        var markers = [];
        var i = 1;

 // Create a new DirectionService object
 var directionsService;

        function initMap() {

            var initialLocation = {
                lat: 6.967769517607751,
                lng: 79.92962009763649
            };
            map = new google.maps.Map(document.getElementById("map"), {
                center: initialLocation,
                zoom: 13.5,
            });
            var marker = new google.maps.Marker({
                position: initialLocation,
                map: map,
            });
            var circle = new google.maps.Circle({
                strokeColor: "#99ccff",
                strokeOpacity: 0.8,
                strokeWeight: 2,
                fillColor: "#99ccff",
                fillOpacity: 0.35,
                map: map,
                center: initialLocation,
                radius: 2000, // in meters

            });
            markers.push(marker);
            circle.bindTo("center", marker, "position");

            circle.addListener("click", (event) => {
                           
               if(directionsService==null){
                directionsService=   new google.maps.DirectionsService();
                var marker = new google.maps.Marker({
                    position: event.latLng,
                    map: map,
                });
                markers.push(marker);
                if (markers.length >= 2) {
                    calculateDistance();
                }
                
            }

                




            });

            map.addListener("click", function(event) {


                alert("Not available for this area..");

            });
        }



        function calculateDistance() {
            var origin = "6.967769517607751, 79.92962009763649";
            var destination = markers[i].getPosition().lat() + "," + markers[i].getPosition().lng();
            i++;
            alert(destination);

            var service = new google.maps.DistanceMatrixService();
            service.getDistanceMatrix({
                    origins: [origin],
                    destinations: [destination],
                    travelMode: google.maps.TravelMode.DRIVING,
                    unitSystem: google.maps.UnitSystem.METRIC,
                },
                function(response, status) {
                    if (status !== google.maps.DistanceMatrixStatus.OK) {
                        alert("Error: " + status);
                    } else {
                        var distance = response.rows[0].elements[0].distance.value;
                        var distance_km = (distance / 1000).toFixed(2);
                        document.getElementById("output").innerHTML = origin + " : " + destination +
                            " - The distance between your location and the destination is " +
                            distance_km +
                            " km.";

                       

                        // Get directions from one location to another
                        directionsService.route({
                                origin: origin,
                                destination: destination,
                                travelMode: google.maps.TravelMode.DRIVING,
                            },
                            (response, status) => {
                                if (status === "OK") {
                                    // Display the directions on the map
                                    const directionsRenderer = new google.maps.DirectionsRenderer();
                                    directionsRenderer.setMap(map);
                                    directionsRenderer.setDirections(response);
                                } else {
                                    window.alert("Directions request failed due to " + status);
                                }
                            }
                        );
                    }
                }
            );
        }
    </script>
</head>

<body onload="initMap()">
    <h1>Distance Calculator</h1>
    <div id="map" style="height: 400px;"></div>
    <div id="output"></div>
</body>

</html>