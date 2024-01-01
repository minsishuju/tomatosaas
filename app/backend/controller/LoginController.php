<?php declare (strict_types=1);

namespace app\backend\controller;
use app\BaseController;
use app\common\service\CaptchaService;
use think\facade\Request;

class LoginController extends BaseController
{
    public function index()
    {
        return view('/login/login');
    }




    /**
     * 输出验证码
    */
    public function captcha()
    {
        ob_clean();
        $Captcha=new CaptchaService();
        return $Captcha->create();
    }
}