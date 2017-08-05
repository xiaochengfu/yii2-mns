<?php
namespace xiaochengfu\mns\component;
use yii\base\Component;

class YunPian
{

    public $apikey;
    public $state;
    public $message;

    const SINGLE_SEND_URL = 'https://sms.yunpian.com/v2/sms/single_send.json';
    const BATCH_SEND_URL = 'https://sms.yunpian.com/v2/sms/batch_send.json';
    const MULTI_SEND_URL = 'https://sms.yunpian.com/v2/sms/multi_send.json';

    public function __construct($config){
        $this->apikey = $config['apikey'];
    }

    public function send($mobile, $content)
    {
        $data = [
            'apikey' => $this->apikey,
            'mobile' => $mobile,
            'text' => $content
        ];

        $result = $this->httpCurl(self::SINGLE_SEND_URL, $data);
        $json = json_decode($result);
        if ($json && is_object($json)) {
            $this->state = isset($json->code) ? (string) $json->code : null;
            $this->message = isset($json->msg) ? (string) $json->msg : null;
        }
        return $this->state === '0';
    }

    public function httpCurl($url, $data) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));

        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

}
