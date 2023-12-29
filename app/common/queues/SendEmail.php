<?php declare (strict_types=1);
/*
 * TomatoAdmin 基础开发框架[tomatosaas]
 * SendEmail create at 2023/12/29 10:45
 * Author: 七彩枫叶 Email：424235748@qq.com
*/

namespace app\common\queues;

use app\common\controller\BaseQueue;
use app\common\traits\QueueTrait;
use think\facade\Log;
class SendEmail
{
    public function fire(Job $job, $data){

        $cfrom=[
            'scheme'   => 'smtps',// "smtps": using TLS, "smtp": without using TLS.
            'host'     => 'smtp.163.com', // 服务器地址
            'nick_name'=> 'TomatoSCRM',
            'username' => 'nuonuodinghuo@163.com',
            'password' => 'CVNPDZCQTXYIGDRW', // 密码
            'port'     => 465, // SMTP服务器端口号,一般为25
            'embed'    => 'cid:', // 邮件中嵌入图片元数据标记

        ];
        $mailer = new EmailService($cfrom);
//        $mailer = new EmailService();
//        $mailer->sendEmail('2461933260@qq.com',1,['title'=>'测试邮件发送自定义标题']);
        $mailer->setTitle($data['type'])
            ->setContent($data['type'],$data['data'])
            ->sendEmail($data['address']);


//        if ($job->attempts() > 3) {
//            //通过这个方法可以检查这个任务已经重试了几次了
//        }
//
//
//        //如果任务执行成功后 记得删除任务，不然这个任务会重复执行，直到达到最大重试次数后失败后，执行failed方法
        $job->delete();
//
//        // 也可以重新发布这个任务
//        $job->release($delay); //$delay为延迟时间

    }

    public function failed($data){

        // ...任务达到最大重试次数后，失败了
    }
}