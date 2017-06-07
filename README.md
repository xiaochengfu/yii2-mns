阿里云短信推送，yii2-aliyun-mns
=======================
阿里云短信推送，yii2-aliyun-mns

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require xiaochengfu/yii2-aliyun-mns "dev-master"
```

or add

```
"xiaochengfu/yii2-aliyun-mns": "*"
```

to the require section of your `composer.json` file.


Usage
-----

1.在主配置文件中增加components
```php
'components' => [
     'mns'=>[
            'class'=> 'xiaochengfu\aliyunMns\component\MnsComponent',
            'accessId' => '',
            'accessKey' => '',
            'endpoint' => 'http://xxxx.mns.cn-hangzhou.aliyuncs.com/',
            'topicName' => '',
            'smsSignName' => '',
        ],
]
```
2.在程序中使用：
```
//单条发送
Yii::$app->mns->send('186********',$smsParams,MnsComponent::YZM);

//批量发送
$mobile = ['phone1','phone2','phone3'];
$result = Yii::$app->mns->batchSend($mobile,$smsParams,MnsComponent::YZM);
```
$smsParams与MnsComponent::YZM的类型要一致，这里可以根据自己的模板id自行定义规则！