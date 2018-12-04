<?php
class PublicAction extends Action {

    static $ROOT = 1;//根节点
    static $HOUSE_RESOURCES_TYPE = 2;//房源信息属性
    static $HOUSE_TYPE = 3;//房屋户型
    static $UPTOWN_TYPE = 27;//小区类型
    static $LEASE_TYPE = 24;//租赁方式
    static $HOUSE_DESC = 9;//房屋装修
    static $HOUSE_DIRECTION = 15;//朝向


	public function _initialize(){

			//获取后台变量  web_name
			$web_name = M("sysconfig")->where("varname = 'web_name' ")->getField('value');
			//关键词
			$web_keywords = M("sysconfig")->where("varname = 'web_keywords' ")->getField('value');
            $web_description= M("sysconfig")->where("varname = 'web_description' ")->getField('value');

			//底部友情链接
			$link = M("yun_weblink")->order("id ASC")->select();

            //底部版权和备案号
            $beian = $this->getsysconfig('ipc_number');
            $tel   = $this->getsysconfig('coop_tel');
            $time   = $this->getsysconfig('work_time');
            $banquan   = $this->getsysconfig('web_copyright');	
            $email   = $this->getsysconfig('web_email');
            $kefutel   = $this->getsysconfig('customer_tel');
			$qqqun   = $this->getsysconfig('web_qqqun');

            $fristnav = $this->getfooternavt(0);
            if($fristnav){
                foreach ($fristnav as $key => $value) {
                     $footernav1[$key]=$value;
                     $footernav2[$key]=$this->getfooternavt($value['id']);
                }
            }
			
			$footeryunnav = $this->getfooteryunnavt(1);
			$gonggao = getGongGao(4);

            $footernav12 = $footernav2['0'];
            $footernav22 = $footernav2['1'];
            $footernav32 = $footernav2['2'];
            $footernav42 = $footernav2['3'];
            $HTTP_HOST = $_SERVER['HTTP_HOST']?$_SERVER['HTTP_HOST']:$HTTP_SERVER_VARS['HTTP_HOST'];
            $domainlink = 'http://' . $HTTP_HOST;

            $this->assign("web_description", $web_description);
            $this->assign("web_keywords", $web_keywords);
            $this->assign("domainlink", $domainlink);
            $this->assign("footernav1", $footernav1);
			$this->assign("footeryunnav", $footeryunnav);
            $this->assign("footernav12", $footernav12);
            $this->assign("footernav22", $footernav22);
            $this->assign("footernav32", $footernav32);
            $this->assign("footernav42", $footernav42);
            
            $this->assign("web_name", $web_name);
			$this->assign("gonggao", $gonggao);
            $this->assign("kefutel", $kefutel);
            $this->assign("email", $email);
            $this->assign("link", $link);
            $this->assign("beian", $beian);
            $this->assign("tel", $tel);
			$this->assign("qqqun", $qqqun);
            $this->assign("time", $time);
            $this->assign("banquan", $banquan);

            //判断头部选中样式用
            $murl=$_GET["_URL_"][1];
            $this->assign("murl",$murl);
    }

    public function getfooternavt($pid){
        if(!$pid){
            $list = M("yunweb_nav")->where("pid = 0")->order("id asc")->select();
        }else{
            $list = M("yunweb_nav")->where("pid = $pid")->order("id asc")->select();
        }
        return $list;
    }
	
	public function getfooteryunnavt($pid){
        $list = M("yun720_nav")->where("type = $pid")->order("id asc")->select();       
        return $list;
    }
	


	public function index(){
		$this->display();
    }
	//自定义图片验证码
	public function verifyBig() {
        createImageVerify();
    }

public function getfooternav($level,$pos,$num){
            if(!$level) return ;
            if(!$pos) return ;
            if(!$num) return ;
            $where=array(
                'level'=>$level,
                'pos'=>$pos,
                'isshow'=>1
              );

            $Model = M("yunweb_nav");
            $list = $Model->where($where)->order("orderlist ASC")->limit($num)->select();
            return $list;
  }
  
 

    public function getsysconfig($varname){
    		if(!$varname) return ;
    		$varname = array("varname" => $varname);
            $res = M("sysconfig")->where($varname)->getField("value");
            return $res;
    }


    /**
     * 获取客户端ip
     * @return array|false|string
     */
    public function get_ip(){
        if(isset($_SERVER)){
            if(isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
                $realip = $_SERVER["HTTP_X_FORWARDED_FOR"];
            }else if(isset($_SERVER["HTTP_CLIENT_IP"])){
                $realip = $_SERVER["HTTP_CLIENT_IP"];
            }else{
                $realip = $_SERVER["REMOTE_ADDR"];
            }
        }else{
            if(getenv("HTTP_X_FORWARDED_FOR")){
                $realip = getenv("HTTP_X_FORWARDED_FOR");
            }else if (getenv("HTTP_CLIENT_IP")){
                $realip = getenv("HTTP_CLIENT_IP");
            }else{
                $realip = getenv("REMOTE_ADDR");
            }
        }
        if(strrpos($realip,",")>0){
			$arr = explode(',',$realip);
            $realip=trim($arr[0],' ');
        }
        return $realip;
    }


    //获取几房几厅
    function getCommonalityArr($pid){
        $list = M('commonality')->where(array('pid' => $pid))->field('id,name')->select();
        $attrList = array();
        foreach ($list as $key => $value){
            $attrList[$value['id']] = $value['name'];
        }

        return $attrList;
    }

}