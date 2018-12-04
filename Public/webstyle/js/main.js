// JavaScript Document
var winW,winH;
$(document).ready(function(){
	setSize();
	if(sessionStorage.getItem('show')=='true'){
		$(".classifyList ul li.shouqi").show();
		$(".classifyList ul li b").removeClass("kaiqi");
	}
	$(".classifyList ul li b").click(function(){
		if(!($(this).hasClass("kaiqi"))){
			$(".classifyList ul li.shouqi").hide();
			$(this).addClass("kaiqi");
			sessionStorage.setItem('show','false');
		}else{
			$(".classifyList ul li.shouqi").show();
			$(this).removeClass("kaiqi");
			sessionStorage.setItem('show','true');
		}
		//console.log(sessionStorage.getItem('show'))
	});
	
	$(".classifyListImg ul li").hover(function(){
		$(this).addClass("on");
		$(this).find("i").fadeIn("500").end().find("b").show().animate({"margin-top":"-28px"},500);
	},function(){
		$(this).removeClass("on");
		$(this).find("i").fadeOut("500").end().find("b").animate({"margin-top":"-38px"},500).hide();
	});
	//index
	$(".wlzgTopFenLeiList").hover(function(){
		$(this).find(".wlzgTopRList").show();
	},function(){
		$(this).find(".wlzgTopRList").hide();
	});
	
	//热门
	$('.wlzgHotCont li').hover(function(){
		$(this).find('b').stop(true,true).fadeIn(500);
		$(this).find('.whiteBroder').stop(true,true).fadeIn(500);
		$(this).find('.blackBg').show();
		
	},function(){
		$(this).find('b').stop(true,true).fadeOut(500);
		$(this).find('.whiteBroder').stop(true,true).fadeOut(500);
		$(this).find('.blackBg').hide();
	});
	
	//查看其它省市
	$(".provinceTab span").click(function(){
		if(!($(this).hasClass("provinceTabOn"))){
			$(".provinceTabList").show();
			$(this).addClass("provinceTabOn");
			$(".provinceTab").css("border-bottom","none");
			$(this).css("border-left","none");
		}else{
			$(".provinceTabList").hide();
			$(this).removeClass("provinceTabOn");
			$(".provinceTab").css("border-bottom","#dfdfdf 1px solid");
			$(this).css("border-left","#dfdfdf 1px solid");
		}
	});
});


function setSize(){
	winW = document.documentElement.clientWidth; //浏览器可视区域的宽度
	winH = document.documentElement.clientHeight; //浏览器可视区域的宽度
	searchImg();
	searchImgNo();
}
function searchImg(){
	var $liWidth		= (winW -80)/5;
	var $imgHeight		=  ($liWidth)*(216/364);
	if(winW > 1210){
		$liWidth	= Math.floor($liWidth);
		$imgHeight	= Math.floor($imgHeight);
	}else if(winW <= 1210){
		$liWidth	= 222;
		$imgHeight	= 128;
	}
	$(".classifyListBox .classifyListImg ul li").css("width",($liWidth+15)+"px");  
	$(".classifyListBox .classifyListImg ul li a.img").css({"width":$liWidth+"px","height":$imgHeight+"px"});
	//$(".classifyListBox .classifyListImg ul li a.img img").css({"width":$liWidth+"px","height":$imgHeight+"px"});
	$(".classifyListBox .classifyListImg ul li a.img img").css({"width":$liWidth+"px","height":"268px"});
}	
function searchImgNo(){
	var $liWidth		= (winW -80)/5;
	var $imgHeightNo	= ($liWidth)*(256/381);
	if(winW > 1210){
		$liWidth	= Math.floor($liWidth);
		$imgHeightNo	= Math.floor($imgHeightNo);
	}else if(winW <= 1210){
		$liWidth	= 222;
		$imgHeightNo= 149;
	}
	$(".classifyListBoxNo .classifyListImg ul li").css("width",($liWidth+15)+"px");
	$(".classifyListBoxNo .classifyListImg ul li a.img").css({"width":$liWidth+"px","height":$imgHeightNo+"px"});
	//$(".classifyListBoxNo .classifyListImg ul li a.img img").css({"width":$liWidth+"px","height":$imgHeightNo+"px"});
	$(".classifyListBoxNo .classifyListImg ul li a.img img").css({"width":$liWidth+"px","height":"268px"});
}
window.onresize=setSize;





