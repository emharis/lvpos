@extends('app')
@section('content')
{!! Html::script('js/angular.min.js', array('type' => 'text/javascript')) !!}
{!! Html::script('js/sale.js', array('type' => 'text/javascript')) !!}

<div class="container-fluid">
   <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-primary">
                <div class="panel-heading"><span class="glyphicon glyphicon-inbox" aria-hidden="true"></span> {{trans('sale.sales_register')}}</div>

            <div class="panel-body">

                @if (Session::has('message'))
                    <div class="alert alert-success">{{ Session::get('message') }}</div>
                @endif
                {!! Html::ul($errors->all()) !!}
                
                <div class="row" ng-controller="SearchItemCtrl">

                    <div class="col-md-3">
                        <label>{{trans('sale.search_item')}} <input ng-model="searchKeyword" class="form-control"></label>

                        <table class="table table-hover">
                        <tr ng-repeat="item in items  | filter: searchKeyword | limitTo:10">

                        <td>@{{item.item_name}}</td>
                        <td><button class="btn btn-success btn-xs" type="button" ng-click="addSaleTemp(item, newsaletemp)"><span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span></button></td>

                        </tr>
                        </table>
                    </div>

                    <div class="col-md-9">

                        <div class="row">
                            
                            {!! Form::open(array('url' => 'sales', 'class' => 'form-horizontal')) !!}
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="invoice" class="col-sm-3 control-label">{{trans('sale.invoice')}}</label>
                                        <div class="col-sm-9">
                                        <input type="text" class="form-control" id="invoice" value="@if ($sale) {{$sale->id + 1}} @else 1 @endif" readonly/>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="employee" class="col-sm-3 control-label">{{trans('sale.employee')}}</label>
                                        <div class="col-sm-9">
                                        <input type="text" class="form-control" id="employee" value="{{ Auth::user()->name }}" readonly/>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="form-group">
                                        <label for="customer_id" class="col-sm-4 control-label">{{trans('sale.customer')}}</label>
                                        <div class="col-sm-8">
                                        {!! Form::select('customer_id', $customer, Input::get('customer_id') ? Input::get('customer_id') : Input::old('customer_id'), array('class' => 'form-control', 'id' => 'customer_id', 'readonly')) !!}
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="bill_acccount" class="col-sm-4 control-label">Bank  Account</label>
                                        <div class="col-sm-8">
                                        {!! Form::select('bank_account_id', $bankAccounts, Input::old('bank_account_id'), array('class' => 'form-control')) !!}
                                        </div>
                                    </div>
                                </div>
                            
                        </div>
                           
                        <table class="table table-bordered">
                            <tr><th>{{trans('sale.item_id')}}</th><th>{{trans('sale.item_name')}}</th><th>{{trans('sale.price')}}</th><th>Price Ref</th><th>{{trans('sale.quantity')}}</th><th>{{trans('sale.total')}}</th><th>&nbsp;</th></tr>
                            <tr ng-repeat="newsaletemp in saletemp">
                            <td>@{{newsaletemp.item_id}}</td><td>@{{newsaletemp.item.item_name}}</td><td>Rp <input type="text"  autocomplete="off" name="selling_price" ng-change="updateSaleTemp(newsaletemp)" ng-model="newsaletemp.item.selling_price" size="15" value="@{{newsaletemp.item.selling_price}}">
                            </td><td>@{{newsaletemp.price_ref}}</td><td><input type="text" style="text-align:center" autocomplete="off" name="quantity" ng-change="updateSaleTemp(newsaletemp)" ng-model="newsaletemp.quantity" size="2"></td><td>@{{newsaletemp.item.selling_price * newsaletemp.quantity | currency: "Rp "}}</td><td><button class="btn btn-danger btn-xs" type="button" ng-click="removeSaleTemp(newsaletemp.id)"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button></td>
                            </tr>
                        </table>

                        <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="source" class="col-sm-4 control-label">Source</label>
                                        <div class="col-sm-8">
                                        <input type="text" id="source" name="source" readonly class="form-control"/>
                                        </div>
                                    </div>
                                    <div>&nbsp;</div>
                                    <div class="form-group">
                                        <label for="employee" class="col-sm-4 control-label">{{trans('sale.comments')}}</label>
                                        <div class="col-sm-8">
                                        <input type="text" class="form-control" name="comments" id="comments" />
                                        </div>
                                    </div>
                                    <div>&nbsp;</div>
                                    <div class="form-group">
                                        <label for="is_dropshipper" class="col-sm-4 control-label">Dropship</label>
                                        <div class="col-sm-8">
                                        {!! Form::select('is_dropshipper', array('0'=>'No', '1' => 'Yes'), Input::old('is_dropshipper'), array('class' => 'form-control')) !!}
                                        </div>
                                    </div>
                                    <div>&nbsp;</div>
                                    <div class="form-group">
                                        <label for="no_resi" class="col-sm-4 control-label">No. Resi</label>
                                        <div class="col-sm-8">
                                        <input type="text" class="form-control" name="no_resi" id="no_resi" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">

                                    <div class="form-group">
                                     <label for="total" class="col-sm-4 control-label">Closing Date
</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                                {!! Form::text('closing_date', Input::old('closing_date'), array('class' => 'form-control datepicker', 'id' => 'closing_date')) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div>&nbsp;</div>

                                    <div class="form-group">
                                        <label for="shipping_method" class="col-sm-4 control-label">Shipping Method</label>
                                        <div class="col-sm-8">
                                        <input type="text" class="form-control" name="shipping_method" id="shipping_method" />
                                        </div>
                                    </div>
                                     <div>&nbsp;</div>
                                    <div class="form-group">
                                        <label for="shipping_price" class="col-sm-4 control-label">Shipping Price</label>
                                        <div class="col-sm-8">
                                        <input type="text" class="form-control"   name="shipping_price" id="shipping_price" value="@{{shipping_price}}" ng-model="shipping_price" />
                                        </div>
                                    </div>
                                    <div>&nbsp;</div>
                                    <div class="form-group">
                                        <label for="insurance" class="col-sm-4 control-label">Insurance</label>
                                        <div class="col-sm-8">
                                        {!! Form::text('insurance', Input::old('insurance'), array('class' => 'form-control', 'id' => 'insurance')) !!}
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="supplier_id" class="col-sm-4 control-label">{{trans('sale.grand_total')}}</label>
                                        <div class="col-sm-8">
                                            <p class="form-control-static"><b>@{{ sum(saletemp) + parseFloat(shipping_price) | currency: "Rp "}}</b></p>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                            <label for="amount_due" class="col-sm-4 control-label">{{trans('sale.amount_due')}}</label>
                                            <div class="col-sm-8">
                                            <p class="form-control-static">@{{ sum(saletemp) + parseFloat(shipping_price) | currency: "Rp "  }}</p>
                                            </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <button type="submit" class="btn btn-success btn-block">{{trans('sale.submit')}}</button>
                                        </div>
                                    </div>

                                </div>
                            </div>
                            {!! Form::close() !!}
                            
                        

                    </div>

                </div>

            </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('pagejs')
<script type="text/javascript">
 
    $('#shipping_price').attr("value", "0");

    $( "#customer_id" ).change(function() {
        var selectedValue = this.value;

        //make the ajax call
        $.ajax({
            url: 'customersource',
            type: 'GET',
            data: {customer_id : selectedValue},
            success: function(data) {
                $("#source").val(data);
            }
        });
    });

    $.ajax({
            url: 'customersource',
            type: 'GET',
            data: {customer_id : $( "#customer_id" ).val()},
            success: function(data) {
                $("#source").val(data);
            }
        });

</script>
 
<script type="text/javascript">
$.fn.datepicker.defaults.format = "yyyy-mm-dd";
$('.datepicker').datepicker();
</script>
 
@endsection