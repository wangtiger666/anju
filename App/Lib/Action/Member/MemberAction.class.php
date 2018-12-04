<?php

class MemberAction extends Action{

    var $member_id = "";
    var $menuList = "";

    static $HOUSER_INFO_ATTR = '房源信息属性';
    static $HOUSER_TYPE = '房屋户型';

    public function _initialize() {
        $member_pix = C("SESSION_MEMBERID");
        $this->member_id = $_SESSION[$member_pix];

        if (!$this->member_id > 0) {
            $this->redirect("login/index");
        }
		$pano_id = intval($_REQUEST['pano_id']);
		if(!empty($pano_id)){
			$panorow = D("Pano")->GetOne($pano_id, $this->member_id);
			$this->assign('pano_id', $pano_id);
			$this->assign('panorow', $panorow);
		}
		$HTTP_HOST = $_SERVER['HTTP_HOST']?$_SERVER['HTTP_HOST']:$HTTP_SERVER_VARS['HTTP_HOST'];
		$domainlink = 'http://' . $HTTP_HOST;
		$this->assign('domainlink', $domainlink);

        $this->menuList = '            
            <menu:top name="全景项目" open="1" ico="ico2.png" rank="">
                <menu:item name="作品管理" link="panolist/index" id="panolist"  rank="" />
                <menu:item name="全景热点" link="makespot/index" id="makespot" rank="" />
                <menu:item name="背景音乐" link="musicstore/index" id="musicstore" rank="" />
				<menu:item name="导航样式" link="daohang/manage" id="daohang" rank="" />    
				<menu:item name="UI模块" link="uistore/index" id="uistore" rank="" /> 


        
            </menu:top>
			<menu:top name="我的账户" open="1" ico="ico1.png" rank="">
                <menu:item name="帐号信息" link="user/index" id="user" rank="" />
            </menu:top>
            ';
        $this->pano = array(
            "front","back","left","right","up","down"
        );
        $this->mbpano = array(
            "f","b","l","r","u","d"
        );
    }
}

?>
