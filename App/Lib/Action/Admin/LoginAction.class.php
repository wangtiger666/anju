<?php

class LoginAction extends Action {

    public function index() {
    	    	  	//站点名称  by SDL 20161226
    	$web_name = M("sysconfig")->where("varname = 'web_name' ")->getField('value');
		$this->assign("web_name", $web_name);
        if (I("post.dopost") == "login") {
            if (empty($_POST['userid'])) {
                $this->error('请输入帐号！');
            } else if (empty($_POST['pwd'])) {
                $this->error('密码必须！');
            }
            $adminName = preg_replace("/[^0-9a-zA-Z_@!\.-]/", '', $_POST['userid']);
            $adminPwd = preg_replace("/[^0-9a-zA-Z_@!\.-]/", '', $_POST['pwd']);
            $pwd = substr(md5($adminPwd), 5, 20);
            $map = array(
                "account" => $adminName
            );
            $row = M("Admin")->where($map)->find();
			if($row['status'] == 0)
			{
				$this->error('此帐号已被限制登录！');
				exit;
			}

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
                session(C("SESSION_ADMINID"), $row['id']);
                $map = array(
                    "id" => $row['group_id']
                );
                session(C("SESSION_NAME"), $row['nickname']);
                $grow = M("Admin_group")->where($map)->find();
                if(is_array($grow)){
                    session(C("SESSION_POWER"), $grow['grouppower']);
                }else{
                    session(C("SESSION_POWER"), "");
                }
                
                $editarr = array(
                    "last_login_time" => time(),
                    "last_login_ip" => get_client_ip()
                );
                M("Admin")->where("id={$row['id']}")->save($editarr);
               	$addmap = array(
	                "user_account" =>$adminName,
	                "user_id" =>$row['id'],
	                "login_time" =>time(),
	                "login_ip" =>get_client_ip()
	            );
               	M("admin_log")->add($addmap);

                $this->redirect('admin/index/index');
            }
            exit();
        }
        $this->display();
    }
    
    public function logout() {
        $tag_userid = C("SESSION_ADMINID");
        if (isset($_SESSION[$tag_userid])) {
            unset($_SESSION[$tag_userid]);
            unset($_SESSION);
            session_destroy();
        }
        $this->success('退出成功', U("admin/index/index"));
    }

}

?>
