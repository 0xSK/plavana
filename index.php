<?php
/*
  @Author: ananayarora
  @Date:   2017-05-02T19:50:46+05:30
  @Last modified by:   ananayarora
  @Last modified time: 2017-05-03T04:26:59+05:30
*/

    require("api/getFlights.php");

    $f = new Flight();

    $random = $f->getRandomFlight();
    $flight = $f->getFlight($random);
    
    while (!$f->check($flight)) {
      $random = $f->getRandomFlight();
      $flight = $f->getFlight($random);
    }

    $flightId = $random;
    $flightNumber = $f->getFlightNumber($flight);

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Plavana</title>
        <link rel="stylesheet" href="css/home.css">
    </head>
    <body>
        <div class="logo">
            <img src="img/logo.png" alt="">
        </div>
        <h1 class="logo_text">Plavana</h1>
        <div class="random_flight">Flight selected: <?php echo $flightNumber; ?></div>
        <div class="buttons">
            <a href="pilot/?lat=25.2048&lng=55.2708"><div class="btn inline">Pilot</div></a>
            <a href="passenger.php?flight=<?php echo $flightId; ?>"><div class="btn inline">Passenger</div></a>
        </div>
    </body>
</html>
