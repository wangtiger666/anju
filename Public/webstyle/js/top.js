// JavaScript Document
$(function(){
	//浮动导航
	(function(){
		var defaultSearch = $('#so_wd1').val();
		var defaultSearchTe = $("#search_v1 input[name=te]").val();
		if(document.getElementById('header_warp')){
			var html = '';
				html += '<div class="float_nav_con">';
				html +='<ul class="small_nav">';
				html +='<li><a href="index.php?s=/Website">首页</a></li>';
				html +='<li><a href="index.php?s=/Website/qjzs">全景案例</a></li>';
				html +='<li><a href="index.php?s=/Website/qjzt">全景专题</a></li>';
				html +='<li><a href="index.php?s=/Website/qjfx">全景发现</a></li>';
				html +='<li><a href="index.php?s=/Website/wzzx">全景资讯</a></li></ul>';
				html +='<div class="small_login_warp fr">';
				html +='<div class="login fl"><a href="/member/login" target="_blank" class="login_btn">登录</a> <span class="fl">&frasl;</span> <a href="/member/reg" target="_blank">注册</a></div></div>';
		
				html +='<div class="small_search"><form method="get"  enctype="multipart/form-data" id="search_v2">';
				html +='<input type="text" id="so_wd2" name="wd" value="'+defaultSearch+'" /> <input type="button" class="small_search_btn"  onclick="search()" />';
				html +='<input name="te" type="hidden" value="'+defaultSearchTe+'" /></form></div></div>';
				$("#float_nav").html("").html(html);
			$(window).scroll(function(){
				if ($(window).scrollTop() > ($('#header_warp').offset().top + $('#header_warp').outerHeight()+40)) {
					$('#float_nav').stop().animate({
						top:0
					});
				}else{
					$('#float_nav').stop().animate({
						top:'-40px'
					});	
					$('.float_nav_con .client_download').hide();
				}
			});		
		}	
	})();
	
	
	//客户端下载
	(function(){
		function downLoad(btn,con){
			var timer=null;
		
			$(btn).hover(function(){
				clearTimeout(timer);
				$(con).show();
			},function(){
				timer=setTimeout(function(){
					$(con).hide();
				},300);
			});
			
			$(con).hover(function(){
				clearTimeout(timer);
				$(con).show();
			},function(){
				timer=setTimeout(function(){
					$(con).hide();
				},300);
			});	
		};
		downLoad('#client_btn','.header .client_download');
		downLoad('.small_login_warp .client','.float_nav_con .client_download');
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
		
		$('#side_bar .top').click(function(){
			$('body,html').animate({scrollTop:0},300);
		});
	})();
	
	//搜索框
	(function(){
		var defaultSearch = $('#so_wd1').val();
		function fnSearch(parent, hoverPos, defaultPos, bdColor, defaultBdColor){
			if(parent == '.index_search'){
				var left = '10px';
			}else if(parent == '.small_search'){
				var left = '0';
			}
			$(parent+' input[type=text]').focus(function(){
				if($(this).val() == defaultSearch){
					$(this).val('');
				}
				$(parent).css('border-color',bdColor);
				$(this).next().css('background-position',left+' '+hoverPos);	
			});	
			
			$(parent+' input[type=text]').blur(function(){
				if($(this).val() == ''){
					$(this).val(defaultSearch);
				}
				$(parent).css('border-color',defaultBdColor);
				$(this).next().css('background-position',left+' '+defaultPos);	
			});		
		};
		fnSearch('.index_search', '-132px', '-66px', '#0099ff', '#0099ff');
		fnSearch('.small_search', '-99px' , '-66px', '#fff', '#0099ff');
	})();
	
	
	//替换导航
/*	$('ul li a').each(function(){
		if($(this).text()=="网络展馆"){
			$(this).attr('href','http://www.expoon.com/quanjing/'); 
			$(this).text("全景展示");
		}
		if($(this).text()=="网络博览会"){
			$(this).attr('href','http://www.expoon.com/activity/'); 
			$(this).text("全景专题");
		}
		if($(this).text()=="闪展"){
			$(this).attr('href','http://www.expoon.com/find/'); 
			$(this).text("全景发现");
		}
});*/
	
})