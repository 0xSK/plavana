<html>
<head>
  <title>Pilot Front-end</title>
  <style>
    #map {
    height: 100%;
    }

    html,
    body {
    height: 100%;
    margin: 0;
    padding: 0;
    }
  </style>
</head>
<body>
  <div id="map"></div>
                <a href="alt.html" style="background: white; padding: 20px; z-index: 999; margin: 10px; border-radius: 100%; position: absolute; left: 0px; top: 400px; box-shadow: 0px 4px 13px 0px rgba(0, 0, 0, 0.41); font-size: 25px; text-decoration: none; color: black;">&rarr;</a>
  <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDB7RXH2jnglSvbxxCNPtD2xlNIcAOxdlA&callback=initMap"></script>
  <script language="javascript" >
  var map;
  var infoWindow;

  function initMap() {
    map = new google.maps.Map(document.getElementById('map'), {
      zoom: 10,
      center: {lat: <?php echo $_GET['lat'] ?>, lng: <?php echo $_GET['lng'] ?>},
      mapTypeId: google.maps.MapTypeId.HYBRID

    });
    <?php echo shell_exec("ruby triangles.rb ".$_GET['lat']." ".$_GET['lng']);?>
</script>
</body>
</html>
