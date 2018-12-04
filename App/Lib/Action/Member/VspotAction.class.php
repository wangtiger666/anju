<?php
class VspotAction extends MemberAction {
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
            $vspotrow = M("Pano_vspot")->where($lwhere)->select();
            $viewlist[$k]["len"] = count($vspotrow);
        }
        $this->assign('viewlist', $viewlist);

        $this->display();
    }

    function view(){
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
        $vspotrow = M("Pano_vspot")->where($lwhere)->select();
        foreach ($vspotrow as $k => $hrow) {
            if ($hrow["action"] == 1) {
                $vspotrow[$k]["action"] = "暂停/播放";
            } else if ($hrow["action"] == 2) {
                $vspotrow[$k]["action"] = "飞出/飞回";
            }
        }
        $this->assign("hotployrow", $vspotrow);

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

            $fileurl = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/{$viewrow['pano_id']}/toolbox/video";
            $data["file"] = ExecUpload($data["file"], $data["old_file"], $fileurl);
            unset($data["old_file"]);
            $data["applefile"] = ExecUpload($data["applefile"], $data["old_applefile"], $fileurl);
            unset($data["old_applefile"]);
            $data["appleimg"] = ExecUpload($data["appleimg"], $data["old_appleimg"], $fileurl);
            unset($data["old_appleimg"]);

            switch ($action) {
                case "add":
                    $id = M("Pano_vspot")->add($data);
                    $this->redirect($backurl);
                    exit();
                    break;
                case "edit":
                    $id = I("get.id");
                    M("Pano_vspot")->where(array("id" => $id))->save($data);
                    $this->redirect($backurl);
                    exit();
                    break;
            }
        }

        switch ($action) {
            case "add":
                $row = array(
                    "ath" => 0,
                    "atv" => 0,
                    "rx" => 0,
                    "ry" => 0,
                    "rz" => 0,
                    "scale" => 1,
					"width" => 300,
					"height" => 220,
                    "action" => 1
                );
                $this->assign("row", $row);
                break;
            case "edit":
                $id = I("get.id");
                $row = M("Pano_vspot")->where(array("id" => $id))->find();
                $this->assign("row", $row);
                break;
            case "del":
                $id = I("get.id");
                $row = M("Pano_vspot")->where(array("id" => $id))->find();
                @unlink(APP_ROOT . $row["file"]);
                @unlink(APP_ROOT . $row["applefile"]);
                @unlink(APP_ROOT . $row["appleimg"]);
                M("Pano_vspot")->where(array("id" => $id))->delete();
                $this->redirect($backurl);
                exit();
                break;
        }
        $this->display();
    }

    function vset(){
        $view_id = I("get.view_id");
        $flv = I("get.flv");
        $ath = I("get.ath");
        $atv = I("get.atv");
        $rx = I("get.rx");
        $ry = I("get.ry");
        $rz = I("get.rz");
        $scale = I("get.scale");
		$width = I("get.width");
		$height = I("get.height");
        $this->assign("data", $_GET);

        $xmlurl = UOne('vsetxml', array('view_id' => $view_id, "flv" => $flv , "ath"=>$ath , "atv" => $atv , "rx" => $rx , "ry" => $ry, "rz" => $rz, "width" => $width, "height" => $height,"scale" => $scale));
        $xmlscript = "embedpano({swf:\"__PUBLIC__/pano/pano.swf\", xml:\"$xmlurl\",id:\"krpano\", target:\"pano\", html5:\"prefer\", passQueryParameters:true,wmode:\"transparent\"});";
        $this->assign('xmlscript', $xmlscript);

        $this->display();
    }

    function vsetxml(){
        $view_id = I("get.view_id");
        $viewwhere = array(
            "id" => $view_id
        );
        $viewrow = M("Pano_view")->where($viewwhere)->find();
        $this->assign("viewrow", $viewrow);

        $this->assign("data", $_GET);

        $this->display('./App/Tpl/Member/Vspot/vset.xml', 'utf-8', 'text/xml');
    }
}
?>
