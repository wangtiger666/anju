<?php

/**
 * 账号中心相关接口
 * Class AccountAction
 */
class AccountAction extends CommomAccountAction
{
    public function __construct(){
        parent::__construct();
    }


    /**
     * 个人信息
     */
    public function info(){

        $this->display();
    }


    /**
     * 我的关注
     */
    public function myFollow(){
        import('@.Model.Region');
        $result['code'] = 200;
        $type = I('post.type');
        $list = M('house_resources_follow hrf')
                        ->join('left join pano_house_resources hr on hr.id = hrf.house_resouces_id')
                        ->where(['hrf.account' => $this->account, 'hrf.type' => $type,'hr.status'=>['in',['1','2','3']]])
                        ->order('hr.id desc')
                        ->select();
//        echo M()->getLastSql();exit();

        if (empty($list)){
            $result['code'] = 400;
            $result['msg'] = '暂无数据';
        }
        foreach ($list as $val){
            $info = Region::get_region_info($val['uptown_id'],'uptown');
            $area_name = Region::get_region_info($info['pid'],'district');
            $status = $val['status'];
            if ($status == 1){
                $msg = '出租中';
            }elseif($status == 2){
                $msg = '已出租';
            }else{
                $msg = '已预订';
            }
            $house_img = trim($val['house_img']);
            if (!isset($house_img) || $house_img == false){
                $house_img = '';
            }else{
                $house_img = C(HOST_DOMAIN) . $val['house_img'];
            }
            $data = [
                'house_no'=>$val['house_no'],
                'house_img'=>$house_img,
                'house_title'=>$val['house_title'],
                'house_type'=>Region::commonality_type($val['house_type']),
                'lease_type'=>Region::commonality_type($val['lease_type']),
                'house_direction'=>Region::commonality_type($val['house_direction']),
                'lease_money'=>$val['lease_money'],
                'pano_id'=>$val['pano_id'],
                'name'=>$info['name'],
                'area_name'=>$area_name['name'],
                'status'=>$msg,
                'pano_guid'=>$val['pano_guid']
            ];
            $result['follow'][] = $data;
        }
        $this->ajaxReturn($result);
    }


    /**
     * 我的消息
     */
    public function myMessage(){

        $list = M('system_msg')
                    ->where(['receive_account' => $this->account])
                    ->order('id desc')
                    ->select();
        $this->assign('list', $list);

        $this->display();
    }


    /**
     * 我的预订
     */
    public function myBook(){
        import('@.Model.Region');
        $result['code'] = 200;
        $type = I('post.type');
        $list = M('house_resources_book hrb')
            ->join('left join pano_house_resources hr on hr.id = hrb.house_resouces_id')
            ->where(['hrb.account' => $this->account,'hrb.type'=>$type])
            ->order('hr.id desc')
            ->select();
        if (empty($list)){
            $result['code'] = 400;
            $result['msg'] = '暂无数据';
        }
        foreach ($list as $val){
            $info = Region::get_region_info($val['uptown_id'],'uptown');
            $area_name = Region::get_region_info($info['pid'],'district');

            $house_img = trim($val['house_img']);
            if (!isset($house_img) || $house_img == false){
                $house_img = '';
            }else{
                $house_img = C(HOST_DOMAIN) . $val['house_img'];
            }

            $data = [
                'house_img'=>$house_img,
                'house_title'=>$val['house_title'],
                'house_type'=>Region::commonality_type($val['house_type']),
                'lease_type'=>Region::commonality_type($val['lease_type']),
                'house_direction'=>Region::commonality_type($val['house_direction']),
                'lease_money'=>$val['lease_money'],
                'pano_id'=>$val['pano_id'],
                'name'=>$info['name'],
                'area_name'=>$area_name['name'],
                'pano_guid'=>$val['pano_guid']
            ];
            $result['follow'][] = $data;
        }
        $this->ajaxReturn($result);
    }


    /**
     * 修改账号秘密
     */
    public function changePwd(){
        //接收请求参数
        $param = [];
        $param['password'] = I('post.password');
        $param['newpwd'] = I('post.newpwd');
        $param['newpwd_check'] = I('post.newpwd_check');

        $result = [];
        if(checkEmpty($param)){
            $result['code'] = 400;
            $result['msg'] = '参数错误';
            $this->ajaxReturn($result);
        }

        $password = $param['password'];
        $newpwd = $param['newpwd'];
        $newpwd_check = $param['newpwd_check'];
        $account = $this->accountSession['account'];

        if($newpwd != $newpwd_check){
            $result['code'] = 300;
            $result['msg'] = '新密码不一致';
            $this->ajaxReturn($result);
        }

        if($password === $newpwd){
            $result['code'] = 400;
            $result['msg'] = '新旧密码不能保持一致';
            $this->ajaxReturn($result);
        }

        if($this->accountSession['password'] != md5($password)){
            $result['code'] = 300;
            $result['msg'] = '旧密码错误';
            $this->ajaxReturn($result);
        }

        //修改密码
        import('@.Model.Account');
        $nowtime = date('Y-m-d H:i:s');
        $accountData = [
            'password' => md5($newpwd),
            'modify_time' => $nowtime,
        ];
        $ret = Account::update($account, $accountData);
        if(!$ret){
            $result['code'] = 100;
            $result['msg'] = '修改失败';
            $this->ajaxReturn($result);
        }

        $accountArr = Account::getAccount($account);
        $this->accountSession = $accountArr;
        session('accountSession', $accountArr);

        $result['code'] = 200;
        $result['msg'] = '修改成功';
        $this->ajaxReturn($result);
    }

    /**
     * 绑定手机
     */
    public function bindingPhone(){
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
        $account = $this->accountSession['account'];

        if(!isPhone($phone)){
            $result['code'] = 400;
            $result['msg'] = '手机号格式错误';
            $this->ajaxReturn($result);
        }

        //判断短信验证码的正确性
        import('@.Model.PhoneCode');
        $nowtime = date('Y-m-d H:i:s');
        $codeArr = PhoneCode::checkCode($phone, $code, 2);
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


        //修改绑定手机
        import('@.Model.Account');
        $nowtime = date('Y-m-d H:i:s');
        $accountData = [
            'binding_phone' => $phone,
            'modify_time' => $nowtime,
        ];
        $ret = Account::update($account, $accountData);
        if(!$ret){
            $result['code'] = 100;
            $result['msg'] = '修改失败';
            $this->ajaxReturn($result);
        }

        $accountArr = Account::getAccount($account);
        $this->accountSession = $accountArr;
        session('accountSession', $accountArr);

        $result['code'] = 200;
        $result['msg'] = '修改成功';
        $this->ajaxReturn($result);
    }

    /**
     * 解绑手机
     */
    public function removeBindingPhone(){
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
        $account = $this->accountSession['account'];

        if(!isPhone($phone)){
            $result['code'] = 400;
            $result['msg'] = '手机号格式错误';
            $this->ajaxReturn($result);
        }

        //判断短信验证码的正确性
        import('@.Model.PhoneCode');
        $nowtime = date('Y-m-d H:i:s');
        $codeArr = PhoneCode::checkCode($phone, $code, 5);
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

        //移出绑定手机
        import('@.Model.Account');
        $nowtime = date('Y-m-d H:i:s');
        $accountData = [
            'binding_phone' => '',
            'modify_time' => $nowtime,
        ];
        $ret = Account::update($account, $accountData);
        if(!$ret){
            $result['code'] = 100;
            $result['msg'] = '修改失败';
            $this->ajaxReturn($result);
        }

        $accountArr = Account::getAccount($account);
        $this->accountSession = $accountArr;
        session('accountSession', $accountArr);

        $result['code'] = 200;
        $result['msg'] = '修改成功';
        $this->ajaxReturn($result);
    }

    /**
     * 实名认证
     */
    public function realNameAuth(){
        //接收请求参数
        $param = [];
        $param['real_name'] = I('post.real_name');
        $param['id_card'] = I('post.id_card');

        $result = [];
        if(checkEmpty($param)){
            $result['code'] = 400;
            $result['msg'] = '参数错误';
            $this->ajaxReturn($result);
        }

        $real_name = $param['real_name'];
        $id_card = $param['id_card'];
        $account = $this->accountSession['account'];

        //实名认证
        import('@.Model.Account');
        $nowtime = date('Y-m-d H:i:s');
        $accountData = [
            'real_name' => $real_name,
            'id_card' => $id_card,
            'isid_card' => 1,
            'modify_time' => $nowtime,
        ];
        $ret = Account::update($account, $accountData);
        if(!$ret){
            $result['code'] = 100;
            $result['msg'] = '修改失败';
            $this->ajaxReturn($result);
        }

        $accountArr = Account::getAccount($account);
        $this->accountSession = $accountArr;
        session('accountSession', $accountArr);

        $result['code'] = 200;
        $result['msg'] = '修改成功';
        $this->ajaxReturn($result);
    }

    /**
     * 修改信息
     */
    public function updateInfo(){
        //接收请求参数
        $param = [];
        $nick_name = I('post.nick_name');
        $contact_phone = I('post.contact_phone');
        $contact_qq = I('post.contact_qq');
        $contact_weixin = I('post.contact_weixin');
        $contact_period = I('post.contact_period');
        $contact_time = I('post.contact_time');

        $result = [];

        $account = $this->accountSession['account'];

        //实名认证
        import('@.Model.Account');
        $nowtime = date('Y-m-d H:i:s');
        $accountData = [
            'nick_name' => $nick_name,
            'contact_phone' => $contact_phone,
            'contact_qq' => $contact_qq,
            'contact_weixin' => $contact_weixin,
            'contact_period' =>$contact_period,
            'contact_time' =>$contact_time,
            'modify_time' => $nowtime,
        ];
        $ret = Account::update($account, $accountData);
        if(!$ret){
            $result['code'] = 100;
            $result['msg'] = '修改失败';
            $this->ajaxReturn($result);
        }

        $accountArr = Account::getAccount($account);
        $this->accountSession = $accountArr;
        session('accountSession', $accountArr);

        $result['code'] = 200;
        $result['msg'] = '修改成功';
        $this->ajaxReturn($result);
    }


    /**
     * 浏览记录
     */
    public function history(){
        $house_resouces_id = I('post.house_resouces_id');

        $result = [];
        if(empty($house_resouces_id)){
            $result['code'] = 400;
            $result['msg'] = '参数错误';
            $this->ajaxReturn($result);
        }

        //判断房源是否存在
        $count = M('house_resouces')->where(['id' => $house_resouces_id])->count('id');
        if($count <= 0){
            $result['code'] = 400;
            $result['msg'] = '参数错误';
            $this->ajaxReturn($result);
        }

        //添加浏览记录
        $nowtime = date('Y-m-d H:i:s');
        $data = [
            'house_resouces_id' => $house_resouces_id,
            'account' => $this->account,
            'type' => 1,
            'create_time' => $nowtime,
        ];
        M('house_resouces_follow')->add($data);

        $result['code'] = 200;
        $result['msg'] = '成功';
        $this->ajaxReturn($result);
    }

    public function is_follow(){
        $house_resouces_id = I('post.house_resouces_id');

        $result = [];
        if(empty($house_resouces_id)){
            $result['code'] = 400;
            $result['msg'] = '参数错误';
            $this->ajaxReturn($result);
        }

        //判断房源是否存在
        $count = M('house_resources')->where(['id' => $house_resouces_id])->count('id');
        if($count <= 0){
            $result['code'] = 400;
            $result['msg'] = '参数错误';
            $this->ajaxReturn($result);
        }

        $count = M('house_resources_follow')
            ->where(['house_resouces_id' => $house_resouces_id, 'account' => $this->account,'type'=>'2'])
            ->count('id');
//        OutputDebugString_DB('follow');
        $result['count'] = $count;
        $result['code'] = 200;
        $this->ajaxReturn($result);
    }

    /**
     * 关注记录
     */
    public function follow(){
        $house_resouces_id = I('post.house_resouces_id');

        $result = [];
        if(empty($house_resouces_id)){
            $result['code'] = 400;
            $result['msg'] = '参数错误';
            $this->ajaxReturn($result);
        }

        //判断房源是否存在
        $count = M('house_resources')->where(['id' => $house_resouces_id])->count('id');
        if($count <= 0){
            $result['code'] = 400;
            $result['msg'] = '参数错误';
            $this->ajaxReturn($result);
        }

        //判断是否已经关注过
        $count = M('house_resources_follow')
                    ->where(['house_resouces_id' => $house_resouces_id, 'account' => $this->account,'type'=>'2'])
                    ->count('id');
//        OutputDebugString_DB('follow');
        if ($count > 0){
//            M('house_resources_follow')->where(['house_resouces_id'=>$house_resouces_id, 'account' => $this->account])->delete();
            $result['code'] = 401;
            $result['msg'] = '您已经关注过该房源,不能重复关注';
            $this->ajaxReturn($result);
        }else{
            $nowtime = date('Y-m-d H:i:s');
            $data = [
                'house_resouces_id' => $house_resouces_id,
                'account' => $this->account,
                'type' => '2',
                'create_time' => $nowtime,
            ];

            M('house_resources_follow')->add($data);
            $result['code'] = 200;
            $result['msg'] = '关注成功';
            $this->ajaxReturn($result);
        }


    }

    public function delete_follow(){
        $house_resouces_id = I('post.house_resouces_id');

        $result = [];
        if(empty($house_resouces_id)){
            $result['code'] = 400;
            $result['msg'] = '参数错误';
            $this->ajaxReturn($result);
        }

        //判断房源是否存在
        $count = M('house_resources')->where(['id' => $house_resouces_id])->count('id');
        if($count <= 0){
            $result['code'] = 400;
            $result['msg'] = '参数错误';
            $this->ajaxReturn($result);
        }

        //判断是否已经关注过
        $count = M('house_resources_follow')
            ->where(['house_resouces_id' => $house_resouces_id, 'account' => $this->account,'type'=>'2'])
            ->count('id');
//        OutputDebugString_DB('follow');
        if ($count > 0){
            M('house_resources_follow')->where(['house_resouces_id'=>$house_resouces_id, 'account' => $this->account])->delete();
            $result['code'] = 200;
            $result['msg'] = '取消关注成功';
            $this->ajaxReturn($result);
        }else{
            $result['code'] = 401;
            $result['msg'] = '您还未关注过该房源';
            $this->ajaxReturn($result);
        }
    }

    //上传头像
    public function uploadHeadPic(){
        $result = [];
        $pic = $_FILES['head_img']['tmp_name'];
        if(!empty($pic)){
            import('ORG.Net.UploadFile');
            $upload = new UploadFile();

            $upload->maxSize = 1048576 ;// 设置附件上传大小1M

            $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型

            $savePath  =  C('UPLOAD_FILE') . '/head_img/'; // 设置附件上传根目录

            $upload->savePath  =  $_SERVER['DOCUMENT_ROOT'] . $savePath; // 设置附件上传（子）目录

            // 上传文件
            if(!$upload->upload()) {// 上传错误提示错误信息
                Log::record($upload->getErrorMsg());
                $result['code'] = 400;
                $result['msg'] = '上传失败';
                $this->ajaxReturn($result);
            }else{// 上传成功

                $info = $upload->getUploadFileInfo();

                //保存用户上传的头像
                $uploaddir = $savePath . $info[0]['savename']; // 保存上传的照片根据需要自行组装

                $ret= M("account")->where(["account" => $this->account])->save([
                    "head_img" => $uploaddir
                ]);

                if(!$ret){
                    $result['code'] = 500;
                    $result['msg'] = '上传失败';
                    $this->ajaxReturn($result);
                }
                //接收旧头像信息
                $old_head_img = I('post.old_head_img');
                if(!empty($old_head_img)){
                    $index = strpos($old_head_img, C('HOST_DOMAIN'));
                    //截取旧文件的路径
                    $oldPath = substr($old_head_img, $index + strlen(C('HOST_DOMAIN')), strlen($old_head_img));
                    //拼装文件的项目路径
                    $path = $_SERVER['DOCUMENT_ROOT'] . $oldPath;
                    //文件存在就删除
                    if(file_exists($path)){
                        @unlink($path);
                    }
                }

                //更新用户session
                $this->accountSession['head_img'] = $uploaddir;
                session('accountSession', $this->accountSession);

                $result['code'] = 200;
                $result['msg'] = $uploaddir;
                $this->ajaxReturn($result);
            }
        }else{
            $result['code'] = 300;
            $result['msg'] = '上传失败';
            $this->ajaxReturn($result);
        }
    }
}