<?php declare (strict_types=1);

namespace app\common\service;
use think\facade\Config;
use captcha\Captcha;
use think\facade\Request;
use tomato\helper\HelperArr;
class CaptchaService
{
    /**
     * @throws \think\Exception
     */
    public function __construct()
    {
        if(!Config::has('captcha'))
        {
            throw new \think\Exception('验证码配置获取失败', 10006);
        }
//        $form = Request::server('HTTP_REFERER');
//        $host = Request::server('HTTP_HOST');
//        if ($form && strpos($form, '://' . $host) != 4 && strpos($form, '://' . $host) != 5) {
//            throw new \think\Exception('非法调用验证码', 10007);
//        }
    }
    /**
     * 获取验证码配置
     * @param string $scene 个性化配置/场景配置
     * @return array
     * @throws \think\Exception
     */
    protected function getConfig(string $scene=''):array
    {
        $necessary=['length','codeSet','expire','fontSize','useCurve','useNoise','fontttf','imageH','imageW'];
        // 这里添加一个数组过滤  确保返回的是一个有效的一维数组
        if(empty($scene))
        {
           return array_intersect_key(Config::get('captcha'),array_flip($necessary));
        }
        if(!Config::has('captcha.'.$scene))
        {
            throw new \think\Exception('指定的验证码场景'.$scene.'配置不存在', 10008);
        }
        return Config::get('captcha.'.$scene);
    }



    /**
     * 生成验证码
    */
    public function create(string $scene='')
    {
        var_dump($this->getConfig($scene));die();
        $code = new Captcha($this->getConfig($scene));
        $code->createCodeImg();
        cache('checkcode', $code->getCode());
    }
    /**
     * 检验验证码
    */
    public function check()
    {

    }
}