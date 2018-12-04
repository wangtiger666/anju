<?php

class TcommonAction extends Action{

    public function _initialize() {
        $member_pix = C("SESSION_MEMBERID");
		header('Content-type: application/json; charset=UTF-8');
		$guid = trim(I("get.guid"));	
		if(empty($guid)){
			$pano_id = intval($_REQUEST['pano_id']);
		}
		else{		
		$info = M("Pano")->where(array("guid"=>$guid))->find();
		$pano_id = $info['id'];
		}			
		
		if(empty($pano_id))
		{
			header("location: /");
        }
		
		
		$panorow = M("Pano")->where("id='$pano_id'")->find();
		$this->member_id = $panorow['member_id'];
		$this->assign('pano_id', $pano_id);
		$this->assign('panorow', $panorow);
		$HTTP_HOST = $_SERVER['HTTP_HOST']?$_SERVER['HTTP_HOST']:$HTTP_SERVER_VARS['HTTP_HOST'];
		$domainlink = 'http://' . $HTTP_HOST;
		$this->assign('domainlink', $domainlink);

        $this->menuList = '            
            <menu:top name="全景项目" open="1" ico="ico2.png" rank="">
                <menu:item name="作品管理" link="panolist/index" rank="" />
                <menu:item name="全景热点" link="makespot/index" rank="" />
                <menu:item name="背景音乐" link="musicstore/index" rank="" />
				<menu:item name="导航样式" link="daohang/manage" rank="" />                
            </menu:top>
			<menu:top name="我的账户" open="1" ico="ico1.png" rank="">
                <menu:item name="帐号信息" link="user/index" rank="" />
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