<!DOCTYPE html>
<html lang="en">
    <head>
        <!-- a stoopy doopy website by ian ulanday -->

        <title>Eskettit!</title>
        <meta name="robots" content="noindex">

        <!--bootstrap css-->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
        
        <!--bootstrap meta-->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        
        <!--map meta and style-->
        <meta name="viewport" content="initial-scale=1.0">
        <meta charset="utf-8">
        <style>
            /* Always set the map height explicitly to define the size of the div
            * element that contains the map. */
            #map {
            height: 100%;
            width: 57%;
            float: right;
            }
            /* Optional: Makes the sample page fill the window. */
            html, body {
            height: 100%;
            margin: 0;
            padding: 0;
            }
        </style>

   
    </head>
    
    
    <body>
        <div class="px-2 text-center">
            <h1>Geocache Locator!</h1>
            <p>A simple Google Maps Geocache locator.<br/>Don't expect any of the data here to be accurate irl :p</p>
            <hr/>
        </div>

        <!--lat/long/etc form-->
        <div class="px-2 float-left mr-1" style="width: 40%;" >
            <div>
                <h4 align="center">Geocache Serarcher Thing</h4>
                <h6 align="center"><i>Or GST. Sophisticated, I know.</i></h6>
                <form id="poopform" action="geocache.php" method="post">
                <div class="form-group row">
                    <label for="inputLat" class="col-sm-5 col-form-label">Latitude: </label>
                    <div class="col-sm-5">
                        <input value="32.2162358" type="number" step="0.00000000001" class="form-control" id="inputLat" name="inputLat">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputLon" class="col-sm-5 col-form-label">Longitude: </label>
                    <div class="col-sm-5">
                        <input value="-110.88261449" type="number" step="0.00000000001" class="form-control" id="inputLon" name="inputLon">
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="radius" class="col-sm-5 col-form-label">Radius (miles): </label>
                    <div class="col-sm-5">
                        <input type="number" value="10" class="form-control" id="radius" name="radius">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="type" class="col-sm-3 col-form-label">Cache type: </label>
                    <div class="col-sm-5">
                            <select id="type" name="type" class="form-control" style="width: 170px;">
                                <option>Traditional</option>
                                <option>Mystery/Puzzle</option>
                                <option>Multi-Cache</option>
                                <option selected>A N Y</option>
                            </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="diff" class="col-sm-3 col-form-label">Difficulty: </label>
                    <div class="col-sm-4">
                            <select id="diff" name="diff" class="form-control" style="width: 170px;">
                                <option value="baby" selected>Very Easy (1 - 4)</option>
                                <option value="easy">Easy (5 - 7)</option>
                                <option value="normal">Normal (8 - 10)</option>
                                <option value="all">A L L</option>
                            </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="maxResults" class="col-sm-3 col-form-label">Max Results: </label>
                    <div class="col-sm-4">
                        <input type="number" max="20" value="5" class="form-control" id="maxResults" name="maxResults">
                    </div>
                </div>

                <button onclick="getCaches();" type="submit" class="btn btn-primary">Search!</button> <-- Try it!
                </form>
                <hr/>
            </div>

            <!--cache search results-->
            <div>
                <h4 align="center">Search Results</h4>
                <table class="table table-striped">
                    <thread>
                        <tr>
                            <th scope="col">Latitude</th>
                            <th scope="col">Longitude</th>
                            <th scope="col">Difficulty</th>
                            <th scope="col">Type</th>
                        </tr>

                        <!--php code for getting cache search results-->
                        <?php 
                        $db = new PDO("mysql:host=150.135.53.5;dbname=test;port=3306", "student", "B3@rD0wn!");
                        $lat = $_POST["inputLat"];
                        $lon = $_POST["inputLon"];
                        $radLat = $_POST["radius"] * 69;
                        $radLon = (cos($radLat) / 180) * 69;
                        $type = $_POST["type"];
                        $diff = $_POST["diff"];
                        $num = $_POST["maxResults"];

                        $caches = $db->query(
                        "SELECT *
                        FROM test_data
                        WHERE (longitude BETWEEN ($lon + $radLon) AND ($lon - $radLon)) AND (latitude BETWEEN ($lat + $radLat) AND ($lat - $radLat));
                        ");
                        ?>

                        <!--php code for setting up the table thingy-->
                        <?php for ($i = 0; $i < $_POST["maxResults"]; $i++) { ?>
                        <tr>
                            <th scope="col">Gabe</th>
                            <th scope="col">Newell</th>
                            <th scope="col">Is a</th>
                            <th scope="col">Beast</th>
                        </tr>
                        
                        <?php } ?>

                    </thread>
                </table>
            </div>
        </div>

        <!--map bit-->
        <div id="map"></div>
        <!--google map api script thingy-->
        <script>
        var map;
        //map optionz
        var options = {
            center: {lat: 32.2162358, lng: -110.88261449},
            zoom: 10
        };

        function initMap() {

            //new map
            map = new google.maps.Map(document.getElementById('map'), options);

            addMarker(32.2162358, -110.88261449);

        }

        //add marker
        function addMarker(lat, lon) {
            var marker = new google.maps.Marker({
                position: {lat: lat, lng: lon},
                map: map
            });
        }
        </script>
        <script src="https://maps.googleapis.com/maps/api/js?key= AIzaSyBENZ68e3RP9aIlqyB8QHBBwG1n4hWyRqs&callback=initMap"
        async defer></script>

        <!--bootstrap js-->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>

        <!--mys tuff-->
        <script src="search.js"></script>
    </body>
</html>