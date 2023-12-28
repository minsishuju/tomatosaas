<?php

use app\AppService;
use tomato\tenancy\TenancyService;
use tomato\multiapp\Service as MultiService;
// 系统服务定义文件
// 服务在完成全局初始化之后执行
return [
    MultiService::class,
//    TenancyService::class,
    AppService::class,

];
