(function(){
	var isWX,krpano,nowScene,comments,code,openid,initializing,openAnyCom,openWXCom,isAuth,headImg,serverDomain,altNum,altRun,errorLog,optionForm
		,addGui,alertForm,state,headState,nowPage,isMobile,idIndex,openVerifyCode,needInit,rate;

	function preInitHC(){
		initializing = 1;
		if(configHC.appid == "") configHC.appid = "";
		serverDomain = configHC.serverDomain;
		comments = {};
		altNum = 0;
		needInit = 0;
		altRun = false;
		headState = false;
		nowPage = 0;
		idIndex = 0;
		isWX = window.navigator.userAgent.toLowerCase().match(/MicroMessenger/i) == 'micromessenger';
		code = getParam("code");
		openid = getParam("openid");
		state = getParam("state");
		openVerifyCode = true;
		rate = window.devicePixelRatio;
		if(window.navigator.userAgent.toLowerCase().indexOf("applewebkit/") != -1){
			var ver = window.navigator.userAgent.toLowerCase();
			ver = ver.substr(ver.indexOf("applewebkit/") + 12, 3);
			ver = parseInt(ver);
			if(!isNaN(ver) && ver > 536) rate = 1;
		}
		if(isWX && !code && !openid){
			var reUrl = escape(window.location.href);
			window.location.href = "https://open.weixin.qq.com/connect/oauth2/authorize?appid="+configHC.appid+"&redirect_uri="+reUrl+"&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
			return;
		}
		waitInit();
	}
	window.preInitHC = preInitHC;
	
	function hideAddGui(notCallKr){
		if(notCallKr != false) krpano.call("hideAddGui(1)");
		if(addGui) $(addGui).hide();
	}
	window.hideAddGui = hideAddGui;
	
	function showAddGui(notCallKr){
		hideAlert();
		hideOptionForm();
		if(!notCallKr) krpano.call("showAddGui(1)");
		if(isMobile) krpano.call("skin_hideskin()");
		if(isWX){
			if(openWXCom == 1){
				if(isAuth){
					if(!addGui) createAddGui();
					else $(addGui).show();
				}else{
					showOptionForm("需要您的微信授权才可以发表评论,是否进行授权?", function(){
						var reUrl = window.location.href.replace(/code=[a-z0-9]*/gi,'');
						reUrl = reUrl.replace(/state=[a-z0-9]*/gi,'');
						reUrl = reUrl.replace(/[?][&]*/gi,'?');
						reUrl = reUrl.replace(/[?]$/gi,'');
						reUrl = escape(reUrl);
						window.location.href = "https://open.weixin.qq.com/connect/oauth2/authorize?appid="+configHC.appid+"&redirect_uri="+reUrl+"&response_type=code&scope=snsapi_userinfo&state=2#wechat_redirect";
					}, null, "授权");
				}
			}else if(openAnyCom == 1){
				if(!addGui) createAddGui();
				else $(addGui).show();
			}
		}else{
			if(openAnyCom == 1){
				if(!addGui) createAddGui();
				else $(addGui).show();
			}else{
				showAlert("匿名评论功能被关闭了,请使用微信进入网页评论");
			}
		}
	}
	window.showAddGui = showAddGui;
	
	function refreshCode(){
		//addGui.codeImg.src = serverDomain+"/code.php";
		//$("#inputCode")[0].value = "";
	}
	
	function switchScene(sceneName){
		if(sceneName != nowScene) nowPage = 0;
		nowScene = sceneName;
		loadComment(sceneName);
	}
	window.switchScene = switchScene;
	
	function loadComment(sceneName){
		var thisPage = nowPage;
		if(initializing != 0) return;
		if(!showGui) return;
		if(sceneName != nowScene) return;
		krpano.call("hideComments(1)");
		if(comments[sceneName] == null) comments[sceneName] = [];
		if(comments[sceneName].maxPage == null){
			if(!comments[sceneName].initing){
				comments[sceneName].initing = true;
				tempFun = function(){
					var params = { id:configHC.id, fn:sceneName };
					$.ajax({
						type:"get",
						url:serverDomain+"/my.php?s=/Member/Panoapi/readComment",
						timeout: 10000,
						data: params,
						//dataType:'json',
						dataType:'jsonp',
						jsonp: "readComment",
						jsonpCallback:"success_readComment",
						success: function(data){
							if(!data.error){
								comments[sceneName].maxPage = Math.ceil(data.count / configHC.pageMaxShow) - 1;
								loadComment(sceneName);
								return;
							}else{
								errorLog = data.error;
								setTimeout(tempFun, 300);
							}
						},
						error: function(xml, error){
							errorLog = error;
							setTimeout(tempFun, 300);
						}
					});
				}
				tempFun();
			}
		}else{
			if(nowPage > comments[sceneName].maxPage){
				nowPage = comments[sceneName].maxPage;
				loadComment(sceneName);
				return;
			}else if(nowPage == comments[sceneName].maxPage){
				krpano.call("set(layer[hcRight].visible, false)");
			}else{
				krpano.call("set(layer[hcRight].visible, true)");
			}
			if(nowPage <= 0) krpano.call("set(layer[hcLeft].visible, false)");
			else krpano.call("set(layer[hcLeft].visible, true)");
			
			if(comments[sceneName][thisPage] == null) comments[sceneName][thisPage] = [];
			if(!comments[sceneName][thisPage].initOver){
				if(!comments[sceneName][thisPage].initing){
					comments[sceneName][thisPage].initing = true;
					tempFun = function(){
						var params = { id:configHC.id, fn:sceneName, ps: thisPage * configHC.pageMaxShow, e: configHC.pageMaxShow };
						$.ajax({
							type:"get",
							url:serverDomain+"/my.php?s=/Member/Panoapi/readComment",
							timeout: 10000,
							data: params,
							//dataType:'json',
							dataType:'jsonp',
							jsonp: "readCommentSE",
							jsonpCallback:"success_readCommentSE",
							success: function(data){
								if(!data.error){
									comments[sceneName][thisPage] = data.comments;
									comments[sceneName][thisPage].initOver = true;
									loadComment(sceneName);
									return;
								}else{
									errorLog = data.error;
									setTimeout(tempFun, 300);
								}
							},
							error: function(xml, error){
								errorLog = error;
								setTimeout(tempFun, 300);
							}
						});
					}
					tempFun();
				}
			}else{
				//idIndex = 0;
				for(var i = 0; i < comments[sceneName][thisPage].length; i++){
					addComment(comments[sceneName][thisPage][i]);
				}
			}
		}
	}
	window.loadComment = loadComment;
	
	function CommentLeft(){
		if(nowPage <= 0) {
			showAlert("当前评论已是最前页");
		}else{
			nowPage--;
			loadComment(nowScene);
		}
	}
	window.CommentLeft = CommentLeft;

	function CommentRight(){
		nowPage++;
		loadComment(nowScene);
	}
	window.CommentRight = CommentRight;

	function sendComment(){
		var thisScene = nowScene;
		var thisPage = nowPage;
		var params = { id:configHC.id, name:thisScene, ath:nowAth, atv:nowAtv, text:$("#inputText")[0].value, code:$("#inputCode")[0].value };
		if(headImg) params.openid = openid;		
		$.ajax({
			type:"get",
			url:serverDomain+"/my.php?s=/Member/Panoapi/saveComment",
			timeout: 5000,
			data: params,
			dataType:'jsonp',
			jsonp: "saveComment",
			jsonpCallback:"success_saveComment",
			success: function(data){
				if(!data.error){
					addComment(data);
					hideAddGui();
					altReplace();
					$("#inputText")[0].value = "";
					if(comments[thisScene] == null) comments[thisScene] = [];
					if(comments[thisScene][thisPage] == null) comments[thisScene][thisPage] = [];
					comments[thisScene][thisPage].push(data);
				}else{
					showAlert("评论出错:" + data.error);
				}
				refreshCode();
			},
			error: function(xml, error){
				showAlert("评论出错:" + error);
			}
		});
	}
	
	function waitInit(){
		if(!krpano){
			krpano = document.getElementById("krpanoSWFObject");
			setTimeout(waitInit, 100);
			return;
		}
		if(!window.$){
			setTimeout(waitInit, 100);
			return;
		}
		krpano.call("set(addGui_BackColor, "+configHC.addGui_BackColor+")");
		$(window).resize(onresize);
		$(window).ready(onresize);
		init();
	}
	
	function init(){
		var params = { id:configHC.id };
		if(code) params.code = code;
		if(state) params.state = state;		
		$.ajax({
			type:"get",
			url:serverDomain+"/my.php?s=/Member/Panoapi/commentInit",
			timeout: 5000,
			data: params,
			dataType:'jsonp',
			jsonp: "commentInit",
			jsonpCallback:"success_commentInit",
			success: function(data){
				if(!data.error){
					openAnyCom = data.toggle.openAnyCom == 1;
					openWXCom = data.toggle.openWXCom == 1;
					openVerifyCode = data.toggle.openVerifyCode == 1;
					if(data.openid){
						openid = data.openid;
						isAuth = data.auth;
						if(isAuth) headImg = data.url;
					}
					initializing = 0;
				}else{
					errorLog = data.error;
					initializing = -1;
				}
				initOver();
			},
			error: function(xml, error){
				errorLog = error;
				initializing = -1;
				initOver();
			}
		});
	}
	
	var tryCount = 1;
	function initOver(){
		if(initializing == 0){
			krpano.call("set(layer[hcMainGui].visible,true)");
			krpano.call("set(layer[toggle_gui].visible,true)");
			if(nowScene != null) loadComment(nowScene);
		}else if(initializing == -1){
			if(errorLog == "param code error"){
				showAlert("Code有误, 正在刷新");
				var reUrl = window.location.href.replace(/code=[a-z0-9]*/gi,'');
				reUrl = reUrl.replace(/state=[a-z0-9]*/gi,'');
				reUrl = reUrl.replace(/[?][&]*/gi,'?');
				reUrl = reUrl.replace(/[?]$/gi,'');
				window.location.href = reUrl;
			}else if(errorLog == "timeout"){
				if(tryCount >= 4){
					showAlert("评论初始化超时, 以放弃重试");
				}else{
					showAlert("评论初始化超时,可能是由于网络不稳定引起,正在进行第"+tryCount+"次重试");
					tryCount++;
					init();
				}
			}else{
				showAlert("评论功能初始化错误: " + errorLog);
			}
		}else{
			showAlert("评论功能初始化时遇到了未知错误");
		}
	}
	
	function addComment(comment){
		if(comment.fvName != nowScene) return;
		comment.text = comment.text.replace('&', "&#38;");
		comment.text = comment.text.replace('#', "&#35;");
		comment.text = comment.text.replace('"', "&#34;");
		comment.text = comment.text.replace("'", "&#39;");
		comment.text = comment.text.replace('(', "&#40;");
		comment.text = comment.text.replace(')', "&#41;");
		comment.text = comment.text.replace('<', "&#60;");
		comment.text = comment.text.replace('>', "&#62;");
		comment.text = comment.text.replace('%', "&#37;");
		if(comment.avatar == "%SWFPATH%/hotspotComment/avatar.png"){
			krpano.call("addComment("+idIndex+","+comment.ath+","+comment.atv+","+comment.text+","+comment.avatar+")");
		}else{
			krpano.call("addComment("+idIndex+","+comment.ath+","+comment.atv+","+comment.text+",%SWFPATH%/hotspotComment/avatar_alt.png?"+comment.avatar+")");
		}
		needInit++;
		idIndex++;
		altReplace();
	}
	
	function altReplace(){
		if(altRun) return;
		if(needInit <= 0) return;
		altRun = true;
		$("div").each(function(i,e){
			if(needInit > 0 && e.style.backgroundImage.indexOf("hotspotComment/avatar") != -1 && e.style.backgroundImage.indexOf("avatar.png?headimg") == -1){
				if(!e.isInit && e.parentNode.children.length >= 2 && e.parentNode.children[1].children.length >= 1 && e.parentNode.children[1].children[0].children.length >= 1){
					if(e.style.backgroundImage.indexOf("hotspotComment/avatar_alt.png") != -1){
						var tempStr = e.style.backgroundImage.substr(e.style.backgroundImage.indexOf("?") + 1);
						if(tempStr.substr(tempStr.length - 2, 2) == "\")") tempStr = "url(\"" + tempStr;
						else tempStr = "url(" + tempStr;
						e.style.backgroundImage = tempStr;
					}
					e.style.borderRadius = "5px";
					e.style.border = "1px solid rgba(0, 0, 0, 0.35)";
					e.isInit = true;
					e.parentNode.children[1].children[0].children[0].style.border = "1px solid rgba(0, 0, 0, 0)";
					needInit--;
				}
			}
		});
		if(needInit > 0) setTimeout(altReplace, 100);
		altRun = false;
	}
	
	function headReplace(){
		if(headState) return;
		headState = true;
		var flag1 = false;
		var flag2 = false;
		$("div").each(function(i,e){
			if(e.style.backgroundImage.indexOf("hotspotComment/avatar.png?headimg") != -1){
				if(isWX && headImg) e.style.backgroundImage = "url(" + headImg + ")";
				e.style.borderRadius = "5px";
				e.style.border = "1px solid rgba(0, 0, 0, 0.1)";
				e.parentNode.children[1].children[0].children[0].style.border = "1px solid rgba(0, 0, 0, 0)";
				flag1 = true;
				return;
			}
		});
		$("div").each(function(i,e){
			if(e.style.backgroundImage.indexOf("hotspotComment/transparent.png?zindex") != -1){
				e.parentNode.style.zIndex = "3000";
				flag2 = true;
				return;
			}
		});
		if(!(flag1 && flag2)) setTimeout(headReplace, 500);
		headState = false;
	}
	
	function createAddGui(){
		addGui = document.createElement("div");
		document.getElementsByTagName("body")[0].appendChild(addGui);
		if(!isMobile) addGui.id = "addGui";
		else{
			addGui.id = "addGui_m";
			$(addGui).width($(addGui).width() - 40);
			addGui.style.fontSize = 16 * rate * 0.75 + "px";
		}

		var openVerifyCode = false;
		var codeImg = document.createElement("img");
		codeImg.id = "codeImg";
		//codeImg.src = serverDomain+"/code.php";
		codeImg.onclick = refreshCode;
		if(!openVerifyCode) codeImg.style.display = "none";
		addGui.appendChild(codeImg);		
		if(isMobile){
			$(codeImg).width($(codeImg).width() * rate);
			$(codeImg).height($(codeImg).height() * rate);
			codeImg.style.marginBottom = 4 * rate + "px";
		}

		var inputCode = document.createElement("input");
		inputCode.id = "inputCode";
		inputCode.type = "text";
		inputCode.name = "code";
		inputCode.maxLength = "4";
		inputCode.placeholder = "请输入验证码";
		inputCode.onkeydown = function(e){
			//if(e.keyCode == 13)	sendComment();
		};
		if(!openVerifyCode) inputCode.style.display = "none";
		addGui.appendChild(inputCode);
		if(isMobile){
			inputCode.style.lineHeight = $(inputCode).height() * rate + "px";
			$(inputCode).height($(inputCode).height() * rate);
			inputCode.style.fontSize = 16 * rate * 0.75 + "px";
			inputCode.style.padding = (5 * rate) + "px " + (10 * rate) + "px";
			inputCode.style.marginLeft = 4 * rate + "px";
		}
		inputCode.style.width = $(addGui).width() - $(codeImg).width() - 20 * rate - 4 * rate + "px";
		var inputText = document.createElement("input");
		inputText.id = "inputText";
		inputText.type = "text";
		inputText.name = "text";
		inputText.maxLength = "30";
		inputText.placeholder = "广告及恶意留言将封号!限制字数30";
		inputText.onkeydown = function(e){
			//if(e.keyCode == 13)	sendComment();
		};
		addGui.appendChild(inputText);
		if(isMobile){
			inputText.style.lineHeight = $(inputText).height() * rate + "px";
			$(inputText).height($(inputText).height() * rate);
			inputText.style.fontSize = 16 * rate * 0.75 + "px";
		}
		inputText.style.width = $(addGui).width() - 20 + "px";
		var bnA = document.createElement("div");
		bnA.id = "bnConfirm";
		bnA.className = "button";
		bnA.textContent = "发表";

		//bnA.onclick = sendComment;
		bnA.onclick = function(){
			sendComment();
		};

		addGui.appendChild(bnA);
		if(isMobile){
			bnA.style.lineHeight = $(bnA).height() * rate * 0.75 + "px";
			$(bnA).height($(bnA).height() * rate * 0.75);
			bnA.style.marginTop = 6 * rate + "px";
		}
		var bnB = document.createElement("div");
		bnB.id = "bnCancel";
		bnB.className = "button";
		bnB.textContent = "取消";
		bnB.style.marginLeft = $(addGui).width() - ($(addGui).width() * 0.94 + 4) + "px";
		bnB.onclick = hideAddGui;
		addGui.appendChild(bnB);
		if(isMobile){
			bnB.style.lineHeight = $(bnB).height() * rate * 0.75 + "px";
			$(bnB).height($(bnB).height() * rate * 0.75);
			bnB.style.marginTop = 6 * rate + "px";
		}
		
		addGui.codeImg = codeImg;
		addGui.inputCode = inputCode;
		addGui.inputText = inputText;
		addGui.bnA = bnA;
		addGui.bnB = bnB;
		headReplace();
	}
	
	function createAlertForm(){
		alertForm = document.createElement("div");
		document.getElementsByTagName("body")[0].appendChild(alertForm);
		if(!isMobile) alertForm.id = "alertForm";
		else{
			alertForm.id = "alertForm_m";
			$(alertForm).width($(alertForm).width() - 40);
			alertForm.style.fontSize = 16 * rate * 0.8 + "px";
		}
		var text = document.createElement("p");
		text.id = "alertFormText";
		alertForm.appendChild(text);
		if(isMobile){
			text.style.marginBottom = 10 * rate + "px";
			text.style.lineHeight = 22 * rate + "px";
		}
		var bnAlert = document.createElement("div");
		bnAlert.id = "bnAlert";
		bnAlert.className = "button";
		bnAlert.textContent = "确定";
		bnAlert.onclick = hideAlert;
		alertForm.appendChild(bnAlert);
		if(isMobile){
			bnAlert.style.lineHeight = $(bnAlert).height() * rate * 0.75 + "px";
			$(bnAlert).height($(bnAlert).height() * rate * 0.75);
		}
		alertForm.bnAlert = bnAlert;
		alertForm.text = text;
	}
	
	function createOptionForm(){
		optionForm = document.createElement("div");
		document.getElementsByTagName("body")[0].appendChild(optionForm);
		if(!isMobile) optionForm.id = "optionForm";
		else{
			optionForm.id = "optionForm_m";
			$(optionForm).width($(optionForm).width() - 40);
			optionForm.style.fontSize = 16 * rate * 0.8 + "px";
		}
		var text = document.createElement("p");
		text.id = "optionFormText";
		optionForm.appendChild(text);
		if(isMobile){
			text.style.marginBottom = 10 * rate + "px";
			text.style.lineHeight = 22 * rate + "px";
		}
		var bnA = document.createElement("div");
		bnA.id = "bnA";
		bnA.className = "button";
		optionForm.appendChild(bnA);
		if(isMobile){
			bnA.style.lineHeight = $(bnA).height() * rate * 0.75 + "px";
			$(bnA).height($(bnA).height() * rate * 0.75);
		}
		var bnB = document.createElement("div");
		bnB.id = "bnB";
		bnB.className = "button";
		optionForm.appendChild(bnB);
		if(isMobile){
			bnB.style.lineHeight = $(bnB).height() * rate * 0.75 + "px";
			$(bnB).height($(bnB).height() * rate * 0.75);
		}
		
		optionForm.bnA = bnA;
		optionForm.bnB = bnB;
		optionForm.text = text;
	}
	
	function onresize(){
		isMobile = _isMobile();
		if(addGui){
			if(!isMobile) addGui.id = "addGui";
			else addGui.id = "addGui_m";
		}
		if(alertForm){
			if(!isMobile) alertForm.id = "alertForm";
			else alertForm.id = "alertForm_m";
		}
		if(optionForm){
			if(!isMobile) optionForm.id = "optionForm";
			else optionForm.id = "optionForm_m";
		}
		
		if(!isMobile){
			if(addGui){
				addGui.style.width = "";
				addGui.inputText.style.width = $(addGui).width() - 20 + "px";
				addGui.inputCode.style.width = $(addGui).width() - 180 - 4 + "px";
				addGui.bnB.style.marginLeft = $(addGui).width() - ($(addGui).width() * 0.94 + 4) + "px";
			}
			if(alertForm) alertForm.style.width = "";
			if(optionForm) optionForm.style.width = "";
		}else{
			if(addGui){
				addGui.style.width = "";
				$(addGui).width($(addGui).width() - 40);
				addGui.inputText.style.width = $(addGui).width() - 20 + "px";
				inputCode.style.width = $(addGui).width() - $(codeImg).width() - 20 * rate - 4 * rate + "px";
				addGui.bnB.style.marginLeft = $(addGui).width() - ($(addGui).width() * 0.94 + 4) + "px";
			}
			if(alertForm){
				alertForm.style.width = "";
				$(alertForm).width($(alertForm).width() - 40);
			}
			if(optionForm){
				optionForm.style.width = "";
				$(optionForm).width($(optionForm).width() - 40);
			}
		}
	}
	
	function showAlert(str){
		hideAddGui();
		hideOptionForm();
		if(!alertForm) createAlertForm();
		alertForm.text.textContent = str;
		$(alertForm).show();
	}
	
	function hideAlert(){
		if(alertForm) $(alertForm).hide();
	}
	
	function showOptionForm(text, funA, funB, bnAText, bnBText){
		hideAddGui();
		hideAlert();
		if(!optionForm) createOptionForm();
		if(!funA) funA = hideOptionForm;
		if(!funB) funB = hideOptionForm;
		if(!bnAText) bnAText = "确定";
		if(!bnBText) bnBText = "取消";
		optionForm.bnA.onclick = funA;
		optionForm.bnB.onclick = funB;
		optionForm.text.textContent = text;
		optionForm.bnA.textContent = bnAText;
		optionForm.bnB.textContent = bnBText;
		$(optionForm).show();
	}
	
	function hideOptionForm(){
		if(optionForm) $(optionForm).hide();
	}
	
	function loadJs(src){
		var includeNode = document.getElementsByTagName('script')[0];
		var tempInclude = document.createElement('script');
		tempInclude.src = src;
		includeNode.parentNode.insertBefore(tempInclude, includeNode);
	}
	
	function loadCss(src){
		var includeNode = document.getElementsByTagName('script')[0];
		var tempInclude = document.createElement("link");
		tempInclude.rel = "stylesheet";
		tempInclude.type = "text/css";
		tempInclude.href = src;
		includeNode.parentNode.insertBefore(tempInclude, includeNode);
	}
	
	function getParam(name)
	{
		var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
    	var r = window.location.search.substr(1).match(reg);
		if(r != null) return unescape(r[2]);
		return null;
	}
	
	function _isMobile(){
		if(document.body.clientWidth < 400 * rate) return true;
		return false;
	}
	
	function _isMobile_UA(){//android/iPad/ipod(wait remove),mobile(wait add)
		var ua = navigator.userAgent.toLowerCase();
		var mobileUa = ["Android", "iPhone", "SymbianOS", "Windows Phone", "iPad", "iPod"];
		for(var i = 0; i < mobileUa.length; i++)
			if(ua.indexOf(mobileUa[i].toLowerCase()) != -1) return true;
		return false;
	}
}());
preInitHC();