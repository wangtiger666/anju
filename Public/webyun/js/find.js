$(document).ready(function(){
  $(".MobileNav_l_3-E4u4").click(function(){
    $(".MobileNav_l_3-E4u4").toggleClass("MobileNav_active_3b1S6a");
	$(".MobileNav_menu1_3j5a8X").children(".MobileNav_anim_3zUUfh").toggleClass("MobileNav_menu1move_1Oueah");
  });
});

$(document).ready(function(){
  $(".MobileNav_r_PREDCd").click(function(){
    $(".MobileNav_r_PREDCd").toggleClass("MobileNav_active_3b1S6a");
	$(".MobileNav_menu2_1EeQyl").children(".MobileNav_anim_3zUUfh").toggleClass("MobileNav_menu1move_1Oueah");
  });
});



$(document).ready(function(){ 
	$("#faxian").hover(function () {
    $(this).addClass("Select_active_1oRcUU");
    $(this).removeClass("");
}, function () {
    $(this).addClass("");
    $(this).removeClass("Select_active_1oRcUU");
}); 
});


$(document).ready(function(){ 
	$("#zuozhe").hover(function () {
    $(this).addClass("Select_active_1oRcUU");
    $(this).removeClass("");
}, function () {
    $(this).addClass("");
    $(this).removeClass("Select_active_1oRcUU");
}); 
});


$(document).ready(function(){ 
	$("#listpanok").hover(function () {
    $(this).addClass("Select_active_1oRcUU");
    $(this).removeClass("");
}, function () {
    $(this).addClass("");
    $(this).removeClass("Select_active_1oRcUU");
}); 
});


$(document).ready(function(){ 
	$("#pingdaoxiala").hover(function () {
    $(this).addClass("Select_active_1oRcUU");
    $(this).removeClass("");
}, function () {
    $(this).addClass("");
    $(this).removeClass("Select_active_1oRcUU");
}); 
});


$(document).ready(function(){
  $("#pingdaoxialam").click(function(){
    $("#pingdaoxialam").toggleClass("Select_active_1oRcUU");
  });
});


$(document).ready(function(){
	$("#um").mouseover(function(){
		$(".ui-login-status").show();
	});
	$("#um").mouseout(function(){
		$(".ui-login-status").hide();
	});
});



$(document).ready(function(){ 
	$("#authork").hover(function () {
    $(this).addClass("Select_active_1oRcUU");
    $(this).removeClass("");
}, function () {
    $(this).addClass("");
    $(this).removeClass("Select_active_1oRcUU");
}); 
});

$(document).ready(function(){ 
	$("#info").hover(function () {
    $(this).addClass("Select_active_1oRcUU");
    $(this).removeClass("");
}, function () {
    $(this).addClass("");
    $(this).removeClass("Select_active_1oRcUU");
}); 
});

$(document).ready(function(){ 
	$("#infolin").hover(function () {
    $(this).addClass("Select_active_1oRcUU");
    $(this).removeClass("");
}, function () {
    $(this).addClass("");
    $(this).removeClass("Select_active_1oRcUU");
}); 
});


$(document).ready(function(){
  $("#authorkm").click(function(){
    $("#authorkm").toggleClass("Select_active_1oRcUU");
  });
});


$(document).ready(function(){ 
	closeBg();
});

function showBg() {  
	$(".ReactModalPortal").show();}//关闭灰色 jQuery 遮罩
	function closeBg() {
		$(".ReactModalPortal").hide();
	}


