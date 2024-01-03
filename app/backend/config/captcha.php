<?php
return [
    //验证码位数
    'length'   => 5,
    // 验证码字符集合
    'codeSet'  => '2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY',
    // 验证码过期时间
    'expire'   => 1800,
    //验证码字符大小
    'fontSize' => 25,
    // 是否使用混淆曲线
    'useCurve' => true,
    //是否添加杂点
    'useNoise' => true,
    // 验证码字体 不设置则随机
    'fontttf'  => '',
    // 验证码图片高度
    'imageH'   => 40,
    // 验证码图片宽度
    'imageW'   => 100,
    //登录验证场景
    'login' => [
        'length'   => 4,
        'codeSet'  => '1023456789',
        'fontSize' => 18,
        'useCurve' => false,
        'useNoise' => false,
        'imageW'   => 130,
        'imageH'   => 50,
    ]

];