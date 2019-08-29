<?php
namespace App\Listener;
use App\Event\TestEvent;
use Hhxsv5\LaravelS\Swoole\Task\Event;
use Hhxsv5\LaravelS\Swoole\Task\Listener;
use Illuminate\Support\Facades\Log;
use Hhxsv5\LaravelS\Swoole\Task\Task;
class TestListener1 extends Listener
{
    // 声明没有参数的构造函数
    public function __construct()
    {
    }
    public function handle(Event $event)
    {
        Log::info(__CLASS__ . ':handle start', [$event->getData()]);
        sleep(2);// 模拟一些慢速的事件处理
        // 监听器中也可以投递Task，但不支持Task的finish()回调。
        // 注意：
        // 1.参数2需传true
        // 2.config/laravels.php中修改配置task_ipc_mode为1或2，参考
//                1，使用Unix Socket通信，默认模式
//        2，使用消息队列通信
//        3，使用消息队列通信，并设置为争抢模式
        $ret = Task::deliver(new TestTask('task data'), true);
        var_dump($ret);
        // throw new \Exception('an exception');// handle时抛出的异常上层会忽略，并记录到Swoole日志，需要开发者try/catch捕获处理
    }
}