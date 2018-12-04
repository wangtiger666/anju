<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/10
 * Time: 14:06
 */

class CommomAccountAction extends Action
{

    protected $accountSession;
    protected $account;

    public function __construct()
    {
        parent::__construct();

        //测试代码
//        import('@.Model.Account');
//        $accountArr = Account::getAccountInfo('15260228937', md5(111111));
//        session('accountSession', $accountArr);
        //测试代码

        $this->accountSession = session('accountSession');
        if(empty($this->accountSession)){
            //session为空  返回登陆页
            redirect('/Welcome/index');
        }

        $this->account = $this->accountSession['account'];
        $this->assign('accountSession', $this->accountSession);
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
            $realip=trim(explode(',',$realip)[0],' ');
        }
        return $realip;
    }
}