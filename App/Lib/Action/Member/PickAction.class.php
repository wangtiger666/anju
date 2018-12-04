<?php

class PickAction extends MemberAction {

    function spot() {
        $sp = I("get.sp");
        if ($sp == "") {
            $sp = 1;
        }
        $this->assign("sp", $sp);

        import('ORG.Util.Page'); // 导入分页类

        if ($sp == 1) {
            $spotwhere = array(
                "mode" => "system",
                "type" => "spot"
            );
        } else {
            $spotwhere = array(
                "mode" => "self",
                "type" => "spot",
                "member_id" => $this->member_id
            );
        }
        $F = M("Spot");
        $count = $F->where($spotwhere)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 20); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $F->where($spotwhere)->order('id')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出

        $this->display();
    }

    function movespot() {
        $sp = I("get.sp");
        if ($sp == "") {
            $sp = 1;
        }
        $this->assign("sp", $sp);

        import('ORG.Util.Page'); // 导入分页类

        if ($sp == 1) {
            $spotwhere = array(
                "mode" => "system",
                "type" => "movespot"
            );
        } else {
            $spotwhere = array(
                "mode" => "self",
                "type" => "movespot",
                "member_id" => $this->member_id
            );
        }
        $F = M("Spot");
        $count = $F->where($spotwhere)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 20); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $F->where($spotwhere)->order('id')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出

        $this->display();
    }

    function spotxy() {
        $view_id = I("get.view_id");
        $spot_id = I("get.spot_id");
        $this->assign('view_id', $view_id);
        $this->assign('spot_id', $spot_id);

        $roam_id = I("get.roam_id");
        $this->assign('roam_id', $roam_id);

        $viewwhere = array(
            "id" => $view_id
        );
        $viewrow = M("Pano_view")->where($viewwhere)->find();
        $this->assign("viewrow", $viewrow);

        $xmlurl = U('spotxyxml', array('view_id' => $view_id, "roam_id" => $roam_id, "spot_id" => $spot_id));
        $xmlscript = "embedpano({swf:\"__PUBLIC__/pano/pano.swf\", xml:\"$xmlurl\", target:\"pano\", html5:\"auto\", passQueryParameters:true});";
        $this->assign('xmlscript', $xmlscript);

        $this->display();
    }

    function spotxyxml() {
        $view_id = I("get.view_id");
        $viewwhere = array(
            "id" => $view_id
        );
        $viewrow = M("Pano_view")->where($viewwhere)->find();
        $this->assign("viewrow", $viewrow);

        $spot_id = I("get.spot_id");
        $spotwhere = array(
            "id" => $spot_id
        );
        $spotrow = M("Spot")->where($spotwhere)->find();
        $this->assign("spotrow", $spotrow);

        $roam_id = I("get.roam_id");
        $this->assign('roam_id', $roam_id);

        $x = 0;
        $y = 0;
        $scale = "1";
        $edge = "center";

        if ($roam_id != "") {
            $roamwhere = array(
                "id" => $roam_id,
                "member_id" => $this->member_id
            );
            $roamrow = M("Pano_roam")->where($roamwhere)->find();
            $x = $roamrow["spot_x"];
            $y = $roamrow["spot_y"];
            $scale = $roamrow["spot_scale"];
            $scale = $scale / 100;
            $edge = $roamrow["spot_edge"];
        }
        $this->assign("x", $x);
        $this->assign("y", $y);
        $this->assign("scale", $scale);
        $this->assign("edge", $edge);

        $this->display('./App/Tpl/Member/Pick/spotxyxml.xml', 'utf-8', 'text/xml');
    }

    function smartspot(){
        $view_id = I("get.view_id");
        $viewwhere = array(
            "id" => $view_id
        );
        $viewrow = M("Pano_view")->where($viewwhere)->find();
        $this->assign("viewrow", $viewrow);

        $img = I('get.img');
        cookie("img",$img);
        $spot_x = I('get.spot_x');
        cookie("spot_x",$spot_x);
        $spot_y = I('get.spot_y');
        cookie("spot_y",$spot_y);
        $rx = I('get.rx');
        cookie("rx",$rx);
        $ry = I('get.ry');
        cookie("ry",$ry);
        $rz = I('get.rz');
        cookie("rz",$rz);
        $scale = I('get.scale');
        cookie("scale",$scale);
        $rotate = I('get.rotate');
        cookie("rotate",$rotate);

        $xmlurl = U('smartspotxml', array('view_id' => $view_id));
        $xmlscript = "embedpano({id:\"krpano\",swf:\"__PUBLIC__/pano/pano.swf\", xml:\"$xmlurl\", target:\"pano\", html5:\"auto\",wmode:\"transparent\"});";
        $this->assign('xmlscript', $xmlscript);

        $this->display();
    }

    function smartspotxml(){
        $view_id = I("get.view_id");
        $viewwhere = array(
            "id" => $view_id
        );
        $viewrow = M("Pano_view")->where($viewwhere)->find();
        $this->assign("viewrow", $viewrow);

        $this->display('./App/Tpl/Member/Pick/smartspot.xml', 'utf-8', 'text/xml');
    }

    function targetxy() {
        $view_id = I("get.view_id");
        $this->assign('view_id', $view_id);

        $roam_id = I("get.roam_id");
        $this->assign('roam_id', $roam_id);

        $viewwhere = array(
            "id" => $view_id
        );
        $viewrow = M("Pano_view")->where($viewwhere)->find();
        $this->assign("viewrow", $viewrow);

        $xmlurl = U('targetxyxml', array('view_id' => $view_id, "roam_id" => $roam_id));
        $xmlscript = "embedpano({swf:\"__PUBLIC__/pano/pano.swf\", xml:\"$xmlurl\", target:\"pano\", html5:\"auto\", passQueryParameters:true});";
        $this->assign('xmlscript', $xmlscript);

        $this->display();
    }

    function targetxyxml() {
        $view_id = I("get.view_id");
        $viewwhere = array(
            "id" => $view_id
        );
        $viewrow = M("Pano_view")->where($viewwhere)->find();
        $this->assign("viewrow", $viewrow);

        $roam_id = I("get.roam_id");
        $this->assign('roam_id', $roam_id);

        $x = 0;
        $y = 0;

        if ($roam_id != "") {
            $roamwhere = array(
                "id" => $roam_id,
                "member_id" => $this->member_id
            );
            $roamrow = M("Pano_roam")->where($roamwhere)->find();
            $x = $roamrow["target_x"];
            $y = $roamrow["target_y"];
        }
        $this->assign("x", $x);
        $this->assign("y", $y);

        $this->display('./App/Tpl/Member/Pick/targetxyxml.xml', 'utf-8', 'text/xml');
    }

    function music() {
        $where = array(
            "member_id" => $this->member_id
        );
        import('ORG.Util.Page'); // 导入分页类

        $F = M("Music");
        $count = $F->where($where)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 8); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $F->where($where)->order('id')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出

        $this->display();
    }

    function musicbtn() {
        $pano_id = I("get.pano_id");
        $this->assign("pano_id", $pano_id);

        $this->display();
    }

    function uibox() {
        $devices = I("get.devices");
        $this->assign("devices",$devices);
        $tp = I("get.tp");
        $this->assign("tp", $tp);

        if ($tp == 1) {
            $where = array(
                "member_id" => $this->member_id,
                "devices" => $devices
            );
            $list = M("Ui")->where($where)->select();

            $uc = D("Uichild");
            foreach ($list as $k => $va) {
                $uclist = $uc->getList($va['id']);
                $list[$k]['len'] = count($uclist);
            }
            $this->assign('list', $list);
        }else{
            $list = M("Sysui")->where($where)->order('id asc')->select();
            $this->assign('list', $list);
        }
		//2017.3.15 降序排列
        $this->display();
    }
    
    function scene(){
        $pano_id = I("get.pano_id");
        $view_id = I("get.view_id");
        $this->assign("pano_id", $pano_id);
        $this->assign("view_id", $view_id);
        
        $vlist = D("Panoview")->GetList($pano_id, $this->member_id);
        $this->assign("vlist", $vlist);
        
        $this->display();
    }
}

?>
