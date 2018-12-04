<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/10
 * Time: 13:13
 */

class PhoneCode
{

    /**
     * 添加短信记录
     * @param $phone
     * @param $code
     * @param $code_type
     * @param $ip
     */
    public static function add($phone, $code, $code_type, $ip){
        $nowtime = date('Y-m-d H:i:s');
        $codeData = [
            'phone' => $phone,
            'code' => $code,
            'code_type' => $code_type,
            'ip' => $ip,
            'create_time' => $nowtime,
            'modify_time' => $nowtime,
            'valid_time' => date('Y-m-d H:i:s', strtotime("+5 minute")),
        ];

        M('phone_code')->add($codeData);
    }

    /**
     * 判断短信验证码的正确性
     * @param $phone
     * @param $code
     * @param $code_type
     * @return []
     */
    public static function checkCode($phone, $code, $code_type){
        $nowtime = date('Y-m-d H:i:s');
        $codeArr = M('phone_code')
            ->where(['phone' => $phone, 'code_type' => $code_type, 'status' => 1, 'code' => $code])
            ->field('valid_time, id')->order('id desc')->find();

        if($codeArr['valid_time'] < $nowtime){
           return [];
        }
        return $codeArr;
    }

    /**
     * 更新
     * @param $id
     * @param $code
     * @param $code_type
     * @return []
     */
    public static function update($id, $data){
        M('phone_code')->where(['id' => $id])->save($data);
    }
}