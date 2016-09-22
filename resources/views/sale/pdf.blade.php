@extends('app')
@section('content')
{!! Html::script('js/angular.min.js', array('type' => 'text/javascript')) !!}
{!! Html::script('js/app.js', array('type' => 'text/javascript')) !!}
<style>
table td {
    border-top: none !important;
}
</style>
<div class="container-fluid">
   <div class="row">
        <div class="col-md-12" style="text-align:center">
            DEMO POS     
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            {{trans('sale.customer')}}: {{ $sales->customer->name}}<br />
            Store: {{ $sales->store_name}}<br />
            {{trans('sale.sale_id')}}: SALE{{$sales->id}}<br />
            {{trans('sale.employee')}}: {{$sales->user->name}}<br />
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="table-responsive">
           <table class="table">
                <tr>
                    <td>{{trans('sale.item')}}</td>
                    <td>{{trans('sale.price')}}</td>
                    <td>{{trans('sale.qty')}}</td>
                    <td>{{trans('sale.total')}}</td>
                </tr>
                @foreach($saleItems as $value)
                <tr>
                    <td>{{$value->item->item_name}}</td>
                    <td>{{ number_format($value->selling_price, 2)}}</td>
                    <td>{{$value->quantity}}</td>
                    <td>{{number_format($value->total_selling, 2)}}</td>
                </tr>
                @endforeach
                <tr><td></td><td></td>
                    <td align="right">Sub Total</td>
                    <td>{{number_format($sales->sub_total, 2)}}</td>
                </tr>
                <tr><td></td><td></td>
                    <td align="right">Shipping Charges</td>
                    <td>{{number_format($sales->shipping_price, 2)}}</td>
                </tr>
                <tr><td></td><td></td>
                    <td align="right"><b>Total</b></td>
                    <td><b>{{number_format($sales->total, 2)}}</b></td>
                </tr>
            </table>
        </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            {{trans('sale.payment_type')}}: {{$sales->payment_type}}
        </div>
    </div>
    <hr class="hidden-print"/>
    <div class="row">
        <div class="col-md-8">
            &nbsp;
        </div>
        <div class="col-md-2">
            <a href="{{ url('/sales/'.$sales->id.'/print') }}" type="button" class="btn btn-info pull-right hidden-print">Print</a>
        </div>
        <div class="col-md-2">
            <a href="{{ url('/sales/create') }}" type="button" class="btn btn-info pull-right hidden-print">{{trans('sale.new_sale')}}</a>
        </div>
    </div>
</div>
<script>
function printInvoice() {
    window.print();
}
</script>
@endsection