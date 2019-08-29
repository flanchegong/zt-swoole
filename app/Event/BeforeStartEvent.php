<?php
namespace App\Event;
use Hhxsv5\LaravelS\Swoole\Events\BeforeStartInterface;
use Swoole\Atomic;
use Swoole\Http\Server;
class BeforeStartEvent implements BeforeStartInterface
{
    public function __construct()
    {
    }
    public function handle(Server $server)
    {
        // 初始化一个全局计数器(跨进程的可用)
        $server->atomicCount = new Atomic(2233);

        // 控制器中调用：app('swoole')->atomicCount->get();
    }
}