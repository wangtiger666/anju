<?php

class ControlAction extends AdminAction {

    function  show(){
        $this->display(); 
    }

    function index() {
        cookie("back", __SELF__);
        $User = M('Admin'); // 实例化User对象
        $where = "group_id = 10";
		import('ORG.Util.Page'); // 导入分页类
        $count = $User->where($where)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 15); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $User->where($where)->order('id')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach ($list as $k=>$li) {
			$groupname = M("admin_group")->where(array("id"=>$li['group_id']))->getField('groupname');
			$list[$k]['groupname'] = $groupname;	
		}
		
		$this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display(); // 输出模板
    }

	function dlindex() {
        cookie("back", __SELF__);
        $User = M('Admin'); // 实例化User对象
        $where = "group_id = 11";
		import('ORG.Util.Page'); // 导入分页类
        $count = $User->where($where)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 15); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $User->where($where)->order('id')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		foreach ($list as $k=>$li) {
			$groupname = M("admin_group")->where(array("id"=>$li['group_id']))->getField('groupname');
			$list[$k]['groupname'] = $groupname;	
		}
		
		$this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        $this->display(); // 输出模板
    }


    function add() {
        $backurl = cookie("back");
        $this->assign("backurl", $backurl);

        if ($_POST['dopost'] == "save") {
            $group_id = I('post.group_id');
            $account = I("post.account");
            $password = I("post.password");
            $nickname = I("post.nickname");

            if ($account == "") {
                $this->error("帐号不能为空");
                exit();
            } else {
                $findwhere = array(
                    "account" => $account
                );
                $r = M("Admin")->where($findwhere)->find();
                if (is_array($r)) {
                    $this->error("帐号已经被注册！");
                    exit();
                }
            }

            $pwd = substr(md5($password), 5, 20);
            $data = array(
                "account" => $account,
                "password" => $pwd,
                "nickname" => $nickname,
                "create_time" => time(),
                "last_login_time" => time(),
                "group_id" => $group_id
            );

            M("Admin")->add($data);
            redirect($backurl);
            exit();
        }
        
        $grow = M("Admin_group")->order("id")->select();
        $garr = array();
        foreach ($grow as $gk => $gv) {
            $garr[$gk] = $gv['id'] . "|" . $gv['groupname'];
        }
        $this->assign('garr', $garr);

        $this->display(); // 输出模板
    }

    function edit() {
        $backurl = cookie("back");
        $this->assign("backurl", $backurl);

        if ($_POST['dopost'] == "save") {
            $id = I("post.id");
            $group_id = I('post.group_id');
            $password = I("post.password");
            $nickname = I("post.nickname");

            if ($id == 1 && $this->adminid != 1) {
                $this->error("你没有权限修改原始管理员，只有原始管理员本人可以修改！");
                exit();
            }

            if ($password != "") {
                $pwd = substr(md5($password), 5, 20);
                $data = array(
                    "password" => $pwd,
                    "nickname" => $nickname,
                    "group_id" => $group_id
                );
            } else {
                $data = array(
                    "nickname" => $nickname,
                    "group_id" => $group_id
                );
            }
            $where = array(
                "id" => $id
            );

            M("Admin")->where($where)->save($data);
            redirect($backurl);
            exit();
        }

        if ($_GET['id'] != "") {
            $row = M("Admin")->where("id=" . $_GET['id'])->find();
            $this->assign("row", $row);

            $grow = M("Admin_group")->order("id")->select();
            $garr = array();
            foreach ($grow as $gk => $gv) {
                $garr[$gk] = $gv['id'] . "|" . $gv['groupname'];
            }
            $this->assign('garr', $garr);
            $this->display();
        }
    }
	
	function adminopened()
	{
		$id = intval(I("get.id"));
		if(!empty($id))
		{
			M("Admin")->where(array("id"=>$id))->data(array("status"=>1))->save();
			$this->success("启用成功！", U("index"));
		}
	}
	function admindisabled()
	{
		$id = intval(I("get.id"));
		if(!empty($id))
		{
			M("Admin")->where(array("id"=>$id))->data(array("status"=>0))->save();
			$this->success("禁用成功！", U("index"));
		}
	}

    function del() {
        $backurl = cookie("back");
        $this->assign("backurl", $backurl);

        if ($_GET['id'] != "") {
            if ($id == 1 && $this->adminid != 1) {
                $this->error("原始管理员不可被删除！");
                exit();
            }

            M("Admin")->where("id=" . $_GET['id'])->delete();
        }
    }

}

?>
