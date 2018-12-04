<?php

class ConfigAction extends MemberAction {

    function index() {
        if (I("post.dopost") == "save") {
            $pano_id = I("post.pano_id");
			$hangyeid = M("Pano")->where(array("id"=>$pano_id))->getField('hangyeid');
			$web_hangye = $this->hangye($hangyeid);//行业
			
            $panowhere = array(
                "id" => $pano_id,
                "member_id" => $this->member_id
            );
            $title = trim(I('post.title'));
            $is_ipad_view = intval(I('post.is_ipad_view'));
            $is_littleplanet_view = intval(I('post.is_littleplanet_view'));
            $cursor_open = intval(I("post.cursor_open"));
            $cursor_id = intval(I('post.cursor_id'));
            $openautorate = intval(I("post.openautorate"));
            $autoratewaittime = intval(I("post.autoratewaittime"));
            $autoratespeed = intval(I("post.autoratespeed"));
            $autorateaccel = intval(I("post.autorateaccel"));
            $openautonext = intval(I('post.openautonext'));
            $autonextpass = intval(I('post.autonextpass'));
            $thumbwidth = intval(I('post.thumbwidth'));
            $thumbheight = intval(I('post.thumbheight'));

			$openuipifu = intval(I('post.openuipifu'));   //皮肤控制 2017.10.15
			$openwechat = intval(I('post.openwechat'));
			$opentongji = intval(I('post.opentongji'));
			$openzan = intval(I('post.openzan'));
			$openshare = intval(I('post.openshare'));

			$website = str_replace("http://","",trim(I('post.website')));			
			$website = "http://".$website;

			$linkphone = trim(I('post.linkphone'));
			
			
			$wxdesc = trim(I('post.wxdesc'));  //微信描述
			$wx_url = trim(I('post.wx_url'));  //微信链接
			$gundongzimu = trim(I('post.gundongzimu'));
			$address = trim(I('post.address'));
			$map = trim(I('post.map'));
			//weather start 2017.10.15 start
			$openweahter = intval(I('post.openweahter'));   
			$cityname = trim(I('post.cityname'));
			$weatheropen = intval(I('post.weatheropen'));
			//weather start 2017.10.15 end
			
			
			$openwelcome = intval(I('post.openwelcome'));
			$openauthor = intval(I('post.openauthor'));
			$openpanologo = intval(I('post.openpanologo'));
			$openwxlogo = intval(I('post.openwxlogo'));  //微信小图标

			$openhttp = intval(I('post.openhttp'));
			$opentel = intval(I('post.opentel'));
			$opennews = intval(I('post.opennews'));
			$opendaohang = intval(I('post.opendaohang'));
			$opencard = intval(I('post.opencard'));
			$mobileuisize = trim(I('post.mobileuisize'));
			$open_apple = intval(I('post.open_apple'));
			$hangyeid = I("post.hangyeid");
			$pid = I("post.pid");
			$content = I("post.content");

			if(!empty($map))
			{
				$map = explode(",",$map);
				$map_x = $map[0];
				$map_y = $map[1];
				M("Pano")->where(array("id" => $pano_id))->save(array("map_x"=>$map_x,"map_y"=>$map_y));
			}

            if ($title == "") {
                $this->error("标题不能为空！");
                exit();
            }
			$fileurl = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/uiimage";
			if(!empty($_POST['welcome'])){
				$welcome = ExecUpload($_POST['welcome'], $_POST['old_welcome'], $fileurl);
				M("Pano")->where(array("id" => $pano_id))->save(array("welcome"=>$welcome));
			}

			$fileurl = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/uiimage";
			if(!empty($_POST['pano_logo'])){
				$pano_logo = ExecUpload($_POST['pano_logo'], $_POST['old_pano_logo'], $fileurl);
				M("Pano")->where(array("id" => $pano_id))->save(array("pano_logo"=>$pano_logo));
			}
			//微信小图标
			$fileurl = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/uiimage";
			if(!empty($_POST['wx_logo'])){
				$wx_logo = ExecUpload($_POST['wx_logo'], $_POST['old_wx_logo'], $fileurl);
				M("Pano")->where(array("id" => $pano_id))->save(array("wx_logo"=>$wx_logo));
			}
			//微信小图标
			
			
			
			

            $data = array(
                "title" => $title,
                "member_id" => $this->member_id,
                "is_ipad_view" => $is_ipad_view,
                "is_littleplanet_view" => $is_littleplanet_view,
                "cursor_open" => $cursor_open,
                "cursor_id" => $cursor_id,
                "openautorate" => $openautorate,
                "autoratewaittime" => $autoratewaittime,
                "autoratespeed" => $autoratespeed,
                "autorateaccel" => $autorateaccel,
                "openautonext" => $openautonext,
                "autonextpass" => $autonextpass,
				
				"thumbwidth" => $thumbwidth,
				"thumbheight" => $thumbheight,
				
				'openuipifu' => $openuipifu,    //皮肤控制 2017.10.15
                'openwechat' => $openwechat,
                'opentongji' => $opentongji,
                'openzan' => $openzan,
                'openshare' => $openshare,
                'website' => $website,
                'linkphone' => $linkphone,
				'wxdesc' => $wxdesc,    //微信描述
				'wx_url' => $wx_url,    //微信分享链接
                'gundongzimu' => $gundongzimu,
                'address' => $address,
				//weather start 2017.10.15 start
				'openweahter' => $openweahter,
                'cityname' => $cityname,
                'weatheropen' => $weatheropen,
				//weather start 2017.10.15 end
				
                'openwelcome' => $openwelcome,
                'openauthor' => $openauthor,
                'openpanologo' => $openpanologo,
				'openwxlogo' => $openwxlogo,  //微信小图标
                'openhttp' => $openhttp,
                'opentel' => $opentel,
                'opennews' => $opennews,
                'opendaohang' => $opendaohang,
                'opencard' => $opencard,
				
				'hangyeid' => $hangyeid,
				'open_apple' => $open_apple,
				'mobileuisize' => $mobileuisize,
				'pid' => $pid,
				'content' => $content,
            );
            M("Pano")->where($panowhere)->save($data);
			
			$zan = intval(I('post.zan'));
			$hits = intval(I('post.hits'));
			if(isset($zan))
			{
				M("Hitscount")->where(array("pano_id"=>$pano_id))->save(array("zan"=>$zan));
			}
			if(isset($hits))
			{
				M("Hitscount")->where(array("pano_id"=>$pano_id))->save(array("hits"=>$hits));
			}
            $this->success("修改成功！", U("index", array("pano_id" => $pano_id)));
            exit();
        }

        $pano_id = I("get.pano_id");
        $this->assign('pano_id', $pano_id);

        $panowhere = array(
            "id" => $pano_id,
            "member_id" => $this->member_id
        );
        $panorow = M("Pano")->where($panowhere)->find();
        if(!empty($panorow['map_x'])||!empty($panorow['map_y'])){
			$panorow['map'] = $panorow['map_x'].",".$panorow['map_y'];
		}
		$this->assign('panorow', $panorow);

        $syscursorwhere = array(
            "type" => "system"
        );
        $syscursorrow = M("Cursor")->where($syscursorwhere)->select();
        $this->assign("syscursorrow", $syscursorrow);

        $selfcursorwhere = array(
            "type" => "self",
            "member_id" => $this->member_id
        );
        $selfcursorrow = M("Cursor")->where($selfcursorwhere)->select();
        $this->assign("selfcursorrow", $selfcursorrow);

        $mid = $this->member_id;
        $this->assign('mid', $mid);

		$panohits = M("Hitscount")->where(array("pano_id"=>$pano_id))->find();
		
		
		$hangyeid = M("Pano")->where(array("id"=>$pano_id))->getField('hangyeid'); 
			
		//查询列表数据
		$panoList = M("Pano")->field('id, title')->select();
        $this->assign('panoList', $panoList);

		$web_hangye = $this->hangye($hangyeid);//行业
		
		$this->assign('web_hangye', $web_hangye);
		$zan = intval($panohits['zan']);
		$hits = intval($panohits['hits']);		
		$this->assign('zan', $zan);
		$this->assign('hits', $hits);

        $this->display();
    }
	public function card()
	{
		$pano_id = intval($_REQUEST['pano_id']);
		if (I("post.dopost") == "save")
		{
			$data = array();			
			$data['c_name'] = I("post.c_name");
			$data['c_phone'] = I("post.c_phone");
			$data['c_email'] = I("post.c_email");
			$data['c_address'] = I("post.c_address");
			$data['c_wechat'] = I("post.c_wechat");
			$data['c_qq'] = I("post.c_qq");
			$data['c_remarks'] = I("post.c_remarks");
			$fileurl = "/uploads/user/" . substr(md5($this->member_id), 5, 15) . "/weixinimg";
			if(!empty($_POST['file'])){
				createFolder(APP_ROOT . $fileurl);
				$c_weixinimg = ExecUpload($_POST['file'], $_POST['old_file'], $fileurl);
				$data['c_weixinimg'] = $c_weixinimg;
			}

			$count = M("pano_card")->where(array("pano_id"=>$pano_id))->count();			
			if($count<=0){
				$data['pano_id'] = $pano_id;
				M("pano_card")->data($data)->add();
			}else{
				M("pano_card")->where(array("pano_id"=>$pano_id))->data($data)->save();
			}
			echo "<script>alert('修改成功！');window.location.href='".U("card", array("pano_id" => $pano_id))."';</script>";
            exit;
		}
		$cardinfo = M("pano_card")->where(array("pano_id"=>$pano_id))->find();
		$this->assign('cardinfo', $cardinfo);
		$this->display();
	}

	//腾讯地图
	public function getpoint()
	{
		$this->display();
	}


	//高德地图
    public function gdgetpoint()
    {

    	$province_id = I('get.province_id');
    	$city_id = I('get.city_id');
    	$district_id = I('get.district_id');
    	$detailAddress = I('get.detailAddress');
    	$province = M('region')->where(array('id' => $province_id))->field('name')->find();
    	$city = M('region')->where(array('id' => $city_id))->field('name')->find();
    	$district = M('region')->where(array('id' => $district_id))->field('name')->find();


    	$detailAddress = $province['name'] . $city['name'] . $district['name'] . $detailAddress;
    	$this->assign('detailAddress', $detailAddress);
        $this->display();
    }
	
	//所属行业函数
	public function hangye($hangyeid)
	{

		 $group = '<select name="hangyeid">
            <option value="">请选择</option>';
            $grouplist = M("yunweb_hangye")->select();
            if(!empty($grouplist))
            {
                foreach($grouplist as $key=>$val)
                {
                    $seled = "";
                    if($hangyeid==$val['id']) $seled = " selected";
                    $group .= "<option value=\"".$val['id']."\" $seled>".$val['name']."</option>";
                }
            }
        $group .= "</select>";
        return $group;
	}
	

}

?>
