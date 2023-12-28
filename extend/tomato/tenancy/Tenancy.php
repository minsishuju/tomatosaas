<?php declare (strict_types=1);
namespace tomato\tenancy;
use think\db\exception\DataNotFoundException;
use think\db\exception\DbException;
use think\db\exception\ModelNotFoundException;
use think\Exception;
use think\Model;
use tenancy\model\Tenants;
class Tenancy
{
    public Model|null|Tenants $tenant;

    public bool $initialized = false;

    /**
     * Initializes the tenant.
     * @param $tenant
     * @throws Exception
     */
    public function initialize($tenant): void
    {
        if (! \is_object($tenant)) {
            $subdomain = $tenant;
            $tenant = $this->find($subdomain);
            if (! $tenant) {
                throw new Exception('未找到相关资源', 404);
            }
        }

        if ($this->initialized && $this->tenant[$this->subDomainField] === $tenant[$this->subDomainField]) {
            return;
        }
        if ($this->initialized) {
            $this->end();
        }

        $this->tenant = $tenant;

        event(new InitializingTenancy($this));

        $this->initialized = true;

        event(new TenancyInitialized($this));
    }
    /**
     * @throws Exception
     */
    public function find($sub_domain,$root_domain)
    {
        $where=['sub_domain'=>$sub_domain,'root_domain'=>$root_domain];
        try {
            return $this->model()->where($where)->find();
        } catch (DataNotFoundException | ModelNotFoundException | DbException $e) {
            throw new Exception($e->getMessage(), 404);
        }
    }

    /**
     * 实例化数据模型
     * @return Model|Tenants
     */
    public function model(): Tenants|Model
    {
        return new Tenants();
    }
}