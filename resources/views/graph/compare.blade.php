@extends('layouts.app')
<style>
    .community-conainer{
        width: 400px;
        height: 400px;
        border-style: groove;
    }
</style>

@section('content')
    <div class="container">
        <div class="table-responsive">
            <table class="table">
                <thead>
                <tr>
                    <th>#</th>
                    @foreach ($results as $item)
                        <th><img src="{{route('imageuri',$item->community_path)}}" class="community-conainer" alt="none"/></th>
                    @endforeach

                </tr>
                </thead>
                <tbody>
                <tr>
                    <th scope="row">1</th>
                    @foreach ($results as $item)
                        <td>{{$item->type}}</td>
                    @endforeach
                </tr>
                <tr>
                    <th scope="row">2</th>
                    @foreach ($results as $item)
                        <td>{{round($item->modularity, 2)}}</td>
                    @endforeach
                </tr>
                <tr>
                    <th scope="row">3</th>
                    @foreach ($results as $item)
                        <td>{{round($item->time, 4)}}</td>
                    @endforeach
                </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
