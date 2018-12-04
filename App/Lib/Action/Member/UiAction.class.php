<?php

class UiAction extends MemberAction {
    var $chu=array(
        "click" => "鼠标点击",
        "over" => "鼠标经过",
        "out" => "鼠标移开",
        "down"=>"鼠标按下",
        "up" => "鼠标弹起"
    );

    public function index($pano_id) {
        cookie("back", __SELF__);
        $this->pano_id = $pano_id;
        $panorow = D("Pano")->GetOne($pano_id, $this->member_id);
        $this->panorow = $panorow;

        $where = array(
            "member_id" => $this->member_id,
            "pano_id" => $pano_id,
            "utp" => 2
        );
        $sysrow = M("Pano_ui")->where($where)->select();
        foreach ($sysrow as $k => $v) {
            if ($v['utp'] == 1) {
                $ue = D("Ui");
                $urow = $ue->GetOne($v['uid']);
            } else {
                $ue = D("Sysui");
                $urow = $ue->GetOne($v['uid']);
            }
            $sysrow[$k]["title"] = $urow['title'];
            $sysrow[$k]["info"] = $urow['info'];
			$sysrow[$k]["uipic"] = $urow['uipic'];
        }
        $this->assign("sysrow", $sysrow);

        $where = array(
            "member_id" => $this->member_id,
            "pano_id" => $pano_id,
            "utp" => 1
        );
        $pcrow = M("Pano_ui")->where($where)->select();
        foreach ($pcrow as $k => $v) {
            if ($v['utp'] == 1) {
                $ue = D("Ui");
                $urow = $ue->GetOne($v['uid']);
            } else {
                $ue = D("Sysui");
                $urow = $ue->GetOne($v['uid']);
            }
            $pcrow[$k]["title"] = $urow['title'];
            $pcrow[$k]["info"] = $urow['info'];
        }
        $this->assign("pcrow", $pcrow);

        $mbrow = M("Pano_uimobie")->where($where)->select();
        foreach ($mbrow as $k => $v) {
            if ($v['utp'] == 1) {
                $ue = D("Ui");
                $urow = $ue->GetOne($v['uid']);
            } else {
                $ue = D("Sysui");
                $urow = $ue->GetOne($v['uid']);
            }
            $mbrow[$k]["title"] = $urow['title'];
            $mbrow[$k]["info"] = $urow['info'];
        }
        $this->assign("mbrow", $mbrow);

        $this->display();
    }

    function uiadd() {
        $pano_id = I("post.pano_id");
        $uid = I("post.uid");
        $utp = I("post.utp");

        $where = array(
            "member_id" => $this->member_id,
            "pano_id" => $pano_id,
            "uid" => $uid,
            "utp" => $utp
        );
        $row = M("Pano_ui")->where($where)->find();
        if (is_array($row)) {
            $r = array(
                "error" => 1
            );
        } else {
            $data = array(
                "member_id" => $this->member_id,
                "pano_id" => $pano_id,
                "uid" => $uid,
                "utp" => $utp
            );
            $new_id = M("Pano_ui")->add($data);
            if ($utp == 1) {
                $ue = D("Ui");
                $urow = $ue->GetOne($uid);
            } else {
                $ue = D("Sysui");
                $urow = $ue->GetOne($uid);
            }
            $r = array(
                "error" => 0,
                "id" => $new_id,
                "title" => $urow["title"],
                "info" => $urow["info"],
				"uipic" => $urow["uipic"]
            );
        }
        echo json_encode($r);
    }

    function mbuiadd() {
        $pano_id = I("post.pano_id");
        $uid = I("post.uid");
        $utp = I("post.utp");

        $where = array(
            "member_id" => $this->member_id,
            "pano_id" => $pano_id,
            "uid" => $uid,
            "utp" => $utp
        );
        $row = M("Pano_uimobie")->where($where)->find();
        if (is_array($row)) {
            $r = array(
                "error" => 1
            );
        } else {
            $data = array(
                "member_id" => $this->member_id,
                "pano_id" => $pano_id,
                "uid" => $uid,
                "utp" => $utp
            );
            $new_id = M("Pano_uimobie")->add($data);
            if ($utp == 1) {
                $ue = D("Ui");
                $urow = $ue->GetOne($uid);
            } else {
                $ue = D("Sysui");
                $urow = $ue->GetOne($uid);
            }
            $r = array(
                "error" => 0,
                "id" => $new_id,
                "title" => $urow["title"],
                "info" => $urow["info"]
            );
        }
        echo json_encode($r);
    }

    function uidel($id) {
        $where = array(
            "member_id" => $this->member_id,
            "id" => $id
        );
        M("Pano_ui")->where($where)->delete();
        echo "$('#pcui_$id').remove();";
    }

    function mbuidel($id) {
        $where = array(
            "member_id" => $this->member_id,
            "id" => $id
        );
        M("Pano_uimobie")->where($where)->delete();
        echo "$('#mbui_$id').remove();";
    }

    function view() {
        cookie("back", __SELF__);

        $pano_id = I("get.pano_id");
        $this->assign('pano_id', $pano_id);

        $panorow = D("Pano")->GetOne($pano_id, $this->member_id);
        $this->assign('panorow', $panorow);

        $viewlist = D("Panoview")->GetList($pano_id, $this->member_id);
        foreach ($viewlist as $key => $value) {
            $vid = $value['id'];
            $cwhere = array(
                "view_id" => $vid
            );
            $vrow = M("Pano_ui_paths")->where($cwhere)->select();
            $viewlist[$key]['len'] = count($vrow);
        }
        $this->assign('viewlist', $viewlist);

        $this->display();
    }

    function uimain() {
        $backurl = cookie("back");
        $this->assign('backurl', $backurl);

        cookie("uiback", __SELF__);

        $view_id = I("get.view_id");
        $this->assign("view_id", $view_id);

        $viewrow = D("Panoview")->getOne($view_id, $this->member_id);
        $this->assign("viewrow", $viewrow);

        $panorow = D("Pano")->getOne($viewrow['pano_id'], $this->member_id);
        $this->assign("panorow", $panorow);
        $this->assign("pano_id", $viewrow['pano_id']);

        $devices = I("get.devices");
        $this->assign("devices", $devices);
        if ($devices == "") {
            $this->redirect("uimain", array("view_id" => $view_id, "devices" => "flash"));
        }

        cookie("devices", $devices);

        $devices = cookie("devices");
        $ucwhere = array(
            "view_id" => $view_id,
            "member_id" => $this->member_id,
            "devices" => $devices
        );
        $uclist = M("Pano_ui_paths")->where($ucwhere)->select();
        foreach ($uclist as $k => $v) {
            if ($v['parent'] == "0") {
                $uclist[$k]['pname'] = "顶级";
            } else {
                $vcwhere = array(
                    "id" => $v['parent'],
                    "member_id" => $this->member_id
                );
                $myrow = M("Pano_ui_paths")->where($vcwhere)->find();
                $uclist[$k]['pname'] = $myrow['title'];
            }
            if ($v['uitype'] == "image") {
                $uclist[$k]['uitpname'] = "图片";
            } else if ($v['uitype'] == "superimage") {
                $uclist[$k]['uitpname'] = "多状态图片";
            } else if ($v['uitype'] == "video") {
                $uclist[$k]['uitpname'] = "视频FLV";
            }
            $acwhere = array(
                "cid" => $v['id']
            );
            $acrow = M("Pano_ui_action")->where($acwhere)->select();
            $uclist[$k]['len'] = count($acrow);
        }
        $this->assign("uclist", $uclist);

        $this->display();
    }

    function uixml() {
        $view_id = I("get.view_id");
        $this->assign("view_id", $view_id);

        $viewrow = D("Panoview")->getOne($view_id, $this->member_id);
        $this->assign("viewrow", $viewrow);

        $devices = cookie("devices");

        $ucwhere = array(
            "view_id" => $view_id,
            "member_id" => $this->member_id,
            "devices" => $devices
        );
        $uclist = M("Pano_ui_paths")->where($ucwhere)->select();
        $this->assign("uclist", $uclist);

        $this->display('./App/Tpl/Member/Ui/ui.xml', 'utf-8', 'text/xml');
    }

    function child_add() {
        $backurl = cookie("uiback");
        $this->assign('backurl', $backurl);

        if (I("post.dopost") == "save") {
            $view_id = I('post.view_id');
            $title = I('post.title');
            $uitype = I('post.uitype');
            $devices = I("post.devices");
            cookie("devices", $devices);
            $data = array(
                "member_id" => $this->member_id,
                'view_id' => $view_id,
                'devices' => $devices,
                "uitype" => $uitype,
                "title" => $title
            );

            $cid = M("Pano_ui_paths")->add($data);
            if ($uitype == "superimage") {
                $this->redirect("set_superimage", array("cid" => $cid));
            } else if ($uitype == "image") {
                $this->redirect("set_image", array("cid" => $cid));
            } else if ($uitype == "video") {
                $this->redirect("set_video", array("cid" => $cid));
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

        $devices = cookie("devices");
        $this->assign("devices", $devices);

        $this->display();
    }

    function set_image() {
        $back = cookie("uiback");
        $this->assign('backurl', $back);

        if (I("post.dopost") == "save") {
            $cid = I('post.cid');
            $title = I('post.title');
            $url = I("post.url");

            $vcwhere = array(
                "id" => $cid,
                "member_id" => $this->member_id
            );
            $row = M("Pano_ui_paths")->where($vcwhere)->find();
            $view_id = $row["view_id"];

            $viewrow = D("Panoview")->getOne($view_id, $this->member_id);
            $pano_id = $viewrow["pano_id"];

            if (substr_count($url, "station") > 0) {
                $file = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id . "/" . $viewrow['filedir'] . "/ui";
                createFolder(APP_ROOT . $file);
                if (is_file(APP_ROOT . $url)) {
                    $filename = basename($url);
                    $filepath = $file . "/" . $filename;
                    rename(APP_ROOT . $url, APP_ROOT . $filepath);
                    $url = $filepath;
                }
            }

            $data = array(
                'title' => $title,
                "url" => $url
            );
            $where = array(
                "member_id" => $this->member_id,
                "id" => $cid
            );

            M("Pano_ui_paths")->where($where)->save($data);
            $this->redirect("position", array("cid" => $cid));
            exit();
        }

        $cid = I('get.cid');
        $this->assign('cid', $cid);

        $vcwhere = array(
            "id" => $cid,
            "member_id" => $this->member_id
        );

        $row = M("Pano_ui_paths")->where($vcwhere)->find();
        $this->assign('row', $row);

        $view_id = $row["view_id"];
        $this->assign("view_id", $view_id);

        $viewrow = D("Panoview")->getOne($view_id, $this->member_id);
        $this->assign("viewrow", $viewrow);

        $panorow = D("Pano")->getOne($viewrow['pano_id'], $this->member_id);
        $this->assign("panorow", $panorow);
        $this->assign("pano_id", $viewrow['pano_id']);

        $this->display();
    }

    function set_superimage() {
        $back = cookie("uiback");
        $this->assign('backurl', $back);

        if (I("post.dopost") == "save") {
            $cid = I('post.cid');
            $title = I('post.title');
            $url = I("post.url");
            $crop = I("post.crop");
            $downcrop = I("post.downcrop");
            $hovercrop = I("post.hovercrop");

            $vcwhere = array(
                "id" => $cid,
                "member_id" => $this->member_id
            );
            $row = M("Pano_ui_paths")->where($vcwhere)->find();
            $view_id = $row["view_id"];

            $viewrow = D("Panoview")->getOne($view_id, $this->member_id);
            $pano_id = $viewrow["pano_id"];

            if (substr_count($url, "station") > 0) {
                $file = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id . "/" . $viewrow['filedir'] . "/ui";
                createFolder(APP_ROOT . $file);
                if (is_file(APP_ROOT . $url)) {
                    $filename = basename($url);
                    $filepath = $file . "/" . $filename;
                    rename(APP_ROOT . $url, APP_ROOT . $filepath);
                    $url = $filepath;
                }
            }

            $data = array(
                'title' => $title,
                "url" => $url,
                "crop" => $crop,
                "hovercrop" => $hovercrop,
                "downcrop" => $downcrop
            );
            $where = array(
                "member_id" => $this->member_id,
                "id" => $cid
            );

            M("Pano_ui_paths")->where($where)->save($data);
            $this->redirect("position", array("cid" => $cid));
            exit();
        }

        $cid = I('get.cid');
        $this->assign('cid', $cid);

        $vcwhere = array(
            "id" => $cid,
            "member_id" => $this->member_id
        );

        $row = M("Pano_ui_paths")->where($vcwhere)->find();
        $this->assign('row', $row);

        $view_id = $row["view_id"];
        $this->assign("view_id", $view_id);

        $viewrow = D("Panoview")->getOne($view_id, $this->member_id);
        $this->assign("viewrow", $viewrow);

        $panorow = D("Pano")->getOne($viewrow['pano_id'], $this->member_id);
        $this->assign("panorow", $panorow);
        $this->assign("pano_id", $viewrow['pano_id']);

        $this->display();
    }

    function set_video(){
        $back = cookie("uiback");
        $this->assign('backurl', $back);
        if (I("post.dopost") == "save") {
            $cid = I('post.cid');
            $title = I('post.title');
            $url = I("post.url");

            $vcwhere = array(
                "id" => $cid,
                "member_id" => $this->member_id
            );
            $row = M("Pano_ui_paths")->where($vcwhere)->find();
            $view_id = $row["view_id"];

            $viewrow = D("Panoview")->getOne($view_id, $this->member_id);
            $pano_id = $viewrow["pano_id"];

            if (substr_count($url, "station") > 0) {
                $file = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id . "/" . $viewrow['filedir'] . "/ui";
                createFolder(APP_ROOT . $file);
                if (is_file(APP_ROOT . $url)) {
                    $filename = basename($url);
                    $filepath = $file . "/" . $filename;
                    rename(APP_ROOT . $url, APP_ROOT . $filepath);
                    $url = $filepath;
                }
            }

            $data = array(
                'title' => $title,
                "url" => $url
            );
            $where = array(
                "member_id" => $this->member_id,
                "id" => $cid
            );

            M("Pano_ui_paths")->where($where)->save($data);
            $this->redirect("position", array("cid" => $cid));
            exit();
        }

        $cid = I('get.cid');
        $this->assign('cid', $cid);

        $vcwhere = array(
            "id" => $cid,
            "member_id" => $this->member_id
        );

        $row = M("Pano_ui_paths")->where($vcwhere)->find();
        $this->assign('row', $row);

        $view_id = $row["view_id"];
        $this->assign("view_id", $view_id);

        $viewrow = D("Panoview")->getOne($view_id, $this->member_id);
        $this->assign("viewrow", $viewrow);

        $panorow = D("Pano")->getOne($viewrow['pano_id'], $this->member_id);
        $this->assign("panorow", $panorow);
        $this->assign("pano_id", $viewrow['pano_id']);

        $this->display();
    }

    function crop() {
        $img = I("get.img");
        $crop = I("get.crop");
        $w = I("get.w");
        $h = I("get.h");
        $this->assign('w', $w);
        $this->assign('h', $h);
        $this->assign('img', $img);
        $this->assign('crop', $crop);

        $cc = explode("|", $crop);
        $this->assign('cc_x', $cc[0]);
        $this->assign('cc_y', $cc[1]);
        $this->assign('cc_w', $cc[2]);
        $this->assign('cc_h', $cc[3]);
        $this->display();
    }

    function child_del(){
        $back = cookie("uiback");
        $this->assign('backurl', $back);
        $cid = I("get.cid");

        $F = M("Pano_ui_paths");
        $where = array(
            "id" => $cid,
            "member" => $this->member_id
        );
        $arow = M("Pano_ui_paths")->where($where)->find();
        if(is_file(APP_ROOT.$arow['url'])){
            unlink(APP_ROOT.$arow['url']);
        }
        $pwhere = array(
            "parent" => $cid,
            "member" => $this->member_id
        );
        if(is_file(APP_ROOT.$brow['url'])){
            unlink(APP_ROOT.$brow['url']);
        }
        $brow = M("Pano_ui_paths")->where($pwhere)->find();

        $F->where($pwhere)->delete();
        $F->where($where)->delete();

        $this->success("删除成功", $back);
    }

    function position() {
        $back = cookie("uiback");
        $this->assign('backurl', $back);

        if (I("post.dopost") == "save") {
            $cid = I('post.cid');
            $align = I('post.align');
            $x = I('post.x');
            $y = I('post.y');
            $scale = I('post.scale') / 100;
            $alpha = I('post.alpha') / 100;
            $rotate = I('post.rotate');
            $edge = I('post.edge');
            $zorder = I('post.zorder');
            $parent = I('post.parent');

            $where = array(
                "member_id" => $this->member_id,
                "id" => $cid
            );
            $data = array(
                "align" => $align,
                "x" => $x,
                "y" => $y,
                "scale" => $scale,
                "alpha" => $alpha,
                "rotate" => $rotate,
                "zorder" => $zorder,
                'parent' => $parent,
                "edge" => $edge
            );
            M("Pano_ui_paths")->where($where)->save($data);
            $this->success("设置成功！", $back);
            exit();
        }

        $cid = I('get.cid');
        $this->assign('cid', $cid);

        $cwhere = array(
            "id" => $cid,
            "member_id" => $this->member_id
        );
        $row = M("Pano_ui_paths")->where($cwhere)->find();
        $this->assign('row', $row);

        $view_id = $row["view_id"];
        $this->assign("view_id", $view_id);

        $viewrow = D("Panoview")->getOne($view_id, $this->member_id);
        $this->assign("viewrow", $viewrow);

        $panorow = D("Pano")->getOne($viewrow['pano_id'], $this->member_id);
        $this->assign("panorow", $panorow);
        $this->assign("pano_id", $viewrow['pano_id']);

        $ucwhere = array(
            "view_id" => $view_id,
            "member_id" => $this->member_id
        );
        $uclist = M("Pano_ui_paths")->where($ucwhere)->select();
        $this->assign("uclist", $uclist);

        $this->display();
    }

    function positionxml() {
        $cid = I('get.cid');
        $this->assign('cid', $cid);

        $cwhere = array(
            "id" => $cid,
            "member_id" => $this->member_id
        );
        $row = M("Pano_ui_paths")->where($cwhere)->find();
        $this->assign('row', $row);

        $view_id = $row["view_id"];
        $ucwhere = array(
            "view_id" => $view_id,
            "member_id" => $this->member_id
        );
        $uclist = M("Pano_ui_paths")->where($ucwhere)->select();
        $this->assign("uclist", $uclist);

        $viewwhere = array(
            "id" => $view_id
        );
        $viewrow = M("Pano_view")->where($viewwhere)->find();
        $this->assign("viewrow", $viewrow);

        $this->display('./App/Tpl/Member/Ui/position.xml', 'utf-8', 'text/xml');
    }

    function uiac(){
        $back = cookie("uiback");
        $this->assign('backurl', $back);

        cookie("action", __SELF__);

        $cid = I('get.cid');
        $this->assign('cid', $cid);

        $vcwhere = array(
            "id" => $cid,
            "member_id" => $this->member_id
        );

        $row = M("Pano_ui_paths")->where($vcwhere)->find();
        $this->assign('row', $row);
        $this->assign('devices', $row['devices']);

        $view_id = $row["view_id"];
        $this->assign("view_id", $view_id);

        $viewrow = D("Panoview")->getOne($view_id, $this->member_id);
        $this->assign("viewrow", $viewrow);

        $panorow = D("Pano")->getOne($viewrow['pano_id'], $this->member_id);
        $this->assign("panorow", $panorow);
        $this->assign("pano_id", $viewrow['pano_id']);

        $ewhere = array(
            "cid" => $cid,
            "member_id" => $this->member_id
        );
        $elist = M("Pano_ui_action")->where($ewhere)->select();
        foreach ($elist as $k => $arr) {
            $fix = $arr['eventchu'];
            $elist[$k]['chuname'] = $this->chu[$fix];;
        }
        $this->assign('elist', $elist);

//        import('@.Class.UiEvent');
//        $uev = new UiEvent();
//
//        $uelist = $uev->eventlist();
//
//        $this->assign("uelist", $uelist);

        $this->display();
    }

    function action_add(){
        $back = cookie("action");
        $this->assign('backurl', $back);

        if (I("post.dopost") == "save") {
            $cid = I('post.cid');
            $eventchu = I('post.eventchu');
            $event = I('post.event');

            $data = array(
                "member_id" => $this->member_id,
                'cid' => $cid,
                "action_type" => $event,
                "eventchu" => $eventchu
            );
            $aid = M("Pano_ui_action")->add($data);
            $this->redirect("action_do",array("aid"=>$aid));
            exit();
        }

        $cid = I('get.cid');
        $this->assign('cid', $cid);

        $vcwhere = array(
            "id" => $cid,
            "member_id" => $this->member_id
        );

        $row = M("Pano_ui_paths")->where($vcwhere)->find();
        $this->assign('row', $row);
        $this->assign('devices', $row['devices']);

        $view_id = $row["view_id"];
        $this->assign("view_id", $view_id);

        $viewrow = D("Panoview")->getOne($view_id, $this->member_id);
        $this->assign("viewrow", $viewrow);

        $panorow = D("Pano")->getOne($viewrow['pano_id'], $this->member_id);
        $this->assign("panorow", $panorow);
        $this->assign("pano_id", $viewrow['pano_id']);

        $this->display();
    }

    function action_do(){
        $back = cookie("action");
        $this->assign('backurl', $back);
        $this->assign('back', $back);

        if(I("post.dopost") == "save"){
            $aid = I('post.aid');
            $action_type = I('post.action_type');
            $eventname = I('post.eventname');
            $data1 = I('post.data1');
            $data2 = I('post.data2');
            $data3 = I('post.data3');
            $data4 = I('post.data4');
            $info = I('post.info');

            if($action_type == "view"){
                $actiondo = $eventname."($data1,$data2)";
            }else if($action_type == "link"){
                $actiondo = $eventname."($data1)";
            }

            $where = array(
                "id" => $aid,
                "member_id" => $this->member_id
            );
            $data = array(
                'eventname' => $eventname,
                'data1' => $data1,
                'data2' => $data2,
                'data3' => $data3,
                'data4' => $data4,
                'actiondo' => $actiondo,
                'action_info' => $info
            );

            M("pano_ui_action")->where($where)->save($data);
            $this->success("添加完成！", $back);
            exit();
        }

        $aid = I('get.aid');
        $this->assign('aid', $aid);

        $where = array(
            "id" => $aid,
            "member_id" => $this->member_id
        );
        $row = M("Pano_ui_action")->where($where)->find();
        $this->assign('row', $row);
        $cid = $row['cid'];

        $vcwhere = array(
            "id" => $cid,
            "member_id" => $this->member_id
        );

        $crow = M("Pano_ui_paths")->where($vcwhere)->find();
        $this->assign('crow', $crow);
        $this->assign('devices', $crow['devices']);

        $view_id = $crow["view_id"];
        $this->assign("view_id", $view_id);

        $viewrow = D("Panoview")->getOne($view_id, $this->member_id);
        $this->assign("viewrow", $viewrow);

        $panorow = D("Pano")->getOne($viewrow['pano_id'], $this->member_id);
        $this->assign("panorow", $panorow);
        $this->assign("pano_id", $viewrow['pano_id']);

        $this->display($row['action_type']."_creat");
    }

    function uiactionedit(){
        $back = cookie("action");
        $this->assign("back", $back);
        if(I("post.dopost") == "save"){
            $aid = I('post.aid');
            $action_type = I('post.action_type');
            $eventname = I('post.eventname');
            $data1 = I('post.data1');
            $data2 = I('post.data2');
            $data3 = I('post.data3');
            $data4 = I('post.data4');
            $info = I('post.info');

            if($action_type == "view"){
                $actiondo = $eventname."($data1,$data2)";
            }else if($action_type == "link"){
                $actiondo = $eventname."($data1)";
            }

            $where = array(
                "id" => $aid,
                "member_id" => $this->member_id
            );
            $data = array(
                'eventname' => $eventname,
                'data1' => $data1,
                'data2' => $data2,
                'data3' => $data3,
                'data4' => $data4,
                'actiondo' => $actiondo,
                'action_info' => $info
            );

            M("Pano_ui_action")->where($where)->save($data);
            $this->success("添加完成！", $back);
            exit();
        }

        $aid = I('get.id');
        $this->assign('aid', $aid);

        $where = array(
            "id" => $aid,
            "member_id" => $this->member_id
        );
        $row = M("Pano_ui_action")->where($where)->find();
        $this->assign('row', $row);
        $cid = $row['cid'];

        $vcwhere = array(
            "id" => $cid,
            "member_id" => $this->member_id
        );

        $crow = M("Pano_ui_paths")->where($vcwhere)->find();
        $this->assign('crow', $crow);
        $this->assign('devices', $crow['devices']);

        $view_id = $crow["view_id"];
        $this->assign("view_id", $view_id);

        $viewrow = D("Panoview")->getOne($view_id, $this->member_id);
        $this->assign("viewrow", $viewrow);

        $panorow = D("Pano")->getOne($viewrow['pano_id'], $this->member_id);
        $this->assign("panorow", $panorow);
        $this->assign("pano_id", $viewrow['pano_id']);

        $this->display($row['action_type']."_edit");
    }

    function uiactiondel(){
        $id = I('get.id');

        $back = cookie("action");
        $where = array(
            "id" => $id,
            "member_id" => $this->member_id
        );
        M("Pano_ui_action")->where($where)->delete();
        redirect($back);
    }
}

?>
