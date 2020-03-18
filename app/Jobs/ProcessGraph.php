<?php

namespace App\Jobs;

use App\CommunityResult;
use App\Graph;
use App\Providers\CommunityDetectionApiProvider;
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
        $file_name = $this->graph->file_name;
        $file = Storage::get("graphs/".$file_name.".txt");
        $g_id = Graph::where("file_name",'=',$file_name)->first();

        $api = resolve('CommunityDetectionApi');
        //register
        $api->registerGraph($file_name,$file,$g_id);
        //community detection methods
        $api->fastgreedy($file_name,$g_id['id']);
        $api->walktrap($file_name,$g_id['id']);
        //unregister
        $api->unregisterGraph($file_name,$g_id);

    }


}
