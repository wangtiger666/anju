<?php

class YunArticleAction extends AdminAction{

    function show(){
        $this->display();
    }

    function index(){
        cookie("back", __SELF__);
        import('ORG.Util.Page'); // 导入分页类

        $mm = M("yun720_article");
        $catid = I("post.catid");//推荐位
        $cid = I("post.cid");//推荐位

        $where=" 1 ";

        if($catid){
        	$where .=" AND catid=".$catid ;
        }
        if($cid){
        	$where .=" AND lanmuid=".$cid;
        }
        $count = $mm->where($where)->count(); // 查询满足要求的总记录数
      	$Page = new Page($count, 15); // 实例化分页类 传入总记录数和每页显示的记录数
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
       	$list = $mm->where($where)->order("id")->order("id desc")->limit($Page->firstRow . ',' . $Page->listRows)->select();
        $show = $Page->show(); // 分页显示输出

		$group = '<select name="catid">
			<option value="">请选择</option>';
			$grouplist = M("yun720_artcat")->order("listorder")->select();
			if(!empty($grouplist))
			{
				foreach($grouplist as $key=>$val)
				{
					$seled = "";
					if($catid==$val['id']) $seled = " selected";
					$group .= "<option value=\"".$val['id']."\" $seled>".$val['name']."</option>";
				}
			}
		$group .= "</select>";
		$selecttree = $this->selecttree($cid);

		$this->assign('selecttree', $selecttree); // 赋值数据集
		$this->assign('group', $group); // 赋值数据集
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display(); // 输出模板
    }


    function del() {
        if ($_GET['id'] != "") {
            M("yun720_article")->where("id=" . $_GET['id'])->delete();
        }
        $this->success("删除成功！", U("index"));
    }

	function add()
	{

        $cid = I("get.cid");//文章分类
        $cateselect = $this->selecttree($cid);
		
		$minfo = M("Admin")->where(array("id" => $this->adminid))->find();
		
        if ($_POST['dopost'] == "save")
		{
            $catid = I("post.catid");
            $lanmuid = I("post.cid");
            $title = I("post.title");
            $keywords = I("post.keywords");
            $description = I("post.description");
            $listorder = I("post.listorder");
			$outlink = I("post.outlink");
			$content = I("post.content");
			$status = I("post.status");
			if($lanmuid  == "") {
                $this->error("文章栏目不能为空！");
                exit();
            }
            if ($title == "") {
                $this->error("文章标题不能为空！");
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
				"lanmuid" => $lanmuid,
	            "catid" => $catid,
				"userid" => $minfo['id'],
				"username" => $minfo['nickname'],
	            "title" => $title,
	            "keywords" => $keywords,
	            "description" => $description,
	            "outlink" => $outlink,
	            "content" => $content,
	            "status" => $status,
	            "listorder"=>$listorder,
	            "createtime" => time(),
	            "updatetime" => time(),
	            "thumb" => $file
	        );

           $address =  M("yun720_article")->add($addmap);
		   //echo M('yun720_article')->_sql();
		   //exit();

            if($address){
            	$this->success("添加成功！", U("index"));
            }else{
            	$this->success("添加失败！", U("add"));
            }
            exit();
		}

        $group = '<select name="catid">
            <option value="">请选择</option>';
            $grouplist = M("yun720_artcat")->select();
            if(!empty($grouplist))
            {
                foreach($grouplist as $key=>$val)
                {
                    $seled = "";
                    if($catid==$val['id']) $seled = " selected";
                    $group .= "<option value=\"".$val['id']."\" $seled>".$val['name']."</option>";
                }
            }
            $group .= "</select>";

        $this->assign('group', $group); // 赋值数据集
        $this->assign('cateselect', $cateselect);
		$this->assign("grouplist", $grouplist);
		$this->assign("group_id", $group_id);
		$this->display();	
	}
	
	function edit()
	{
		$id = intval($_REQUEST['id']);
		//根据文章ID 选择出文章lanmuid

		if ($_POST['dopost'] == "save")
		{
            $catid = I("post.catid");
            $lanmuid = I("post.cid");
            $title = I("post.title");
            $keywords = I("post.keywords");
            $description = I("post.description");
            $listorder = I("post.listorder");
			$outlink = I("post.outlink");
			$content = I("post.content");
			$status = I("post.status");

			if($lanmuid  == "") {
                $this->error("文章分类不能为空！");
                exit();
            }

            if ($title == "") {
                $this->error("文章标题不能为空！");
                exit();
            }

            //图片处理
			$thumb ="";
            $fileurl = "/uploads/artthumb";
			if(!empty($_POST['image'])){
				$image = ExecUpload($_POST['image'], $_POST['old_image'], $fileurl);
				 M("yun720_article")->where(array("id"=>$id))->save(array("thumb"=>$image));
			}

			$addmap = array(
	            "catid" => $catid,
	            "lanmuid" => $lanmuid,
	            "title" => $title,
	            "keywords" => $keywords,
	            "description" => $description,
	            "listorder"=>$listorder,
	            "outlink" => $outlink,
	            "content" => $content,
	            "status" => $status,
	            "updatetime" => time(),
	        );

			$result = M("yun720_article")->where(array("id"=>$id))->data($addmap)->save();

            if($result){
            	$this->success("修改成功！");
            }else{
            	$this->success("修改失败！");
            }
            exit();
		}

		$info = M("yun720_article")->where(array("id"=>$id))->find();

		$cateselect = $this->selecttree($info['lanmuid']); //栏目

		$catid = $info['catid'];//推荐位
		
		$group = '<select name="catid">
            <option value="">请选择</option>';
            $grouplist = M("yun720_artcat")->select();
            if(!empty($grouplist))
            {
                foreach($grouplist as $key=>$val)
                {
                    $seled = "";
                    if($catid==$val['id']) $seled = " selected";
                    $group .= "<option value=\"".$val['id']."\" $seled>".$val['name']."</option>";
                }
            }
            $group .= "</select>";
        $this->assign('cateselect', $cateselect); //赋值数据集
        $this->assign('group', $group); //赋值数据集
		$this->assign("info", $info);
		$this->assign("id", $id);
		$this->display();
	}
	
	function artopened()
	{
		$id = intval(I("get.id"));
		if(!empty($id))
		{
			M("yun720_article")->where(array("id"=>$id))->data(array("status"=>1))->save();
			$this->success("启用成功！", U("index"));
		}
	}
	
	function artdisabled()
	{
		$id = intval(I("get.id"));
		if(!empty($id))
		{
			M("yun720_article")->where(array("id"=>$id))->data(array("status"=>0))->save();
			$this->success("禁用成功！", U("index"));
		}
	}

    function set_recommend()
    {
        $id = intval($_REQUEST['id']);
        $is_recommend_s = M("yun720_article")->where("id = $id")->getfield("is_recommend");
        $is_recommend = ($is_recommend_s=='0') ? "1" : '0';
        $res = M("yun720_article")->where(array("id"=>$id))->data(array("is_recommend"=>$is_recommend))->save();
        echo json_encode($is_recommend);die;
    }


	 function selecttree($id){  
        $data = M("yun720_lanmu")->where($where)->order("listorder")->select(); 
        $option = $this->get_select_Tree($data,0,0,$id);
        $res = "<select name='cid'><option value='0'>请选择...</option> ".$option."</select>";
        return $res; 
    }

    //递归栏目列表选择函数   data 数据  pid 父id  n 递归深度 cid 选中的栏目ID
    function get_select_Tree($data, $pId,$n,$cid)
    {
        $html = '';
        $fenjie=array();
        foreach($data as $k => $v)
        {
           if($v['pid'] == $pId)
           { 
            $frist = ($v['pid']!=0) ? "|": "";
            if($n){
                for ($i=0; $i < $n; $i++) { 
                    $v['char']=$v['char']."-";
                    $v['nbs']=$v['nbs']."&nbsp&nbsp&nbsp&nbsp";
                }
            }

            $selected = $v['id']==$cid ? "selected": " " ;
            $html .= '<option '.$selected.' value ="'.$v['id'].'">'.$v['nbs'].$frist.$v['char'].$v['name'];              
            $html .= self::get_select_Tree($data, $v['id'],($n+1),$cid);
            $html = $html.'</option>';
           }
        }
        return $html;
    }


}

?>
