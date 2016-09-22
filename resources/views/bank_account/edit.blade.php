@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-primary">
                <div class="panel-heading">Edit Account</div>

                <div class="panel-body">
                    {!! Html::ul($errors->all()) !!}
                    {!! Form::model($account, array('route' => array('bankaccounts.update', $account->id), 'method' => 'PUT', 'files' => true)) !!}
                    <div class="col-md-5">
                        

                        <div class="form-group">
                        {!! Form::label('account_name', 'Name *') !!}
                        {!! Form::text('account_name', null, array('class' => 'form-control datepicker')) !!}
                        </div>

                        <div class="form-group">
                        {!! Form::label('bank_account_name', 'Bank Account Name') !!}
                        {!! Form::text('bank_account_name', null, array('class' => 'form-control')) !!}
                        </div>

                        <div class="form-group">
                        {!! Form::label('account_number', 'Account Number') !!}
                        {!! Form::text('account_number', null, array('class' => 'form-control')) !!}
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
 