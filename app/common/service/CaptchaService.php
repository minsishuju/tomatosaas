<?php declare (strict_types=1);

namespace app\common\service;
use think\Exception;
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
        $form = Request::server('HTTP_REFERER');
        $host = Request::server('HTTP_HOST');
        if ($form && strpos($form, '://' . $host) != 4 && strpos($form, '://' . $host) != 5) {
            throw new \think\Exception('非法调用验证码', 10007);
        }
    }
    /**
     * 获取验证码配置
     * @param string $scene 个性化配置/场景配置
     * @return array
     * @throws \think\Exception
     */
    protected function getConfig(string $scene=''):array
    {
        $necessary=['length','charSet','expire','fontSize','useCurve','useNoise','height','width'];
        if(empty($scene))
        {
            //移除多余数据，没有指定场景的情况下移除场景的配置数据
            return array_intersect_key(Config::get('captcha'),array_flip($necessary));
        }
        if(!Config::has('captcha.'.$scene))
        {
            throw new \think\Exception('指定的验证码场景'.$scene.'配置不存在', 10008);
        }
        $scene_data = Config::get('captcha.'.$scene); //获取指定场景的配置
        //合并配置,指定场景中没有配置的采用默认值
        return array_merge(array_intersect_key(Config::get('captcha'),array_flip($necessary)),$scene_data);
    }



    /**
     * 生成验证码
    */
    public function create(string $scene='')
    {
        $config_data = $this->getConfig($scene);
        $code = new Captcha($config_data);
        $code->createCodeImg();
        cache('checkcode', $code->getCode(),$config_data['expire']);
    }
    /**
     * 检验验证码
    */
    public function check()
    {

    }
}