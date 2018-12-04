<?php

class EffectAction extends MemberAction {

    public function index() {
        cookie("effectback", __SELF__);

        $pano_id = I("get.pano_id");
        $this->assign('pano_id', $pano_id);

        $panorow = D("Pano")->GetOne($pano_id, $this->member_id);
        $this->assign('panorow', $panorow);

        $viewlist = D("Panoview")->GetList($pano_id, $this->member_id);
        foreach ($viewlist as $key => $value) {

        }
        $this->assign('viewlist', $viewlist);

        $this->display();
    }

    function weater_add() {
        $backurl = cookie("effectback");
        $this->assign('backurl', $backurl);

        cookie("back", __SELF__);

        if (I("post.dopost") == "save") {
            $view_id = I('post.view_id');
            $viewrow = D("Panoview")->GetOne($view_id, $this->member_id);
            $effect = I('post.effect');
            $effect_name = I('post.effect_name');

            $data = array(
                "effect_mod" => $effect,
                "effect_name" => $effect_name
            );
            $where = array(
                "member_id" => $this->member_id,
                "id" => $view_id
            );
            M("Pano_view ")->where($where)->save($data);
            $this->success("保存成功！", U("index", array("pano_id" => $viewrow['pano_id'])));
            exit();
        }

        $view_id = I("get.view_id");
        $this->assign("view_id", $view_id);

        $viewrow = D("Panoview")->GetOne($view_id, $this->member_id);
        $this->assign("viewrow", $viewrow);

        $panorow = D("Pano")->GetOne($viewrow['pano_id'], $this->member_id);
        $this->assign("panorow", $panorow);
        $this->assign("pano_id", $viewrow['pano_id']);

        $this->display();
    }

    function weatherxml() {
        $view_id = I('get.view_id');

        $viewrow = D("Panoview")->GetOne($view_id, $this->member_id);
        $this->assign('viewrow', $viewrow);

        $this->display('./App/Tpl/Member/Effect/weatherxml.xml', 'utf-8', 'text/xml');
    }

    function star() {
        cookie("starback", __SELF__);

        $pano_id = I("get.pano_id");
        $this->assign('pano_id', $pano_id);

        $panorow = D("Pano")->GetOne($pano_id, $this->member_id);
        $this->assign('panorow', $panorow);

        $viewlist = D("Panoview")->GetList($pano_id, $this->member_id);

        $this->assign('viewlist', $viewlist);

        $openarr = array(
            0 => "关闭",
            1 => "开启"
        );
        $this->assign('openarr', $openarr);

        $this->display();
    }

    function star_add() {
        $backurl = cookie("starback");
        $this->assign('backurl', $backurl);

        cookie("back", __SELF__);

        if (I("post.dopost") == "save") {
            $view_id = I('post.view_id');
            $viewrow = D("Panoview")->GetOne($view_id, $this->member_id);
            $star_open = I('post.star_open');
            $star_time = I('post.star_time');

            $data = array(
                "star_open" => $star_open,
                "star_time" => $star_time
            );
            $where = array(
                "member_id" => $this->member_id,
                "id" => $view_id
            );
            M("Pano_view")->where($where)->save($data);
            $this->success("保存成功！", U("star", array("pano_id" => $viewrow['pano_id'])));
            exit();
        }

        $view_id = I("get.view_id");
        $this->assign("view_id", $view_id);

        $viewrow = D("Panoview")->GetOne($view_id, $this->member_id);
        $this->assign("viewrow", $viewrow);

        $panorow = D("Pano")->GetOne($viewrow['pano_id'], $this->member_id);
        $this->assign("panorow", $panorow);
        $this->assign("pano_id", $viewrow['pano_id']);

        $this->display();
    }

    function starxml() {
        $view_id = I('get.view_id');

        $viewrow = D("Panoview")->GetOne($view_id, $this->member_id);
        $this->assign('viewrow', $viewrow);

        $this->display('./App/Tpl/Member/Effect/starxml.xml', 'utf-8', 'text/xml');
    }

    function lensflare() {
        cookie("lensflareback", __SELF__);

        $pano_id = I("get.pano_id");
        $this->assign('pano_id', $pano_id);

        $panorow = D("Pano")->GetOne($pano_id, $this->member_id);
        $this->assign('panorow', $panorow);

        $viewlist = D("Panoview")->GetList($pano_id, $this->member_id);
        foreach ($viewlist as $key => $value) {
            $lwhere = array(
                "member_id" => $this->member_id,
                "view_id" => $value['id']
            );
            $lrow = M("Pano_lensflare")->where($lwhere)->select();
            if (is_array($lrow)) {
                $viewlist[$key]['len'] = count($lrow);
            } else {
                $viewlist[$key]['len'] = 0;
            }
        }
        $this->assign('viewlist', $viewlist);

        $this->display();
    }

    function lensflareview() {
        $backurl = cookie("lensflareback");
        $this->assign('backurl', $backurl);

        cookie("back", __SELF__);

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
        $lensflarerow = M("Pano_lensflare")->where($lwhere)->select();
        $this->assign("lensflarerow", $lensflarerow);

        $this->display();
    }

    function lensflare_add() {
        $backurl = cookie("back");
        $this->assign('backurl', $backurl);

        if (I("post.dopost") == "save") {
            $view_id = I('post.view_id');
            $ath = I('post.ath');
            $atv = I('post.atv');
            $size = I('post.size');
            $blind = I('post.blind');
            $blindcurve = I('post.blindcurve');

            $data = array(
                "member_id" => $this->member_id,
                "view_id" => $view_id,
                "ath" => $ath,
                "atv" => $atv,
                "size" => $size,
                "blind" => $blind,
                "blindcurve" => $blindcurve
            );

            M("Pano_lensflare ")->add($data);
            $this->success("保存成功！", $backurl);
            exit();
        }

        $view_id = I("get.view_id");
        $this->assign("view_id", $view_id);

        $viewrow = D("Panoview")->GetOne($view_id, $this->member_id);
        $this->assign("viewrow", $viewrow);

        $panorow = D("Pano")->GetOne($viewrow['pano_id'], $this->member_id);
        $this->assign("panorow", $panorow);
        $this->assign("pano_id", $viewrow['pano_id']);

        $this->display();
    }

    function lensflare_edit() {
        $backurl = cookie("back");
        $this->assign('backurl', $backurl);

        if (I("post.dopost") == "save") {
            $len_id = I('post.len_id');
            $ath = I('post.ath');
            $atv = I('post.atv');
            $size = I('post.size');
            $blind = I('post.blind');
            $blindcurve = I('post.blindcurve');

            $data = array(
                "ath" => $ath,
                "atv" => $atv,
                "size" => $size,
                "blind" => $blind,
                "blindcurve" => $blindcurve
            );

            $lwhere = array(
                "id" => $len_id,
                "member_id" => $this->member_id
            );

            M("Pano_lensflare ")->where($lwhere)->save($data);
            $this->success("保存成功！", $backurl);
            exit();
        }

        $len_id = I("get.len_id");
        $this->assign("len_id", $len_id);

        $lwhere = array(
            "id" => $len_id,
            "member_id" => $this->member_id
        );
        $lenrow = M("Pano_lensflare")->where($lwhere)->find();
        $this->assign("lenrow", $lenrow);

        $view_id = $lenrow['view_id'];
        $this->assign("view_id", $view_id);

        $viewrow = D("Panoview")->GetOne($view_id, $this->member_id);
        $this->assign("viewrow", $viewrow);

        $panorow = D("Pano")->GetOne($viewrow['pano_id'], $this->member_id);
        $this->assign("panorow", $panorow);
        $this->assign("pano_id", $viewrow['pano_id']);

        $this->display();
    }

    function lensflare_del() {
        $backurl = cookie("back");
        $this->assign('backurl', $backurl);

        if (I("post.dopost") == "save") {
            $len_id = I('post.len_id');

            $lwhere = array(
                "id" => $len_id,
                "member_id" => $this->member_id
            );

            M("Pano_lensflare ")->where($lwhere)->delete();
            $this->success("删除成功！", $backurl);
            exit();
        }

        $len_id = I("get.len_id");
        $this->assign("len_id", $len_id);

        $lwhere = array(
            "id" => $len_id,
            "member_id" => $this->member_id
        );
        $lenrow = M("Pano_lensflare")->where($lwhere)->find();
        $this->assign("lenrow", $lenrow);

        $view_id = $lenrow['view_id'];
        $this->assign("view_id", $view_id);

        $viewrow = D("Panoview")->GetOne($view_id, $this->member_id);
        $this->assign("viewrow", $viewrow);

        $panorow = D("Pano")->GetOne($viewrow['pano_id'], $this->member_id);
        $this->assign("panorow", $panorow);
        $this->assign("pano_id", $viewrow['pano_id']);

        $this->display();
    }

    function lensflarexml() {
        $view_id = I('get.view_id');

        $viewrow = D("Panoview")->GetOne($view_id, $this->member_id);
        $this->assign('viewrow', $viewrow);

        $this->display('./App/Tpl/Member/Effect/lensflarexml.xml', 'utf-8', 'text/xml');
    }

    function logo() {
        cookie("logoback", __SELF__);

        $pano_id = I("get.pano_id");
        $this->assign('pano_id', $pano_id);

        $panorow = D("Pano")->GetOne($pano_id, $this->member_id);
        $this->assign('panorow', $panorow);

        $viewlist = D("Panoview")->GetList($pano_id, $this->member_id);
        foreach ($viewlist as $key => $value) {
            $lwhere = array(
                "member_id" => $this->member_id,
                "view_id" => $value['id'],
                "open" => 1
            );
            $lrow = M("Pano_bottomlogo")->where($lwhere)->find();
            if (is_array($lrow)) {
                $viewlist[$key]['len'] = "开";
            } else {
                $viewlist[$key]['len'] = "关";
            }
        }
        $this->assign('viewlist', $viewlist);

        $this->display();
    }

    function logoview() {
        $backurl = cookie("logoback");
        $this->assign('backurl', $backurl);
          
        $view_id = I("get.view_id");
        $this->assign("view_id", $view_id);

        $viewrow = D("Panoview")->GetOne($view_id, $this->member_id);
        $this->assign("viewrow", $viewrow);

        $member_id = $this->member_id;

        $pano_id = $viewrow['pano_id'];
        $panorow = D("Pano")->GetOne($viewrow['pano_id'], $this->member_id);
        $this->assign("panorow", $panorow);
        $this->assign("pano_id", $viewrow['pano_id']);

        $where = array(
            "member_id" => $member_id,
            "pano_id" => $pano_id,
            "view_id" => $view_id
        );

        $fileurl = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/$pano_id/toolbox/bottomlogo";
		
		

        if (I("post.dopost") == "save") {
            $data = readPost($_POST);
            $data["image"] = ExecUpload($data["image"], $data["old_image"], $fileurl);
            unset($data["old_image"]);			
            M("Pano_bottomlogo")->where($where)->save($data);
			
			
			
			
			
			
			
			$qjdata["openbottomlogo"] = $data["qjopen"];
			$qjdata["openbottomlogoimg"] = ExecUpload($data["image"], $data["old_image"], $fileurl);
			unset($qjdata["old_image"]);
			$qjdata["openbottomlogoscale"] = $data["scale"];
			$qjdata["openbottomlogogs"] = $data["logogsopen"];
			$qjwhere = array(
            "member_id" => $member_id,
            "id" => $pano_id           
			);
			M("Pano")->where($qjwhere)->save($qjdata);
			//echo M('Pano')->_sql();
			//exit();
			
			
			
			
            $this->redirect("logo",array("pano_id"=>$pano_id));
            exit();
        }

        $row = M("Pano_bottomlogo")->where($where)->find();
        if (!is_array($row)) {
            $row = array();
            $row["member_id"] = $member_id;
            $row["view_id"] = $view_id;
            $row["pano_id"] = $pano_id;
            $row["open"] = 0;
            $row["scale"] = 100;
            $row["image"] = ExecUpload("/Public/member/images/pano/logo.png", "", $fileurl);
            M("Pano_bottomlogo")->add($row);
        }
        $this->assign("row", $row);

        $this->display();
    }

    function logoxml() {
        $view_id = I('get.view_id');

        $viewrow = D("Panoview")->GetOne($view_id, $this->member_id);
        $this->assign('viewrow', $viewrow);

        $this->display('./App/Tpl/Member/Effect/logoxml.xml', 'utf-8', 'text/xml');
    }

}

?>
