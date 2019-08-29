<?php
/**
 * @see https://github.com/hhxsv5/laravel-s/blob/master/Settings-CN.md  Chinese
 * @see https://github.com/hhxsv5/laravel-s/blob/master/Settings.md  English
 */
return [
    'listen_ip'                => env('LARAVELS_LISTEN_IP', '127.0.0.1'),
    'listen_port'              => env('LARAVELS_LISTEN_PORT', 5200),
    'socket_type'              => defined('SWOOLE_SOCK_TCP') ? SWOOLE_SOCK_TCP : 1,
    'enable_coroutine_runtime' => true,//表示可以在运行时动态将基于php_stream 实现的扩展、PHP 网络客户端代码一键协程化，该特性需要 Swoole 4.1.0 及以上版本才支持，这些扩展目前包括 PHP 官方 redis、pdo、mysqli 扩展等。
    'server'                   => env('LARAVELS_SERVER', 'LaravelS'),
    'handle_static'            => env('LARAVELS_HANDLE_STATIC', false),
    'laravel_base_path'        => env('LARAVEL_BASE_PATH', base_path()),
    'inotify_reload'           => [
        'enable'        => env('LARAVELS_INOTIFY_RELOAD', false),
        'watch_path'    => base_path(),
        'file_types'    => ['.php'],
        'excluded_dirs' => [],
        'log'           => true,
    ],
    'event_handlers'           => [
        'BeforeStart' => \App\Event\BeforeStartEvent::class,
       // 'WorkerStart' => \App\Event\WorkerStartEvent::class,
        'WorkerStart' => \App\Listener\WorkerStartEventListener::class,
    ],
    'websocket'                => [
        'enable' => true,
        'handler' => \App\Services\WebSocketService::class,

    ],
    'sockets' => [
        [
            'host'     => '127.0.0.1',
            'port'     => 5291,
            'type'     => SWOOLE_SOCK_TCP,// 支持的嵌套字类型：https://wiki.swoole.com/wiki/page/16.html#entry_h2_0
            'settings' => [// Swoole可用的配置项：https://wiki.swoole.com/wiki/page/526.html
                'open_eof_check' => true,
                'package_eof'    => "\r\n",
            ],
            'handler'  => \App\Sockets\TestTcpSocket::class,
        ],
    ],
    'processes'                => [
        [
            'class'    => \App\Processes\TestProcess::class,
            'redirect' => false, // Whether redirect stdin/stdout, true or false
            'pipe'     => 0 // The type of pipeline, 0: no pipeline 1: SOCK_STREAM 2: SOCK_DGRAM
        ],
    ],
    'timer'                    => [
        'enable'        => true,
        'jobs'          => [
            // Enable LaravelScheduleJob to run `php artisan schedule:run` every 1 minute, replace Linux Crontab
            //\Hhxsv5\LaravelS\Illuminate\LaravelScheduleJob::class,
            // Two ways to configure parameters:
            // [\App\Jobs\XxxCronJob::class, [1000, true]], // Pass in parameters when registering
             \App\Jobs\Timer\TestCronJob::class, // Override the corresponding method to return the configuration
        ],
        'max_wait_time' => 5,
    ],
    'events' => [
        \App\Event\TestEvent::class => [
            \App\Listener\TestListener1::class,
        ],
    ],
    'swoole_tables'  => [
        // 场景：WebSocket中UserId与FD绑定
        'ws' => [// Key为Table名称，使用时会自动添加Table后缀，避免重名。这里定义名为wsTable的Table
            'size'   => 102400,//Table的最大行数
            'column' => [// Table的列定义
                ['name' => 'value', 'type' => \Swoole\Table::TYPE_INT, 'size' => 8],
            ],
        ],
        //...继续定义其他Table
    ],
    'register_providers'       => [],
    'cleaners'                 => [
        // If you use the session or authentication in your project, please uncomment this line
        //Hhxsv5\LaravelS\Illuminate\Cleaners\SessionCleaner::class,

        // If you use the authentication or passport in your project, please uncomment this line
        //Hhxsv5\LaravelS\Illuminate\Cleaners\AuthCleaner::class,

        // If you use the package "tymon/jwt-auth" in your project, please uncomment this line
        //Hhxsv5\LaravelS\Illuminate\Cleaners\JWTCleaner::class,

    ],
    'destroy_controllers'      => [
        'enable'        => false,
        'excluded_list' => [
            //\App\Http\Controllers\TestController::class,
        ],
    ],
    'swoole'                   => [
        'daemonize'          => env('LARAVELS_DAEMONIZE', false),
        'dispatch_mode'      => 2,
        'reactor_num'        => function_exists('swoole_cpu_num') ? swoole_cpu_num() * 2 : 4,
        'worker_num'         => function_exists('swoole_cpu_num') ? swoole_cpu_num() * 2 : 8,
        'task_worker_num'    => function_exists('swoole_cpu_num') ? swoole_cpu_num() * 2 : 8,
        'task_ipc_mode'      => 1,
        'task_max_request'   => 8000,
        'task_tmpdir'        => @is_writable('/dev/shm/') ? '/dev/shm' : '/tmp',
        'max_request'        => 8000,
        'open_tcp_nodelay'   => true,
        'pid_file'           => storage_path('laravels.pid'),
        'log_file'           => storage_path(sprintf('logs/swoole-%s.log', date('Y-m'))),
        'log_level'          => 4,
        'document_root'      => base_path('public'),
        'buffer_output_size' => 2 * 1024 * 1024,
        'socket_buffer_size' => 128 * 1024 * 1024,
        'package_max_length' => 4 * 1024 * 1024,
        'reload_async'       => true,
        'max_wait_time'      => 60,
        'enable_reuse_port'  => true,
        'enable_coroutine'   => true,//在 LaravelS 代码中启用 Swoole 协程
        'http_compression'   => false,
        'heartbeat_idle_time'      => 600,
        'heartbeat_check_interval' => 60,

        // Slow log
        // 'request_slowlog_timeout' => 2,
        // 'request_slowlog_file'    => storage_path(sprintf('logs/slow-%s.log', date('Y-m'))),
        // 'trace_event_worker'      => true,

        /**
         * More settings of Swoole
         * @see https://wiki.swoole.com/wiki/page/274.html  Chinese
         * @see https://www.swoole.co.uk/docs/modules/swoole-server/configuration  English
         */
    ],
];
