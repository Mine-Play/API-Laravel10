<?php

namespace App\Listeners\Users;

use App\Events\Users\UserRegistered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Models\Wallet;

class RegisterWallet
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(UserRegistered $event): void
    {
        Wallet\Instance::create(["id" => $event->user_id]);
    }
}
