<?php

class LoginAction extends Action {

    public function index() {
        
        $coomeid = cookie(C("SESSION_MEMBERID"));
        $coomeme = cookie(C("SESSION_NAME"));
       
        if($coomeid){
              session(C("SESSION_MEMBERID"), $coomeid);  //设置cookie
        }
        if($coomeid){
              session(C("SESSION_NAME"), $coomeme);  //设置cookie
        }

        if (I("post.dopost") == "login") {

            $sign_remember =$_REQUEST['sign_remember'];
            
            if (empty($_POST['userid'])) {
                $this->error('请输入帐号！');
            } else if (empty($_POST['pwd'])) {
                $this->error('密码必须！');
            }
            $adminName = preg_replace("/[^0-9a-zA-Z_@!.-]/", '', $_POST['userid']);
            $adminPwd = preg_replace("/[^0-9a-zA-Z_@!.-]/", '', $_POST['pwd']);
            $pwd = substr(md5($adminPwd), 5, 20);
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
                session(C("SESSION_MEMBERID"), $row['id']);
               /* if($sign_remember){ //写cookie 记住我
                    cookie(C("SESSION_MEMBERID"), $row['id'],86400);  //设置cookie
                }*/
                cookie(C("SESSION_MEMBERID"), $row['id'],86400);  //设置cookie

                $map = array(
                    "id" => $row['group_id']
                );
                session(C("SESSION_NAME"), $row['nickname']);
               /* if($sign_remember){ 
                   cookie(C("SESSION_NAME"), $row['nickname'],86400);  //设置cookie
                }*/
                cookie(C("SESSION_NAME"), $row['nickname'],86400);  //设置cookie
              

                $grow = M("Member_group")->where($map)->find();
                if (is_array($grow)) {
                    session(C("SESSION_POWER"), $grow['grouppower']);
                    session(C("SESSION_POWER_NAME"), $grow['groupname']);
                } else {
                    session(C("SESSION_POWER"), "");
                    session(C("SESSION_POWER_NAME"), "");
                }

                $editarr = array(
                    "last_login_time" => time(),
                    "last_login_ip" => get_client_ip()
                );
                M("Member")->where("id={$row['id']}")->save($editarr);

				header("Location: /member/index");
            }
            exit();
        }

        $HTTP_HOST = $_SERVER['HTTP_HOST']?$_SERVER['HTTP_HOST']:$HTTP_SERVER_VARS['HTTP_HOST'];

        $domainlink = 'http://' . $HTTP_HOST;

        $member_id_s = session(C("SESSION_MEMBERID"));
        if($member_id_s){
		            //header("Location:".$domainlink);die;
            header("Location: /member/index");die;
        }

        $appid =  M("wxconfig")->where("id=2")->getField('appid');
        $weixinlogin_url = "https://open.weixin.qq.com/connect/qrconnect?appid=".$appid."&redirect_uri=http://".$HTTP_HOST."/member/login/weixinlogin&response_type=code&scope=snsapi_login&state=STATE#wechat_redirect";
        $this->assign("weixinlogin_url",$weixinlogin_url);
        $this->assign("domainlink",$domainlink);
		$this->assign("appid",$appid);
        $this->display();
    }

    public function auto() {
        if ($_GET['from'] != "") {
            $getwhere = array(
                "key" => $_GET['from']
            );
            $getrow = M("Admin_login")->where($getwhere)->find();
            if (is_array($getrow)) {
                $where = array(
                    "account" => $getrow['user_account'],
                    "password" => $getrow['user_pwd'],
                );
                $row = M("Member")->where($where)->find();
                M("Admin_login")->where($getwhere)->delete();
                if (is_array($row)) {
                    session(C("SESSION_MEMBERID"), $row['id']);
                    $map = array(
                        "id" => $row['group_id']
                    );
                    session(C("SESSION_NAME"), $row['nickname']);
                    $grow = M("Member_group")->where($map)->find();
                    if (is_array($grow)) {
                        session(C("SESSION_POWER"), $grow['grouppower']);
                        session(C("SESSION_POWER_NAME"), $grow['groupname']);
                    } else {
                        session(C("SESSION_POWER"), "");
                        session(C("SESSION_POWER_NAME"), "");
                    }

                    $editarr = array(
                        "last_login_time" => time(),
                        "last_login_ip" => get_client_ip()
                    );
                    M("Member")->where("id={$row['id']}")->save($editarr);
					header("Location: /member/index");
                }
            }
        }
    }


    public function logout() {
        $tag_userid = C("SESSION_MEMBERID");
        if (isset($_SESSION[$tag_userid])) {
            unset($_SESSION[$tag_userid]);
            unset($_SESSION);
            session_destroy();
        }
		cookie("member_id", null);
        cookie("member_name", null);
        cookie("member_power", null);
        cookie("member_power_name", null);
        cookie(C("SESSION_MEMBERID"),null);
        cookie(C("SESSION_NAME"),null);
        header("location: /");
    }
	/**
     * **************************************************
     * 微信提交API方法，返回微信指定JSON
     * **************************************************
     */
public function wxHttpsRequest($url, $data = null)
    {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
        if (! empty($data)) {
            curl_setopt($curl, CURLOPT_POST, 1);
            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        }
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        $output = curl_exec($curl);
        curl_close($curl);
        return $output;
    }
   //扫码微信返回处理函数
	public function weixinlogin() 
	{
		$code = $_REQUEST['code'];
		$appid = M("wxconfig")->where("id=2")->getField('appid');
		$secret = M("wxconfig")->where("id=2")->getField('appsecret');
		if(!empty($code))
		{
			//获取 access_token
			$url ="https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$secret."&code=".$code."&grant_type=authorization_code";
			$result = $this->wxHttpsRequest($url);
			$res =  json_decode($result);
			//refresh_token拥有较长的有效期（30天），当refresh_token失效的后，需要用户重新授权
			if(!empty($res))
			{
				$access_token = $res->access_token;
				$refresh_token = $res->refresh_token;
				$ref_rul = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=".$appid."&grant_type=refresh_token&refresh_token=".$refresh_token;
				$result = $this->wxHttpsRequest($ref_rul);
				$ref_res =  json_decode($result);

				//获取个人信息接口 /sns/userinfo  
				$openid  =$ref_res->openid;
				$access_token  =$ref_res->access_token; 
				$refresh_token  =$ref_res->refresh_token;
				$scope =  $ref_res->scope;
				//获取用户信息  名字等  参考 微信网页开发-微信网页授权-第四步：拉取用户信息(需scope为 snsapi_userinfo)
				$use_url ="https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
				$result = $this->wxHttpsRequest($use_url);
				$userinfo = json_decode($result);
				/*
				stdClass Object ( [openid] =>  [nickname] =>  [sex] => 1 [language] => zh_CN [city] =>  [province] =>  [country] =>  [headimgurl] => http://wx.qlogo.cn/mmopen/yfEQ [privilege] => Array ( ) [unionid] =>  ) 
				*/
				//用户信息登录 判断有没有账户信息  unionid  没有则入库 weixinlogin
				//SDL 
				$openid = $userinfo->openid;
				$unionid = $userinfo->unionid;
				$city = $userinfo->city;
				$headimgurl = $userinfo->headimgurl;
				$nickname =  $userinfo->nickname;

				//查表 入库 写SESSION
				$map = array(
					"weixin_unionid" => $unionid
				);
				$weixin_user = M("yun_weixinlogin")->where($map)->find();
				$member_id = $weixin_user['member_id'];
				if(!$member_id)
				{
					//模拟注册
					$addmap = array(
						"account" => $nickname,
						"password" => "",
						"nickname" => $nickname,
						"city" => $city,
						"headimg" => $headimgurl,
						"create_time" => time()
					);
					$member_id = M("Member")->add($addmap);
					if($member_id)
					{
						$weixindata = array(
							"weixin_unionid" => $unionid,
							"weixin_openid" => $openid,
							"member_id" =>$member_id
						);
						M("yun_weixinlogin")->add($weixindata);	
					}
				}
				//根据 member_id查询用户信息  写cookie
				$m_map = array(
					"id" => $member_id
				);
				$member_info = M("Member")->where($m_map)->find();
				$nickname = $member_info['nickname'];

				$expire = 3600*24*30;
				session(C("SESSION_MEMBERID"), $member_id);
				cookie(C("SESSION_MEMBERID"), $member_id,"expire=$expire&domain=.360720.com");

				session(C("SESSION_NAME"), $nickname);
				cookie(C("SESSION_NAME"), $nickname,"expire=$expire&domain=.360720.com");

				$editarr = array(
					"last_login_time" => time(),
					"last_login_ip" => get_client_ip()
				);
				$map_id = array(
					"id" => $member_id
				);
				M("Member")->where($map_id)->save($editarr);
				$HTTP_HOST = $_SERVER['HTTP_HOST']?$_SERVER['HTTP_HOST']:$HTTP_SERVER_VARS['HTTP_HOST'];
				$domainlink = 'http://'.$HTTP_HOST.'/member/index';
				header("Location:".$domainlink);
				exit();
			}
        }
		else
		{
			redirect(U('member/login' ,'' ,'') ,2 ,'error...' );exit();
		}
     }

    /*//第三方登录 QQ
    public function _initialize(){
        //引入QQ登陆类
        import('ORG.Connect.qqConnectAPI');
        //实例化
        $this->QC = new QC();
    }
	*/
    //开始登陆
    public function qq_login(){
        $this->QC->qq_login();

    }
    //回调
    public function qq_callback(){
        $token  = $this->QC->qq_callback();
        $openid = $this->QC->get_openid();
        $QC = new QC($token,$openid);
        $arr = $QC->get_user_info();
        
        echo "token";
        // var_dump($token);
        echo "openid";
        // var_dump($openid);
        dump($arr);

/*      $db=M("Member");
        $where['qq_openid']=$openid;
        //判断此QQ是否注册 唯一标识符openid 在表里加了个qq_openid为唯一哈
        $isqq=$db->where($where)->find();
        if($isqq){
            //写入登陆状态
            session('id', $isqq['id']);
            session('account', $isqq['account']);
            session('nickname', $isqq['account']);
            session('email', $isqq['email']);
            session('lastLoginTime', $isqq['lastLoginTime']);
            session('login_count', $isqq['login_count']);
            $this->success('登陆成功！',U('Member/index'));
        }else{
            $data['qq_openid']=$openid; //QQ登陆唯openid
            $data['account']=$arr['nickname']; //用户名
            $data['nickname']=$arr['nickname']; //网名
            $data['thumb']=$arr['figureurl_2']; //头像
            $data['status']='1'; //用户状态为启用
            //如果用户名存在
            $name['account']=$arr['nickname'];
            $isname=$db->where($name)->find();
            if($isname){
                //用户名存在添加随机数
                $data['account']=$arr['nickname']."_".rand(1000,9999);
            }
            //判断是否写入成功
            if($id=$db->add($data)){
                //写入登陆状态
                session('id', $id);
                session('account', $data['account']);
                session('nickname', $data['account']);
                session('email', '未填写');
                session('lastLoginTime', time());
                session('login_count', '1');
                $this->success('注册成功！',U('Member/index'));
            }else{
                $this->error('注册失败！',U('Member/login'));
            }
        } */
    }








}
?>