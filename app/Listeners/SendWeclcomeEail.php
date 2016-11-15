<?php

namespace App\Listeners;

use App\Events\UserRegistered;

use App\Mail\UserRegisterEail;


class SendWeclcomeEail
{
    public $mail;
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(UserRegisterEail $mail)
    {

        $this->mail = $mail;
    }

    /**
     * Handle the event.
     *
     * @param  UserRegistered  $event
     * @return void
     */
    public function handle(UserRegistered $event)
    {

        $this->mail->welcome($event->user);
    }
}
