<?php declare (strict_types=1);
/*
 * TomatoAdmin 基础开发框架[tp8]
 * index create at 2023/10/28 18:03
 * Author: 七彩枫叶 Email：424235748@qq.com
*/

namespace app\frontend\controller;


class IndexController
{

    public function index()
    {

        echo "这个是系统管理前台";

        echo app('http')->getName();
    }
}