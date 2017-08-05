<?php
namespace xiaochengfu\mns;

use xiaochengfu\mns\component\AliYun;
use xiaochengfu\mns\component\YunPian;
use yii\web\BadRequestHttpException;

class Module extends \yii\base\Component
{
    public $config;
    public $aa;
    public $model;
    public $type;
   public function init()
   {
       $config = $this->config;
       if(isset($config['aliyun']) && $config['aliyun']['active']){
           $this->type = 'aliyun';
           $this->model = new AliYun($config['aliyun']);
       }elseif (isset($config['yunpian']) && $config['yunpian']['active']){
           $this->type = 'yunpian';
           $this->model = new YunPian($config['yunpian']);
       }else{
           throw new BadRequestHttpException('请至少配置一家短信服务商！');
       }
   }

    /**
     * @param $mobile
     * @param array $smsParams
     * @param string $sms_type
     * 拆分短信服务商，单发
     */
    public function send($mobile,$smsParams=[],$sms_type = AliYun::YZM){
        switch ($this->type){
            case 'aliyun':
                $this->model->send($mobile,$smsParams,$sms_type);break;
            case 'yunpian':
                $this->model->send($mobile,$smsParams);
        }
    }

    /**
     * @param array $mobiles
     * 格式：
     * $mobiles = [
     *  '186xxx',
     *  '183xxx'
     * ]
     * @param array $smsParams
     * @param string $sms_type
     * @throws BadRequestHttpException
     * 阿里云群发
     */
    public function batchSend($mobiles=[],$smsParams=[],$sms_type = AliYun::YZM){
        if($this->type == 'aliyun'){
            $this->model->batchSend($mobiles,$smsParams,$sms_type);
        }else{
            throw new BadRequestHttpException('云片目前不支持群发短信！');
        }
    }

}
