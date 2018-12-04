<?php

class VpointAction extends MemberAction {

    public function index() {
        cookie("headback", __SELF__);

        $pano_id = I("get.pano_id");
        $this->assign('pano_id', $pano_id);

        $panorow = D("Pano")->GetOne($pano_id, $this->member_id);
        $this->assign('panorow', $panorow);

        $viewlist = D("Panoview")->GetList($pano_id, $this->member_id);
        foreach ($viewlist as $k => $view) {
            $lwhere = array(
                "view_id" => $view["id"],
                "member_id" => $this->member_id
            );
            $vspotrow = M("Pano_vpoint")->where($lwhere)->select();
            $viewlist[$k]["len"] = count($vspotrow);
        }
        $this->assign('viewlist', $viewlist);

        $this->display();
    }

    function view() {
        $backurl = cookie("headback");
        $this->assign('backurl', $backurl);
        cookie("backurl", __SELF__);

        $view_id = I("get.view_id");
        $this->assign("view_id", $view_id);

        $view_id = I("get.view_id");
        $this->assign("view_id", $view_id);

        $viewrow = D("Panoview")->getOne($view_id, $this->member_id);
        $this->assign("viewrow", $viewrow);

        $panorow = D("Pano")->getOne($viewrow['pano_id'], $this->member_id);
        $this->assign("panorow", $panorow);
        $this->assign("pano_id", $viewrow['pano_id']);

        $roamwhere = array(
            "view_id" => $view_id,
            "member_id" => $this->member_id
        );
        $roamrow = M("Pano_vpoint")->where($roamwhere)->order("id")->select();

        $len = count($roamrow);
        foreach ($roamrow as $key => $roamvalue) {
            if ($roamvalue['spot_type'] == 1) {
                $abwhere = array(
                    "id" => $roamvalue['spot_id']
                );
                $abrow = M("Spot")->where($abwhere)->find();
                $roamrow[$key]["spotphoto"] = $abrow['file'];
            } else if ($roamvalue['spot_type'] == 2) {
                $abwhere = array(
                    "id" => $roamvalue['spot_id']
                );
                $abrow = M("Spot")->where($abwhere)->find();
                $roamrow[$key]["spotphoto"] = $abrow['file'];
                $roamrow[$key]["width"] = $abrow['width'];
                $roamrow[$key]["height"] = $abrow['height'];
                $roamrow[$key]["len"] = $abrow['len'];
                $roamrow[$key]["speed"] = $abrow['speed'];
            } else if ($roamvalue['spot_type'] == 3) {

            }
        }
        $this->assign("roamrow", $roamrow);
        $this->assign("len", $len);

        $this->display();
    }

    function roam_add() {
        $backurl = cookie("backurl");
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

            $spoturl = I('post.spoturl');
            $smartrx = I('post.smartrx');
            $smartry = I('post.smartry');
            $smartrz = I('post.smartrz');

            $file = I('post.file');
            $applefile = I('post.applefile');
            $appleimg = I('post.appleimg');
            $old_file = I('post.old_file');
            $old_applefile = I('post.old_applefile');
            $old_appleimg = I('post.old_appleimg');
            $open_apple = I('post.open_apple');
			
			if(empty($file)) $file = $applefile;
			$open_apple = 1;


            $viewrow = D("Panoview")->getOne($view_id, $this->member_id);
            $pano_id = $viewrow["pano_id"];

            if ($title == "") {
                $this->error("请写好【漫游热点名称】！");
                exit();
            }
            if ($spot_type == 1) {

            } else if ($spot_type == 2) {
                $spot_id = $movespot_id;
            } else if ($spot_type == 3) {
                if (is_file(APP_ROOT . $spoturl)) {
                    $mybag = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/{$pano_id}";
                    $smartbag = $mybag . "/smartspot";
                    createFolder(APP_ROOT . $smartbag);
                    $newspot = $smartbag . "/" . basename($spoturl);
                    rename(APP_ROOT . $spoturl, APP_ROOT . $newspot);
                    $spoturl = $newspot;
                }
            }

            $fileurl = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/$pano_id/toolbox/video";
            $file = ExecUpload($file, $old_file, $fileurl);
            $applefile = ExecUpload($applefile, $old_applefile, $fileurl);
            $appleimg = ExecUpload($appleimg, $old_appleimg, $fileurl);

            $data = array(
                "title" => $title,
                "member_id" => $this->member_id,
                "pano_id" => $pano_id,
                "view_id" => $view_id,
                "spot_type" => $spot_type,
                "spot_x" => $spot_x,
                "spot_y" => $spot_y,
                "spot_id" => $spot_id,
                "spot_scale" => $spot_scale,
                "is_hover" => $is_hover,
                "is_flash" => $is_flash,
                "is_html5" => $is_html5,
                "spot_edge" => $spot_edge,
                'spoturl' => $spoturl,
                'rx' => $smartrx,
                'ry' => $smartry,
                'rz' => $smartrz,
                'file' => $file,
                'applefile' => $applefile,
                'appleimg' => $appleimg,
                'open_apple' => $open_apple
            );
            $add_id = M("Pano_vpoint")->add($data);
            if ($add_id) {
                $this->success("添加成功！", $backurl);
            }
            exit();
        }

        $view_id = I("get.view_id");
        $this->assign("view_id", $view_id);

        $viewrow = D("Panoview")->getOne($view_id, $this->member_id);
        $this->assign("viewrow", $viewrow);

        $panorow = D("Pano")->getOne($viewrow['pano_id'], $this->member_id);
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

    function roam_edit() {
        $backurl = cookie("backurl");
        $this->assign("backurl", $backurl);

        if (I("post.dopost") == "save") {
            $view_id = I('post.view_id');
            $spot_type = I('post.spot_type');
            $spot_id = I('post.spot_id');
            $movespot_id = I("post.movespot_id");

            $title = trim(I('post.title'));
            $is_hover = I('post.is_hover');
            $is_flash = I('post.is_flash');
            $is_html5 = I('post.is_html5');

            $spot_x = I('post.spot_x');
            $spot_y = I('post.spot_y');
            $spot_scale = I('post.spot_scale');
            $spot_edge = I("post.spot_edge");

            $spoturl = I('post.spoturl');
            $smartrx = I('post.smartrx');
            $smartry = I('post.smartry');
            $smartrz = I('post.smartrz');

            $file = I('post.file');
            $applefile = I('post.applefile');			
            $appleimg = I('post.appleimg');
            $old_file = I('post.old_file');
            $old_applefile = I('post.old_applefile');
            $old_appleimg = I('post.old_appleimg');
            $open_apple = I('post.open_apple');

			if(empty($file)) $file = $applefile;
			$open_apple = 1;

            if ($title == "") {
                $this->error("请写好【漫游热点名称】！");
                exit();
            }

            $viewrow = D("Panoview")->getOne($view_id, $this->member_id);
            $pano_id = $viewrow["pano_id"];

            if ($spot_type == 1) {

            } else if ($spot_type == 2) {
                $spot_id = $movespot_id;
            } else if ($spot_type == 3) {
                if (substr_count($spoturl, "station") > 0) {
                    if (is_file(APP_ROOT . $spoturl)) {
                        $roamwhere = array(
                            "member_id" => $this->member_id,
                            "id" => I("post.roam_id")
                        );
                        $roamrow = M("Pano_link ")->where($roamwhere)->find();
                        @unlink(APP_ROOT . $roamrow['spoturl']);

                        $mybag = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/{$pano_id}";
                        $smartbag = $mybag . "/smartspot";
                        createFolder(APP_ROOT . $smartbag);
                        $newspot = $smartbag . "/" . basename($spoturl);
                        rename(APP_ROOT . $spoturl, APP_ROOT . $newspot);
                        $spoturl = $newspot;
                    }
                }
            }

            $fileurl = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/$pano_id/toolbox/video";
            $file = ExecUpload($file, $old_file, $fileurl);
            $applefile = ExecUpload($applefile, $old_applefile, $fileurl);
            $appleimg = ExecUpload($appleimg, $old_appleimg, $fileurl);

            $data = array(
                "title" => $title,
                "member_id" => $this->member_id,
                "pano_id" => $pano_id,
                "view_id" => $view_id,
                "spot_type" => $spot_type,
                "spot_x" => $spot_x,
                "spot_y" => $spot_y,
                "spot_id" => $spot_id,
                "spot_scale" => $spot_scale,
                "target_id" => $target_id,
                "target_x" => $target_x,
                "target_y" => $target_y,
                "is_hover" => $is_hover,
                "is_flash" => $is_flash,
                "is_html5" => $is_html5,
                "spot_edge" => $spot_edge,
                'changetype' => $changetype,
                'spoturl' => $spoturl,
                'rx' => $smartrx,
                'ry' => $smartry,
                'rz' => $smartrz,
                'file' => $file,
                'applefile' => $applefile,
                'appleimg' => $appleimg,
                'open_apple' => $open_apple
            );
            $where = array(
                "id" => I("post.roam_id"),
                "member_id" => $this->member_id
            );
            M("Pano_vpoint")->where($where)->save($data);
            $this->success("修改成功！", $backurl);
            exit();
        }

        $roam_id = I("get.roam_id");
        $this->assign("roam_id", $roam_id);

        $roamwhere = array(
            "member_id" => $this->member_id,
            "id" => $roam_id
        );
        $roamrow = M("Pano_vpoint")->where($roamwhere)->find();
        $this->assign("roamrow", $roamrow);

        $viewwhere = array(
            "member_id" => $this->member_id,
            "id" => $roamrow['view_id']
        );
        $viewrow = M("Pano_view")->where($viewwhere)->find();
        $this->assign("viewrow", $viewrow);
        $this->assign("view_id", $viewrow['id']);

        $panowhere = array(
            "member_id" => $this->member_id,
            "id" => $viewrow['pano_id']
        );
        $panorow = M("Pano")->where($panowhere)->find();
        $this->assign("panorow", $panorow);
        $this->assign("pano_id", $panorow['id']);

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

        $spot_type = $roamrow['spot_type'];
        if ($spot_type == 1) {
            $spot_id = $roamrow["spot_id"];
            $thespotwhere = array(
                "id" => $spot_id
            );
            $thespotrow = M("Spot")->where($thespotwhere)->find();
            $this->assign('spotrow', $thespotrow);
        } else if ($spot_type == 2) {
            $movespot_id = $roamrow["spot_id"];
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

    function roam_del() {
        $roam_id = I("get.roam_id");
        $roamwhere = array(
            "member_id" => $this->member_id,
            "id" => $roam_id
        );
        $roamrow = M("Pano_vpoint")->where($roamwhere)->find();
        @unlink(APP_ROOT.$roamrow["file"]);
        @unlink(APP_ROOT.$roamrow["applefile"]);
        @unlink(APP_ROOT.$roamrow["appleimg"]);

        $viewwhere = array(
            "member_id" => $this->member_id,
            "id" => $roamrow['view_id']
        );
        $viewrow = M("Pano_view")->where($viewwhere)->find();

        $view_id = $viewrow['id'];

        M("Pano_vpoint")->where($roamwhere)->delete();
        $this->redirect("view", array("view_id" => $view_id));
    }

}

?>
