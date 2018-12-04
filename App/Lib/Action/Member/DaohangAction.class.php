<?php

class DaohangAction extends MemberAction {
	
	public function manage()
	{
		$sysrow = M("navbg")->where(array("member_id"=>0))->select();
		$this->assign('sysrow', $sysrow);
		$this->display();	
	}
	
	public function manage_my()
	{
		$selfrow = M("navbg")->where(array("member_id"=>$this->member_id,"mode"=>"self"))->select();
		$this->assign('selfrow', $selfrow);
		$this->display();	
	}
	
	public function self_add()
	{
		if (I("post.dopost") == "save")
		{
			$bgname = I("post.bgname");
			if(empty($bgname)){
				$this->error("导航样式名称不能为空！");
				exit();
            }
			if(empty($_POST['file']))
			{
				$this->error("请上传导航背景图片！");
				exit();
			}
			$fileurl = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/selfnav";
			createFolder(APP_ROOT.$filedir);
			if(!empty($_POST['file']))
			{
				$arr = getimagesize(APP_ROOT.$_POST['file']);
                $width = $arr[0];
                $height = $arr[1];
                $imgtype = $arr[2];
                if($imgtype == 1){
                    $this->error("图片格式不可以为GIF，请使用PNG图片！");
					exit();
                }
				$file = ExecUpload($_POST['file'], '', $fileurl);
				M("navbg")->data(array("bgname"=>$bgname,"member_id"=>$this->member_id,"mode"=>'self',"file"=>$file,"width"=>$width,"height"=>$height))->add();
				$this->success("添加成功！", U("manage_my"));
				exit;
			}
		}
		$this->display();
	}
	public function self_edit()
	{
		$id = I("get.id");
		if (I("post.dopost") == "save")
		{
			$bgname = I("post.bgname");
			$fileurl = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/selfnav";
			createFolder(APP_ROOT.$filedir);
			if(!empty($_POST['file']))
			{
				$arr = getimagesize(APP_ROOT.$_POST['file']);
                $width = $arr[0];
                $height = $arr[1];
                $imgtype = $arr[2];
                if($imgtype == 1){
                    $this->error("图片格式不可以为GIF，请使用PNG图片！");
					exit();
                }
				$file = M("navbg")->where(array("id" => $id))->getField("file");
				@unlink(APP_ROOT . $file);
				$file = ExecUpload($_POST['file'], $_POST['old_file'], $fileurl);
				M("navbg")->where(array("id" => $id))->save(array("bgname"=>$bgname,"file"=>$file,"width"=>$width,"height"=>$height));
			}
			$this->success("修改成功！", U("manage_my"));
			exit;
		}
		$row = M("navbg")->where(array("id"=>$id))->find();
		$this->assign('id', $id);
		$this->assign('row', $row);
		$this->display();	
	}
	public function self_del()
	{
		$id = I("get.id");
		if(!empty($id))
		{
			$file = M("navbg")->where(array("id" => $id))->getField("file");
			@unlink(APP_ROOT . $file);
			M("navbg")->where("id='$id'")->delete();
			$this->success("删除成功！", U("manage_my"));
		}
	}

	public function index() {
        $pano_id = I("get.pano_id");
        $this->assign('pano_id', $pano_id);

        $member_id = $this->member_id;
        $this->assign("member_id", $member_id);

        $panorow = D("Pano")->GetOne($pano_id, $this->member_id);
        $this->assign('panorow', $panorow);
		$count = M("pano_nav")->where(array("pano_id"=>$pano_id))->count();
		if($count<=0)
		{
			$data = array();
			$data['navname'] = "默认导航";
			$data['color'] = "#FFFFFF";
			$data['overcolor'] = "#FFFFFF";
			$data['pano_id'] = $pano_id;
			$data['navbgid'] = 4;
			$data['ox'] = 20;
			$data['oy'] = 0;
			$result = M("pano_nav")->data($data)->add();
			if($result)
			{
				M("pano_nav")->data(array("listorder"=>$result))->save();
			}
		}
		if (I("post.dopost") == "save")
		{
			$data = array();
			$opennav = I("post.opennav");
			$data['color'] = I("post.color");
			$data['overcolor'] = I("post.overcolor");
			$data['navbgid'] = I("post.navbgid");
			$data['ox'] = I("post.ox");
			$data['oy'] = I("post.oy");
			if($data['navbgid']==1) $data['oy']=9;
			M("pano_nav")->where(array("pano_id"=>$pano_id))->save($data);
			M("Pano")->where(array("id"=>$pano_id))->save(array("opennav"=>$opennav));
			$this->success("保存成功！", U("index", array("pano_id" => $pano_id)));
			exit;
		}
		$panonav = M("pano_nav")->where(array("pano_id"=>$pano_id))->find();
		$navbginfo = M("navbg")->where(array("id"=>$panonav['navbgid']))->find();
		M("pano_nav")->where(array("pano_id"=>$pano_id))->save(array("color"=>$panonav['color'],"overcolor"=>$panonav['overcolor'],"navbgid"=>$panonav['navbgid'],"ox"=>$panonav['ox'],"oy"=>$panonav['oy']));
		$this->assign('panonav', $panonav);
		$this->assign('navbginfo', $navbginfo);
        $this->display();
    }
	public function selNavbg()
	{
		$mode = I("get.mode")?I("get.mode"):"system";
		if($mode=="system")
		{
			$where = array(
				"member_id"=>0	
			);
		}
		elseif($mode=="self")
		{
			$where = array(
				"member_id"=>$this->member_id	
			);
		}
		$list = M("navbg")->where($where)->select();		
		$this->assign('list', $list);
		$this->assign('mode', $mode);
		$this->display();
	}
	public function navlist()
	{
		$pano_id = I("get.pano_id");
		$this->assign('pano_id', $pano_id);
		$list = M("pano_nav")->where(array("pano_id"=>$pano_id))->order("listorder asc")->select();
		$this->assign('list', $list);
		$this->display();
	}
	
	public function navadd()
	{
		$pano_id = I("get.pano_id");
		$this->assign('pano_id', $pano_id);
		if (I("post.dopost") == "save")
		{
			$navinfo = M("pano_nav")->where(array("pano_id"=>$pano_id))->find();
			$navbgid = $navinfo['navbgid'];
			$content = $_POST["content"];
			$opentype = I("post.opentype");
			$data = array();
			$data['navname'] = I("post.navname");
			$data['listorder'] = I("post.listorder");
			$data['pano_id'] = I("post.pano_id");
			$data['color'] = $navinfo['color'];
			$data['overcolor'] = $navinfo['overcolor'];
			$data['navbgid'] = $navinfo['navbgid'];
			$data['ox'] = $navinfo['ox'];
			$data['oy'] = $navinfo['oy'];
			$data['opentype'] = $opentype;
			$data['iframewidth'] = I("post.iframewidth");
			$data['iframeheight'] = I("post.iframeheight");
			if($opentype==1){
				$data['content'] = $content;				
			}
			elseif($opentype==2)
			{
				$data['linkurl'] = htmlspecialchars_decode(I("post.linkurl"));
			}
			M("pano_nav")->data($data)->add();
			$this->success("添加成功！", U("navlist", array("pano_id" => $pano_id)));
			exit;
		}
		$this->display();
	}

	public function msglist()
	{
		$pano_id = I("get.pano_id");
		$this->assign('pano_id', $pano_id);
		$list = M("pano_msglist")->where(array("pano_id"=>$pano_id))->order("id desc")->select();
		$this->assign('list', $list);
		$this->display();
	}
	public function msgdel()
	{
		$id = I("get.id");
		$pano_id = I("get.pano_id");
		if(!empty($id))
		{
			M("pano_msglist")->where("id='$id'")->delete();
			$this->success("删除成功！", U("msglist", array("pano_id" => $pano_id)));
		}
	}
	
	public function edit()
	{
		$id = I("get.id");
		$navinfo = M("pano_nav")->where(array("id"=>$id))->find();
		if (I("post.dopost") == "save" && !empty($id))
		{
			$content = $_POST["content"];
			$opentype = I("post.opentype");
			$data = array();
			$data['navname'] = I("post.navname");
			$data['listorder'] = I("post.listorder");
			$data['opentype'] = $opentype;
			$data['iframewidth'] = I("post.iframewidth");
			$data['iframeheight'] = I("post.iframeheight");
			if($opentype==1){
				$data['content'] = $content;				
			}
			elseif($opentype==2)
			{
				$data['linkurl'] = htmlspecialchars_decode(I("post.linkurl"));
			}
			M("pano_nav")->where(array("id"=>$id))->save($data);
			$this->success("保存成功！", U("navlist", array("pano_id" => $navinfo['pano_id'])));
			exit;
		}

		$this->assign('navinfo', $navinfo);
		$this->assign('pano_id', $navinfo['pano_id']);
		$this->display();
	}

	public function del()
	{
		$id = I("get.id");
		$pano_id = I("get.pano_id");
		if(!empty($id))
		{
			M("pano_nav")->where("id='$id'")->delete();
			$this->success("删除成功！", U("navlist", array("pano_id" => $pano_id)));
		}
	}
}
?>
