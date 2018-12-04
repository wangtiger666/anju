$(function () {

   /**
    * [服务协议]
    */
   $(".protocol").on("click",function(){
      $(".sevice").show()
   })

   $(".closeserver").on("click",function(){
      $(".sevice").hide()
   })

    /**
     * [登录]
     */
   	$("#logbtn").on("click",function(){
   		$("#loginbox").show()
   		$(".loginshow").show()
   		$(".chitloginshow").hide()
   		$(".reginshow").hide()
   		$(".logichitnshow").hide();
   		$("#log_phone").val('');
   		$("#log_pwd").val('');
   	})

   	/**
     * [注册]
     */
   	$("#regbtn").on("click",function(){
   		$("#loginbox").show()
   		$(".loginshow").hide()
   		$(".chitloginshow").hide()
   		$(".reginshow").show()
   		$(".logichitnshow").hide();
   		$("#regphone").val('');
   		$("#reg_chitcode").val('');
   		$("#reg_pwd").val('');
   	})

   	/**
     * [登录/注册/找回密码/短信登录页面切换]
     */
   	$(".forgetpwd").on("click",function(){
   		$(".loginshow").hide();
   		$(".logichitnshow").hide();
   		$(".chitloginshow").show();
   		$("#chitphone").val('');
   		$("#chitcode").val('');
   	})
   	$(".returnlog").on("click",function(){
   		$(".loginshow").show();
   		$(".chitloginshow").hide();
   		$(".logichitnshow").hide();
   		$(".reginshow").hide();
   		$("#log_phone").val('');
   		$("#log_pwd").val('');
   	})
   	$(".pwdlog").on("click",function(){
   		$(".logichitnshow").hide();
   		$(".chitloginshow").hide();
   		$(".loginshow").show();
   		$("#log_phone").val('');
   		$("#log_pwd").val('');
   	})
   	$(".chitlog").on("click",function(){
   		$(".loginshow").hide();
   		$(".chitloginshow").hide();
   		$(".logichitnshow").show();
   		$("#log_chitphone").val('');
   		$("#log_chitcode").val('');
   	})
   	$(".freereg").on("click",function(){
   		$(".loginshow").hide();
   		$(".logichitnshow").hide();
   		$(".reginshow").show();
   		$("#regphone").val('');
   		$("#reg_chitcode").val('');
   		$("#reg_pwd").val('');
   	})

   	/**
     * [关闭登录页面]
     */
    $(".closelog").on("click",function(){
    	$("#loginbox").hide()
    })
});