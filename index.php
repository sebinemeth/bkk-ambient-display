<!doctype html>
<html>
    <head>
        <title>BKK Ambient Display</title><meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://fonts.googleapis.com/css?family=Alata|Lato&display=swap" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
        
        <link rel="stylesheet" href="main.css">
        <script src="js/main.js"></script>
        <script src="js/time.js"></script>
        <script src="js/bkk.js"></script>
        
    </head>
    <body>
        <div class="fs-div"></div>
        <div class="time">
            <div id="clock"></div>
            <div id="date"></div>
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
                srvTimeFreq: 600000, //10 min
                locationFreq: 30000, //30 sec
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
            updateLayout();
            $(window).resize(updateLayout);
        </script>
    </body>
</html>
