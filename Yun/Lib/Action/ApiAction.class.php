<?php

/**
 * 公共方法接口
 * Class LoginRegAction
 */
class ApiAction extends PublicAction {

    private $ignorePhoneArr = [ '15260228937' ];

    /**
     * 发送短信接口
     * phone 手机号
     * code_type 短信类型 1：手机注册 2：手机绑定 3：找回密码  4:手机登陆
     */
    public function sendPhoneMessage(){
        //接收请求参数
        $param = [];
        $param['phone'] = I('post.phone');
        $param['code_type'] = I('post.code_type');
        $result = [];
        if(checkEmpty($param)){
            $result['code'] = 400;
            $result['msg'] = '参数错误';
            $this->ajaxReturn($result);
        }

        $phone = $param['phone'];
        $code_type = $param['code_type'];

        if(!isPhone($phone)){
            $result['code'] = 400;
            $result['msg'] = '手机号格式错误';
            $this->ajaxReturn($result);
        }

        if(!in_array($code_type, [1 ,2, 3, 4])){
            $result['code'] = 400;
            $result['msg'] = '短信类型错误';
            $this->ajaxReturn($result);
        }

        //判断手机号发送上限
        if(!in_array($phone, $this->ignorePhoneArr)){
            $dateStart = getDateStart();
            $dateEnd = getDateEnd();
            $count = M('phone_code')
                ->where(['phone' => $phone, 'code_type' => $code_type])
                ->where(['create_time' => array(array('egt', $dateStart),array('elt', $dateEnd))])
                ->count('id');

            if($count >= 3){
                $result['code'] = 400;
                $result['msg'] = '当天发送量已达到上限';
                $this->ajaxReturn($result);
            }
        }


        $code = rand(100000, 999999);

        import('@.Service.ApiService');
        $ret = ApiService::sendPhoneMessage($phone, $code);
        $retArr = json_decode($ret, true);
        if(json_last_error() > 0){
            $result['code'] = 400;
            $result['msg'] = '短信发送失败';
            $this->ajaxReturn($result);
        }

        //发送失败
        if($retArr['code'] != '000000'){
            Log::record('【ApiController\sendPhoneMessage】运营商返回错误代码：'. $retArr['code']);
            Log::record('【ApiController\sendPhoneMessage】运营商返回错误描述：'. $retArr['description']);
            $result['code'] = 400;
            $result['msg'] = '短信发送失败！';
            $this->ajaxReturn($result);
        }

        //记录短信记录
        import('@.Model.PhoneCode');
        PhoneCode::add($phone, $code, $code_type, $this->get_ip());

        //发送成功
        $result['code'] = 200;
        $result['msg'] = '短信发送成功！' . $code;
        $this->ajaxReturn($result);
    }



    /**
     * 手机验证码验证接口
     * phone 手机号
     * code 验证码
     * code_type 短信类型 1：手机注册 2：手机绑定 3：找回密码  4:手机登陆
     */
    public function phoneVerifycodeValid(){
        //接收请求参数
        $param = [];
        $param['phone'] = I('post.phone');
        $param['code'] = I('post.code');
        $param['code_type'] = I('post.code_type');

        $result = [];
        if(checkEmpty($param)){
            $result['code'] = 400;
            $result['msg'] = '参数错误';
            $this->ajaxReturn($result);
        }

        $phone = $param['phone'];
        $code = $param['code'];
        $code_type = $param['code_type'];

        if(!isPhone($phone)){
            $result['code'] = 400;
            $result['msg'] = '手机号格式错误';
            $this->ajaxReturn($result);
        }

        //验证账号的正确性
        import('@.Model.Account');
        $check = Account::checkAccount($phone);
        if(!$check){
            $result['code'] = 100;
            $result['msg'] = '手机号不存在';
            $this->ajaxReturn($result);
        }

        //判断短信验证码的正确性
        import('@.Model.PhoneCode');
        $nowtime = date('Y-m-d H:i:s');
        $codeArr = PhoneCode::checkCode($phone, $code, $code_type);
        if(empty($codeArr)){
            $result['code'] = 300;
            $result['msg'] = '验证码已失效，请重新获取';
            $this->ajaxReturn($result);
        }

        //修改验证码状态
        $codeData = [
            'status' => 2,
            'modify_time' => $nowtime,
        ];
        PhoneCode::update($codeArr['id'], $codeData);

        //验证通过
        $result['code'] = 200;
        $result['msg'] = '验证成功';
        $this->ajaxReturn($result);
    }

    //生成验证码
    public function geetestLib(){
        require_once VENDOR_PATH . 'gt3-php-sdk/lib/class.geetestlib.php';
        require_once VENDOR_PATH . 'gt3-php-sdk/config/config.php';

        $gtSdk = new GeetestLib(CAPTCHA_ID, PRIVATE_KEY);
        session_start();

        $ip = $this->get_ip();
        $data = [
            "user_id" => "chaoyou@xmchaoyou.com.cn", # 网站用户id
            "client_type" => "web", #web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
            "ip_address" => $ip # 请在此处传输用户请求验证时所携带的IP
        ];
        $status = $gtSdk->pre_process($data, 1);
        $_SESSION['gtserver'] = $status;
        $_SESSION['user_id'] = $data['user_id'];
        echo $gtSdk->get_response_str();
    }
}