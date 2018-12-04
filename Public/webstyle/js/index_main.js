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
	//banner换地址
	(function(){
		for(var i=0; i<$('.banner_warp ul li').length; i++){
			$('.banner_warp ul li').eq(i).find('img').attr('src',$('.banner_warp ul li').eq(i).find('img').attr('_src'));
		}
	})();
	
	//网络展览list
	(function(){
		function calcLi(){
			var scale = 190 / 320;
			if($(window).width()>=1190){
				var liHeight=$(window).width()/6;
				$('#auto_list li').width(liHeight);
				$('#auto_list li img').width(liHeight);
				$('#auto_list li').height(parseInt(liHeight * scale - 1));
				/*$('#auto_list li img').height(liHeight*scale);*/
				$('#auto_list li img').height(liHeight);
				$('#auto_list').width($(window).width());
			}
		}
		calcLi();
		
		$(window).resize(function(){
			calcLi();
		});	
		
		$('#auto_list li').hover(function(){
			$(this).find('p').stop().animate({
				bottom:0
			});	
		},function(){
			$(this).find('p').stop().animate({
				bottom:'-46px'
			});	
		});
	})();
	
	//视差滚动
	(function(){
		$('#bgParallax').css('background-attachment','fixed');
		$(window).scroll(function(){		
			$('#bgParallax').css('background-position','0px '+(0 + (Math.max($(window).scrollTop) / 8)) + 'px');
		});	
	})();
	
	//成就数字递加
	(function(){
		var Mark = 0;
		$(window).scroll(function(){
			if(Mark == 0 && $(window).scrollTop() > ($('.achievement').offset().top + 118 - $(window).height())){
				$('.timer').each(count);
				Mark++
			}
		});
		if(Mark == 0 && $(window).scrollTop() > ($('.achievement').offset().top + 118 - $(window).height())){
			$('.timer').each(count);
			Mark++
		}	
	})();
	
	//侧边栏
	(function(){
		$(window).scroll(function(){
			if ($(window).scrollTop() > 200) {
				$('#side_bar').fadeIn();
			}else{
				$('#side_bar').hide();	
			}
		});
		
		$('#side_bar li').hover(function(){
			$(this).addClass('hover');
			$('#side_bar div').eq($(this).index()).stop().animate({right:50});
		},function(){
			$(this).removeClass('hover');
			$('#side_bar div').eq($(this).index()).stop().animate({right:-$('#side_bar div').eq($(this).index()).outerWidth()});
		});
			
		$('#side_bar div').hover(function(){
			$('#side_bar li').eq($(this).index()-1).addClass('hover');
			$(this).stop().animate({right:50});
		},function(){
			$('#side_bar li').eq($(this).index()-1).removeClass('hover');
			$(this).stop().animate({right:-$(this).outerWidth()});	
		});
		
		$('.top_con').click(function(){
			$('body,html').animate({scrollTop:0},300);
		});
	})();
	
	//轮播图
	(function(){
		function tab(){
			if($('.banner_warp ol li').length<2){
				$('.banner_warp ol').hide();
				$('.banner_warp .left').hide();
				$('.banner_warp .right').hide();
				return;
			}
			$('.banner_warp ol').css('margin-left',-$('.banner_warp ol').width()/2);
			clearInterval(timer);
			var iNow=0;
			var timer=null;
			var timer2=null;
			var bClick=true;
			function tab(){
				$('.banner_warp ol li').removeClass('cur');
				$('.banner_warp ul li').stop().fadeOut(500);
				$('.banner_warp ol li').eq(iNow).addClass('cur');
				$('.banner_warp ul li').eq(iNow).stop().fadeIn(500);	
				setTimeout(function(){
					bClick=true;
				},500);
			};
	
			$('.banner_warp ol li').hover(function(){
				var _this=$(this);
				timer2=setTimeout(function(){
					iNow=_this.index();
					tab();
				},400);
			},function(){
				clearTimeout(timer2);	
			});
			
			function next(){
				iNow++;
				if(iNow==$('.banner_warp ol li').length)iNow=0;
				tab();
			};
			
			function prev(){
				iNow--;
				if(iNow==-1)iNow=$('.banner_warp ol li').length-1;
				tab();	
			};
			
			timer=setInterval(next,5000);
			
			$('.banner_warp .right').click(function(){
				if(bClick){
					bClick=false;
					next();	
				}
			})
			
			$('.banner_warp .left').click(function(){
				if(bClick){
					bClick=false;
					prev();	
				}
			});
			
			$('.banner_warp').hover(function(){
				clearInterval(timer);
				$('.banner_warp .left').show();
				$('.banner_warp .right').show();
			},function(){
				timer=setInterval(next,5000);
				$('.banner_warp .left').hide();
				$('.banner_warp .right').hide();
			});
			
			$('.banner_warp .left').hover(function(){
				$(this).addClass('hover');
			},function(){
				$(this).removeClass('hover');	
			});
			
			$('.banner_warp .right').hover(function(){
				$(this).addClass('hover');
			},function(){
				$(this).removeClass('hover');	
			});	
		};
		tab();	
	})();
	
	//热门行业
	(function(){
		$('.hot_industry_list_warp ul li .img').hover(function(){
			$(this).css('border-color','#c9c9c9');
		},function(){
			$(this).css('border-color','#f6f6f6');
		});
	})();
})