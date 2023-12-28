<?php declare (strict_types=1);
namespace tomato\tenancy;

use think\Exception;
use think\Service;
use tenancy\service\ResetService;
class TenancyService extends Service
{
    public function register()
    {
    }

    public function boot()
    {
        if (! $this->app->runningInConsole()) {
            if ($this->app->request->host(true)==config('tenancy.sys_manage_domain'))
            {
                return;
            }
            if ($this->app->request->host(true)==config('tenancy.sys_index_doamin'))
            {
                return;
            }

            $subDomain = $this->app->request->subDomain();

            if (empty($subDomain))
            {
                $this->validateTenancy($subDomain);
            }
            if ($subDomain) {
                $tenant = app(\tenancy\Tenancy::class)->find($subDomain,$this->app->request->rootDomain());
                $this->validateTenancy($tenant);
                ResetService::reset($tenant);
            }
        }
    }

    /**
     * @throws Exception
     */
    private function validateTenancy($tenant): void
    {
        if (! $tenant) {
            throw new Exception('未找到相关资源', 404);
        }

        if (! $tenant["status"]) {
            throw new Exception('当前租户已暂停使用', 403);
        }

        if ($tenant["expired_at"] <= time()) {
            throw new Exception('当前租户已过期', 403);
        }
    }
}