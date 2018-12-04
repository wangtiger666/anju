$(document).ready(function(){
	$("#yunReg,#divyunReg").click(function() { $("#regdiv").show();$("#logindiv").hide(); $("#yunbody").addClass("modal-open");});
	$("#yunLogin,#divyunLogin").click(function() { $("#regdiv").hide();$("#logindiv").show(); $("#yunbody").addClass("modal-open");});
	$("#closeLogindiv").click(function() {$("#logindiv").hide(); $("#yunbody").removeClass("modal-open"); });
	$("#closeRegdiv").click(function() {$("#regdiv").hide(); $("#yunbody").removeClass("modal-open"); });
});
$('#subLogin').click(function() {
	var userid = $.trim($("#userid").val());
	var pwd = $.trim($("#pwd").val());
	var remember = 0;
	if($("#remember").prop("checked")) remember=1;

	$("#showmsg").html('');
	if(userid==""){
		$("#showmsg").html("请填写用户名");
		return false;
	}
	if(pwd=="")
	{
		$("#showmsg").html("请填写登录密码");
		return false;
	}
	$.ajax({
		type:"POST",
		url:"/index.php?s=/User/Login",
		data: {userid:userid,pwd:pwd,remember:remember,dopost:'login'},
		dataType:'json',
		success:function(data){
			if (data.status==1){
				window.location.href=window.location.href;
			}else{
				$('#showmsg').html(data.info);
			}
		}
	});
});
$('#subReg').click(function() {
	var reg_userid = $.trim($("#reg_userid").val());
	var reg_pwd = $.trim($("#reg_pwd").val());
	var reg_nickname = $.trim($("#reg_nickname").val());
	var reg_verify = $.trim($("#reg_verify").val());

	$("#reg_showmsg").html('');
	if(reg_userid==""){
		$("#reg_showmsg").html("请填写用户名");
		return false;
	}
	if(reg_pwd=="")
	{
		$("#reg_showmsg").html("请填写密码");
		return false;
	}
	if(reg_nickname=="")
	{
		$("#reg_showmsg").html("请填写昵称");
		return false;
	}
	if(reg_verify=="")
	{
		$("#reg_showmsg").html("请填写验证码");
		return false;
	}
	$.ajax({
		type:"POST",
		url:"/index.php?s=/User/Reg",
		data: {reg_userid:reg_userid,reg_pwd:reg_pwd,reg_nickname:reg_nickname,reg_verify:reg_verify,dopost:'reg'},
		dataType:'json',
		success:function(data){
			if (data.status==1){
				window.location.href=window.location.href;
			}else{
				$('#reg_showmsg').html(data.info);
			}
		}
	});
});
