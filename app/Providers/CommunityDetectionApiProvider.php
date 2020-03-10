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

    public function __construct($app)
    {
        parent::__construct($app);
        $this->client = new Client(['base_uri' => 'http://192.168.183.133:8000']);
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



    public function edgeBetweenness($g){
        //save results
        $file = Storage::get("graphs/".$g->file_name.".txt");
        $this->reqOptions['multipart'][1]['contents']= $file;

        $response = $this->client->post('community/edge',$this->reqOptions);

        $g_id = Graph::select('id')->where("file_name",'=',$g->file_name)->first()['id'];

        $content = json_decode($response->getBody()->getContents());
        $this->_saveToDataBase($content,"GIRVAN",$g_id);
    }

    private function _saveToDataBase($content,$type,$g_id){
        $cr= new CommunityResult();
        //graph file handle
        $graph_file_name = md5(uniqid(rand(), true));
        $graph_data = base64_decode($content->graph);
        Storage::disk('local')->put("community\\".$graph_file_name.".png", $graph_data);
        $cr->graph_path= $graph_file_name;

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
}
