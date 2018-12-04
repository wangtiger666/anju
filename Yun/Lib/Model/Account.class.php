<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/10
 * Time: 13:13
 */

class Account
{

    /**
     * 添加账号信息
     * @param $phone
     * @param $password
     * @return mixed
     */
    public static function add($phone, $password){
        $nowtime = date('Y-m-d H:i:s');
        $accountData = [
            'account' => $phone,
            'password' => md5($password),
            'binding_phone' => $phone,
            'head_img' => '/Public/welcome/images/user-img-test.png',
            'create_time' => $nowtime,
            'modify_time' => $nowtime,
        ];
        $ret = M('account')->add($accountData);
        return $ret;
    }


    /**
     * 更新账号的信息
     * @param $account
     * @param $password
     * @return bool
     */
    public static function update($account, $data){
        $ret = M('account')->where(['account' => $account])->save($data);
        return $ret;
    }

    /**
     * 验证账号的正确性
     * @param $account
     * @return bool
     */
    public static function checkAccount($account){
        $count = M('account')->where(['account' => $account])->count('id');
        if($count <= 0){
            return false;
        }
        return true;
    }

    /**
     * 获取账号的信息
     * @param $account
     * @return bool
     */
    public static function getAccount($account){
        $account = M('account')->where(['account' => $account])->find();
        return $account;
    }

    /**
     * 获取账号的信息
     * @param $account
     * @param $password
     * @return bool
     */
    public static function getAccountInfo($account, $password){
        $account = M('account')->where(['account' => $account, 'password' => $password])->find();
        return $account;
    }
}