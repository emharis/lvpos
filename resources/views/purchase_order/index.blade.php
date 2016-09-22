@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">Purchase Orders</div>

				<div class="panel-body">
				<a class="btn btn-small btn-success" href="{{ URL::to('po/create') }}">New PO</a>
				<hr />
@if (Session::has('message'))
    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
@endif

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <td>PO #</td>
            <td>Supplier</td>
            <td>Date</td>
            <td>Delivery Date</td>
            <td>Sub Total</td>
            <td>Add. Charges.</td>
            <td>Account</td>
            <td>Amount</td>
            <td>Quotation</td>
            <td></td>
        </tr>
    </thead>
    <tbody>
    @foreach($purchaseOrders as $value)
        <tr>
            <td>{{ $value->po_number }}</td>
            <td>{{ $value->supplier->company_name }}</td>
            <td>{{ $value->po_date }}</td>
            <td>{{ $value->delivery_date }}</td>
            <td>{{ number_format($value->sub_total, 2) }}</td>
            <td>{{ number_format($value->additional_charges, 2) }}</td>
            <td>{{ $value->bank_account_name }}</td>
            <td>{{ number_format(($value->sub_total +  $value->additional_charges), 2) }}</td>
            <td>@if ($value->quotation_path) 
                <a class="btn btn-sm btn-primary" href="{{ URL::to('/uploads/' . $value->quotation_path) }}" target="_blank">Download</a> 
                @endif</td>
            <td>

                <a class="btn btn-sm btn-info" href="{{ URL::to('po/' . $value->id . '/edit') }}">Edit</a>
                <a class="btn btn-sm btn-success" target="_blank" href="{{ URL::to('po/' . $value->id . '/pdf') }}">PDF</a>
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