//广告轮播
function advert(){
	for(var i=0; i<$('.slider').length; i++){
		$('.slider').eq(i).attr('id','ad'+i);
		slider('#ad'+i);
	}
	function slider(id){
		for(var i=0; i<$(id+' ul li').length; i++){
			$(id+' ol').append('<li></li>');
		}

		$(id+' ol li').eq(0).attr('class','cur');
		$(id+' ol').css('margin-top',-$(id+' ol').height()/2+5);

		if($(id+' ol li').length<2){
			$(id+' ol').hide();
			return;
		}

		clearInterval(timer);
		var iNow=0;
		var timer=null;

		function tab(){
			$(id+' ol li').removeClass('cur');
			$(id+' ul li').hide();
			$(id+' ol li').eq(iNow).addClass('cur');
			$(id+' ul li').eq(iNow).show();
		};

		$(id+' ol li').hover(function(){
			clearInterval(timer);
			iNow=$(this).index();
			tab();
		});
		
		function next(){
			iNow++;
			if(iNow==$(id+' ol li').length)iNow=0;
			tab();
		};
		
		timer=setInterval(next,5000);
		
		$(id).hover(function(){
			clearInterval(timer);
		},function(){
			timer=setInterval(next,5000);
		});
	};
};
$(function(){
	var ad_arr	= [];
	var ad_number	= 0;
	$("script").each(function(){
		var id_val = $(this).attr("id");
		if(id_val !== undefined && id_val !== ''){
			if(id_val.substr(0,8) == "s_advert"){
				ad_arr[ad_number] = id_val.substr(9);
				ad_number++;
				//alert(id_val.substr(9));
			}
		}
	});
/*	if(ad_number !== 0){
		$.ajax({
			url: "http://api.expoon.com/index.php/Advert/getAllAdContent",
			type: 'GET',
			data:{ad_arr:ad_arr},
			dataType: "jsonp",
			jsonp: 'callback',
			success: function(json) {
				if(json.msg == 'ok'){
					$.each(json.c, function(i, n){
						$("#s_advert_"+i).replaceWith(n);
					});
				}
				advert();
			}
		});
	}*/
});