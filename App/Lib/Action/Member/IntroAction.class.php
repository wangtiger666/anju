<?php

class IntroAction extends MemberAction {
    public function index() {
        $pano_id = I("get.pano_id");
        $this->assign('pano_id', $pano_id);

        $member_id = $this->member_id;
        $this->assign("member_id", $member_id);

        $panorow = D("Pano")->GetOne($pano_id, $this->member_id);
        $this->assign('panorow', $panorow);

        $fileurl = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/store/intro";

        $where = array(
            "member_id" => $member_id,
            "pano_id" => $pano_id
        );

        if (I("post.dopost") == "save") {
            $data = readPost($_POST);
            $data["image"] = ExecUpload($data["image"], $data["old_image"], $fileurl);
            unset($data["old_image"]);

            M("pano_intro")->where($where)->save($data);
            $this->redirect("index",array("pano_id"=>$pano_id));
            exit();
        }

        $row = M("pano_intro")->where($where)->find();
        if(!is_array($row)){
            $row = array();
            $row["member_id"] = $member_id;
            $row["pano_id"] = $pano_id;
            $row["open"] = 0;
            $row["image"] = ExecUpload("/Public/member/images/pano/intro.png", "", $fileurl);
            M("pano_intro")->add($row);
        }
        $this->assign("row", $row);

        $this->display();
    }
}
?>
