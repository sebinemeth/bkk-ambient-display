<!doctype html>
<html lang="hu">
    <head>
        <title>BKK Ambient Display</title><meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="BKK Ambient Display">
        <meta name="theme-color" content="#000000">
        
        <link href="https://fonts.googleapis.com/css?family=Alata|Lato&display=swap" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        
        <link rel="stylesheet" href="main.css?v=<?=time();?>">
        <script src="js/main.js?v=<?=time();?>"></script>
        <script src="js/time.js?v=<?=time();?>"></script>
        <script src="js/bkk.js?v=<?=time();?>"></script>
        <script src="js/weather.js?v=<?=time();?>"></script>
        
        <link rel="icon" type="image/png" src="img/bad-icon-144.png">
        <meta name="apple-mobile-web-app-capable" content="yes">
        <meta name="apple-mobile-web-app-status-bar-style" content="black">
        <meta name="apple-mobile-web-app-title" content="BKK Ambient Display">
        <link rel="apple-touch-icon" href="img/bad-icon-192.png">
        <link rel="manifest" href="manifest.json">
    </head>
    <body>
        <div class="fs-div"></div>
        <div class="time">
            <div id="clock"></div>
            <div id="date"></div>
            <div id="weather">
                <div class="info">
                    <p>Getting weather info</p>
                </div>
            </div>
        </div><div id="bkk-container">
            <div class="ADFL-update dyn-timestamp"></div>
        </div>
        
        <script>
            var global = {
                config: {
                    showSecs: false
                },
                DOMs: {
                    clock: $("#clock"),
                    date: $("#date"),
                    ADFLContainer: $("#bkk-container"),
                    ADFLElem: null,
                    loader: null,
                },
                time: {},
                clientLoc: {},
                timeFreq: 500,
                srvTimeFreq: 10*60*1000, //10 min
                locationFreq: 30*1000, //30 sec
		weatherFreq: 15*60*1000, //15 min
                delay: 0
            }
            
            $(".fs-div").on('touchstart',function(){ toggleFullScreen(); });
            
            global.DOMs.ADFLElem = $("<div>").addClass("ADFL-elem").append($("<div>").addClass("stop-header")).append($("<div>").addClass("stop-body"));
            global.time.humanRead = humanRead;
            global.time.isPast = isPast;
            
            updateServerTime(global);
            updateTime(global);
            updateLocation(global);
            updateADFL(global);
            updateWeather(global);
            updateLayout();
            $(window).resize(updateLayout);
        </script>
    </body>
</html>
