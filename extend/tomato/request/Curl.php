<?php declare (strict_types=1);
namespace tomato\request;

class Curl
{
    private $ch;
    private string $base_url;
    //构造函数
    public function __construct($base_url)
    {
//        if(!function_exists('curl_init'))
//        {
//            die('正在使用的php版本，不支持Curl函数！');
//        }
        $this->base_url = $base_url;
        $this->ch       = curl_init();
    }

    /**
     * 请求方式 建议使用post get 其它方法服务器端不一定支持
     * @param string $method 请求方式 默认get
    */
    public function HttpMethod(string $method='get')
    {
        match ($method)
        {
            'get'    => $this->ch,
            'post'   => curl_setopt($this->ch, CURLOPT_POST, true),
            'put'    => curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "PUT"),
            'delete' => curl_setopt($this->ch, CURLOPT_CUSTOMREQUEST, "DELETE"),
        };
    }
    /**
     * 设置具体请求的路径
     * @param string $path
    */
    public function setUri(string $path)
    {
        curl_setopt ($this->ch, CURLOPT_URL, $this->base_url.$path);
    }



//https://www.cnblogs.com/ddcoder/articles/6710028.html ftp
//https://blog.csdn.net/weixin_41728103/article/details/107645000  ftp
//https://www.php.cn/faq/393305.html
    //析构函数
    public function __destruct()
    {
        curl_close($this->ch);
    }
}