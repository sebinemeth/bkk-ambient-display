function updateADFL(global) {
    if (global.clientLoc.lat && global.clientLoc.lon) {
        $.getJSON("back/ADFL.php", global.clientLoc, function (response) {
            console.log(response);
            try {
                global.DOMs.ADFLContainer.find(".ADFL-elem").remove();
                global.DOMs.ADFLContainer.find(".ADFL-update").attr("timestamp", response.currentTime);
                $.each(response.data, function (id, stationItem) {
                    var e = global.DOMs.ADFLElem.clone().addClass(stationItem.station.id);
                    e.find(".stop-header").html(stationItem.station.name);
                    $.each(stationItem.list, function (i, item) {
                        var depRow = $("<div>").addClass("dep-row")
                            .append($("<div>").addClass("route-badge").html(item.route.shortName).css("background-color", "#" + item.route.style.color))
                            .append($("<div>").addClass("headsign").html(item.headsign).attr("data-fullheadsign", item.headsign));
                        var depTimestamp = item.predictedDepartureTime ? item.predictedDepartureTime * 1000 : item.departureTime * 1000;
                        var depTime = $("<div>").addClass("dep-time dyn-timestamp").html(global.time.humanRead(depTimestamp, global)).attr("timestamp", depTimestamp);
                        if (item.predictedDepartureTime)
                            depTime.addClass("pred");
                        if (global.time.isPast(depTimestamp))
                            depTime.addClass("past");
                        e.find(".stop-body").append(depRow.append(depTime));
                    });
                    var sorted = e.find(".dep-row").sort(function (a, b) {
                        var aTs = parseInt($(a).find(".dep-time").attr("timestamp"));
                        var bTs = parseInt($(b).find(".dep-time").attr("timestamp"));
                        if (aTs > bTs)
                            return 1;
                        if (aTs < bTs)
                            return -1;
                        return 0;
                    });
                    e.find('.stop-body').append(sorted);
                    global.DOMs.ADFLContainer.append(e);
                });
                updateLayout();
            } catch (e) {
                alert(e.message);
            }
            setTimeout(function () {
                updateADFL(global);
            }, response.refreshTime);
        });
    } else {
        console.log("No location info, retrying");
        setTimeout(function () {
            updateADFL(global);
        }, 1000);
    }
}