function toggleFullScreen() {
    var doc = window.document;
    var docEl = doc.documentElement;

    var requestFullScreen = docEl.requestFullscreen || docEl.mozRequestFullScreen || docEl.webkitRequestFullScreen || docEl.msRequestFullscreen;
    var cancelFullScreen = doc.exitFullscreen || doc.mozCancelFullScreen || doc.webkitExitFullscreen || doc.msExitFullscreen;

    if (!doc.fullscreenElement && !doc.mozFullScreenElement && !doc.webkitFullscreenElement && !doc.msFullscreenElement) {
        requestFullScreen.call(docEl);
    } else {
        cancelFullScreen.call(doc);
    }
}

function updateLocation(global) {
    var callBack = function (position) {
        global.clientLoc.lat = position.coords.latitude;
        global.clientLoc.lon = position.coords.longitude;
        console.log("Location updated", global.clientLoc);
        setTimeout(function () {
            updateLocation(global);
        }, global.locationFreq);
    }
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(callBack);
    } else {
        alert("Geolocation is not supported by this browser.");
    }
}

function updateLayout() {
    //vertical center align
    $(".time").css("margin", window.innerHeight / 2 - $(".time").outerHeight() / 2 + "px 5%");
    $("#bkk-container").css({
        "margin": Math.max(window.innerHeight / 2 - $("#bkk-container").outerHeight() / 2, 20) + "px 5%",
        "max-height": window.innerHeight - 40 + "px"
    });
    //fullscreen overlay
    $(".fs-div").height(window.innerHeight + "px");
    //long headsigns
    var trimHeadsign = function (headSign) {
        if (headSign.endsWith("..."))
            return headSign.substring(0, headSign.length - 5) + "...";
        return headSign.substring(0, headSign.length - 2) + "...";
    }
    $(".dep-row").each(function (index, item) {
        var headSign = $(this).find(".headsign");
        var maxWidth = $(this).innerWidth() - $(this).find(".route-badge").outerWidth() - $(this).find(".dep-time").outerWidth();
        var offset = 20;
        headSign.html(headSign.data("fullheadsign"));
        while (headSign.outerWidth() > maxWidth - offset && headSign.html().length > 4)
            headSign.html(trimHeadsign(headSign.html()))
    });
}