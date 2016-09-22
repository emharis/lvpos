@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-,md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">Sales</div>

				<div class="panel-body">
				 <a class="btn btn-small btn-success" href="{{ URL::to('sales/create') }}">New Sales</a>
				<hr />
@if (Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
@endif

<table class="table table-striped table-bordered" id="sale-index">
    <thead>
        <tr>
            <td>Sale #</td>
            <td>Customer</td>
            <td>Date</td>
            <td>Bank Account</td>
            <td>Sub Total</td>
            <td>Sub Total of Price Ref</td>
            <td>Shipping Method</td>
            <td>Shipping Charges</td>
            <td>Total</td>
            <td>No Resi</td>
            <td>Dropship</td>
            <td></td>
        </tr>
    </thead>
    <tbody>
    @foreach($sales as $value)
        <tr>
            <td>{{ $value->id }}</td>
            <td>{{ $value->customer->name }}</td>
            <td>{{ date("Y-m-d", strtotime($value->created_at)) }}</td>
            <td>{{ $value->bank_account_name }}</td>
            <td>{{ number_format($value->sub_total, 2) }}</td>
            <td>
                {{ number_format($value->sub_total_price_ref, 2) }}
            </td>

            <td>{{ $value->shipping_method }}</td>
            <td>{{ number_format($value->shipping_price, 2) }}</td>
            <td style="<?php if($value->total < $value->sub_total_price_ref) echo 'color: red;'  ?>">{{ number_format($value->total,2) }}</td>
             <td>{{ $value->no_resi }}</td>
             <td>{{ $value->is_dropshipper }}</td>
            <td>

                <a class="btn btn-sm btn-success" target="_blank" href="{{ URL::to('sales/' . $value->id . '/print') }}">Print</a>

                 <a class="btn btn-sm btn-info"   href="{{ URL::to('sales/' . $value->id . '/edit') }}">Edit</a>
                    @if (Auth::user()->is_admin == 1)
                        {!! Form::open(array('url' => 'sales/' . $value->id, 'class' => 'delete-form')) !!}
                            {!! Form::hidden('_method', 'DELETE') !!}
                            {!! Form::submit('Delete', array('class' => 'btn btn-sm  btn-warning')) !!}
                        {!! Form::close() !!}
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

@section('pagejs')
<script type="text/javascript">
$('.delete-form').submit(function() {
    var c = confirm("Are you sure want to delete?");
    return c; 
});
</script>


<link href="//datatables.net/download/build/nightly/jquery.dataTables.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css"/>
<script src="//datatables.net/download/build/nightly/jquery.dataTables.js"></script>
<script>
$(document).ready(function() {
    $('#sale-index').DataTable();
} );
</script>
@endsection