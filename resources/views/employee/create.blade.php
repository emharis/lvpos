@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-primary">
				<div class="panel-heading">{{trans('employee.new_employee')}}</div>
				<div class="panel-body">
					{!! Html::ul($errors->all()) !!}

					{!! Form::open(array('url' => 'employees')) !!}

					<div class="form-group">
					{!! Form::label('name', trans('employee.name').' *') !!}
					{!! Form::text('name', Input::old('name'), array('class' => 'form-control')) !!}
					</div>

					<div class="form-group">
					{!! Form::label('email', trans('employee.email').' *') !!}
					{!! Form::text('email', Input::old('email'), array('class' => 'form-control')) !!}
					</div>

					<div class="form-group">
					{!! Form::label('password', trans('employee.password').' *') !!}
					<input type="password" class="form-control" name="password" placeholder="Password">
					</div>

					<div class="form-group">
					{!! Form::label('password_confirmation', trans('employee.confirm_password').' *') !!}
					<input type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password">
					</div>

                    <div class="form-group">
                    {!! Form::label('is_admin', 'Is Admin') !!}
                    {!! Form::select('is_admin', array('0' => 'No', '1' => 'Yes'), Input::old('is_admin'), array('class' => 'form-control')) !!}
                    </div>

                    <div class="form-group">
                        {!! Form::label('store_id', 'Stores') !!}
                        <br/>
                        @foreach ($stores as $id => $name)
                            {!! Form::checkbox('store_id[]', $id) !!}
                            {!! Form::label('role', $name) !!}<br>
                        @endforeach
                    </div>
					{!! Form::submit(trans('employee.submit'), array('class' => 'btn btn-primary')) !!}

					{!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>
</div>
@endsection