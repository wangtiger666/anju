// JavaScript Document
var tab,s=0,isScroll=false,thisScrollTop;
$(document).ready(function(){
	showFun();
	$(".expoReview ul li").hover(function(){$(".expoReview ul li").removeClass("imgOn");$(this).addClass("imgOn");})
	$(".expoNextTit h4").click(function(){
		var $parentObj = $(this).parent().parent().parent();	
		var $index=$(this).index();	
		$parentObj.find('.expoNextTit h4 a').removeClass('on');
		$parentObj.find('.expoNextTit h4 a').eq($index).addClass('on');
		$parentObj.find('.expoNextContList').hide();
		$parentObj.find('.expoNextContList').eq($index).show();
	});
	//bolanhui tuijian
	$('.bolanhuiList li').hover(function(){
		$(this).find('b').stop(true,true).fadeIn(500);
		$(this).find('.whiteBroder').stop(true,true).fadeIn(500);
		$(this).find('.blackBg').show();
		
	},function(){
		$(this).find('b').stop(true,true).fadeOut(500);
		$(this).find('.whiteBroder').stop(true,true).fadeOut(500);
		$(this).find('.blackBg').hide();
	});
	$(".reviewMainLeftCont .reviewCont:last").addClass("borderNo");
	
	$(".bolanhuiList li a.blhImg b").each(function(){
		 $(this).after('<div class="whiteBroder">'+$(this).text()+'</div>');
    });
});

function showFun(){
	var liShu		= $(".expoLeftR .tabContList p a").length;
	if(liShu>9){
		var pingNum		= Math.ceil(liShu/9);
		var html		="";
		for(i=0;i<pingNum;i++){
			html +='<a href="javascript:void(0)">'+(i+1)+'</a>';	
		}
		$(".expoLeftRtab").append(html);
		$(".expoLeftRtab a:eq(0)").addClass("tabsOn");
		$('.expoLeftRCont a').hide();
		var index=0;
		function showIngFun(){	
			IngFun(index);
			if(index<pingNum-1){
				index++;
			}else{
				index=0;
			}
		}
		function IngFun(IngNum){
			$(".expoLeftRtab a").removeClass("tabsOn");
			$(".expoLeftRtab a:eq("+IngNum+")").addClass("tabsOn");
			$(".expoLeftRCont p a").fadeOut(100);
			for(var q=0;q<9;q++){
				$(".expoLeftRCont p a:eq("+((IngNum*9)+q)+")").fadeIn(100);
			}
		}
		
		$(".expoLeftRtab a").hover(function(){
			index=$(this).index();
			clearInterval(tab);
			IngFun($(this).index());
			if(index<pingNum-1){
				index++;
			}else{
				index=0;
			}
		},function(){
			clearInterval(tab);
			tab=setInterval(showIngFun,3000);
		});
		$(".expoLeftRCont").hover(function(){
			clearInterval(tab);
		},function(){
			clearInterval(tab);
			tab=setInterval(showIngFun,3000);
		});
		clearInterval(tab);
		showIngFun();
		tab=setInterval(showIngFun,3000);
	}//if
}
function videoFun(){
	$(".videoList li").hover(function(){
		$(this).find("b").addClass("videoHover");
	},function(){
		$(this).find("b").removeClass("videoHover");
	});
	$(".videoList li").click(function(){
		var videoUrl=$(this).attr("video-url");
		var flashvars={f:''+videoUrl+''};
		 CKobject.embedSWF('http://s.expoon.com/js/ckplayer/ckplayer.swf','video','ckplayer_video','642','517',flashvars);
		thisScrollTop=document.documentElement.scrollTop+document.body.scrollTop;
		$(".videoMain").css({"margin-top":(thisScrollTop-272)+"px"});
		$(".videoBg").css({"margin-top":(thisScrollTop)+"px"});
		$(".videoMain,.videoBg").show();
		isScroll=true;
	});	
	$(".videoClose").click(function(){
		isScroll=false;
		$(".videoMain").hide();
		$(".videoBg").hide();
	});
	$(".videoBg").click(function(){
		isScroll=false;
		$(this).hide();
		$(".videoMain").hide();
	});
}

window.onload=function(){
	videoFun();
}
function scrollFun(e){
if(isScroll){
		e.preventDefault();
	}
}
window.onmousewheel=document.onmousewheel=scrollFun;
if(document.addEventListener){
	document.addEventListener('DOMMouseScroll',scrollFun,false);  
}