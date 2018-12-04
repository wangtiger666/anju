<?php

class PanoAction extends AdminAction{

    function show(){
        $this->display();
    }

    function index(){
        cookie("back", __SELF__);
        import('ORG.Util.Page'); // 导入分页类

        $act = $_REQUEST['act'];
        
        $areaid = I("post.areaid");//地区
        $hangyeid = I("post.hangyeid");//行业

        $where=" 1 ";
        if($areaid){
            $where .=" AND areaid=".$areaid;
        }
        if($hangyeid){
            $where .=" AND hangyeid=".$hangyeid;
        }
        
        $count = M("pano")->where($where)->count();
        $Page = new Page($count, 20); 
        $YunAdlist = M("pano")->where($where)->order('id desc')->limit($Page->firstRow . ',' . $Page->listRows)->select();
     
        $list = $YunAdlist;
        $show = $Page->show();

        $hangye = $this->hangye($hangyeid);//行业
        $area = $this->area($areaid);//地区

        $this->assign('hangye', $hangye);
        $this->assign('area', $area);
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display(); // 输出模板
    }


    function del() {
        if ($_GET['id'] != "") {
            M("pano")->where("id=" . $_GET['id'])->delete();
        }
        $this->success("删除成功！", U("index"));
    }

	function edit()
	{
		$id = intval($_REQUEST['id']);

        $areaid = M("Pano")->where(array("id"=>$id))->getField('areaid');  
        $hangyeid = M("Pano")->where(array("id"=>$id))->getField('hangyeid'); 
        $info = M("Pano")->where(array("id"=>$id))->find();
		
        if ($_POST['dopost'] == "save")
		{	
            $areaid = I("post.areaid");
            $hangyeid = I("post.hangyeid");
            $data = array("areaid"=>$areaid,"hangyeid"=>$hangyeid);
            $res = M("Pano")->where(array("id"=>$id))->data($data)->save();

            $this->success("修改成功！", U("index"));
			exit;
		}

		$web_hangye = $this->hangye($hangyeid);//行业
		$web_area = $this->area($areaid);//地区

        $this->assign('web_hangye', $web_hangye);
        $this->assign('web_area', $web_area);	
		$this->assign("group", $group);
		$this->assign("info", $info);
		$this->assign("id", $id);
		$this->display();
	}
	
	function set_recommend()
	{
        $id = intval($_REQUEST['id']);
        $is_recommend_s = M("Pano")->where("id = $id")->getfield("is_recommend");
        $is_recommend = ($is_recommend_s=='0') ? "1" : '0';
		$res = M("Pano")->where(array("id"=>$id))->data(array("is_recommend"=>$is_recommend))->save();
        echo json_encode($is_recommend);die;
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
