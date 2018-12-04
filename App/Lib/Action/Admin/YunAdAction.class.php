<?php

class YunAdAction extends AdminAction{
   
	function imgindex(){
		cookie("back", __SELF__);
        import('ORG.Util.Page'); // 导入分页类

        $mm = M("yun720_ad");
		$where = "1";
		
		$count = $mm->where($where)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 20); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出

		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $mm->where($where)->order("id")->limit($Page->firstRow . ',' . $Page->listRows)->select();
        
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display(); // 输出模板
	}

    function index(){
        cookie("back", __SELF__);
        import('ORG.Util.Page'); // 导入分页类

        $adid = I("get.id");
        $mm = M("yun720_adlist");
		$where = array(
                'ad_id'=>$adid
        );
        $count = $mm->where($where)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 20); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出

		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $mm->where($where)->order("listorder")->limit($Page->firstRow . ',' . $Page->listRows)->select();
        
        foreach ($list as $k=>$li) {
            $panocount = M("yun720_adlist")->where(array("member_id"=>$li['id']))->count();
            $list[$k]['len'] = $panocount;
			$groupname = M("admin_group")->where(array("id"=>$li['group_id']))->getField('groupname');
			$list[$k]['groupname'] = $groupname;
        }

        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display(); // 输出模板
    }
	
	function add()
	{
		if ($_POST['dopost'] == "save")
		{
			$ad_name = trim(I("post.ad_name"));
            $outlink = I("post.outlink");
            $adid = I("post.adid");
            $is_show = I("post.is_show");
            $listorder = I("post.listorder");

            if ($ad_name == "") {
                $this->error("请填写广告名称");
                exit();
            } 

            if ($adid == "") {
                $this->error("请选择广告位置");
                exit();
            }

           	$fileurl = "/uploads/img";
			createFolder(APP_ROOT.$filedir);
			if(!empty($_POST['file']))
			{
				$arr = getimagesize(APP_ROOT.$_POST['file']);
                $width = $arr[0];
                $height = $arr[1];
                $imgtype = $arr[2];
				$file = ExecUpload($_POST['file'], '', $fileurl);
			}

            $addmap = array(
                "ad_name" => $ad_name,
                "thumb" =>$file,
                "outlink" => $outlink,
                "ad_id" => $adid,
                "is_show" => $is_show,
                "listorder" =>$listorder
            );
            M("yun720_adlist")->add($addmap);
			$this->success("添加成功！");
			exit;
		}

		$grouplist = M("yun720_ad")->select();
	
		$this->assign("grouplist", $grouplist);
		$this->assign("group_id", $group_id);
		$this->display();	
	}
	
   function del() {
        $backurl = cookie("back");
        $this->assign("backurl", $backurl);
        if ($_GET['id'] != "") {
            M("yun720_adlist")->where("id=" . $_GET['id'])->delete();
        }
        $this->success("删除成功！");
    }

public function upload(){
        if (!empty($_FILES)) {

        	$img_width = getimagesize($_FILES);
        	$img_height = getimagesize($_FILES);

            //引入UploadFile类
            import('ORG.Net.UploadFile');
            //实例化UploadFile类
            $upload  = new UploadFile();
            $upload->maxSize = 2048000;
            $upload->allowExts = array('jpg','jpeg','gif','png');
            $upload->savePath = "./uploads/img/";

            if(!$upload->upload()){
                $this->error($upload->getErrorMsg());//获取失败信息
            } else {
                $info = $upload->getUploadFileInfo();//获取成功信息
            }
            echo $info[0]['savepath'].$info[0]['savename'];    //返回文件名给JS作回调用
        }
    }

	function edit()
	{
		$id = intval($_REQUEST['id']);
		if ($_POST['dopost'] == "save")
		{	
			//根据ID选择出作者ID
			$ad_name = trim(I("post.ad_name"));
            $outlink = I("post.outlink");
            $adid = I("post.adid");
            $is_show = I("post.is_show");
            $listorder =I("post.listorder");

            $thumb ="";
            $fileurl = "/uploads/img";
			if(!empty($_POST['image'])){
				$image = ExecUpload($_POST['image'], $_POST['old_image'], $fileurl);
				 M("yun720_adlist")->where(array("id"=>$id))->save(array("thumb"=>$image));
			}

            $save_date =array(
                        'ad_id'=>$adid,
                        'ad_name'=>$ad_name,
                        'outlink'=>$outlink,
                        'positionid'=>$positionid,
                        'is_show'=>$is_show,
                        'listorder'=>$listorder
                );
      
            M("yun720_adlist")->where(array("id"=>$id))->data($save_date)->save();
            $this->success("修改成功！");
			exit;
        
		}

		$info = M("yun720_adlist")->where(array("id"=>$id))->find();

		$group = '<select name="adid">
			<option value="">请选择</option>';
			$grouplist = M("yun720_ad")->select();
			if(!empty($grouplist))
			{
				foreach($grouplist as $key=>$val)
				{
					$seled = "";
					if($info['ad_id']==$val['id']) $seled = " selected";
					$group .= "<option value=\"".$val['id']."\" $seled>".$val['name']."</option>";
				}
			}
			$group .= "</select>";
									
		$this->assign("group", $group);
		$this->assign("info", $info);
		$this->assign("id", $id);
		$this->display();
	}
	
	function adopened()
	{
		$id = intval(I("get.id"));
		if(!empty($id))
		{
			$adid = M("yun720_adlist")->where(array("id"=>$id))->getField('ad_id');
            M("yun720_adlist")->where(array("id"=>$id))->data(array("is_show"=>1))->save();
			$this->success("显示成功！");
		}
	}
	
	function addisabled()
	{
		$id = intval(I("get.id"));
		if(!empty($id))
		{
			M("yun720_adlist")->where(array("id"=>$id))->data(array("is_show"=>0))->save();
			$this->success("禁用成功！");
		}
	}

}

?>
