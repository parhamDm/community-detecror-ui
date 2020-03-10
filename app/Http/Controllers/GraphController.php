<?php

namespace App\Http\Controllers;

use App\Graph;
use App\Jobs\ProcessGraph;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
        $persons = [
            [
                'id'         => 1,
                'first_name' => 'John',
                'last_name'  => 'Smith',
            ],
            [
                'id'         => 2,
                'first_name' => 'Jane',
                'last_name'  => 'Smith',
            ],
        ];

        $params = [
            'title'   => 'Persons Listing',
            'persons' => $persons,
        ];

        return view('graph.addGraph')->with($params);
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
            'password' => 'required',
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
        $request->graph->storeAs('graphs',$file_name.".txt");
        $graph->status=false;
        $graph->methods="";
        $graph->user_id=Auth::user()->id;
        $graph->save();
       // dd($request->graph->getClientOriginalExtension());
        ;
        ProcessGraph::dispatch($graph);
        return redirect()
            ->route('')
            ->with('status', 'New person successfully created!');
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
        return redirect()
            ->route('graph.index')
            ->with('status', 'Person record successfully deleted!');
    }

}
