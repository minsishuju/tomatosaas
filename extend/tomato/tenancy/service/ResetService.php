<?php declare (strict_types=1);
namespace tomato\tenancy\service;
use tenancy\model\Tenants;
/**
 * 重置租户配置信息
*/
class ResetService
{
    public static function reset(Tenants $tenant): void
    {
        // 重新配置数据库连接。
        self::resetDatabase($tenant);
    }

    /**
     * 重置数据库配置.
     *
     * @param $tenant
     */
    public static function resetDatabase($tenant): void
    {
        $connect = config('tenancy.database.tenant_connect');
        if ($connect == config('database.default')) {
            return;
        }
        $database = config('database');
        $database['default'] = $connect;
        $database['connections'][$connect]['database'] = $tenant[config('tenancy.database.db_name_field')];
        $database['connections'][$connect]['username'] = $tenant[config('tenancy.database.db_username_field')];
        $database['connections'][$connect]['password'] = $tenant[config('tenancy.database.db_password_field')];
        config(['database' => $database]);
    }



}