function boluo_autocheckui(){
    var len = $(".boluo_checkbox").length;
    for (i = 0; i < len; i++) {
        if ($(".boluo_checkbox").eq(i).children("input").val() > 0) {
            $(".boluo_checkbox").eq(i).addClass("checked");
        }
    }
    $(".boluo_checkbox").click(function() {
        var lock = $(this).children("input").data("lock");
        if(!lock){
            if($(this).children("input").val() == 0){
                $(this).children("input").val(1);
                $(this).addClass("checked");
            }else{
                $(this).children("input").val(0);
                $(this).removeClass("checked");
            }
        }
    });
}
function boluo_closecheckui(me){
    $(me).val(0);
    $(me).parent().removeClass("checked");
}
function boluo_checkui(me){
    $(me).val(1);
    $(me).parent().addClass("checked");
}
function boluo_lockcheckui(me){
    $(me).data("lock","true");
}
function boluo_unlockcheckui(me){
    $(me).removeData("lock");
}


function boluo_showloadbar(title){
    boluo_hideloadbar();
    var html = '';
    var winw = $(window).width();
    var winh = $(window).height();

    var mtop = (winh - 22 - 2)*(3/7);
    var mleft = (winw - 300 - 2)/2;

    html += '<div id="boluoloadbar" style="margin-left: '+mleft+'px;margin-top: '+mtop+'px;">';
    html += '<div class="boluoloadtext">'+title+'</div>';
    html += '<div class="boluoloadouter">';
    html += '<div class="boluoloadbox">'+'</div>';
    html += '</div>';
    html += '</div>';

    $("body").prepend(html);
}

function boluo_loadbar(num,total){
    k = 300*num/total;
    if(num<total){
        $(".boluoloadbox").stop().animate({
            "width": k+"px"
        }, 500);
    }else{
        $(".boluoloadbox").stop().animate({
            "width": "300px"
        }, 500,function(){
            boluo_hideloadbar();
        });
    }
}

function boluo_hideloadbar(){
    $("#boluoloadbar").remove();
}

function boluo_loadding(title){
    boluo_showloadbar(title);
    k = 300;
    $(".boluoloadbox").stop().animate({
        "width": k+"px"
    }, 10);
}

function boluo_DoNote(text,gourl,title) {
    if(!window.confirm(text)){
        return false;
    }
    boluo_loadding(title);
    location.href=gourl;
}
function boluo_Do(text,gourl) {
    if(!window.confirm(text)){
        return false;
    }
    location.href=gourl;
}

function boluoselect(temp){
    var inp = $(temp).children("input");
    var div = $(temp).children(".boluoselectbox");
    div.click(function(){
        var len = $(this).prevAll("div").length;
        div.removeClass("me");
        div.eq(len).addClass("me");
        inp.val(div.eq(len).data("value"));
    });
}
function boluoselect_setnum(temp,num){
    var inp = $(temp).children("input");
    var div = $(temp).children(".boluoselectbox");
    div.removeClass("me");
    div.eq(num).addClass("me");
    inp.val(div.eq(num).data("value"));
}
function boluoselect_setvalue(temp,text){
    var inp = $(temp).children("input");
    var div = $(temp).children("div");
    var len = div.length;
    for(i=0;i<len;i++){
        var p = div.eq(i).data("value");
        if(p == text){
            div.removeClass("me");
            div.eq(i).addClass("me");
            inp.val(div.eq(i).data("value"));
        }
    }

}