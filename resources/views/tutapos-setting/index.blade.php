@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-primary">
				<div class="panel-heading">Application Settings</div>

				<div class="panel-body">
					@if (Session::has('message'))
                    	<div class="alert alert-info">{{ Session::get('message') }}</div>
                	@endif
					{!! Html::ul($errors->all()) !!}

					{!! Form::model($tutapos_settings, array('route' => array('settings.update', $tutapos_settings->id), 'method' => 'PUT', 'files' => true)) !!}

					<div class="form-group">
					{!! Form::label('language', 'Language') !!}
					{!! Form::select('language', array('en' => 'English'), Input::old('language'), array('class' => 'form-control')) !!}
					</div>

                    <div class="form-group">
                    {!! Form::label('shipping_factor', 'Shipping fr Oversea') !!}
                    {!! Form::text('shipping_factor', null, array('class' => 'form-control')) !!}
                    </div>

                    <div class="form-group">
                    {!! Form::label('margin_factor', 'Target Margin') !!}
                    {!! Form::text('margin_factor', null, array('class' => 'form-control')) !!}
                    </div>

                    <div class="form-group">
                    {!! Form::label('retail_price_factor', 'Retail Price Factor (%)') !!}
                    {!! Form::text('retail_price_factor', null, array('class' => 'form-control')) !!}
                    </div>

					{!! Form::submit('Submit', array('class' => 'btn btn-primary')) !!}

					{!! Form::close() !!}




                    {!! Form::open(array('url' => 'settings/add-store', 'files' => true)) !!}
                        <div>&nbsp;</div>
                        <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <td><b>Store Settings</b></td>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($stores as  $value)
                                <tr>
                                    <td>{{ $value->store_name }}</td>

                                </tr>
                            @endforeach
                            </tbody>
                        </table>


                        <div class="form-group">

                        {!! Form::label('store', 'New Store') !!}
                        {!! Form::text('store_name',  Input::old('store_name'), array('class' => 'form-control')) !!}
                        </div>

                        {!! Form::submit('Submit', array('class' => 'btn btn-primary')) !!}

                    {!! Form::close() !!}

				</div>
			</div>
		</div>
	</div>
</div>
@endsection