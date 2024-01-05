<?php declare (strict_types=1);
/*
 * TomatoAdmin 基础开发框架[tomatosaas]
 * Command create at 2024/1/5 14:29
 * Author: 七彩枫叶 Email：424235748@qq.com
*/

namespace app\common\command;

/**
 * 将需要执行的外部命令集中到这里进行管理
 */
class Command
{
    public static function execCommand($command)
    {
        //拼合命令 将当前位置移动到根目录
        $command='cd '.root_path().' && '.$command;

        $output=null;
        $result_code=null;
        exec($command,$output, $result_code);

        return ['code'=>$result_code,'content'=>$output];
    }
}