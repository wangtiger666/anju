<?php

class LuopanAction extends MemberAction {

    public function index() {
        cookie("backurl", __SELF__);

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
            $vspotrow = M("Pano_luopan")->where($lwhere)->find();
            if(is_array($vspotrow)){
                $oo = $vspotrow["open"];
            }else{
                $oo = 0;
            }
            $viewlist[$k]["len"] = $oo>0?"开":"关";
        }
        $this->assign('viewlist', $viewlist);

        $this->display();
    }

    public function handle() {
        $backurl = cookie("backurl");
        $this->assign('backurl', $backurl);
        $action = I("get.action");

        $this->assign("member_id", $this->member_id);

        $os = (DIRECTORY_SEPARATOR == '\\') ? "windows" : 'linux';
        $this->assign('os', $os);

        $view_id = I("get.view_id");
        $this->assign("view_id", $view_id);
        $viewrow = D("Panoview")->getOne($view_id, $this->member_id);
        $this->assign("viewrow", $viewrow);
        $panorow = D("Pano")->getOne($viewrow['pano_id'], $this->member_id);
        $this->assign("panorow", $panorow);
        $this->assign("pano_id", $viewrow['pano_id']);

        $lprow = M("Pano_luopan")->where(array("view_id" => $view_id))->find();

        if (I("post.dopost") == "save") {
            $data = readPost($_POST);

            $fileurl = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/{$viewrow['pano_id']}/toolbox/luopan";
            $data["image"] = ExecUpload($data["image"], $data["old_image"], $fileurl);
            unset($data["old_image"]);

            if (!is_array($lprow)) {
                $id = M("Pano_luopan")->add($data);
                $this->redirect($backurl);
                exit();
            } else {
                M("Pano_luopan")->where(array("view_id" => $view_id))->save($data);
                $this->redirect($backurl);
                exit();
            }
        }

        if (!is_array($lprow)) {
            $row = array(
                "open" => 0
            );
            $this->assign("row", $row);
        } else {
            $this->assign("row", $lprow);
        }

        $this->display();
    }

}

?>
