// JavaScript Document
var isScroll=false,thisScrollTop;
//数字递加插件
$.fn.countTo = function(options) {
    options = options || {};
    return $(this).each(function() {
        var settings = $.extend({}, $.fn.countTo.defaults, {
            from: $(this).data('from'),
            to: $(this).data('to'),
            speed: $(this).data('speed'),
            refreshInterval: $(this).data('refresh-interval'),
            decimals: $(this).data('decimals')
        }, options);
        var loops = Math.ceil(settings.speed / settings.refreshInterval),
            increment = (settings.to - settings.from) / loops;
        var self = this,
            $self = $(this),
            loopCount = 0,
            value = settings.from,
            data = $self.data('countTo') || {};
        $self.data('countTo', data);
        if (data.interval) {
            clearInterval(data.interval)
        }
        data.interval = setInterval(updateTimer, settings.refreshInterval);
        render(value);

        function updateTimer() {
            value += increment;
            loopCount++;
            render(value);
            if (typeof(settings.onUpdate) == 'function') {
                settings.onUpdate.call(self, value)
            }
            if (loopCount >= loops) {
                $self.removeData('countTo');
                clearInterval(data.interval);
                value = settings.to;
                if (typeof(settings.onComplete) == 'function') {
                    settings.onComplete.call(self, value)
                }
            }
        }
        function render(value) {
            var formattedValue = settings.formatter.call(self, value, settings);
            $self.html(formattedValue)
        }
    })
};

$.fn.countTo.defaults = {
    from: 0,
    to: 0,
    speed: 1000,
    refreshInterval: 100,
    decimals: 0,
    formatter: formatter,
    onUpdate: null,
    onComplete: null
};

function formatter(value, settings) {
    return value.toFixed(settings.decimals)
};

function count(options) {
    var $this = $(this);
    options = $.extend({}, options || {}, $this.data('countToOptions') || {});
    $this.countTo(options)
};

$(function(){
    //焦点图
    (function(){
        var focusWidth = $(".focus").width(); //获取焦点图的宽度（显示面积）
        var liNum = $(".focus ul li").length; //获取焦点图个数
        var index = 0;
        var picTimer;
        var firstText   = $(".focus ul li:first img").attr("alt");
        //以下代码添加数字按钮和按钮后的半透明条，还有上一页、下一页两个按钮
        var btn     = "<div class='btnBg'></div><div class='btn'>";
        btn         +='<b>"'+firstText+'"</b><p>';
        for(var i=0; i < liNum; i++) {
            btn     += "<span></span>";
        }
        btn         += "</p></div><div class='preNext pre'></div><div class='preNext next'></div>";
        $(".focus").append(btn);

        //为小按钮添加鼠标滑入事件，以显示相应的内容
        $(".focus .btn span").mouseover(function() {
            index = $(".focus .btn span").index(this);
            showPics(index);
        }).eq(0).trigger("mouseover");

        //上一页、下一页按钮透明度处理
        $(".focus").hover(function() {
            $(".focus .pre,.focus .preNext").stop(true,false).animate({"opacity":"1.0"},300);
        },function() {
            $(".focus .pre,.focus .preNext").stop(true,false).animate({"opacity":"0.5"},300);
        });

        //上一页按钮
        $(".focus .pre").click(function() {
            index -= 1;
            if(index == -1) {index = liNum - 1;}
            showPics(index);
        });

        //下一页按钮
        $(".focus .next").click(function() {
            index += 1;
            if(index == liNum) {index = 0;}
            showPics(index);
        });

        //本例为左右滚动，即所有li元素都是在同一排向左浮动，所以这里需要计算出外围ul元素的宽度
        $(".focus ul").css("width",focusWidth * (liNum));

        //鼠标滑上焦点图时停止自动播放，滑出时开始自动播放
        $(".focus").hover(function() {
            clearInterval(picTimer);
        },function() {
            picTimer = setInterval(function() {
                showPics(index);
                index++;
                if(index == liNum) {index = 0;}
            },4000); //此4000代表自动播放的间隔，单位：毫秒
        }).trigger("mouseleave");

        //显示图片函数，根据接收的index值显示相应的内容
        function showPics(index) { //普通切换
            var nowLeft = -index*focusWidth; //根据index值计算ul元素的left值
            $(".focus ul").stop(true,false).animate({"left":nowLeft},300); //通过animate()调整ul元素滚动到计算出的position
            $(".focus .btn span").removeClass("on").eq(index).addClass("on"); //为当前的按钮切换到选中的效果
            //$(".focus .btn span").stop(true,false).animate({"opacity":"0.4"},300).eq(index).stop(true,false).animate({"opacity":"1"},300); //为当前的按钮切换到选中的效果
            dangText = $(".focus ul li:eq("+index+") img").attr("alt");
            $(".focus .btn b").html("").html(dangText);
        }
    })();
	
	 $(document).ready(function(){//阅读关键词  临时的 90天后删除 当前日期20160909
		 var ydKey = $(".readKeyWord").clone();
		$(".readKeyWord").remove();
		$(".newsContent").after(ydKey);
		//资讯导航  临时的 90天后删除 当前日期20160912
		//$('.subNav p').html('').append('<a href="http://news.expoon.com">资讯首页</a><span>/</span><a href="http://news.expoon.com/qjxw/">全景新闻</a><span>/</span><a href="http://news.expoon.com/qjzs/">全景知识</a><span>/</span><a href="http://news.expoon.com/hyzs/">行业资讯</a><span>/</span><a href="http://news.expoon.com/qyzl/">企业专栏</a><span>/</span><a href="http://news.expoon.com/wzdt/">网展动态</a><span>/</span><a href="http://news.expoon.com/zhsd/">展会速递</a></p>');
	});
		
    //行业知识
    /*(function(){
        $(".tabbox .tabTitle ul li").hover(function(){
            var thisIndex   = $(this).index();
            $(".tabbox .tabTitle ul li").removeClass("on");
            $(".tabbox .tabTitle ul li:eq("+thisIndex+")").addClass("on");
            $(".tabContBox .tabCont").hide();
            $(".tabContBox .tabCont:eq("+thisIndex+")").show();
        });
    })();*/
    //网展动态
    (function(){
        $(".expodynamicCont02 ul li a").wrapInner("<span class='out'></span>");
        $(".expodynamicCont02 ul li a").each(function(){
            $(this).append("<span class='over'>"+$(this).text()+"</span>");
        });
            $(".expodynamicCont02 ul li a").hover(function(){
            $(this).find("span.out").stop(true,true).animate({"bottom":"30px"},200);
            $(this).find("span.over").stop(true,true).animate({"bottom":"0px"},200);
        },function(){
            $(this).find("span.out").stop(true,true).animate({"bottom":"0px"},200);
            $(this).find("span.over").stop(true,true).animate({"bottom":"-30px"},200);
        });
    })();
    //网展视频
    (function(){
        $(".expoVideo ul li").hover(function(){
            $(".expoVideo ul li").find("i").stop(true,true).hide();
            $(".expoVideo ul li").find("div").stop(true,true).hide();
            $(".expoVideo ul li").find("span").stop(true,true).hide();
            $(this).find("i").stop(true,true).show();
            $(this).find("div").stop(true,true).show();
            $(this).find("span").stop(true,true).show();
        },function(){
            $(".expoVideo ul li").find("i").stop(true,true).hide();
            $(".expoVideo ul li").find("div").stop(true,true).hide();
            $(".expoVideo ul li").find("span").stop(true,true).hide();
        });

        $(".videoList li").click(function(e){
        var videoUrl=$(this).attr("video-url");
        var flashvars={f:''+videoUrl+''};
         CKobject.embedSWF('.swf','video','ckplayer_video','642','517',flashvars);
        thisScrollTop=document.documentElement.scrollTop+document.body.scrollTop;
        $(".videoMain").css({"margin-top":(thisScrollTop-272)+"px"});
        $(".videoMainBg").css({"margin-top":(thisScrollTop)+"px"});
        $(".videoMain").show();

        $(".videoMainBg").show();
        isScroll=true;
    });

    $(".videoClose").click(function(){
        isScroll=false;
        $(".videoMain").hide();
        $(".videoMainBg").hide();
    });
    $(".videoMainBg").click(function(){
        isScroll=false;
        $(this).hide();
        $(".videoMain").hide();
    });
    })();
    //展位
    (function(){
        $(".panoIndexList ul li a.img span").each(function(){
            $(this).after("<b>"+$(this).text()+"</b>");
        });
        $(".panoIndexList ul li").hover(function(){
            $(".panoIndexList ul li").find("i").stop(true,true).hide();
            $(".panoIndexList ul li").find("span").stop(true,true).hide();
            $(".panoIndexList ul li").find("b").stop(true,true).hide();
            $(this).find("i").stop(true,true).show();
            $(this).find("span").stop(true,true).show();
            $(this).find("b").stop(true,true).show();
        },function(){
            $(".panoIndexList ul li").find("i").stop(true,true).hide();
            $(".panoIndexList ul li").find("span").stop(true,true).hide();
            $(".panoIndexList ul li").find("b").stop(true,true).hide();
        });
    })();

    //成就数字递加
    (function(){
        var Mark = 0;
        if(Mark == 0){
            $.ajax({
                type: "get",
                //url:"http://news.expoon.com/Index201512/getNewsCountByAjax",
                dataType: "jsonp",
                success: function(data){
                    if(data.msg == "ok"){
                        var newsc = data.c;
                        $('.timer').attr("data-to",newsc);
                        $('.timer').each(count);
                        Mark++;
                    }
                }
            });
        }
    })();

    //视频列表页
    (function(){
        $('.event_year label').click(function(){
            $('.event_year>li').removeClass('current');
            $(this).parent('li').addClass('current');
            var year = $(this).attr('for');
            $('#'+year).parent().prevAll('div').slideUp(800);
            $('#'+year).parent().slideDown(800).nextAll('div').slideDown(800);
        });
    })();

	
	

    //资讯内容页 百度分享
    var newsContShareBd= '<div class="bdsharebuttonbox"><a href="#" class="bds_tsina" data-cmd="tsina" title="分享到新浪微博"></a><a href="#" class="bds_weixin" data-cmd="weixin" title="分享到微信"></a><a href="#" class="bds_qzone" data-cmd="qzone" title="分享到QQ空间"></a></div><script>window._bd_share_config={"common":{"bdSnsKey":{},"bdText":"","bdMini":"2","bdMiniList":false,"bdPic":"","bdStyle":"1","bdSize":"32"},"share":{}};with(document)0[(getElementsByTagName("head")[0]||body).appendChild(createElement("script")).src="http://bdimg.share.baidu.com/static/api/js/share.js?v=89860593.js?cdnversion="+~(-new Date()/36e5)];<\/script>';
    $(".shareBd").append(newsContShareBd);

});

function scrollFun(e){
    if(isScroll){
        e.preventDefault();
    }
}
//鼠标滚轴事件
window.onmousewheel=document.onmousewheel=scrollFun;
if(document.addEventListener){
    document.addEventListener('DOMMouseScroll',scrollFun,false);  // 火狐特有
}

window.onscroll=function(){
    if(isScroll){
        $("html,body").scrollTop(thisScrollTop);
        return false;
    }
    if(!($("div.leftVideo").is(":hidden"))){
        if($(window).scrollTop()>366){
            $("div.leftVideo").css({"top":$(window).scrollTop()-366});
        }else{
            $("div.leftVideo").css({"top":"44px"});
        }
    }
};