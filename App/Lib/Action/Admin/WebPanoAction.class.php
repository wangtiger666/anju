<?php

class WebPanoAction extends AdminAction{

    function index(){
        cookie("back", __SELF__);
        import('ORG.Util.Page'); // 导入分页类

        $mm = M("yunweb_pano");
        $areaid = I("post.areaid");//地区
        $hangyeid = I("post.hangyeid");//行业
        $posid = I("post.posid");//推荐位

        $where=" 1 ";
        if($areaid){
        	$where .=" AND areaid=".$areaid ;
        }
        if($hangyeid){
        	$where .=" AND hangyeid=".$hangyeid;
        }
        if($posid){
        	$where .=" AND posid=".$posid;
        }

        $count = $mm->where($where)->count(); // 查询满足要求的总记录数
      	$Page = new Page($count, 15); // 实例化分页类 传入总记录数和每页显示的记录数
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
       	$list = $mm->where($where)->order("listorder")->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $show = $Page->show(); // 分页显示输出
       	
       	$tuijianwei = $this->tuijianwei($posid);//推荐位
		$hangye = $this->hangye($hangyeid);//行业
		$area = $this->area($areaid);//地区
        $this->assign('tuijianwei', $tuijianwei);
        $this->assign('hangye', $hangye);
        $this->assign('area', $area);
	
		$this->assign('group', $group); // 赋值数据集
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display(); // 输出模板
    }


    function del() {
        if ($_GET['id'] != "") {
            M("yunweb_pano")->where("id=" . $_GET['id'])->delete();
        }
        $this->success("删除成功！", U("index"));
    }

	function add()
	{
        if ($_POST['dopost'] == "save")
		{
            $title = I("post.title");
            $keywords = I("post.keywords");
            $description = I("post.description");
            $outlink = I("post.outlink");
            $posid = I("post.posid");
            $listorder = I("post.listorder");
            $status = I("post.status");
            $hangyeid = I("post.hangyeid");
            $areaid = I("post.areaid");
            if ($title == "") {
                $this->error("标题不能为空！");
                exit();
            }

            //图片处理
            $fileurl = "/uploads/artthumb";
			createFolder(APP_ROOT.$filedir);
			$file="";
			if(!empty($_POST['file']))
			{
				$arr = getimagesize(APP_ROOT.$_POST['file']);
                $width = $arr[0];
                $height = $arr[1];
                $imgtype = $arr[2];
				$file = ExecUpload($_POST['file'], '', $fileurl);
			}

			$addmap = array(
				 "title" => $title,
				 "keywords" => $keywords,
				 "description" => $description,
				 "outlink" => $outlink,
				 "posid" => $posid,
				 "listorder"=>$listorder,
				 "status" => $status,
	             "areaid"=>$areaid,
	             "hangyeid"=>$hangyeid,
	             "createtime" => time(),
	             "updatetime" => time(),
	             "thumb" => $file
	        );

           $address =  M("yunweb_pano")->add($addmap);

            if($address){
            	$this->success("添加成功！");
            }else{
            	$this->success("添加失败！");
            }
            exit();
		}

		$tuijianwei = $this->tuijianwei();//推荐位
		$hangye = $this->hangye();//行业
		$area = $this->area();//地区
        $this->assign('tuijianwei', $tuijianwei);
        $this->assign('hangye', $hangye);
        $this->assign('area', $area);
		$this->display();	
	}
	
	function edit()
	{
		$id = intval($_REQUEST['id']);

		if ($_POST['dopost'] == "save")
		{
            $title = I("post.title");
            $keywords = I("post.keywords");
            $description = I("post.description");
            $outlink = I("post.outlink");
            $posid = I("post.posid");
            $listorder = I("post.listorder");
            $status = I("post.status");
            $hangyeid = I("post.hangyeid");
            $areaid = I("post.areaid");
            if ($title == "") {
                $this->error("标题不能为空！");
                exit();
            }

            //图片处理
			$thumb ="";
            $fileurl = "/uploads/artthumb";
			if(!empty($_POST['image'])){
				$image = ExecUpload($_POST['image'], $_POST['old_image'], $fileurl);
				 M("yunweb_pano")->where(array("id"=>$id))->save(array("thumb"=>$image));
			}

			$addmap = array(
	            "title" => $title,
	            "keywords" => $keywords,
	            "description" => $description,
	            "outlink" => $outlink,
	            "posid"=>$posid,
	            "listorder"=>$listorder,
	            "status" => $status,
	            "hangyeid"=>$hangyeid,
	            "areaid"=>$areaid,
	            "updatetime" => time()
	        );
			$result = M("yunweb_pano")->where(array("id"=>$id))->data($addmap)->save();
            if($result){
            	$this->success("修改成功！");
            }else{
            	$this->success("修改失败！");
            }
            exit();
		}

		$info = M("yunweb_pano")->where(array("id"=>$id))->find();
		if($info){
			$areaid =$info['areaid'];
			$hangyeid =$info['hangyeid'];
			$posid =$info['posid'];
		}
		
		$tuijianwei = $this->tuijianwei($posid);//推荐位
		$hangye = $this->hangye($hangyeid);//行业
		$area = $this->area($areaid);//地区
        $this->assign('tuijianwei', $tuijianwei);
        $this->assign('hangye', $hangye);
        $this->assign('area', $area);
		$this->assign("info", $info);
		$this->assign("id", $id);
		$this->display();
	}
	
	function artopened()
	{
		$id = intval(I("get.id"));
		if(!empty($id))
		{
			M("yunweb_pano")->where(array("id"=>$id))->data(array("status"=>1))->save();
			$this->success("启用成功！", U("index"));
		}
	}
	
	function artdisabled()
	{
		$id = intval(I("get.id"));
		if(!empty($id))
		{
			M("yunweb_pano")->where(array("id"=>$id))->data(array("status"=>0))->save();
			$this->success("禁用成功！", U("index"));
		}
	}

	//推荐位函数
	function tuijianwei($posid)
	{
		 $group = '<select name="posid">
            <option value="">请选择</option>';
            $grouplist = M("yunweb_panoposition")->select();
            if(!empty($grouplist))
            {
                foreach($grouplist as $key=>$val)
                {
                    $seled = "";
                    if($posid==$val['id']) $seled = " selected";
                    $group .= "<option value=\"".$val['id']."\" $seled>".$val['name']."</option>";
                }
            }
        $group .= "</select>";
        return $group;
	}

	//所属行业函数
	function hangye($hangyeid)
	{
		 $group = '<select name="hangyeid">
            <option value="">请选择</option>';
            $grouplist = M("yunweb_hangye")->select();
            if(!empty($grouplist))
            {
                foreach($grouplist as $key=>$val)
                {
                    $seled = "";
                    if($hangyeid==$val['id']) $seled = " selected";
                    $group .= "<option value=\"".$val['id']."\" $seled>".$val['name']."</option>";
                }
            }
        $group .= "</select>";
        return $group;
	}

	//地区
	function area($areaid)
	{
		 $group = '<select name="areaid">
            <option value="">请选择</option>';
            $grouplist = M("yunweb_area")->select();
            if(!empty($grouplist))
            {
                foreach($grouplist as $key=>$val)
                {
                    $seled = "";
                    if($areaid==$val['id']) $seled = " selected";
                    $group .= "<option value=\"".$val['id']."\" $seled>".$val['name']."</option>";
                }
            }
        $group .= "</select>";
        return $group;
	}


   

}

?>
