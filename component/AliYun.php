<?php
/**
 * aliyun-mns的短信推送
 * time:2017-05-27
 * author:houpeng
 */
namespace xiaochengfu\mns\component;
require_once(dirname(dirname(__FILE__)).'/php_sdk/mns-autoloader.php');
use AliyunMNS\Client;
use AliyunMNS\Exception\MnsException;
use AliyunMNS\Model\BatchSmsAttributes;
use AliyunMNS\Model\MessageAttributes;
use AliyunMNS\Model\SmsAttributes;
use AliyunMNS\Requests\PublishMessageRequest;
use Yii;

class AliYun
{
    public $accessId = '';
    public $accessKey = '';
    public $endpoint = '';
    public $topicName = '';
    public $smsSignName = '';
    private $client;

    const YZM = 'SMS_xxxx';//常规验证码
    const ZDTZ = 'SMS_xxxx';//财务账单通知

    /**
     * AliYun constructor.
     * @param $config
     * 短信模板
     * 1.普通验证码模板-YZM = [
     *     'code' => 'xxx',
     *     'product' => 'xxx'
     * ]
     * 2.账单通知模板-ZDTZ = [
     *    'username'=> 'xx',
     *    'paytype' => '微信',
     *    'storename'=> 'xxx体验店',
     *    'money'=> '100',
     *    'ordersn'=>'2054984646498'
     * ]
     */
    public function __construct($config){
        /**
         * Step 1. 初始化Client
         */
        $this->endpoint = $config['endpoint'];
        $this->accessId = $config['accessId'];
        $this->accessKey = $config['accessKey'];
        $this->topicName = $config['topicName'];
        $this->smsSignName = $config['smsSignName'];
        $this->client = new Client($this->endpoint, $this->accessId, $this->accessKey);
        return $this->client;
    }

    /**
     * @param $mobile
     * @param array $smsParams
     * @param string $sms_type
     * @return array
     * 单一用户，发送短信
     */
    public function send($mobile,$smsParams=[],$sms_type = self::YZM ){
        $result = $this->format($sms_type,$smsParams);
        if($result !== true){
            return $result;
        }
        /**
         * Step 2. 获取主题引用
         */
        $topic = $this->client->getTopicRef($this->topicName);
        /**
         * Step 3. 生成SMS消息属性
         * 3.1 设置发送短信的签名（SMSSignName）和模板（SMSTemplateCode）
         */
        $smsAttributes = new SmsAttributes($this->smsSignName,$sms_type, $smsParams,$mobile);
        $messageAttributes = new MessageAttributes($smsAttributes);
        /**
         * Step 4. 设置SMS消息体（必须）
         *
         * 注：目前暂时不支持消息内容为空，需要指定消息内容，不为空即可。
         */
        $messageBody = "smsmessage";
        /**
         * Step 5. 发布SMS消息
         */
        $request = new PublishMessageRequest($messageBody, $messageAttributes);
        try {
            $res = $topic->publishMessage($request);
            if($res->isSucceed() == true && !empty($res->getMessageId())){
                return ['status'=>1,'info'=>'发送成功'];
            }else{
                return ['status'=>2,'info'=>'发送失败'];
            }
        } catch (MnsException $e) {
            Yii::warning('短信发送失败');
        }
        $this->client->deleteTopic($topic->getTopicName());
    }

    /**
     * @param array $mobiles
     * @param array $smsParams
     * @param string $sms_type
     * @return array
     * 批量发送短信
     */
    public function batchSend($mobiles=[],$smsParams=[],$sms_type = self::ZDTZ){
        $result = $this->format($sms_type,$smsParams);
        if($result !== true){
            return $result;
        }
        /**
         * Step 2. 获取主题引用
         */
        $topic = $this->client->getTopicRef($this->topicName);
        /**
         * Step 3. 生成SMS消息属性
         * 3.1 设置发送短信的签名（SMSSignName）和模板（SMSTemplateCode）
         * 3.2 （如果在短信模板中定义了参数）指定短信模板中对应参数的值
         */
        $batchSmsAttributes = new BatchSmsAttributes($this->smsSignName,$sms_type);
        foreach($mobiles as $value){
            $batchSmsAttributes->addReceiver($value, $smsParams);
        }
        $messageAttributes = new MessageAttributes(array($batchSmsAttributes));
        /**
         * Step 4. 设置SMS消息体（必须）
         *
         * 注：目前暂时不支持消息内容为空，需要指定消息内容，不为空即可。
         */
        $messageBody = "smsmessage";
        /**
         * Step 5. 发布SMS消息
         */
        $request = new PublishMessageRequest($messageBody, $messageAttributes);
        try {
            $res = $topic->publishMessage($request);
            if($res->isSucceed() == true && !empty($res->getMessageId())){
                return ['status'=>1,'info'=>'发送成功'];
            }else{
                return ['status'=>2,'info'=>'发送失败'];
            }
        } catch (MnsException $e) {
            Yii::warning('批量短信发送失败');
        }
        $this->client->deleteTopic($topic->getTopicName());
    }

    /**
     * @param $sms_type
     * @param $smsParams
     * @return array|bool
     * 模板格式检查
     */
    public function format($sms_type,$smsParams){
        $smsParams = array_keys($smsParams);
        $warning = ['status'=>2,'info'=>'模板格式不正确'];
        switch($sms_type){
            case self::YZM:
                if(in_array('code',$smsParams) && in_array('product',$smsParams)){
                   return true;
                }else{
                    return $warning;
                }
            case self::ZDTZ:
                if(in_array('username',$smsParams) && in_array('paytype',$smsParams) && in_array('storename',$smsParams) && in_array('money',$smsParams) && in_array('ordersn',$smsParams)){
                    return true;
                }else{
                    return $warning;
                }
            default :return $warning;
        }
    }
}