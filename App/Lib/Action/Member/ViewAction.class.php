<?php

class ViewAction extends MemberAction {

    function index() {
        cookie("viewback", __SELF__);
        $pano_id = I("get.pano_id");
        $fzid = intval($_REQUEST['fzid']);
        $this->assign('pano_id', $pano_id);

		if(!empty($_POST['btnSubmit']))
		{
			if(empty($fzid)){
				$this->error("未选择任何分组");
				exit();
			}
			$ids = I('post.ids');
			if(!empty($ids))
			{
				$ids = implode(",",$ids);
				if($fzid=="-1"){
					M("Pano_view")->where("id in ($ids)")->save(array("fenzuid"=>0));
				}else{
					M("Pano_view")->where("id in ($ids)")->save(array("fenzuid"=>$fzid));
				}
			}
			$this->success('设置成功', U("view/index", array("pano_id" => $pano_id)));
            exit();
		}	

        $panowhere = array(
            "id" => $pano_id,
            "member_id" => $this->member_id
        );
        $panorow = M("Pano")->where($panowhere)->find();
		
		
		$videowhere = array(
            "pano_id" => $pano_id,
            "member_id" => $this->member_id,
			"openvideo" => 1
        );
		
		$ring720where = array(
            "pano_id" => $pano_id,
            "member_id" => $this->member_id,
			"open720ring"=> 1
        );
		
		$ringwhere = array(
            "pano_id" => $pano_id,
            "member_id" => $this->member_id,
			"open_ring"=> 1
        );
		
		$open_panowhere = array(
            "pano_id" => $pano_id,
            "member_id" => $this->member_id,
			"open_pano"=> 1
        );
		
		
		$video = M("Pano_view")->where($videowhere)->count();
		$ring720 = M("Pano_view")->where($ring720where)->count();
		$ring = M("Pano_view")->where($ringwhere)->count();
		$open_pano = M("Pano_view")->where($open_panowhere)->count();
		

		
		
		
		$this->assign('video', $video);
		$this->assign('ring720', $ring720);
		$this->assign('ring', $ring);
		$this->assign('open_pano', $open_pano);
		
        $this->assign('panorow', $panorow);

		$viewwhere['pano_id'] = $pano_id;
		$title = trim(I('post.title'));
		if(!empty($title))
		{
			$viewwhere['title'] = array('like',"%".$title."%");
			$this->assign('title', $title);
		}
		if(!empty($fzid))
		{
			$viewwhere['fenzuid'] = $fzid;
			$this->assign('fzid', $fzid);
		}
        $viewlist = M("Pano_view")->where($viewwhere)->order("sort")->select();
		if(!empty($viewlist))
		{
			foreach($viewlist as $key=>$val)
			{
				$viewlist[$key]['fenzuname'] = M("Pano_fenzu")->where(array("id"=>$val['fenzuid']))->getField("name");
			}
		}
        $this->assign('viewlist', $viewlist);
		
		$fenzuwhere = array(
			"pano_id" => $this->pano_id
		);
		$fenzulist = M("Pano_fenzu")->where(array("pano_id"=>$pano_id))->order("id asc")->select();
		$this->assign('fenzulist', $fenzulist);
		
        $this->display();
    }

	function fenzulist()
	{
		$pano_id = I("get.pano_id");
		if (I("post.dopost") == "save")
		{
			$ids = I('post.ids');
			$names = I('post.names');
			if(!empty($ids))
			{
				foreach($ids as $key=>$val)
				{
					M("Pano_fenzu")->where(array("id" => $val))->save(array("name" => $names[$key]));
				}
			}
			$this->success('修改成功', U("view/fenzulist", array("pano_id" => $pano_id)));
            exit();
		}


		$fenzulist = M("Pano_fenzu")->where(array("pano_id"=>$pano_id))->order("id asc")->select();
		$this->assign('fenzulist', $fenzulist);
		$this->assign('pano_id', $pano_id);
		$this->display();
	}
	
	function createfenzu()
	{
		$pano_id = intval($_REQUEST['pano_id']);
		$name = I('post.name');
		if(empty($pano_id))
		{
			$this->error("未指定任何场景");
			exit();
		}		
		if (I("post.dopost") == "save")
		{	
			if(empty($name))
			{
				$this->error("请填写分组名称");
				exit();
			}
			M("Pano_fenzu")->add(array("pano_id" => $pano_id,"name" => $name));
			$this->redirect("view/fenzulist",array("pano_id" => $pano_id));
            exit();
		}
		$this->assign('pano_id', $pano_id);
		$this->display();
	}
	function delfenzu()
	{
		$id = I("get.id");
		$pano_id = I("get.pano_id");
		if(empty($id))
		{
			$this->error("未指定任何分组");
			exit();
		}
		else
		{
			M("Pano_fenzu")->where(array("id"=>$id))->delete();
			$this->redirect("view/fenzulist",array("pano_id" => $pano_id));
            exit();
		}
	}

    function add() {
        $backurl = cookie("viewback");
        $this->assign('backurl', $backurl);

        $os = (DIRECTORY_SEPARATOR == '\\') ? "windows" : 'linux';
        $this->assign('os', $os);

        if (I("post.dopost") == "save") {
            $pano_id = I('post.pano_id');
            $title = trim(I('post.title'));
            $thumb = I('post.thumb');
            $front = I('post.front');
            $back = I('post.back');
            $left = I('post.left');
            $right = I('post.right');
            $up = I('post.up');
            $down = I('post.down');
            $fov = I("post.fov");
            $hlookat = I("post.hlookat");
            $vlookat = I("post.vlookat");
            $openmusic = I("post.openmusic");
            $musicfile = I("post.musicfile");
            $musictimes = I("post.musictimes");
            $musicvolume = I("post.musicvolume");
            $fovmin = I('post.fovmin');
            $fovmax = I('post.fovmax');
            $hlookatmin = I('post.hlookatmin');
            $hlookatmax = I('post.hlookatmax');
            $vlookatmin = I('post.vlookatmin');
            $vlookatmax = I('post.vlookatmax');
            $open_limit = I('post.open_limit');
            $limitview = I('post.limitview');
            
            if($hlookatmin > $hlookatmax){
                $hlookatmax = $hlookatmax + 360;
            }
            if($hlookatmin>180){
                $hlookatmin = $hlookatmin - 360;
                $hlookatmax = $hlookatmax - 360;
            }

            if ($title == "") {
                $this->error("请填写【全景场景名称】！");
                exit();
            }

            $viewwhere = array(
                "pano_id" => $pano_id,
                "member_id" => $this->member_id
            );
            $viewrow = M("Pano_view ")->where($viewwhere)->select();
            if (is_array($viewrow)) {
                $m = count($viewrow);
                $sort = $m + 1;
                $first_read = 0;
            } else {
                $sort = 1;
                $first_read = 1;
            }

            $filedir = substr(md5(time()), 5, 8);

            foreach ($this->pano as $pk) {
                if (${$pk} == "") {
                    $this->error("请确保上传所有全景图！");
                    exit();
                } else {
                    if (substr_count(${$pk}, "station") > 0) {
                        ${$pk} = MoveViewPhoto(${$pk}, $this->member_id, $pano_id, $filedir, $pk);
                    }
                }
            }
			if($thumb=="#") {
				$thumbfront = APP_ROOT.$front;
				$thumbfile = APP_ROOT."/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id . "/" . $filedir. "/thumb.jpg";
				lfthumb($thumbfront,$thumbfile);
				$thumb = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id . "/" . $filedir. "/thumb.jpg";
			} elseif (substr_count($thumb, "station") > 0) {
                $thumb = MoveThumbPhoto($thumb, $this->member_id, $pano_id, $filedir);
            } else {
                $thumb = CopyThumbPhoto($thumb, $this->member_id, $pano_id, $filedir);
            }
            $pano_path = str_replace("pano_l", "pano_%s", $left);

            if (substr_count($musicfile, "station") > 0) {
                $musicfile = MoveThumbPhoto($musicfile, $this->member_id, $pano_id, $filedir,"music");
            }
            if(!is_file(APP_ROOT.$musicfile)){
                $openmusic = 0;
            }
			$open_pano = 1;
			
            $data = array(
                "title" => $title,
                "member_id" => $this->member_id,
                "pano_id" => $pano_id,
                "filedir" => $filedir,
                "thumb" => $thumb,
                "front" => $front,
                "back" => $back,
                "left" => $left,
                "right" => $right,
                "up" => $up,
                "down" => $down,
                "sort" => $sort,
                "pano_path" => $pano_path,
                "first_read" => $first_read,
                "fov" => $fov,
                "hlookat" => $hlookat,
                "vlookat" => $vlookat,
                "openmusic" => $openmusic,
                "musicfile" => $musicfile,
                "musictimes" => $musictimes,
                "musicvolume" => $musicvolume,
                'fovmin' => $fovmin,
                'fovmax' => $fovmax,
                'hlookatmin'=>$hlookatmin,
                'hlookatmax' => $hlookatmax,
                'vlookatmin' => $vlookatmin,
                "vlookatmax" => $vlookatmax,
                'open_limit' => $open_limit,
                'limitview' => $limitview,
				'open_pano' => $open_pano,
            );
            $view_id = M("Pano_view")->add($data);

            $imgdir = APP_ROOT . "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/station";
            RemoveDirFiles($imgdir);

            $this->redirect("mkpreview", array("view_id" => $view_id));
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
	
	function add3d() {
        $backurl = cookie("viewback");
        $this->assign('backurl', $backurl);

        $os = (DIRECTORY_SEPARATOR == '\\') ? "windows" : 'linux';
        $this->assign('os', $os);

        if (I("post.dopost") == "save") {
            $pano_id = I('post.pano_id');
            $title = trim(I('post.title'));
            $thumb = I('post.thumb');
            $front = I('post.front');
            $back = I('post.back');
            $left = I('post.left');
            $right = I('post.right');
            $up = I('post.up');
            $down = I('post.down');
            $fov = I("post.fov");
            $hlookat = I("post.hlookat");
            $vlookat = I("post.vlookat");
            $openmusic = I("post.openmusic");
            $musicfile = I("post.musicfile");
            $musictimes = I("post.musictimes");
            $musicvolume = I("post.musicvolume");
            $fovmin = I('post.fovmin');
            $fovmax = I('post.fovmax');
            $hlookatmin = I('post.hlookatmin');
            $hlookatmax = I('post.hlookatmax');
            $vlookatmin = I('post.vlookatmin');
            $vlookatmax = I('post.vlookatmax');
            $open_limit = I('post.open_limit');
            $limitview = I('post.limitview');
            
            if($hlookatmin > $hlookatmax){
                $hlookatmax = $hlookatmax + 360;
            }
            if($hlookatmin>180){
                $hlookatmin = $hlookatmin - 360;
                $hlookatmax = $hlookatmax - 360;
            }

            if ($title == "") {
                $this->error("请填写【全景场景名称】！");
                exit();
            }

            $viewwhere = array(
                "pano_id" => $pano_id,
                "member_id" => $this->member_id
            );
            $viewrow = M("Pano_view ")->where($viewwhere)->select();
            if (is_array($viewrow)) {
                $m = count($viewrow);
                $sort = $m + 1;
                $first_read = 0;
            } else {
                $sort = 1;
                $first_read = 1;
            }

            $filedir = substr(md5(time()), 5, 8);

            foreach ($this->pano as $pk) {
                if (${$pk} == "") {
                    $this->error("请确保上传所有全景图！");
                    exit();
                } else {
                    if (substr_count(${$pk}, "station") > 0) {
                        ${$pk} = MoveViewPhoto(${$pk}, $this->member_id, $pano_id, $filedir, $pk);
                    }
                }
            }
			if($thumb=="#") {
				$thumbfront = APP_ROOT.$front;
				$thumbfile = APP_ROOT."/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id . "/" . $filedir. "/thumb.jpg";
				lfthumb($thumbfront,$thumbfile);
				$thumb = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id . "/" . $filedir. "/thumb.jpg";
			} elseif (substr_count($thumb, "station") > 0) {
                $thumb = MoveThumbPhoto($thumb, $this->member_id, $pano_id, $filedir);
            } else {
                $thumb = CopyThumbPhoto($thumb, $this->member_id, $pano_id, $filedir);
            }
            $pano_path = str_replace("pano_l", "pano_%s", $left);

            if (substr_count($musicfile, "station") > 0) {
                $musicfile = MoveThumbPhoto($musicfile, $this->member_id, $pano_id, $filedir,"music");
            }
            if(!is_file(APP_ROOT.$musicfile)){
                $openmusic = 0;
            }
			$open_pano = 1;
			$sdpic = 1;
			 
            $data = array(
                "title" => $title,
                "member_id" => $this->member_id,
                "pano_id" => $pano_id,
                "filedir" => $filedir,
                "thumb" => $thumb,
                "front" => $front,
                "back" => $back,
                "left" => $left,
                "right" => $right,
                "up" => $up,
                "down" => $down,
                "sort" => $sort,
                "pano_path" => $pano_path,
                "first_read" => $first_read,
                "fov" => $fov,
                "hlookat" => $hlookat,
                "vlookat" => $vlookat,
                "openmusic" => $openmusic,
                "musicfile" => $musicfile,
                "musictimes" => $musictimes,
                "musicvolume" => $musicvolume,
                'fovmin' => $fovmin,
                'fovmax' => $fovmax,
                'hlookatmin'=>$hlookatmin,
                'hlookatmax' => $hlookatmax,
                'vlookatmin' => $vlookatmin,
                "vlookatmax" => $vlookatmax,
                'open_limit' => $open_limit,
                'limitview' => $limitview,
				"open_pano" => $open_pano,
				"sdpic" => $sdpic,
            );
            $view_id = M("Pano_view")->add($data);

            $imgdir = APP_ROOT . "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/station";
			$img3ddir = APP_ROOT . "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/cutstation/";
			createFolder($img3ddir);
            copyFolder($imgdir, $img3ddir);
			
			
            RemoveDirFiles($imgdir);

            $this->redirect("mkpreview3d", array("view_id" => $view_id));
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
	
	
	function add_video() {
        $backurl = cookie("viewback");
        $this->assign('backurl', $backurl);

        $os = (DIRECTORY_SEPARATOR == '\\') ? "windows" : 'linux';
        $this->assign('os', $os);

        if (I("post.dopost") == "save") {
            $pano_id = I('post.pano_id');
            $title = trim(I('post.title'));
            $thumb = I('post.thumb'); 
			$openvideo = I("post.openvideo");    

            $videofile = I("post.videofile");
			$videofilewebm = I("post.videofilewebm");
			$videofilem4a = I("post.videofilem4a");
			$videofileimg = I("post.videofileimg");
			$videofilehd = I("post.videofilehd");
			$videofilehdwebm = I("post.videofilehdwebm");
			$videofilehdm4a = I("post.videofilehdm4a");
			$videofilehdimg = I("post.videofilehdimg");
            $house_detail_img = I('post.house_detail_img');
            $view_type = I('post.view_type');

            if ($title == "") {
                $this->error("请填写【全景视频名称】！");
                exit();
            }

            $viewwhere = array(
                "pano_id" => $pano_id,
                "member_id" => $this->member_id
            );
            $viewrow = M("Pano_view ")->where($viewwhere)->select();
            if (is_array($viewrow)) {
                $m = count($viewrow);
                $sort = $m + 1;
                $first_read = 0;
            } else {
                $sort = 1;
                $first_read = 1;
            }

            $filedir = substr(md5(time()), 5, 8);

			
		
			
			
            if (substr_count($videofile, "station") > 0) {
                $videofile = MoveThumbPhoto($videofile, $this->member_id, $pano_id, $filedir,"video");
            }
			if (substr_count($videofilewebm, "station") > 0) {
                $videofilewebm = MoveThumbPhoto($videofilewebm, $this->member_id, $pano_id, $filedir,"videofilewebm");
            }
			if (substr_count($videofilem4a, "station") > 0) {
                $videofilem4a = MoveThumbPhoto($videofilem4a, $this->member_id, $pano_id, $filedir,"videofilem4a");
            }
			//标清----高清分界线
			if (substr_count($videofilehd, "station") > 0) {
                $videofilehd = MoveThumbPhoto($videofilehd, $this->member_id, $pano_id, $filedir,"videohd");
            }
			if (substr_count($videofilehdwebm, "station") > 0) {
                $videofilehdwebm = MoveThumbPhoto($videofilehdwebm, $this->member_id, $pano_id, $filedir,"videofilehdwebm");
            }
			if (substr_count($videofilehdm4a, "station") > 0) {
                $videofilehdm4a = MoveThumbPhoto($videofilehdm4a, $this->member_id, $pano_id, $filedir,"videofilehdm4a");
            }
			//end
			
			
			
			if($thumb=="#") {
				$thumbfront = APP_ROOT."/Public/member/images/pano/pano.jpg";
				$thumbfile = APP_ROOT."/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id . "/" . $filedir. "/thumb.jpg";
				lfthumb($thumbfront,$thumbfile);
				$thumb = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id . "/" . $filedir. "/thumb.jpg";
			} elseif (substr_count($thumb, "station") > 0) {
                $thumb = MoveThumbPhoto($thumb, $this->member_id, $pano_id, $filedir);
            } else {
                $thumb = CopyThumbPhoto($thumb, $this->member_id, $pano_id, $filedir);
            }

			if($videofileimg=="#") {
				    $videofileimgfront = APP_ROOT."/Public/pano/images/videofileimg.jpg";
					$videofileimgfile = APP_ROOT."/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id . "/" . $filedir."/videofileimg.jpg";
					copy($videofileimgfront,$videofileimgfile);
					$videofileimg = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id . "/" . $filedir."/videofileimg.jpg";
			} elseif (substr_count($videofileimg, "station") > 0) {
				$videofileimg = MoveThumbPhotovideo($videofileimg, $this->member_id, $pano_id, $filedir);
            } else {
				$videofileimg = CopyThumbPhotovideo($videofileimg, $this->member_id, $pano_id, $filedir);
            }
			
			
			if($videofilehdimg=="#") {
				    $videofilehdimgfront = APP_ROOT."/Public/pano/images/videofilehdimg.jpg";
					$videofilehdimgfile = APP_ROOT."/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id . "/" . $filedir."/videofilehdimg.jpg";
					copy($videofilehdimgfront,$videofilehdimgfile);

					$videofilehdimg = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id . "/" . $filedir."/videofilehdimg.jpg";		
			} elseif (substr_count($videofilehdimg, "station") > 0) {
                $videofilehdimg = MoveThumbPhotovideo($videofilehdimg, $this->member_id, $pano_id, $filedir);
            } else {
                $videofilehdimg = CopyThumbPhotovideo($videofilehdimg, $this->member_id, $pano_id, $filedir);
            }
			
			
			
            if(!is_file(APP_ROOT.$videofile)){
                $openvideo = 0;
            }

            $data = array(
                "title" => $title,
                "member_id" => $this->member_id,
                "pano_id" => $pano_id,
                "filedir" => $filedir,
                "thumb" => $thumb,
                
                "openvideo" => $openvideo,
                "videofile" => $videofile,
				"videofilewebm" => $videofilewebm,
				"videofilem4a" => $videofilem4a,
				"videofileimg" => $videofileimg,
				"videofilehd" => $videofilehd,
				"videofilehdwebm" => $videofilehdwebm,
				"videofilehdm4a" => $videofilehdm4a,
				"videofilehdimg" => $videofilehdimg,
				"house_detail_img" => $house_detail_img,
				"view_type" => $view_type,

                
            );
            $view_id = M("Pano_view")->add($data);
			//更新项目状态 start
			$videowhere = array(
                "id" => $pano_id,
                "member_id" => $this->member_id
            );
			$videodata = array(
                "member_id" => $this->member_id,
                "id" => $pano_id,             
                "openvideo" => $openvideo,              
            );
			M("Pano")->where($videowhere)->save($videodata);
			//更新项目状态  end

            $imgdir = APP_ROOT . "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/station";
            RemoveDirFiles($imgdir);

            $this->redirect("mkpreview", array("view_id" => $view_id));
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


    function edit() {
        $backurl = cookie("viewback");
        $this->assign('backurl', $backurl);

        $os = (DIRECTORY_SEPARATOR == '\\') ? "windows" : 'linux';
        $this->assign('os', $os);

        if (I("post.dopost") == "save") {
            $view_id = I("post.view_id");
            $this->assign("view_id", $view_id);
            $viewwhere = array(
                "id" => $view_id,
                "member_id" => $this->member_id
            );
            $viewrow = M("Pano_view")->where($viewwhere)->find();

            $pano_id = I('post.pano_id');
            $title = trim(I('post.title'));
            $thumb = I('post.thumb');
            $front = I('post.front');
            $back = I('post.back');
            $left = I('post.left');
            $right = I('post.right');
            $up = I('post.up');
            $down = I('post.down');
            $fov = I("post.fov");
            $hlookat = I("post.hlookat");
            $vlookat = I("post.vlookat");
            $openmusic = I("post.openmusic");
            $musicfile = I("post.musicfile");
            $musictimes = I("post.musictimes");
            $musicvolume = I("post.musicvolume");
            $fovmin = I('post.fovmin');
            $fovmax = I('post.fovmax');
            $open_limit = I('post.open_limit');
            $hlookatmin = I('post.hlookatmin');
            $hlookatmax = I('post.hlookatmax');
            $vlookatmin = I('post.vlookatmin');
            $vlookatmax = I('post.vlookatmax');
            $limitview = I('post.limitview');
            $house_detail_img = I('post.house_detail_img');
            $view_type = I('post.view_type');

            if($hlookatmin > $hlookatmax){
                $hlookatmax = $hlookatmax + 360;
            }
            if($hlookatmin>180){
                $hlookatmin = $hlookatmin - 360;
                $hlookatmax = $hlookatmax - 360;
            }

            if ($title == "") {
                $this->error("请填写【全景场景名称】！");
                exit();
            }

            $is_edit = 0;
            foreach ($this->pano as $pk) {
                if (${$pk} == "#") {
                    ${$pk} = $viewrow[$pk];
                    $res = checkPano($viewrow[$pk]);
                    if($res == 1){
                        $is_edit = 1;
                    }
                } else {
                    $is_edit = 1;
                    if (substr_count(${$pk}, "station") > 0) {
                        ${$pk} = MoveViewPhoto(${$pk}, $this->member_id, $pano_id, $viewrow['filedir'], $pk);
                    }
                }
            }
            $pano_path = str_replace("pano_l", "pano_%s", $left);

            if ($thumb == "#") {
                //if(!empty($viewrow['thumb']))
				//{
				//	$thumb = $viewrow['thumb'];
				//}
				//else
				//{
					$thumbfront = APP_ROOT.$viewrow['front'];
					$thumbfile = APP_ROOT."/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id . "/" . $viewrow['filedir']. "/thumb.jpg";
					lfthumb($thumbfront,$thumbfile);
					$thumb = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id . "/" . $viewrow['filedir']. "/thumb.jpg";
				//}
            } else if (substr_count($thumb, "station") > 0) {
                $thumb = MoveThumbPhoto($thumb, $this->member_id, $pano_id, $viewrow['filedir']);
            } else {
                $thumb = CopyThumbPhoto($thumb, $this->member_id, $pano_id, $viewrow['filedir']);
            }

            if (substr_count($musicfile, "station") > 0) {
                $musicfile = MoveThumbPhoto($musicfile, $this->member_id, $pano_id, $viewrow['filedir'],"music");
            }
            if(!is_file(APP_ROOT.$musicfile)){
                $openmusic = 0;
            }

            $data = array(
                "title" => $title,
                "thumb" => $thumb,
                "front" => $front,
                "back" => $back,
                "left" => $left,
                "right" => $right,
                "up" => $up,
                "down" => $down,
                "pano_path" => $pano_path,
                "fov" => $fov,
                "hlookat" => $hlookat,
                "vlookat" => $vlookat,
                "openmusic" => $openmusic,
                "musicfile" => $musicfile,
                "musictimes" => $musictimes,
                "musicvolume" => $musicvolume,
                'fovmin' => $fovmin,
                'fovmax' => $fovmax,
                'hlookatmin'=>$hlookatmin,
                'hlookatmax' => $hlookatmax,
                'vlookatmin' => $vlookatmin,
                "vlookatmax" => $vlookatmax,
                'open_limit' => $open_limit,
                'limitview' => $limitview,
                'house_detail_img' => $house_detail_img,
                'view_type' => $view_type,
            );
            
            
            $where = array(
                "id" => $view_id,
                "pano_id" => $pano_id,
                "member_id" => $this->member_id
            );
            M("Pano_view")->where($where)->save($data);

            $imgdir = APP_ROOT . "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/station";
            RemoveDirFiles($imgdir);

            if ($is_edit == 1) {
                $this->success("【{$title}】修改成功！", U("mkpreview", array("view_id" => $view_id)));
            } else {
                $filedir = APP_ROOT . "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $viewrow["pano_id"] . "/" . $viewrow["filedir"] . "/scene";
                if(is_dir($filedir)){
                    clearPian($filedir);
                }
                $this->success("【{$title}】修改成功！", $backurl);
            }
            exit();
        }

        $view_id = I("get.view_id");
        $this->assign("view_id", $view_id);
        $viewwhere = array(
            "id" => $view_id,
            "member_id" => $this->member_id
        );
        $viewrow = M("Pano_view")->where($viewwhere)->find();
        $this->assign("viewrow", $viewrow);

        $panowhere = array(
            "id" => $viewrow['pano_id'],
            "member_id" => $this->member_id
        );
        $panorow = M("Pano")->where($panowhere)->find();
        $this->assign("panorow", $panorow);
        $this->assign("pano_id", $viewrow['pano_id']);

        $this->display();
    }
	
	function edit3d() {
        $backurl = cookie("viewback");
        $this->assign('backurl', $backurl);

        $os = (DIRECTORY_SEPARATOR == '\\') ? "windows" : 'linux';
        $this->assign('os', $os);

        if (I("post.dopost") == "save") {
            $view_id = I("post.view_id");
            $this->assign("view_id", $view_id);
            $viewwhere = array(
                "id" => $view_id,
                "member_id" => $this->member_id
            );
            $viewrow = M("Pano_view")->where($viewwhere)->find();

            $pano_id = I('post.pano_id');
            $title = trim(I('post.title'));
            $thumb = I('post.thumb');
            $front = I('post.front');
            $back = I('post.back');
            $left = I('post.left');
            $right = I('post.right');
            $up = I('post.up');
            $down = I('post.down');
            $fov = I("post.fov");
            $hlookat = I("post.hlookat");
            $vlookat = I("post.vlookat");
            $openmusic = I("post.openmusic");
            $musicfile = I("post.musicfile");
            $musictimes = I("post.musictimes");
            $musicvolume = I("post.musicvolume");
            $fovmin = I('post.fovmin');
            $fovmax = I('post.fovmax');
            $open_limit = I('post.open_limit');
            $hlookatmin = I('post.hlookatmin');
            $hlookatmax = I('post.hlookatmax');
            $vlookatmin = I('post.vlookatmin');
            $vlookatmax = I('post.vlookatmax');
            $limitview = I('post.limitview');
            
            if($hlookatmin > $hlookatmax){
                $hlookatmax = $hlookatmax + 360;
            }
            if($hlookatmin>180){
                $hlookatmin = $hlookatmin - 360;
                $hlookatmax = $hlookatmax - 360;
            }

            if ($title == "") {
                $this->error("请填写【全景场景名称】！");
                exit();
            }

            $is_edit = 0;
            foreach ($this->pano as $pk) {
                if (${$pk} == "#") {
                    ${$pk} = $viewrow[$pk];
                    $res = checkPano($viewrow[$pk]);
                    if($res == 1){
                        $is_edit = 1;
                    }
                } else {
                    $is_edit = 1;
                    if (substr_count(${$pk}, "station") > 0) {
                        ${$pk} = MoveViewPhoto(${$pk}, $this->member_id, $pano_id, $viewrow['filedir'], $pk);
                    }
                }
            }
            $pano_path = str_replace("pano_l", "pano_%s", $left);

            if ($thumb == "#") {
                //if(!empty($viewrow['thumb']))
				//{
				//	$thumb = $viewrow['thumb'];
				//}
				//else
				//{
					$thumbfront = APP_ROOT.$viewrow['front'];
					$thumbfile = APP_ROOT."/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id . "/" . $viewrow['filedir']. "/thumb.jpg";
					lfthumb($thumbfront,$thumbfile);
					$thumb = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id . "/" . $viewrow['filedir']. "/thumb.jpg";
				//}
            } else if (substr_count($thumb, "station") > 0) {
                $thumb = MoveThumbPhoto($thumb, $this->member_id, $pano_id, $viewrow['filedir']);
            } else {
                $thumb = CopyThumbPhoto($thumb, $this->member_id, $pano_id, $viewrow['filedir']);
            }

            if (substr_count($musicfile, "station") > 0) {
                $musicfile = MoveThumbPhoto($musicfile, $this->member_id, $pano_id, $viewrow['filedir'],"music");
            }
            if(!is_file(APP_ROOT.$musicfile)){
                $openmusic = 0;
            }

            $data = array(
                "title" => $title,
                "thumb" => $thumb,
                "front" => $front,
                "back" => $back,
                "left" => $left,
                "right" => $right,
                "up" => $up,
                "down" => $down,
                "pano_path" => $pano_path,
                "fov" => $fov,
                "hlookat" => $hlookat,
                "vlookat" => $vlookat,
                "openmusic" => $openmusic,
                "musicfile" => $musicfile,
                "musictimes" => $musictimes,
                "musicvolume" => $musicvolume,
                'fovmin' => $fovmin,
                'fovmax' => $fovmax,
                'hlookatmin'=>$hlookatmin,
                'hlookatmax' => $hlookatmax,
                'vlookatmin' => $vlookatmin,
                "vlookatmax" => $vlookatmax,
                'open_limit' => $open_limit,
                'limitview' => $limitview
            );
            
            
            $where = array(
                "id" => $view_id,
                "pano_id" => $pano_id,
                "member_id" => $this->member_id
            );
            M("Pano_view")->where($where)->save($data);

			$imgdir = APP_ROOT . "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/station";
			$img3ddir = APP_ROOT . "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/cutstation/";
			createFolder($img3ddir);
            copyFolder($imgdir, $img3ddir);	
            RemoveDirFiles($imgdir);


            if ($is_edit == 1) {
                $this->success("【{$title}】修改成功！", U("mkpreview3d", array("view_id" => $view_id)));
            } else {
                $filedir = APP_ROOT . "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $viewrow["pano_id"] . "/" . $viewrow["filedir"] . "/scene";
                if(is_dir($filedir)){
                    clearPian($filedir);
                }
                $this->success("【{$title}】修改成功！", $backurl);
            }
            exit();
        }

        $view_id = I("get.view_id");
        $this->assign("view_id", $view_id);
        $viewwhere = array(
            "id" => $view_id,
            "member_id" => $this->member_id
        );
        $viewrow = M("Pano_view")->where($viewwhere)->find();
        $this->assign("viewrow", $viewrow);

        $panowhere = array(
            "id" => $viewrow['pano_id'],
            "member_id" => $this->member_id
        );
        $panorow = M("Pano")->where($panowhere)->find();
        $this->assign("panorow", $panorow);
        $this->assign("pano_id", $viewrow['pano_id']);

        $this->display();
    }

	function edit_video() {
        $backurl = cookie("viewback");
        $this->assign('backurl', $backurl);

        $os = (DIRECTORY_SEPARATOR == '\\') ? "windows" : 'linux';
        $this->assign('os', $os);

        if (I("post.dopost") == "save") {
            $view_id = I("post.view_id");
            $this->assign("view_id", $view_id);
            $viewwhere = array(
                "id" => $view_id,
                "member_id" => $this->member_id
            );
            $viewrow = M("Pano_view")->where($viewwhere)->find();

            $pano_id = I('post.pano_id');
            $title = trim(I('post.title'));
            $thumb = I('post.thumb');
            $openvideo = I("post.openvideo");
            $videofile = I("post.videofile");
			$videofilewebm = I("post.videofilewebm");
			$videofilem4a = I("post.videofilem4a");
			$videofileimg = I("post.videofileimg");
			$videofilehd = I("post.videofilehd");
			$videofilehdwebm = I("post.videofilehdwebm");
			$videofilehdm4a = I("post.videofilehdm4a");
			$videofilehdimg = I("post.videofilehdimg");
            

            if ($title == "") {
                $this->error("请填写【全景视频名称】！");
                exit();
            }

            $is_edit = 0;
            foreach ($this->pano as $pk) {
                if (${$pk} == "#") {
                    ${$pk} = $viewrow[$pk];
                    $res = checkPano($viewrow[$pk]);
                    if($res == 1){
                        $is_edit = 1;
                    }
                } else {
                    $is_edit = 1;
                    if (substr_count(${$pk}, "station") > 0) {
                        ${$pk} = MoveViewPhoto(${$pk}, $this->member_id, $pano_id, $viewrow['filedir'], $pk);
                    }
                }
            }

            if ($thumb == "#") {
                //if(!empty($viewrow['thumb']))
				//{
				//	$thumb = $viewrow['thumb'];
				//}
				//else
				//{
					$thumbfront = APP_ROOT.$viewrow['front'];
					$thumbfile = APP_ROOT."/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id . "/" . $viewrow['filedir']. "/thumb.jpg";
					lfthumb($thumbfront,$thumbfile);
					$thumb = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id . "/" . $viewrow['filedir']. "/thumb.jpg";
				//}
            } else if (substr_count($thumb, "station") > 0) {
                $thumb = MoveThumbPhoto($thumb, $this->member_id, $pano_id, $viewrow['filedir']);
            } else {
                $thumb = CopyThumbPhoto($thumb, $this->member_id, $pano_id, $viewrow['filedir']);
            }

			if($videofileimg=="#") {
				    $videofileimgfront = APP_ROOT.$viewrow['videofileimg'];
					$videofileimgfile = APP_ROOT."/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id . "/" . $viewrow['filedir']."/videofileimg.jpg";
					videothumb($videofileimgfront,$videofileimgfile);
					$videofileimg = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id . "/" . $viewrow['filedir']."/videofileimg.jpg";
			} elseif (substr_count($videofileimg, "station") > 0) {
				$videofileimg = MoveThumbPhotovideo($videofileimg, $this->member_id, $pano_id, $viewrow['filedir']);
            } else {
				$videofileimg = CopyThumbPhotovideo($videofileimg, $this->member_id, $pano_id, $viewrow['filedir']);
            }
			if($videofilehdimg=="#") {
				    $videofilehdimgfront = APP_ROOT.$viewrow['videofilehdimg'];
					$videofilehdimgfile = APP_ROOT."/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id . "/" . $viewrow['filedir']."/videofilehdimg.jpg";
					videothumb($videofilehdimgfront,$videofilehdimgfile);
					$videofilehdimg = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id . "/" . $viewrow['filedir']."/videofilehdimg.jpg";		
			} elseif (substr_count($videofilehdimg, "station") > 0) {
                $videofilehdimg = MoveThumbPhotovideo($videofilehdimg, $this->member_id, $pano_id, $viewrow['filedir']);
            } else {
                $videofilehdimg = CopyThumbPhotovideo($videofilehdimg, $this->member_id, $pano_id, $viewrow['filedir']);
            }
			
			
			
			
			
            if (substr_count($videofile, "station") > 0) {
                $videofile = MoveThumbPhoto($videofile, $this->member_id, $pano_id, $viewrow['filedir'],"video");
            }
			if (substr_count($videofilewebm, "station") > 0) {
                $videofilewebm = MoveThumbPhoto($videofilewebm, $this->member_id, $pano_id, $viewrow['filedir'],"videofilewebm");
            }
			if (substr_count($videofilem4a, "station") > 0) {
                $videofilem4a = MoveThumbPhoto($videofilem4a, $this->member_id, $pano_id, $viewrow['filedir'],"videofilem4a");
            }
			//标清----高清分界线
			if (substr_count($videofilehd, "station") > 0) {
                $videofilehd = MoveThumbPhoto($videofilehd, $this->member_id, $pano_id, $viewrow['filedir'],"videohd");
            }
			if (substr_count($videofilehdwebm, "station") > 0) {
                $videofilehdwebm = MoveThumbPhoto($videofilehdwebm, $this->member_id, $pano_id, $viewrow['filedir'],"videofilehdwebm");
            }
			if (substr_count($videofilehdm4a, "station") > 0) {
                $videofilehdm4a = MoveThumbPhoto($videofilehdm4a, $this->member_id, $pano_id, $viewrow['filedir'],"videofilehdm4a");
            }
			//end

            $data = array(
                "title" => $title,
                "thumb" => $thumb,
                "openvideo" => $openvideo,
                "videofile" => $videofile,
				"videofilewebm" => $videofilewebm,
				"videofilem4a" => $videofilem4a,
				"videofileimg" => $videofileimg,
				"videofilehd" => $videofilehd,
				"videofilehdwebm" => $videofilehdwebm,
				"videofilehdm4a" => $videofilehdm4a,
				"videofilehdimg" => $videofilehdimg,
            );
            
            
            $where = array(
                "id" => $view_id,
                "pano_id" => $pano_id,
                "member_id" => $this->member_id
            );
            M("Pano_view")->where($where)->save($data);
			//更新项目状态 start
			$videowhere = array(
                "id" => $pano_id,
                "member_id" => $this->member_id
            );
			$videodata = array(
                "member_id" => $this->member_id,
                "id" => $pano_id,             
                "openvideo" => $openvideo,              
            );
			M("Pano")->where($videowhere)->save($videodata);
			//更新项目状态  end

            $imgdir = APP_ROOT . "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/station";
            RemoveDirFiles($imgdir);

            if ($is_edit == 1) {
                $this->success("【{$title}】修改成功！", U("mkpreview", array("view_id" => $view_id)));
            } else {
                $filedir = APP_ROOT . "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $viewrow["pano_id"] . "/" . $viewrow["filedir"] . "/scene";
                if(is_dir($filedir)){
                    clearPian($filedir);
                }
                $this->success("【{$title}】修改成功！", $backurl);
            }
            exit();
        }

        $view_id = I("get.view_id");
        $this->assign("view_id", $view_id);
        $viewwhere = array(
            "id" => $view_id,
            "member_id" => $this->member_id
        );
        $viewrow = M("Pano_view")->where($viewwhere)->find();
        $this->assign("viewrow", $viewrow);

        $panowhere = array(
            "id" => $viewrow['pano_id'],
            "member_id" => $this->member_id
        );
        $panorow = M("Pano")->where($panowhere)->find();
        $this->assign("panorow", $panorow);
        $this->assign("pano_id", $viewrow['pano_id']);

        $this->display();
    }
	
    function mkpreview() {
        $backurl = cookie("viewback");
        $this->assign('backurl', $backurl);

        $view_id = I("get.view_id");
        $this->assign("view_id", $view_id);
        $viewwhere = array(
            "id" => $view_id,
            "member_id" => $this->member_id
        );
        $viewrow = M("Pano_view")->where($viewwhere)->find();
        $this->assign("viewrow", $viewrow);

        $this->display();
    }
	function mkpreview3d() {
        $backurl = cookie("viewback");
        $this->assign('backurl', $backurl);

        $view_id = I("get.view_id");
        $this->assign("view_id", $view_id);
        $viewwhere = array(
            "id" => $view_id,
            "member_id" => $this->member_id
        );
        $viewrow = M("Pano_view")->where($viewwhere)->find();
        $this->assign("viewrow", $viewrow);

        $this->display();
    }

    function del() {
        $backurl = cookie("viewback");
        $this->assign('backurl', $backurl);

        $view_id = I("get.view_id");
        $this->assign("view_id", $view_id);
        $viewwhere = array(
            "id" => $view_id,
            "member_id" => $this->member_id
        );
        $viewrow = M("Pano_view")->where($viewwhere)->find();
        if (is_array($viewrow)) {
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
			
		    //720环物 del start
			if($viewrow['open_ring']==1){
			$delringwhere = array(
                "pano_id" => $viewrow['pano_id'],
                "member_id" => $this->member_id
            );	
			M("Pano_cube_store")->where($delringwhere)->delete();
			}
			//720环物 del end

            $filedir = APP_ROOT . ViewDir($this->member_id, $view_id);
            RemoveDirFiles($filedir);
            M("Pano_view")->where($viewwhere)->delete();
            $paiwhere = array(
                "pano_id" => $viewrow['pano_id'],
                "member_id" => $this->member_id
            );
            $pairow = M("Pano_view")->where($paiwhere)->order("sort")->select();
            $i = 1;
            foreach ($pairow as $pai) {
                $bianwhere = array(
                    "id" => $pai['id'],
                    "member_id" => $this->member_id
                );
                $biandata = array(
                    "sort" => $i
                );
                M("Pano_view")->where($bianwhere)->save($biandata);
                $i++;
            }
            $this->success("【{$viewrow['title']}】删除成功！", $backurl);
        }
    }

    function changeview() {
        cookie("viewback", __SELF__);
        $pano_id = I("get.pano_id");
        $this->assign('pano_id', $pano_id);

        $panowhere = array(
            "id" => $pano_id,
            "member_id" => $this->member_id
        );
        $panorow = M("Pano")->where($panowhere)->find();
        $this->assign('panorow', $panorow);

        $viewwhere = array(
            "pano_id" => $pano_id
        );
        $viewlist = M("Pano_view")->where($viewwhere)->order("sort")->select();
        $this->assign('viewlist', $viewlist);

        $this->display();
    }

    function firstview() {
        cookie("viewback", __SELF__);
        $pano_id = I("get.pano_id");
        $this->assign('pano_id', $pano_id);

        $panowhere = array(
            "id" => $pano_id,
            "member_id" => $this->member_id
        );
        $panorow = M("Pano")->where($panowhere)->find();
        $this->assign('panorow', $panorow);

        $viewwhere = array(
            "pano_id" => $pano_id
        );
        $viewlist = M("Pano_view")->where($viewwhere)->order("sort")->select();
        $this->assign('viewlist', $viewlist);

        $this->display();
    }

    function lookat(){
        $view_id = I("get.view_id");
        $front = explode("?",I("get.pic0"));
        $back = explode("?",I("get.pic1"));
        $left = explode("?",I("get.pic2"));
        $right = explode("?",I("get.pic3"));
        $up = explode("?",I("get.pic4"));
        $down = explode("?",I("get.pic5"));

        $hlookat = I("get.hlookat");
        $vlookat = I("get.vlookat");

        cookie("front", $front[0]);
        cookie("back", $back[0]);
        cookie("left", $left[0]);
        cookie("right", $right[0]);
        cookie("up", $up[0]);
        cookie("down", $down[0]);
        cookie("hlookat", $hlookat);
        cookie("vlookat", $vlookat);

        $xmlurl = U('lookatxml', array('view_id' => $view_id));
        $xmlscript = "embedpano({swf:\"__PUBLIC__/pano/pano.swf\", xml:\"$xmlurl\", target:\"pano\", html5:\"auto\", passQueryParameters:true});";
        $this->assign('xmlscript', $xmlscript);

        $this->assign("view_id",$view_id);
        $this->display();
    }
    function lookatxml(){
        $view_id = I("get.view_id");

        $this->display('./App/Tpl/Member/View/lookat.xml', 'utf-8', 'text/xml');
    }
    
    function vat(){
        $view_id = I("get.view_id");
        $front = explode("?",I("get.pic0"));
        $back = explode("?",I("get.pic1"));
        $left = explode("?",I("get.pic2"));
        $right = explode("?",I("get.pic3"));
        $up = explode("?",I("get.pic4"));
        $down = explode("?",I("get.pic5"));

        $hlookat = I("get.hlookat");
        $vlookat = I("get.vlookat");

        cookie("front", $front[0]);
        cookie("back", $back[0]);
        cookie("left", $left[0]);
        cookie("right", $right[0]);
        cookie("up", $up[0]);
        cookie("down", $down[0]);
        cookie("hlookat", $hlookat);
        cookie("vlookat", $vlookat);

        $xmlurl = U('vatxml', array('view_id' => $view_id));
        $xmlscript = "embedpano({swf:\"__PUBLIC__/pano/pano.swf\", xml:\"$xmlurl\", target:\"pano\", html5:\"auto\", passQueryParameters:true});";
        $this->assign('xmlscript', $xmlscript);

        $this->assign("view_id",$view_id);
        $this->display();
    }
    
    function vatxml(){
        $view_id = I("get.view_id");
        $this->display('./App/Tpl/Member/View/vat.xml', 'utf-8', 'text/xml');
    }
    
    function hat(){
        $view_id = I("get.view_id");
        $front = explode("?",I("get.pic0"));
        $back = explode("?",I("get.pic1"));
        $left = explode("?",I("get.pic2"));
        $right = explode("?",I("get.pic3"));
        $up = explode("?",I("get.pic4"));
        $down = explode("?",I("get.pic5"));

        $hlookat = I("get.hlookat");
        $vlookat = I("get.vlookat");

        cookie("front", $front[0]);
        cookie("back", $back[0]);
        cookie("left", $left[0]);
        cookie("right", $right[0]);
        cookie("up", $up[0]);
        cookie("down", $down[0]);
        cookie("hlookat", $hlookat);
        cookie("vlookat", $vlookat);

        $xmlurl = U('hatxml', array('view_id' => $view_id));
        $xmlscript = "embedpano({swf:\"__PUBLIC__/pano/pano.swf\", xml:\"$xmlurl\", target:\"pano\", html5:\"auto\", passQueryParameters:true});";
        $this->assign('xmlscript', $xmlscript);

        $this->assign("view_id",$view_id);
        $this->display();
    }
    
    function hatxml(){
        $view_id = I("get.view_id");
        $this->display('./App/Tpl/Member/View/hat.xml', 'utf-8', 'text/xml');
    }
	
	
	
	
	//环物 2017.10.25
	function add_ring() {
        $backurl = cookie("viewback");
        $this->assign('backurl', $backurl);
		

        if (I("post.dopost") == "save") {
            $pano_id = I('post.pano_id');
            $title = trim(I('post.title'));
			$thumb = I('post.thumb');
            if ($title == "") {
                $this->error("请写好【360环物名称】！");
                exit();
            }
			$filedir = substr(md5(time()), 5, 8);
			if($thumb=="#") {
				$thumbfront = APP_ROOT."/Public/member/images/pano/pano.jpg";
				$thumbfile = APP_ROOT."/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id . "/" . $filedir. "/thumb.jpg";
				lfthumb($thumbfront,$thumbfile);
				$thumb = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id . "/" . $filedir. "/thumb.jpg";
			} elseif (substr_count($thumb, "station") > 0) {
                $thumb = MoveThumbPhoto($thumb, $this->member_id, $pano_id, $filedir);
            } else {
                $thumb = CopyThumbPhoto($thumb, $this->member_id, $pano_id, $filedir);
            }
			
			
			$datapano = array(
                "title" => $title,
                "member_id" => $this->member_id,
                "pano_id" => $pano_id,
				"thumb" => $thumb,
				"filedir" => $filedir,
				"open_ring" => 1
			);
			$view_id = M("Pano_view")->add($datapano);
			
			$ringpano = array(
                "member_id" => $this->member_id,
                "pano_id" => $pano_id,
				"open_ring" => 1
			);
			$ringwhere = array(
                "id" => $pano_id,
                "member_id" => $this->member_id
            );
			M("Pano")->where($ringwhere)->save($ringpano);
			
			
			
            $cubewhere = array(
                "pano_id" => $pano_id,
                "member_id" => $this->member_id
            );
            $cubelist = M("Pano_cube_store")->where($cubewhere)->find();
            if ($cubelist['is_ok'] == 0) {
                $cubetitle = I("post.title");
                $cubedata = array(
                    "title" => $cubetitle,
                    "is_ok" => 1
                );
                M("Pano_cube_store")->where($cubewhere)->save($cubedata);
            }
      
            $this->success('添加360环物成功', U("view/index", array("pano_id" => $pano_id)));

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
	
	
	
	function edit_ring(){
        $backurl = cookie("viewback");
        $this->assign('backurl', $backurl);

        if(I("post.dopost") == "save"){
            $pano_id = I('post.pano_id');
            $view_id = I('post.view_id');
            $title = trim(I('post.title'));
            $thumb = I('post.thumb');
			
            $viewwhere = array(
                "id" => $view_id,
                "member_id" => $this->member_id
            );
            $viewrow = M("Pano_view")->where($viewwhere)->find();

		 if ($thumb == "#") {
                //if(!empty($viewrow['thumb']))
				//{
				//	$thumb = $viewrow['thumb'];
				//}
				//else
				//{
					$thumbfront = APP_ROOT.$viewrow['front'];
					$thumbfile = APP_ROOT."/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id . "/" . $viewrow['filedir']. "/thumb.jpg";
					lfthumb($thumbfront,$thumbfile);
					$thumb = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id . "/" . $viewrow['filedir']. "/thumb.jpg";
				//}
            } else if (substr_count($thumb, "station") > 0) {
                $thumb = MoveThumbPhoto($thumb, $this->member_id, $pano_id, $viewrow['filedir']);
            } else {
                $thumb = CopyThumbPhoto($thumb, $this->member_id, $pano_id, $viewrow['filedir']);
            }	
			
			
			
            if ($title == "") {
                $this->error("请写好【360环物名称】！");
                exit();
            }
			
			$viewwhere = array(
				"id" => $view_id,
                "pano_id" => $pano_id,
                "member_id" => $this->member_id
            );
			$viewdata = array(
			    "id" => $view_id,
                "member_id" => $this->member_id,
                "pano_id" => $pano_id,
				"title" => $title,
				"thumb" => $thumb
			);
			M("Pano_view")->where($viewwhere)->save($viewdata);
			
			
            $data = array(
                "title" => $title
            );
            $where = array(
                "pano_id" => $pano_id,
                "member_id" => $this->member_id
            );
			M("Pano_cube_store")->where($where)->save($data);
			
			$this->success("【{$title}】修改成功！", $backurl);
            exit();
        }
		
		$pano_id = I("get.pano_id");
        $this->assign('pano_id', $pano_id);
        $panowhere = array(
            "id" => $pano_id,
            "member_id" => $this->member_id
        );
        $panorow = M("Pano")->where($panowhere)->find();		

        $viewrow = D("Panoview")->GetOne($view_id,$this->member_id);
        $this->assign('viewrow', $viewrow);
        $pano_id = $viewrow['pano_id'];
        $this->assign('pano_id', $pano_id);

        $panorow = D("Pano")->GetOne($pano_id,$this->member_id);
        $this->assign('panorow', $panorow);

        

       

		
		$view_id = I("get.view_id");
		
        $this->assign("view_id", $view_id);
        $viewwhere = array(
            "id" => $view_id,
            "member_id" => $this->member_id
        );
        $viewrow = M("Pano_view")->where($viewwhere)->find();
        $this->assign("viewrow", $viewrow);

        $panowhere = array(
            "id" => $viewrow['pano_id'],
            "member_id" => $this->member_id
        );
        $panorow = M("Pano")->where($panowhere)->find();
        $this->assign("panorow", $panorow);
        $this->assign("pano_id", $viewrow['pano_id']);

        $this->display();
    }

	 function readcube() {
        $pano_id = I('get.pano_id');
        $where = array(
            "pano_id" => $pano_id
        );
        $cubelist = M("Pano_cube_store")->where($where)->find();
        $this->assign("row", $cubelist);

        $end = array(
            0 => "png",
            1 => "gif",
            2 => "jpg"
        );
        $this->assign("end", $end);

        $speed = I("get.speed");

        if ($speed == "") {
            $speed = 5;
        }
        $speedarr = array();
        $speedarr[1] = 0.4;
        $speedarr[2] = 0.35;
        $speedarr[3] = 0.3;
        $speedarr[4] = 0.25;
        $speedarr[5] = 0.2;
        $speedarr[6] = 0.15;
        $speedarr[7] = 0.1;
        $speedarr[8] = 0.05;
        $speedarr[9] = 0.03;
        $speedarr[10] = 0.001;

        $this->assign('speed', $speedarr[$speed]);

        $fd = I("get.fd");
        if($fd == 1){
            $fd = -1;
        }else{
            $fd = 1;
        }

        $this->assign('fd', $fd);

        $this->display('./App/Tpl/Member/View/readcube.xml', 'utf-8', 'text/xml');
    }
	//2018.3.1   720环物
    function add_720ring() {
        $backurl = cookie("viewback");
        $this->assign('backurl', $backurl);

        $os = (DIRECTORY_SEPARATOR == '\\') ? "windows" : 'linux';
        $this->assign('os', $os);

        if (I("post.dopost") == "save") {
            $pano_id = I('post.pano_id');
            $title = trim(I('post.title'));
			$open720ring = I('post.open720ring');
            $thumb = I('post.thumb');
			$obj720ring = I('post.obj720ring');
			$mtl720ring = I('post.mtl720ring');
			$thumb720ring = I('post.thumb720ring');
			$bg720ring = I('post.bg720ring');
            $openmusic = I("post.openmusic");
            $musicfile = I("post.musicfile");
            
            

            if ($title == "") {
                $this->error("请填写【720环物名称】！");
                exit();
            }

            $viewwhere = array(
                "pano_id" => $pano_id,
                "member_id" => $this->member_id
            );
            $viewrow = M("Pano_view ")->where($viewwhere)->select();
            if (is_array($viewrow)) {
                $m = count($viewrow);
                $sort = $m + 1;
                $first_read = 0;
            } else {
                $sort = 1;
                $first_read = 1;
            }

            $filedir = substr(md5(time()), 5, 8);

           
			
			
			if (substr_count($obj720ring, "station") > 0) {
                $obj720ring = MoveThumbPhoto($obj720ring, $this->member_id, $pano_id, $filedir,"model");
            }
			if (substr_count($mtl720ring, "station") > 0) {
                $mtl720ring = MoveThumbPhoto($mtl720ring, $this->member_id, $pano_id, $filedir,"model");
            }
			
			if($thumb=="#") {
				copy(APP_ROOT . "/Public/member/images/pano/pano.jpg", APP_ROOT ."/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id . "/" . $filedir . "/thumb.jpg");
				$thumb = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id . "/" . $filedir. "/thumb.jpg";
			} elseif (substr_count($thumb, "station") > 0) {
                $thumb = MoveThumbPhoto($thumb, $this->member_id, $pano_id, $filedir);
            } else {
                $thumb = CopyThumbPhoto($thumb, $this->member_id, $pano_id, $filedir);
            }
			
			if($thumb720ring=="#") {
				copy(APP_ROOT . "/Public/member/images/pano/pano.jpg", APP_ROOT ."/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id . "/" . $filedir . "/model.jpg");
				$thumb720ring = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id . "/" . $filedir. "/model.jpg";				
			} elseif (substr_count($thumb720ring, "station") > 0) {
                $thumb720ring = MoveThumbPhoto($thumb720ring, $this->member_id, $pano_id, $filedir,"model");
            } else {
                $thumb720ring = CopyThumbPhoto($thumb720ring, $this->member_id, $pano_id, $filedir,"model");
            }
			
			if($bg720ring=="#") {
				copy(APP_ROOT . "/Public/member/images/pano/pano.jpg", APP_ROOT ."/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id . "/" . $filedir . "/background.jpg");
				$bg720ring = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id . "/" . $filedir. "/background.jpg";				
			} elseif (substr_count($bg720ring, "station") > 0) {
                $bg720ring = MoveThumbPhoto($bg720ring, $this->member_id, $pano_id, $filedir,"background");
            } else {
                $bg720ring = CopyThumbPhoto($bg720ring, $this->member_id, $pano_id, $filedir,"background");
            }
            

            if (substr_count($musicfile, "station") > 0) {
                $musicfile = MoveThumbPhoto($musicfile, $this->member_id, $pano_id, $filedir,"music");
            }
            if(!is_file(APP_ROOT.$musicfile)){
                $openmusic = 0;
            }

            $data = array(
                "title" => $title,
                "member_id" => $this->member_id,
                "pano_id" => $pano_id,
                "filedir" => $filedir,
                "thumb" => $thumb,
				"open720ring" => $open720ring,
				"obj720ring" => $obj720ring,
				"mtl720ring" => $mtl720ring,
				"thumb720ring" => $thumb720ring,
				"bg720ring" => $bg720ring,
                "openmusic" => $openmusic,
                "musicfile" => $musicfile,
                
            );
            $view_id = M("Pano_view")->add($data);
			
			$ring720pano = array(
                "member_id" => $this->member_id,
                "pano_id" => $pano_id,
				"open720ring" => $open720ring,
			);
			$ring720where = array(
                "id" => $pano_id,
                "member_id" => $this->member_id
            );
			M("Pano")->where($ring720where)->save($ring720pano);
			//echo M('Pano_view')->_sql();
			//echo "--------------";
			//echo M('Pano')->_sql();
			//exit();

            $imgdir = APP_ROOT . "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/station";
            RemoveDirFiles($imgdir);

            $this->redirect("mkpreview", array("view_id" => $view_id));
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
	//2018.3.1   720环物
	function edit_720ring() {
        $backurl = cookie("viewback");
        $this->assign('backurl', $backurl);

        $os = (DIRECTORY_SEPARATOR == '\\') ? "windows" : 'linux';
        $this->assign('os', $os);

        if (I("post.dopost") == "save") {
            $view_id = I("post.view_id");
            $this->assign("view_id", $view_id);
            $viewwhere = array(
                "id" => $view_id,
                "member_id" => $this->member_id
            );
            $viewrow = M("Pano_view")->where($viewwhere)->find();

            $pano_id = I('post.pano_id');
            $title = trim(I('post.title'));
			$open720ring = I('post.open720ring');
            $thumb = I('post.thumb');
            $obj720ring = I('post.obj720ring');
			$mtl720ring = I('post.mtl720ring');
			$thumb720ring = I('post.thumb720ring');
			$bg720ring = I('post.bg720ring');
            $openmusic = I("post.openmusic");
            $musicfile = I("post.musicfile");
			

            if ($title == "") {
                $this->error("请填写【720环物】！");
                exit();
            }

            
            
			if (substr_count($obj720ring, "station") > 0) {
                $obj720ring = MoveThumbPhoto($obj720ring, $this->member_id, $pano_id, $viewrow['filedir'],"model");
            }
			if (substr_count($mtl720ring, "station") > 0) {
                $mtl720ring = MoveThumbPhoto($mtl720ring, $this->member_id, $pano_id, $viewrow['filedir'],"model");
            }
			
			if($thumb=="#") {
				
				$thumb = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id . "/" . $viewrow['filedir']. "/thumb.jpg";
			} elseif (substr_count($thumb, "station") > 0) {
                $thumb = MoveThumbPhoto($thumb, $this->member_id, $pano_id, $viewrow['filedir']);
            } else {
                $thumb = CopyThumbPhoto($thumb, $this->member_id, $pano_id, $viewrow['filedir']);
            }
			
			if($thumb720ring=="#") {
				$thumb720ring = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id . "/" .$viewrow['filedir']. "/model.jpg";				
			} elseif (substr_count($thumb720ring, "station") > 0) {
                $thumb720ring = MoveThumbPhoto($thumb720ring, $this->member_id, $pano_id, $viewrow['filedir'],"model");
            } else {
                $thumb720ring = CopyThumbPhoto($thumb720ring, $this->member_id, $pano_id, $viewrow['filedir'],"model");
            }
			
			if($bg720ring=="#") {
				
				$bg720ring = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id . "/" . $viewrow['filedir']. "/background.jpg";				
			} elseif (substr_count($bg720ring, "station") > 0) {
                $bg720ring = MoveThumbPhoto($bg720ring, $this->member_id, $pano_id, $viewrow['filedir'],"background");
            } else {
                $bg720ring = CopyThumbPhoto($bg720ring, $this->member_id, $pano_id, $viewrow['filedir'],"background");
            }
            

            if (substr_count($musicfile, "station") > 0) {
                $musicfile = MoveThumbPhoto($musicfile, $this->member_id, $pano_id, $viewrow['filedir'],"music");
            }
            if(!is_file(APP_ROOT.$musicfile)){
                $openmusic = 0;
            }

            $data = array(
                "title" => $title,
                "member_id" => $this->member_id,
                "pano_id" => $pano_id,
                "thumb" => $thumb,
				"open720ring" => $open720ring,
				"obj720ring" => $obj720ring,
				"mtl720ring" => $mtl720ring,
				"thumb720ring" => $thumb720ring,
				"bg720ring" => $bg720ring,
                "openmusic" => $openmusic,
                "musicfile" => $musicfile,
            );
            
            
            $where = array(
                "id" => $view_id,
                "pano_id" => $pano_id,
                "member_id" => $this->member_id
            );
            M("Pano_view")->where($where)->save($data);
			
			
			$ring720pano = array(
                "member_id" => $this->member_id,
                "pano_id" => $pano_id,
				"open720ring" => $open720ring,
			);
			$ring720where = array(
                "id" => $pano_id,
                "member_id" => $this->member_id
            );
			M("Pano")->where($ring720where)->save($ring720pano);
			
			//echo M('Pano_view')->_sql();
			//echo "--------------";
			//echo M('Pano')->_sql();
			//exit();

            $imgdir = APP_ROOT . "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/station";
            RemoveDirFiles($imgdir);

            if ($is_edit == 1) {
                $this->success("【{$title}】修改成功！", U("mkpreview", array("view_id" => $view_id)));
            } else {
                $filedir = APP_ROOT . "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $viewrow["pano_id"] . "/" . $viewrow["filedir"] . "/scene";
                if(is_dir($filedir)){
                    clearPian($filedir);
                }
                $this->success("【{$title}】修改成功！", $backurl);
            }
            exit();
        }

        $view_id = I("get.view_id");
        $this->assign("view_id", $view_id);
        $viewwhere = array(
            "id" => $view_id,
            "member_id" => $this->member_id
        );
        $viewrow = M("Pano_view")->where($viewwhere)->find();
        $this->assign("viewrow", $viewrow);

        $panowhere = array(
            "id" => $viewrow['pano_id'],
            "member_id" => $this->member_id
        );
        $panorow = M("Pano")->where($panowhere)->find();
        $this->assign("panorow", $panorow);
        $this->assign("pano_id", $viewrow['pano_id']);

        $this->display();
    }
	//2018.3.1


}

?>
