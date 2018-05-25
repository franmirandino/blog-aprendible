<?php

namespace App\Listeners;

use App\Events\UserWasCreated;
use App\Mail\LoginCredencials;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendLoginCredentials
{
    /**
     * Handle the event.
     *
     * @param  UserWasCreated  $event
     * @return void
     */
    public function handle(UserWasCreated $event)
    {
        // Enviar el email con las credenciales del login

        Mail::to($event->user)->queue(
            new LoginCredencials($event->user, $event->password)
        );

    }
}
