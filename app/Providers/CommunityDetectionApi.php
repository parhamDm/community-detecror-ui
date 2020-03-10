<?php

namespace App\Providers;


use App\CommunityResult;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CommunityDetectionApi
{
    protected $client;
    public function __construct()
    {
        $this->client = new Client(['base_uri' => 'http://192.168.183.133:8000']);

    }

    public function edgeBetweenness($g){

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
