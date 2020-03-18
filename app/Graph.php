<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Graph extends Model
{
    protected $table = 'graphs';
    public $timestamps=false;
    protected $primaryKey = 'id';

    public function communityResults(){
        $this->primaryKey='id';
        $a = $this->hasMany(CommunityResult::class)->get();
        return $a;
    }

    public static function removeBasedOnGraph($graph_name){
        $graph = Graph::where("file_name",$graph_name)->first();
        $id = $graph->id;
        //community result
        $cr = CommunityResult::where("graph_id",$id)->get();
        $deleteList = array();
        foreach ($cr as $item){
            Storage::disk("local")->delete("community/".$item->community_path.".png");
            array_push($deleteList,$item->id);
        }
        CommunityResult::destroy($deleteList);
        //graph
        Storage::disk("local")->delete("community/".$graph->file_name.".png");
        Storage::disk("local")->delete("graphs/".$graph->file_name.".txt");

        Graph::destroy([$id]);
    }
}
