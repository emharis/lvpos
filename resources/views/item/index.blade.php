@extends('app')

@section('content')
<div class="container">
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-primary">
				<div class="panel-heading">{{trans('item.list_items')}}</div>
               
				<div class="panel-body" style="overflow-x: auto;">
				 @if (Auth::user()->is_admin == 1)
                 <a class="btn btn-small btn-success" href="{{ URL::to('items/create') }}">{{trans('item.new_item')}}</a>

				<hr />
                @endif
@if (Session::has('message'))
	<div class="alert alert-info">{{ Session::get('message') }}</div>
@endif

<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <td>{{trans('item.upc_ean_isbn')}}</td>
            <td>{{trans('item.item_name')}}</td>
            <td>Item Category</td>

            <td>Price Ref (Selling Price)</td>
            @if (Auth::user()->is_admin == 1)
                <td>Revenue</td>
            @endif
            @if (Auth::user()->is_admin == 1)
                <td>Sold</td>
            @endif
            <td>Ready Stock</td>
            @if (Auth::user()->is_admin == 1)
                <td>Persediaan</td>
            @endif
            <td>Image</td>
            @if (Auth::user()->is_admin == 1)
            <td>&nbsp;</td>
            @endif
        </tr>
    </thead>
    <tbody>
    @foreach($item as $value)
        <tr>
            {{-- <td>{{ $value->upc_ean_isbn }}</td>
            <td>{{ $value->item_name }}</td>
            <td>{{ $value->category->name }}</td>
           
            <td>{{ number_format($value->selling_price, 2) }}</td>
            @if (Auth::user()->is_admin == 1)
            <td>{{ number_format($value->revenue, 2) }}</td>
            @endif
            @if (Auth::user()->is_admin == 1)
            <td>{{ $value->sold }}</td>
            @endif
            <td>{{ $value->quantity }}</td>
            @if (Auth::user()->is_admin == 1)
            <td>{{ number_format($value->persediaan, 2) }}</td>
            @endif
            <td>{!! Html::image(url() . '/images/items/' . $value->avatar, 'a picture', array('class' => 'thumb')) !!}</td>

             @if (Auth::user()->is_admin == 1)
                <td>
                   
                    <a class="btn btn-sm btn-success" href="{{ URL::to('inventory/' . $value->id . '/edit') }}">{{trans('item.inventory')}}</a>
                    
                    <a class="btn btn-sm btn-info" href="{{ URL::to('items/' . $value->id . '/edit') }}">{{trans('item.edit')}}</a>
                    
                </td>
            @endif --}}
            
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