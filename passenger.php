<?php
/*
  @Author: ananayarora
  @Date:   2017-05-02T22:59:15+05:30
  @Last modified by:   ananayarora
  @Last modified time: 2017-05-03T18:18:36+05:30
*/
    error_reporting(0);
    require("api/getFlights.php");

    $f = new Flight();
    $flight = $f->getFlight($_GET['flight']);
    $destination = $f->getDestination($flight);
    $location = $f->getFlightLocation($flight);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Passenger – Plavana</title>
        <link rel="stylesheet" href="css/cesium.css">
        <link rel="stylesheet" href="css/passenger.css">
        <link rel="stylesheet" href="css/sweetalert.css">
        <script type="text/javascript" src="js/jquery.min.js"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDCobO4v0gsYoPKsodPJvgwuVLAi2rkM6A&libraries=drawing"></script>
        <script type="text/javascript" src="js/passenger.js"></script>
        <script type="text/javascript" src="js/sweetalert.min.js"></script>
    </head>
    <body>
        <div class="pilot">
                <center>
                    <div class="close">CLOSE WINDOW</div>
                </center>
                <iframe width="100%" height="100%" src="pilot/alt.html"></iframe>
        </div>
        <center>
          <div class="sidebar">
            <div class="top">
                <img src="img/logo.png">
            </div>
            <br />
            <br />
            <div class="info">
                <div class="small">You are currently flying over</div>
                <br />
                <div class="name currentlocation"><?php echo $location; ?></div>
            </div>
            <br />
            <br />
            <div class="line"></div>
            <br />
            <br />
            <div class="info">
                <div class="small">Your destination</div>
                <br />
                <div class="name destination"><?php echo $destination; ?></div>
            </div>
            <br />
            <br />
            <div class="line"></div>
            <br />
            <br />
            <div class="info">
                <div class="small">Time to destination</div>
                <br />
                <div class="name timetodestination">8h 41 m</div>
            </div>
            <br />
            <br />
            <div class="buttons">
                <div class="btn poi">Places of Interest</div>
                <div class="btn geo">Geological Map</div>
                <div class="btn dys">Dyslexic Mode</div>
                <div class="btn pilotinfo">Pilot Info</div>
            </div>
          </div>
        </center>
        <div id="map"></div>
    </body>
</html>
