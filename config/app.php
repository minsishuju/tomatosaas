<?php
// +----------------------------------------------------------------------
// | 应用设置
// +----------------------------------------------------------------------

return [
    // 应用地址
    'app_host'         => env('APP_HOST', ''),
    // 应用的命名空间
    'app_namespace'    => '',
    // 是否启用路由
    'with_route'       => true,
    // 默认应用
    'default_app'      => 'index',
    // 默认时区
    'default_timezone' => 'Asia/Shanghai',

    // 应用映射（自动多应用模式有效）
    'app_map'          => [
//        'admin'=>'tenant'  //将tenant应用映射为admin
        ],
    // 域名绑定（自动多应用模式有效）
    'domain_bind'      => [
        env('SYS_MANAGE', '')       => 'backend', //系统管理员后台
        env('SYS_INDEX', '')        => 'frontend', //系统前台
        env('TENANT_ADMIN', 'admin')=> 'tenant',  //租户管理后台
        env('TENANT_INDEX', '*')    => 'index',   //租户前台
    ],
    // 禁止URL访问的应用列表（自动多应用模式有效）
    'deny_app_list'    => ['common'],

    // 异常页面的模板文件
    'exception_tmpl'   => app()->getThinkPath() . 'tpl/think_exception.tpl',

    // 错误显示信息,非调试模式有效
    'error_message'    => '页面错误！请稍后再试～',
    // 显示错误信息
    'show_error_msg'   => true,
];
