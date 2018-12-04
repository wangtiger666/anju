<?php
header("Access-Control-Allow-Origin：*");
class PanoapiAction extends Action {

	public function _initialize(){
        $HTTP_HOST = $_SERVER['HTTP_HOST']?$_SERVER['HTTP_HOST']:$HTTP_SERVER_VARS['HTTP_HOST'];
		$this->domainlink = 'http://' . $HTTP_HOST;
    }
	
    public function view()
	{
		$pano_id = intval($_GET['pano_id']);
		if(!$pano_id) return ;
		$panorow = M("Pano")->where(array("id"=>$pano_id))->find();
		if(!empty($panorow))
		{
			$panohits = M("Hitscount")->where(array("pano_id"=>$pano_id))->find();
			if(!empty($panohits)){
				$hits = $panohits['hits']+1;
				M("Hitscount")->where(array("pano_id"=>$pano_id))->save(array("pano_id"=>$pano_id,"member_id"=>$panorow['member_id'],"hits"=>$hits));	
			}else{
				$hits = 1;
				M("Hitscount")->where(array("pano_id"=>$pano_id))->add(array("pano_id"=>$pano_id,"member_id"=>$panorow['member_id'],"hits"=>$hits));	
			}
		}
		else
		{
			M("Hitscount")->where(array("pano_id"=>$pano_id))->delete();
		}
		echo 'success_jsonpCallback({"data":'.$hits.',"info":"","status":1})';  
		exit;
    }

	public function zan()
	{
		$pano_id = intval($_REQUEST['pano_id']);
		$ip = get_client_ip();
		if(!$pano_id) return ;
		$deltime = time()-3600*24;
		M("zanip")->where("addtime<'".$deltime."'")->delete();
		$ipcount = M("zanip")->where(array("pano_id"=>$pano_id,"ip"=>$ip))->count();
		if($ipcount<=0)
		{
			M("zanip")->data(array("pano_id"=>$pano_id,"ip"=>$ip,"addtime"=>time()))->add();		
		}
		else
		{
			echo 'success_jsonpCallback({"data":"","info":"","status":0})';
			exit;
		}

		$panorow = M("Pano")->where(array("id"=>$pano_id))->find();
		if(!empty($panorow))
		{
			$panohits = M("Hitscount")->where(array("pano_id"=>$pano_id))->find();
			if(!empty($panohits)){
				$zan = $panohits['zan']+1;
				M("Hitscount")->where(array("pano_id"=>$pano_id))->save(array("pano_id"=>$pano_id,"member_id"=>$panorow['member_id'],"zan"=>$zan));	
			}else{
				$zan = 1;
				M("Hitscount")->where(array("pano_id"=>$pano_id))->add(array("pano_id"=>$pano_id,"member_id"=>$panorow['member_id'],"zan"=>$zan));	
			}
		}
		else
		{
			M("Hitscount")->where(array("pano_id"=>$pano_id))->delete();
		}
		echo 'success_jsonpCallback({"data":'.$zan.',"info":"","status":1})';  
		exit;
    }

	public function getzan()
	{
		$pano_id = intval($_GET['pano_id']);
		if(!$pano_id) return ;
		$panohits = M("Hitscount")->where(array("pano_id"=>$pano_id))->find();
		$zan = intval($panohits['zan']);
		
		$ip = get_client_ip();
		$deltime = time()-3600*24;
		M("zanip")->where("addtime<'".$deltime."'")->delete();
		$ipcount = M("zanip")->where(array("pano_id"=>$pano_id,"ip"=>$ip))->count();
		if($ipcount>0)
		{
			echo 'success_callbackzan({"data":'.$zan.',"info":"","status":2})';		
		}
		else{
			echo 'success_callbackzan({"data":'.$zan.',"info":"","status":1})';
		}
		exit;
	}
	
	public function saveComment()
	{
		$pano_id = I("get.id");
		$fvName = I("get.name");
		$ath = I("get.ath");
		$atv = I("get.atv");
		$text = I("get.text");
		$openid = I("get.openid");
		if(!empty($pano_id)&&!empty($text))
		{
			$avatar = "";
			$wxuserid = 0;
			if($openid){
				$Wxuser = M("Wxuser")->where(array("openid"=>$openid))->find();
				$avatar = $Wxuser['headimgurl'];
				$wxuserid = $Wxuser['id'];
			}
			$panorow = M("Pano")->where(array("id"=>$pano_id))->find();
			$data = array(
				"wxuserid"=>$wxuserid,
				"fvName"=>$fvName,
				"pano_id"=>$pano_id,
				"member_id"=>$panorow['member_id'],
				"ath"=>$ath,
				"atv"=>$atv,
				"avatar"=>$avatar,
				"text"=>$text,
				"time"=>time()
			);
			if(empty($avatar)) $avatar = "%SWFPATH%/hotspotComment/avatar.png";
			M("Comment")->data($data)->add();
			echo 'success_saveComment({"fvName":"'.$fvName.'","ath":'.$ath.',"atv":'.$atv.',"avatar":"'.$avatar.'","text":"'.$text.'"})';
		}
	}

	public function readComment()
	{
		$pano_id = I("get.id");
		$fvName = I("get.fn");
		if(!isset($_GET['e']))
		{
			$total_count = M("Comment")->where(array("pano_id"=>$pano_id,"fvName"=>$fvName))->count();
			echo 'success_readComment({"count":'.$total_count.'})';
		}
		else
		{	
			$ps = I("get.ps");
			$e = I("get.e");
			$list = M("Comment")->where(array("pano_id"=>$pano_id,"fvName"=>$fvName))->limit($e)->order("id desc")->select();
			$comments = array();
			if($list)
			{
				foreach($list as $key=>$val)
				{
					if(empty($val['avatar'])) $val['avatar'] = "%SWFPATH%/hotspotComment/avatar.png";
					$comments[] = array(
						"fvName"=>$val['fvName'],
						"atv"=>$val['atv'],
						"ath"=>$val['ath'],
						"text"=>$val['text'],
						"avatar"=>$val['avatar'],
					);
				}
				$dataarr = array();
				$dataarr['comments']=$comments;
				$jsonData = json_encode($dataarr);
				echo 'success_readCommentSE('.$jsonData.')';
			}
		}
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

	public function commentInit()
	{
		//1 get access_token by code
		$code = trim($_GET['code']);
		$state = trim($_GET['state']);
		if(!empty($code)&&$state==1)
		{
			$wxinfo = M("Wxconfig")->where(array("id"=>1))->find();
			$url =  "https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$wxinfo['appid']."&secret=".$wxinfo['appsecret']."&code=".$code."&grant_type=authorization_code";
			$result = $this->wxHttpsRequest($url);
			$jsoninfo = json_decode($result, true);	
			if(!empty($jsoninfo))
			{
				$userinfo = "https://api.weixin.qq.com/sns/userinfo?access_token=".$jsoninfo['access_token']."&openid=".$jsoninfo['openid']."&lang=zh_CN";
				$result = $this->wxHttpsRequest($userinfo);
				$jsoninfo = json_decode($result, true);
				if(!empty($jsoninfo)){
					$data = array();
					$openid = $jsoninfo['openid'];
					$headimgurl = str_replace("/0","/46",$jsoninfo['headimgurl']);
					$data['openid'] = $openid;
					$data['nickname'] = $jsoninfo['nickname'];
					$data['headimgurl'] = $headimgurl;
					$data['time'] = time();
					$result	= M("Wxuser")->where("openid='".$openid."'")->find();
					if(empty($result))
					{
						M("Wxuser")->data($data)->add();
					}
					else
					{
						M("Wxuser")->where(array("openid"=>$openid))->data(array("headimgurl"=>$headimgurl))->save();	
					}
					echo 'success_commentInit({"openid":"'.$openid.'","auth":"1","url":"'.$headimgurl.'","toggle":{"openAnyCom":"1","openWXCom":"1","openVerifyCode":"0"}})';
				}
			}
			else
			{
				echo 'success_commentInit({"toggle":{"openAnyCom":"1","openWXCom":"1","openVerifyCode":"0"}})';
			}
		}
		else
		{
			echo 'success_commentInit({"toggle":{"openAnyCom":"1","openWXCom":"1","openVerifyCode":"0"}})';
		}
	}
	public function tuwen()
	{
		$type = I("get.type");
		$m_type = I("get.m_type")?I("get.m_type"):0;
		$this->assign("m_type", intval($m_type));
		switch($type)
		{
			case "nav":
				$id = I("get.id");
				if(!empty($id))
				{
					$twinfo = M("pano_nav")->where(array("id"=>intval($id)))->find();
					$twinfo['twname'] = $twinfo['navname'];
					$twinfo['twcontent'] = $twinfo['content'];
				}
			break;
			case "hotploy":
				$id = I("get.id");
				if(!empty($id))
				{
					$twinfo = M("pano_hotploy")->where(array("id"=>intval($id)))->find();
					$twinfo['twname'] = $twinfo['title'];
					$twinfo['twcontent'] = $twinfo['text2'];
				}
			break;
			case "link":
				$id = I("get.id");
				if(!empty($id))
				{
					$twinfo = M("pano_link")->where(array("id"=>intval($id)))->find();
					$twinfo['twname'] = $twinfo['title'];
					$twinfo['twcontent'] = $twinfo['content'];
					$twinfo['twframebgcolor'] = $twinfo['framebgcolor'];
					$twinfo['twframebgcoloralpha'] = $twinfo['framebgcoloralpha'];
					$twinfo['twscrollingcolor'] = $twinfo['scrollingcolor'];
					$twinfo['twscrollingalpha'] = $twinfo['scrollingalpha'];
					$twinfo['twtextcolor'] = $twinfo['textcolor'];
					$twinfo['twtextcoloralpha'] = $twinfo['textcoloralpha'];
				}
			break;
			case "zhuspot":
				$id = I("get.id");
				if(!empty($id))
				{
					$twinfo = M("pano_zhu")->where(array("id"=>intval($id)))->find();
					$twinfo['twname'] = $twinfo['title'];
					if(empty($twinfo['content'])) $twinfo['content'] = $twinfo['text'];
					$twinfo['twcontent'] = $twinfo['content'];
					$this->assign("twinfo", $twinfo);
					$this->display("zhuspot");
					exit;
				}
			break;
			case "card":
				$id = I("get.id");
				if(!empty($id))
				{
					$twinfo = M("pano_card")->where(array("pano_id"=>intval($id)))->find();					
					if(!empty($twinfo['c_weixinimg'])){
						$twinfo['c_weixinimg'] = $domainlink.$twinfo['c_weixinimg'];
					}
					$pano_logo = M("pano")->where(array("id"=>intval($id)))->getField('pano_logo');
					if(!empty($pano_logo)){
						$twinfo['pano_logo'] = $domainlink.$pano_logo;
					}
					$this->assign("twinfo", $twinfo);
					$this->display("card");
					exit;
				}
			break;
		}
		$this->assign("twinfo", $twinfo);
		$this->display();
	}
	public function navmsg()
	{
		$id = I("get.id");
		$twinfo = M("pano_nav")->where(array("id"=>intval($id)))->find();

		if (I("post.dopost") == "save") {
            $name = trim(I('post.name'));
            $phone = I('post.phone');
            $qq = I('post.qq');
            $email = I('post.email');
            $pano_id = I('post.pano_id');
            $content = I('post.content');
			
            if ($name == ""){
                echo "<script>alert('请填写您的姓名！!');history.back(-1);</script>";
                exit();
            }
			if ($phone == ""){
				echo "<script>alert('请填写您的联系方式！');history.back(-1);</script>";
                exit();
            }
            $data = array(
                "name" => $name,
                "phone" => $phone,
                "email" => $email,
                "qq" => $qq,
                "pano_id" => $pano_id,
                "content" => $content,
                "addtime" => time()
            );
			M("pano_msglist")->data($data)->add();
			echo "<script>alert('感谢您的留言，我们将尽快与你取得联系！');history.back(-1);</script>";
            exit();
        }
		$this->assign("twinfo", $twinfo);
		$this->display();
	}

	public function share()
	{
		$pano_id = I("get.pano_id");
		$shareurl = $this->domainlink."/member/preview/view/pano_id/".$pano_id;
		$this->assign("shareurl", $shareurl);
		$this->display();
	}
}
?>