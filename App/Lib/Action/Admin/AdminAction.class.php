<?php

class AdminAction extends Action {

    var $adminid = "";
    var $menuFather = "";
    var $menuList = "";
    protected $pageSize = 15;
    static $COOPERATE_NO = 40;

	//获取当前时间
    public function getNowTime(){
    	return date('Y-m-d H:i:s');
	}

    public function _initialize() {

    	//站点名称  by SDL 20161226
    	$web_name = M("sysconfig")->where("varname = 'web_name' ")->getField('value');
		$this->assign("web_name", $web_name);

        $admin_id = C("SESSION_ADMINID");
		$res = $_SESSION[$admin_id];
        $this->adminid = $res;
		$this->assign('adminid', $this->adminid);
		
		$groupidrow = M("Admin")->where(array("id"=>$this->adminid))->find();		
		$group_id=$groupidrow['group_id'];
		$this->group_id = $group_id;
		$this->assign('group_idid', $this->group_id);
		
        
		if(!empty($this->adminid) && $this->adminid <> 1)
		{
			$row = M("Admin")->where(array("id"=>$this->adminid))->find();
			if($row['status'] == 0)
			{
				unset($_SESSION);
				session_destroy();
				$this->error('此帐号已被限制登录！');
				exit;
			}
		}	
		
		if($this->adminid == 1)
		{
			$this->menuFather = '
					<menu:father name="系统设置" id="1" rank=""/>
					<menu:father name="全景分类" id="2" rank=""/>
					<menu:father name="代理账号" id="3" rank=""/>
					<menu:father name="用户账号" id="4" rank=""/>										
					<menu:father name="全部作品" id="5" rank=""/>
					<menu:father name="广告位" id="6" rank=""/>
					<menu:father name="Web站管理" id="7" rank=""/>
				';

			$this->menuList = '
				<menu:top id="1" name="系统设置" display="block" rank="">
					<menu:item name="系统设置" link="system/index" rank="admin_info" />
					<menu:item name="微信配置" link="system/weixin" rank="admin_info" />
					<menu:item name="系统管理员" link="control/index" rank="admin_main" />
					<menu:item name="系统用户组" link="group/index" rank="admin_group" />
					<menu:item name="系统变量管理" link="Websystemval/index" rank="" />
					<menu:item name="友情链接管理" link="Weblink/index" rank="" />
				</menu:top>
				<menu:top id="1" name="版本升级" display="block" rank="">
					<menu:item name="一键版本更新" link="updata/index" rank="admin_updata" />
				</menu:top>
				
				<menu:top id="2" name="全景分类" display="block" rank="">
					<menu:item name="全景分类" link="channel/index" rank="" />
				</menu:top>

				<menu:top id="3" name="代理账号" display="block" rank="">
					<menu:item name="代理账号" link="control/dlindex" rank="" />
				</menu:top>

				<menu:top id="4" name="用户账号" display="block" rank="">
					<menu:item name="用户账号" link="user/index" rank="" />
				</menu:top>				

				<menu:top id="5" name="全景项目" display="block" rank="">
					<menu:item name="全景项目" link="pano/index" rank="" />
				</menu:top>

				<menu:top id="6" name="广告位" display="block" rank="">
					<menu:item name="广告位" link="ad/index" rank="" />
				</menu:top>
					<menu:top id="7" name="Web站管理" display="block" rank="">
					<menu:item name="文章栏目管理" link="WebArtlanmu/index" rank="" />
					<menu:item name="文章管理" link="WebArticle/index" rank="" />
					<menu:item name="文章推荐位管理" link="WebArtcat/index" rank="" />
					<menu:item name="广告管理" link="WebAd/imgindex" rank="" />
					<menu:item name="全景推荐位管理" link="WebPanoposition/index" rank="" />
					<menu:item name="全景行业管理" link="WebHangye/index" rank="" />
					
				</menu:top>
				';
		}
		else
		{
			$this->menuFather = '
					<menu:father name="用户账号" id="1" rank=""/>
				';

			$this->menuList = '
				<menu:top id="1" name="用户账号" display="block" rank="">
					<menu:item name="用户账号" link="user/index" rank="" />
				</menu:top>
			';
		}

        if (!$res > 0) {
            $this->redirect("login/index");
        }

    $HTTP_HOST = $_SERVER['HTTP_HOST']?$_SERVER['HTTP_HOST']:$HTTP_SERVER_VARS['HTTP_HOST'];
   	$domainlink = 'http://' . $HTTP_HOST;
    $this->assign("domainlink", $domainlink);
    }
}

?>
