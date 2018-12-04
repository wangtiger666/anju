<?php

class HotpolyAction extends MemberAction {

    public function index() {
        cookie("polyback", __SELF__);

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
            $hotployrow = M("Pano_hotploy")->where($lwhere)->select();
            $viewlist[$k]["len"] = count($hotployrow);
        }
        $this->assign('viewlist', $viewlist);

        $this->display();
    }

    public function ployview() {
        $backurl = cookie("polyback");
        $this->assign('backurl', $backurl);
        cookie("backurl", __SELF__);

        $view_id = I("get.view_id");
        $this->assign("view_id", $view_id);

        $viewrow = D("Panoview")->getOne($view_id, $this->member_id);
        $this->assign("viewrow", $viewrow);

        $panorow = D("Pano")->getOne($viewrow['pano_id'], $this->member_id);
        $this->assign("panorow", $panorow);
        $this->assign("pano_id", $viewrow['pano_id']);

        $lwhere = array(
            "view_id" => $view_id,
            "member_id" => $this->member_id
        );
        $hotployrow = M("Pano_hotploy")->where($lwhere)->select();
        foreach ($hotployrow as $k => $hrow) {
            if ($hrow["action_type"] == 1) {
                $scene_id = $hrow["scene_id"];
                $theview = D("Panoview")->getOne($scene_id, $this->member_id);
                $hotployrow[$k]["action"] = "场景" . $theview["sort"];
            } else if ($hrow["action_type"] == 2) {
                $hotployrow[$k]["action"] = "弹出图片";
            } else if ($hrow["action_type"] == 3) {
                $hotployrow[$k]["action"] = "弹出图文";
            } else if ($hrow["action_type"] == 4) {
                $hotployrow[$k]["action"] = "弹出图集";
            }
        }
        $this->assign("hotployrow", $hotployrow);

        $this->display();
    }

    public function handle() {
        $backurl = cookie("backurl");
        $this->assign('backurl', $backurl);
        $action = I("get.action");

        $this->assign("member_id", $this->member_id);

        $view_id = I("get.view_id");
        $this->assign("view_id", $view_id);
        $viewrow = D("Panoview")->getOne($view_id, $this->member_id);
        $this->assign("viewrow", $viewrow);
        $panorow = D("Pano")->getOne($viewrow['pano_id'], $this->member_id);
        $this->assign("panorow", $panorow);
        $this->assign("pano_id", $viewrow['pano_id']);

        if (I("post.dopost") == "save") {
            $data = readPost($_POST);

            $fileurl = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/store/photo";
            $data["photo1"] = ExecUpload($data["photo1"], $data["old_photo1"], $fileurl);
            unset($data["old_photo1"]);
            $data["photo2"] = ExecUpload($data["photo2"], $data["old_photo2"], $fileurl);
            unset($data["old_photo2"]);

            unset($data["tupian_url"]);
            unset($data["tupian_id"]);
            unset($data["tupian_text"]);

            switch ($action) {
                case "add":
                    $id = M("Pano_hotploy")->add($data);
                    savePhotoStore($_POST["tupian_url"], $_POST["tupian_id"], $_POST["tupian_text"], $this->member_id, $viewrow['pano_id'], "hotploy", $id);
                    $this->redirect($backurl);
                    exit();
                    break;
                case "edit":
                    $id = I("get.id");
                    M("Pano_hotploy")->where(array("id" => $id))->save($data);
                    savePhotoStore($_POST["tupian_url"], $_POST["tupian_id"], $_POST["tupian_text"], $this->member_id, $viewrow['pano_id'], "hotploy", $id);
                    $this->redirect($backurl);
                    exit();
                    break;
            }
        }


        $vlist = D("Panoview")->GetList($viewrow['pano_id'], $this->member_id);
        $this->assign("vlist", $vlist);



        switch ($action) {
            case "add":
                $row = array(
                    "borderwidth" => 1,
                    "borderalpha" => 0.5,
                    "bordercolor" => "#FFFFFF",
                    "borderwidthhover" => 1,
                    "borderalphahover" => 0.5,
                    "bordercolorhover" => "#FFFFFF",
                    "fillcolor" => "#FFFFFF",
                    "fillalpha" => 0.2,
                    "fillcolorhover" => "#FFFFFF",
                    "fillalphahover" => 0.4,
                    "action_type" => 1,
                    "scene_sort" => "空",
                    "scene_name" => "空",
                    "polycount" => 0,
                    "textbox_width" => 820,
                    "textbox_height" => 540,
                    "photobox_width" => 200
                );
                $this->assign("row", $row);
                $photoarr = array();
                $this->assign("photoarr", $photoarr);
                break;
            case "edit":
                $id = I("get.id");
                $row = M("Pano_hotploy")->where(array("id" => $id))->find();
                if ($row["scene_id"] != "") {
                    $theview = D("Panoview")->getOne($row["scene_id"], $this->member_id);
                    $row["scene_sort"] = "场景" . $theview['sort'];
                    $row["scene_name"] = $theview['title'];
                }
                $row["polycount"] = count(explode("$", $row["ploydata"]));
                $this->assign("row", $row);
                $photoarr = M("Imagestore")->where(array("pano_id" => $viewrow["pano_id"], "type" => "hotploy", "from_id" => $id))->order("sort")->select();
                $this->assign("photoarr", $photoarr);
                break;
            case "del":
                $id = I("get.id");
                $row = M("Pano_hotploy")->where(array("id" => $id))->find();
                @unlink(APP_ROOT . $row["photo1"]);
                @unlink(APP_ROOT . $row["photo2"]);
                $photoarr = M("Imagestore")->where(array("pano_id" => $viewrow["pano_id"], "type" => "hotploy", "from_id" => $id))->order("sort")->select();
                foreach ($photoarr as $photo) {
                    @unlink(APP_ROOT . $photo["imageurl"]);
                }
                M("Imagestore")->where(array("pano_id" => $viewrow["pano_id"], "type" => "hotploy", "from_id" => $id))->delete();
                M("Pano_hotploy")->where(array("id" => $id))->delete();
                $this->redirect($backurl);
                exit();
                break;
        }

        $this->display();
    }

    function ployset() {
        $view_id = I("get.view_id");
        $ploydata = I("get.ploydata");

        $xmlurl = U('ploysetxml', array('view_id' => $view_id, "ploydata" => $ploydata));
        $xmlscript = "embedpano({swf:\"__PUBLIC__/pano/pano.swf\", xml:\"$xmlurl\", target:\"pano\", html5:\"auto\", passQueryParameters:true});";
        $this->assign('xmlscript', $xmlscript);
        $this->display();
    }

    function ploysetxml() {
        $view_id = I("get.view_id");
        $ploydata = I("get.ploydata");

        $view_id = I("get.view_id");
        $viewwhere = array(
            "id" => $view_id
        );
        $viewrow = M("Pano_view")->where($viewwhere)->find();
        $this->assign("viewrow", $viewrow);
        $ployarr = array();
        if ($ploydata == "") {
            $x = 0;
            $y = 0;
        } else {
            $ploydataarr = explode("$", $ploydata);
            $n = 0;
            $maxx;
            $minx;
            $maxy;
            $miny;
            foreach ($ploydataarr as $k => $ployone) {
                $ployonearr = explode("|", $ployone);
                $ployarr[$k]["x"] = floatval($ployonearr[1]);
                $ployarr[$k]["y"] = floatval($ployonearr[0]);
                if ($ployarr[$k]["x"] >= $maxx || $maxx == "") {
                    $maxx = $ployarr[$k]["x"];
                }
                if ($ployarr[$k]["x"] <= $minx || $minx == "") {
                    $minx = $ployarr[$k]["x"];
                }
                if ($ployarr[$k]["y"] >= $maxy || $maxy == "") {
                    $maxy = $ployarr[$k]["y"];
                }
                if ($ployarr[$k]["y"] <= $miny || $miny == "") {
                    $miny = $ployarr[$k]["y"];
                }
                $n++;
            }
            $x = ($maxx + $minx) / 2;
            $y = ($maxy + $miny) / 2;
        }

        $this->assign("x", $x);
        $this->assign("y", $y);
        $this->assign("ployarr", $ployarr);

        $this->display('./App/Tpl/Member/Hotpoly/ployset.xml', 'utf-8', 'text/xml');
    }

}

?>
