<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\PushRepository;
# php artisan push:start
class Push extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'push:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '推送服务启动命令';

    /**
     * push处理推送任务的对象实例
     */
    protected $_repPush;

    /**
     * Create a new command instance.
     *
     * @param  DripEmailer  $drip
     * @return void
     */
    public function __construct(PushRepository $repPush)
    {
        parent::__construct();
        $this->_repPush = $repPush;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->_repPush->start();
    }


}