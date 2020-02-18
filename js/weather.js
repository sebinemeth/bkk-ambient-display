function updateWeather(global) {
    $.getJSON("back/weather.php",function(response){
        console.log(response)
        var weather = $("#weather").empty().html("<table><tr></tr></table>").find("tr")
        $("<td rowspan='2'>").addClass("temp").html(Math.floor(response.main.temp) + "°C").appendTo(weather)
        $("<td rowspan='2'>").addClass("icon").css("background-image","url(https://openweathermap.org/img/wn/"+response.weather[0].icon+"@2x.png)").appendTo(weather)
        $("<td>").addClass("condition").html(response.weather[0].description).appendTo(weather)

        $("<tr>").html($("<td>")
                       .append($("<span>").addClass("location").html(response.name))
                       .append($("<span>").addClass("updated dyn-timestamp").attr("timestamp",(new Date().getTime())+global.delay)))
            .insertAfter(weather)
        setTimeout(function(){
            updateWeather(global)
        },global.weatherFreq)
    })
    $("#weather .info").empty().append("<p>Could not get weather information</p>")
}
