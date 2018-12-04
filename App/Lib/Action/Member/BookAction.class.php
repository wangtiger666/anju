<?php

class BookAction extends MemberAction {


	public function index() {
        $pano_id = I("get.pano_id");
        $this->assign('pano_id', $pano_id);
		$list = M("pano_book")->where(array("pano_id"=>$pano_id))->order("id desc")->select();
		if(!empty($list))
		{
			foreach($list as $key=>$val)
			{
				$count = M("pano_bookpic")->where(array("book_id"=>$val['id']))->count();
				$list[$key]['bookpicnum'] = intval($count);
			}
		}
		$this->assign('list', $list);
        $this->display();
    }
	
	public function bookadd()
	{
		$pano_id = intval($_REQUEST['pano_id']);
		$this->assign('pano_id', $pano_id);
		if (I("post.dopost") == "save")
		{
			$name = I("post.name");
			if(empty($pano_id)){
				$this->error("请选择一个项目！");
				exit();
            }
			if(empty($name)){
				$this->error("相册名称不能为空！");
				exit();
            }
			$count = M("pano_book")->where(array("pano_id"=>$pano_id,"name"=>$name))->count();
			if($count>0)
			{
				$this->error("相册名称已存在！");
				exit();	
			}
			M("pano_book")->data(array("pano_id"=>$pano_id,"name"=>$name))->add();
			$this->success("添加成功！", U("index", array("pano_id" => $pano_id)));
			exit;
		}
		$this->display();
	}

	public function bookedit()
	{
		$pano_id = intval($_REQUEST['pano_id']);
		$id = intval($_REQUEST['id']);
		$this->assign('pano_id', $pano_id);
		if (I("post.dopost") == "save")
		{
			$name = I("post.name");
			if(empty($pano_id)){
				$this->error("请选择一个项目！");
				exit();
            }
			if(empty($name)){
				$this->error("相册名称不能为空！");
				exit();
            }
			$count = M("pano_book")->where(array("pano_id"=>$pano_id,"name"=>$name))->count();
			if($count>0)
			{
				$this->error("相册名称已存在！");
				exit();	
			}
			M("pano_book")->where(array("id"=>$id))->data(array("name"=>$name))->save();
			$this->success("修改成功！", U("index", array("pano_id" => $pano_id)));
			exit;
		}
		$bookinfo = M("pano_book")->where(array("id"=>$id))->find();
		$this->assign('bookinfo', $bookinfo);	
		$this->display();
	}

	public function piclist()
	{
		$pano_id = intval($_REQUEST['pano_id']);
		$book_id = intval($_REQUEST['book_id']);
		$piclist = M("pano_bookpic")->where(array("book_id"=>$book_id))->order("listorder asc")->select();
		$this->assign('piclist', $piclist);

		$bookinfo = M("pano_book")->where(array("id"=>$book_id))->find();
		$this->assign('bookinfo', $bookinfo);
		$this->assign('pano_id', $pano_id);

		if (I("post.dopost") == "save")
		{
		
		}

		$this->display();	
	}






	//=========================================
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
				$data['linkurl'] = I("post.linkurl");
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
				$data['linkurl'] = I("post.linkurl");
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
