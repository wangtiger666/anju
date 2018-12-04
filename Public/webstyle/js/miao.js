function jump_url(u){
    window.location.href="http://user.expoon.com/SecKill/seckillReg";
}
function jump_url_pd(u){
    window.location.href="http://user.expoon.com/SecKill/secKillIndex/skId/"+u;
}
function view_szal(u){
    $.ajax({
        url:'http://www.expoon.com/index.php/Miao/Index/viewAddNum',
        type:'post',
        data:{h_id:u},
        dataType:"json",
        success:function(data){
            var viewV = $("#view_history_"+u).text();
            if(viewV != undefined){
                $("#view_history_"+u).html(viewV*1+1);
            }
        }//--success
    });//--ajax
}
$(function(){
	//计算闪展banner高度
	(function(){
		function calcBanner(){
			$('.shanBanner').height($(window).height()-$('#header_warp').outerHeight());
			$('.shanBannerCon').height($(window).height()-$('#header_warp').outerHeight());
			$('.shanBannerPos').css('margin-top',-$('.shanBannerPos').outerHeight()/2);	
		};
		calcBanner();
		$(window).resize(function(){
			calcBanner();
		});
	})();	
	
	//全局变量，动态的文章ID
	var ShareId = '';
	//分享hover
	(function(){
		for(var i=0; i<$('.shareBox').length; i++){
			$('.shareBox').eq(i).css('left',$('.moreActivities').eq(i).width()+50);
		}
		
		//分享插件自定义
		$('.bdsharebuttonbox a').mouseover(function () {
			ShareId = $(this).attr('data-id');
		});

        function SetShareUrl(cmd, config) {            
            if (ShareId) {
                config.bdUrl = 'http://www.expoon.com/' + ShareId;
                config.bdText = $('.shanListTitle h3[data-id='+ ShareId +']').html(); 
                config.bdPic = $('.shanListImg img[data-id='+ ShareId +']').attr('src');   
            }
            return config;
        }
		
        window._bd_share_config = {
            'common': {
                onBeforeClick:SetShareUrl,'bdSnsKey':{},'bdText':'','bdMini':'2'
                ,'bdMiniList':false,'bdPic':'','bdStyle':'1','bdSize':'32'
            }, 'share': {}
        };
		
        with (document) 0[(getElementsByTagName('head')[0] || body).appendChild(createElement('script')).src = 'http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion='+ ~(-new Date() / 36e5)];
		
		
		var timer=null;
		$('.share').hover(function(){
			clearTimeout(timer);
			$(this).parent().next().show();
		},function(){
			var _this=$(this);
			timer=setTimeout(function(){
				_this.parent('.moreActivities').next('.shareBox').hide();	
			},300);
		});
		$('.shareBox').hover(function(){
			clearTimeout(timer);
			$(this).show();
		},function(){
			var _this=$(this);
			timer=setTimeout(function(){
				_this.hide();	
			},300);
		});
	})();
	
	//点击banner下箭头
	(function(){
		$('.downArrow').click(function(){
			$('body,html').animate({scrollTop:$(window).height()},300);
		});	
	})();
});