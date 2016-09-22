@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-primary">
                <div class="panel-heading">New Account</div>

                <div class="panel-body">
                    {!! Html::ul($errors->all()) !!}
                    {!! Form::open(array('url' => 'bankaccounts', 'files' => true)) !!}
                    <div class="col-md-5">
                        

                        <div class="form-group">
                        {!! Form::label('account_name', 'Name *') !!}
                        {!! Form::text('account_name', Input::old('account_name'), array('class' => 'form-control')) !!}
                        </div>

                        <div class="form-group">
                        {!! Form::label('bank_account_name', 'Bank Account Name') !!}
                        {!! Form::text('bank_account_name', Input::old('bank_account_name'), array('class' => 'form-control')) !!}
                        </div>

                        <div class="form-group">
                        {!! Form::label('account_number', 'Account Number') !!}
                        {!! Form::text('account_number', Input::old('account_number'), array('class' => 'form-control')) !!}
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
