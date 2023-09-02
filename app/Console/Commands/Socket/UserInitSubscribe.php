<?php

namespace App\Console\Commands\Socket;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;

use App\Models\Wallet;

class UserInitSubscribe extends Command
{
    /**
     * Имя и сигнатура консольной команды.
     *
     * @var string
     */
    protected $signature = 'socket:UserInit';

    /**
     * Описание консольной команды.
     *
     * @var string
     */
    protected $description = 'Subscribe to a limbo user init';

    /**
     * Выполнить консольную команду.
     *
     * @return mixed
     */
    public function handle()
    {
        Redis::subscribe(['user_init'], function ($message) {
            $data = json_decode($message, false);
            if($wallet = Wallet\Instance::where('id', $data->uniqueId)->select('money', 'coins', 'keys')->first()){
                 
            }
        });
    }
}