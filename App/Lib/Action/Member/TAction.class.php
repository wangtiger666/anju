<?php

class TAction extends TcommonAction {

    function index() {
        cookie("previewback", __SELF__);
        $pano_id = I("get.pano_id");
        $this->assign('pano_id', $pano_id);

        $panowhere = array(
            "id" => $pano_id,
            "member_id" => $this->member_id
        );
        $panorow = M("Pano")->where($panowhere)->find();
        $this->assign('panorow', $panorow);

        $viewwhere = array(
            "pano_id" => $pano_id
        );
        $viewlist = M("Pano_view")->where($viewwhere)->order("sort")->select();
        $this->assign('viewlist', $viewlist);

        $this->display();
    }

    function view() {
		header('Content-type: application/json; charset=UTF-8');
		$guid = trim(I("get.guid"));
		
		$info = M("Pano")->where(array("guid"=>$guid))->find();
		$pano_id = $info['id'];
		
        //$pano_id = I("get.pano_id");

        $panowhere = array(
            "id" => $pano_id,
            "member_id" => $this->member_id
        );
        $panorow = M("Pano")->where($panowhere)->find();
        $this->assign('panorow', $panorow);


        // 720环物  start   2017.10.31
		
		if($panorow['open720ring']==1){
			$viewwhere = array(
            "pano_id" => $pano_id,
            "member_id" => $this->member_id
        	);
        	$viewlist = M("Pano_view")->where($viewwhere)->find();
			$this->assign('viewlist', $viewlist);
        	$this->display('ring');
		}
		else{
		//720环物 end   2017.10.31
		
        $scene = I("get.scene");
        if ($scene == "") {
            $firstscene = getFirstScene($pano_id);
            $scene = $firstscene["sort"];
        }
		$HTTP_HOST = $_SERVER['HTTP_HOST']?$_SERVER['HTTP_HOST']:$HTTP_SERVER_VARS['HTTP_HOST'];
		$domainlink = 'http://' . $HTTP_HOST;
		$zhuchiren = M("pano_zhuchiren")->where(array("pano_id" => $pano_id))->find();
		$zuozhe=M("Member")->where(array("id"=>$this->member_id))->find();
		$wxinfo = M("Wxconfig")->where(array("id" => 1))->find();
		
		
		
		$loading = M("pano_loading")->where(array("pano_id" => $pano_id))->find();
		$loadingbg = M("loadingbg")->where(array("id" => $loading['navbgid']))->find();
		$this->assign(loading, $loading);
		$this->assign(loadingbg, $loadingbg);
		
		
		
		$this->assign('wxinfo', $wxinfo);
		$this->assign('zhuchiren', $zhuchiren);
		$this->assign('zuozhe', $zuozhe);
		$this->assign('domainlink', $domainlink);
		$this->assign('pano_id', $pano_id);

        $xmlurl = U('xml', array('pano_id' => $pano_id, 'scene' => $scene));
        $xmlscript = "embedpano({swf:\"__PUBLIC__/pano/pano.swf\", xml:\"$xmlurl\", target:\"pano\", html5:\"prefer\",wmode:\"transparent\", initvars:{design:\"\"}, passQueryParameters:true});";
        $this->assign('xmlscript', $xmlscript);
        $this->display();

	 }
    }
	

    function loadingbg() {
        $pano_id = I("get.pano_id");
		$panorow = M("Pano")->where(array("id" => $pano_id))->find();
        $loading = M("pano_loading")->where(array("pano_id" => $pano_id))->find();
		$loadingbg = M("loadingbg")->where(array("id" => $loading['navbgid']))->find();

		$this->assign(panorow, $panorow);
		$this->assign(loading, $loading);
		$this->assign(loadingbg, $loadingbg);

        $this->display('./App/Tpl/Member/Preview/loadingbg.css', 'utf-8', 'text/css');
    }
	
    function xml() {
        $pano_id = I("get.pano_id");
        $scene = I("get.scene");

        $this->assign("pano_id", $pano_id);

        if ($scene == "") {
            $scene = 1;
        }
        $this->assign('scene', $scene);

        $cursorM = D("Cursor");
        $this->assign("cursorM", $cursorM);

        $cursordog = $cursorM->getValue(1, "mode");
        $this->assign("cursordog", $cursordog);

        $panowhere = array(
            "id" => $pano_id,
            "member_id" => $this->member_id
        );
        
		$panorow = M("Pano")->where($panowhere)->find();        
		if($panorow['opennews']==1){
			$panorow['opennews'] = "true";
		}else{
			$panorow['opennews'] = "false";
		}
		if($panorow['opentel']==1){
			$panorow['opentel'] = "true";
		}else{
			$panorow['opentel'] = "false";
		}
		if($panorow['openhttp']==1){
			$panorow['openhttp'] = "true";
		}else{
			$panorow['openhttp'] = "false";
		}
		if($panorow['opendaohang']==1){
			$panorow['opendaohang'] = "true";
		}else{
			$panorow['opendaohang'] = "false";
		}
		if($panorow['openzan']==1){
			$panorow['openzan'] = "true";
		}else{
			$panorow['openzan'] = "false";
		}
		if($panorow['openshare']==1){
			$panorow['openshare'] = "true";
		}else{
			$panorow['openshare'] = "false";
		}
		if($panorow['opencard']==1){
			$panorow['opencard'] = "true";
		}else{
			$panorow['opencard'] = "false";
		}
		if(!empty($panorow['map_x'])&&!empty($panorow['map_y'])){
			$panorow['map'] = $panorow['map_x'].",".$panorow['map_y'];
		}
		$HTTP_HOST = $_SERVER['HTTP_HOST']?$_SERVER['HTTP_HOST']:$HTTP_SERVER_VARS['HTTP_HOST'];
		$domainlink = 'http://' . $HTTP_HOST;
		$this->assign('domainlink', $domainlink);
		$userinfo = M("Member")->where(array("id" => $this->member_id))->find();
		$this->assign('userinfo', $userinfo);		
		$this->assign('panorow', $panorow);
		
		//720环物 start   2017.10.31
		$ringwhere = array(
            "pano_id" => $pano_id,
            "member_id" => $this->member_id
        );
		$ringvr = M("Pano_cube_store")->where($ringwhere)->find();
		$this->assign('ringvr', $ringvr);
		
		//720环物end
		
		
		
		//视维分组插件
			$swfenzuwhere = array(
				"pano_id" => $pano_id			
       		);
        	$swfenzurow = M("Pano_fenzu")->where($swfenzuwhere)->select();
			//网展分组插件
			if(!empty($swfenzurow))
			{
				foreach($swfenzurow as $key=>$val)
				{
					
					$wzListrow = M("Pano_view")->where("fenzuid='".$val['id']."'")->select();
					foreach($wzListrow as $wzkey=>$wzval)
					{
					$wzListrow[$wzkey]['sort'] = $wzval['sort'];
					$wzListrow[$wzkey]['title'] = $wzval['title'];
					$wzListrow[$wzkey]['thumb'] = $wzval['thumb'];
					}
					$swfenzurow[$key]["wzListrow"] = $wzListrow;
					
				}
				
			}
			//end
        	$this->assign('swfenzurow', $swfenzurow);	
		//end			
			
		$navList = M("Pano_nav")->where("pano_id='$pano_id'")->order("listorder asc")->select();
		if(!empty($navList))
		{
			$x = 2;
			foreach($navList as $key=>$val)
			{
				$navbg = M("Navbg")->where("id='".$val['navbgid']."'")->find();
				$navList[$key]['navbg_file'] = $navbg['file'];
				$navList[$key]['navbg_width'] = $navbg['width'];
				$navList[$key]['navbg_height'] = $navbg['height'];
				$navList[$key]['navbg_crop'] = ceil($navbg['height']/2);
				if($val['opentype']==1){
					$navList[$key]['linkurl'] = $domainlink."/my.php?s=/Member/Panoapi/tuwen/type/nav/id/".$val['id'];
				}elseif($val['opentype']==2){
					$navList[$key]['linkurl'] = str_replace("&","$",$val['linkurl']);
				}elseif($val['opentype']==3){
					$navList[$key]['linkurl'] = $domainlink."/my.php?s=/Member/Panoapi/navmsg/id/".$val['id'];
				}
				$navList[$key]['navbg_x'] = $x;
				$x = $x+$navbg['width'];
			}
		}
		$panonav= M("Pano_nav")->where("pano_id='$pano_id'")->find();
		$this->assign('panonav', $panonav);
		$this->assign('navList', $navList);

        if ($panorow['open_music'] == 1) {
            $musicrow = M("Music")->where(array("id" => $panorow['music_id']))->find();
            $musicurl = $musicrow["file"];
            $this->assign('musicurl', $musicurl);
        }

        $viewwhere = array(
            "pano_id" => $pano_id,
            "member_id" => $this->member_id
        );
        $viewlist = M("Pano_view")->where($viewwhere)->order("sort")->select();
        $thumb = $viewlist[0]['thumb'];
        $thumbarr = getimagesize(APP_ROOT . $thumb);
        $this->thumbwidth = $thumbarr[0];
        $this->thumbheight = $thumbarr[1];

        $strorow = M("pano_intro")->where($viewwhere)->find();
        if (!is_array($strorow)){
            $strorow["open"] = 0;
        }
        $this->assign("strorow", $strorow);

        $scenedata = array();
        foreach ($viewlist as $vk => $view) {

            $scenedir = APP_ROOT . "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id . "/" . $view['filedir'];
            if (is_dir($scenedir . "/scene")){
                $viewlist[$vk]["vopen"] = 1;
                $frontdata = getimagesize(APP_ROOT.$view["front"]);
                $panowidth = $frontdata[0];
                $panowidth2 = ceil($panowidth / 2);
                $viewlist[$vk]["v1"] = $panowidth;
                $viewlist[$vk]["v2"] = $panowidth2;
                $viewlist[$vk]["web_dir"] = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/" . $pano_id . "/" . $view['filedir']."/scene";
            } else {
                $viewlist[$vk]["vopen"] = 0;
            }
			
			
			$toolboxmapspoint = M("Pano_toolbox_maps_point")->where("view_id='".$view['id']."'")->find();
			$viewlist[$vk]["toolboxmaps_on"] = intval($toolboxmapspoint['open']);

            $logowhere = array(
                "member_id" => $this->member_id,
                "view_id" => $view['id'],
                "open" => 1
            );
            $openrow = M("Pano_bottomlogo")->where($logowhere)->find();
            if (is_array($openrow)) {
                $viewlist[$vk]["logo"] = $openrow;
            } else {
                $viewlist[$vk]["logo"]["open"] = 0;
            }

            $luopanwhere = array(
                "member_id" => $this->member_id,
                "view_id" => $view['id']
            );
            $luopanrow = M("Pano_luopan")->where($luopanwhere)->find();
            if(!is_array($luopanrow)){
                $luopanrow = array("open"=>0);
            }
            $viewlist[$vk]["luopan"] = $luopanrow;

            $roamwhere = array(
                "member_id" => $this->member_id,
                "view_id" => $view['id']
            );
            $roamrow = M("Pano_roam")->where($roamwhere)->select();
            $viewlist[$vk]["roam"] = $roamrow;
			
			//热点音乐插件
			$musicpointwhere = array(
                "member_id" => $this->member_id,
                "view_id" => $view['id']
            );
            $musicpointrow = M("Pano_musicpoint")->where($musicpointwhere)->select();
            foreach ($musicpointrow as $vks => $vspot) {
                if($vspot["open_apple"] == 1){
                    $musicpointrow[$vks]["url"] = CC("web_root").$vspot["applefile"];
                    $musicpointrow[$vks]["devices"] = "all";
                }else{
                    $musicpointrow[$vks]["url"] = CC("web_root").$vspot["applefile"];
                    $musicpointrow[$vks]["devices"] = "flash";
                }

            }
            $viewlist[$vk]["musicpoint"] = $musicpointrow;
			//end
			
			
			
			
			//分组插件
			$fenzuwhere = array(
				"id" => $view['fenzuid']			
       	 	);
        	$fenzurow = M("Pano_fenzu")->where($fenzuwhere)->select();
        	$viewlist[$vk]["fenzu"] = $fenzurow;
			//end

            $vpointwhere = array(
                "member_id" => $this->member_id,
                "view_id" => $view['id']
            );
            $vpointrow = M("Pano_vpoint")->where($vpointwhere)->select();
            foreach ($vpointrow as $vks => $vspot) {
                if($vspot["open_apple"] == 1){
                    $vpointrow[$vks]["url"] = CC("web_root").$vspot["applefile"];
                    $vpointrow[$vks]["devices"] = "all";
                }else{
                    $vpointrow[$vks]["url"] = CC("web_root").$vspot["applefile"];
                    $vpointrow[$vks]["devices"] = "flash";
                }
				if(!empty($vspot['appleimg'])){
					$vpointrow[$vks]["appleimg"] = CC("web_root").$vspot["appleimg"];
				}else{
					$vpointrow[$vks]["appleimg"] = "";	
				}
            }
            $viewlist[$vk]["vpoint"] = $vpointrow;

            $linkwhere = array(
                "member_id" => $this->member_id,
                "view_id" => $view['id']
            );
            $linkrow = M("Pano_link")->where($linkwhere)->select();
			foreach($linkrow as $lk=>$lval)
			{
				$linkrow[$lk]["tuwenlink"] = $domainlink."/my.php?s=/Member/Panoapi/tuwen/type/link/id/".$lval['id'];
				$linkrow[$lk]["link"] = str_replace("&","$",$lval['linkurl']);
			}
            $viewlist[$vk]["link"] = $linkrow;
			
			
			//皓哥源码独家开发  2017.6.23  start
			 $rewwwwhere = array(
                "member_id" => $this->member_id,
                "view_id" => $view['id']
            );
            $rewwwrow = M("Pano_rewww")->where($rewwwwhere)->select();
			foreach($rewwwrow as $lk=>$lval)
			{
				$rewwwrow[$lk]["rewww"] = str_replace("&","$",$lval['linkurl']);
			}
            $viewlist[$vk]["rewww"] = $rewwwrow;
			//皓哥源码独家开发  2017.6.23  end
			
			
			
			
			

            $zhuwhere = array(
                "member_id" => $this->member_id,
                "view_id" => $view['id']
            );
            $zhurow = M("Pano_zhu")->where($zhuwhere)->select();
			if(!empty($zhurow))
			{
				foreach($zhurow as $zk=>$zval)
				{
					$zhurow[$zk]["tuwenlink"] = $domainlink."/my.php?s=/Member/Panoapi/tuwen/type/zhuspot/id/".$zval['id'];
				}
			}
            $viewlist[$vk]["zhu"] = $zhurow;

            $cuberow = M("Pano_cube")->where($roamwhere)->select();
            $viewlist[$vk]["cube"] = $cuberow;
            $viewlist[$vk]["cubebag"] = array();
            foreach ($cuberow as $cube) {
                array_push($viewlist[$vk]["cubebag"], $cube['id']);
            }

            $photorow = M("Pano_photo")->where($roamwhere)->select();
            $viewlist[$vk]["photo"] = $photorow;
            $viewlist[$vk]["photobag"] = array();
            foreach ($photorow as $photo) {
                array_push($viewlist[$vk]["photobag"], $photo['id']);
            }

            $lensflarerow = M("Pano_lensflare")->where($roamwhere)->select();
            $viewlist[$vk]["lensflare"] = $lensflarerow;

            $vurow = M("Pano_ui_paths")->where($roamwhere)->select();
            if (count($vurow) > 0) {
                $viewlist[$vk]["haveui"] = 1;
            } else {
                $viewlist[$vk]["haveui"] = 0;
            }

            $ployrow = M("Pano_hotploy")->where($roamwhere)->select();
            $viewlist[$vk]["hotploybag"] = array();
            foreach ($ployrow as $pk => $ploy) {
                $ployarr = explode("$", $ploy["ploydata"]);
                $ployspot = array();
                foreach ($ployarr as $k => $parr) {
                    $pcut = explode("|", $parr);
                    $ployspot[$k]["atv"] = $pcut[0];
                    $ployspot[$k]["ath"] = $pcut[1];
                }
                $ployrow[$pk]["pointarr"] = $ployspot;
                if ($ploy["action_type"] != "1") {
                    array_push($viewlist[$vk]["hotploybag"], $ploy['id']);
                }
				$ployrow[$pk]['tuwenlink'] = $domainlink."/my.php?s=/Member/Panoapi/tuwen/type/hotploy/id/".$ploy['id'];
            }
            $viewlist[$vk]["hotploy"] = $ployrow;
			
			
			
			//2017.3.22热点图片 start				
			$redpicrow = M("Pano_link")->where($roamwhere)->select();
            $viewlist[$vk]["redpicbag"] = array();
			foreach ($redpicrow as $pk => $ploy) {
                $ployarr = explode("$", $ploy["ploydata"]);
                $ployspot = array();
                foreach ($ployarr as $k => $parr) {
                    $pcut = explode("|", $parr);
                    $ployspot[$k]["atv"] = $pcut[0];
                    $ployspot[$k]["ath"] = $pcut[1];
                }
                $ployrow[$pk]["pointarr"] = $ployspot;
                if ($ploy["action_type"] = "3") {
                    array_push($viewlist[$vk]["redpicbag"], $ploy['id']);
                }
			 }	
			//2017.3.22热点图片 end	
			
			
			

            $ployvideorow = M("Pano_ployvideo")->where($roamwhere)->select();
            foreach ($ployvideorow as $pk => $ploy) {
                $ployarr = explode("$", $ploy["ploydata"]);
                $ployspot = array();
                foreach ($ployarr as $k => $parr) {
                    $pcut = explode("|", $parr);
                    $ployspot[$k]["atv"] = $pcut[0];
                    $ployspot[$k]["ath"] = $pcut[1];
                }
                $ployvideorow[$pk]["pointarr"] = $ployspot;
                if($ploy["open_apple"] == 1){
                    $ployvideorow[$pk]["url"] = CC("web_root").$ploy["applefile"];                    
                }else{
                    $ployvideorow[$pk]["url"] = CC("web_root").$ploy["applefile"];
                }
				if(!empty($ploy['appleimg'])){
					$ployvideorow[$pk]["appleimg"] = CC("web_root").$ploy["appleimg"];
				}else{
					$ployvideorow[$pk]["appleimg"] = "";	
				}
            }
            $viewlist[$vk]["ployvideo"] = $ployvideorow;

            $vspotrow = M("Pano_vspot")->where($roamwhere)->select();
            foreach ($vspotrow as $vks => $vspot) {
                if($vspot["open_apple"] == 1){
                    $vspotrow[$vks]["url"] = CC("web_root").$vspot["applefile"];
                    $vspotrow[$vks]["devices"] = "all";
                }else{
                    $vspotrow[$vks]["url"] = CC("web_root").$vspot["applefile"];
                    $vspotrow[$vks]["devices"] = "flash";
                }
                if($vspot["action"] == 1){
                    $vspotrow[$vks]["eve"] = "togglepause();";
                    $vspotrow[$vks]["style"] = "";
                    $vspotrow[$vks]["hover"] = "if(ispaused,showtext('点击播放');,showtext('点击暂停'););";
                }else if($vspot["action"] == 2){
                    $vspotrow[$vks]["eve"] = "";
                    $vspotrow[$vks]["style"] = "flyoutimage";
                    $vspotrow[$vks]["hover"] = "showtext('点击飞出/飞回');";
                }
				if(!empty($vspot['appleimg'])){
					$vspotrow[$vks]["appleimg"] = CC("web_root").$vspot["appleimg"];
				}else{
					$vspotrow[$vks]["appleimg"] = "";	
				}
            }
            $viewlist[$vk]["vspot"] = $vspotrow;

            $scenedata[$view['id']] = $view['sort'];
        }

        $this->assign('viewlist', $viewlist);
        $this->assign("scenedata", $scenedata);

        $weather_used = 0;
        $viewwhere = array(
            "pano_id" => $pano_id,
            "member_id" => $this->member_id
        );
        $viewlist = M("Pano_view")->where($viewwhere)->order("sort")->select();
        foreach ($viewlist as $vk => $view) {
            if ($view['effect_mod'] != "noeffect") {
                $weather_used++;
            }
        }
        $this->assign("weather_used", $weather_used);

        $spotdata = array();
        $sysspotwhere = array(
            "mode" => "system"
        );
        $sysspotrow = M("Spot")->where($sysspotwhere)->select();
        foreach ($sysspotrow as $sysspot) {
            $spotdata[$sysspot['id']] = $sysspot;
        }
        $spotwhere = array(
            "mode" => "self",
            "member_id" => $this->member_id
        );
        $spotrow = M("Spot")->where($spotwhere)->select();
        foreach ($spotrow as $spot) {
            $spotdata[$spot['id']] = $spot;
        }
        $this->assign("spotdata", $spotdata);

        $mybag = CC("web_root") . "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/{$pano_id}";
        $this->assign("mybag", $mybag);

        $device = array(
            0 => array(
                0 => "flash",
                1 => "html5"
            ),
            1 => array(
                0 => "flash",
                1 => "all"
            )
        );
        $this->assign("device", $device);

        $sysuiwhere = array(
            "pano_id" => $pano_id,
            "member_id" => $this->member_id,
            "utp" => 2
        );
        $sysuirow = M("Pano_ui")->where($sysuiwhere)->select();
        foreach ($sysuirow as $k => $sui) {
            $suirow = M("Sysui")->where(array("id" => $sui['uid']))->find();
            $sysuirow[$k]['path'] = $suirow['path'];
        }
        $this->assign("sysuirow", $sysuirow);

        $uiwhere = array(
            "pano_id" => $pano_id,
            "member_id" => $this->member_id,
            "utp" => 1
        );
        $uirow = M("Pano_ui")->where($uiwhere)->select();
        $this->assign("uirow", $uirow);

        $mbuiwhere = array(
            "pano_id" => $pano_id,
            "member_id" => $this->member_id,
            "utp" => 1
        );
        $mbuirow = M("Pano_ui")->where($mbuiwhere)->select();
        $this->assign("mbuirow", $mbuirow);

        $banwhere = array(
            "pano_id" => $pano_id,
            "member_id" => $this->member_id
        );
        $banrow = M("Pano_ban")->where($banwhere)->select();
        $this->assign("banrow", $banrow);

        $toolboxmapwhere = array(
            "pano_id" => $pano_id,
            "member_id" => $this->member_id,
            "open" => 1
        );
        $toolboxmaprow = M("Pano_toolbox_map")->where($toolboxmapwhere)->find();
        if (is_array($toolboxmaprow)) {
            $toolboxmap_on = 1;
        } else {
            $toolboxmap_on = 0;
        }
        $this->assign('toolboxmap_on', $toolboxmap_on);

        $toolboxmapswhere = array(
            "pano_id" => $pano_id,
            "member_id" => $this->member_id,
            "open" => 1
        );
        $toolboxmapsrow = M("Pano_toolbox_maps")->where($toolboxmapswhere)->find();
        if (is_array($toolboxmapsrow)) {
            $toolboxmaps_on = 1;
        } else {
            $toolboxmaps_on = 0;
        }
        $this->assign('toolboxmaps_on', $toolboxmaps_on);
	
		// 720环物  start   2017.10.31
		
		if($panorow['open_ring']==1){
        	$this->display('./App/Tpl/Member/Preview/ringxml.xml', 'utf-8', 'text/xml');
		}
		else{
			$this->display('./App/Tpl/Member/Preview/xml.xml', 'utf-8', 'text/xml');
		}
		//720环物 end   2017.10.31
    }

    function basexml() {
        $pano_id = I("get.pano_id");

        $weather_used = 0;
        $viewwhere = array(
            "pano_id" => $pano_id,
            "member_id" => $this->member_id
        );
        $viewlist = M("Pano_view")->where($viewwhere)->order("sort")->select();
        foreach ($viewlist as $vk => $view) {
            if ($view['effect_mod'] != "noeffect") {
                $weather_used++;
            }
        }
        $this->assign("weather_used", $weather_used);

        $this->display('./App/Tpl/Member/Preview/base.xml', 'utf-8', 'text/xml');
    }

    function readui() {
        $uid = I("get.uid");
        $this->assign('uid', $uid);
        $where = array(
            "uid" => $uid
        );
        $row = M("Ui_child")->where($where)->select();
        foreach ($row as $k => $ui) {
            if ($ui['parent'] == 0) {
                $row[$k]['parent_name'] = "";
            } else {
                $row[$k]['parent_name'] = "ui" . $ui['parent'];
            }
            $actionwhere = array(
                "cid" => $ui['id'],
                "member_id" => $this->member_id
            );
            $actionrow = M("Ui_action")->where($actionwhere)->select();
            $actionxml = "";
            foreach ($actionrow as $actiondata) {
                if ($actiondata['actiondo'] != "") {
                    $actionxml .= " on{$actiondata['eventchu']}=\"{$actiondata['actiondo']}\"";
                }
            }
            $row[$k]['action'] = $actionxml;
        }
        $this->assign("row", $row);
        $this->display('./App/Tpl/Member/Preview/readui.xml', 'utf-8', 'text/xml');
    }

    function readvui() {
        $view_id = I("get.view_id");
        $this->assign("view_id", $view_id);

        $viewrow = D("Panoview")->getOne($view_id, $this->member_id);
        $this->assign("viewrow", $viewrow);

        $where = array(
            "view_id" => $view_id,
            "devices" => "flash"
        );
        $row = M("Pano_ui_paths")->where($where)->select();
        foreach ($row as $k => $ui) {
            if ($ui['parent'] == 0) {
                $row[$k]['parent_name'] = "";
            } else {
                $row[$k]['parent_name'] = "ui" . $ui['parent'];
            }
            $actionwhere = array(
                "cid" => $ui['id'],
                "member_id" => $this->member_id
            );
            $actionrow = M("Pano_ui_action")->where($actionwhere)->select();
            $actionxml = "";
            foreach ($actionrow as $actiondata) {
                if ($actiondata['actiondo'] != "") {
                    $actionxml .= " on{$actiondata['eventchu']}=\"{$actiondata['actiondo']}\"";
                }
            }
            $row[$k]['action'] = $actionxml;
        }
        $this->assign("row", $row);

        $mbwhere = array(
            "view_id" => $view_id,
            "devices" => "mobile"
        );
        $mbrow = M("Pano_ui_paths")->where($mbwhere)->select();
        foreach ($mbrow as $k => $ui) {
            if ($ui['parent'] == 0) {
                $mbrow[$k]['parent_name'] = "";
            } else {
                $mbrow[$k]['parent_name'] = "ui" . $ui['parent'];
            }
            $actionwhere = array(
                "cid" => $ui['id'],
                "member_id" => $this->member_id
            );
            $actionmbrow = M("Pano_ui_action")->where($actionwhere)->select();
            $actionxml = "";
            foreach ($actionmbrow as $actiondata) {
                if ($actiondata['actiondo'] != "") {
                    $actionxml .= " on{$actiondata['eventchu']}=\"{$actiondata['actiondo']}\"";
                }
            }
            $mbrow[$k]['action'] = $actionxml;
        }
        $this->assign("mbrow", $mbrow);

        $this->display('./App/Tpl/Member/Preview/readvui.xml', 'utf-8', 'text/xml');
    }

    function readmbui() {
        $uid = I("get.uid");
        $this->assign('uid', $uid);
        $where = array(
            "uid" => $uid
        );
        $row = M("Ui_child")->where($where)->select();
        foreach ($row as $k => $ui) {
            if ($ui['parent'] == 0) {
                $row[$k]['parent_name'] = "";
            } else {
                $row[$k]['parent_name'] = "ui" . $ui['parent'];
            }
            $actionwhere = array(
                "cid" => $ui['id'],
                "member_id" => $this->member_id
            );
            $actionrow = M("Ui_action")->where($actionwhere)->select();
            $actionxml = "";
            foreach ($actionrow as $actiondata) {
                if ($actiondata['actiondo'] != "") {
                    $actionxml .= " on{$actiondata['eventchu']}=\"{$actiondata['actiondo']}\"";
                }
            }
            $row[$k]['action'] = $actionxml;
        }
        $this->assign("row", $row);
        $this->display('./App/Tpl/Member/Preview/readmbui.xml', 'utf-8', 'text/xml');
    }

    function readcube() {
        $cube_id = I("get.cube_id");
        $this->assign('cube_id', $cube_id);
        $cuberow = D("Cube")->GetOne($cube_id, $this->member_id);
        $this->assign('cuberow', $cuberow);


        $where = array(
            "id" => $cuberow['target_id']
        );
        $cubelist = M("Pano_cube_store")->where($where)->find();
        $cubelist['show_width'] = $cubelist['width']+50;
        $cubelist['show_height'] = $cubelist['height']+100;
		$this->assign("row", $cubelist);

        $end = array(
            0 => "png",
            1 => "gif",
            2 => "jpg"
        );
        $this->assign("end", $end);

        $speed = $cuberow['speed'];

        if ($speed == "") {
            $speed = 5;
        }
        $speedarr = array();
        $speedarr[1] = 0.4;
        $speedarr[2] = 0.35;
        $speedarr[3] = 0.3;
        $speedarr[4] = 0.25;
        $speedarr[5] = 0.2;
        $speedarr[6] = 0.15;
        $speedarr[7] = 0.1;
        $speedarr[8] = 0.05;
        $speedarr[9] = 0.03;
        $speedarr[10] = 0.001;

        $this->assign('speed', $speedarr[$speed]);

        $fd = $cuberow['otherdirc'];
        if ($fd == 1) {
            $fd = -1;
        } else {
            $fd = 1;
        }

        $this->assign('fd', $fd);

        $this->display('./App/Tpl/Member/Preview/readcube.xml', 'utf-8', 'text/xml');
    }

    function readphoto() {
        $photo_id = I("get.photo_id");
        $this->assign('photo_id', $photo_id);
        $photorow = D("Photo")->GetOne($photo_id, $this->member_id);
        $this->assign('photorow', $photorow);
        $where = array(
            "photo_id" => $photo_id
        );
        $photolist = M("Pano_photo_store")->where($where)->order("sord")->select();
        $this->assign("row", $photolist);
        $photourlarrlen = count($photolist);
        $this->assign("photourlarrlen", $photourlarrlen);

        $view_id = $photorow["view_id"];
        $viewwhere = array(
            "id" => $view_id,
            "member_id" => $this->member_id
        );
        $viewrow = M("Pano_view")->where($viewwhere)->find();
        $pano_id = $viewrow["pano_id"];

        $this->photoallwidth = $photourlarrlen * 90 + 5 * ($photourlarrlen - 1);
        $this->fileurl = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/{$pano_id}/photo/" . $photorow['pdir'];

        $this->display('./App/Tpl/Member/Preview/readphoto.xml', 'utf-8', 'text/xml');
    }

    function readholy() {
        $holy_id = I("get.holy_id");
        $this->assign('holy_id', $holy_id);
        $holyrow = M("Pano_hotploy")->where(array("id" => $holy_id))->find();
        $this->assign("holyrow", $holyrow);
        $view_id = $holyrow["view_id"];
        $this->assign("view_id", $view_id);
        $viewwhere = array(
            "id" => $view_id,
            "member_id" => $this->member_id
        );
        $viewrow = M("Pano_view")->where($viewwhere)->find();
        $pano_id = $viewrow["pano_id"];

        if ($holyrow["action_type"] == 2) {
            $photofile = APP_ROOT . $holyrow["photo1"];
            $photo = array();
            $arr = getimagesize($photofile);
            $photo["w"] = $arr[0];
            $photo["h"] = $arr[1];
            $photo["bw"] = $arr[0] + 10;
            $photo["bh"] = $arr[1] + 10;
            $this->assign("photo", $photo);
        } else if ($holyrow["action_type"] == 3) {
            $photofile = APP_ROOT . $holyrow["photo2"];
            $photo = array();
            $arr = getimagesize($photofile);
            $w = $arr[0];
            $h = $arr[1];
            $p = $w / $holyrow["photobox_width"];
            $h0 = $h / $p;
            $photo["w"] = $holyrow["photobox_width"];
            $photo["h"] = $h0;
            $this->assign("photo", $photo);
            $text = $holyrow["text2"];
            $text = str_replace("\r\n", "[br]", $text);
            $this->assign("text", $text);
        } else if ($holyrow["action_type"] == 4) {
            $photoarr = M("Imagestore")->where(array("pano_id" => $pano_id, "type" => "hotploy", "from_id" => $holy_id))->order("sort")->select();
            $photo_total = count($photoarr);
            $this->assign("photo_total", $photo_total);
            $this->photoallwidth = $photo_total * 90 + 5 * ($photo_total - 1);
            $this->assign("photoarr", $photoarr);
        }

        $this->display('./App/Tpl/Member/Preview/readholy.xml', 'utf-8', 'text/xml');
    }


	//2017.3.23新增热点图片放大缩小功能 start

 	function redpic() {
        $redpic_id = I("get.redpic_id");
        $this->assign('redpic_id', $redpic_id);
        $redpicrow = M("Pano_link")->where(array("id" => $redpic_id))->find();
        $this->assign("redpicrow", $redpicrow);
        $view_id = $redpicrow["view_id"];
        $this->assign("view_id", $view_id);
        $viewwhere = array(
            "id" => $view_id,
            "member_id" => $this->member_id
        );
        $viewrow = M("Pano_view")->where($viewwhere)->find();
        $pano_id = $viewrow["pano_id"];

        if ($redpicrow["opentype"] == 3) {
            $photofile = APP_ROOT . $redpicrow["photo1"];
            $photo = array();
            $arr = getimagesize($photofile);
            $photo["w"] = $arr[0];
            $photo["h"] = $arr[1];
            $photo["bw"] = $arr[0] + 10;
            $photo["bh"] = $arr[1] + 10;
            $this->assign("photo", $photo);
        }

        $this->display('./App/Tpl/Member/Preview/redpic.xml', 'utf-8', 'text/xml');
    }

	//2017.3.21新增热点图片放大缩小功能 end




    function readvtoolmap() {
        $pano_id = I("get.pano_id");

        $mapwhere = array(
            "member_id" => $this->member_id,
            "pano_id" => $pano_id
        );
        $maprow = M("Pano_toolbox_map")->where($mapwhere)->find();

        $photofile = APP_ROOT . $maprow['mapimg'];

        $this->assign('maprow', $maprow);
        $maparr = getimagesize(APP_ROOT . $maprow["mapimg"]);
        $this->assign("maparr", $maparr);
        $photo["w"] = $maparr[0];
        $photo["h"] = $maparr[1];
        $photo["bw"] = $maparr[0] + 10;
        $photo["bh"] = $maparr[1] + 10;
        $this->assign("photo", $photo);

        $viewwhere = array(
            "member_id" => $this->member_id,
            "pano_id" => $pano_id
        );
        $viewlist = M("Pano_view")->where($viewwhere)->order("sort")->select();
        foreach ($viewlist as $vk => $vrow) {
            $vid = $vrow['id'];
            $vwhere = array(
                "member_id" => $this->member_id,
                "pano_id" => $pano_id,
                "view_id" => $vid
            );
            $vfind = M("Pano_toolbox_map_point")->where($vwhere)->find();
            if (!is_array($vfind)) {
                $data = array(
                    "member_id" => $this->member_id,
                    "pano_id" => $pano_id,
                    "view_id" => $vid
                );
                M("Pano_toolbox_map_point")->add($data);
                $vfind = M("Pano_toolbox_map_point")->where($vwhere)->find();
            }
            $viewlist[$vk]['maparr'] = $vfind;
        }
        $this->assign('viewlist', $viewlist);

        $this->display('./App/Tpl/Member/Preview/readvtoolmap.xml', 'utf-8', 'text/xml');
    }

    function readvtoolmaps() {
        $pano_id = I("get.pano_id");

        $mapwhere = array(
            "member_id" => $this->member_id,
            "pano_id" => $pano_id
        );
        $maprow = M("Pano_toolbox_maps")->where($mapwhere)->find();
        $this->assign('maprow', $maprow);
        $maplist = M("Pano_toolbox_maps_map")->where($mapwhere)->select();
        foreach ($maplist as $mk => $mlarr) {
            $maparr = getimagesize(APP_ROOT . $mlarr["mapurl"]);
            $maplist[$mk]["photo"] = $maparr;
            $mppwhere = array(
                "member_id" => $this->member_id,
                "pano_id" => $pano_id,
                "maps_id" => $mlarr["id"]
            );
            $mpprow = M("Pano_toolbox_maps_point")->where($mppwhere)->select();
            $maplist[$mk]["mpp"] = $mpprow;
        }
        $this->assign('maplist', $maplist);

        $viewwhere = array(
            "member_id" => $this->member_id,
            "pano_id" => $pano_id
        );
        $viewlist = M("Pano_view")->where($viewwhere)->order("sort")->select();

        $viewsort = array();
        $viewname = array();
        foreach ($viewlist as $vk => $vrow) {
            $viewsort[$vrow["id"]] = $vrow["sort"];
            $viewname[$vrow["id"]] = $vrow["title"];
        }
        $this->assign('viewsort', $viewsort);
        $this->assign('viewname', $viewname);
        $this->assign('viewlist', $viewlist);

        if (substr_count($maprow["map_align"], "left") > 0) {
            $mapcenter = "lefttop";
        } else if (substr_count($maprow["map_align"], "right") > 0) {
            $mapcenter = "righttop";
        } else {
            $mapcenter = "lefttop";
        }
        $this->assign("mapcenter", $mapcenter);

        $this->display('./App/Tpl/Member/Preview/readvtoolmaps.xml', 'utf-8', 'text/xml');
    }

}

?>
