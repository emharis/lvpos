@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">{{trans('customer.list_customers')}}</div>

				<div class="panel-body">
				<a class="btn btn-small btn-success" href="{{ URL::to('customers/create') }}">{{trans('customer.new_customer')}}</a>
				<hr />
@if (Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
@endif

<table class="table table-striped table-bordered" id="customer-index">
    <thead>
        <tr>
            <td>{{trans('customer.customer_id')}}</td>
            <td>{{trans('customer.name')}}</td>
            <td>{{trans('customer.email')}}</td>
            <td>{{trans('customer.phone_number')}}</td>
            <td>Store Source</td>
            <td>Created By</td>
            <td>&nbsp;</td>
        </tr>
    </thead>
    <tbody>
    @foreach($customer as $value)
        <tr>
            <td>{{ $value->id }}</td>
            <td>{{ $value->name }}</td>
            <td>{{ $value->email }}</td>
            <td>{{ $value->phone_number }}</td>
            <td>{{ $value->store_name }}</td>
            <td>{{ $value->created_by_name }}</td>
            <td>

                <a class="btn btn-sm btn-info" href="{{ URL::to('customers/' . $value->id . '/edit') }}">{{trans('customer.edit')}}</a>
                 <a class="btn btn-sm btn-primary" href="{{ URL::to('sales/create?customer_id='.$value->id) }}">Create Sale</a>
                {!! Form::open(array('url' => 'customers/' . $value->id, 'class' => 'pull-right')) !!}
                    {!! Form::hidden('_method', 'DELETE') !!}
                    {!! Form::submit(trans('customer.delete'), array('class' => 'btn btn-sm  btn-warning')) !!}
                {!! Form::close() !!}
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



@section('pagejs')

 
<link href="//datatables.net/download/build/nightly/jquery.dataTables.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css"/>
<script src="//datatables.net/download/build/nightly/jquery.dataTables.js"></script>
<script>
$(document).ready(function() {
    $('#customer-index').DataTable();
} );
</script>
@endsection