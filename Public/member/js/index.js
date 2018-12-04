var nowchannel;

var winopen = true;
$(document).ready(function() {
    setWin();
    
    $(".cube_parent").click(function() {
        var len = $(this).parent().prevAll().length;
        changeChannel(len);
    });
    
    $("#onoffbtn").click(function() {
        if (winopen == true) {
            $(".cube_child").hide();
            $(".icontitle").hide();
            $("#arrowtext").hide();
            $("#leftbox").css("width", "60px");
            $("#arrow").css("background-position","0 -20px");
            winopen = false;
        } else {
            changeChannel(nowchannel);
        }
    });
});
function makemenu(){
    for(k=0;k<openchannel.length;k++){
        $(".cube_child").eq(openchannel[k]).show();
    }
}

function setWin() {
    var $width = $(window).width();
    var $height = $(window).height();
    $("#leftbox").css("height", ($height - 50) + "px");
    $(".rightbox").css("height", ($height - 50) + "px");    
}
$(window).resize(function() {
    setWin();
});

function changeChannel(n) {
    if (winopen == false) {
        $("#leftbox").css("width", "165px");
        $(".icontitle").show();
        $("#arrowtext").show();
        $("#arrow").css("background-position","0 0");
        winopen = true;
    }
    
    var en = $(".cube_child").eq(n).css("display");
    if(en == "block"){
        $(".cube_child").eq(n).stop().slideUp(200);
    }else{
        $(".cube_child").eq(n).stop().slideDown(200);
    }
}