@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">Post Settings</div>

                <div class="panel-body">
                <a class="btn btn-small btn-success" href="{{ URL::to('postsettings/create') }}">New Item</a>
                <hr />
@if (Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
@endif

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <td>Id</td>
            <td>Option Name</td>
            <td></td>
        </tr>
    </thead>
    <tbody>
    @foreach($settings as $setting)
        <tr>
            <td>{{ $setting->id }}</td>
            <td>{{ $setting->name }}</td>
            <td>
                <a class="btn btn-sm btn-info" href="{{ URL::to('postsettings/' . $setting->id . '/edit') }}">Edit</a>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection