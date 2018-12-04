<?php
/**
 * Created by PhpStorm.
 * User: chao you 02
 * Date: 2018/9/19
 * Time: 11:27
 */
class UrlUtil{
    /**
     * 封装GET请求
     * @param $url      请求的地址（包含根地址和参数）
     * @return mixed    页面的输出结果
     */
    public function getContents($url){
        // 初始化连接
        $ch = curl_init($url);
        // 设置选项
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        // 执行请求
        $response = curl_exec($ch);
        // 关闭连接
        curl_close($ch);
        // 返回结果
        return $response;
    }

    /**
     * 拼接GET请求
     * @param $baseUrl  请求的跟地址
     * @param $paramArr 参数键值对
     * @return mixed    页面输出结果
     */
    public function get($baseUrl,$paramArr){
        // 拼接请求的URL地址
        $url = $this->combineUrl($baseUrl,$paramArr);
        // 发送get请求
        return $this->getContents($url);
    }

    /**
     * 拼接URL请求地址
     * @param $baseUrl  请求的跟地址
     * @param $paramArr 参数键值对
     * @return string   请求的完整地址
     */
    public function combineUrl($baseUrl,$paramArr){
        $url = $baseUrl;
        $join = "?";
        foreach ($paramArr as $name=>$val){
            $url .= $join . $name . "=" . $val;
            $join = "&";
        }
        return $url;
    }

    /**
     * 发送POST请求
     * @param $url      请求地址
     * @param $paramArr 请求参数
     * @return mixed    返回结果
     */
    public function post($url,$paramArr){
        // 初始化连接
        $ch = curl_init($url);
        // 设置选项
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_FOLLOWLOCATION,true);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_POST,true);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$paramArr);
        // 执行请求
        $response = curl_exec($ch);
        // 关闭连接
        curl_close($ch);
        // 返回结果
        return $response;
    }
}