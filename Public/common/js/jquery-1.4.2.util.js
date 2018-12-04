/*
jquery-util v1.0.0
Release Date: 2011-03-21

Copyright (c) 2011 Lywww.com
*/

if ($) 
{
	$.extend(
	{
		/* 等比例缩小已上传的图片 */
		imageAuto: function(imageId,imageUrl,width,height,removeSrc)
		{
			var newImg = $('#'+imageId);
			if( newImg && newImg.attr('src')!='' ) 
			{
				//重设大小 需要先删除对象现有属性
				newImg.css('visibility','hidden');  //隐藏图片
				newImg.removeAttr('width');
				newImg.removeAttr('height');

				if(removeSrc) newImg.removeAttr('src');
				newImg.attr('src',imageUrl);	//重新加载该图片
				newImg.bind('load', function() {
					while(newImg.attr('width')>width || newImg.attr('height')>height)
					{
						newImg.attr('width', newImg.attr('width') - Math.floor( newImg.attr('width') / 10 ) );
						newImg.attr('height', newImg.attr('height') - Math.floor( newImg.attr('height') / 10 ) );
					}
				});

				newImg.css('visibility','visible');  //显示该图片
			}
		},
		/* 产生随机数 */
		random: function(end) 
		{
			if(!end) end = 1000;
			return Math.floor( Math.random() * end );
		},
		/* 查看对象结构 */
		dump: function(object)
		{
			var msg = '';
			try{
			   //在此运行代码
				for(var attr in object){
					msg += attr + ' = ' + object[attr] + '<br />';
				}
			}catch(err){
			   return err;
			}
			return msg;
		},
		/* 复制网页链接 */
		copyWebUr: function(url)
		{
			try{
				clipboardData.setData('Text', url);
				alert('复制完成!');
			}catch(e){
				alert("您的浏览器设置为不允许复制！\n如果需要此操作，请在浏览器地址栏输入'about:config'并回车\n然后将'signed.applets.codebase_principal_support'设置为'true',再重试复制操作!");
			}
		},
		/* 网页链接加入收藏夹 */
		addFavorite: function(url,name)
		{
			try{
				if (document.all)
				{
					window.external.addFavorite(url,name);
				}
				else if (window.sidebar)
				{
					window.sidebar.addPanel(name, url, "");
				}
			}
			catch(e)
			{
				alert("加入收藏失败，请使用Ctrl+D进行添加");
			}
		},
		/* 取得一个日期的 Unix 时间戳 */
		mktime: function(year,month,day,hour,minute,second)
		{
			if(!year || !month || !day) return false;
			if(!hour) hour = '1';
			if(!minute) minute = '0';
			if(!second) second = '0';
			return Date.parse(year+'/'+month+'/'+day+' '+hour+':'+minute+':'+second)/1000;
		},

		/* 返回当前时间距 1970 年 1 月 1 日之间的秒数。 */
		time: function()
		{
			return new Date().getTime()/1000;
		},

		/* 格式化显示时间 */
		date: function(format,time)
		{
			var date	= new Date();
			var format	= format?format:'Y-m-d H:i:s';
			if(time) date.setTime(time*1000);
			format = format.replace('Y',date.getFullYear());
			format = format.replace('m',date.getMonth()+1);
			format = format.replace('d',date.getDate());
			format = format.replace('H',date.getHours());
			format = format.replace('i',date.getMinutes());
			format = format.replace('s',date.getSeconds());
			
			return format;
		},

		/* 去除字符串中的单引号双引号  全角半角 */
		stripBracket: function(str)
		{
			return str.replace(/[\'\"\‘]/g, '');
		},

		/*
		* input 要统计的 Input id
		* countfield	要更新的 对象id
		* maxlimit		允许最大的字节数
		* countType		指示是递增(+)还是递减(-)	默认 - 
		*
		* 文本计数器 
		*/
		textCounter: function(input,countfield,maxlimit,countType)
		{ 
			var countType	= countType?countType:'-';
			var input =  typeof(input)=='object'?input:$('#'+input);;
			var countfield = typeof(countfield)=='object'?countfield:$('#'+countfield);;

			if(input.val().length > maxlimit)
			{
				input.val( input.val().substring(0,maxlimit) );
				if(countType=='-')
				{
					countfield.html('0');
				}			
			}
			else
			{
				if(countType=='-')
				{
					countfield.html( maxlimit - input.val().length );
				}
				else
				{
					countfield.html( input.val().length );
				}
			}
		},

		/* 页面重新载入函数 */
		reLoad: function(){window.location.reload();},

		/* 页面重定向 */
		redirect: function(url){window.location = url;},

		/* 打开新页面 */
		open: function(url){window.open(url);},

		/*
		* checkbox 全选和取消全选
		* checkboxName : name属性
		* op		   : 布尔值 true or false
		*/
		checkAll: function(checkboxName,op)
		{
			$("input[name='"+checkboxName+"']").attr("checked", op);
		},


		/* 返回或设置目标select 的当前选择的option对象 */
		select: function(selectId,value)
		{
			if(value)
			{
				$('#'+selectId).children("[value='"+value+"']").attr("selected", true);
			}
			else
			{
				return $('#'+selectId).children(":selected");
			}
		},

		/* 返回或设置目标 radio 的当前选择的对象 */
		radio: function(radioNames,value)
		{
			if(value)
			{
				$("input:radio[name='"+radioNames+"'][value='"+value+"']").attr("checked", true);
			}
			else
			{
				return $("input:radio[name='"+radioNames+"']:checked");
			}
		},


		/*
		* 批量提交表单 bulkSubmit
		*
		* checbox_name		checkbox 的 name 属性
		* actionName		动作名称(用作显示) 默认为删除
		* itemName			对象单位(用作显示) 默认为记录
		* diyMessage		自定义消息(用作显示) 其中[n]会自动替换掉数量
		*/

		bulkSubmit: function(checbox_name,actionName,itemName,diyMessage,searchName)
		{
			if(actionName==undefined || actionName=='') actionName = '操作';
			if(itemName==undefined || itemName=='') itemName = '记录';
			if(searchName==undefined) searchName = true; //默认按name 搜索 其他情况 按 class 搜索

			var selected = 0;
			var ids = searchName?$("input[name='"+checbox_name+"']"):$('.'+checbox_name);
			//如果 checkbox 数量 >1 则变量 ids 是一个对象数组
			if(ids.length) {
				var selected = searchName?$("input[name='"+checbox_name+"']:checked"):$('.'+checbox_name).filter(":checked");
				if(selected.length==0){
					alert('请选择要'+actionName+'的'+itemName+'!');
					return false;
				}else{
					var msg = diyMessage?diyMessage.replace('[n]',selected.length):actionName+'选择的['+selected.length+']个'+itemName+'?';
					if(confirm(msg)){
						//表单对象
						return true;
					}
					return false;
				}
			}
			else
			{
				alert('没有可'+actionName+'的'+itemName+'!');
				return false;
			}
		},


		/* 常用正则表达式 */
		validator: {
			Account : /^[a-zA-Z0-9]+[A-Za-z0-9\-\._]*$/,
			Require : /.+/,
			Email : /^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/,
			Phone : /^(((((\(\d{3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7})|([1-9]{1}[0-9]{10})){1}[,，\s]{0,1})+$/,
			Mobile : /^([1-9]{1}[0-9]{10}[,]{0,1})+$/,
			Url : /^http:\/\/[A-Za-z0-9]+\.[A-Za-z0-9]+[\/=\?%\-&_~`@[\]\':+!]*([^<>\"\"])*$/,
			IdCard : /^\d{15}(\d{2}[A-Za-z0-9])?$/,
			Currency : /^\d+(\.\d+)?$/,
			Number : /^\d+$/,
			Zip : /^[1-9]\d{5}$/,
			QQ : /^[1-9]\d{4,}$/,
			Integer : /^[-\+]?\d+$/,
			Double : /^[-\+]?\d+(\.\d+)?$/,
			English : /^[A-Za-z]+$/,
			Chinese : /^[\u0391-\uFFE5]+$/,
			UnSafe : /^(([A-Z]*|[a-z]*|\d*|[-_\~!@#\$%\^&\*\.\(\)\[\]\{\}<>\?\\\/\'\"]*)|.{0,5})$|\s/
		},

		/* 正则表达式匹配 */
		regexp: function(regexp,str){
			for(var v in this.validator){
				if(regexp == v){
					return this.validator[v].test(str);
				}
			}
			return new RegExp(regexp).test(str);
		}
	});
}