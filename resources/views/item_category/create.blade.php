@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-primary">
                <div class="panel-heading">New Category</div>

                <div class="panel-body">
                    {!! Html::ul($errors->all()) !!}
                    {!! Form::open(array('url' => 'itemcategories/insert','method'=>'POST')) !!}
                    <div class="col-md-5">

                        <div class="form-group">
                        {!! Form::label('name', 'Category Name *') !!}
                        {!! Form::text('name', Input::old('name'), array('class' => 'form-control','required'=>'required','autofocus')) !!}
                        </div>
 
                         {!! Form::submit('Create', array('class' => 'btn btn-primary')) !!}
                    </div>
                   
                     {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
