<?php
return [
    //验证码位数
    'length'   => 5,
    // 验证码字符集合
    'charSet'  => '2345678abcdefhijkmnpqrstuvwxyzABCDEFGHJKLMNPQRTUVWXY',
    // 验证码过期时间
    'expire'   => 1800,
    //验证码字符大小
    'fontSize' => 25,
    // 是否使用混淆曲线
    'useCurve' => true,
    //是否添加杂点
    'useNoise' => true,
    // 验证码图片高度
    'height'   => 40,
    // 验证码图片宽度
    'width'   => 100,
    //登录验证场景
    'login' => [
        'length'   => 4,
        'charSet'  => '1023456789',
        'fontSize' => 22,
        'useNoise' => false,
        'width'    => 130,
        'height'   => 50,
    ]

];