<!DOCTYPE html>
<html>
<body>
<h1>HTML Geolocation</h1>
<p>Click the button to get your coordinates.</p>
<?php 
echo date("D");
?>

<button onclick="getLocation()">Try It</button>
<p id="demo"></p>

<script>
const x = document.getElementById("demo");

function getLocation() {
  if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);
  } else { 
    x.innerHTML = "Geolocation is not supported by this browser.";
  }
  //return position.coords.latitude;
  
}

function showPosition(position) {
  x.innerHTML = "Latitude: " + position.coords.latitude + 
  "<br>Longitude: " + position.coords.longitude;
  //$sql1 = "INSERT INTO Chktest SET lat = '',lon =''";
  

}
</script>
<?
echo date();
?>

</body>
</html>