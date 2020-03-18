<?php

namespace App\Providers;

use App\CommunityResult;
use App\Graph;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;

class CommunityDetectionApiProvider extends ServiceProvider
{
    protected $client;
    protected $reqOptions;
    protected $baseUrl;
    public function __construct($app)
    {
        parent::__construct($app);
        $this->baseUrl = env("API_URL","http://192.168.183.133:8000");
        $this->client = new Client(['base_uri' => $this->baseUrl]);
        $this->reqOptions=[
            'multipart' => [
                [
                    'name'     => 'key',
                    'contents' => 'JAFAR'
                ],
                [
                    'name'     => 'file',
                    'contents' => ''
                ],
            ]
        ];
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('CommunityDetectionApi', function ($app) {
            return new CommunityDetectionApiProvider($app->make(CommunityDetectionApi::class));
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    public function registerGraph($g,$file,$graph){
        //save results
        $reqOptions =$this->_registerFile($file);
        $response = $this->client->post("community/register?id=$g",$reqOptions);
        $content = json_decode($response->getBody()->getContents());
        $graph_data = base64_decode($content->graph);

        $graph->edges = $content->edges;
        $graph->vertices = $content->vertices;
        //save to data base
        $graph->save();
        //save preview to storage
        Storage::disk('local')->put("community\\".$g.".png", $graph_data);
    }

    public function unregisterGraph($file_name,$graph){
        //save results
        $response = $this->client->get("community/unregister?id=$file_name");
        $graph->status = 1;
        //save to data base
        $graph->save();

    }


    public function edgeBetweenness($file_name, $g_id){
        //save results
        $url=env("API_EDGE_BETWEENNESS_URL",'community/edge');
        $response = $this->client->get($url."?id=$file_name");
        $this->_saveToDataBase($response,"GIRVAN",$g_id);
    }

    public function fastGreedy($file_name,$g_id){
        //save results
        $url=env("API_FAST_GREEDY_URL",'community/fastgreedy');
        $response = $this->client->get($url."?id=$file_name");
        $this->_saveToDataBase($response,"FAST_GREEDY",$g_id);
    }

    public function walktrap($file_name,$g_id){
        //save results
        $url=env("API_WALK_TRAP_URL",'community/walktrap');
        $response = $this->client->get($url."?id=$file_name");
        $this->_saveToDataBase($response,"WALK_TRAP",$g_id);
    }


    private function _saveToDataBase($response,$type,$g_id){
        $cr= new CommunityResult();
        $content = json_decode($response->getBody()->getContents());
        //graph file handle
        $graph_file_name = md5(uniqid(rand(), true));
        $graph_data = base64_decode($content->cluster);
        Storage::disk('local')->put("community\\".$graph_file_name.".png", $graph_data);
        $cr->community_path= $graph_file_name;
        $cr->modularity = $content->modularity;
        $cr->type = $type;
        $cr->time = $content->time;
        $cr->user_id = Auth::user()->id;
        $cr->graph_id = $g_id;
        $cr->save();
    }

    private function _registerFile($file){
         $this->reqOptions['multipart'][1]['contents']= $file;
         return $this->reqOptions;
    }
}
