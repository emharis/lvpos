@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">Item Categories</div>

                <div class="panel-body">
                <a class="btn btn-small btn-success" href="{{ URL::to('itemcategories/create') }}">New Category</a>
                <hr />
@if (Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
@endif

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <td class="col-lg-1 col-md-1 col-sm-1">Id</td>
            <td >Category Name</td>
            <td class="col-lg-2 col-md-2 col-sm-2" ></td>
        </tr>
    </thead>
    <tbody>
    @foreach($itemcategories as $dt)
        <tr>
            <td>{{ $dt->id }}</td>
            <td>{{ $dt->name }}</td>
            <td>
                <a class="btn btn-sm btn-info" href="{{ URL::to('itemcategories/' . $dt->id . '/edit') }}">Edit</a>
                @if($dt->is_used == 0)
                    <a class="btn btn-sm btn-warning pull-right" href="{{ URL::to('itemcategories/' . $dt->id . '/delete') }}">Delete</a>
                @endif
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