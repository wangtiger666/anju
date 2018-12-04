<?php
 
class RepassAction extends Action {

    function index() {
        if (I("post.dopost") == "reg") {

            $account = trim(I("post.account"));
            $pwd = I("post.pwd");
            $nickname = I("post.nickname");
            $verifycode = I("post.verifycode");
            $reg_act =  I("post.reg_act");

            if ($account == "") {
                echo "javascript:alert('用户名不能为空！',2);";
                exit();
            } else {
                $map = array(
                    "account" => $account
                );
                $row = M("Member")->where($map)->find();
                if (!is_array($row)) {
                    echo "javascript:alert('帐号不存在！',2);";
                    exit();
                }
            }
            if ($pwd == "") {
                echo "javascript:alert('密码不能为空！',2);";
                exit();
            }
            $userpwd = substr(md5($pwd), 5, 20);

            if ($nickname == "") {
                echo "javascript:alert('昵称不能为空！',2);";
                exit();
            }

            //验证码两种方式验证 手机验证码 和 TK框架验证码
            if($reg_act=="email"){
                 $phone = "";
                 $email = I("post.account");

                if($_SESSION['verify'] != md5($verifycode)) {
                      echo "javascript:alert('验证码错误',2);";
                        exit();
                 }

                }elseif($reg_act=="phone"){
                    $phone = I("post.account");
                    $email = "";
                    //验证码有效期  一分钟
                    $onesec_time=time()-60;
                    $v_row = M("yun_verifycode")->where('phone="'.$phone .'" AND inserttime >= "'.$onesec_time.'"')->order('inserttime DESC')->limit(1)->select();
                    $code_res = $v_row[0]['verifycode'];

                    if($code_res==""){
                     echo "javascript:alert('验证码超时，请重新发送',2);";
                        exit();
                    }
                    if($verifycode!=$code_res){
                        echo "javascript:alert('验证码错误',2);";
                        exit();
                    }
                }else{
                    echo "javascript:alert('错误操作，请刷新页面重新提交',2);";
                    exit();
            }

            $editarr = array(
                "password" => $userpwd
            );
             
            $map = array(
                "id" => $row['id']
            );
            M("Member")->where($map)->save($editarr);

            session(C("SESSION_MEMBERID"), $member_id);
            session(C("SESSION_NAME"), $member_name);
            session(C("SESSION_POWER"), $power);
            session(C("SESSION_POWER_NAME"), $power_name);
        
            $url = U("member/index");
            echo "javascript:alert('修改成功',2);";
            echo "LinkTo('$url');";
            exit();
        }

        $HTTP_HOST = $_SERVER['HTTP_HOST']?$_SERVER['HTTP_HOST']:$HTTP_SERVER_VARS['HTTP_HOST'];
        $domainlink = $HTTP_HOST;

         $appid =  "wxa76bbf4fc700255a";
        $weixinlogin_url = "https://open.weixin.qq.com/connect/qrconnect?appid=wxa76bbf4fc700255a&redirect_uri=http%3A%2F%2Fwww.360720.com%2Fmember%2Flogin%2Fweixinlogin&response_type=code&scope=snsapi_login&state=STATE#wechat_redirect";


        $this->assign("weixinlogin_url",$weixinlogin_url);
        $this->assign("domainlink",$domainlink);
        $this->display();
    }
    
    /*注册手机验证*/ 
function send_mobile_code(){
             
        //接受过来的mobile_phone 数据库查询有没有用户名用mobile_phone
        $userid=$_POST['mobile_phone']; 
        //没填写手机号验证
        if(empty($userid)){
             $res['msg']="请填写手机号";
            $res['status']="0";
            echo json_encode($res);die;
        }
        //手机格式验证
        if(empty($userid) || strlen($userid) <> 11 || !preg_match("/^(((13[0-9]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/", $userid)){
            $res['msg']="手机格式不正确";
            $res['status']="0";
            echo json_encode($res);die;
        }
        //是否有手机号验证
        $map = array(
                "phone" => $userid
        );
        $row = M("member")->where($map)->find();

        if(!is_array($row))
        {
            $res['msg']="手机号未注册";
            $res['status']="0";
            echo json_encode($res);die;
        }
        //验证码
        $mobile_code = rand(100000,999999);
        $juheres = $this->sendjuheSMS($userid,$mobile_code);

        //当前时间
        $time= time();
        if($juheres['error_code']=="0"){
            //发送成功入库 pano_verifycode
            $editarr = array(
                "phone" => $userid,
                'verifycode'=>$mobile_code,
                "inserttime" => $time
            );

            $phonereg_res = M("yun_verifycode")->add($editarr);
           
            if($phonereg_res){
                        $res['msg']="短信发送成功";
                        $res['status']="1";
                        echo json_encode($res);die;
                }else{
                        $res['msg']="请重新发送。";
                        $res['status']="0";
                        echo json_encode($res);die;
                   }
            }else{
                //短信发送失败
                $res['msg']="短信发送失败。";
                $res['status']="0";
                echo json_encode($res);die;
                }

        echo $reslut;die;
    }

 function sendjuheSMS($mobile, $content){
    $appkey ='6efff19ec80fcd9f91500168519f1c6e';
    $url ='http://v.juhe.cn/sms/send'; #请求的数据接口URL
    $tpl_id=10732;
    $tpl_value = urlencode("#code#=".$content);
    $mobile = $mobile;
    $params ="key=".$appkey."&mobile=".$mobile."&tpl_id=".$tpl_id."&tpl_value=".$tpl_value;
    $content = $this->juhecurl($url,$params,0);
    $reslut= $this->objecttoarray($content);
    $reason=$reslut['reason'];
    $error=$reslut['error_code'];

    $res=array(
            "reason"=>$reason,
            "error_code"=>$error,
            );

    return $res;
}   

    
function objecttoarray(&$object) {
             $object =  json_decode($object,true);
             return  $object;
}

/*聚合短信发送信息接口函数*/
function juhecurl($url,$params=false,$ispost=0){
    $httpInfo = array();
    $ch = curl_init();

    curl_setopt( $ch, CURLOPT_HTTP_VERSION , CURL_HTTP_VERSION_1_0 );
    curl_setopt( $ch, CURLOPT_USERAGENT , 'Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.22 (KHTML, like Gecko) Chrome/25.0.1364.172 Safari/537.22' );
    curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT , 30 );
    curl_setopt( $ch, CURLOPT_TIMEOUT , 30);
    curl_setopt( $ch, CURLOPT_RETURNTRANSFER , true );
    if( $ispost )
    {
        curl_setopt( $ch , CURLOPT_POST , true );
        curl_setopt( $ch , CURLOPT_POSTFIELDS , $params );
        curl_setopt( $ch , CURLOPT_URL , $url );
    }
    else
    {
        if($params){
            curl_setopt( $ch , CURLOPT_URL , $url.'?'.$params );
        }else{
            curl_setopt( $ch , CURLOPT_URL , $url);
        }
    }
    $response = curl_exec( $ch );
    if ($response === FALSE) {
        #echo "cURL Error: " . curl_error($ch);
        return false;
    }
    $httpCode = curl_getinfo( $ch , CURLINFO_HTTP_CODE );
    $httpInfo = array_merge( $httpInfo , curl_getinfo( $ch ) );
    curl_close( $ch );
    return $response;
}


//检测账号是否被注册
 function check_regstatus(){
        $account = I("post.account");
        $map = array(
                    "account" => $account
                );
        $row = M("Member")->where($map)->find();
        if (!is_array($row)) {
            echo "javascript:alert('帐号不存在！',2);";
            exit();
        }
 }

//验证码
public function verify(){
        session_start();
        import('ORG.Util.Image');
        ob_clean();
        Image::buildImageVerify();
    }

}

?>
