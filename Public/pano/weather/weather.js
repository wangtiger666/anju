
var krpanoplugin = function(){
	var local = this;

	var krpano = null;
	var plugin = null;
	var imgElement = null;

	

	local.registerplugin = function(krpanointerface, pluginpath, pluginobject)
	{
		krpano=krpanointerface;
		plugin=pluginobject;
		imgElement = document.createElement('img');
		imgElement.style.height = '42px';
		imgElement.style.width = '60px';
		imgElement.style.position = 'absolute';
		imgElement.style.top = '61px';
		imgElement.style.left = '28px';
		


		plugin.getdata = getdata;
		plugin.sprite.appendChild(imgElement);
	}

	local.unloadplugin = function()
	{
		plugin = null;
		krpano = null;
	}

	function iscity(){
		var location = {
			lng:krpano.get("skin_settings.lng"),
			lat:krpano.get("skin_settings.lat")
		}

		var cityname = krpano.get("skin_settings.cityname");

		if(location.lng =='' || location.lat == ''){
			return cityname;
		}
		else{
			return location.lng +"," + location.lat;
		}
	}
	function isHour(){
		var hour = new Date().getHours();
		if(hour >= 6 && hour <= 18 ){
			return true;  //白天
		}
		else{
			return false;  //夜间
		}
	}

	function getdata(){
		var link = "http://api.map.baidu.com/telematics/v3/weather?location="+iscity()+"&output=json&ak=6tYzTvGZSOpYB5Oc2YGGOKt8";
		$.ajax({
            url:link,
            type: "GET",
            dataType: "JSONP",
			success:function(data){
			var weather = data;
			
			//params
			var title = weather.results[0].currentCity;
			if(isHour()){
				var img_code = weather.results[0].weather_data[0].dayPictureUrl;
			}
			else{
				
				var img_code = weather.results[0].weather_data[0].nightPictureUrl;
			}
			

			var temperature = weather.results[0].weather_data[0].date.split("实时")[1];
				temperature = temperature.substring(1,temperature.length-2) + "°";
			var now_text = weather.results[0].weather_data[0].weather;
			var wind = weather.results[0].weather_data[0].wind;
			var temperature_range = weather.results[0].weather_data[0].temperature;

			//render
			krpano.set("layer[city-title].html",title);
			imgElement.src = img_code;
			krpano.set("layer[temperature].html",temperature);
			krpano.set("layer[desc_text].html",now_text);
			krpano.set("layer[wind_direction].html", wind);
			krpano.set("layer[temp_range].html",temperature_range);
		}
		
	  })
	}
}