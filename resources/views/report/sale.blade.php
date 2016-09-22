@extends('app')

@section('content')

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/dt/jq-2.2.3/dt-1.10.12/datatables.min.css"/>

<div class="container">
	<div class="row">
		<div class="col-md-10 col-md-offset-1">
			<div class="panel panel-primary">
				<div class="panel-heading">{{trans('report-sale.reports')}} - {{trans('report-sale.sales_report')}}</div>

				<div class="panel-body">

                       <div>
                            {!! Form::open(['method'=>'GET','url'=>'reports/sales','class'=>'form-inline'])  !!}
          
                            <div class="form-group"><label>From </label> 
                                {!! Form::text('from', Input::get('from'), array('class' => 'form-control datepicker')) !!} 
                            </div>
                            <div class="form-group"><label>To </label> 
                                {!! Form::text('to', Input::get('to'), array('class' => 'form-control datepicker')) !!} 
                            </div>
                            @if (Auth::user()->is_admin == 1)
                                <div class="form-group"><label>User </label> 
                                    {!! Form::select('user_id', $employees, Input::get('user_id'), array('class' => 'form-control', 'id' => 'user_filter')) !!}
                                </div>
                            @endif
                             {!! Form::submit('Filter', array('class' => 'btn btn-info btn-sm')) !!}
                            {!! Form::close() !!}
                        </div>
                    <div>&nbsp;</div>
                    <div class="row">

                        <div class="col-md-4">
                            <div class="well well-sm">{{trans('report-sale.grand_total')}}: {{ number_format($sumTotal, 2) }}</div>
                        </div>
                        <div class="col-md-4">
                            <div class="well well-sm">{{trans('report-sale.grand_profit')}}:{{ number_format($sumProfit, 2) }}</div>
                        </div>
                    </div>
<table class="table table-striped table-bordered" id="sales-report">
    <thead>
        <tr>
            <td>{{trans('report-sale.sale_id')}}</td>
            <td>{{trans('report-sale.date')}}</td>
            <td>{{trans('report-sale.items_purchased')}}</td>
            <td>{{trans('report-sale.sold_by')}}</td>
            <td>{{trans('report-sale.sold_to')}}</td>
            <td>{{trans('report-sale.total')}}</td>
            <td>{{trans('report-sale.profit')}}</td>
            <td>Price Ref</td>
            <td>Bank Account</td>
            <td>&nbsp;</td>
        </tr>
    </thead>
    <tbody>
    @foreach($saleReport as $value)
        <tr>
            <td>{{ $value->id }}</td>
            <td>{{ $value->created_at }}</td>
            <td>{{DB::table('sale_items')->where('sale_id', $value->id)->sum('quantity')}}</td>
            <td>{{ $value->user->name }}</td>
            <td>{{ $value->customer->name }}</td>
            <td>{{ number_format(DB::table('sale_items')->where('sale_id', $value->id)->sum('total_selling'), 2)}}</td>
            <?php $saleProfit = DB::table('sale_items')->where('sale_id', $value->id)->sum('total_selling') - $value->price_ref; 
            ?>
            <td @if($saleProfit < 0) style='color:red' @endif>{{ number_format($saleProfit, 2)}}</td>
            <td>{{ number_format($value->price_ref,2 ) }}</td>
            <td>{{ $value->bank_account }}</td>
            <td>
                <a class="btn btn-small btn-info" data-toggle="collapse" href="#detailedSales{{ $value->id }}" aria-expanded="false" aria-controls="detailedReceivings">
                    {{trans('report-sale.detail')}}</a>
            </td>
        </tr>
        
            <tr class="collapse" id="detailedSales{{ $value->id }}">
                <td colspan="10">
                    <table class="table">
                        <tr>
                            <td>{{trans('report-sale.item_id')}}</td>
                            <td>{{trans('report-sale.item_name')}}</td>
                            <td>{{trans('report-sale.quantity_purchase')}}</td>
                            <td>{{trans('report-sale.total')}}</td>
                            <td>Total Price Ref</td>
                            <td>{{trans('report-sale.profit')}}</td>
                        </tr>
                        @foreach(ReportSalesDetailed::sale_detailed($value->id) as $SaleDetailed)
                        <tr>
                            <td>{{ $SaleDetailed->item_id }}</td>
                            <td>{{ $SaleDetailed->item->item_name }}</td>
                            <td>{{ $SaleDetailed->quantity }}</td>
                            <td>{{ number_format($SaleDetailed->total_selling, 2) }}</td>
                             <td>{{ number_format($SaleDetailed->total_price_ref, 2) }}</td>
                             <?php $profit = $SaleDetailed->total_selling - $SaleDetailed->total_price_ref;?>

                            <td @if($profit < 0) style='color:red' @endif>{{ number_format($profit, 2)  }}</td>
                        </tr> 
                        @endforeach
                    </table>
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
$.fn.datepicker.defaults.format = "yyyy-mm-dd";
$('.datepicker').datepicker();

$("#user_filter").prepend("<option value='' selected='selected'></option>");
</script>


@endsection