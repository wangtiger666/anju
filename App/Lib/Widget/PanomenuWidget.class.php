<?php

class PanomenuWidget extends Widget{
    public function render($data) {
        $theid = $data['pano_id'];
        $thenum = $data['num'];
        $nome = array(
            '<a href="'.U("member/config/index",array("pano_id"=>$theid)).'" class="pano_menu_bottom">全局参数</a>',
            '<a href="'.U("member/view/index",array("pano_id"=>$theid)).'" class="pano_menu_bottom">全景场景</a>',
            //'<a href="'.U("member/house_resources/addOrEdit",array("pano_id"=>$theid)).'" class="pano_menu_bottom">房源编辑</a>',
            '<a href="'.U("member/roam/index",array("pano_id"=>$theid)).'" class="pano_menu_bottom">全景漫游</a>',
            '<a href="'.U("member/toolbox/index",array("pano_id"=>$theid)).'" class="pano_menu_bottom">模块管理</a>',
            '<a href="'.U("member/music/index",array("pano_id"=>$theid)).'" class="pano_menu_bottom">背景音乐</a>',
            '<a href="'.U("member/ui/index",array("pano_id"=>$theid)).'" class="pano_menu_bottom">界面模板</a>',
            '<a href="'.U("member/effect/index",array("pano_id"=>$theid)).'" class="pano_menu_bottom">全景特效</a>',
            '<a href="'.U("member/ban/index",array("pano_id"=>$theid)).'" class="pano_menu_bottom">版权信息</a>',
            '<a href="'.U("member/photo/index",array("pano_id"=>$theid)).'" class="pano_menu_bottom">全景图集</a>',
            '<a href="'.U("member/cube/index",array("pano_id"=>$theid)).'" class="pano_menu_bottom">360物体</a>',			
			//'<a href="'.U("member/preview/index",array("pano_id"=>$theid)).'" class="pano_menu_bottom">预览</a>',
            //'<a href="'.U("member/putout/index",array("pano_id"=>$theid)).'" class="pano_menu_bottom">生成</a>'

			'<a href="javascript:void(0);" onclick=\'openwin("项目预览","'.U("member/preview/index",array("pano_id"=>$theid)).'",1200,650);\' class="pano_menu_bottom">预览</a>',
			'<a href="javascript:void(0);" onclick=\'openwin("项目生成","'.U("member/putout/index",array("pano_id"=>$theid)).'",800,650);\' class="pano_menu_bottom">生成</a>',
        );
        $me = array(
            '<a href="'.U("member/config/index",array("pano_id"=>$theid)).'" class="pano_menu_bottom me">全局参数</a>',
            '<a href="'.U("member/view/index",array("pano_id"=>$theid)).'" class="pano_menu_bottom me">全景场景</a>',
            //'<a href="'.U("member/house_resources/addOrEdit",array("pano_id"=>$theid)).'" class="pano_menu_bottom me">房源编辑</a>',
            '<a href="'.U("member/roam/index",array("pano_id"=>$theid)).'" class="pano_menu_bottom me">全景漫游</a>',
            '<a href="'.U("member/toolbox/index",array("pano_id"=>$theid)).'" class="pano_menu_bottom me">模块管理</a>',
            '<a href="'.U("member/music/index",array("pano_id"=>$theid)).'" class="pano_menu_bottom me">背景音乐</a>',
            '<a href="'.U("member/ui/index",array("pano_id"=>$theid)).'" class="pano_menu_bottom me">界面模板</a>',
            '<a href="'.U("member/effect/index",array("pano_id"=>$theid)).'" class="pano_menu_bottom me">全景特效</a>',
            '<a href="'.U("member/ban/index",array("pano_id"=>$theid)).'" class="pano_menu_bottom me">版权信息</a>',
            '<a href="'.U("member/photo/index",array("pano_id"=>$theid)).'" class="pano_menu_bottom me">全景图集</a>',
            '<a href="'.U("member/cube/index",array("pano_id"=>$theid)).'" class="pano_menu_bottom me">360物体</a>',
            '<a href="'.U("member/preview/index",array("pano_id"=>$theid)).'" class="pano_menu_bottom me">预览</a>', 
            '<a href="'.U("member/putout/index",array("pano_id"=>$theid)).'" class="pano_menu_bottom me">生成</a>',

        );

        $html = '';
        foreach ($nome as $k => $v) {
            $n = $k+1;
            if($n != $thenum){
                $html .= $v;
            }else{
                $html .= $me[$k];
            }
        }

        return $html;
    }
}

?>
