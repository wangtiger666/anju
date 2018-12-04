<?php

class CubeAction extends MemberAction {

    function index() {
        cookie("cubeback", __SELF__);

        $pano_id = I("get.pano_id");
        $this->assign('pano_id', $pano_id);

        $panorow = D("Pano")->GetOne($pano_id, $this->member_id);
        $this->assign('panorow', $panorow);

        $viewlist = D("Panoview")->GetList($pano_id, $this->member_id);
        foreach ($viewlist as $key => $value) {
            $cubelist = D("Cube")->GetList($value['id'],$this->member_id);
            $viewlist[$key]['len'] = count($cubelist);
        }
        $this->assign('viewlist', $viewlist);

        $this->display();
    }

    function cubeview() {
        $backurl = cookie("cubeback");
        $this->assign('backurl', $backurl);

        cookie("back", __SELF__);

        $view_id = I("get.view_id");
        $this->assign("view_id", $view_id);

        $viewrow = D("Panoview")->GetOne($view_id, $this->member_id);
        $this->assign("viewrow", $viewrow);

        $panorow = D("Pano")->GetOne($viewrow['pano_id'], $this->member_id);
        $this->assign("panorow", $panorow);
        $this->assign("pano_id", $viewrow['pano_id']);

        $cubelist = D("Cube")->GetList($view_id, $this->member_id);
        foreach ($cubelist as $key => $cubevalue) {
            if ($cubevalue['spot_type'] == 1) {
                $abwhere = array(
                    "id" => $cubevalue['spot_id']
                );
                $abrow = M("Spot")->where($abwhere)->find();
                $cubelist[$key]["spotphoto"] = $abrow['file'];
            } else if ($cubevalue['spot_type'] == 2) {
                $abwhere = array(
                    "id" => $cubevalue['spot_id']
                );
                $abrow = M("Spot")->where($abwhere)->find();
                $cubelist[$key]["spotphoto"] = $abrow['file'];
                $cubelist[$key]["width"] = $abrow['width'];
                $cubelist[$key]["height"] = $abrow['height'];
                $cubelist[$key]["len"] = $abrow['len'];
                $cubelist[$key]["spotspeed"] = $abrow['speed'];
            } else if ($cubevalue['spot_type'] == 3) {

            }
        }
        $this->assign('cubelist', $cubelist);

        $this->display();
    }

    function cube_add() {
        $backurl = cookie("back");
        $this->assign("backurl", $backurl);

        if (I("post.dopost") == "save") {
            $view_id = I('post.view_id');
            $spot_type = I('post.spot_type');
            $spot_id = I('post.spot_id');
            $movespot_id = I("post.movespot_id");
            $target_id = I('post.target_id');

            $title = trim(I('post.title'));
            $is_hover = I('post.is_hover');
            $is_flash = I('post.is_flash');
            $is_html5 = I('post.is_html5');

            $spot_x = I('post.spot_x');
            $spot_y = I('post.spot_y');
            $spot_scale = I('post.spot_scale');
            $spot_edge = I("post.spot_edge");

            $speed = I('post.speed');
            $otherdirc = I('post.otherdirc');
            $bg_color = I('post.bg_color');
            $is_auto = I('post.is_auto');
            $is_myself = I('post.is_myself');

            $spoturl = I('post.spoturl');
            $smartrx = I('post.smartrx');
            $smartry = I('post.smartry');
            $smartrz = I('post.smartrz');

            if ($title == "") {
                $this->error("请写好【热点名称】！");
                exit();
            }
            if ($target_id == 0) {
                $this->error("请选定360物体！");
                exit();
            }

            if ($spot_type == 1) {

            } else if ($spot_type == 2) {
                $spot_id = $movespot_id;
            }else if ($spot_type == 3) {
                if (is_file(APP_ROOT . $spoturl)) {
                    $viewrow = D("Panoview")->getOne($view_id, $this->member_id);
                    $pano_id = $viewrow["pano_id"];
                    $mybag = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/{$pano_id}";
                    $smartbag = $mybag . "/smartspot";
                    createFolder(APP_ROOT . $smartbag);
                    $newspot = $smartbag . "/" . basename($spoturl);
                    rename(APP_ROOT . $spoturl, APP_ROOT . $newspot);
                    $spoturl = $newspot;
                }
            }

            $cubewhere = array(
                "id" => $target_id,
                "member_id" => $this->member_id
            );
            $cubelist = M("Pano_cube_store")->where($cubewhere)->find();
            if ($cubelist['is_ok'] == 0) {
                $cubetitle = I("post.cubetitle");
                $cubedata = array(
                    "title" => $cubetitle,
                    "is_ok" => 1
                );
                M("Pano_cube_store")->where($cubewhere)->save($cubedata);
            }

            $data = array(
                "title" => $title,
                "member_id" => $this->member_id,
                "view_id" => $view_id,
                "spot_type" => $spot_type,
                "spot_x" => $spot_x,
                "spot_y" => $spot_y,
                "spot_id" => $spot_id,
                "spot_scale" => $spot_scale,
                "target_id" => $target_id,
                "is_hover" => $is_hover,
                "is_flash" => $is_flash,
                "is_html5" => $is_html5,
                "spot_edge" => $spot_edge,
                "speed" => $speed,
                "otherdirc" => $otherdirc,
                "bg_color" => $bg_color,
                "is_auto" => $is_auto,
                "is_myself" => $is_myself,
                'spoturl' => $spoturl,
                'rx' => $smartrx,
                'ry' => $smartry,
                'rz' => $smartrz
            );
            $add_id = M("Pano_cube")->add($data);
            if ($add_id) {
                $this->success("添加成功！", U("cubeview", array("view_id" => $view_id)));
            }
            exit();
        }

        $view_id = I("get.view_id");
        $this->assign("view_id", $view_id);

        $viewrow = D("Panoview")->GetOne($view_id, $this->member_id);
        $this->assign("viewrow", $viewrow);

        $panorow = D("Pano")->GetOne($viewrow['pano_id'], $this->member_id);
        $this->assign("panorow", $panorow);
        $this->assign("pano_id", $viewrow['pano_id']);

        $spotwhere = array(
            "mode" => "system",
            "type" => "spot"
        );
        $spotrow = M("Spot")->where($spotwhere)->order("id")->find();
        $spot_id = $spotrow['id'];
        $this->assign('spotrow', $spotrow);
        $this->assign('spot_id', $spot_id);

        $movespotwhere = array(
            "mode" => "system",
            "type" => "movespot"
        );
        $movespotrow = M("Spot")->where($movespotwhere)->order("id")->find();
        $movespot_id = $movespotrow['id'];
        $this->assign('movespotrow', $movespotrow);
        $this->assign('movespot_id', $movespot_id);

        $this->display();
    }

    function cube_edit(){
        $backurl = cookie("back");
        $this->assign("backurl", $backurl);

        if(I("post.dopost") == "save"){
            $cube_id = I('post.cube_id');
            $view_id = I('post.view_id');
            $spot_type = I('post.spot_type');
            $spot_id = I('post.spot_id');
            $movespot_id = I("post.movespot_id");
            $target_id = I('post.target_id');

            $title = trim(I('post.title'));
            $is_hover = I('post.is_hover');
            $is_flash = I('post.is_flash');
            $is_html5 = I('post.is_html5');

            $spot_x = I('post.spot_x');
            $spot_y = I('post.spot_y');
            $spot_scale = I('post.spot_scale');
            $spot_edge = I("post.spot_edge");

            $speed = I('post.speed');
            $otherdirc = I('post.otherdirc');
            $bg_color = I('post.bg_color');
            $is_auto = I('post.is_auto');
            $is_myself = I('post.is_myself');

            $spoturl = I('post.spoturl');
            $smartrx = I('post.smartrx');
            $smartry = I('post.smartry');
            $smartrz = I('post.smartrz');

            if ($title == "") {
                $this->error("请写好【热点名称】！");
                exit();
            }
            if ($target_id == 0) {
                $this->error("请选定360物体！");
                exit();
            }

            if ($spot_type == 1) {

            } else if ($spot_type == 2) {
                $spot_id = $movespot_id;
            }else if ($spot_type == 3) {
                if (substr_count($spoturl, "station") > 0) {
                    if (is_file(APP_ROOT . $spoturl)) {
                        $cubewhere = array(
                            "member_id" => $this->member_id,
                            "id" => $cube_id
                        );
                        $cuberow = M("Pano_photo")->where($cubewhere)->find();
                        @unlink(APP_ROOT.$cuberow['spoturl']);

                        $viewrow = D("Panoview")->getOne($view_id, $this->member_id);
                        $pano_id = $viewrow["pano_id"];
                        $mybag = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/{$pano_id}";
                        $smartbag = $mybag . "/smartspot";
                        createFolder(APP_ROOT . $smartbag);
                        $newspot = $smartbag . "/" . basename($spoturl);
                        rename(APP_ROOT . $spoturl, APP_ROOT . $newspot);
                        $spoturl = $newspot;
                    }
                }
            }

            $data = array(
                "title" => $title,
                "spot_type" => $spot_type,
                "spot_x" => $spot_x,
                "spot_y" => $spot_y,
                "spot_id" => $spot_id,
                "spot_scale" => $spot_scale,
                "is_hover" => $is_hover,
                "is_flash" => $is_flash,
                "is_html5" => $is_html5,
                "spot_edge" => $spot_edge,
                "speed" => $speed,
                "otherdirc" => $otherdirc,
                "bg_color" => $bg_color,
                "is_auto" => $is_auto,
                "is_myself" => $is_myself,
                'spoturl' => $spoturl,
                'rx' => $smartrx,
                'ry' => $smartry,
                'rz' => $smartrz
            );
            $where = array(
                "id" => $cube_id,
                "member_id" => $this->member_id
            );
            M("Pano_cube")->where($where)->save($data);
            $this->success("修改成功！",U("cubeview", array("view_id" => $view_id)));

            exit();
        }

        $cube_id = I("get.cube_id");
        $this->assign("cube_id", $cube_id);

        $cuberow = D("Cube")->GetOne($cube_id,$this->member_id);
        $this->assign('cuberow', $cuberow);
        $view_id = $cuberow['view_id'];
        $this->assign('view_id', $view_id);

        $viewrow = D("Panoview")->GetOne($view_id,$this->member_id);
        $this->assign('viewrow', $viewrow);
        $pano_id = $viewrow['pano_id'];
        $this->assign('pano_id', $pano_id);

        $panorow = D("Pano")->GetOne($pano_id,$this->member_id);
        $this->assign('panorow', $panorow);

        $spotwhere = array(
            "mode" => "system",
            "type" => "spot"
        );
        $spotrow = M("Spot")->where($spotwhere)->order("id")->find();
        $spot_id = $spotrow['id'];
        $spot_file = $spotrow['file'];
        $this->assign('spotrow', $spotrow);

        $movespotwhere = array(
            "mode" => "system",
            "type" => "movespot"
        );
        $movespotrow = M("Spot")->where($movespotwhere)->order("id")->find();
        $movespot_id = $movespotrow['id'];
        $movespot_file = $movespotrow['file'];
        $this->assign('movespotrow', $movespotrow);

        $spot_type = $cuberow['spot_type'];
        if ($spot_type == 1) {
            $spot_id = $cuberow["spot_id"];
            $thespotwhere = array(
                "id" => $spot_id
            );
            $thespotrow = M("Spot")->where($thespotwhere)->find();
            $this->assign('spotrow', $thespotrow);
        } else if ($spot_type == 2) {
            $movespot_id = $cuberow["spot_id"];
            $thespotwhere = array(
                "id" => $movespot_id
            );
            $thespotrow = M("Spot")->where($thespotwhere)->find();
            $this->assign('movespotrow', $thespotrow);
        }
        $this->assign('spot_id', $spot_id);
        $this->assign('movespot_id', $movespot_id);

        $this->assign('spot_file', $spot_file);
        $this->assign('movespot_file', $movespot_file);

        $this->display();
    }

    function cube_readd(){
        $backurl = cookie("back");
        $this->assign("backurl", $backurl);

        if(I("post.dopost") == "save"){
            $cube_id = I('post.cube_id');
            $view_id = I('post.view_id');
            $spot_type = I('post.spot_type');
            $spot_id = I('post.spot_id');
            $movespot_id = I("post.movespot_id");
            $target_id = I('post.target_id');

            $title = trim(I('post.title'));
            $is_hover = I('post.is_hover');
            $is_flash = I('post.is_flash');
            $is_html5 = I('post.is_html5');

            $spot_x = I('post.spot_x');
            $spot_y = I('post.spot_y');
            $spot_scale = I('post.spot_scale');
            $spot_edge = I("post.spot_edge");

            $speed = I('post.speed');
            $otherdirc = I('post.otherdirc');
            $bg_color = I('post.bg_color');
            $is_auto = I('post.is_auto');
            $is_myself = I('post.is_myself');

            if ($title == "") {
                $this->error("请写好【热点名称】！");
                exit();
            }
            if ($target_id == 0) {
                $this->error("请选定360物体！");
                exit();
            }

            if ($spot_type == 1) {

            } else if ($spot_type == 2) {
                $spot_id = $movespot_id;
            }

            $cubewhere = array(
                "id" => $target_id,
                "member_id" => $this->member_id
            );
            $cubelist = M("Pano_cube_store")->where($cubewhere)->find();
            if ($cubelist['is_ok'] == 0) {
                $cubetitle = I("post.cubetitle");
                $cubedata = array(
                    "title" => $cubetitle,
                    "is_ok" => 1
                );
                M("Pano_cube_store")->where($cubewhere)->save($cubedata);
            }

            $data = array(
                "title" => $title,
                "spot_type" => $spot_type,
                "spot_x" => $spot_x,
                "spot_y" => $spot_y,
                "spot_id" => $spot_id,
                "spot_scale" => $spot_scale,
                "target_id" => $target_id,
                "is_hover" => $is_hover,
                "is_flash" => $is_flash,
                "is_html5" => $is_html5,
                "spot_edge" => $spot_edge,
                "speed" => $speed,
                "otherdirc" => $otherdirc,
                "bg_color" => $bg_color,
                "is_auto" => $is_auto,
                "is_myself" => $is_myself
            );
            $where = array(
                "id" => $cube_id,
                "member_id" => $this->member_id
            );
            M("Pano_cube")->where($where)->save($data);
            $this->success("修改成功！",U("cubeview", array("view_id" => $view_id)));

            exit();
        }

        $cube_id = I("get.cube_id");
        $this->assign("cube_id", $cube_id);

        $cuberow = D("Cube")->GetOne($cube_id,$this->member_id);
        $this->assign('cuberow', $cuberow);
        $view_id = $cuberow['view_id'];
        $this->assign('view_id', $view_id);

        $viewrow = D("Panoview")->GetOne($view_id,$this->member_id);
        $this->assign('viewrow', $viewrow);
        $pano_id = $viewrow['pano_id'];
        $this->assign('pano_id', $pano_id);

        $panorow = D("Pano")->GetOne($pano_id,$this->member_id);
        $this->assign('panorow', $panorow);

        $spotwhere = array(
            "mode" => "system",
            "type" => "spot"
        );
        $spotrow = M("Spot")->where($spotwhere)->order("id")->find();
        $spot_id = $spotrow['id'];
        $spot_file = $spotrow['file'];
        $this->assign('spotrow', $spotrow);

        $movespotwhere = array(
            "mode" => "system",
            "type" => "movespot"
        );
        $movespotrow = M("Spot")->where($movespotwhere)->order("id")->find();
        $movespot_id = $movespotrow['id'];
        $movespot_file = $movespotrow['file'];
        $this->assign('movespotrow', $movespotrow);

        $spot_type = $cuberow['spot_type'];
        if ($spot_type == 1) {
            $spot_id = $cuberow["spot_id"];
            $thespotwhere = array(
                "id" => $spot_id
            );
            $thespotrow = M("Spot")->where($thespotwhere)->find();
            $this->assign('spotrow', $thespotrow);
        } else if ($spot_type == 2) {
            $movespot_id = $cuberow["spot_id"];
            $thespotwhere = array(
                "id" => $movespot_id
            );
            $thespotrow = M("Spot")->where($thespotwhere)->find();
            $this->assign('movespotrow', $thespotrow);
        }
        $this->assign('spot_id', $spot_id);
        $this->assign('movespot_id', $movespot_id);

        $this->assign('spot_file', $spot_file);
        $this->assign('movespot_file', $movespot_file);

        $this->display();
    }

    function readcube() {
        $cube_id = I("get.cube_id");
        $where = array(
            "id" => $cube_id
        );
        $cubelist = M("Pano_cube_store")->where($where)->find();
        $this->assign("row", $cubelist);

        $end = array(
            0 => "png",
            1 => "gif",
            2 => "jpg"
        );
        $this->assign("end", $end);

        $speed = I("get.speed");

        if ($speed == "") {
            $speed = 5;
        }
        $speedarr = array();
        $speedarr[1] = 0.4;
        $speedarr[2] = 0.35;
        $speedarr[3] = 0.3;
        $speedarr[4] = 0.25;
        $speedarr[5] = 0.2;
        $speedarr[6] = 0.15;
        $speedarr[7] = 0.1;
        $speedarr[8] = 0.05;
        $speedarr[9] = 0.03;
        $speedarr[10] = 0.001;

        $this->assign('speed', $speedarr[$speed]);

        $fd = I("get.fd");
        if($fd == 1){
            $fd = -1;
        }else{
            $fd = 1;
        }

        $this->assign('fd', $fd);

        $this->display('./App/Tpl/Member/Cube/readcube.xml', 'utf-8', 'text/xml');
    }

    function cube_del(){
        $del_id = I("get.cube_id");
        $row = D("Cube")->GetOne($del_id,$this->member_id);
        if(is_array($row)){
            D("Cube")->DelOne($del_id,$this->member_id);
            $this->redirect("cubeview",array("view_id" => $row['view_id']));
        }else{
            $this->error("删除失败！");
        }
    }

    function cube_bag(){
        $pano_id = I("get.pano_id");
        $this->assign('pano_id', $pano_id);

        $panorow = D("Pano")->GetOne($pano_id, $this->member_id);
        $this->assign('panorow', $panorow);

        $cubewhere = array(
            "pano_id" => $pano_id,
            "member_id" => $this->member_id
        );
        $cuberow = M("Pano_cube_store")->where($cubewhere)->select();
        $this->assign('cuberow', $cuberow);

        $this->display();
    }

}

?>
