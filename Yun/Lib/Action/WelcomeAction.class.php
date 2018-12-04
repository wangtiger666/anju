<?php
// 本类由系统自动生成，仅供测试用途
class WelcomeAction extends PublicAction {

    protected $accountSession;
    protected $account;

    public function __construct()
    {
        parent::__construct();
        // var_dump(session('accountSession'));die;
        $this->accountSession = session('accountSession');
        if(empty($this->accountSession)){
            $this->account = '';
            $this->assign('accountSession', array());
        }else{
            $this->account = $this->accountSession['account'];
            $this->assign('accountSession', $this->accountSession);
        }
   

    }

    //首页
    public function index(){

        $this->display();
    }

    //主页面
    public function main(){

        $this->display();
    }

    public function mobile_main(){
        $this->display();
    }
}