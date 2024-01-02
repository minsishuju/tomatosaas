<?php declare (strict_types=1);
/**
 * 验证码生成
*/
namespace captcha;
class Captcha
{
    // 随机因子
    public $charset = 'ABCDEFGHKMNPRTUVWXY23456789';

    // 指定字体大小
    public $fontsize = 18;

    // 验证码长度
    public $codelen = 4;

    // 宽度
    public $width = 130;

    // 高度
    public $height = 50;

    // 验证码
    private $code;

    // 图形资源句柄
    private $img;

    // 指定的字体
    private $font;

    // 指定字体颜色
    private $fontcolor;

    // 构造方法初始化
    public function __construct()
    {
        $this->font = dirname(__FILE__) . '/Font/captcha'.rand(0,5).'.ttf';
    }

    /**
     * 生成随机内容
    */
    private function createCode()
    {
        $this->charset = str_shuffle($this->charset);
        $_len = strlen($this->charset) - 1;
        for ($i = 0; $i < $this->codelen; $i ++) {
            $this->code .= $this->charset[mt_rand(0, $_len)];
        }
    }

    /**
     * 生成图片的背景
    */
    private function createBackground()
    {
        $this->img = imagecreatetruecolor($this->width, $this->height);
        $color = imagecolorallocate($this->img, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255));
        imagefilledrectangle($this->img, 0, $this->height, $this->width, 0, $color);
    }

    /**
     * 在背景上生成干扰内容
     * 随机线条 和 *
    */
    private function createLine()
    {
        for ($i = 0; $i < 12; $i ++) {
            $color = imagecolorallocate($this->img, mt_rand(100, 200), mt_rand(100, 200), mt_rand(100, 200));
            imageline($this->img, mt_rand(0, $this->width), mt_rand(0, $this->height), mt_rand(0, $this->width), mt_rand(0, $this->height), $color);
        }
        for ($i = 0; $i < 100; $i ++) {
            $color = imagecolorallocate($this->img, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255));
            imagestring($this->img, mt_rand(1, 5), mt_rand(0, $this->width), mt_rand(0, $this->height), '*', $color);
        }
    }

    /**
     * 将随机内容写入到图片上
    */
    private function createFont()
    {
        $_x = ($this->width - 10) / $this->codelen;
        for ($i = 0; $i < $this->codelen; $i ++) {
            $this->fontcolor = imagecolorallocate($this->img, mt_rand(0, 100), mt_rand(0, 100), mt_rand(0, 100));
            imagettftext($this->img, $this->fontsize, mt_rand(- 20, 20), intval($_x * $i + $_x / 3), intval($this->height / 1.4), $this->fontcolor, $this->font, $this->code[$i]);
        }
    }

    /**
     * 将图像对象输出为图片
     * 释放相关资源（内存）
    */
    private function outPut()
    {
        header('Content-type:image/png');
        imagepng($this->img);
        imagedestroy($this->img);
    }

    /**
     * 输出验证码图像
    */
    public function createCodeImg()
    {
        @ob_clean(); // 清理图片输出前内容，避免生成错误！
        $this->createBackground();
        $this->createCode();
        $this->createLine();
        $this->createFont();
        $this->outPut();
    }

    /**
     * 获取本次生成的内容
    */
    public function getCode(): string
    {
        return strtolower($this->code);
    }
}