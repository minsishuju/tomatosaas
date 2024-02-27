<?php declare (strict_types=1);
namespace app\common\traits;
use think\exception\HttpResponseException;
use think\App;
use think\facade\Request;
use think\Response;
trait BaseTrait
{
    /**
     * Request实例
     * @var \think\Request|object
     */
    protected $request;

    public function __construct()
    {
        $this->request = Request::instance();
    }
    /**
     * 获取当前的response 输出类型
     * @access protected
     * @return string
     */
    protected function getResponseType()
    {
        return $this->request->isJson() || $this->request->isAjax() ? 'json' : 'html';
    }

}