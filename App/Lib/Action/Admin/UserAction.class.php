<?php

class UserAction extends AdminAction{
    function index(){
        cookie("back", __SELF__);
        import('ORG.Util.Page'); // 导入分页类

        $mm = M("Member");
		$where = "1";
		if($this->adminid<>1){
			$group_id = M("admin")->where(array("id"=>$this->adminid))->getField('group_id');
			$where = array("group_id"=>$group_id);
		}
        $count = $mm->where($where)->count(); // 查询满足要求的总记录数
        $Page = new Page($count, 15); // 实例化分页类 传入总记录数和每页显示的记录数
        $show = $Page->show(); // 分页显示输出
		// 进行分页数据查询 注意limit方法的参数要使用Page类的属性
        $list = $mm->where($where)->order("id")->limit($Page->firstRow . ',' . $Page->listRows)->select();
        foreach ($list as $k=>$li) {
            $panocount = M("Pano")->where(array("member_id"=>$li['id']))->count();
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
			$account = trim(I("post.userid"));
            $pwd = I("post.pwd");
            $nickname = I("post.user_name");
            $phone = I("post.phone");
            $email = I("post.email");
			$group_id = I("post.group_id");
			$pano_limit = I("post.pano_limit");
			$cooperate_no = I("post.cooperate_no");

            if ($account == "") {
                $this->error("用户名不能为空！");
                exit();
            } else {
                $map = array(
                    "account" => $account
                );
                $row = M("Member")->where($map)->find();
                if (is_array($row)) {
                    $this->error("帐号已经存在！");
                    exit();
                }
            }
            if ($pwd == "") {
                $this->error("密码不能为空！");
                exit();
            }
            if ($nickname == "") {
                $this->error("昵称不能为空！");
                exit();
            }
			$userpwd = substr(md5($pwd), 5, 20);
            $addmap = array(
                "account" => $account,
                "password" => $userpwd,
                "nickname" => $nickname,
                "phone" => $phone,
                "email" => $email,
                "group_id" => $group_id,
				"pano_limit" => intval($pano_limit),
				"cooperate_no" => $cooperate_no,
                "create_time" => time()
            );
            M("Member")->add($addmap);
			redirect("index");
            exit();
		}
		if($this->adminid==1){
			$grouplist = M("admin_group")->select();
		}else{
			$group_id = M("Admin")->where(array("id"=>$this->adminid))->getField("group_id");
		}

		//查询合作编号数组
		$cooperateNoList = M('commonality')->where(['pid' => self::$COOPERATE_NO])->select();
        $this->assign("cooperateNoList", $cooperateNoList);

		$this->assign("grouplist", $grouplist);
		$this->assign("group_id", $group_id);
		$this->display();	
	}
	
	function edit()
	{
		$id = intval($_REQUEST['id']);
		if ($_POST['dopost'] == "save")
		{
			$account = trim(I("post.userid"));
            $pwd = I("post.pwd");
            $nickname = I("post.user_name");
            $phone = I("post.phone");
            $email = I("post.email");
            $group_id = I("post.group_id");
            $pano_limit = I("post.pano_limit");
            $cooperate_no = I("post.cooperate_no");
			
            $addmap = array(
                "account" => $account,
                "nickname" => $nickname,
                "phone" => $phone,
                "email" => $email,
                "group_id" => intval($group_id),
                "cooperate_no" => $cooperate_no,
                "pano_limit" => intval($pano_limit)
            );
			if(!empty($pwd))
			{
				$userpwd = substr(md5($pwd), 5, 20);
				$addmap['password'] = $userpwd;
			}
			M("Member")->where(array("id"=>$id))->data($addmap)->save();
			$this->success("修改成功！", U("index"));
			exit;
		}
		$info = M("Member")->where(array("id"=>$id))->find();
		
		if($this->adminid==1){
			$group = '<select name="group_id"><option value="">请选择</option>';
			$grouplist = M("admin_group")->select();
			if(!empty($grouplist))
			{
				foreach($grouplist as $key=>$val)
				{
					$seled = "";
					if($info['group_id']==$val['id']) $seled = " selected";
					$group .= "<option value=\"".$val['id']."\" $seled>".$val['groupname']."</option>";
				}
			}
			$group .= "</select>";
		}else{
			$group_id = M("Admin")->where(array("id"=>$this->adminid))->getField("group_id");
		}

        //查询合作编号数组
        $cooperateNoList = M('commonality')->where(['pid' => self::$COOPERATE_NO])->select();
        $this->assign("cooperateNoList", $cooperateNoList);

		$this->assign("group", $group);
		$this->assign("group_id", $group_id);

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
	
	
	function set_membertuijian()
	{
        $id = intval($_REQUEST['id']);
        $is_tuijian_s = M("Member")->where("id = $id")->getfield("tuijian");
        $tuijian = ($is_tuijian_s=='0') ? "1" : '0';
		$res = M("Member")->where(array("id"=>$id))->data(array("tuijian"=>$tuijian))->save();
        echo json_encode($tuijian);die;
	}
	
	
	function set_memberrengzheng()
	{
        $id = intval($_REQUEST['id']);
        $is_rengzheng_s = M("Member")->where("id = $id")->getfield("rengzheng");
        $rengzheng = ($is_rengzheng_s=='0') ? "1" : '0';
		$res = M("Member")->where(array("id"=>$id))->data(array("rengzheng"=>$rengzheng))->save();
        echo json_encode($rengzheng);die;
	}
	
	
	function set_membervip()
	{
        $id = intval($_REQUEST['id']);
        $is_vip_s = M("Member")->where("id = $id")->getfield("vip");
        $vip = ($is_vip_s=='0') ? "1" : '0';
		$res = M("Member")->where(array("id"=>$id))->data(array("vip"=>$vip))->save();
        echo json_encode($vip);die;
	}
}

?>
