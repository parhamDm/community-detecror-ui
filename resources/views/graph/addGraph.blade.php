@extends('layouts.app')


@section('content')
    <div class="container">
        <div class="row justify-content-center" >
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">{{ __('Add Graph') }}</div>

                    <div class="card-body">
                        <form method="POST" action="/graph" enctype="multipart/form-data">
                            @csrf
{{--                            @method('PUT')--}}
                            <div class="form-group row">
                                <label for="file" class="col-md-4 col-form-label text-md-right">{{ __('graph file(txt)') }}</label>

                                <div class="col-md-6">
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="inputGroupFile01" name="graph"
                                                   aria-describedby="inputGroupFileAddon01">
                                            <label class="custom-file-label" for="inputGroupFile01">Choose file</label>
                                        </div>
                                    </div>
                                    @error('graph')
                                    <span class="invalid-feedback" role="alert" style="display: block" >
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>

                            </div>

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Graph Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" class="form-control" name="name" required >

                                    @error('name')
                                    <span class="invalid-feedback" role="alert" style="display: block">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Submit') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
