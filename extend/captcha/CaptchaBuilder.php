<?php declare (strict_types=1);

namespace captcha;
class CaptchaBuilder implements CaptchaBuilderInterface
{
    /**
     * 创建一个验证码
     */
    public function build($width, $height, $font, $fingerprint)
    {

    }

    /**
     * 保存验证码图片文件
     */
    public function save($filename, $quality)
    {

    }

    /**
     * 获取验证码图片内容
     */
    public function get($quality)
    {

    }

    /**
     * 输出验证码文件
     */
    public function output($quality)
    {

    }
}