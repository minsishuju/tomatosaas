<?php declare (strict_types = 1);
namespace tomato\multiapp;
/*
 * TomatoAdmin 基础开发框架[tomatosaas]
 * MultiApp create at 2023/11/1 17:52
 * Author: 七彩枫叶 Email：424235748@qq.com
*/

use think\Service as BaseService;

class Service extends BaseService
{
    public function boot()
    {
        $this->app->event->listen('HttpRun', function () {
            $this->app->middleware->add(MultiApp::class);
        });

        $this->commands([
            'build' => command\Build::class,
            'clear' => command\Clear::class,
        ]);

        $this->app->bind([
            'think\route\Url' => Url::class,
        ]);
    }
}
