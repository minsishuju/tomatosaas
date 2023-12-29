<?php

use think\migration\Migrator;
use think\migration\db\Column;
use Phinx\Db\Adapter\MysqlAdapter;

class InstallSystem extends Migrator
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function change()
    {
        $this->managers();
        $this->managerGroup();
        $this->managerGroupAccess();
        $this->managerLog();
        $this->users();
        $this->userGroup();
        $this->userRule();
        $this->userMoneyLog();
        $this->userScoreLog();
        $this->tenancys();
        $this->attachments();
        $this->captchas();
        $this->area();
        $this->config();
    }


    /**
     * 管理员表 managers
    */
    public function managers()
    {
        if (!$this->hasTable('managers')) {
            $table = $this->table('managers', [
                'id'          => false,
                'comment'     => '管理员表',
                'row_format'  => 'DYNAMIC',
                'primary_key' => 'id',
                'collation'   => 'utf8mb4_unicode_ci',
            ]);
            $table->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('username', 'string', ['limit' => 20, 'default' => '', 'comment' => '用户名', 'null' => false])
                ->addColumn('password', 'string', ['limit' => 70, 'default' => '', 'comment' => '密码', 'null' => false])
                ->addColumn('salt', 'string', ['limit' => 30, 'default' => '', 'comment' => '密码盐', 'null' => false])
                ->addColumn('nickname', 'string', ['limit' => 50, 'default' => '', 'comment' => '昵称', 'null' => false])
                ->addColumn('avatar', 'string', ['limit' => 255, 'default' => '', 'comment' => '头像', 'null' => false])
                ->addColumn('email', 'string', ['limit' => 50, 'default' => '', 'comment' => '邮箱', 'null' => false])
                ->addColumn('mobile', 'string', ['limit' => 11, 'default' => '', 'comment' => '手机', 'null' => false])
                ->addColumn('last_login_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '上次登录时间'])
                ->addColumn('last_login_ip', 'string', ['limit' => 50, 'default' => '', 'comment' => '上次登录IP', 'null' => false])
                ->addColumn('login_failure', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_TINY, 'default' => 0, 'comment' => '登录失败次数', 'null' => false])
                ->addColumn('motto', 'string', ['limit' => 255, 'default' => '', 'comment' => '签名', 'null' => false])
                ->addColumn('status', 'enum', ['values' => '0,1', 'default' => '1', 'comment' => '状态:0=禁用,1=启用', 'null' => false])
                ->addColumn('create_time', 'datetime', [ 'null' => false, 'default' => null, 'comment' => '创建时间'])
                ->addColumn('update_time', 'datetime', [ 'null' => false, 'default' => null, 'comment' => '更新时间'])
                ->addColumn('delete_time', 'datetime', [ 'null' => false, 'default' => null, 'comment' => '删除时间'])
                ->addIndex(['username'], [
                    'unique' => true,
                ])
                ->create();
        }
    }
    /**
     * 管理分组表 manager_group
    */
    public function managerGroup()
    {
        if (!$this->hasTable('manager_group')) {
            $table = $this->table('manager_group', [
                'id'          => false,
                'comment'     => '管理分组表',
                'row_format'  => 'DYNAMIC',
                'primary_key' => 'id',
                'collation'   => 'utf8mb4_unicode_ci',
            ]);
            $table->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('pid', 'integer', ['comment' => '上级分组', 'default' => 0, 'signed' => false, 'null' => false])
                ->addColumn('name', 'string', ['limit' => 100, 'default' => '', 'comment' => '组名', 'null' => false])
                ->addColumn('rules', 'text', ['null' => true, 'default' => null, 'comment' => '权限规则ID'])
                ->addColumn('status', 'enum', ['values' => '0,1', 'default' => '1', 'comment' => '状态:0=禁用,1=启用', 'null' => false])
                ->addColumn('create_time', 'datetime', [ 'null' => false, 'default' => null, 'comment' => '创建时间'])
                ->addColumn('update_time', 'datetime', [ 'null' => false, 'default' => null, 'comment' => '更新时间'])
                ->addColumn('delete_time', 'datetime', [ 'null' => false, 'default' => null, 'comment' => '删除时间'])
                ->create();
        }
    }
    /**
     * 管理分组映射表 manager_group_access
    */
    public function managerGroupAccess()
    {
        if (!$this->hasTable('manager_group_access')) {
            $table = $this->table('manager_group_access', [
                'id'         => false,
                'comment'    => '管理分组映射表',
                'row_format' => 'DYNAMIC',
                'collation'  => 'utf8mb4_unicode_ci',
            ]);
            $table->addColumn('uid', 'integer', ['comment' => '管理员ID', 'signed' => false, 'null' => false])
                ->addColumn('group_id', 'integer', ['comment' => '分组ID', 'signed' => false, 'null' => false])
                ->addIndex(['uid'], [
                    'type' => 'BTREE',
                ])
                ->addIndex(['group_id'], [
                    'type' => 'BTREE',
                ])
                ->create();
        }
    }
    /**
     * 管理员日志表 manager_log
    */
    public function managerLog()
    {
        if (!$this->hasTable('manager_log')) {
            $table = $this->table('manager_log', [
                'id'          => false,
                'comment'     => '管理员日志表',
                'row_format'  => 'DYNAMIC',
                'primary_key' => 'id',
                'collation'   => 'utf8mb4_unicode_ci',
            ]);
            $table->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('manager_id', 'integer', ['comment' => '管理员ID', 'default' => 0, 'signed' => false, 'null' => false])
                ->addColumn('username', 'string', ['limit' => 20, 'default' => '', 'comment' => '管理员用户名', 'null' => false])
                ->addColumn('url', 'string', ['limit' => 1500, 'default' => '', 'comment' => '操作Url', 'null' => false])
                ->addColumn('title', 'string', ['limit' => 100, 'default' => '', 'comment' => '日志标题', 'null' => false])
                ->addColumn('data', 'text', ['limit' => MysqlAdapter::TEXT_LONG, 'null' => true, 'default' => null, 'comment' => '请求数据'])
                ->addColumn('ip', 'string', ['limit' => 50, 'default' => '', 'comment' => 'IP', 'null' => false])
                ->addColumn('useragent', 'string', ['limit' => 255, 'default' => '', 'comment' => 'User-Agent', 'null' => false])
                ->addColumn('create_time', 'datetime', [ 'null' => false, 'default' => null, 'comment' => '创建时间'])
                ->create();
        }
    }
    /**
     * 用户表 users
    */
    public function users()
    {
        if (!$this->hasTable('users')) {
            $table = $this->table('users', [
                'id'          => false,
                'comment'     => '用户信息表',
                'row_format'  => 'DYNAMIC',
                'primary_key' => 'id',
                'collation'   => 'utf8mb4_unicode_ci',
            ]);
            $table->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('group_id', 'integer', ['comment' => '分组ID', 'default' => 0, 'signed' => false, 'null' => false])
                ->addColumn('username', 'string', ['limit' => 32, 'default' => '', 'comment' => '用户名', 'null' => false])
                ->addColumn('password', 'string', ['limit' => 70, 'default' => '', 'comment' => '密码', 'null' => false])
                ->addColumn('salt', 'string', ['limit' => 30, 'default' => '', 'comment' => '密码盐', 'null' => false])
                ->addColumn('nickname', 'string', ['limit' => 50, 'default' => '', 'comment' => '昵称', 'null' => false])
                ->addColumn('email', 'string', ['limit' => 50, 'default' => '', 'comment' => '邮箱', 'null' => false])
                ->addColumn('mobile', 'string', ['limit' => 11, 'default' => '', 'comment' => '手机', 'null' => false])
                ->addColumn('avatar', 'string', ['limit' => 255, 'default' => '', 'comment' => '头像', 'null' => false])
                ->addColumn('gender', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_TINY, 'default' => 0, 'comment' => '性别:0=未知,1=男,2=女', 'null' => false])
                ->addColumn('birthday', 'date', ['null' => true, 'default' => null, 'comment' => '生日'])
                ->addColumn('money', 'integer', ['comment' => '余额', 'default' => 0, 'signed' => false, 'null' => false])
                ->addColumn('score', 'integer', ['comment' => '积分', 'default' => 0, 'signed' => false, 'null' => false])
                ->addColumn('motto', 'string', ['limit' => 255, 'default' => '', 'comment' => '签名', 'null' => false])
                ->addColumn('last_login_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '上次登录时间'])
                ->addColumn('last_login_ip', 'string', ['limit' => 50, 'default' => '', 'comment' => '上次登录IP', 'null' => false])
                ->addColumn('login_failure', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_TINY, 'default' => 0, 'comment' => '登录失败次数', 'null' => false])
                ->addColumn('join_ip', 'string', ['limit' => 50, 'default' => '', 'comment' => '加入IP', 'null' => false])
                ->addColumn('activate_time', 'datetime', ['null' => true, 'default' => null, 'comment' => '激活时间'])
                ->addColumn('status', 'enum', ['values' => '0,1,2', 'default' => '0', 'comment' => '用户状态 状态:0=待激活,1=已激活,2=已认证', 'null' => false])
                ->addColumn('create_time', 'datetime', [ 'null' => false, 'default' => null, 'comment' => '创建时间'])
                ->addColumn('update_time', 'datetime', [ 'null' => false, 'default' => null, 'comment' => '更新时间'])
                ->addColumn('delete_time', 'datetime', [ 'null' => false, 'default' => null, 'comment' => '删除时间'])
                ->addIndex(['username'], [
                    'unique' => true,
                ])
                ->addIndex(['email'], [
                    'unique' => true,
                ])
                ->addIndex(['mobile'], [
                    'unique' => true,
                ])
                ->create();
        }
    }
    /**
     * 会员组表 user_group
    */
    public function userGroup()
    {
        if (!$this->hasTable('user_group')) {
            $table = $this->table('user_group', [
                'id'          => false,
                'comment'     => '会员组表',
                'row_format'  => 'DYNAMIC',
                'primary_key' => 'id',
                'collation'   => 'utf8mb4_unicode_ci',
            ]);
            $table->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('name', 'string', ['limit' => 50, 'default' => '', 'comment' => '组名', 'null' => false])
                ->addColumn('rules', 'text', ['null' => true, 'default' => null, 'comment' => '权限节点'])
                ->addColumn('status', 'enum', ['values' => '0,1', 'default' => '1', 'comment' => '状态:0=禁用,1=启用', 'null' => false])
                ->addColumn('create_time', 'datetime', [ 'null' => false, 'default' => null, 'comment' => '创建时间'])
                ->addColumn('update_time', 'datetime', [ 'null' => false, 'default' => null, 'comment' => '更新时间'])
                ->addColumn('delete_time', 'datetime', [ 'null' => false, 'default' => null, 'comment' => '删除时间'])
                ->create();
        }
    }
    /**
     * 会员菜单权限规则表 user_rule
    */
    public function userRule()
    {
        if (!$this->hasTable('user_rule')) {
            $table = $this->table('user_rule', [
                'id'          => false,
                'comment'     => '会员菜单权限规则表',
                'row_format'  => 'DYNAMIC',
                'primary_key' => 'id',
                'collation'   => 'utf8mb4_unicode_ci',
            ]);
            $table->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('pid', 'integer', ['comment' => '上级菜单', 'default' => 0, 'signed' => false, 'null' => false])
                ->addColumn('type', 'enum', ['values' => 'route,menu_dir,menu,nav_user_menu,nav,button', 'default' => 'menu', 'comment' => '类型:route=路由,menu_dir=菜单目录,menu=菜单项,nav_user_menu=顶栏会员菜单下拉项,nav=顶栏菜单项,button=页面按钮', 'null' => false])
                ->addColumn('title', 'string', ['limit' => 50, 'default' => '', 'comment' => '标题', 'null' => false])
                ->addColumn('name', 'string', ['limit' => 50, 'default' => '', 'comment' => '规则名称', 'null' => false])
                ->addColumn('path', 'string', ['limit' => 100, 'default' => '', 'comment' => '路由路径', 'null' => false])
                ->addColumn('icon', 'string', ['limit' => 50, 'default' => '', 'comment' => '图标', 'null' => false])
                ->addColumn('menu_type', 'enum', ['values' => 'tab,link,iframe', 'default' => 'tab', 'comment' => '菜单类型:tab=选项卡,link=链接,iframe=Iframe', 'null' => false])
                ->addColumn('url', 'string', ['limit' => 255, 'default' => '', 'comment' => 'Url', 'null' => false])
                ->addColumn('component', 'string', ['limit' => 100, 'default' => '', 'comment' => '组件路径', 'null' => false])
                ->addColumn('no_login_valid', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_TINY, 'default' => 0, 'comment' => '未登录有效:0=否,1=是', 'null' => false])
                ->addColumn('extend', 'enum', ['values' => 'none,add_rules_only,add_menu_only', 'default' => 'none', 'comment' => '扩展属性:none=无,add_rules_only=只添加为路由,add_menu_only=只添加为菜单', 'null' => false])
                ->addColumn('remark', 'string', ['limit' => 255, 'default' => '', 'comment' => '备注', 'null' => false])
                ->addColumn('weigh', 'integer', ['comment' => '权重', 'default' => 0, 'null' => false])
                ->addColumn('status', 'enum', ['values' => '0,1', 'default' => '1', 'comment' => '状态:0=禁用,1=启用', 'null' => false])
                ->addColumn('create_time', 'datetime', [ 'null' => false, 'default' => null, 'comment' => '创建时间'])
                ->addColumn('update_time', 'datetime', [ 'null' => false, 'default' => null, 'comment' => '更新时间'])
                ->addColumn('delete_time', 'datetime', [ 'null' => false, 'default' => null, 'comment' => '删除时间'])
                ->addIndex(['pid'], [
                    'type' => 'BTREE',
                ])
                ->create();
        }
    }
    /**
     * 会员余额变动表 user_money_log
    */
    public function userMoneyLog()
    {
        if (!$this->hasTable('user_money_log')) {
            $table = $this->table('user_money_log', [
                'id'          => false,
                'comment'     => '会员余额变动表',
                'row_format'  => 'DYNAMIC',
                'primary_key' => 'id',
                'collation'   => 'utf8mb4_unicode_ci',
            ]);
            $table->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('user_id', 'integer', ['comment' => '会员ID', 'default' => 0, 'signed' => false, 'null' => false])
                ->addColumn('money', 'integer', ['comment' => '变更余额', 'default' => 0, 'null' => false])
                ->addColumn('before', 'integer', ['comment' => '变更前余额', 'default' => 0, 'null' => false])
                ->addColumn('after', 'integer', ['comment' => '变更后余额', 'default' => 0, 'null' => false])
                ->addColumn('memo', 'string', ['limit' => 255, 'default' => '', 'comment' => '备注', 'null' => false])
                ->addColumn('create_time', 'datetime', [ 'null' => false, 'default' => null, 'comment' => '创建时间'])
                ->create();
        }
    }
    /**
     * 会员积分变动表 user_score_log
    */
    public function userScoreLog()
    {
        if (!$this->hasTable('user_score_log')) {
            $table = $this->table('user_score_log', [
                'id'          => false,
                'comment'     => '会员积分变动表',
                'row_format'  => 'DYNAMIC',
                'primary_key' => 'id',
                'collation'   => 'utf8mb4_unicode_ci',
            ]);
            $table->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('user_id', 'integer', ['comment' => '会员ID', 'default' => 0, 'signed' => false, 'null' => false])
                ->addColumn('score', 'integer', ['comment' => '变更积分', 'default' => 0, 'null' => false])
                ->addColumn('before', 'integer', ['comment' => '变更前积分', 'default' => 0, 'null' => false])
                ->addColumn('after', 'integer', ['comment' => '变更后积分', 'default' => 0, 'null' => false])
                ->addColumn('memo', 'string', ['limit' => 255, 'default' => '', 'comment' => '备注', 'null' => false])
                ->addColumn('create_time', 'datetime', [ 'null' => false, 'default' => null, 'comment' => '创建时间'])
                ->create();
        }
    }
    /**
     * 租户信息表 tenancys
     */
    public function tenancys()
    {
        if (!$this->hasTable('tenancys')) {
            $table = $this->table('tenancys', [
                'id'          => false,
                'comment'     => '租户信息表',
                'row_format'  => 'DYNAMIC',
                'primary_key' => 'id',
                'collation'   => 'utf8mb4_unicode_ci',
            ]);
            $table->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('user_id', 'integer', ['comment' => '用户ID', 'signed' => false,'null' => false])
                ->addColumn('tenant_name', 'string', ['limit' => 120, 'default' => '', 'comment' => '租户名称', 'null' => false])
                ->addColumn('sub_domain', 'string', ['limit' => 100, 'default' => '', 'comment' => '子域名', 'null' => true])
                ->addColumn('root_domain', 'string', ['limit' => 100, 'default' => '', 'comment' => '根域名', 'null' => true])
                ->addColumn('db_url', 'string', ['limit' => 100, 'default' => '', 'comment' => '租户数据库地址', 'null' => true])
                ->addColumn('db_port', 'integer', ['limit' => 10, 'default' => '3306', 'comment' => '租户数据库端口', 'null' => true])
                ->addColumn('db_name', 'string', ['limit' => 50, 'default' => '', 'comment' => '租户数据库名称', 'null' => true])
                ->addColumn('db_user', 'string', ['limit' => 50, 'default' => '', 'comment' => '租户数据库用户', 'null' => true])
                ->addColumn('db_password', 'string', ['limit' => 50, 'default' => '', 'comment' => '租户数据库密码', 'null' => true])
                ->addColumn('avatar', 'string', ['limit' => 255, 'default' => '', 'comment' => '头像', 'null' => true])
                ->addColumn('email', 'string', ['limit' => 50, 'default' => '', 'comment' => '邮箱', 'null' => true])
                ->addColumn('mobile', 'string', ['limit' => 11, 'default' => '', 'comment' => '手机', 'null' => false])
                ->addColumn('status', 'enum', ['values' => '0,1', 'default' => '1', 'comment' => '租户状态 状态:0=禁用,1=启用', 'null' => false])
                ->addColumn('expired_time', 'integer', ['limit' => 10, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '到期时间'])
                ->addColumn('create_time', 'integer', ['limit' => 10, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '更新时间'])
                ->addColumn('update_time', 'integer', ['limit' => 10, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '创建时间'])
                ->addColumn('delete_time', 'integer', ['limit' => 10, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '删除时间'])
                ->addIndex(['tenant_name'], [
                    'unique' => true,
                ])
                ->create();
        }
    }
    /**
     * 附件表 attachments
    */
    public function attachments()
    {
        if (!$this->hasTable('attachments')) {
            $table = $this->table('attachments', [
                'id'          => false,
                'comment'     => '附件表',
                'row_format'  => 'DYNAMIC',
                'primary_key' => 'id',
                'collation'   => 'utf8mb4_unicode_ci',
            ]);
            $table->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('topic', 'string', ['limit' => 20, 'default' => '', 'comment' => '细目', 'null' => false])
                ->addColumn('manage_id', 'integer', ['comment' => '上传管理员ID', 'default' => 0, 'signed' => false, 'null' => false])
                ->addColumn('user_id', 'integer', ['comment' => '上传用户ID', 'default' => 0, 'signed' => false, 'null' => false])
                ->addColumn('url', 'string', ['limit' => 255, 'default' => '', 'comment' => '物理路径', 'null' => false])
                ->addColumn('width', 'integer', ['comment' => '宽度', 'default' => 0, 'signed' => false, 'null' => false])
                ->addColumn('height', 'integer', ['comment' => '高度', 'default' => 0, 'signed' => false, 'null' => false])
                ->addColumn('name', 'string', ['limit' => 100, 'default' => '', 'comment' => '原始名称', 'null' => false])
                ->addColumn('size', 'integer', ['comment' => '大小', 'default' => 0, 'signed' => false, 'null' => false])
                ->addColumn('mimetype', 'string', ['limit' => 100, 'default' => '', 'comment' => 'mime类型', 'null' => false])
                ->addColumn('quote', 'integer', ['comment' => '上传(引用)次数', 'default' => 0, 'signed' => false, 'null' => false])
                ->addColumn('storage', 'string', ['limit' => 50, 'default' => '', 'comment' => '存储方式', 'null' => false])
                ->addColumn('sha1', 'string', ['limit' => 40, 'default' => '', 'comment' => 'sha1编码', 'null' => false])
                ->addColumn('create_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '创建时间'])
                ->addColumn('last_upload_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '最后上传时间'])
                ->create();
        }
    }
    /**
     * 验证码信息表 captchas
    */
    public function captchas()
    {
        if (!$this->hasTable('captchas')) {
            $table = $this->table('captchas', [
                'id'          => false,
                'comment'     => '验证码表',
                'row_format'  => 'DYNAMIC',
                'primary_key' => 'key',
                'collation'   => 'utf8mb4_unicode_ci',
            ]);
            $table->addColumn('key', 'string', ['limit' => 32, 'default' => '', 'comment' => '验证码Key', 'null' => false])
                ->addColumn('code', 'string', ['limit' => 32, 'default' => '', 'comment' => '验证码(加密后)', 'null' => false])
                ->addColumn('captcha', 'text', ['limit' => MysqlAdapter::TEXT_LONG, 'null' => true, 'default' => null, 'comment' => '验证码数据'])
                ->addColumn('create_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '创建时间'])
                ->addColumn('expire_time', 'biginteger', ['limit' => 16, 'signed' => false, 'null' => true, 'default' => null, 'comment' => '过期时间'])
                ->create();
        }
    }
    /**
     * 省份地区表 area
    */
    public function area()
    {
        if (!$this->hasTable('area')) {
            $table = $this->table('area', [
                'id'          => false,
                'comment'     => '省份地区表',
                'row_format'  => 'DYNAMIC',
                'primary_key' => 'id',
                'collation'   => 'utf8mb4_unicode_ci',
            ]);
            $table->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('pid', 'integer', ['comment' => '父id', 'null' => true, 'default' => null, 'signed' => false])
                ->addColumn('shortname', 'string', ['limit' => 100, 'null' => true, 'default' => null, 'comment' => '简称'])
                ->addColumn('name', 'string', ['limit' => 100, 'null' => true, 'default' => null, 'comment' => '名称'])
                ->addColumn('mergename', 'string', ['limit' => 255, 'null' => true, 'default' => null, 'comment' => '全称'])
                ->addColumn('level', 'enum', ['values' => '1,2,3,4', 'default' => '1', 'comment' => '层级:1=省,2=市,3=区/县,4=乡镇/街道', 'null' => false])
                ->addColumn('pinyin', 'string', ['limit' => 100, 'null' => true, 'default' => null, 'comment' => '拼音'])
                ->addColumn('code', 'string', ['limit' => 100, 'null' => true, 'default' => null, 'comment' => '长途区号'])
                ->addColumn('zip', 'string', ['limit' => 100, 'null' => true, 'default' => null, 'comment' => '邮编'])
                ->addColumn('first', 'string', ['limit' => 50, 'null' => true, 'default' => null, 'comment' => '首字母'])
                ->addColumn('lng', 'string', ['limit' => 50, 'null' => true, 'default' => null, 'comment' => '经度'])
                ->addColumn('lat', 'string', ['limit' => 50, 'null' => true, 'default' => null, 'comment' => '纬度'])
                ->addIndex(['pid'], [
                    'type' => 'BTREE',
                ])
                ->create();
        }
    }

    public function config()
    {
        if (!$this->hasTable('config')) {
            $table = $this->table('config', [
                'id'          => false,
                'comment'     => '系统配置',
                'row_format'  => 'DYNAMIC',
                'primary_key' => 'id',
                'collation'   => 'utf8mb4_unicode_ci',
            ]);
            $table->addColumn('id', 'integer', ['comment' => 'ID', 'signed' => false, 'identity' => true, 'null' => false])
                ->addColumn('name', 'string', ['limit' => 30, 'default' => '', 'comment' => '变量名', 'null' => false])
                ->addColumn('group', 'string', ['limit' => 30, 'default' => '', 'comment' => '分组', 'null' => false])
                ->addColumn('title', 'string', ['limit' => 50, 'default' => '', 'comment' => '中文名称', 'null' => false])
                ->addColumn('tip', 'string', ['limit' => 100, 'default' => '', 'comment' => '变量描述', 'null' => false])
                ->addColumn('type', 'string', ['limit' => 30, 'default' => '', 'comment' => '变量输入组件类型', 'null' => false])
                ->addColumn('value', 'text', ['limit' => MysqlAdapter::TEXT_LONG, 'null' => true, 'default' => null, 'comment' => '变量值'])
                ->addColumn('content', 'text', ['limit' => MysqlAdapter::TEXT_LONG, 'null' => true, 'default' => null, 'comment' => '字典数据'])
                ->addColumn('rule', 'string', ['limit' => 100, 'default' => '', 'comment' => '验证规则', 'null' => false])
                ->addColumn('extend', 'string', ['limit' => 255, 'default' => '', 'comment' => '扩展属性', 'null' => false])
                ->addColumn('allow_del', 'integer', ['signed' => false, 'limit' => MysqlAdapter::INT_TINY, 'default' => 0, 'comment' => '允许删除:0=否,1=是', 'null' => false])
                ->addColumn('weigh', 'integer', ['comment' => '权重', 'default' => 0, 'null' => false])
                ->addIndex(['name'], [
                    'unique' => true,
                ])
                ->create();
        }
    }
}
