<?php
require("config.php");

require("sensitive.php"); // contains $weather_api_key

$curl = curl_init();

$query = http_build_query([
    'appid' => $weather_api_key,
    'lang' => "hu",
    'units' => "metric",
    'q' => "Budapest,HU"
]);

curl_setopt($curl, CURLOPT_URL, "http://api.openweathermap.org/data/2.5/weather?".$query);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    
));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$curl_output = curl_exec($curl);
curl_close($curl);

$response = json_decode($curl_output,true);

echo json_encode($response,JSON_PRETTY_PRINT);
?>
