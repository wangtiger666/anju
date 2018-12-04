<?php

class PhotoAction extends MemberAction {

    public function index() {
        cookie("photoback", __SELF__);

        $pano_id = I("get.pano_id");
        $this->assign('pano_id', $pano_id);

        $panorow = D("Pano")->GetOne($pano_id, $this->member_id);
        $this->assign('panorow', $panorow);

        $viewlist = D("Panoview")->GetList($pano_id, $this->member_id);
        foreach ($viewlist as $key => $value) {
            $photolist = D("Photo")->GetList($value['id'], $this->member_id);
            $viewlist[$key]['len'] = count($photolist);
        }
        $this->assign('viewlist', $viewlist);

        $this->display();
    }

    function photoview() {
        $backurl = cookie("photoback");
        $this->assign('backurl', $backurl);

        cookie("back", __SELF__);

        $view_id = I("get.view_id");
        $this->assign("view_id", $view_id);

        $viewrow = D("Panoview")->GetOne($view_id, $this->member_id);
        $this->assign("viewrow", $viewrow);

        $panorow = D("Pano")->GetOne($viewrow['pano_id'], $this->member_id);
        $this->assign("panorow", $panorow);
        $this->assign("pano_id", $viewrow['pano_id']);

        $photolist = D("Photo")->GetList($view_id, $this->member_id);
        foreach ($photolist as $key => $photovalue) {
            if ($photovalue['spot_type'] == 1) {
                $abwhere = array(
                    "id" => $photovalue['spot_id']
                );
                $abrow = M("Spot")->where($abwhere)->find();
                $photolist[$key]["spotphoto"] = $abrow['file'];
            } else if ($photovalue['spot_type'] == 2) {
                $abwhere = array(
                    "id" => $photovalue['spot_id']
                );
                $abrow = M("Spot")->where($abwhere)->find();
                $photolist[$key]["spotphoto"] = $abrow['file'];
                $photolist[$key]["width"] = $abrow['width'];
                $photolist[$key]["height"] = $abrow['height'];
                $photolist[$key]["len"] = $abrow['len'];
                $photolist[$key]["spotspeed"] = $abrow['speed'];
            } else if ($photovalue['spot_type'] == 3) {

            }
            $thewhere = array(
                "member_id" => $this->member_id,
                "photo_id" => $photovalue['id']
            );
            $findrow = M("Pano_photo_store")->where($thewhere)->order("sord")->select();
            $findlen = count($findrow);
            $photolist[$key]["photolen"] = $findlen;
            $findrow = M("Pano_photo_store")->where($thewhere)->order("sord")->limit(0, 6)->select();
            $photolist[$key]["photoarr"] = $findrow;
        }
        $this->assign('photolist', $photolist);
        $fileurl = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/{$viewrow['pano_id']}/photo/";
        $this->assign("fileurl", $fileurl);

        $this->display();
    }

    function photo_add() {
        $backurl = cookie("back");
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
            $photo = I("post.photo");
            $phototitle = I("post.phototitle");

            $spoturl = I('post.spoturl');
            $smartrx = I('post.smartrx');
            $smartry = I('post.smartry');
            $smartrz = I('post.smartrz');

            if ($title == "") {
                $this->error("请写好【热点名称】！");
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

            $pdir = "photo" . time();
            $data = array(
                "title" => $title,
                "member_id" => $this->member_id,
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
                "pdir" => $pdir,
                "ptitle" => $phototitle,
                'spoturl' => $spoturl,
                'rx' => $smartrx,
                'ry' => $smartry,
                'rz' => $smartrz
            );
            $add_id = M("Pano_photo")->add($data);
            if ($add_id) {
                $viewrow = D("Panoview")->GetOne($view_id, $this->member_id);
                $pano_id = $viewrow['pano_id'];
                $filedir = APP_ROOT . "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/{$pano_id}/photo/" . $pdir;
                createFolder($filedir);
                $dex = 0;
                foreach ($photo as $img) {
                    $filename = APP_ROOT . $img;
                    if (is_file($filename)) {
                        $thename = basename($filename);
                        $newfile = $filedir . "/" . $thename;
                        rename($filename, $newfile);
                        $photodata = array(
                            "member_id" => $this->member_id,
                            "photo_id" => $add_id,
                            "file" => $thename,
                            "sord" => $dex
                        );
                        M("Pano_photo_store")->add($photodata);
                        $dex++;
                    }
                }

                $this->success("添加成功！", U("photoview", array("view_id" => $view_id)));
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

    function photo_edit() {
        $backurl = cookie("back");
        $this->assign("backurl", $backurl);

        if (I("post.dopost") == "save") {
            $photo_id = I('post.photo_id');
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

            $photo = I("post.photo");
            $phototitle = I("post.phototitle");
            $delimg = I('post.delimg');

            $spoturl = I('post.spoturl');
            $smartrx = I('post.smartrx');
            $smartry = I('post.smartry');
            $smartrz = I('post.smartrz');

            if ($title == "") {
                $this->error("请写好【热点名称】！");
                exit();
            }

            if ($spot_type == 1) {

            } else if ($spot_type == 2) {
                $spot_id = $movespot_id;
            }else if ($spot_type == 3) {
                if (substr_count($spoturl, "station") > 0) {
                    if (is_file(APP_ROOT . $spoturl)) {
                        $photowhere = array(
                            "member_id" => $this->member_id,
                            "id" => $photo_id
                        );
                        $photorow = M("Pano_photo")->where($photowhere)->find();
                        @unlink(APP_ROOT.$photorow['spoturl']);

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

            $photorow = D("Photo")->GetOne($photo_id, $this->member_id);
            $viewrow = D("Panoview")->GetOne($view_id, $this->member_id);
            $pano_id = $viewrow['pano_id'];

            $delimgarr = explode("|", $delimg);
            foreach ($delimgarr as $delimgdata) {
                $realname = basename($delimgdata);
                $delwhere = array(
                    "member_id" => $this->member_id,
                    "file" => $realname
                );
                @unlink(APP_ROOT . $delimgdata);
                M("Pano_photo_store")->where($delwhere)->delete();
            }

            $filedir = APP_ROOT . "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/{$pano_id}/photo/" . $photorow['pdir'];
            createFolder($filedir);
            $dex = 0;
            foreach ($photo as $img) {
                if (substr_count($img, "station") > 0) {
                    if (is_file(APP_ROOT . $img)) {
                        $realname = basename($img);
                        rename(APP_ROOT . $img, $filedir . "/" . $realname);
                        $photodata = array(
                            "member_id" => $this->member_id,
                            "photo_id" => $photo_id,
                            "file" => $realname,
                            "sord" => $dex
                        );
                        M("Pano_photo_store")->add($photodata);
                        $dex++;
                    }
                } else {
                    $realname = basename($img);
                    $realwhere = array(
                        "member_id" => $this->member_id,
                        "file" => $realname
                    );
                    $readdata = array(
                        "sord" => $dex
                    );
                    M("Pano_photo_store")->where($realwhere)->save($readdata);
                    $dex++;
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
                "ptitle" => $phototitle,
                'spoturl' => $spoturl,
                'rx' => $smartrx,
                'ry' => $smartry,
                'rz' => $smartrz
            );
            $where = array(
                "id" => $photo_id,
                "member_id" => $this->member_id
            );
            M("Pano_photo")->where($where)->save($data);
            $this->success("修改成功！", U("photoview", array("view_id" => $view_id)));

            exit();
        }

        $photo_id = I("get.photo_id");
        $this->assign("photo_id", $photo_id);

        $photorow = D("Photo")->GetOne($photo_id, $this->member_id);
        $this->assign('photorow', $photorow);
        $view_id = $photorow['view_id'];
        $this->assign('view_id', $view_id);

        $viewrow = D("Panoview")->GetOne($view_id, $this->member_id);
        $this->assign('viewrow', $viewrow);
        $pano_id = $viewrow['pano_id'];
        $this->assign('pano_id', $pano_id);

        $panorow = D("Pano")->GetOne($pano_id, $this->member_id);
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

        $spot_type = $photorow['spot_type'];
        if ($spot_type == 1) {
            $spot_id = $photorow["spot_id"];
            $thespotwhere = array(
                "id" => $spot_id
            );
            $thespotrow = M("Spot")->where($thespotwhere)->find();
            $this->assign('spotrow', $thespotrow);
        } else if ($spot_type == 2) {
            $movespot_id = $photorow["spot_id"];
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

        $thewhere = array(
            "member_id" => $this->member_id,
            "photo_id" => $photo_id
        );
        $findrow = M("Pano_photo_store")->where($thewhere)->order("sord")->select();
        $havephoto = "";
        $fileurl = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/{$viewrow['pano_id']}/photo/" . $photorow['pdir'];
        foreach ($findrow as $photodata) {
            if ($havephoto != "") {
                $havephoto .= "|";
            }
            $havephoto .= $fileurl . "/" . $photodata['file'];
        }
        $this->assign('havephoto', $havephoto);

        $this->display();
    }

    function photo_del() {
        $photo_id = I("get.photo_id");

        $photorow = D("Photo")->GetOne($photo_id, $this->member_id);
        $view_id = $photorow['view_id'];

        $viewrow = D("Panoview")->GetOne($view_id, $this->member_id);
        $pano_id = $viewrow['pano_id'];

        $panorow = D("Pano")->GetOne($pano_id, $this->member_id);
        $this->assign('panorow', $panorow);

        $filedir = APP_ROOT . "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/{$pano_id}/photo/" . $photorow['pdir'];
        RemoveDirFiles($filedir);
        $where = array(
            "id" => $photo_id,
            "member_id" => $this->member_id
        );
        M("Pano_photo")->where($where)->delete();
        $pwhere = array(
            "photo_id" => $photo_id,
            "member_id" => $this->member_id
        );
        M("Pano_photo_store")->where($pwhere)->delete();
        $this->success("删除成功！", U("photoview", array("view_id" => $view_id)));
        exit();
    }

}

?>
