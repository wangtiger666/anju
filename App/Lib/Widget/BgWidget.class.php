<?php

class BgWidget extends Widget {

    public function render($data) {
        $name = C("SESSION_MEMBERID");
        $member_id = session($name);
        $where = array(
            "member_id" => $member_id
        );
        $row = M("Background")->where($where)->find();
        if (!is_array($row)) {
            $img = "1.jpg";
            $data = array(
                "member_id" => $member_id,
                "bgimg" => $img
            );
            M("Background")->add($data);
        } else {
            $img = $row['bgimg'];
        }
        $html = '<script type="text/javascript">

            $(function(){
                $(window).resize(function(){
                    win_bg();
                })
            });
            function win_bg(){
                $("#bg").css("width","100%");
                $("#bg").css("height","auto");
                var win_w = $(window).width();
                var win_h = $(window).height();
                var pic_w = $("#bg").width();
                var pic_h = $("#bg").height();
                if(win_h<pic_h){
                    $("#bg").css("width","100%");
                    $("#bg").css("height","auto");
                }else{
                    $("#bg").css("height","100%");
                    $("#bg").css("width","auto");
                }
            }
        </script>
        <style type="text/css">
            .suoboxin{width:360px;height:40px;overflow:hidden;cursor: default;line-height:40px; background:url(/Public/member/images/common/white.png);position: absolute;left:200px;bottom:0px;z-index:100000;}
            .suobox1{width:auto;height:40px;overflow:hidden; line-height:40px;float:left; margin-left:15px;}
        </style>
        <img id="bg" onload="win_bg();" src="__PUBLIC__/member/images/background/' . $img . '" />';
        return $html;
    }

}

?>
