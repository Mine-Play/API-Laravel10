<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

class RedisSubscribe extends Command
{
    /**
     * Имя и сигнатура консольной команды.
     *
     * @var string
     */
    protected $signature = 'redis:subscribe';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Subscribe to a Redis channel';

    /**
     * Выполнить консольную команду.
     *
     * @return mixed
     */
    public function handle()
    {
        Redis::subscribe(['balance'], function ($message) {
            echo $message;
        });
        Redis::subscribe(['balance2'], function ($message) {
            echo $message;
        });
    }
}