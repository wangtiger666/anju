<?php
// 本类由系统自动生成，仅供测试用途
class WebyunAction extends PublicAction {
	/**********************
	         首页
	*****************/
    public function index(){
    
		$banner = getYunAd(1,5);//首页banner
		
		$lanmulist = M("yun720_nav")->where("type=2")->order("orderlist")->select();
		$lanmu = $this->getTree($lanmulist,0,0);
		$bannerad = getYunAd(2,5);//首页顶部广告
		$bannerm = getYunAd(6,5);//手机首页顶部广告
		$hot_pano = getReadPano(0,12);//从1开始的12条数据  推荐
		$hengtiaoad = getYunAd(3,1);//首页横条广告
		$articlefirst = getYunAd(4,1);//首页文章精选广告1
		$articletwo = getYunarticleAd(4,1,4);//首页文章精选广告4
		$author_member = getZhuozhevipPano(0,1);//显示一个认证作者信息
		$sheys_member = getSheysPano(0,6);//显示6个摄影师信息

		$this->assign("lanmu",$lanmu);
		$this->assign("banner",$banner);
		$this->assign("bannerm",$bannerm);
		$this->assign("bannerad",$bannerad);
		$this->assign("hot_pano", $hot_pano);
		$this->assign("hengtiaoad",$hengtiaoad);
		$this->assign("articlefirst",$articlefirst);
		$this->assign("articletwo",$articletwo);
		$this->assign("author_member", $author_member);
		$this->assign("sheys_member", $sheys_member);

		//判断是手机端还是PC端
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $mobile_agents = Array("240x320","acer","acoon","acs-","abacho","ahong","airness","alcatel","amoi","android","anywhereyougo.com","applewebkit/525","applewebkit/532","asus","audio","au-mic","avantogo","becker","benq","bilbo","bird","blackberry","blazer","bleu","cdm-","compal","coolpad","danger","dbtel","dopod","elaine","eric","etouch","fly ","fly_","fly-","go.web","goodaccess","gradiente","grundig","haier","hedy","hitachi","htc","huawei","hutchison","inno","ipad","ipaq","ipod","jbrowser","kddi","kgt","kwc","lenovo","lg ","lg2","lg3","lg4","lg5","lg7","lg8","lg9","lg-","lge-","lge9","longcos","maemo","mercator","meridian","micromax","midp","mini","mitsu","mmm","mmp","mobi","mot-","moto","nec-","netfront","newgen","nexian","nf-browser","nintendo","nitro","nokia","nook","novarra","obigo","palm","panasonic","pantech","philips","phone","pg-","playstation","pocket","pt-","qc-","qtek","rover","sagem","sama","samu","sanyo","samsung","sch-","scooter","sec-","sendo","sgh-","sharp","siemens","sie-","softbank","sony","spice","sprint","spv","symbian","tablet","talkabout","tcl-","teleca","telit","tianyu","tim-","toshiba","tsm","up.browser","utec","utstar","verykool","virgin","vk-","voda","voxtel","vx","wap","wellco","wig browser","wii","windows ce","wireless","xda","xde","zte");
	        $is_mobile = false;
	        foreach ($mobile_agents as $device) {
	                if (stristr($user_agent, $device)) {
	                        $is_mobile = true;
	                        break;
	                }
	        }
		if($is_mobile){
			$this->display('index_m');
		}else{
			$this->display();	
		}		
    }
	/**********************
	         列表页
	**********************/
	public function listpano(){
		//判断是手机端还是PC端
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $mobile_agents = Array("240x320","acer","acoon","acs-","abacho","ahong","airness","alcatel","amoi","android","anywhereyougo.com","applewebkit/525","applewebkit/532","asus","audio","au-mic","avantogo","becker","benq","bilbo","bird","blackberry","blazer","bleu","cdm-","compal","coolpad","danger","dbtel","dopod","elaine","eric","etouch","fly ","fly_","fly-","go.web","goodaccess","gradiente","grundig","haier","hedy","hitachi","htc","huawei","hutchison","inno","ipad","ipaq","ipod","jbrowser","kddi","kgt","kwc","lenovo","lg ","lg2","lg3","lg4","lg5","lg7","lg8","lg9","lg-","lge-","lge9","longcos","maemo","mercator","meridian","micromax","midp","mini","mitsu","mmm","mmp","mobi","mot-","moto","nec-","netfront","newgen","nexian","nf-browser","nintendo","nitro","nokia","nook","novarra","obigo","palm","panasonic","pantech","philips","phone","pg-","playstation","pocket","pt-","qc-","qtek","rover","sagem","sama","samu","sanyo","samsung","sch-","scooter","sec-","sendo","sgh-","sharp","siemens","sie-","softbank","sony","spice","sprint","spv","symbian","tablet","talkabout","tcl-","teleca","telit","tianyu","tim-","toshiba","tsm","up.browser","utec","utstar","verykool","virgin","vk-","voda","voxtel","vx","wap","wellco","wig browser","wii","windows ce","wireless","xda","xde","zte");
	        $is_mobile = false;
	        foreach ($mobile_agents as $device) {
	                if (stristr($user_agent, $device)) {
	                        $is_mobile = true;
	                        break;
	                }
	        }
		if($is_mobile){
			import('ORG.Util.Pageyunm'); // 导入分页类
		}else{
			import('ORG.Util.Pageyun'); // 导入分页类
		}
    	$id = I('get.id');
		$channelid=I('get.channelid');
		$newpano=I('get.newpano');
		
		
		$where = "1";
		
		
		
		if(!empty($channelid))
		{
			$where .=" AND hangyeid=".$channelid;
			$wherep =" and goods.hangyeid = ".$channelid;
			$hangyename = M("yunweb_hangye")->where(array("id"=>$channelid))->getField("name");
			$hangyename =preg_replace("/([\x{4e00}-\x{9fa5}])/u", "$1　", $hangyename,1);
			
			$paixulist="<a href=\"/index.php?s=/webyun/listpano/channelid/".$channelid."/newpano/1\">最新收录</a><a href=\"/index.php?s=/webyun/listpano/channelid/".$channelid."/newpano/2\">人气</a>";
		}
		else{
			$hangyename ="全　部";
			$paixulist="<a href=\"/index.php?s=/webyun/listpano/newpano/1\">最新收录</a><a href=\"/index.php?s=/webyun/listpano/newpano/2\">人气</a>";
		}
		
		if($newpano==1){
			$datawhere = "id desc";
			$paixu="最新收录";
		}
		elseif($newpano==2){
			$paixu="人气";

		}
		else{
			$datawhere = "id desc";
			$paixu="最新收录";
			
		}
		
		
		
        if($id){
            $where .=" AND hangyeid=".$id;
        }else{
        	$where .=" AND is_recommend= 1 ";
        	$recommend="1";
        }
		
		
        $count = M("pano")->where($where)->count();
        $Page = new Page($count, 32); 
		
		if($newpano==2){ 
			$goods = M('Good');
			$panolist = $goods->table('pano_pano goods,pano_hitscount brand')->where('goods.id = brand.pano_id'.$wherep)->field('goods.*')->order('brand.hits desc ')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		}else{
			$panolist = M("pano")->where($where)->order($datawhere)->limit($Page->firstRow . ',' . $Page->listRows)->select();
		}
		
        
		
		
		
        $show = $Page->show();

		if(!empty($panolist))
		{
			foreach($panolist as $key=>$val)
			{
				$minfo = M("Member")->where(array("id"=>$val['member_id']))->find();
				if(!empty($minfo['headimg'])) $headimg = $minfo['headimg'];
				else $headimg = "/Public/member/images/common/no_img.jpg";
				$panolist[$key]['rengzheng'] = $minfo['rengzheng'];
				$panolist[$key]['vip'] = $minfo['vip'];
				$panolist[$key]['author'] = $minfo['nickname'];
				$panolist[$key]['headimg'] = $headimg;
				$panolist[$key]['panothumb'] = getPanoThumb($val['id']);
				$hits[$key] = M("hitscount")->where(array("pano_id"=>$val['id']))->getField("hits");
				$zan[$key] = M("hitscount")->where(array("pano_id"=>$val['id']))->getField("zan");
				if($hits[$key]){
					if($hits[$key]>=10000){
						$hits[$key] = ($hits[$key]/10000)."万";
					}
				}else{
					$hits[$key] =0;
				}
				if($zan[$key]){
					if($zan[$key]>=10000){
						$zan[$key] = ($zan[$key]/10000)."万";
					}
				}else{
					$zan[$key] =0;
				}
				$panolist[$key]['hits'] = $hits[$key];
				$panolist[$key]['zan']  = $zan[$key];
				$panolist[$key]['panopath'] = "/t/".$val['guid'];
			}
		}

		$channel_list = M("YunwebHangye")->order("listorder asc")->limit(0,10)->select();
		$channelnav=$_GET['id'];
		
		$lanmulist = M("yun720_nav")->where("type=2")->order("orderlist")->select();
		$lanmu = $this->getTree($lanmulist,0,0);

		$this->assign("paixu", $paixu);
		$this->assign("paixulist", $paixulist);
		$this->assign("hangyename", $hangyename);	
		$this->assign("lanmu", $lanmu);
		$this->assign("page", $show);
		$this->assign("channel_list", $channel_list);
		$this->assign("id", $id);
		$this->assign("recommend", $recommend);
        $this->assign("channelnav", $channelnav);
		$this->assign("pano_list", $panolist);
		
		//判断是手机端还是PC端
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $mobile_agents = Array("240x320","acer","acoon","acs-","abacho","ahong","airness","alcatel","amoi","android","anywhereyougo.com","applewebkit/525","applewebkit/532","asus","audio","au-mic","avantogo","becker","benq","bilbo","bird","blackberry","blazer","bleu","cdm-","compal","coolpad","danger","dbtel","dopod","elaine","eric","etouch","fly ","fly_","fly-","go.web","goodaccess","gradiente","grundig","haier","hedy","hitachi","htc","huawei","hutchison","inno","ipad","ipaq","ipod","jbrowser","kddi","kgt","kwc","lenovo","lg ","lg2","lg3","lg4","lg5","lg7","lg8","lg9","lg-","lge-","lge9","longcos","maemo","mercator","meridian","micromax","midp","mini","mitsu","mmm","mmp","mobi","mot-","moto","nec-","netfront","newgen","nexian","nf-browser","nintendo","nitro","nokia","nook","novarra","obigo","palm","panasonic","pantech","philips","phone","pg-","playstation","pocket","pt-","qc-","qtek","rover","sagem","sama","samu","sanyo","samsung","sch-","scooter","sec-","sendo","sgh-","sharp","siemens","sie-","softbank","sony","spice","sprint","spv","symbian","tablet","talkabout","tcl-","teleca","telit","tianyu","tim-","toshiba","tsm","up.browser","utec","utstar","verykool","virgin","vk-","voda","voxtel","vx","wap","wellco","wig browser","wii","windows ce","wireless","xda","xde","zte");
	        $is_mobile = false;
	        foreach ($mobile_agents as $device) {
	                if (stristr($user_agent, $device)) {
	                        $is_mobile = true;
	                        break;
	                }
	        }
		if($is_mobile){
			$this->display('listpano_m');
		}else{
			$this->display();	
		}		
	}
	/**********************
	         作者列表页
	**********************/	
	 public function author(){
		$filter = I('get.filter') ? intval(I('get.filter')) : 1 ;
		$this->assign("filter",$filter);
		$order = "id desc";
		$where = "status=1";
		$Model = M("Member");
		import('ORG.Util.Pageyun'); // 导入分页类
			

		if($filter==1)
		{
			$goods = M('Good');
			 $countold = $goods->table('pano_member goods,pano_hitscount brand')
								 ->where('goods.id = brand.member_id and STATUS =1')
								 ->field('goods.*,brand.member_id')
								 ->group('goods.id having COUNT( brand.pano_id)>2')
								 ->order('goods.id desc ')->buildSql();
			 $count=$goods->table($countold.' a')->count();		
			 
			$Page = new Page($count, 15); 
			$authorlist = $goods->table('pano_member goods,pano_hitscount brand')
								 ->where('goods.id = brand.member_id and STATUS =1')
								 ->field('goods.*,brand.member_id')
								 ->group('goods.id having COUNT( brand.pano_id)>2')
								 ->order('goods.id desc ')
								 ->limit($Page->firstRow . ',' . $Page->listRows)->select();
			
			$paixu="新晋作者";
			
		}
		if($filter==2)
		{	
			 $goods = M('Good');
			 $countold = $goods->table('pano_member goods,pano_hitscount brand')
								 ->where('goods.id = brand.member_id and STATUS =1')
								 ->field('goods.*,brand.member_id, SUM( brand.hits ) as hits')
								 ->group('goods.id having COUNT( brand.pano_id)>2')
								 ->order('hits desc ')->buildSql();
			 $count=$goods->table($countold.' a')->count();		
			 
			$Page = new Page($count, 15); 
			$authorlist = $goods->table('pano_member goods,pano_hitscount brand')
								 ->where('goods.id = brand.member_id and STATUS =1')
								 ->field('goods.*,brand.member_id, SUM( brand.hits ) as hits')
								 ->group('goods.id having COUNT( brand.pano_id)>2')
								 ->order('hits desc ')
								 ->limit($Page->firstRow . ',' . $Page->listRows)->select();
			
			$paixu="人气";
		}
		$paixulist="<a href=\"/index.php?s=/webyun/author/filter/1\">新晋作者</a><a href=\"/index.php?s=/webyun/author/filter/2\">人气</a>";
		
		if($filter==4)
		{
			$authorlist = $Model->where($where)->order("id desc")->select();
		}

		//$count = $Model->where($where)->count();
        
        $show = $Page->show();

		if(!empty($authorlist))
		{
			foreach($authorlist as $key=>$val)
			{
				if(!empty($val['headimg'])) $headimg = $val['headimg'];
				else $headimg = "/Public/member/images/common/no_img.jpg";
				$authorlist[$key]['headimg'] = $headimg;
				$authorlist[$key]['rengzheng'] = $val['rengzheng'];
				$authorlist[$key]['vip'] = $val['vip'];
				$authorlist[$key]['zuopin']  = M("pano")->where("member_id='".$val['id']."'")->count();
				$renqi_list= M("hitscount")->where("member_id='".$val['id']."'")->select();

				$renqi_res = 0;
				
				$zhuopnew="";
				foreach ($renqi_list as $keyt => $value) {
						$renqi_res += $value['hits'];
				}
				$authorlist[$key]['renqi'] = intval($renqi_res);
				

				if($authorlist[$key]['renqi']){
					if($authorlist[$key]['renqi']>=10000){
						$authorlist[$key]['renqi'] = ($authorlist[$key]['renqi']/10000)."万";
					}
				}else{
					$hits[$key] =0;
				}
				$authorlist[$key]['renqi'] = $authorlist[$key]['renqi'];
				
				$sheng = M("Yun720_city")->where("id=".$val['province'])->getField("name");
				$shi = M("Yun720_city")->where("id=".$val['city'])->getField("name");
				$qu  = M("Yun720_city")->where("id=".$val['area'])->getField("name");
		
				if($sheng!=null){
					$sheng=$sheng;
					} else{
						$sheng="";
					}
					if($shi!=null){
						$shi=",".$shi;
					} else{
						$shi="";
					}
					if($qu!=null){
						$qu=",".$qu;
					} else{
						$qu="";
					}
		
					$authorlist[$key]["dizhi"]=$sheng.$shi.$qu;
		
				
				$zpListrow = M("Hitscount")->where("member_id='".$val['id']."'")->order('zan desc')->limit(0,3)->select(); 
				foreach($zpListrow as $zpkey=>$zpval)
					{
					$panorow = M("Pano")->where("id='".$zpval['pano_id']."'")->find();
					$zpListrow[$zpkey]['panothumb'] = getPanoThumb($panorow['id']);
					$zpListrow[$zpkey]['panopath'] = "/t/".$panorow['guid'];
					$zpListrow[$zpkey]['id'] = $panorow['id']."<br>";
					$zhuopxh=$zpListrow[$zpkey]['id'];
					$zhuopxh= "<div class='Author_cell_3A7i5I'><a href='".$zpListrow[$zpkey]['panopath']."' target='_blank' style='width: 110px; height: 110px; background-image: url(&quot;".$zpListrow[$zpkey]['panothumb']."&quot;);' class='ResPanoImg_proimg_1ydNS8'></a></div>";
					$zhuopnew .= $zhuopxh;

					}
				$authorlist[$key]["zhuop"] = $zhuopnew;	
			}
		}
		/*echo "<pre>";
		var_dump($authorlist);die;*/

		$lanmulist = M("yun720_nav")->where("type=2")->order("orderlist")->select();
		$lanmu = $this->getTree($lanmulist,0,0);

		$this->assign("paixu", $paixu);
		$this->assign("paixulist", $paixulist);
		$this->assign("lanmu", $lanmu);
		$this->assign("page", $show);
		$this->assign("authorlist",$authorlist);
		$this->assign("authornav",1);
		//判断是手机端还是PC端
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $mobile_agents = Array("240x320","acer","acoon","acs-","abacho","ahong","airness","alcatel","amoi","android","anywhereyougo.com","applewebkit/525","applewebkit/532","asus","audio","au-mic","avantogo","becker","benq","bilbo","bird","blackberry","blazer","bleu","cdm-","compal","coolpad","danger","dbtel","dopod","elaine","eric","etouch","fly ","fly_","fly-","go.web","goodaccess","gradiente","grundig","haier","hedy","hitachi","htc","huawei","hutchison","inno","ipad","ipaq","ipod","jbrowser","kddi","kgt","kwc","lenovo","lg ","lg2","lg3","lg4","lg5","lg7","lg8","lg9","lg-","lge-","lge9","longcos","maemo","mercator","meridian","micromax","midp","mini","mitsu","mmm","mmp","mobi","mot-","moto","nec-","netfront","newgen","nexian","nf-browser","nintendo","nitro","nokia","nook","novarra","obigo","palm","panasonic","pantech","philips","phone","pg-","playstation","pocket","pt-","qc-","qtek","rover","sagem","sama","samu","sanyo","samsung","sch-","scooter","sec-","sendo","sgh-","sharp","siemens","sie-","softbank","sony","spice","sprint","spv","symbian","tablet","talkabout","tcl-","teleca","telit","tianyu","tim-","toshiba","tsm","up.browser","utec","utstar","verykool","virgin","vk-","voda","voxtel","vx","wap","wellco","wig browser","wii","windows ce","wireless","xda","xde","zte");
	        $is_mobile = false;
	        foreach ($mobile_agents as $device) {
	                if (stristr($user_agent, $device)) {
	                        $is_mobile = true;
	                        break;
	                }
	        }
		if($is_mobile){
			$this->display('author_m');
		}else{
			$this->display();	
		}
    }	
	
	/**********************
	         作者主页
	**********************/	
	public   function authorlist(){
        cookie("back", __SELF__);
		//判断是手机端还是PC端
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $mobile_agents = Array("240x320","acer","acoon","acs-","abacho","ahong","airness","alcatel","amoi","android","anywhereyougo.com","applewebkit/525","applewebkit/532","asus","audio","au-mic","avantogo","becker","benq","bilbo","bird","blackberry","blazer","bleu","cdm-","compal","coolpad","danger","dbtel","dopod","elaine","eric","etouch","fly ","fly_","fly-","go.web","goodaccess","gradiente","grundig","haier","hedy","hitachi","htc","huawei","hutchison","inno","ipad","ipaq","ipod","jbrowser","kddi","kgt","kwc","lenovo","lg ","lg2","lg3","lg4","lg5","lg7","lg8","lg9","lg-","lge-","lge9","longcos","maemo","mercator","meridian","micromax","midp","mini","mitsu","mmm","mmp","mobi","mot-","moto","nec-","netfront","newgen","nexian","nf-browser","nintendo","nitro","nokia","nook","novarra","obigo","palm","panasonic","pantech","philips","phone","pg-","playstation","pocket","pt-","qc-","qtek","rover","sagem","sama","samu","sanyo","samsung","sch-","scooter","sec-","sendo","sgh-","sharp","siemens","sie-","softbank","sony","spice","sprint","spv","symbian","tablet","talkabout","tcl-","teleca","telit","tianyu","tim-","toshiba","tsm","up.browser","utec","utstar","verykool","virgin","vk-","voda","voxtel","vx","wap","wellco","wig browser","wii","windows ce","wireless","xda","xde","zte");
	        $is_mobile = false;
	        foreach ($mobile_agents as $device) {
	                if (stristr($user_agent, $device)) {
	                        $is_mobile = true;
	                        break;
	                }
	        }
		if($is_mobile){
			import('ORG.Util.Pageyunm'); // 导入分页类
		}else{
			import('ORG.Util.Pageyun'); // 导入分页类
		}

        $mm = M("Pano");
        $id = I("get.id");//分类
		$zuopin = I("get.zuopin");

        $where = array(
        	"member_id" => $id,
			"is_recommend" => 1
        	);
			
			
		//统计总数人气 
		$hiscount=0;
		$thiscount = M("hitscount")->where($where)->sum("hits");
		if($thiscount){
				if($thiscount>=10000){
					$thiscount = ($thiscount/10000)."万";
				}
			}else{
				$hits[$key] =0;
			}
		$hiscount = $thiscount;
		
		
		if($zuopin==1){  
			$paixuwhere = "id desc";
			$paixuname = "最新作品";
		}
		elseif($zuopin==2){
			$paixuwhere = "hits desc";
			$paixuname = "最热作品";
		}
		else{
			$paixuwhere = "id desc";
			$paixuname ="最新作品";
		}

		
		
		$paixulist="<a href=\"/index.php?s=/webyun/authorlist/id/".$id."/zuopin/1\">最新作品</a><a href=\"/index.php?s=/webyun/authorlist/id/".$id."/zuopin/2\">最热作品</a>";
								
								
								
        $count = $mm->where($where)->count(); // 查询满足要求的总记录数
      	$Page = new Page($count, 8); // 实例化分页类 传入总记录数和每页显示的记录数
        // 进行分页数据查询 注意limit方法的参数要使用Page类的属性
		
		
       	
		

		if($zuopin==2){ 
			$goods = M('Good');
			$list = $goods->table('pano_pano goods,pano_hitscount brand')->where('goods.id = brand.pano_id and goods.member_id = '.$id)->field('goods.*')->order('brand.hits desc ')->limit($Page->firstRow . ',' . $Page->listRows)->select();
		}else{
			$list = $mm->where($where)->order($paixuwhere)->limit($Page->firstRow . ',' . $Page->listRows)->select();
		}
			
			
			

        $show = $Page->show(); // 分页显示输出

        
        foreach ($list as $k=>$li) {
            $list[$k]['panopath'] = "/t/".$li['guid'];
            $list[$k]['panothumb'] = getPanoThumb($li['id']);
       		
       		$hits[$key] = M("hitscount")->where(array("pano_id"=>$li['id']))->getField("hits");
			$zan[$key] = M("hitscount")->where(array("pano_id"=>$li['id']))->getField("zan");

			if($hits[$key]){
				if($hits[$key]>=10000){
					$hits[$key] = ($hits[$key]/10000)."万";
				}
			}else{
				$hits[$key] =0;
			}
			if($zan[$key]){
				if($zan[$key]>=10000){
					$zan[$key] = ($zan[$key]/10000)."万";
				}
			}else{
				$zan[$key] =0;
			}

			$list[$k]['zan']=$hits[$key];
			$list[$k]['hits']=$zan[$key];

       		
        }

        $photocount =  M("pano")->where($where)->count();

        //根据ID选择出会员信息
         $u_where = array(
        	"id" => $id
        	);
        $userinfo = M("Member")->where($u_where)->find();
		$sheng = M("Yun720_city")->where("id=".$userinfo['province'])->getField("name");
		
		$shi = M("Yun720_city")->where("id=".$userinfo['city'])->getField("name");
		$qu  = M("Yun720_city")->where("id=".$userinfo['area'])->getField("name");
		
		if($sheng!=null){
			$sheng=$sheng;
		} else{
			$sheng="";
		}
		if($shi!=null){
			$shi=" ".$shi;
		} else{
			$shi="";
		}
		if($qu!=null){
			$qu=" ".$qu;
		} else{
			$qu="";
		}
		
		$dizhi=$sheng.$shi.$qu;
		
		$lanmulist = M("yun720_nav")->where("type=2")->order("orderlist")->select();
		$lanmu = $this->getTree($lanmulist,0,0);
		
	
		$this->assign('dizhi', $dizhi);
		$this->assign('lanmu', $lanmu);
		$this->assign('paixuname', $paixuname);
		$this->assign('paixulist', $paixulist);
        $this->assign('photocount', $photocount);
        $this->assign('hiscount', $hiscount);
        $this->assign('userinfo', $userinfo); 
		$this->assign('group', $group); // 赋值数据集
        $this->assign('list', $list); // 赋值数据集
        $this->assign('page', $show); // 赋值分页输出
        //判断是手机端还是PC端
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $mobile_agents = Array("240x320","acer","acoon","acs-","abacho","ahong","airness","alcatel","amoi","android","anywhereyougo.com","applewebkit/525","applewebkit/532","asus","audio","au-mic","avantogo","becker","benq","bilbo","bird","blackberry","blazer","bleu","cdm-","compal","coolpad","danger","dbtel","dopod","elaine","eric","etouch","fly ","fly_","fly-","go.web","goodaccess","gradiente","grundig","haier","hedy","hitachi","htc","huawei","hutchison","inno","ipad","ipaq","ipod","jbrowser","kddi","kgt","kwc","lenovo","lg ","lg2","lg3","lg4","lg5","lg7","lg8","lg9","lg-","lge-","lge9","longcos","maemo","mercator","meridian","micromax","midp","mini","mitsu","mmm","mmp","mobi","mot-","moto","nec-","netfront","newgen","nexian","nf-browser","nintendo","nitro","nokia","nook","novarra","obigo","palm","panasonic","pantech","philips","phone","pg-","playstation","pocket","pt-","qc-","qtek","rover","sagem","sama","samu","sanyo","samsung","sch-","scooter","sec-","sendo","sgh-","sharp","siemens","sie-","softbank","sony","spice","sprint","spv","symbian","tablet","talkabout","tcl-","teleca","telit","tianyu","tim-","toshiba","tsm","up.browser","utec","utstar","verykool","virgin","vk-","voda","voxtel","vx","wap","wellco","wig browser","wii","windows ce","wireless","xda","xde","zte");
	        $is_mobile = false;
	        foreach ($mobile_agents as $device) {
	                if (stristr($user_agent, $device)) {
	                        $is_mobile = true;
	                        break;
	                }
	        }
		if($is_mobile){
			
			$this->display('authorlist_m');
		}else{
			$this->display();	
		}
    }
	
	
	
	/**********************
	         文章主页
	**********************/		
	public function indexarticle(){
		
			$hot = getYunArticle(2);
			$lanmu = getYunLanmu(0,4);
			$articlead = getYunAd(5,2);//首页顶部广告
			$new = M("yun720_article")->where(" status='1' ")->order("id desc")->limit(15)->select();
			if(!empty($new)){
				foreach($new as $key=>$val)
				{
					$new[$key]['ariurl']= "/index.php?s=/Webyun/content/id/".$val['id'];
					
					$minfo = M("Member")->where(array("id"=>$val['userid']))->find();
					if(!empty($minfo['headimg'])) $headimg = $minfo['headimg'];
					else $headimg = "/Public/member/images/common/no_img.jpg";
					$new[$key]['headimg'] = $headimg;
				}
			}
						
			$this->assign("hot",$hot);		  	
			$this->assign("lanmu",$lanmu);
			$this->assign("new",$new);
			$this->assign("articlead",$articlead);	
         
			$this->display();
    }
	
	
	/**********************
	         文章列表页
	**********************/		

    public function articlelist(){ 
		import('ORG.Util.Pagearticle'); // 导入分页类
        $id = $_REQUEST['lanmuid'];
		$sort = I('get.sort');
		
		

		if($sort==2){
			$sortnew=" and is_recommend=1";
			$paixu="<li ><a href=\"/index.php?s=/Webyun/articlelist/lanmuid/".$id."/sort/1\">新帖</a></li>
		<li class=\"on\"><a href=\"/index.php?s=/Webyun/articlelist/lanmuid/".$id."/sort/2\">精华</a></li>
		<li ><a href=\"/index.php?s=/Webyun/articlelist/lanmuid/".$id."/sort/3\">查看数</a></li>";

		}
		elseif($sort==3){
			
			$datawhere=" hits desc";
			$paixu="<li ><a href=\"/index.php?s=/Webyun/articlelist/lanmuid/".$id."/sort/1\">新帖</a></li>
		<li ><a href=\"/index.php?s=/Webyun/articlelist/lanmuid/".$id."/sort/2\">精华</a></li>
		<li class=\"on\"><a href=\"/index.php?s=/Webyun/articlelist/lanmuid/".$id."/sort/3\">查看数</a></li>";
		}
		else{
			$datawhere = "id desc";
			$paixu="<li class=\"on\"><a href=\"/index.php?s=/Webyun/articlelist/lanmuid/".$id."/sort/1\">新帖</a></li>
		<li ><a href=\"/index.php?s=/Webyun/articlelist/lanmuid/".$id."/sort/2\">精华</a></li>
		<li ><a href=\"/index.php?s=/Webyun/articlelist/lanmuid/".$id."/sort/3\">查看数</a></li>";
			
		}
		

		
		
		
		
		
		
		
		
		//根据栏目ID选择栏目内容		
		$chapid = M("yun720_lanmu")->where("id='".$id."'")->find();	
		$lanmuwhere = array("lanmuid" =>$id);
		if(!$chapid['pid']){
			$chaid = M("yun720_lanmu")->where("pid='".$id."'")->order("listorder")->select();
            foreach($chaid as $key=>$val)
					{
					$chaidold= ",".$val['id']."";
					$chaidnew .= $chaidold;
										
					$chaidlanmu = "<li ><a title='".$val['name']."' href='/index.php?s=/Webyun/articlelist/lanmuid/".$val['id']."'>".$val['name']."</a></li>";
					$chaidlanmunew .= $chaidlanmu;		
					}
					$lanmudh="<li id='ttp_all' class='on'><a title='全部标签' href='/index.php?s=/Webyun/articlelist/lanmuid/".$chapid['id']."'>全部标签</a></li>".$chaidlanmunew;

		}
		else{
			$chaidone = M("yun720_lanmu")->where("id='".$id."'")->find();
			$chaidtwo = M("yun720_lanmu")->where("id='".$chaidone['pid']."'")->find();
			$chaid = M("yun720_lanmu")->where("pid='".$chaidtwo['id']."'")->order("listorder")->select();
            foreach($chaid as $key=>$val)
					{	
  		 	
					if($id==$val['id']){
					$chaidlanmu = "<li id='ttp_all' class='on'><a title='".$val['name']."' href='/index.php?s=/Webyun/articlelist/lanmuid/".$val['id']."'>".$val['name']."</a></li>";
					
					}else{
						$chaidlanmu = "<li ><a title='".$val['name']."' href='/index.php?s=/Webyun/articlelist/lanmuid/".$val['id']."'>".$val['name']."</a></li>";
					}
					$chaidlanmunew .= $chaidlanmu;
					}
					$lanmudh="<li ><a title='全部标签' href='/index.php?s=/Webyun/articlelist/lanmuid/".$chaidtwo['id']."'>全部标签</a></li>".$chaidlanmunew;
		}
		
        $count = M("yun720_article")->where("lanmuid in (".$id.$chaidnew.")".$sortnew)->count();
        $Page = new Page($count, 10); 
		
		$positionnew = M("yun720_lanmu")->where("id=$id")->find();
		$position="<em>›</em><a href=\"/index.php?s=/Webyun/articlelist/lanmuid/".$positionnew['id']."\">".$positionnew['name']."</a>";

        $arclist = M("yun720_article")->where("lanmuid in (".$id.$chaidnew.")".$sortnew)->order($datawhere)->limit($Page->firstRow . ',' . $Page->listRows)->select();
		
		
		$hiscount=0;
			$thiscount = M("yun720_article")->where("lanmuid in (".$id.$chaidnew.")".$sortnew)->sum("hits");
			if($thiscount){
				if($thiscount>=10000){
					$thiscount = ($thiscount/10000)."万";
				}
			}else{
				$hits[$key] =0;
			}
			$hiscount = $thiscount;
			if($hiscount==null){
				$hiscount=0;
			}
			else{
				$hiscount=$hiscount;
			}
			
		$Dao = M("paixudb");// 实例化一个model对象 没有对应任何数据表
			$paixuold=$Dao->query("select aid from (SELECT * , CASE WHEN @shits = a.shits THEN @id WHEN @shits := a.shits THEN @id := @id +1 END AS aid FROM ( SELECT * , SUM( hits ) AS shits FROM pano_yun720_article GROUP BY lanmuid ORDER BY SUM( hits ) DESC )a, ( SELECT @shits :=0, @id :=0 )r) x where  x.lanmuid in (".$id.")".$sortnew."");
			$paixunew= $paixuold[0][aid];
			if($paixunew==null){
				$paixunew="未计排名";
			}
			else{
				$paixunew=$paixunew;
			}
		//echo M('paixudb')->_sql();
		//exit();
        $show = $Page->show();

		if(!empty($arclist))
		{
			foreach($arclist as $key=>$val)
			{
				$minfo = M("Member")->where(array("id"=>$val['userid']))->find();

				if(!empty($minfo['headimg'])) $headimg = $minfo['headimg'];
				else $headimg = "/Public/member/images/common/no_img.jpg";
			
				$lanmucha = M("yun720_lanmu")->where(array("id"=>$val['lanmuid']))->find();
				if($lanmucha['pid']){
				 $arclist[$key]['lanmucha'] = "<em>[<a href='/index.php?s=/Webyun/articlelist/lanmuid/".$lanmucha['id']."'>".$lanmucha['name']."</a>]</em>";	
				}

				$arclist[$key]['author'] = $minfo['nickname'];
				$arclist[$key]['headimg'] = $headimg;

			}

		}
		

		
		if($chapid['pid']==0){
			$lanmu=$chapid['name'];
		}else{
			$lanmuid=M("yun720_lanmu")->where("id='".$chapid['pid']."'")->find();	
			$lanmu=$lanmuid['name'];
		}
		
		$hot = getYunlistArticle(1);
		$articlead = getYunAd(5,2);//首页顶部广告
		
		$this->assign("paixu",$paixu);
		$this->assign("position",$position);	
		$this->assign("hot",$hot);	
		$this->assign("page", $show);	
		$this->assign("lanmudh", $lanmudh);
		$this->assign("lanmu", $lanmu);
		$this->assign("arclist", $arclist);
		$this->assign("count", $count);
		$this->assign("paixunew", $paixunew);
		$this->assign("hiscount", $hiscount);
		$this->assign("articlead", $articlead);
		$this->display();
	}
	
	
	/**********************
	         文章内容页
	**********************/		

    public function content(){
		
          	$id = $_REQUEST['id'];
          	$where=array(
          			'id'=>$id,
          		);
          	$info = M('yun720_article')->where($where)->find();
			$tiezicount = M("yun720_article")->where("username='".$info['username']."'")->count();
			$xiangguan_article = $this->get_xiangguan_article($info['username'],6);
			$lanmu = getYunLanmu(0,4);
			$minfo = M("Member")->where(array("id"=>$info['userid']))->find();
			
			$hiscount=0;
			$thiscount = M("yun720_article")->where(array("userid"=>$info['userid']))->sum("hits");
			if($thiscount){
				if($thiscount>=10000){
					$thiscount = ($thiscount/10000)."万";
				}
			}else{
				$hits[$key] =0;
			}
			$hiscount = $thiscount;
			
			
			
            $ad = getWebPosAd(17);

            
            if($info){
              $lanmuid=$info['lanmuid'];
            }else{
               $lanmuid="";
            }
            //根据栏目ID选择栏目名称
            if($lanmuid){
            	$lanmu_name = M("yun720_lanmu")->where("id=$lanmuid")->getfield('name');
            }
			
            $positionold = M("yun720_lanmu")->where("id=$lanmuid")->getfield('pid');
			$positionnew = M("yun720_lanmu")->where("id=$positionold")->find();
			$position="<em>›</em><a href=\"/index.php?s=/Webyun/articlelist/lanmuid/".$positionnew['id']."\">".$positionnew['name']."</a>";
			

			$Dao = M("paixudb");// 实例化一个model对象 没有对应任何数据表
			$paixuold=$Dao->query("select aid from (SELECT * , CASE WHEN @shits = a.shits THEN @id WHEN @shits := a.shits THEN @id := @id +1 END AS aid FROM ( SELECT * , SUM( hits ) AS shits FROM pano_yun720_article GROUP BY userid ORDER BY SUM( hits ) DESC )a, ( SELECT @shits :=0, @id :=0 )r) x where x.userid=".$info['userid']."");
			$paixu= $paixuold[0][aid];


			
			
			
			
            $this->assign("zxpano",$zxpano); 
        	$this->assign("tuijian_article",$tuijian_article);
        	
        	$this->assign("left_article",$left_article);
        	$this->assign("redian_article",$redian_article);
            $this->assign("banner",$banner);  
            
            $this->assign("ad",$ad); 
            
			
			$this->assign("position",$position);
			$this->assign("info",$info);
			$this->assign("minfo",$minfo);
			$this->assign("lanmuid",$lanmuid);
			$this->assign("lanmu_name",$lanmu_name);
			$this->assign("tiezicount",$tiezicount); 
			$this->assign("hiscount",$hiscount);
			$this->assign("paixu",$paixu); 
			$this->assign("xiangguan_article",$xiangguan_article);
			$this->assign("lanmu",$lanmu); 
          	
          	$this->display();
    }
	/**********************
	         搜索页
	**********************/		

    public function search(){
		//判断是手机端还是PC端
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $mobile_agents = Array("240x320","acer","acoon","acs-","abacho","ahong","airness","alcatel","amoi","android","anywhereyougo.com","applewebkit/525","applewebkit/532","asus","audio","au-mic","avantogo","becker","benq","bilbo","bird","blackberry","blazer","bleu","cdm-","compal","coolpad","danger","dbtel","dopod","elaine","eric","etouch","fly ","fly_","fly-","go.web","goodaccess","gradiente","grundig","haier","hedy","hitachi","htc","huawei","hutchison","inno","ipad","ipaq","ipod","jbrowser","kddi","kgt","kwc","lenovo","lg ","lg2","lg3","lg4","lg5","lg7","lg8","lg9","lg-","lge-","lge9","longcos","maemo","mercator","meridian","micromax","midp","mini","mitsu","mmm","mmp","mobi","mot-","moto","nec-","netfront","newgen","nexian","nf-browser","nintendo","nitro","nokia","nook","novarra","obigo","palm","panasonic","pantech","philips","phone","pg-","playstation","pocket","pt-","qc-","qtek","rover","sagem","sama","samu","sanyo","samsung","sch-","scooter","sec-","sendo","sgh-","sharp","siemens","sie-","softbank","sony","spice","sprint","spv","symbian","tablet","talkabout","tcl-","teleca","telit","tianyu","tim-","toshiba","tsm","up.browser","utec","utstar","verykool","virgin","vk-","voda","voxtel","vx","wap","wellco","wig browser","wii","windows ce","wireless","xda","xde","zte");
	        $is_mobile = false;
	        foreach ($mobile_agents as $device) {
	                if (stristr($user_agent, $device)) {
	                        $is_mobile = true;
	                        break;
	                }
	        }
		if($is_mobile){
			import('ORG.Util.Pageyunm'); // 导入分页类
		}else{
			import('ORG.Util.Pageyun'); // 导入分页类
		}
		
          	$keywords=$_GET['keywords'];
            
            if($keywords){
                $count = M("pano")->where("title like '%$keywords%' and is_recommend='1' ")->count();
				$Page = new Page($count, 8);
                $pos_pano = M("pano")->where( " title like '%$keywords%' and is_recommend='1' " )->order("id desc")->limit($Page->firstRow . ',' . $Page->listRows)->select();
                $show = $Page->show();

                foreach($pos_pano as $key=>$val)
                {
                  $pos_pano[$key]['member_id'] = M("Pano")->where(array("id"=>$val['id']))->getField("member_id");
                  $pos_pano[$key]['title'] = M("Pano")->where(array("id"=>$val['id']))->getField("title");
				  $pos_pano[$key]['wxdesc'] = M("Pano")->where(array("id"=>$val['id']))->getField("wxdesc");
				  
				  $pos_pano[$key]['guid'] = M("Pano")->where(array("id"=>$val['id']))->getField("guid");
				  $pos_pano[$key]['panopath'] = "/t/".$pos_pano[$key]['guid'];
				  
				  
				  //频道格式化
					$hangyeold= M("yunweb_hangye")->where(array("id"=>$val['hangyeid']))->getField("name");
					$pos_pano[$key]['hangye'] =preg_replace("/([\x{4e00}-\x{9fa5}])/u", "$1 ", $hangyeold,1);
				 //作者主页格式化
				  
                  $pos_pano[$key]['author'] = M("Member")->where(array("id"=>$pos_pano[$key]['member_id']))->getField("nickname");
                 
                  $pano_view[$key] = M("pano_view")->where(array("pano_id"=>$val['id']))->find();
                  if(!empty($pano_view[$key]['thumb'])){
                    $thumb[$key] = $pano_view[$key]['thumb'];
                  }else{
                    $thumb[$key] = "/Public/member/images/pano/pano.jpg";
                  }
                  $pos_pano[$key]['thumb'] = $thumb[$key];

                  
              }
            }

            $panolist = $pos_pano;
			
			
			$lanmulist = M("yun720_nav")->where("type=2")->order("orderlist")->select();
			$lanmu = $this->getTree($lanmulist,0,0);
			
			
			$this->assign("lanmu",$lanmu);
		
            $this->assign("count",$count);
            $this->assign("page",$show);
            $this->assign("keywords",$keywords);
            $this->assign("panolist",$panolist);
			
			//判断是手机端还是PC端
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $mobile_agents = Array("240x320","acer","acoon","acs-","abacho","ahong","airness","alcatel","amoi","android","anywhereyougo.com","applewebkit/525","applewebkit/532","asus","audio","au-mic","avantogo","becker","benq","bilbo","bird","blackberry","blazer","bleu","cdm-","compal","coolpad","danger","dbtel","dopod","elaine","eric","etouch","fly ","fly_","fly-","go.web","goodaccess","gradiente","grundig","haier","hedy","hitachi","htc","huawei","hutchison","inno","ipad","ipaq","ipod","jbrowser","kddi","kgt","kwc","lenovo","lg ","lg2","lg3","lg4","lg5","lg7","lg8","lg9","lg-","lge-","lge9","longcos","maemo","mercator","meridian","micromax","midp","mini","mitsu","mmm","mmp","mobi","mot-","moto","nec-","netfront","newgen","nexian","nf-browser","nintendo","nitro","nokia","nook","novarra","obigo","palm","panasonic","pantech","philips","phone","pg-","playstation","pocket","pt-","qc-","qtek","rover","sagem","sama","samu","sanyo","samsung","sch-","scooter","sec-","sendo","sgh-","sharp","siemens","sie-","softbank","sony","spice","sprint","spv","symbian","tablet","talkabout","tcl-","teleca","telit","tianyu","tim-","toshiba","tsm","up.browser","utec","utstar","verykool","virgin","vk-","voda","voxtel","vx","wap","wellco","wig browser","wii","windows ce","wireless","xda","xde","zte");
	        $is_mobile = false;
	        foreach ($mobile_agents as $device) {
	                if (stristr($user_agent, $device)) {
	                        $is_mobile = true;
	                        break;
	                }
	        }
		if($is_mobile){
			
			$this->display('search_m');
		}else{
			$this->display('search');
		}
            
    }
	/**********************
	         文章搜索页
	**********************/		

    public function articlesearch(){ 
        $keywords=$_GET['keywords'];
		import('ORG.Util.Pagearticle'); // 导入分页类

            
            if($keywords){
                $count = M("yun720_article")->where("title like '%$keywords%' ")->count();
				$Page = new Page($count, 10);
                $arclist = M("yun720_article")->where( " title like '%$keywords%' " )->order("id desc")->limit($Page->firstRow . ',' . $Page->listRows)->select();
                $show = $Page->show();
			if(!empty($arclist))
			{
			foreach($arclist as $key=>$val)
			{
				$minfo = M("Member")->where(array("id"=>$val['userid']))->find();

				if(!empty($minfo['headimg'])) $headimg = $minfo['headimg'];
				else $headimg = "/Public/member/images/common/no_img.jpg";
			
				$lanmucha = M("yun720_lanmu")->where(array("id"=>$val['lanmuid']))->find();
				if($lanmucha['pid']){
				 $arclist[$key]['lanmucha'] = "<em>[<a href='/index.php?s=/Webyun/articlelist/lanmuid/".$lanmucha['id']."'>".$lanmucha['name']."</a>]</em>";	
				}

				$arclist[$key]['author'] = $minfo['nickname'];
				$arclist[$key]['headimg'] = $headimg;

			}

			}
		}
		
		$hot = getYunlistArticle(1);
		$articlead = getYunAd(5,2);//首页顶部广告
		
		$this->assign("position",$position);	
		$this->assign("page", $show);	
		$this->assign("lanmudh", $lanmudh);
		$this->assign("arclist", $arclist);
		$this->assign("count", $count);
		$this->assign("keywords",$keywords);
		$this->assign("paixunew", $paixunew);
		$this->assign("hiscount", $hiscount);
		$this->assign("articlead", $articlead);
		$this->display();
	}
	
	/**********************
	         申请认证
	*****************/
    public function goauth(){
    
		$banner = getYunAd(1,5);//首页banner
		
		$lanmulist = M("yun720_nav")->where("type=2")->order("orderlist")->select();
		$lanmu = $this->getTree($lanmulist,0,0);
		
		$bannerad = getYunAd(2,5);//首页顶部广告
		$bannerm = getYunAd(6,5);//手机首页顶部广告
		$hot_pano = getReadPano(0,12);//从1开始的12条数据  推荐
		$hengtiaoad = getYunAd(3,1);//首页横条广告
		$articlefirst = getYunAd(4,1);//首页文章精选广告1
		$articletwo = getYunarticleAd(4,1,4);//首页文章精选广告4
		$author_member = getZhuozhevipPano(0,1);//显示一个认证作者信息
		$sheys_member = getSheysPano(0,6);//显示6个摄影师信息

		$this->assign("lanmu",$lanmu);
		$this->assign("banner",$banner);
		$this->assign("bannerm",$bannerm);
		$this->assign("bannerad",$bannerad);
		$this->assign("hot_pano", $hot_pano);
		$this->assign("hengtiaoad",$hengtiaoad);
		$this->assign("articlefirst",$articlefirst);
		$this->assign("articletwo",$articletwo);
		$this->assign("author_member", $author_member);
		$this->assign("sheys_member", $sheys_member);

		//判断是手机端还是PC端
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $mobile_agents = Array("240x320","acer","acoon","acs-","abacho","ahong","airness","alcatel","amoi","android","anywhereyougo.com","applewebkit/525","applewebkit/532","asus","audio","au-mic","avantogo","becker","benq","bilbo","bird","blackberry","blazer","bleu","cdm-","compal","coolpad","danger","dbtel","dopod","elaine","eric","etouch","fly ","fly_","fly-","go.web","goodaccess","gradiente","grundig","haier","hedy","hitachi","htc","huawei","hutchison","inno","ipad","ipaq","ipod","jbrowser","kddi","kgt","kwc","lenovo","lg ","lg2","lg3","lg4","lg5","lg7","lg8","lg9","lg-","lge-","lge9","longcos","maemo","mercator","meridian","micromax","midp","mini","mitsu","mmm","mmp","mobi","mot-","moto","nec-","netfront","newgen","nexian","nf-browser","nintendo","nitro","nokia","nook","novarra","obigo","palm","panasonic","pantech","philips","phone","pg-","playstation","pocket","pt-","qc-","qtek","rover","sagem","sama","samu","sanyo","samsung","sch-","scooter","sec-","sendo","sgh-","sharp","siemens","sie-","softbank","sony","spice","sprint","spv","symbian","tablet","talkabout","tcl-","teleca","telit","tianyu","tim-","toshiba","tsm","up.browser","utec","utstar","verykool","virgin","vk-","voda","voxtel","vx","wap","wellco","wig browser","wii","windows ce","wireless","xda","xde","zte");
	        $is_mobile = false;
	        foreach ($mobile_agents as $device) {
	                if (stristr($user_agent, $device)) {
	                        $is_mobile = true;
	                        break;
	                }
	        }
		if($is_mobile){
			$this->display('goauth_m');
		}else{
			$this->display();	
		}		
    }
	/**********************
	         申请供图
	*****************/
    public function gocopyright(){
    
		$banner = getYunAd(1,5);//首页banner
		
		$lanmulist = M("yun720_nav")->where("type=2")->order("orderlist")->select();
		$lanmu = $this->getTree($lanmulist,0,0);
		
		$bannerad = getYunAd(2,5);//首页顶部广告
		$bannerm = getYunAd(6,5);//手机首页顶部广告
		$hot_pano = getReadPano(0,12);//从1开始的12条数据  推荐
		$hengtiaoad = getYunAd(3,1);//首页横条广告
		$articlefirst = getYunAd(4,1);//首页文章精选广告1
		$articletwo = getYunarticleAd(4,1,4);//首页文章精选广告4
		$author_member = getZhuozhevipPano(0,1);//显示一个认证作者信息
		$sheys_member = getSheysPano(0,6);//显示6个摄影师信息

		$this->assign("lanmu",$lanmu);
		$this->assign("banner",$banner);
		$this->assign("bannerm",$bannerm);
		$this->assign("bannerad",$bannerad);
		$this->assign("hot_pano", $hot_pano);
		$this->assign("hengtiaoad",$hengtiaoad);
		$this->assign("articlefirst",$articlefirst);
		$this->assign("articletwo",$articletwo);
		$this->assign("author_member", $author_member);
		$this->assign("sheys_member", $sheys_member);

		//判断是手机端还是PC端
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $mobile_agents = Array("240x320","acer","acoon","acs-","abacho","ahong","airness","alcatel","amoi","android","anywhereyougo.com","applewebkit/525","applewebkit/532","asus","audio","au-mic","avantogo","becker","benq","bilbo","bird","blackberry","blazer","bleu","cdm-","compal","coolpad","danger","dbtel","dopod","elaine","eric","etouch","fly ","fly_","fly-","go.web","goodaccess","gradiente","grundig","haier","hedy","hitachi","htc","huawei","hutchison","inno","ipad","ipaq","ipod","jbrowser","kddi","kgt","kwc","lenovo","lg ","lg2","lg3","lg4","lg5","lg7","lg8","lg9","lg-","lge-","lge9","longcos","maemo","mercator","meridian","micromax","midp","mini","mitsu","mmm","mmp","mobi","mot-","moto","nec-","netfront","newgen","nexian","nf-browser","nintendo","nitro","nokia","nook","novarra","obigo","palm","panasonic","pantech","philips","phone","pg-","playstation","pocket","pt-","qc-","qtek","rover","sagem","sama","samu","sanyo","samsung","sch-","scooter","sec-","sendo","sgh-","sharp","siemens","sie-","softbank","sony","spice","sprint","spv","symbian","tablet","talkabout","tcl-","teleca","telit","tianyu","tim-","toshiba","tsm","up.browser","utec","utstar","verykool","virgin","vk-","voda","voxtel","vx","wap","wellco","wig browser","wii","windows ce","wireless","xda","xde","zte");
	        $is_mobile = false;
	        foreach ($mobile_agents as $device) {
	                if (stristr($user_agent, $device)) {
	                        $is_mobile = true;
	                        break;
	                }
	        }
		if($is_mobile){
			$this->display('gocopyright_m');
		}else{
			$this->display();	
		}		
    }
	/**********************
	         图片市场
	*****************/
    public function market(){
    
		$banner = getYunAd(1,5);//首页banner
		
		$lanmulist = M("yun720_nav")->where("type=2")->order("orderlist")->select();
		$lanmu = $this->getTree($lanmulist,0,0);
		
		$bannerad = getYunAd(2,5);//首页顶部广告
		$bannerm = getYunAd(6,5);//手机首页顶部广告
		$hot_pano = getReadPano(0,12);//从1开始的12条数据  推荐
		$hengtiaoad = getYunAd(3,1);//首页横条广告
		$articlefirst = getYunAd(4,1);//首页文章精选广告1
		$articletwo = getYunarticleAd(4,1,4);//首页文章精选广告4
		$author_member = getZhuozhevipPano(0,1);//显示一个认证作者信息
		$sheys_member = getSheysPano(0,6);//显示6个摄影师信息

		$this->assign("lanmu",$lanmu);
		$this->assign("banner",$banner);
		$this->assign("bannerm",$bannerm);
		$this->assign("bannerad",$bannerad);
		$this->assign("hot_pano", $hot_pano);
		$this->assign("hengtiaoad",$hengtiaoad);
		$this->assign("articlefirst",$articlefirst);
		$this->assign("articletwo",$articletwo);
		$this->assign("author_member", $author_member);
		$this->assign("sheys_member", $sheys_member);

		//判断是手机端还是PC端
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $mobile_agents = Array("240x320","acer","acoon","acs-","abacho","ahong","airness","alcatel","amoi","android","anywhereyougo.com","applewebkit/525","applewebkit/532","asus","audio","au-mic","avantogo","becker","benq","bilbo","bird","blackberry","blazer","bleu","cdm-","compal","coolpad","danger","dbtel","dopod","elaine","eric","etouch","fly ","fly_","fly-","go.web","goodaccess","gradiente","grundig","haier","hedy","hitachi","htc","huawei","hutchison","inno","ipad","ipaq","ipod","jbrowser","kddi","kgt","kwc","lenovo","lg ","lg2","lg3","lg4","lg5","lg7","lg8","lg9","lg-","lge-","lge9","longcos","maemo","mercator","meridian","micromax","midp","mini","mitsu","mmm","mmp","mobi","mot-","moto","nec-","netfront","newgen","nexian","nf-browser","nintendo","nitro","nokia","nook","novarra","obigo","palm","panasonic","pantech","philips","phone","pg-","playstation","pocket","pt-","qc-","qtek","rover","sagem","sama","samu","sanyo","samsung","sch-","scooter","sec-","sendo","sgh-","sharp","siemens","sie-","softbank","sony","spice","sprint","spv","symbian","tablet","talkabout","tcl-","teleca","telit","tianyu","tim-","toshiba","tsm","up.browser","utec","utstar","verykool","virgin","vk-","voda","voxtel","vx","wap","wellco","wig browser","wii","windows ce","wireless","xda","xde","zte");
	        $is_mobile = false;
	        foreach ($mobile_agents as $device) {
	                if (stristr($user_agent, $device)) {
	                        $is_mobile = true;
	                        break;
	                }
	        }
		if($is_mobile){
			$this->display('market_m');
		}else{
			$this->display();	
		}		
    }
	/**********************
	        开通VIP
	*****************/
    public function openvip(){
    
		$banner = getYunAd(1,5);//首页banner
		
		$lanmulist = M("yun720_nav")->where("type=2")->order("orderlist")->select();
		$lanmu = $this->getTree($lanmulist,0,0);
		
		$bannerad = getYunAd(2,5);//首页顶部广告
		$bannerm = getYunAd(6,5);//手机首页顶部广告
		$hot_pano = getReadPano(0,12);//从1开始的12条数据  推荐
		$hengtiaoad = getYunAd(3,1);//首页横条广告
		$articlefirst = getYunAd(4,1);//首页文章精选广告1
		$articletwo = getYunarticleAd(4,1,4);//首页文章精选广告4
		$author_member = getZhuozhevipPano(0,1);//显示一个认证作者信息
		$sheys_member = getSheysPano(0,6);//显示6个摄影师信息

		$this->assign("lanmu",$lanmu);
		$this->assign("banner",$banner);
		$this->assign("bannerm",$bannerm);
		$this->assign("bannerad",$bannerad);
		$this->assign("hot_pano", $hot_pano);
		$this->assign("hengtiaoad",$hengtiaoad);
		$this->assign("articlefirst",$articlefirst);
		$this->assign("articletwo",$articletwo);
		$this->assign("author_member", $author_member);
		$this->assign("sheys_member", $sheys_member);

		//判断是手机端还是PC端
        $user_agent = $_SERVER['HTTP_USER_AGENT'];
        $mobile_agents = Array("240x320","acer","acoon","acs-","abacho","ahong","airness","alcatel","amoi","android","anywhereyougo.com","applewebkit/525","applewebkit/532","asus","audio","au-mic","avantogo","becker","benq","bilbo","bird","blackberry","blazer","bleu","cdm-","compal","coolpad","danger","dbtel","dopod","elaine","eric","etouch","fly ","fly_","fly-","go.web","goodaccess","gradiente","grundig","haier","hedy","hitachi","htc","huawei","hutchison","inno","ipad","ipaq","ipod","jbrowser","kddi","kgt","kwc","lenovo","lg ","lg2","lg3","lg4","lg5","lg7","lg8","lg9","lg-","lge-","lge9","longcos","maemo","mercator","meridian","micromax","midp","mini","mitsu","mmm","mmp","mobi","mot-","moto","nec-","netfront","newgen","nexian","nf-browser","nintendo","nitro","nokia","nook","novarra","obigo","palm","panasonic","pantech","philips","phone","pg-","playstation","pocket","pt-","qc-","qtek","rover","sagem","sama","samu","sanyo","samsung","sch-","scooter","sec-","sendo","sgh-","sharp","siemens","sie-","softbank","sony","spice","sprint","spv","symbian","tablet","talkabout","tcl-","teleca","telit","tianyu","tim-","toshiba","tsm","up.browser","utec","utstar","verykool","virgin","vk-","voda","voxtel","vx","wap","wellco","wig browser","wii","windows ce","wireless","xda","xde","zte");
	        $is_mobile = false;
	        foreach ($mobile_agents as $device) {
	                if (stristr($user_agent, $device)) {
	                        $is_mobile = true;
	                        break;
	                }
	        }
		if($is_mobile){
			$this->display('market_m');
		}else{
			$this->display();	
		}		
    }
	
//首页导航
//递归首页栏目列表函数  data 数据  pid 父id  n 递归深度
public function getTree1($data, $pId,$n)
    {
        $html = '';
        $fenjie=array();
        foreach($data as $k => $v)
        {
           if($v['pid'] == $pId)
           { 
	           if($v['pid']==0){
	           		$frist="";
	           }else{
	           		$frist="|";
	           }
		    if($n){
		    	for ($i=0; $i < $n; $i++) { 
		    		$v['char']=$v['char']."<a href=\"".$v['id']."\">";
		    		$v['nbs']=$v['nbs']."</a>";
		    	}
		    }
	

		 	


		    
            $html .= $frist.$v['char'].$v['name']. $v['nbs'];
            $html .= self::getTree($data, $v['id'],($n+1));

           }
        }
        return $html ? '<tbody">'.$html.'</tbody>' : $html ;
    }
	
	
	//首页导航
//递归首页栏目列表函数  data 数据  pid 父id  n 递归深度
public function getTree($data, $pId,$n)
    {
        $html = '';
        $fenjie=array();
        foreach($data as $k => $v)
        {
           if($v['pid'] == $pId)
           { 
	           if($v['pid']==0 and $v['id']==1){
	           		$frist="<div class=\"Select_select_3SYiKS\" id=\"faxian\"><div class=\"Select_header_coOU8v \"><div class=\"FindSelect_head_2DbWd1\"><a class=\"FindSelect_linkActive_2Ir0Qo \" href=\"".$v['outlink']."\">";
					$fristtwo="</a></div></div><div class=\"Select_menu_tkm095\" style=\"left: -40px; top: 50px; width: 110px; padding-bottom: 0px; padding-top: 0px; border-radius: 0px;\"><div class=\"FindSelect_block_2kfVmB\">
";
	           }elseif($v['pid']==0 and $v['id']==2){
	           		$frist="<div class=\"Select_select_3SYiKS\" id=\"zuozhe\"><div class=\"Select_header_coOU8v \"><div class=\"FindSelect_head_2DbWd1\"><a class=\"FindSelect_link_1p7vCj \" href=\"".$v['outlink']."\">";
					$fristtwo="</a></div></div><div class=\"Select_menu_tkm095\" style=\"left: -40px; top: 50px; width: 110px; padding-bottom: 0px; padding-top: 0px; border-radius: 0px;\"><div class=\"FindSelect_block_2kfVmB\">
";
	           }
			   else{
	           		$frist="";
	           }
			   
			   
		    if($n){
		    	for ($i=0; $i < $n; $i++) { 
		    		$v['char']=$v['char']."<a href=\"".$v['outlink']."\">";
		    		$v['nbs']=$v['nbs']."</a>";
					if($v['orderlist']==3){
						$three="</div></div></div>";
					}
		    	}
		    }
	
	

			
			if($v['id']==3){
				   $two="<div><a href=\"".$v['outlink']."\" class=\"FindSelect_link_1p7vCj\">".$v['name']."</a></div>";
			   }else{
				    //$html .="<div class=\"Select_select_3SYiKS\"><div class=\"Select_header_coOU8v \"><div class=\"FindSelect_head_2DbWd1\"><a href=\"\" class=\"FindSelect_link_1p7vCj \">";
					$html .= $frist.$v['char'].$v['name']. $v['nbs'].$fristtwo.$three;
					//$html .="</a></div></div><div class=\"Select_menu_tkm095\" style=\"left: -40px; top: 50px; width: 110px; padding-bottom: 0px; padding-top: 0px; border-radius: 0px;\"><div class=\"FindSelect_block_2kfVmB\">";
					$html .= self::getTree($data, $v['id'],($n+1));
					//$html .= "</div></div></div>";
			   }
			  
		 	
       
           }
        }
        return ''.$html.$two.'';
    }
	
	public function get_xiangguan_article($username,$num){
		
      			$article = M("yun720_article")->where(" status='1' and  lanmuid !=0 and username='".$username."' ")->order("listorder ASC")->limit(0,$num)->select();
				if(!empty($article)){
      				foreach($article as $key=>$val)
      				{
      					$article[$key]['ariurl']= "/index.php?s=/Webyun/content/id/".$val['id'];
      				}
      			}
      			return $article;
	}   
}