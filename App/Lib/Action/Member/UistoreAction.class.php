<?php

class UistoreAction extends MemberAction {

    var $chu=array(
        "click" => "鼠标点击",
        "over" => "鼠标经过",
        "out" => "鼠标移开",
        "down"=>"鼠标按下",
        "up" => "鼠标弹起"
    );

    function index() {
        $ue = D("Ui");
        $list = $ue->GetList();
        $this->assign('list', $list);

        $this->display();
    }

    function sysindex(){
        $ue = D("Sysui");
        $list = $ue->GetList();
        $this->assign('list', $list);
        $this->display();
    }

    function add() {
        $dopost = I('post.dopost');
        if ($dopost == "save") {
            $title = I('post.title');
            $info = I('post.info');
            $devices = I('post.devices');

            $data = array(
                'title' => $title,
                'info' => $info,
                'devices' => $devices,
                "member_id" => $this->member_id
            );
            $id = M("Ui")->add($data);
            $this->redirect("main", array("uid" => $id));

            exit();
        }
        $this->display();
    }

    function main() {
        $uid = I('get.uid');
        $this->assign('uid', $uid);

        cookie("main", __SELF__);

        $ue = D("Ui");
        $row = $ue->GetOne($uid);
        $this->assign('row', $row);

        $uc = D("Uichild");

        $uclist = $uc->getList($uid);
        foreach ($uclist as $k => $v) {
            if ($v['parent'] == "0") {
                $uclist[$k]['pname'] = "顶级";
            } else {
                $myrow = $uc->GetOne($v['parent']);
                $uclist[$k]['pname'] = $myrow['title'];
            }
            if ($v['uitype'] == "image") {
                $uclist[$k]['uitpname'] = "图片";
            } else if ($v['uitype'] == "superimage") {
                $uclist[$k]['uitpname'] = "多状态图片";
            } else if($v['uitype'] == "video"){
                $uclist[$k]['uitpname'] = "视频FLV";
            }
            $acwhere = array(
                "cid" => $v['id']
            );
            $acrow = M("Ui_action")->where($acwhere)->select();
            $uclist[$k]['len'] = count($acrow);
        }
        $this->assign("uclist", $uclist);

        $this->display();
    }

    function main_del(){
        $uid = I('get.uid');
        $this->assign('uid', $uid);

        $F = M("Ui_child");
        $where = array(
            "uid" => $uid,
            "member" => $this->member_id
        );
        $F->where($where)->delete();

        $M = M("Ui");
        $delwhere = array(
            "id" => $uid,
            "member" => $this->member_id
        );
        $M->where($delwhere)->delete();

        $this->success("删除成功", $back);
    }

    function uixml() {
        $uid = I("get.uid");

        $uc = D("Uichild");

        $uclist = $uc->getList($uid);
        $this->assign("uclist", $uclist);

        $this->display('./App/Tpl/Member/Uistore/ui.xml', 'utf-8', 'text/xml');
    }

    function child_add() {
        $uid = I('get.uid');
        $this->assign('uid', $uid);

        $back = cookie("main");
        $this->assign('back', $back);
        $this->assign('backurl', $back);

        if (I("post.dopost") == "save") {
            $uid = I('post.uid');
            $title = I('post.title');
            $uitype = I('post.uitype');

            $data = array(
                "member_id" => $this->member_id,
                'uid' => $uid,
                "uitype" => $uitype,
                "title" => $title
            );
            $cid = M("Ui_child")->add($data);
            if ($uitype == "superimage") {
                $this->redirect("set_superimage", array("cid" => $cid));
            } else if ($uitype == "image") {
                $this->redirect("set_image", array("cid" => $cid));
            }else if($uitype == "video"){
                $this->redirect("set_video", array("cid" => $cid));
            }

            exit();
        }

        $ue = D("Ui");
        $row = $ue->GetOne($uid);
        $this->assign('row', $row);

        $this->display();
    }

    function child_del(){
        $back = cookie("main");
        $this->assign('backurl', $back);
        $cid = I("get.cid");

        $F = M("Ui_child");
        $where = array(
            "id" => $cid,
            "member" => $this->member_id
        );
        $pwhere = array(
            "parent" => $cid,
            "member" => $this->member_id
        );
        $F->where($pwhere)->delete();
        $F->where($where)->delete();

        $this->success("删除成功", $back);
    }

    function set_image() {
        $back = cookie("main");
        $this->assign('backurl', $back);
        if (I("post.dopost") == "save") {
            $cid = I('post.cid');
            $title = I('post.title');
            $url = I("post.url");
            $uc = D("Uichild");
            $row = $uc->GetOne($cid);
            $uid = $row['uid'];

            if (substr_count($url, "station") > 0) {
                $file = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/uistore/{$uid}";
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

            M("Ui_child")->where($where)->save($data);
            $this->redirect("position", array("cid" => $cid));
            exit();
        }

        $cid = I('get.cid');
        $this->assign('cid', $cid);

        $uc = D("Uichild");
        $row = $uc->GetOne($cid);
        $this->assign('row', $row);

        $this->display();
    }

    function set_video(){
        $back = cookie("main");
        $this->assign('backurl', $back);
        if (I("post.dopost") == "save") {
            $cid = I('post.cid');
            $title = I('post.title');
            $url = I("post.url");

            $uc = D("Uichild");
            $row = $uc->GetOne($cid);
            $uid = $row['uid'];

            if (substr_count($url, "station") > 0) {
                $file = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/uistore/{$uid}";
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

            M("Ui_child")->where($where)->save($data);
            $this->redirect("position", array("cid" => $cid));
            exit();
        }

        $cid = I('get.cid');
        $this->assign('cid', $cid);

        $uc = D("Uichild");
        $row = $uc->GetOne($cid);
        $this->assign('row', $row);

        $this->display();
    }

    function set_superimage() {
        $back = cookie("main");
        $this->assign('backurl', $back);

        if (I("post.dopost") == "save") {
            $cid = I('post.cid');
            $title = I('post.title');
            $url = I("post.url");
            $crop = I("post.crop");
            $downcrop = I("post.downcrop");
            $hovercrop = I("post.hovercrop");

            $uc = D("Uichild");
            $row = $uc->GetOne($cid);
            $uid = $row['uid'];

            if (substr_count($url, "station") > 0) {
                $file = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/uistore/{$uid}";
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

            M("Ui_child")->where($where)->save($data);
            $this->redirect("position", array("cid" => $cid));
            exit();
        }

        $cid = I('get.cid');
        $this->assign('cid', $cid);

        $uc = D("Uichild");
        $row = $uc->GetOne($cid);
        $this->assign('row', $row);

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

    function position() {
        $back = cookie("main");
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
            M("Ui_child")->where($where)->save($data);
            $this->success("设置成功！", $back);
            exit();
        }

        $cid = I('get.cid');
        $this->assign('cid', $cid);

        $uc = D("Uichild");
        $row = $uc->GetOne($cid);
        $this->assign('row', $row);
        $uclist = $uc->getList($row['uid']);
        $this->assign("uclist", $uclist);

        $ue = D("Ui");
        $uirow = $ue->GetOne($row['uid']);
        $this->assign("uirow", $uirow);

        $this->display();
    }

    function positionxml() {
        $cid = I('get.cid');
        $this->assign('cid', $cid);

        $back = cookie("main");
        $this->assign('backurl', $back);

        $uc = D("Uichild");
        $row = $uc->GetOne($cid);
        $this->assign('row', $row);

        $uclist = $uc->getList($row['uid']);
        $this->assign("uclist", $uclist);

        $this->display('./App/Tpl/Member/Uistore/position.xml', 'utf-8', 'text/xml');
    }

    function uiaction(){
        $back = cookie("main");
        $this->assign('backurl', $back);

        cookie("action", __SELF__);

        $cid = I('get.cid');
        $this->assign('cid', $cid);

        $uc = D("Uichild");
        $row = $uc->GetOne($cid);
        $this->assign('row', $row);

        $ue = D("Ui");
        $uirow = $ue->GetOne($row['uid']);
        $this->assign('uirow', $uirow);

        $ua = D("Uaction");
        $elist = $ua->GetList($cid);
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
            $aid = M("Ui_action")->add($data);
            $this->redirect("action_do",array("aid"=>$aid));
            exit();
        }

        $cid = I('get.cid');
        $this->assign('cid', $cid);

        $uc = D("Uichild");
        $row = $uc->GetOne($cid);
        $this->assign('row', $row);

        $this->display();
    }

    function uiactiondel(){
        $id = I('get.id');

        $back = cookie("action");
        $where = array(
            "id" => $id,
            "member_id" => $this->member_id
        );
        M("Ui_action")->where($where)->delete();
        redirect($back);
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
			else if($action_type == "linkself"){
                $actiondo = $eventname."('$data1','_self')";
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

            M("Ui_action")->where($where)->save($data);
            $this->success("添加完成！", $back);
            exit();
        }

        $id = I('get.id');
        $this->assign("aid", $id);

        $where = array(
            "id" => $id,
            "member_id" => $this->member_id
        );
        $row = M("Ui_action")->where($where)->find();
        $this->assign("row", $row);
        $cid = $row['cid'];
        $uc = D("Uichild");
        $crow = $uc->GetOne($cid);
        $this->assign('crow', $crow);

        $this->display($row['action_type']."_edit");
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
			else if($action_type == "linkself"){
                $actiondo = $eventname."('$data1','_self')";
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

            M("Ui_action")->where($where)->save($data);
            $this->success("添加完成！", $back);
            exit();
        }

        $aid = I('get.aid');
        $this->assign('aid', $aid);

        $where = array(
            "id" => $aid,
            "member_id" => $this->member_id
        );
        $row = M("Ui_action")->where($where)->find();
        $this->assign('row', $row);
        $cid = $row['cid'];
        $uc = D("Uichild");
        $crow = $uc->GetOne($cid);
        $this->assign('crow', $crow);

        $this->display($row['action_type']."_creat");
    }
}

?>
