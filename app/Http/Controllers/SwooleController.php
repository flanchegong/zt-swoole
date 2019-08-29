<?php
namespace App\Http\Controllers;
use Swoole\Http\Request;
use Swoole\WebSocket\Frame;
use Swoole\WebSocket\Server;
class SwooleController extends Controller
{

// 场景：WebSocket中UserId与FD绑定
    public function onOpen(Server $server, Request $request)
    {
        // var_dump(app('swoole') === $server);// 同一实例
        $userId = mt_rand(1000, 10000);
        app('swoole')->wsTable->set('uid:' . $userId, ['value' => $request->fd]);// 绑定uid到fd的映射
        app('swoole')->wsTable->set('fd:' . $request->fd, ['value' => $userId]);// 绑定fd到uid的映射
        $server->push($request->fd, 'Welcome to LaravelS');
    }

    public function onMessage(Server $server, Frame $frame)
    {
        foreach (app('swoole')->wsTable as $key => $row) {
            if (strpos($key, 'uid:') === 0 && $server->exist($row['value'])) {
                $server->push($row['value'], 'Broadcast: ' . date('Y-m-d H:i:s'));// 广播
            }
        }
    }

    public function onClose(Server $server, $fd, $reactorId)
    {
        $uid = app('swoole')->wsTable->get('fd:' . $fd);
        if ($uid !== false) {
            app('swoole')->wsTable->del('uid:' . $uid['value']);// 解绑uid映射
        }
        app('swoole')->wsTable->del('fd:' . $fd);// 解绑fd映射
        $server->push($fd, 'Goodbye');
    }

    public function form(\Illuminate\Http\Request $request)
    {
        $name = $request->input('name');
        $all = $request->all();
        $sessionId = $request->cookie('sessionId');
        $photo = $request->file('photo');
        // 调用getContent()来获取原始的POST body，而不能用file_get_contents('php://input')
        $rawContent = $request->getContent();
        //...
    }

    public function json()
    {
        return response()->json(['time' => time()])->header('header1', 'value1')->withCookie('c1', 'v1');
    }
}