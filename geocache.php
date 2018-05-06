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

        <!--lat/long/etc form, search results-->
        <div class="px-2 float-left mr-1" style="width: 40%;" >
            <div>
                <h4 align="center">Geocache Serarcher Thing</h4>
                <h6 align="center"><i>Or GST. Sophisticated, I know.</i></h6>
                <form id="poopform" action="geocache.php" method="post">
                <div class="form-group row">
                    <label for="inputLat" class="col-sm-5 col-form-label">Latitude: </label>
                    <div class="col-sm-5">
                        <input value="<?php echo isset($_POST['inputLat']) ? $_POST['inputLat'] : '32.2162358' ?>" type="number" step="0.00000000001" class="form-control" id="inputLat" name="inputLat">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="inputLon" class="col-sm-5 col-form-label">Longitude: </label>
                    <div class="col-sm-5">
                        <input value="<?php echo isset($_POST['inputLon']) ? $_POST['inputLon'] : '-110.88261449' ?>" type="number" step="0.00000000001" class="form-control" id="inputLon" name="inputLon">
                    </div>
                </div>
                
                <div class="form-group row">
                    <label for="radius" class="col-sm-5 col-form-label">Radius (miles): </label>
                    <div class="col-sm-5">
                        <input type="number" value="<?php echo isset($_POST['radius']) ? $_POST['radius'] : '10' ?>" step="5" max="200" class="form-control" id="radius" name="radius">
                    </div>
                </div>
                <div class="form-group row">
                    <label for="type" class="col-sm-3 col-form-label">Cache type: </label>
                    <div class="col-sm-5">
                            <select id="type" name="type" class="form-control" style="width: 170px;">
                                <option value="1">Traditional</option>
                                <option value="2">Mystery/Puzzle</option>
                                <option value="3">Multi-Cache</option>
                                <option value="0" selected>A N Y</option>
                            </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label for="diff" class="col-sm-3 col-form-label">Difficulty: </label>
                    <div class="col-sm-4">
                            <select id="diff" name="diff" class="form-control" style="width: 170px;">
                                <option value="baby">Baby (1 - 4)</option>
                                <option value="easy">Easy (5 - 7)</option>
                                <option value="normal">Normal (8 - 10)</option>
                                <option selected>A L L</option>
                            </select>
                    </div>
                </div>

                <div class="form-group row">
                    <label for="maxResults" class="col-sm-3 col-form-label">Max Results: </label>
                    <div class="col-sm-4">
                        <input type="number" max="200" step="10" class="form-control" id="maxResults" name="maxResults" value="<?php echo isset($_POST['maxResults']) ? $_POST['maxResults'] : '20' ?>">
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
                        $radLat = $_POST["radius"] / 69.0;
                        $radLon = $_POST["radius"] * (1 / ((cos($lat)) * 69.0));
                        $type = $_POST["type"];
                        $diff = $_POST["diff"];
                        $num = $_POST["maxResults"];
                        $defaultNum = 5;
                        $maxDiff = 10;
                        $minDiff = 1;
                        $cacheArray = [];

                        $caches = $db->query(
                        "SELECT *
                        FROM test_data
                        WHERE (
                        longitude
                        BETWEEN ($lon - $radLon)
                        AND ($lon + $radLon)
                        )
                        AND (
                        latitude
                        BETWEEN ($lat - $radLat)
                        AND ($lat + $radLat)
                        )
                        ");

                        $q2 = $db->query(
                        "SELECT *
                        FROM test_data
                        WHERE (
                        longitude
                        BETWEEN ($lon - $radLon)
                        AND ($lon + $radLon)
                        )
                        AND (
                        latitude
                        BETWEEN ($lat - $radLat)
                        AND ($lat + $radLat)
                        )
                        ")->fetchAll();
                        $jsonCache = json_encode($q2);

                        ?>

                        <!--php code for setting up the table thingy-->
                        <?php 
                            if ($diff == "baby") {
                                $maxDiff = 4;
                                $minDiff = 1;
                            } elseif ($diff == "easy") {
                                $maxDiff = 7;
                                $minDiff = 5;
                            } elseif ($diff == "normal") {
                                $maxDiff = 10;
                                $minDiff = 8;
                            }

                            if (!empty($caches)) { 
                            $i = 1;

                            foreach ($caches as $cache) { ?>
                        <tr>  
                            <?php 
                                if ($cache["difficulty_rating"] < $maxDiff && $cache["difficulty_rating"] > $minDiff) {
                                    if ($cache["cache_type_id"] == $type || $type == 0) {
                                        array_push($cacheArray, $cache["latitude"], $cache["longitude"]);
                            ?>
                                <!--add marker to map-->
                                <th scope="col"><?php echo $cache["latitude"]; ?></th>
                                <th scope="col"><?php echo $cache["longitude"]; ?></th>
                                <th scope="col"><?php echo $cache["difficulty_rating"]; ?></th>
                                <th scope="col"><?php echo $cache["cache_type_id"]; ?></th>
                            <?php $i++;}}?>                       
                            
                        </tr>
                        
                        <?php 
                            if ($i > $num && $num != null) {break;} elseif ($i > 5 && $num == null) {break;}
                            }} ?>

                    </thread>
                </table>
            </div>
        </div>

        <!--map bit-->
        <div id="map"></div>

        <!--lemme see my json stuff!-->
        <div><?php echo $jsonCache;?></div>

        <!--google map api script thingy-->
        <script>
        var map;
        var coordJSON = <?php echo json_encode($cacheArray);?>;
        var jsonCache = <?php echo $jsonCache;?>;
        alert(jsonCache[0].latitude);
        //map optionz
        var options = {
            center: {lat: <?php echo $_POST["inputLat"]; ?>, lng: <?php echo $_POST["inputLon"]; ?>},
            zoom: 10
        };

        function initMap() {
            //new map
            map = new google.maps.Map(document.getElementById('map'), options);
            
            //add markers to map
            for (var i = 0; i < coordJSON.length; i++) {
                addMarker(parseFloat(jsonCache[i].latitude),  parseFloat(jsonCache[i].longitude));
            }
        }

        //add marker
        function addMarker(lat, lon, diff, type) {
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