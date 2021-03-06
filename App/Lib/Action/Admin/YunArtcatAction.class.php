<?php

class YunArtcatAction extends AdminAction{

    function index(){
        cookie("back", __SELF__);
        import('ORG.Util.Page'); // 导入分页类

        $mm = M("yun720_artcat");
		$where = "1";

        $count = $mm->where($where)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 20); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出

		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $mm->where($where)->order("listorder")->limit($Page->firstRow . ',' . $Page->listRows)->select();
   
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display(); // 输出模板
    }


    function del() {
   
        if ($_GET['id'] != "") {
            M("yun720_artcat")->where("id=" . $_GET['id'])->delete();
        }
        $this->success("删除成功！", U("index"));
    }

	function add()
	{
		if ($_POST['dopost'] == "save")
		{
			$name = trim(I("post.name"));
            $listorder = I("post.listorder");

            $addmap = array(
                "name" => $name,
                "listorder" => $listorder
          
            );
            $res = M("yun720_artcat")->add($addmap);
			

			redirect("index");
            exit();
		}
		$this->display();	
	}
	
	function edit()
	{
		$id = intval($_REQUEST['id']);



		if ($_POST['dopost'] == "save")
		{	
            $name = I("post.name");
            $listorder = I("post.listorder");

            $posidarr=array( 
                    "name" => $name,
                    'listorder' =>$listorder
                    );
            
            $posi_res = M("yun720_artcat")->where(array("id"=>$id))->data($posidarr)->save();
 
            $this->success("修改成功！", U("index"));
			exit;
        
		}
	
   		$info = M("yun720_artcat")->where(array("id"=>$id))->find();
					
		$this->assign("group", $group);
		$this->assign("info", $info);
		$this->assign("id", $id);
		$this->display();
	}
	
	function useropened()
	{
		$id = intval(I("get.id"));
		if(!empty($id))
		{
			M("Member")->where(array("id"=>$id))->data(array("status"=>1))->save();
			$this->success("启用成功！", U("index"));
		}
	}
	
	function userdisabled()
	{
		$id = intval(I("get.id"));
		if(!empty($id))
		{
			M("Member")->where(array("id"=>$id))->data(array("status"=>3))->save();
			$this->success("禁用成功！", U("index"));
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
