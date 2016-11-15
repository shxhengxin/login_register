<?php

namespace App\Jobs;


use Illuminate\Bus\Queueable;

use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class RealTime implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;
    public $user;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if(\Redis::decr('name') >= 0){

                Log::info('还有券'.(\Redis::get('name')+1));

        }else{
            Log::info('券领完');
        }



    }
}
