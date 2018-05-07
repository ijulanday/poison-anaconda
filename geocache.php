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

        <!--ajax crap--> 
        <script src="http://ajax.googleapis.com/ajax/libs/prototype/1.6.1.0/prototype.js" type="text/javascript"></script>
		<script src="http://ajax.googleapis.com/ajax/libs/scriptaculous/1.8.3/scriptaculous.js" type="text/javascript"></script>

        <!--more ajax (jquery pls)-->
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
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
                        <input type="number" max="200" step="1" class="form-control" id="maxResults" name="maxResults" value="<?php echo isset($_POST['maxResults']) ? $_POST['maxResults'] : '20' ?>">
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
                        $radLon = $_POST["radius"] * abs((1 / ((cos($lat)) * 69.0)));
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
                            $cacheNum = 0;
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
                            <?php $i++; $cacheNum++;}}?>                       
                            
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

        <!--google map api script thingy-->
        <script>
        var map;
        var jsonCache = <?php echo $jsonCache;?>;
        
        //map optionz
        var options = {
            center: {lat: <?php echo $_POST["inputLat"]; ?>, lng: <?php echo $_POST["inputLon"]; ?>},
            zoom: 10
        };

        function initMap() {
            //new map
            map = new google.maps.Map(document.getElementById('map'), options);
            
            //add markers to map
            for (var i = 0; i < <?php echo $cacheNum; ?>; i++) {
                addMarker(jsonCache[i]);
            }
        }

        //add a marker and stuff
        function addMarker(jcache) {
            var type = ["Traditional", "Mystery/Puzzle", "Multi-Cache"];
            var flickrUrl = "https://api.flickr.com/services/rest/?api_key=6652e444acd4fb5cebeb6e7608de8384&method=flickr.photos.search&lat="
            +jcache.latitude+"&lon="+jcache.longitude;
            var imgs = [];

            var xmlhttp = new XMLHttpRequest();
            xmlhttp.open("GET", flickrUrl, true);
            xmlhttp.onreadystatechange = function() {
                if(xmlhttp.readyState == 4 && xmlhttp.status == 200) {//the below code is similar to the callback function onSuccess in Ajax.Request
                    var rsp = xmlhttp.responseXML.getElementsByTagName("rsp")[0];
                    var photos = rsp.getElementsByTagName("photos")[0].getElementsByTagName("photo");
                    for (var i = 0; i < 6; i++) {
                        var imgurl = "https://farm"
                                    +photos[i].getAttribute("farm")+
                                    ".staticflickr.com/"
                                    +photos[i].getAttribute("server")+
                                    "/"+photos[i].getAttribute("id")+
                                    "_"+photos[i].getAttribute("secret")+
                                    "_t.jpg";
                        imgs.push("<img src=\"" + imgurl + "\" vspace=\"10px\" hspace=\"10px\">");
                        
                        //hey this function call sucks, i need to figure out how
                        //  promises work and how to actually do ajax stuff because
                        //  this is super hacky
                        setUpInfo(i);
                    }
                }
                
            };
            xmlhttp.send();

            var marker = new google.maps.Marker({
                position: {lat: parseFloat(jcache.latitude), lng: parseFloat(jcache.longitude)},
                map: map,
            });
            
            //ewwwww asynchronous code hurts my brain
            function setUpInfo(i) {
                if (i != 5) {return;}
                marker.addListener('click', function(){
                info.open(map, marker);
                });

                var info = new google.maps.InfoWindow({
                    content: (
                        '<h3>Latitude: ' + jcache.latitude + '<br/>Longitude: ' + jcache.longitude + '</h3>'
                        + '<h5>' + 'Type: ' + type[parseInt(jcache.cache_type_id) - 1]
                        + '<br/>Difficulty: ' + jcache.difficulty_rating
                        + '</h5>' + '<p> photos taken near here:<br/>'
                        + imgs[0] + imgs[1] + imgs[2] + '<br/>' 
                        + imgs[3] + imgs[4] + imgs[5]
                    )
                });
            }
            
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

        <!--Autorz' nose: flicker API *frickn sucks*. The people who coded it are probs geniuses but I, however, am not.-->
    </body>
</html>