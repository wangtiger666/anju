var cheakWindow = function(){
    cheakWindowOnes();
    $(window).resize(function(){
        cheakWindowOnes();
    });
}
var cheakWindowOnes = function(){
    var mTop = 60 + 34;
    var windowHeight = $(window).height();
    var viewHeight = windowHeight - mTop;
    $(".viewport").css("height",viewHeight + "px");
    $(".main").css("height",viewHeight + "px");
    $('#menu').tinyscrollbar();
}

var menuListView = function(){
    var len = $(".nemulist ul li").length;
    if(len%2==0){
        $(".nemulist ul li").eq(len-1).css("height","37px");
        $(".nemulist ul li").eq(len-2).css("height","37px");
    }else{
        $(".nemulist ul li").eq(len-1).css("height","37px");
    }
}

function menuShow(num){
    var len = $(".menubox").children(".cube").length;
    for(i=0;i<len;i++){
        $(".menubox").eq(num).children(".cube").eq(i).stop().css("margin-left", (-250-250*i*i) +"px");
    }
    $(".menubox").hide();
    $(".menubox").eq(num).show();
    $(".menubox").eq(num).children(".cube").stop().animate({marginLeft : "0px"},400);
}

$(document).ready(function(){
    $(".menubox").first().show();
    $(".menubox .cube .title").click(function(){
        if($(this).parent(".cube").children(".body").css("display") == "none"){
            $(this).parent(".cube").children(".body").show();
        }else{
            $(this).parent(".cube").children(".body").hide();
        }
    });
    
    $(".nemulist ul li").click(function(){
        var num = $(this).prevAll().length;
        menuShow(num);
    });
});