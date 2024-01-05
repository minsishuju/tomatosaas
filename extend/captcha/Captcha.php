<?php declare (strict_types=1);
/**
 * 验证码生成
*/
namespace captcha;
class Captcha
{
    // 随机因子
    public string $charSet = 'ABCDEFGHKMNPRTUVWXY23456789';
    // 指定字体大小
    public int $fontSize = 18;
    // 验证码长度
    public int $length = 4;
    // 宽度
    public int $width = 130;
    // 高度
    public int $height = 50;
    // 指定的字体
    private string $font;
    // 指定字体颜色
    private $fontcolor;
    // 是否添加杂点
    private bool $useNoise;
    // 是否使用混淆曲线
    private bool $useCurve;
    // 验证码
    private string $code;
    // 图形资源句柄
    private $img;
    // 构造方法初始化
    public function __construct($config)
    {
        $this->charSet   = $this->configHasKey('charSet',$config)?$config['charSet']:$this->charSet;
        $this->fontSize  = $this->configHasKey('fontSize',$config)?$config['fontSize']:$this->fontSize;
        $this->length   = $this->configHasKey('length',$config)?$config['length']:$this->length;
        $this->width     = $this->configHasKey('width',$config)?$config['width']:$this->width;
        $this->height    = $this->configHasKey('height',$config)?$config['height']:$this->height;
        $this->useNoise  = $this->configHasKey('useNoise',$config)?$config['useNoise']:false;
        $this->useCurve  = $this->configHasKey('useCurve',$config)?$config['useCurve']:false;
        $this->font = dirname(__FILE__) . '/Font/captcha'.rand(0,5).'.ttf';
        $this->code='';
    }
    /**
     * 判断是不是存在指定的、有效的配置项
     * @param array $config 配置数组
     * @param string $key   配置项
     * @return bool
    */
    protected function configHasKey(string $key,array $config):bool
    {
        if(array_key_exists($key,$config) && !empty($config[$key]))
        {
            return true;
        }
        return false;
    }
    /**
     * 生成随机内容
    */
    private function createCode()
    {
        $this->charSet = str_shuffle($this->charSet);
        $_len = strlen($this->charSet) - 1;
        for ($i = 0; $i < $this->length; $i ++) {
            $this->code .= $this->charSet[mt_rand(0, $_len)];
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
        if($this->useCurve)
        {
            for ($i = 0; $i < 12; $i ++) {
                $color = imagecolorallocate($this->img, mt_rand(100, 200), mt_rand(100, 200), mt_rand(100, 200));
                imageline($this->img, mt_rand(0, $this->width), mt_rand(0, $this->height), mt_rand(0, $this->width), mt_rand(0, $this->height), $color);
            }
        }
        if($this->useNoise)
        {
            for ($i = 0; $i < 20; $i ++) {
                $color = imagecolorallocate($this->img, mt_rand(200, 255), mt_rand(200, 255), mt_rand(200, 255));
                imagestring($this->img, mt_rand(1, 5), mt_rand(0, $this->width), mt_rand(0, $this->height), '*', $color);
            }
        }

    }

    /**
     * 将随机内容写入到图片上
    */
    private function createFont()
    {
        $_x = ($this->width - 10) / $this->length;
        for ($i = 0; $i < $this->length; $i ++) {
            $this->fontcolor = imagecolorallocate($this->img, mt_rand(0, 100), mt_rand(0, 100), mt_rand(0, 100));
            imagettftext($this->img, $this->fontSize, mt_rand(- 20, 20), intval($_x * $i + $_x / 3), intval($this->height / 1.4), $this->fontcolor, $this->font, $this->code[$i]);
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