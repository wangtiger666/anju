<?php

class BanAction extends MemberAction {

    function index() {
        $pano_id = I("get.pano_id");
        $this->assign('pano_id', $pano_id);

        $panowhere = array(
            "id" => $pano_id,
            "member_id" => $this->member_id
        );
        $panorow = M("Pano")->where($panowhere)->find();
        $this->assign('panorow', $panorow);

        $where = array(
            "pano_id" => $pano_id,
            "member_id" => $this->member_id
        );
        $row = M("Pano_ban")->where($where)->select();
        $this->assign("row", $row);

        $banarr = array(
            "skin_view_normal"=> "切换正常视角",
            "skin_view_fisheye"=> "切换鱼眼视角",
            "skin_view_architectural"=> "切换近距离视角",
            "skin_view_stereographic"=> "切换远距离视角",
            "skin_view_littleplanet"=> "变换小行星",
        );
        $this->assign("banarr", $banarr);

        $this->display();
    }

    function add() {
        if (I("post.dopost") == "save") {
            $pano_id = I("post.pano_id");
            $title = I("post.title");
            $ban_type = I("post.ban_type");
            $link = I("post.link");

            $data = array(
                "pano_id" => $pano_id,
                "member_id" => $this->member_id,
                "title" => $title,
                "type" => $ban_type,
                "link" => $link
            );
            M("Pano_ban")->add($data);
            $this->redirect("index", array("pano_id" => $pano_id));

            exit();
        }

        $pano_id = I("get.pano_id");
        $this->assign('pano_id', $pano_id);

        $panowhere = array(
            "id" => $pano_id,
            "member_id" => $this->member_id
        );
        $panorow = M("Pano")->where($panowhere)->find();
        $this->assign('panorow', $panorow);

        $this->display();
    }

    function del(){
        $pano_id = I("get.pano_id");
        $ban_id = I("get.ban_id");

        $banwhere = array(
            "id" => $ban_id,
            "member_id" => $this->member_id
        );
        M("Pano_ban")->where($banwhere)->delete();
        $this->redirect("index", array("pano_id" => $pano_id));
    }

    function edit(){
        if (I("post.dopost") == "save") {
            $pano_id = I("post.pano_id");
            $ban_id = I("post.ban_id");
            $title = I("post.title");
            $ban_type = I("post.ban_type");
            $link = I("post.link");

            $where = array(
                "id" => $ban_id,
                "member_id" => $this->member_id
            );

            $data = array(
                "title" => $title,
                "type" => $ban_type,
                "link" => $link
            );
            M("Pano_ban")->where($where)->save($data);
            $this->redirect("index", array("pano_id" => $pano_id));

            exit();
        }

        $pano_id = I("get.pano_id");
        $ban_id = I("get.ban_id");
        $this->assign("pano_id", $pano_id);
        $this->assign("ban_id", $ban_id);

        $panowhere = array(
            "id" => $pano_id,
            "member_id" => $this->member_id
        );
        $panorow = M("Pano")->where($panowhere)->find();
        $this->assign('panorow', $panorow);

        $banwhere = array(
            "id" => $ban_id,
            "member_id" => $this->member_id
        );
        $row = M("Pano_ban")->where($banwhere)->find();
        $this->assign('row', $row);

        $this->display();
    }
}

?>
