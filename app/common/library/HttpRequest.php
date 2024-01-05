<?php declare (strict_types=1);

namespace app\common\library;
class HttpRequest
{
    //http状态码 说明信息
    public array $httpcode = [
            100=>"Continue",
            101=>"Switching Protocols",
            200=>"OK",
            201=>"Created",
            202=>"Accepted",
            203=>"Non-Authoritative Information",
            204=>"No Content",
            205=>"Reset Content",
            206=>"Partial Content",
            300=>"Multiple Choices",
            301=>"Moved Permanently",
            302=>"Found",
            303=>"See Other",
            304=>"Not Modified",
            305=>"Use Proxy",
            306=>"(Unused)",
            307=>"Temporary Redirect",
            400=>"Bad Request",
            401=>"Unauthorized",
            402=>"Payment Required",
            403=>"Forbidden",
            404=>"Not Found",
            405=>"Method Not Allowed",
            406=>"Not Acceptable",
            407=>"Proxy Authentication Required",
            408=>"Request Timeout",
            409=>"Conflict",
            410=>"Gone",
            411=>"Length Required",
            412=>"Precondition Failed",
            413=>"Request Entity Too Large",
            414=>"Request-URI Too Long",
            415=>"Unsupported Media Type",
            416=>"Requested Range Not Satisfiable",
            417=>"Expectation Failed",
            500=>"Internal Server Error",
            501=>"Not Implemented",
            502=>"Bad Gateway",
            503=>"Service Unavailable",
            504=>"Gateway Timeout",
            505=>"HTTP Version Not Supported",
        ];
    //基础网址
    public string $baseurl='';

    //还需要 多种请求方式，带cookie，ssl
    protected function Http_post_json(string $uri,array $params=array(),bool $withToken=true)
    {
        $headers= [
            'User-Agent:Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
            'Accept:application/json'
        ];

        //修正下方的token
        if($withToken)
        {
            $userinfo=$this->getUserToekn();
            $headers=[ 'token: ' . $userinfo['token'], 'uuid: ' . $userinfo['uuid'] ];
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $this->baseurl.$uri);
        curl_setopt($ch, CURLOPT_POST, true);
        if(!empty($params))
        {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $result = curl_exec($ch);
        //排除404错误
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if($httpCode > 400)
        {
            return $respData = array('code' => '-1', 'msg' => $this->httpcode[$httpCode]);
        }

        if(curl_errno($ch))
        {
            return $respData = array('code' => '-1', 'msg' => 'Curl error: ' . curl_error($ch));
        }
        if ($result === false) {
            // 处理请求失败的情况
            $respData = array('code' => '-1', 'msg' => 'POST 请求失败');
        } else {
            // 处理成功收到响应的情况
            $respData = json_decode($result, true);
        }
        curl_close($ch);
        return $respData;
    }

}