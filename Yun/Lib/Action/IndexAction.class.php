<?php
// 本类由系统自动生成，仅供测试用途
class IndexAction extends PublicAction {
    public function index(){

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
			$banner_flag ="1";
			$banner = getPosAd(10,10);//手机banner
		}else{
			$banner_flag ="";
			$banner = getPosAd(9,10);//首页banner
		}

		//模板选择 数值为1 是云平台模板  数值为2 是WEB站点模板
		$web_moban = M("Sysconfig")->where("varname='web_moban'")->getfield('value');
		if($web_moban=="2"){
			echo "<script language='javascript'>location.href='index.php?s=/Website'</script>"; exit();	
		}
		if($web_moban=="3"){
			echo "<script language='javascript'>location.href='index.php?s=/Webyun'</script>"; exit();	
		}

		$left_pano = getRecommendPano(0,1); //从0开始的一条数据
		$right_pano = getRecommendPano(1,11);//从1开始的11条数据
		$recommend_pano = getRecommendPano(12,30);//从12开始的30条数据
		$getPosAd1 = getPosAd(1,1);
		$getPosAd2 = getPosAd(2,1);
		$getPosAd3 = getPosAd(3,1);
		$getPosAd4 = getPosAd(4,1);
		$getPosAd5 = getPosAd(5,1);
		$getPosAd6 = getPosAd(6,1);
		$getPosAd7 = getPosAd(7,1);
		$getPosAd8 = getPosAd(8,1);

		$this->assign("banner_flag", $banner_flag);
		$this->assign("banner", $banner);
		$this->assign("getPosAd1", $getPosAd1);
		$this->assign("getPosAd2", $getPosAd2);
		$this->assign("getPosAd3", $getPosAd3);
		$this->assign("getPosAd4", $getPosAd4);
		$this->assign("getPosAd5", $getPosAd5);
		$this->assign("getPosAd6", $getPosAd6);
		$this->assign("getPosAd7", $getPosAd7);
		$this->assign("getPosAd8", $getPosAd8);
		$this->assign("left_pano", $left_pano);
		$this->assign("right_pano", $right_pano);
		$this->assign("recommend_pano", $recommend_pano);
		$this->assign("indexnav", 1);
		
		$this->display();
    }
}