<?php
require("config.php");

$curl = curl_init();

$query = http_build_query([
    'key' => $global['key'],
    'version' => $global['version'],
    'appVersion' => $global['appVersion'],
    'includeReferences' => "true",
    'lon' => $_GET['lon'],
    'lat' => $_GET['lat'],
    'lonSpan' => 0.0025,
    'latSpan' => 0.0025,
    'radius' => 100,
    'onlyDepartures' => "true",
    'limit' => 60,
    'minutesBefore' => 2,
    'minutesAfter' => 40,
    'groupLimit' => 4,
    'clientLon' => $_GET['lon'],
    'clientLat' => $_GET['lat']
]);

curl_setopt($curl, CURLOPT_URL, "https://futar.bkk.hu/api/query/v1/ws/otp/api/where/arrivals-and-departures-for-location.json?".$query);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
curl_setopt($curl, CURLOPT_HTTPHEADER, array(
    
));
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$curl_output = curl_exec($curl);
curl_close($curl);

$response = json_decode($curl_output,true);

$denorm = array(
    "currentTime" => $response['currentTime'],
    "references" => $response['data']['references']
);

//next departing time
$minTimeDiff = PHP_INT_MAX;
foreach($response['data']['list'] as $item) {
    foreach($item['stopTimes'] as $stoptime){
        $stoptime['stop'] = $response['data']['references']['stops'][$stoptime['stopId']];
        unset($stoptime['stopId']);
        $stoptime['route'] = $response['data']['references']['routes'][$item['routeId']];
        $stoptime['headsign'] = $item['headsign'];
        //selecting parent station
        $statId = 
            isset($stoptime['stop']['parentStationId']) && !is_null($response['data']['references']['stops'][$stoptime['stop']['parentStationId']]) ?
            $stoptime['stop']['parentStationId'] : 
            $stoptime['stop']['id'];
    
        
        if(!isset($denorm['data'][$statId]))
            $denorm['data'][$statId] = array(
                "list" => array(),
                "station" => $response['data']['references']['stops'][$statId]
            );
        array_push($denorm['data'][$statId]['list'],$stoptime);
        
        //min-search
        $depTime =
            isset($stoptime['predictedDepartureTime']) ?
            $stoptime['predictedDepartureTime'] : 
            $stoptime['departureTime'];
        $minTimeDiff = min(abs($depTime*1000-$denorm['currentTime']),$minTimeDiff);
    }
}
$denorm['refreshTime'] = min(max(round($minTimeDiff / 5), 3000), 300000);

echo json_encode($denorm,JSON_PRETTY_PRINT);
?>
