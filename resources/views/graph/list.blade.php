@extends('layouts.app')
<style>
    .community-conainer{
        width: 200px;
        height: 200px;
        border-style: groove;
    }
</style>

@section('content')
    <div class="container">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>preview</th>
                    <th>name</th>
                    <th>size</th>
                    <th>vertices</th>
                    <th>edges</th>
                    <th>finished</th>
                    <th>download</th>
                    <th>remove</th>
                </tr>
                </thead>
                <tbody>
                    @foreach ($graphs as $item)
                        <tr >
                            <td>
                                <a href="{{route('compare',$item->file_name)}}">
                                    <img src="{{route('imageuri',$item->file_name)}}" class="community-conainer" alt="none"/>
                                </a>
                            </td>

                            <td>{{$item->name}}</td>
                            <td>{{$item->size/1024}} KB</td>
                            <td>{{$item->vertices}}</td>
                            <td>{{$item->edges}}</td>
                            <td>{{$item->status}}</td>
                            <th>
                                <a href="{{route("fileuri",$item->file_name)}}" type="button" class="btn btn-outline-secondary waves-effect btn-sm m-0">
                                    <i class="fa fa-download "></i>
                                </a>
                            </th>
                            <th>
                                <a href="{{"/graph/destroy/".$item->file_name}}" type="button" class="btn btn-outline-secondary waves-effect btn-sm m-0">
                                    <i class="fa fa-remove-format"></i>
                                </a>
                            </th>
                            <td>{{$item->remove}}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            {{ $graphs->links()}}

        </div>
    </div>
@endsection
