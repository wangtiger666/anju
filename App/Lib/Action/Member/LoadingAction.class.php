<?php

class LoadingAction extends MemberAction {
	

	
	public function manage_my()
	{
		$systemrow = M("loadingbg")->where(array("member_id=0","mode"=>"system"))->select();
		$selfrow = M("loadingbg")->where(array("member_id"=>$this->member_id,"mode"=>"self"))->select();
		$this->assign('systemrow', $systemrow);
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
			$fileurl = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/selfloading";
			createFolder(APP_ROOT.$filedir);
			if(!empty($_POST['file']))
			{
				$arr = getimagesize(APP_ROOT.$_POST['file']);
                $width = $arr[0];
                $height = $arr[1];
                $imgtype = $arr[2];
				$file = ExecUpload($_POST['file'], '', $fileurl);
				M("loadingbg")->data(array("bgname"=>$bgname,"member_id"=>$this->member_id,"mode"=>'self',"file"=>$file,"width"=>$width,"height"=>$height))->add();
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
				$file = M("loadingbg")->where(array("id" => $id))->getField("file");
				@unlink(APP_ROOT . $file);
				$file = ExecUpload($_POST['file'], $_POST['old_file'], $fileurl);
				M("loadingbg")->where(array("id" => $id))->save(array("bgname"=>$bgname,"file"=>$file,"width"=>$width,"height"=>$height));
			}
			$this->success("修改成功！", U("manage_my"));
			exit;
		}
		$row = M("loadingbg")->where(array("id"=>$id))->find();
		$this->assign('id', $id);
		$this->assign('row', $row);
		$this->display();	
	}
	public function self_del()
	{
		$id = I("get.id");
		if(!empty($id))
		{
			$file = M("loadingbg")->where(array("id" => $id))->getField("file");
			@unlink(APP_ROOT . $file);
			M("loadingbg")->where("id='$id'")->delete();
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
		$count = M("pano_loading")->where(array("pano_id"=>$pano_id))->count();
		if($count<=0)
		{
			$data = array();			
			$data['color'] = "#3300FF";
			$data['pano_id'] = $pano_id;
			$data['navbgid'] = 1;
			$result = M("pano_loading")->data($data)->add();
			if($result)
			{
				M("pano_loading")->data(array("listorder"=>$result))->save();
			}
		}
		if (I("post.dopost") == "save")
		{
			$data = array();
			$openloading = I("post.openloading");
			$data['color'] = I("post.color");
			$data['overcolor'] = I("post.overcolor");
			$data['navbgid'] = I("post.navbgid");
			M("pano_loading")->where(array("pano_id"=>$pano_id))->save($data);
			M("Pano")->where(array("id"=>$pano_id))->save(array("openloading"=>$openloading));
			$this->success("保存成功！", U("index", array("pano_id" => $pano_id)));
			exit;
		}
		$panoloading = M("pano_loading")->where(array("pano_id"=>$pano_id))->find();
		$loadingbginfo = M("loadingbg")->where(array("id"=>$panoloading['navbgid']))->find();
		M("pano_loading")->where(array("pano_id"=>$pano_id))->save(array("color"=>$panoloading['color'],"overcolor"=>$panoloading['overcolor'],"navbgid"=>$panoloading['navbgid']));
		$this->assign('panoloading', $panoloading);
		$this->assign('loadingbginfo', $loadingbginfo);
        $this->display();
    }
	public function selNavbg()
	{
		$where ="member_id =".$this->member_id." or member_id = 0 ";
		$list = M("loadingbg")->where($where)->select();	
		$this->assign('list', $list);
		$this->assign('mode', $mode);
		$this->display();
	}
}
?>
