<?php declare (strict_types=1);

namespace app\backend\controller;
use app\BaseController;
use app\common\service\CaptchaService;
use think\facade\Request;

class LoginController extends BaseController
{
    public function index()
    {
        if($this->request->isAjax())
        {
            $params=\request()->post();
            var_dump($params);
        }
        return view('/login/login',['captcha'=>build_uuid('manage_')]);

    }




    /**
     * 输出验证码
    */
    public function captcha()
    {
        $Captcha=new CaptchaService();
        return $Captcha->createCode(input('id'),'login');
    }
}