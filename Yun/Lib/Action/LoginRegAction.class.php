<?php

/**
 * 登陆注册接口
 * Class LoginRegAction
 */
class LoginRegAction extends PublicAction {
    // 微信
    const WX_GET_CODE_URL = 'https://open.weixin.qq.com/connect/qrconnect';
    const WX_GET_ACCESS_TOKEN_URL = 'https://api.weixin.qq.com/sns/oauth2/access_token';
    const WX_REFRESH_ACCESS_TOKEN_URL = 'https://api.weixin.qq.com/sns/oauth2/refresh_token';
    const WX_TEST_ACCESS_TOKEN_URL = 'https://api.weixin.qq.com/sns/auth';
    const WX_GET_USERINFO_URL = 'https://api.weixin.qq.com/sns/userinfo';
    public $urlUtil;
    public function __construct()
    {
        parent::__construct();
        import("@.Class.UrlUtil");
        $this->urlUtil = new UrlUtil;
    }

    /**
     * 扫码之后首页拦截
     */
    public function index() {
        // 不加这个判断，当你第一次第三方登录完之后，刷新页面，session就消失
        $state = I('get.state');
        if (!isset($_SESSION['accountSession']['account'])){
            if ($state == md5("WeChatLogin_GavinTowrite")){
                $this->getAccessToken();
                $this->refreshAccessToken();
                $this->testAccessToken();
            }
        }
        $this->display();
    }

    /**
     * 通过code获取access_token
     */
    public function getAccessToken(){
        $state = I('get.state');
        if (strcmp($state,md5("WeChatLogin_GavinTowrite"))){
            $this->error("当前网站可能来自跨域伪造");
        }
        $code = I('get.code');
        $paramArr = [
            'appid'=>'wx5cabe40f2db5d1a2',
            'secret'=>'b911b1554bdf0977cefbb93e6a7bc5a2',
            'code'=>$code,
            'grant_type'=>'authorization_code'
        ];
        $response = $this->urlUtil->get(self::WX_GET_ACCESS_TOKEN_URL,$paramArr);
        $arr = json_decode($response,true);
        $_SESSION['get']['refresh_token'] = $arr['refresh_token'];
        return;
    }

    /**
     * 刷新或续期access_token使用
     */
    public function refreshAccessToken(){
        $paramArr = [
            'appid'=>'wx5cabe40f2db5d1a2',
            'grant_type'=>'refresh_token',
            'refresh_token'=>$_SESSION['get']['refresh_token']
        ];
        $response = $this->urlUtil->get(self::WX_REFRESH_ACCESS_TOKEN_URL,$paramArr);
        $arr = json_decode($response,true);
        $_SESSION['get']['access_token'] = $arr['access_token'];
        $_SESSION['get']['openid'] = $arr['openid'];
        return;
    }

    /**
     * 检验授权凭证（access_token)是否有效
     */
    public function testAccessToken(){
        import('@.Model.Account');
        $paramArr = [
          'access_token'=>$_SESSION['get']['access_token'],
          'openid'=>$_SESSION['get']['openid']
        ];
        $response = $this->urlUtil->get(self::WX_TEST_ACCESS_TOKEN_URL,$paramArr);
        $arr = json_decode($response,true);
        // 成功，进一步获取扫描人信息
        if ($arr['errcode'] == 0 && $arr['errmsg'] == 'ok') {
            // 获取扫码人信息
            $response = $this->urlUtil->get(self::WX_GET_USERINFO_URL, $paramArr);
            $userarr = json_decode($response, true);
            // 判断用户是否在数据库中
            $res = M('account')->where(['third_login_id' => $userarr['unionid']])->find();
            if (!empty($res)) {
                $_SESSION['accountSession'] = $res;
            } else {
                if ($userarr['unionid'] != null){
                    // 该用户数据库中不存在 保存用户信息
                    //接收请求参数
                    $param = [];
                    $param['phone'] = I('post.phone');
                    $param['code'] = I('post.code');

                    $result = [];
                    if(checkEmpty($param)){
                        $result['code'] = 400;
                        $result['msg'] = '参数错误';
                        $this->ajaxReturn($result);
                    }

                    $phone = $param['phone'];
                    $code = $param['code'];

                    if(!isPhone($phone)){
                        $result['code'] = 400;
                        $result['msg'] = '手机号格式错误';
                        $this->ajaxReturn($result);
                    }

                    $ret = $this->GeetestLibCheck();
                    if($ret['status'] == 'fail'){
                        $result['code'] = 500;
                        $result['msg'] = '验证码验证失败';
                        $this->ajaxReturn($result);
                    }

                    //判断短信验证码的正确性
                    import('@.Model.PhoneCode');
                    $nowtime = date('Y-m-d H:i:s');
                    $codeArr = PhoneCode::checkCode($phone, $code, 4);
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

                    $accountArr = Account::getAccount($phone);
                    if (empty($accountArr)){
                        $password = I('post.password');
                        $nowtime = date('Y-m-d H:i:s');
                        $accountData = [
                            'account' => $phone,
                            'password' => md5($password),
                            'binding_phone' => $phone,
                            'head_img' => '/Public/welcome/images/user-img-test.png',
                            'create_time' => $nowtime,
                            'modify_time' => $nowtime,
                            'third_login_type'=>'2',
                            'third_login_id'=>$userarr['unionid']
                        ];
                        M('account')->add($accountData);
                    }else{
                        $data['third_login_type'] = '2';
                        $data['third_login_id'] = $userarr['unionid'];
                        Account::update($phone, $data);
                    }

                    $account_info = Account::getAccount($phone);
                    $_SESSION['accountSession'] = $account_info;
                }
            }
        }
    }

    //极验验证码二次验证
    public function GeetestLibCheck(){
        //测试代码
        return ['status' => 'success'];

        /*
        //极验验证
        require_once VENDOR_PATH . 'gt3-php-sdk/lib/class.geetestlib.php';
        require_once VENDOR_PATH . 'gt3-php-sdk/config/config.php';

        $GtSdk = new GeetestLib(CAPTCHA_ID, PRIVATE_KEY);
        session_start();

        $data = array(
            "user_id" => 'chaoyou@xmchaoyou.com.cn', # 网站用户id
            "client_type" => "web", #web:电脑上的浏览器；h5:手机上的浏览器，包括移动应用内完全内置的web_view；native：通过原生SDK植入APP应用的方式
            "ip_address" => $this->get_ip() # 请在此处传输用户请求验证时所携带的IP
        );


        if ($_SESSION['gtserver'] == 1) {   //服务器正常
            $result = $GtSdk->success_validate($_POST['geetest_challenge'], $_POST['geetest_validate'], $_POST['geetest_seccode'], $data);
            if ($result) {
                return ['status' => 'success'];
            } else{
                return ['status' => 'fail'];
            }
        }else{  //服务器宕机,走failback模式
            if ($GtSdk->fail_validate($_POST['geetest_challenge'],$_POST['geetest_validate'],$_POST['geetest_seccode'])) {
                return ['status' => 'success'];
            }else{
                return ['status' => 'fail'];
        }
        }
        */
    }

    /**
     * 手机验证码登陆
     */
    public function phoneVerifycodeLogin(){
        //接收请求参数
        $param = [];
        $param['phone'] = I('post.phone');
        $param['code'] = I('post.code');

        $result = [];
        if(checkEmpty($param)){
            $result['code'] = 400;
            $result['msg'] = '参数错误';
            $this->ajaxReturn($result);
        }

        $phone = $param['phone'];
        $code = $param['code'];

        if(!isPhone($phone)){
            $result['code'] = 400;
            $result['msg'] = '手机号格式错误';
            $this->ajaxReturn($result);
        }

        $ret = $this->GeetestLibCheck();
        if($ret['status'] == 'fail'){
            $result['code'] = 500;
            $result['msg'] = '验证码验证失败';
            $this->ajaxReturn($result);
        }

        //验证账号的正确性
        import('@.Model.Account');
        $accountArr = Account::getAccount($phone);
        if(empty($accountArr)){
            $result['code'] = 100;
            $result['msg'] = '手机号不存在';
            $this->ajaxReturn($result);
        }

        //账号信息记录到session
        session('accountSession', $accountArr);

        //判断短信验证码的正确性
        import('@.Model.PhoneCode');
        $nowtime = date('Y-m-d H:i:s');
        $codeArr = PhoneCode::checkCode($phone, $code, 4);
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
        $result['msg'] = '登陆成功';
        $this->ajaxReturn($result);
    }


    /**
     * 账号秘密登陆
     */
    public function accountPwdLogin(){

        //接收请求参数
        $param = [];
        $param['Account.class'] = I('post.account');
        $param['password'] = I('post.password');

        $result = [];
        if(checkEmpty($param)){
            $result['code'] = 400;
            $result['msg'] = '参数错误';
            $this->ajaxReturn($result);
        }

        $account = $param['Account.class'];
        $password = $param['password'];

        if(!isPhone($account)){
            $result['code'] = 400;
            $result['msg'] = '手机号格式错误';
            $this->ajaxReturn($result);
        }

        $ret = $this->GeetestLibCheck();
        if($ret['status'] == 'fail'){
            $result['code'] = 500;
            $result['msg'] = '验证码验证失败';
            $this->ajaxReturn($result);
        }

        //验证账号的正确性
        import('@.Model.Account');
        $accountArr = Account::getAccountInfo($account, md5($password));
        if(empty($accountArr)){
            $result['code'] = 100;
            $result['msg'] = '账号或密码不正确';
            $this->ajaxReturn($result);
        }

        //账号信息记录到session
        session('accountSession', $accountArr);

        //验证通过
        $result['code'] = 200;
        $result['msg'] = '登陆成功';
        $this->ajaxReturn($result);
    }


    /**
     * 手机验证码注册
     */
    public function phoneVerifycodeReg(){
        //接收请求参数
        $param = [];
        $param['phone'] = I('post.phone');
        $param['code'] = I('post.code');
        $param['password'] = I('post.password');

        $result = [];
        if(checkEmpty($param)){
            $result['code'] = 400;
            $result['msg'] = '参数错误';
            $this->ajaxReturn($result);
        }

        $phone = $param['phone'];
        $code = $param['code'];
        $password = $param['password'];

        if(!isPhone($phone)){
            $result['code'] = 400;
            $result['msg'] = '手机号格式错误';
            $this->ajaxReturn($result);
        }

        $ret = $this->GeetestLibCheck();
        if($ret['status'] == 'fail'){
            $result['code'] = 500;
            $result['msg'] = '验证码验证失败';
            $this->ajaxReturn($result);
        }
        
        //验证账号的正确性
        import('@.Model.Account');
        $check = Account::checkAccount($phone);
        if($check){
            $result['code'] = 100;
            $result['msg'] = '手机号已存在';
            $this->ajaxReturn($result);
        }

        //判断短信验证码的正确性
        import('@.Model.PhoneCode');
        $nowtime = date('Y-m-d H:i:s');
        $codeArr = PhoneCode::checkCode($phone, $code, 1);
        if(empty($codeArr)){
            $result['code'] = 300;
            $result['msg'] = '验证码已失效，请重新获取';
            $this->ajaxReturn($result);
        }

        //添加账号新
        $ret = Account::add($phone, $password);
        if(!$ret){
            $result['code'] = 500;
            $result['msg'] = '账号注册失败';
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
        $result['msg'] = '注册成功';
        $this->ajaxReturn($result);
    }

    /**
     * 推出登陆
     */
    public function loginout(){
        session('accountSession', null);
        $this->redirect('Welcome/index');
    }

    /**
     * 微信扫码页面
     */
    public function WeChatLogin(){
        $state = md5("WeChatLogin_GavinTowrite");
        $_SESSION['state'] = $state;
        $paramArr = array(
            'appid'         =>  'wx5cabe40f2db5d1a2',
            'redirect_uri'  =>  urlencode('http://pano.56cy.com'),
            'response_type' =>  'code',
            'scope'         =>  'snsapi_login',
            'state'         =>  $state
        );
        $loginUrl = $this->urlUtil->combineUrl(self::WX_GET_CODE_URL, $paramArr);
        redirect($loginUrl);
    }


}