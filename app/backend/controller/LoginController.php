<?php declare (strict_types=1);

namespace app\backend\controller;
use app\BaseController;

class LoginController extends BaseController
{
    public function index()
    {
        return view('/login/old');
    }
}