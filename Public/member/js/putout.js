var step = 0;
var stepmax = 7;

function startdo(){
    var smax = $("#scene_len").val();
    if(smax>0){
        $(".blackbutton").hide();
    step = 0;
    nextdo();
    }else{
        showMsg("<span>场景数</span>不可以为0！请添加场景！");
    }
}
function nextdo(){
    step = step + 1;
    actiondo(step);
}
function actiondo(st){
    if(st > stepmax){
        finishdo();
    }
    if(st == 1){
        creatdir();
    }else if(st == 2){
        showaction('正在生成场景！文件较多 可能需要花费几分钟的时间');
        scenedo(1);
    }else if(st == 3){
        showaction('正在查看图集数目！');
        photocheck();
    }else if(st == 4){
        showaction('正在查看360物体数目！');
        cubecheck();
    }else if(st == 5){
        showaction('开始生成XML规则！');
        panoxmldo();
    }else if(st == 6){
        showaction('开始生成模块部分！');
        modeldo(1);
    }else if(st == 7){
        showaction('开始生成其它！');
        otherdo(1);
    }
}
function finishdo(){
    pushout();
}

function showaction(txt){
    $("#action").html(txt);
}
function loadto(num,t){
    var w = num * 3;
    var n = step - 1;
    if(num<100){
        $(".loading").eq(n).stop().animate({"width":w+"px"}, t);
    }else{
        $(".loading").eq(n).stop().animate({"width":w+"px"}, t,function(){
            $(".okbox").eq(n).addClass("ok");
        });
    }
}
