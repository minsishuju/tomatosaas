<?php declare (strict_types=1);
/*
 * TomatoAdmin 基础开发框架[tp8]
 * IndexController create at 2023/10/28 18:04
 * Author: 七彩枫叶 Email：424235748@qq.com
*/

namespace app\tenant\controller;

use app\Request;
use think\facade\Db;
class IndexController
{
        public function index()
    {
        echo "这个是租户管理后台";
        echo \request()->host().PHP_EOL;

        var_dump(request()->rootDomain());
        echo "<hr>".md5('123.'.request()->rootDomain());

    }
}