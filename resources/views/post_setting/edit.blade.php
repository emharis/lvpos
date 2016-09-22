@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-primary">
                <div class="panel-heading">Edit Option</div>

                <div class="panel-body">
                    {!! Html::ul($errors->all()) !!}
                    {!! Form::model($setting, array('route' => array('postsettings.update', $setting->id), 'method' => 'PUT', 'files' => true)) !!}
                    <div class="col-md-5">
                        

                        <div class="form-group">
                        {!! Form::label('name', 'Option Name *') !!}
                        {!! Form::text('name', Input::old('name'), array('class' => 'form-control')) !!}
                        </div>

                        {!! Form::submit('Update', array('class' => 'btn btn-primary')) !!}
                    </div>
                     {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
 