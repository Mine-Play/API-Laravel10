<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class RedisSend extends Command
{
    /**
     * Имя и сигнатура консольной команды.
     *
     * @var string
     */
    protected $signature = 'redis:send';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Publish to a Redis channel';

    /**
     * Выполнить консольную команду.
     *
     * @return mixed
     */
    public function handle()
    {
        // Redis::publish('balance', json_encode([
        //     'name' => 'Adam Wathan'
        // ]));
        Redis::publish('balance2', json_encode([
            'name' => 'Adam Wathan'
        ]));
    }
}