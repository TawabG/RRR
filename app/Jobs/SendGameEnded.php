<?php

namespace App\Jobs;

use App\Events\SessionStatusUpdated;
use App\Session;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;


class SendGameEnded implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $session;

    /**
     * @author Geert Berkers, Maikel Hoeks
     *
     * Create a new Session instance.
     *
     * @param  Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;

    }

    /**
     * @author Geert Berkers, Maikel Hoeks
     *
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if($this->session != null){
            $this->session->status = "ENDED";
            $this->session->save();

            event(new SessionStatusUpdated($this->session));
        }
    }
}
