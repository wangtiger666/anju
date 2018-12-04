<?php
class UserAction extends PublicAction {

    public function Login()
	{
		if (I("post.dopost") == "login")
		{
            if (empty($_POST['userid'])) {
                $this->error('请输入帐号！');
            } else if (empty($_POST['pwd'])) {
                $this->error('密码必须！');
            }
            $adminName = preg_replace("/[^0-9a-zA-Z_@!\.-]/", '', $_POST['userid']);
            $adminPwd = preg_replace("/[^0-9a-zA-Z_@!\.-]/", '', $_POST['pwd']);
            $pwd = substr(md5($adminPwd), 5, 20);
			$remember = $_POST['remember'];
            $map = array(
                "account" => $adminName
            );
            $row = M("Member")->where($map)->find();
            if (!is_array($row)) {
                $result = 0;
            } else if ($row['password'] != $pwd) {
                $result = -1;
            } else {
                $result = 1;
            }

            if ($result == 0) {
                $this->error('没有此帐号！');
            } else if ($result == -1) {
                $this->error('密码错误！');
            } else {
				$expire = $remember ? 3600*24*30 : 3600*24;
                cookie("member_id", $row['id'],$expire);
                $map = array(
                    "id" => $row['group_id']
                );
                cookie("member_name", $row['nickname'],$expire);
                $grow = M("Member_group")->where($map)->find();
                if (is_array($grow)) {
                    cookie("member_power", $grow['grouppower'],$expire);
                    cookie("member_power_name", $grow['groupname'],$expire);
                } else {
                    cookie("member_power", "");
                    cookie("member_power_name", "");
                }
                $editarr = array(
                    "last_login_time" => time(),
                    "last_login_ip" => get_client_ip()
                );
                M("Member")->where("id={$row['id']}")->save($editarr);
				$this->success('登录成功');
            }
            exit();
        }
    }

	public function reg()
	{
        if (I("post.dopost") == "reg")
		{
            $account = trim(I("post.reg_userid"));
            $pwd = I("post.reg_pwd");
            $nickname = I("post.reg_nickname");
            $phone = "";
            $email = "";
			$verify = trim(strtolower($_POST['reg_verify']));
			if(md5($verify)	!= $_SESSION['codeverify']) {
				$this->error('验证码错误！');
			}

            if ($account == "") {
				$this->error('用户名不能为空');
            } else {
                $map = array(
                    "account" => $account
                );
                $row = M("Member")->where($map)->find();
                if (is_array($row)) {
                    $this->error('该帐号已存在！');
                }
            }
            if ($pwd == "") {
                $this->error('密码不能为空！');
            }
            $userpwd = substr(md5($pwd), 5, 20);

            if ($nickname == "") {
                $this->error('请填写昵称！');
            }else{
				$row = M("Member")->where(array("nickname"=>$nickname))->find();
				if(!empty($row)){
					$this->error('该昵称已被使用！');
				}
			}

            $addmap = array(
                "account" => $account,
                "password" => $userpwd,
                "nickname" => $nickname,
                "phone" => $phone,
                "email" => $email,
                "create_time" => time()
            );
            M("Member")->add($addmap);

            $map = array(
                "account" => $account
            );
            $row = M("Member")->where($map)->find();

            $member_id = $row['id'];
            $member_name = $row['nickname'];

            $powermap = array(
                "id" => $row['group_id']
            );
            $powerrow = M("Usergroup")->where($powermap)->find();
            if ($powerrow) {
                $power = $powerrow['grouppower'];
                $power_name = $powerrow['groupname'];
            } else {
                $power = "";
                $power_name = "";
            }
			$expire = 3600*24*30;
            cookie("member_id", $member_id,$expire);
            cookie("member_name", $member_name,$expire);
            cookie("member_power", $power,$expire);
            cookie("member_power_name", $power_name,$expire);

            $editarr = array(
                "last_login_time" => time(),
                "last_login_ip" => get_client_ip()
            );
            $map = array(
                "id" => $row['id']
            );
            M("Member")->where($map)->save($editarr);            
            $this->success('注册成功');
        }
        $this->display();
    }
	
	public function mytask()
	{
		if (I("post.dopost") == "mytask")
		{
			$member_id = cookie('member_id');
			if(!empty($member_id))
			{
				$row = M("Member")->where("id='$member_id'")->find();
				import("ORG.Util.String");
				$key = String::randString(12);
				$data = array(
					"user_account" => $row['Account.class'],
					"user_pwd" => $row['password'],
					"key" => $key
				);
				M("Admin_login")->add($data);
				$this->success($key);
			}
			else
			{
				$this->error('请先登录');
			}
		}
	}

	public function logout() {
        cookie("member_id", null);
        cookie("member_name", null);
        cookie("member_power", null);
        cookie("member_power_name", null);
        header("Location: /");
    }
}