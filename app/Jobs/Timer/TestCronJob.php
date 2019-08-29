<?php
namespace App\Jobs\Timer;

use Hhxsv5\LaravelS\Swoole\Timer\CronJob;
use Illuminate\Support\Facades\Log;
use App\Tasks\TestTask;
use Swoole\Coroutine;
use Hhxsv5\LaravelS\Swoole\Task\Task;
class TestCronJob extends CronJob
{
    protected $i = 0;

    // --- 重载对应的方法来返回配置：结束
    public function run()
    {
        Log::info(__METHOD__, ['start', $this->i, microtime(true)]);
        // do something
        // sleep(1); // Swoole < 2.1
        Coroutine::sleep(1); // Swoole>=2.1 run()方法已自动创建了协程。
        $this->i++;
        Log::info(__METHOD__, ['end', $this->i, microtime(true)]);

        if ($this->i >= 10) { // 运行10次后不再执行
            Log::info(__METHOD__, ['stop', $this->i, microtime(true)]);
            $this->stop(); // 终止此任务
            // CronJob中也可以投递Task，但不支持Task的finish()回调。
            // 注意：
            // 1.参数2需传true
            // 2.config/laravels.php中修改配置task_ipc_mode为1或2，参考 https://wiki.swoole.com/wiki/page/296.html
            $ret = Task::deliver(new TestTask('task data'), true);
            var_dump($ret);
        }
        // throw new \Exception('an exception');// 此时抛出的异常上层会忽略，并记录到Swoole日志，需要开发者try/catch捕获处理
    }

    // 每隔 1000ms 执行一次任务
    public function interval()
    {
        return 1000;   // 定时器间隔，单位为 ms
    }

    // 是否在设置之后立即触发 run 方法执行
    public function isImmediate()
    {
        return false;
    }
}