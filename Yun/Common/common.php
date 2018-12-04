<?php

/**
 * 获取开始时间
 * @param null $timestamp
 * @return false|string
 */
function getDateStart($timestamp = null)
{
    if (empty($timestamp)) {
        return date("Y-m-d 00:00:00");
    } else {
        return date("Y-m-d 00:00:00", $timestamp);
    }
}

/**
 * 获取结束时间
 * @param null $timestamp
 * @return false|string
 */
function getDateEnd($timestamp = null)
{
    if (empty($timestamp)) {
        return date("Y-m-d 23:59:59");
    } else {
        return date("Y-m-d 23:59:59", $timestamp);
    }
}

/**
 * 判断是否是手机号
 * @param $phone
 * @return bool
 */
function isPhone($phone) {
    $search = '/^1[3|4|5|6|7|8]\d{9}$/';
    if (preg_match( $search, $phone)) {
        return true;
    } else {
        return false;
    }
}

/**
 * 判断数组或者字符串是否为空
 * @param $param
 * @return bool
 */
function checkEmpty($param){
    $emptyFlag = false;
    if(is_array($param)){
        foreach ($param as $k => $v){
            if(empty($v)){
                $emptyFlag = true;
                break;
            }
        }
    }else if(is_string($param)){
        if(empty($param)){
            $emptyFlag = true;
        }
    }
    return $emptyFlag;
}



/**
 * 模拟post请求
 * @param $url 请求地址
 * @param $data 请求数据
 * @param $headers 头部信息数据
 * @return mixed
 */
function curlPost($url, $data = array(), $headers = array()){
    $curl = curl_init();

    curl_setopt($curl, CURLOPT_URL, $url);

    if(empty($headers)){
        $headers = array();
        $headers[] = 'method: POST';
        $headers[] = 'Content-Type: application/x-www-form-urlencoded;';
    }

    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_HEADER, 1);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($curl, CURLOPT_TIMEOUT,60);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER ,false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST ,false);

    if(!empty($data)){
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
    }

    $return_str = curl_exec($curl);

    curl_close($curl);

    return $return_str;
}




















//获取推荐的作品
function getRecommendPano($limitstart,$limitend)
{
	$Model = M("Pano");

	if(!isset($limitstart)){
			$pos_pano = $Model->where("is_recommend = 1 ")->order('id desc')->select();
	}else{
			$pos_pano = $Model->where("is_recommend = 1 ")->order('id desc')->limit($limitstart,$limitend)->select();
	}
	if(!empty($pos_pano))
	{
		foreach($pos_pano as $key=>$val)
		{
			$pos_pano[$key]['author'] = M("Member")->where(array("id"=>$val['member_id']))->getField("nickname");
			$pos_pano[$key]['panothumb'] = getPanoThumb($val['id']);
			$pos_pano[$key]['hits'] = M("hitscount")->where(array("pano_id"=>$val['id']))->getField("hits");
			$pos_pano[$key]['zan'] = M("hitscount")->where(array("pano_id"=>$val['id']))->getField("zan");
			$pos_pano[$key]['panopath'] = "/t/".$val['guid'];
		}
	}
	return $pos_pano;
}
//获取推荐文章
function getRecommendArt($limitstart,$limitend)
{
	$Model = M("yunweb_article");
	if(!isset($limitstart)){
			$article = $Model->where("is_recommend = 1 ")->order('id desc')->select();
	}else{
			$article = $Model->where("is_recommend = 1 ")->order('id desc')->limit($limitstart,$limitend)->select();
	}

	if(!empty($article)){
		foreach($article as $key=>$val)
		{
			$article[$key]['ariurl']= "/index.php?s=/Website/content/id/".$val['id'];
			if(empty($val['thumb'])){
                  $article[$key]['thumb']="/Public/webstyle/images/srcdefault.jpg";
                }
		}
	}

	return $article;
}


/*
  ********************************************
  	  720yun皮肤方法  start  QQ 540924692
  ********************************************
*/




//获取推荐的作品
function getReadPano($limitstart,$limitend)
{
	$Model = M("Pano");
	if(!isset($limitstart)){
			$pos_pano = $Model->where("is_recommend = 1 ")->order('id desc')->select();
	}else{
			$pos_pano = $Model->where("is_recommend = 1 ")->order('id desc')->limit($limitstart,$limitend)->select();
	}
	if(!empty($pos_pano))
	{
		foreach($pos_pano as $key=>$val)
		{
			$pos_pano[$key]['author'] = M("Member")->where(array("id"=>$val['member_id']))->getField("nickname");
			$pos_pano[$key]['rengzheng'] = M("Member")->where(array("id"=>$val['member_id']))->getField("rengzheng");
			$pos_pano[$key]['vip'] = M("Member")->where(array("id"=>$val['member_id']))->getField("vip");
			$pos_pano[$key]['panothumb'] = getPanoThumb($val['id']);
			$pos_pano[$key]['hits'] = M("hitscount")->where(array("pano_id"=>$val['id']))->getField("hits");
			$pos_pano[$key]['zan'] = M("hitscount")->where(array("pano_id"=>$val['id']))->getField("zan");
			$pos_pano[$key]['panopath'] = "/t/".$val['guid'];
			$pos_pano[$key]['hangyeid'] = "/index.php?s=/webyun/listpano/channelid/".$val['hangyeid'];
			//频道格式化
			$hangyeold= M("yunweb_hangye")->where(array("id"=>$val['hangyeid']))->getField("name");
			$pos_pano[$key]['hangye'] =preg_replace("/([\x{4e00}-\x{9fa5}])/u", "$1 ", $hangyeold,1);
			//作者主页格式化
			

			
		}
	}
	return $pos_pano;
}





//广告调用
function getYunAd($posid,$num)
{
	if(!$posid) return ;
	$Model = M("yun720_adlist");
	$YunAdlist = $Model->where(" is_show='1' and ad_id = ".$posid )->order("id asc")->limit($num)->select();;
	if($num=="1"){
		return $YunAdlist[0];
	}else{
		return $YunAdlist;
	}
}

//广告调用
function getYunarticleAd($posid,$start,$num)
{
	if(!$posid) return ;
	$Model = M("yun720_adlist");
	$YunAdlist = $Model->where(" is_show='1' and ad_id = ".$posid )->order("id asc")->limit($start,$num)->select();;
	if($num=="1"){
		return $YunAdlist[0];
	}else{
		return $YunAdlist;
	}
}
//认证作者
function getZhuozhevipPano($limitstart,$limitend)
{
	$Model = M("Member");
	if(!isset($limitstart)){
			$poszp_member = $Model->where("regfrom = 1 ")->order('id desc')->select();
	}else{
			$goods = M('Good');
			$poszp_member = $goods->table('pano_member goods,pano_hitscount brand')
								 ->where('goods.id = brand.member_id and STATUS =1 and vip=1')
								 ->field('goods.*,brand.member_id')
								 ->group('goods.id having COUNT( brand.pano_id)>3')
								 ->order('goods.id desc ')->limit($limitstart,$limitend)->select();
	}
	if(!empty($poszp_member))
			{
				foreach($poszp_member as $key=>$val)
				{
					
					if(!empty($val['headimg'])) $headimg = $val['headimg'];
					else $headimg = "/Public/member/images/common/no_img.jpg";
					$poszp_member[$key]['headimg'] = $headimg;
					
					
					$zpListrow = M("Hitscount")->where("member_id='".$val['id']."'")->order('zan desc')->limit(0,4)->select();   //按点赞数排序
					foreach($zpListrow as $zpkey=>$zpval)
					{
						
					$panorow = M("Pano")->where("id='".$zpval['pano_id']."'")->find();
					$zpListrow[$zpkey]['panothumb'] = getPanoThumb($panorow['id']);
					$zpListrow[$zpkey]['panopath'] = "/t/".$panorow['guid'];
					}
					$poszp_member[$key]["zhuozhevip"] = $zpListrow;	
				}
			}
	return $poszp_member;				
}

//热门摄影师
function getSheysPano($limitstart,$limitend)
{
	$order = "id desc";
	$where = "status=1";
	$Model = M("Member");
	//if(!isset($limitstart)){
	//$author_list = $Model->where($where)->select();
	$goods = M('Good');
	$author_list = $goods->table('pano_member goods,pano_hitscount brand')
								 ->where('goods.id = brand.member_id and STATUS =1')
								 ->field('goods.*,brand.member_id, SUM( brand.zan ) as zan')
								 ->group('goods.id having COUNT( brand.pano_id)>2')
								 ->order('zan desc ')->select();
	
	
	//$author_list = $Model->where($where)->limit($limitstart,$limitend)->select();

	if(!empty($author_list))
		{
			foreach($author_list as $key=>$val)
			{
				if(!empty($val['headimg'])) $headimg = $val['headimg'];
				else $headimg = "/Public/member/images/common/no_img.jpg";
				$author_list[$key]['headimg'] = $headimg;
				$renqi_list= M("hitscount")->where("member_id='".$val['id']."'")->select();

				$renqi_res = 0;
				foreach ($renqi_list as $keyt => $value) {
						$renqi_res += $value['hits'];
				}
				$sheng = M("Yun720_city")->where("id=".$val['province'])->getField("name");
				if($sheng!=null){
					
					$author_list[$key]['sheng']=$sheng;
				} 

			 
				if($renqi_res){
					if($renqi_res>=10000){
						$renqi_res = ($renqi_res/10000)."万";
					}
				}else{
					$renqi_res =0;
				}
				$author_list[$key]['renqi'] = $renqi_res;
				
				
			}
		}
	return $author_list;				
}

//首页文章推荐
function  getYunArticle($num){
		$Model = M("yun720_article");
		$article = $Model->where(" status='1' and is_recommend='1' ")->order("id desc")->limit($num)->select();
		if(!empty($article)){
			foreach($article as $key=>$val)
			{
				$article[$key]['ariurl']= "/index.php?s=/Webyun/content/id/".$val['id'];
				if(empty($val['thumb'])){
                  $article[$key]['thumb']="/Public/webstyle/images/srcdefault.jpg";
                }
			}
		}
		return $article;
 }
 
 //列表页文章推荐
function  getYunlistArticle($num){
		$Model = M("yun720_article");
		$article = $Model->where(" status='1' and is_recommend='1' ")->order("id desc")->limit($num)->select();
		if(!empty($article)){
			foreach($article as $key=>$val)
			{
				$minfo = M("Member")->where(array("id"=>$val['userid']))->find();

				if(!empty($minfo['headimg'])) $headimg = $minfo['headimg'];
				else $headimg = "/Public/member/images/common/no_img.jpg";
				$article[$key]['headimg'] = $headimg;
				
				$article[$key]['ariurl']= "/index.php?s=/Webyun/content/id/".$val['id'];

			}
		}
		return $article;
 }
 
 
//左文章栏目
function  getYunLanmu($catid,$num){
		$Model = M("yun720_lanmu");
		$lanmurow = $Model->where(" pid='".$catid."'")->order("id asc")->limit($num)->select();
		

		if(!empty($lanmurow)){
			foreach($lanmurow as $key=>$val)
			{
				$lanmurow[$key]['name']= $val['name'];
                $lanmurow[$key]['ariurl']="/index.php?s=/Webyun/articlelist/lanmuid/".$val['id'];
			}
		}
		return $lanmurow;
 }
 
 
 //系统公告
function  getGongGao($num){
		$Model = M("yun720_article");
		$article = $Model->where(" status='1' and is_recommend='1' and lanmuid='7' ")->order("id desc")->limit($num)->select();
		if(!empty($article)){
			foreach($article as $key=>$val)
			{
				$article[$key]['ariurl']= "/index.php?s=/Webyun/content/id/".$val['id'];
				if(empty($val['thumb'])){
                  $article[$key]['thumb']="/Public/webstyle/images/srcdefault.jpg";
                }
			}
		}
		return $article;
 }
/*
  ********************************************
  	  720yun皮肤方法  end  QQ 540924692
  ********************************************
*/
function getRecPano_home($str,$num,$type){
	$where= "is_recommend = '1' AND ";
	if($type=='area'){ 
		$where .=" areaid in (".$str.") ";
	}
	if($type=='hangye'){
		$where .=" hangyeid in (".$str.")";
	}

	$Model = M("Pano");
	$pos_pano = $Model->where($where)->order('id desc')->limit(0,$num)->select();
	if(!empty($pos_pano))
	{
		foreach($pos_pano as $key=>$val)
		{
			$pos_pano[$key]['author'] = M("Member")->where(array("id"=>$val['member_id']))->getField("nickname");
			$pos_pano[$key]['thumb'] = getPanoThumb($val['id']);
			$pos_pano[$key]['panopath'] = "/t/".$val['guid'];
		}
	}
	return $pos_pano;
}


function getRecPano($id,$num,$type){

	$where=array();
	if($type=='area'){ 
		$where['areaid']=$id;
	}
	if($type=='hangye'){
		$where['hangyeid']=$id;
	}
	$where['is_recommend']=1;

	$Model = M("Pano");
	$pos_pano = $Model->where($where)->order('id desc')->limit(0,$num)->select();
	if(!empty($pos_pano))
	{
		foreach($pos_pano as $key=>$val)
		{
			$pos_pano[$key]['author'] = M("Member")->where(array("id"=>$val['member_id']))->getField("nickname");
			$pos_pano[$key]['thumb'] = getPanoThumb($val['id']);
			$pos_pano[$key]['panopath'] = "/t/".$val['guid'];
		}
	}
	return $pos_pano;
}


function getPosPano($posid)
{
	if(!$posid) return ;
	$Model = M("YunPosition");
	$pos_pano = $Model->where("posid in ($posid)")->order("posidorder ASC")->select();
	if(!empty($pos_pano))
	{
		foreach($pos_pano as $key=>$val)
		{
			$pos_pano[$key]['member_id'] = M("Pano")->where(array("id"=>$val['pano_id']))->getField("member_id");
			$pos_pano[$key]['title'] = M("Pano")->where(array("id"=>$val['pano_id']))->getField("title");
			$pos_pano[$key]['author'] = M("Member")->where(array("id"=>$pos_pano[$key]['member_id']))->getField("nickname");
			$pos_pano[$key]['panothumb'] = getPanoThumb($val['pano_id']);
			$pos_pano[$key]['panopath'] = "/t/".$val['guid'];
		}
	}
	return $pos_pano;
}


function getPosArticle($posid,$num)
{
	if(!$posid) return ;
	$Model = M("yunweb_article");
	$article = $Model->where(" status='1' and  catid in ($posid)  ")->order("listorder ASC")->limit($num)->select();

	if(!empty($article)){
		foreach($article as $key=>$val)
		{
			$article[$key]['ariurl']= "index.php?s=/Website/content/id/".$val['id'];
			if(empty($val['thumb'])){
                  $article[$key]['thumb']="/Public/webstyle/images/srcdefault.jpg";
                }
		}
	}
	return $article;
}

function  getCatArticle($catid,$num){
		if(!$catid) return ;
		$Model = M("yunweb_article");
		$article = $Model->where(" status='1' and  lanmuid in ($catid)  ")->order("id desc")->limit($num)->select();

		if(!empty($article)){
			foreach($article as $key=>$val)
			{
				$article[$key]['ariurl']= "index.php?s=/Website/content/id/".$val['id'];
				if(empty($val['thumb'])){
                  $article[$key]['thumb']="/Public/webstyle/images/srcdefault.jpg";
                }
			}
		}
		return $article;
 }

function getWebArea(){
	$list = M("yunweb_area")->where("1")->order("listorder")->select();
	return $list;
}

function getWebHangye(){
	$list = M("yunweb_hangye")->where("1")->order("listorder")->select();
	return $list;
}

function getPosAd($posid,$num)
{
	if(!$posid) return ;
	$Model = M("yun_adlist");
	$YunAdlist = $Model->where(" is_show='1' and positionid = ".$posid )->order("id desc")->limit($num)->select();
	if($num=="1"){
		return $YunAdlist[0];
	}else{
		return $YunAdlist;
	}
}


function getWebPosAd($posid)
{
	if(!$posid) return ;
	$YunAdlist = M("yunweb_adlist")->where(" is_show='1' and ad_id = ".$posid )->order("listorder ASC")->select();
	return $YunAdlist;
}

function getWebPosPano($posid,$num) //全景推荐位
{
	if(!$posid) return ;
	$Idlist = M("yun_position")->where(" webposid = ".$posid )->order("posidorder")->limit($num)->select();

	if($Idlist){
		$idarray = array();
		foreach ($Idlist as $key => $value) {
				$idarray[$key] = $value['pano_id'];
		}
	}
	if($idarray){
		$idstr = implode(",",$idarray);
	}
	$YunAdlist = M("pano")->where( " id in ($idstr) " )->select();
	if($YunAdlist){
		foreach ($YunAdlist as $key => $val) {
			$YunAdlist[$key]['title']=M("Pano")->where(array("id"=>$val['id']))->getField("title");
			$YunAdlist[$key]['thumb']=getPanoThumb($val['id']);
			$pos_pano[$key]['panopath'] = "/t/".$val['guid'];
		}
	}

	return $YunAdlist;
}



function getChannelPanoList($id,$order="id desc")
{
	$Model = M("YunChannelpano");
	$panolist = array();

	if(empty($id)){
			$where = "1";
			$panolist = $Model->where($where)->order($order)->select();
	}else{
			$sql = "SELECT * FROM pano_pano as pp  LEFT JOIN pano_yun_channelpano as pyc  ON pp.id = pyc.pano_id  WHERE   pyc.channel_id = '".$id."'";
   			$panolist = M()->query($sql);
	}


	if(!empty($panolist))
	{
		foreach($panolist as $key=>$val)
		{
			$minfo = M("Member")->where(array("id"=>$val['member_id']))->find();
			if(!empty($minfo['headimg'])) $headimg = $minfo['headimg'];
			else $headimg = "/Public/member/images/common/no_img.jpg";
			$panolist[$key]['author'] = $minfo['nickname'];
			$panolist[$key]['headimg'] = $headimg;
			$panolist[$key]['panothumb'] = getPanoThumb($val['pano_id']);
			
			$hits[$key] = M("hitscount")->where(array("pano_id"=>$val['pano_id']))->getField("hits");
			$zan[$key] = M("hitscount")->where(array("pano_id"=>$val['pano_id']))->getField("zan");


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

			$panolist[$key]['title'] = M("pano")->where(array("id"=>$val['pano_id']))->getField("title");
			$pos_pano[$key]['panopath'] = "/t/".$val['guid'];
		}
	}
	return $panolist;
}

function getUserInfo($memberid)
{
	if(!$memberid) return ;
	$Model = M("Member");
	return $Model->where("memberid = '$memberid'")->find();
}
function getPanoThumb($pano_id)
{
	if(!$pano_id) return ;	
	$pano_view = M("pano_view")->where(array("pano_id"=>$pano_id))->find();
	if(!empty($pano_view['thumb'])){
		$thumb = $pano_view['thumb'];
	}else{
		$thumb = "/Public/member/images/pano/pano.jpg";
	}
	return $thumb;
}

function createImageVerify()
{
	session_start ();
	ob_clean(); //防止出现'图像因其本身有错无法显示'的问题
	header ( 'Content-type: image/png' );
	//创建图片
	$im = imagecreate($x=130,$y=40 );
	$bg = imagecolorallocate($im,rand(50,200),rand(0,155),rand(0,155)); //第一次对 imagecolorallocate() 的调用会给基于调色板的图像填充背景色
	$fontColor = imageColorAllocate ( $im, 255, 255, 255 );   //字体颜色
	//字体样式，这个可以从c:\windows\Fonts\文件夹下找到，这里可以替换其他的字体样式
	$fontstyle = './Public/ROCK.TTF';
	//产生随机字符
	for($i = 0; $i < 4; $i ++) {
		$randAsciiNumArray = array (rand(48,57),rand(65,90));
		$randAsciiNum = $randAsciiNumArray [rand ( 0, 1 )];
		//把 I(73)替换成A(65)  1(49)替换成B(66) O(79)替换成C(67) 数字0(48)替换成D(68) 区分开
		if($randAsciiNum==73) $randAsciiNum=65;
		if($randAsciiNum==49) $randAsciiNum=66;
		if($randAsciiNum==79) $randAsciiNum=67;
		if($randAsciiNum==48) $randAsciiNum=68;
		$randStr = chr ( $randAsciiNum );
		imagettftext($im,30,rand(0,20)-rand(0,25),5+$i*30,rand(30,35),$fontColor,$fontstyle,$randStr);
		$authcode .= $randStr; 
	}
	$_SESSION['codeverify'] = md5(strtolower($authcode));//用户和用户输入的验证码做比较
	//干扰线
	for ($i=0;$i<8;$i++){
		$lineColor = imagecolorallocate($im,rand(0,255),rand(0,255),rand(0,255));
		imageline ($im,rand(0,$x),0,rand(0,$x),$y,$lineColor);
	}
	//干扰点
	for ($i=0;$i<250;$i++){
		imagesetpixel($im,rand(0,$x),rand(0,$y),$fontColor);
	}
	imagepng($im);
	imagedestroy($im);
}


?>