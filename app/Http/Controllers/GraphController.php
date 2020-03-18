<?php

namespace App\Http\Controllers;

use App\Graph;
use App\Jobs\ProcessGraph;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class GraphController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $user = Auth::user()->id;
        $graphs = User::graphs($user)->paginate(5);

        return view('graph.list',['graphs' => $graphs]);
    }

    public function create()
    {
        $params = [
            'title' => 'Add Person',
        ];

        return view('graph.addGraph')->with($params);
    }

    public function store(Request $request)
    {
//        $file = $request->graph;
//        if($file->getClientOriginalExtension() !=="txt"){
//        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:30|min:5',
            'graph' => 'required|max:10000|mimes:txt',
        ]);

        if ($validator->fails()) {

            return redirect('graph/create')
                ->withErrors($validator)
                ->withInput();
        }
        //TODO add file validation

        $graph =new Graph();
        $file_name = md5(uniqid(rand(), true));
        $graph->file_name=$file_name;
        $graph->name =$request->name;
        $graph->size =$request->graph->getSize();
        $request->graph->storeAs('graphs',$file_name.".txt");
        $graph->status=false;
        $graph->user_id=Auth::user()->id;
        $graph->save();
       // dd($request->graph->getClientOriginalExtension());
        ;
        ProcessGraph::dispatch($graph)->delay(now()->addMinutes(1));
        return redirect()
            ->route('graph.index')
            ->with('status', 'Graph Successfully added!');
    }

    public function show($id)
    {
        $person = [
            'id'         => 1,
            'first_name' => 'John',
            'last_name'  => 'Smith',
        ];

        $params = [
            'title'  => 'Person Details',
            'person' => $person,
        ];

        return view('graph.addGraph')->with($params);
    }

    public function edit($id)
    {
        $person = [
            'id'         => 1,
            'first_name' => 'John',
            'last_name'  => 'Smith',
        ];

        $params = [
            'title'  => 'Person Details',
            'person' => $person,
        ];

        return view('graph.addGraph')->with($params);
    }

    public function update(Request $request, $id)
    {
        return redirect()
            ->route('graph.index')
            ->with('status', 'Person details successfully updated!');
    }

    public function destroy($id)
    {
        Graph::removeBasedOnGraph($id);
        return redirect()
            ->route('graph.index')
            ->with('status', 'Person record successfully deleted!');
    }

    public function compare($graph){

        $graph = Graph::where("file_name",$graph)->first();
        if($graph==null){
            return "not found!";

        }
        if($graph->status==0){
            return "not finished yet!";
        }
        $results = $graph->communityResults();

        return view('graph.compare')->with('results',$results);
    }

    public function getImage($path){
//        $id =Auth::user()->id;
//        $graphs = User::graphs($id);
//        $bool = false;
//        foreach ($graphs as $item){
//            if ($item == $id){
//                $bool=true;
//            }
//        }
        if (!true){
                return abort(403);
        }
        try {
            $file =  Storage::disk('local')->get('community/'.$path.'.png');
            return response($file)->header("Content-Type","image/png");
        }catch (\Throwable $ex){
            abort(404);
        }
        return NULL;
    }

    public function getFile($path){
        if (!true){
            return abort(403);
        }
        try {
            $file =  Storage::disk('local')->get('graphs/'.$path.'.txt');
            return response($file)->header("Content-Type","text/plain");
        }catch (\Throwable $ex){
            abort(404);
        }
        return NULL;
    }

}
