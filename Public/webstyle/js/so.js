// JavaScript Document
var winW,winH,listSearchVal;
$(document).ready(function(){
	saveSearchCount();
	setSize();
	$(".khd .khdTitle").hover(function(){
		$(".khd .khdBox").show();
	});
	$(".khd,.khd .khdBox").mouseleave(function(){
		$(".khd .khdBox").hide();
	});
	
	$(".searchListImg ul li").hover(function(){
		$(this).addClass("on");
		$(this).find("i").fadeIn("500").end().find("b").show().animate({"margin-top":"-28px"},500);
	},function(){
		$(this).removeClass("on");
		$(this).find("i").fadeOut("500").end().find("b").animate({"margin-top":"-38px"},500).hide();
	});
	
	// 列表页 搜索框获取焦点时
	$(".searchCont .searchInput").focus(function(){
		listSearchVal	= $(".searchCont .searchInput").val();
		$(".searchCont .searchBtn").addClass("searchBtnOn");
	}).blur(function(){
		var nowListSearchVal =$(".searchCont .searchInput").val(); 
		$(".searchCont .searchBtn").removeClass("searchBtnOn");
		if(nowListSearchVal == ""){
			$(".searchInput").val(listSearchVal);
		}	
	});


	$(".indexMenu span").click(function(){
		var thisSpan = $(this).index();
		$(".indexMenu span").removeClass("curr");
		$(".indexMenu span:eq("+thisSpan+")").addClass("curr");
	});
	
});

function saveSearchCount(){
	var url = window.location.href;
    var loc = url.substring(url.lastIndexOf('/')+1, url.length);
	var typestr = loc.substring(0,3);
	var so_id = '';
	var so_type = '';
	if(typestr == 'qwd'){
		so_type = '1';
	} else if(typestr == 'nwd'){
		so_type = '4';
	} else {
		return false;
	}
	var so_count = $('.searchResult p span').html();
	if(so_count == undefined) so_count = 0;
	var so_id = loc.substring(4,loc.lastIndexOf('.'));
	if(so_id!='' && so_type!='' && so_count>0){
		$.ajax({ 
			type: "get", 
			url:"http://so.expoon.com/index.php/Index201512/searchStatisticsCount",
			data:{'id':so_id,'type':so_type,'count':so_count},
			async:true, 
			dataType: "text",
			success: function(){}
		});	
	}
}

function setSize(){
	winW = document.documentElement.clientWidth; //浏览器可视区域的宽度
	winH = document.documentElement.clientHeight; //浏览器可视区域的宽度
	searchImg();
	searchImgHot();
}
function searchImg(){
	var $liWidth	= (winW -80)/5;
	var $imgHeight	= ($liWidth)*(216/364);
	if(winW > 1210){
		$liWidth	= Math.floor($liWidth);
		$imgHeight	= Math.floor($imgHeight);
	}else if(winW <= 1210){
		$liWidth	= 222;
		$imgHeight	= 128;
	}
	$(".searchList .searchListImg ul li").css("width",($liWidth+15)+"px");
	$(".searchList .searchListImg ul li a.img").css({"width":$liWidth+"px","height":$imgHeight+"px"});
	$(".searchList .searchListImg ul li a.img img").css({"width":$liWidth+"px","height":$imgHeight+"px"});
}	
function searchImgHot(){
	var $liWidth	= (winW -80)/5;
	var $imgHeight	= ($liWidth)*(256/381);
	if(winW > 1210){
		$liWidth	= Math.floor($liWidth);
		$imgHeight	= Math.floor($imgHeight);
	}else if(winW <= 1210){
		$liWidth	= 222;
		$imgHeightNo= 149;
	}
	$(".searchListHot .searchListImg ul li").css("width",($liWidth+15)+"px");
	$(".searchListHot .searchListImg ul li a.img").css({"width":$liWidth+"px","height":$imgHeight+"px"});
	$(".searchListHot .searchListImg ul li a.img img").css({"width":$liWidth+"px","height":$imgHeight+"px"});
}
window.onresize=setSize;
document.write('<script src="http://s.expoon.com/js/201512/search201512.js" language="javascript" type="text/javascript"></script>');