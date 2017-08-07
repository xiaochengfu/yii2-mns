阿里云、云片短信推送，yii2-mns
=======================
阿里云、云片短信推送

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
composer require xiaochengfu/yii2-mns "v1.0.1"
```

or add

```
"xiaochengfu/yii2-mns": "*"
```

to the require section of your `composer.json` file.


Usage
-----

1.在主配置文件中增加components
```php
'components' => [
      'mns'=>[
                 'class'=> 'xiaochengfu\mns\Module',
                 'config'=>[
                     'aliyun'=>[
                         'active'=>true, //true位开启，false为关闭
                         'accessId' => 'xxxx',
                         'accessKey' => 'xxxxxxxxx',
                         'endpoint' => 'http://xxxx.mns.cn-hangzhou.aliyuncs.com/',
                         'topicName' => 'xxx',
                         'smsSignName' => 'xxxx',
                     ],
                     'yunpian' => [
                         'active'=>false,
                         'apikey' => 'xxxxxx', // 请替换成您的apikey
                     ]
                 ]

             ],
]
```
2.在程序中使用：

当使用云片时：
```
$smsParams = '具体的消息内容';
```
当使用阿里云时：
```
$smsParams = [
    'code'=>'xxx',
    'product'=>'xxx'
];
```
单条发送：
```
Yii::$app->mns->send('186********',$smsParams,MnsComponent::YZM);
```

//批量发送
```
$mobile = ['phone1','phone2','phone3'];
$result = Yii::$app->mns->batchSend($mobile,$smsParams,MnsComponent::YZM);
```
$smsParams与MnsComponent::YZM的类型要一致，这里可以根据自己的模板id自行定义规则！
