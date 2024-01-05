<?php declare (strict_types=1);
/*
 * TomatoAdmin 基础开发框架[tpadmin]
 * BtPanel create at 2023/3/16 10:20
 * Author: 七彩枫叶 Email：424235748@qq.com
*/

namespace  tomato\Btpanel;

//需要重新编写

use think\facade\Filesystem;
use yzh52521\EasyHttp\Http;
use tomato\HelperArr;
//use yzh52521\EasyHttp\RequestException;
class BtPanel
{
    private $BT_KEY = "";  	//接口密钥
    private $BT_PANEL = "";	//面板地址

    /**
     * 初始化
     * @param null|string $bt_key   Api秘钥
     * @param null|string $bt_panel 面板地址加端口号
    */
    private function __construct($bt_panel = null,$bt_key = null)
    {
        if($bt_panel) $this->BT_PANEL = $bt_panel;
        if($bt_key) $this->BT_KEY = $bt_key;
    }

    /**
     * 获取服务基础信息
    */
    public function serverBaseInfo()
    {
        $url='/system?action=GetNetWork';

        $result = self::httpPostWithCookie($url);
        return $result->array();
    }
    /**
     * 获取网站列表
     */
    public function Websites($search='',$page='1',$limit='20',$type='-1',$order='id desc',$tojs='')
    {

        $url='/data?action=getData';

        $p_data['table']='sites';
        $p_data['p'] = $page;
        $p_data['limit'] = $limit;
        $p_data['type'] = $type;
        $p_data['order'] = $order;
//        $p_data['tojs'] = $tojs;
//        $p_data['search'] = $search;

        $result = self::httpPostWithCookie($url,$p_data,10)->array();
        if(array_key_exists('status',$result) && $result['status']==false){
            return $result;
        }
        foreach ($result['data'] as $key=>$website)
        {

            if($website['domain']>1){
                $website['domains']= json_encode(HelperArr::tm_array_save_child(self::WebDoaminList($website['id']),['name','addtime']));
            }else{
                $website['domains']='['.json_encode(array_intersect_key($website,array_flip(['name','addtime']))).']';
            }
            if($website['ssl']=='-1')
            {
                $website['is_ssl']=0;
                $website['notAfter']=null;
            }else{
                $website['is_ssl']=1;
                $website['notAfter']=$website['ssl']['notAfter'];
            }

            $website['site_id']=$website['id'];
            unset($website['ssl'],$website['quota'],$website['id']);
            $website['status']=(int)$website['status'];
            $result['data'][$key]=$website;
        }
        return $result;
    }
    /**
     * 获取网站域名列表
    */
    public function WebDoaminList($id,$list=true)
    {
        $url='/data?action=getData&table=domain';
        $p_data['search'] = $id;
        $p_data['list'] = $list;
        $result = self::httpPostWithCookie($url,$p_data,10);
        return $result->array();
    }

    /**
     * 构造带有签名的关联数组
     */
    private function GetKeyData(){
        $now_time = time();
        $p_data = [
            'request_token'	=>	md5($now_time.''.md5($this->BT_KEY)),
            'request_time'	=>	$now_time
        ];
        return $p_data;
    }

    /**
     * 发送post请求
    */
    private function httpPostWithCookie(string $url,array $data=[],int $time_out=30)
    {
        $post_data=array_merge($data, self::GetKeyData());
        $File = Filesystem::disk('local');
        $path = 'cookie/'.md5($this->BT_PANEL);
        if(!$File->has($path))
        {
            $File->write($path, '');
        }
        $cookies = explode(';',$File->read($path));

        try {
            $response = Http::withHost($this->BT_PANEL)
                ->debug(\think\facade\Log::class)
                ->withCookies( $cookies, $this->BT_PANEL)
                ->timeout($time_out)
                ->post($url,$post_data);
        }catch (RequestException $e)
        {
            $e->getMessage();

        }
        $File->update($path,$response->header('Set-Cookie'));
        return $response;
    }
}