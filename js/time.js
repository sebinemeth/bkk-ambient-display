function lz(number, size) {
    var res = '';
    for (var i = 0; i < size - 1; i++)
        res += '0';
    return (res + number).slice(-1 * size);
}

function humanRead(timestamp, global) {
    if (!timestamp)
        return "";
    timestamp = parseInt(timestamp);
    var date = new Date(timestamp);
    var now = new Date(new Date().getTime() + global.delay);
    var minDiff = Math.round((timestamp - now.getTime()) / 60000);
    if (minDiff == 0)
        return "most";
    if (minDiff < 0)
        return Math.abs(minDiff) + " perce";
    if (minDiff < 25)
        return minDiff + "'";
    return lz(date.getHours(), 2) + ":" + lz(date.getMinutes(), 2);
}

function isPast(timestamp) {
    if (!timestamp)
        return false;
    timestamp = parseInt(timestamp);
    var date = new Date(timestamp);
    var now = new Date(new Date().getTime() + global.delay);
    return (date - now) < 0;
}

function updateServerTime(global) {
    $.get("back/serverTime.php", function (timeStamp) {
        global.delay = parseInt(timeStamp) - new Date().getTime();
        //TODO: toast
        //if(global.delay > 2000)
        //    alert("Device has " + global.delay +"ms relative delay to server time.");

        console.log("Device delay:", global.delay, "ms logged at", new Date());
        setTimeout(function () {
            updateServerTime(global);
        }, global.srvTimeFreq);
    });
}

function updateTime(global) {
    var devTime = new Date();
    var dt = new Date(devTime.getTime() + global.delay);
    var time = lz(dt.getHours(), 2) + ':' + lz(dt.getMinutes(), 2);
    if (global.config.showSecs)
        time += ':' + lz(dt.getSeconds(), 2);
    var months = ["január","február","március","április","május","június","július","augusztus","szeptember","október","november","december"]
    var date = global.config.fullDate ?
        dt.getFullYear() + '.' + lz(dt.getMonth() + 1, 2) + '.' + lz(dt.getDate(), 2) + '.':
        months[dt.getMonth()] + " " + lz(dt.getDate(), 2) + '.';
    global.DOMs.clock.html(time);
    global.DOMs.date.html(date);
    setTimeout(function () {
        updateTime(global);
    }, global.timeFreq);

    //displaying HR timestamps
    $(".dyn-timestamp").each(function (i, item) {
        $(item).html(humanRead($(item).attr("timestamp"), global));
    });
}