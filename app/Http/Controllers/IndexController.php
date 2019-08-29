<?php


namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PoiDistricts;
use App\PoiDistrictService;
use Hhxsv5\LaravelS\Swoole\Task\Event;
use Hhxsv5\LaravelS\Swoole\Task\Task;
use App\Event\TestEvent;
use App\Jobs\TestTask;
class IndexController extends Controller
{
    public function index(Request $request)
    {
        $lon = $request->input('lon');
        $lat = $request->input('lat');

        // 查询对应数据
        $client = app()->get(PoiDistrictService::class)->getClassifier();
        $id = $client->predict([$lon, $lat]);

        $model = PoiDistricts::query()->where('oid', $id)->first();
        return $model->toArray();
    }

    public function testEvent(){
        // 实例化TestEvent并通过fire触发，此操作是异步的，触发后立即返回，由Task进程继续处理监听器中的handle逻辑
        $success = Event::fire(new TestEvent('event data'));
        var_dump($success);//判断是否触发成功



    }

    public function testTask(){
        // 实例化TestTask并通过deliver投递，此操作是异步的，投递后立即返回，由Task进程继续处理TestTask中的handle逻辑
        $task = new TestTask('task data');
        // $task->delay(3);// 延迟3秒投放任务
        $ret = Task::deliver($task);
        var_dump($ret);//判断是否投递成功
    }

    public function testSwoole(){
        /**
         * 如果启用WebSocket server，$swoole是`Swoole\WebSocket\Server`的实例，否则是是`Swoole\Http\Server`的实例
         * @var \Swoole\WebSocket\Server|\Swoole\Http\Server $swoole
         */
        $swoole = app('swoole');
        var_dump($swoole->stats());// 单例
    }
}