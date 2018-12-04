<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/10
 * Time: 13:06
 */

class ApiService
{

    /**
     * 发送短信 参考文档 https://support.huaweicloud.com/api-msgsms/sms_05_0001.html
     */
    public static function sendPhoneMessage($phone, $code){
        vendor('guzzlehttp.guzzle.src.Client');
        vendor('guzzlehttp.psr7.src.functions');
        $client = new \GuzzleHttp\Client();

        try {
            $config = C('HUANWEI_YUN_DUANXIN');
            $sender = $config['CHANNEL_NO'];
            $receiver = $phone;

            $url = $config['URL'];
            $templateId = $config['TEST_TEMPLE_ID'];
            $templateParas = '["' . $code . '"]';
            $appKey = $config['APP_KEY'];
            $appSecret = $config['APP_SECRET'];


            $response = $client->request('POST', $url, [
                'form_params' => [
                    'from' => $sender,
                    'to' => $receiver,
                    'templateId' => $templateId,
                    'templateParas' => $templateParas,
                    //'statusCallback' => $statusCallback
                ],
                'headers' => [
                    'Authorization' => 'WSSE realm="SDP",profile="UsernameToken",type="Appkey"',
                    'X-WSSE' => self::buildWsseHeader($appKey, $appSecret)
                ],
                'verify' => false
            ]);
            
            return \GuzzleHttp\Psr7\str($response);
        } catch (Exception $e) {
            Log::record('请求错误'. $e->getMessage());
            return [];
        }
    }


    /**
     * 封装发送请求头部信息
     * @param $appKey
     * @param $appSecret
     * @return string
     */
    private static  function buildWsseHeader($appKey, $appSecret){
        $now = date('Y-m-d\TH:i:s\Z');
        $nonce = uniqid();
        $base64 = base64_encode(hash('sha256', ($nonce . $now . $appSecret)));
        return sprintf("UsernameToken Username=\"%s\",PasswordDigest=\"%s\",Nonce=\"%s\",Created=\"%s\"",
            $appKey, $base64, $nonce, $now);
    }
}