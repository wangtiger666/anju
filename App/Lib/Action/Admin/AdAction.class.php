<?php

class AdAction extends AdminAction{

	function imgindex(){
		cookie("back", __SELF__);
        import('ORG.Util.Page'); // 导入分页类
        $is_mobile=$_REQUEST['ismobile'];
        $mm = M("yun_ad");
		$where = "is_mobile = $is_mobile";
		$count = $mm->where($where)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 20); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $mm->where($where)->order("listorder asc")->limit($Page->firstRow . ',' . $Page->listRows)->select();
        
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display(); // 输出模板
	}

    function index(){
        cookie("back", __SELF__);
        import('ORG.Util.Page'); // 导入分页类
        $adid = I("get.id");
        $mm = M("yun_adlist");
		$where = array(
                'positionid'=>$adid
        );

        $count = $mm->where($where)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 20); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $mm->where($where)->order("id")->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach ($list as $k=>$li) {
            $panocount = M("yun_adlist")->where(array("member_id"=>$li['id']))->count();
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
            $positionid = I("post.positionid");
            $is_show = I("post.is_show");

            if ($ad_name == "") {
                $this->error("请填写广告名称");
                exit();
            } 

            if ($positionid == "") {
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
                "positionid" => $positionid,
                "is_show" => $is_show,
            );
            M("yun_adlist")->add($addmap);

			$this->success("添加成功！", '/admin/ad/imgindex/ismobile/0');
			exit;
		}

		$grouplist = M("yun_ad")->select();
	
		$this->assign("grouplist", $grouplist);
		$this->assign("group_id", $group_id);
		$this->display();	
	}
	
   function del() {
        $backurl = cookie("back");
        $this->assign("backurl", $backurl);
        if ($_GET['id'] != "") {
            M("yun_adlist")->where("id=" . $_GET['id'])->delete();
        }
        $this->success("删除成功！", '/admin/ad/imgindex/ismobile/0');
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
            $positionid = I("post.positionid");
            $is_show = I("post.is_show");

            $thumb ="";
            $fileurl = "/uploads/img";
			if(!empty($_POST['image'])){
				$image = ExecUpload($_POST['image'], $_POST['old_image'], $fileurl);
				 M("yun_adlist")->where(array("id"=>$id))->save(array("thumb"=>$image));
			}

            $save_date =array(
                        'ad_name'=>$ad_name,
                        'outlink'=>$outlink,
                        'positionid'=>$positionid,
                        'is_show'=>$is_show
                );
      
            M("yun_adlist")->where(array("id"=>$id))->data($save_date)->save();
            $this->success("修改成功！", '/admin/ad/imgindex/ismobile/0');
			exit;
        
		}

		$info = M("yun_adlist")->where(array("id"=>$id))->find();

		$group = '<select name="positionid">
			<option value="">请选择</option>';
			$grouplist = M("yun_ad")->select();
			if(!empty($grouplist))
			{
				foreach($grouplist as $key=>$val)
				{
					$seled = "";
					if($info['positionid']==$val['id']) $seled = " selected";
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
			M("yun_adlist")->where(array("id"=>$id))->data(array("is_show"=>1))->save();
			$this->success("显示成功！", '/admin/ad/imgindex/ismobile/0');
		}
	}
	
	function addisabled()
	{
		$id = intval(I("get.id"));
		if(!empty($id))
		{
			M("yun_adlist")->where(array("id"=>$id))->data(array("is_show"=>0))->save();
			$this->success("禁用成功！", '/admin/ad/imgindex/ismobile/0');
		}
	}


    function control(){
        if ($_GET['id'] != "") {
            $row = M("Member")->where("id=" . $_GET['id'])->find();

            import("ORG.Util.String");
            $key = String::randString(12);
            $data = array(
                "user_account" => $row['Account.class'],
                "user_pwd" => $row['password'],
                "key" => $key
            );
            M("Admin_login")->add($data);
            $this->redirect("Member/login/auto",array("from"=>$key));
        }
    }
}

?>
