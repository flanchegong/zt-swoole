<?php
/**
 * 消息推送服务
 * @author: KongSeng <643828892@qq.com>
 */
namespace App\Repositories;
use \Swoole\Server;
use Config;

class PushRepository extends BaseRepository
{

    /**
     * Swoole Server 服务
     */
    private $__serv = null;

    /**
     * 构造函数
     */
    public function __construct(){
        $this->__initSwoole();
    }

    /**
     * 初始化swoole
     */
    private function __initSwoole(){
//        $host = Config::get('swoole.host');
        $host='127.0.0.1';
        $port = '9521';
        $setConf = array('timeout'=>5,'keep_alive'=>true,'websocket_mask'=>true);

        $this->__serv = new Server($host,$port);
        $this->__serv->set($setConf);

        $this->__serv->on('receive', array($this,'onReceive'));
    }

    /**
     * 数据接收回调
     */
    public function onReceive($serv, $fd, $from_id, $data){
        $serv->send($fd,$data."\r\n");
    }

    /**
     * 开启服务
     */
    public function start(){
        $this->__serv->start();
    }


}