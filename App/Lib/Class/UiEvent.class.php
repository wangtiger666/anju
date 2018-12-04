<?php


class UiEvent {
    var $eventdata;

    function __construct() {
        $this->eventdata = array(
            1=>array(
                "info" => "镜头 · 左转",
                "ondown" => "set(hlookat_moveforce,-1);",
                "onup" => "set(hlookat_moveforce,0);",
                "onclick" => "",
                "onover" => "",
                "onout" => "",
                "onloaded" => ""
            ),
            2=>array(
                "info" => "镜头 · 右转",
                "ondown" => "set(hlookat_moveforce,1);",
                "onup" => "set(hlookat_moveforce,0);",
                "onclick" => "",
                "onover" => "",
                "onout" => "",
                "onloaded" => ""
            ),
            3=>array(
                "info" => "镜头 · 上转",
                "ondown" => "set(vlookat_moveforce,-1);",
                "onup" => "set(vlookat_moveforce,0);",
                "onclick" => "",
                "onover" => "",
                "onout" => "",
                "onloaded" => ""
            ),
            4=>array(
                "info" => "镜头 · 下转",
                "ondown" => "set(vlookat_moveforce,1);",
                "onup" => "set(vlookat_moveforce,0);",
                "onclick" => "",
                "onover" => "",
                "onout" => "",
                "onloaded" => ""
            ),
            5=>array(
                "info" => "镜头 · 景深拉近",
                "ondown" => "set(fov_moveforce,-1);",
                "onup" => "set(fov_moveforce,0);",
                "onclick" => "",
                "onover" => "",
                "onout" => "",
                "onloaded" => ""
            ),
            6=>array(
                "info" => "镜头 · 景深拉远",
                "ondown" => "set(fov_moveforce,1);",
                "onup" => "set(fov_moveforce,0);",
                "onclick" => "",
                "onover" => "",
                "onout" => "",
                "onloaded" => ""
            ),
            6=>array(
                "info" => "全屏 · 切换",
                "ondown" => "",
                "onup" => "",
                "onclick" => "switch(fullscreen);",
                "onover" => "",
                "onout" => "",
                "onloaded" => ""
            ),
            7=>array(
                "info" => "鼠标事件 · 可拖动",
                "ondown" => "action(dragui);",
                "onup" => "",
                "onclick" => "",
                "onover" => "",
                "onout" => "",
                "onloaded" => ""
            )
        );
    }

    function eventlist(){
        $r = array();
        $n = 0;
        foreach ($this->eventdata as $num => $event) {
            $r[$n]["id"] = $num;
            $r[$n]["name"] = $event['info'];
            $n++;
        }
        return $r;
    }
    function getOne($n){
        return $this->eventdata[$n];
    }
}

?>
