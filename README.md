阿里云短信推送，yii2-aliyun-mns
=======================
阿里云短信推送，yii2-aliyun-mns

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer require --prefer-dist xiaochengfu/yii2-aliyun-mns "*"
```

or add

```
"xiaochengfu/yii2-aliyun-mns": "*"
```

to the require section of your `composer.json` file.


Usage
-----

在主配置文件中增加components
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