@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading">Manage Bank Accounts</div>

                <div class="panel-body">
                <a class="btn btn-small btn-success" href="{{ URL::to('bankaccounts/create') }}">New Account</a>
                <hr />
@if (Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
@endif

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <td>Name</td>
            <td>Bank Account Name</td>
            <td>Account Number</td>
            <td></td>
        </tr>
    </thead>
    <tbody>
    @foreach($accounts as $account)
        <tr>
            <td>{{ $account->account_name }}</td>
            <td>{{ $account->bank_account_name }}</td>
            <td>{{ $account->account_number }}</td>
            <td>
                <a class="btn btn-sm btn-info" href="{{ URL::to('bankaccounts/' . $account->id . '/edit') }}">Edit</a>
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