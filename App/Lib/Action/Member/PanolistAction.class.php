<?php

class PanolistAction extends MemberAction {

    var $dex = "panolist";

    public function index() {
        $this->assign("dex", $this->dex);
        cookie("panoback", __SELF__);
        $F = M("Pano");
        import('ORG.Util.Page');
		$where['member_id'] = $this->member_id;
		$where['disable'] = 1;
		$panoname = trim(I('post.panoname'));
		if(!empty($panoname))
		{
			$where['title'] = array('like',"%".$panoname."%");
		}
        $count = $F->where($where)->count();
        $Page = new Page($count, 30);
        $show = $Page->show();        
        $list = $F->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach ($list as $key => $va) {
            $vn = D("Panoview");
            $myrow = $vn->GetList($va['id'],$this->member_id);
            $list[$key]['num'] = count($myrow);
			$syscount = M("Comment")->where(array("pano_id"=>$va['id']))->count();
			$list[$key]['syscount'] = $syscount;
        }
        OutputDebugString('pano',json_encode($list));
        $this->assign('list', $list);
        $this->assign('page', $show);
        $this->display();
    }

    function add() {
        if (I("post.dopost") == "save") {
			$minfo = M("Member")->where(array("id" => $this->member_id))->find();	
			$weburl = M("Sysconfig")->where(array("id"=>20))->find();		
			$pano_limit = intval($minfo['pano_limit']);
			$panocount = M("Pano")->where(array("member_id" => $this->member_id))->count();
			if($panocount>=$minfo['pano_limit']){
				$this->error("超过数量限制，你只能创建".$minfo['pano_limit']."个项目！");
                exit;
			}
            $title = trim(I('post.title'));
            $is_ipad_view = I('post.is_ipad_view');
            $qingxi = I('post.qingxi');
            $cursor_open = I('post.cursor_open');
            $cursor_id = I('post.cursor_id');

            if ($title == "") {
                $this->error("标题不能为空！");
                exit();
            }
			$guid = guid(16);
			
			$wx_url = $weburl['value']."/t/".$guid;
            $data = array(
                "title" => $title,
                "member_id" => $this->member_id,
                "is_ipad_view" => $is_ipad_view,
                "cursor_open" => $cursor_open,
                "cursor_id" => $cursor_id,
                "openautonext" => 1,
                "openautorate" => 1,
				"guid" => $guid,
				"wx_url"=>$wx_url,
                "creat_time" => time()
            );
            $now_id = M("Pano")->add($data);
            $this->success("全景项目创建成功！", U("view/index", array("pano_id" => $now_id)));
            exit();
        }


        $syscursorwhere = array(
            "type" => "system"
        );
        $syscursorrow = M("Cursor")->where($syscursorwhere)->select();
        $this->assign("syscursorrow", $syscursorrow);

        $selfcursorwhere = array(
            "type" => "self",
            "member_id" => $this->member_id
        );
        $selfcursorrow = M("Cursor")->where($selfcursorwhere)->select();
        $this->assign("selfcursorrow", $selfcursorrow);

        $this->display();
    }

    function del(){
        $pano_id = I("get.pano_id");

//        $pano_resources = M('house_resources')->where(['pano_id'=>$pano_id,'member_id'=>$this->member_id])->find();


        $panowhere = array(
            "id" => $pano_id,
            "member_id" => $this->member_id
        );
        $pano = M("Pano")->where($panowhere)->find();
        if ($pano['pid'] == 0){
            $pano_resources = M('house_resources')->where(array('pano_id'=>$pano_id,'member_id'=>$this->member_id))->find();
            $uptown_id = $pano_resources['uptown_id'];
            M('region')->where(array('id'=>$uptown_id))->delete();
            $houselist = M('house_resources')->where(array('uptown_id'=>$uptown_id,'member_id'=>$this->member_id))->select();
            foreach ($houselist as $house){
                $id = $house['id'];
                M('house_resources')->where(array('id'=>$id))->delete();
            }
        }else{
            M('house_resources')->where(array('pano_id'=>$pano_id,'member_id'=>$this->member_id))->delete();
        }

        $delwhere = array(
            "pano_id" =>$pano_id,
            "member_id" => $this->member_id
        );
        M("Pano_ban")->where($delwhere)->delete();
        M("Pano_map")->where($delwhere)->delete();
        M("Pano_ui")->where($delwhere)->delete();
        M("Pano_uimobie")->where($delwhere)->delete();
        M("Pano_view")->where($delwhere)->delete();

        $viewwhere = array(
            "pano_id" => $pano_id,
            "member_id" => $this->member_id
        );
        $viewlist = M("Pano_view")->where($viewwhere)->order("sort")->select();

        foreach ($viewlist as $view) {
            $view_id = $view['id'];
            $delwhere = array(
                "view_id" => $view_id,
                "member_id" => $this->member_id
            );
            $targetwhere = array(
                "target_id" => $view_id,
                "member_id" => $this->member_id
            );
            M("Pano_roam")->where($delwhere)->delete();
            M("Pano_roam")->where($targetwhere)->delete();
            M("Pano_cube")->where($delwhere)->delete();
            M("Pano_photo")->where($delwhere)->delete();
            M("Pano_map_node")->where($delwhere)->delete();
        }

        $mybag = APP_ROOT . "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/{$pano_id}";
        RemoveDirFiles($mybag);

        M("Pano")->where($panowhere)->delete();

        $this->success("删除成功！", cookie("panoback"));
    }

}

?>
