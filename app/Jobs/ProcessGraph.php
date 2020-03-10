<?php

namespace App\Jobs;

use App\CommunityResult;
use App\Graph;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;

class ProcessGraph implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected  $graph;
    protected $reqOptions;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($g)
    {
        $this->graph = $g;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $g = $this->graph;
        $api = resolve('CommunityDetectionApi');
        $api->edgeBetweenness($g);
    }


}
