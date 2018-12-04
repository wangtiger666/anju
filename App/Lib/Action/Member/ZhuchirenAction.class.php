<?php

class ZhuchirenAction extends MemberAction {
    public function index() {
        $pano_id = I("get.pano_id");
        $this->assign('pano_id', $pano_id);

        $member_id = $this->member_id;
        $this->assign("member_id", $member_id);

        $panorow = D("Pano")->GetOne($pano_id, $this->member_id);
        $this->assign('panorow', $panorow);

        $fileurl = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/toolbox/video";

        $where = array(
            "member_id" => $member_id,
            "pano_id" => $pano_id
        );

        if (I("post.dopost") == "save") {
            $data = readPost($_POST);
            $data["file"] = ExecUpload($data["file"], $data["old_file"], $fileurl);
            unset($data["old_file"]);
            M("pano_zhuchiren")->where($where)->save($data);            
			$this->success("保存成功！", U("index", array("pano_id" => $pano_id)));
			//$this->redirect("index",array("pano_id"=>$pano_id));
            exit();
        }

        $row = M("pano_zhuchiren")->where($where)->find();
        if(!is_array($row)){
            $row = array();
            $row["member_id"] = $member_id;
            $row["pano_id"] = $pano_id;
            $row["open"] = 0;
            $row["file"] = "";
            $row["position"] = "left";
            M("pano_zhuchiren")->add($row);
        }
        $this->assign("row", $row);
        $this->display();
    }
}
?>
