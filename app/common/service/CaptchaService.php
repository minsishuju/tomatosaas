<?php declare (strict_types=1);

namespace app\common\service;

use captcha\Captcha;
class CaptchaService
{
    //有待继续完善
    /**
     * 生成验证码
    */
    public function create()
    {
        $code = new Captcha();
        $code->height = 48;
        $code->width = 130;
        $code->fontsize = 18;
        $code->fontsize = 18;
        $code->charset = 'abcdefghkmnprtuvwxy23456789ABCDEFGHKMNPRTUVWXY';
        $code->createCodeImg();
        cache('checkcode', $code->getCode());
//        session('checkcode', $code->getCode());
    }
    /**
     * 检验验证码
    */
    public function check()
    {

    }
}