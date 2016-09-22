@extends('app')
@section('content')
{!! Html::script('js/angular.min.js', array('type' => 'text/javascript')) !!}
{!! Html::script('js/po_edit.js', array('type' => 'text/javascript')) !!}
<div class="container-fluid">
   <div class="row">
        <div class="col-md-12">
            <div class="panel panel-primary">
                <div class="panel-heading"><span class="glyphicon glyphicon-inbox" aria-hidden="true"></span> Update Purchaser Order</div>

            <div class="panel-body">

                @if (Session::has('message'))
                    <div class="alert alert-success">{{ Session::get('message') }}</div>
                @endif
                {!! Html::ul($errors->all()) !!}
                
                <div class="row" ng-controller="SearchItemCtrl">

                    <div class="col-md-3">
                        <label>Search Item: <input ng-model="searchKeyword" class="form-control"></label>

                        <table class="table table-hover">
                        <tr ng-repeat="item in items  | filter: searchKeyword | limitTo:10">

                        <td>[@{{item.upc_ean_isbn}}] @{{item.item_name}}</td>
                        <td><button class="btn btn-success btn-xs" type="button" ng-click="addPoTemp(item, newpotemp)"><span class="glyphicon glyphicon-share-alt" aria-hidden="true"></span></button></td>

                        </tr>
                        </table>
                    </div>

                    <div class="col-md-9">

                        <div class="row">
                            {!! Form::model($purchaseOrder, array('route' => array('po.update', $purchaseOrder->id), 'method' => 'PUT', 'files' => true, 'class' => 'form-horizontal')) !!}
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="invoice" class="col-sm-3 control-label">PO #</label>
                                        <div class="col-sm-9">
                                        {!! Form::text('po_number', $purchaseOrder->po_number, array('class' => 'form-control', 'placeholder' => 'e.g. 001', 'id' => 'po_number')) !!}
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
                                        <label for="supplier_id" class="col-sm-4 control-label">Supplier</label>
                                        <div class="col-sm-8">
                                        {!! Form::select('supplier_id', $supplier, $purchaseOrder->supplier_id, array('class' => 'form-control')) !!}
                                        </div>
                                    </div>
                                </div>
                            
                        </div>
                           
                        <table class="table table-bordered">
                            <tr><th>Item ID</th><th>Item Name</th><th>Purchase Price  (Rp)</th><th>Quantity</th><th>Total</th><th>&nbsp;</th></tr>
                            <tr ng-repeat="newsaletemp in potemp">
                            <td>@{{newsaletemp.item_id}}</td><td>@{{newsaletemp.item.item_name}}</td><td><input type="text"  autocomplete="off" name="cost_price" ng-change="updatePoTemp(newsaletemp)" ng-model="newsaletemp.cost_price" size="15" value="@{{newsaletemp.cost_price}}"></td><td><input type="text" style="text-align:center" autocomplete="off" name="quantity" ng-change="updatePoTemp(newsaletemp)" ng-model="newsaletemp.quantity" size="2"></td><td>@{{newsaletemp.cost_price * newsaletemp.quantity | currency:"Rp "}}</td><td><button class="btn btn-danger btn-xs" type="button" ng-click="removePoTemp(newsaletemp.id)"><span class="glyphicon glyphicon-trash" aria-hidden="true"></span></button></td>
                            </tr>
                        </table>

                        <div class="row">
                                <div class="col-md-6">

                                    <div class="form-group">
                                        <label for="total" class="col-sm-4 control-label">Sub Total</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <div class="input-group-addon">Rp</div>
                                                <input type="text" class="form-control" id="sub_total" name="sub_total" value="@{{sum(potemp)}}"/>
                                            </div>
                                        </div>
                                    </div>

                                    <div>&nbsp;</div>
                                    <div class="form-group">
                                        <label for="total" class="col-sm-4 control-label">Additional Charges</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <div class="input-group-addon">Rp</div>
                                                {!! Form::text('additional_charges', $purchaseOrder->additional_charges, array('class' => 'form-control', 'id' => 'additional_charges')) !!}
                                            </div>
                                        </div>
                                    </div>

                                     <div>&nbsp;</div>
                                    <div class="form-group">
                                        <label for="bank_account_id" class="col-sm-4 control-label">Charged Account</label>
                                        <div class="col-sm-8">
                                        {!! Form::select('bank_account_id', $bankAccounts, null, array('class' => 'form-control')) !!}
                                        </div>
                                    </div>

                                    <div>&nbsp;</div>
                                    <div class="form-group">
                                        <label for="comments" class="col-sm-4 control-label">Remarks</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <div class="input-group-addon">...</div>
                                                {!! Form::text('comments', null, array('class' => 'form-control', 'id' => 'comments')) !!} 
                                            </div>
                                        </div>
                                    </div>


                                </div>
                                <div class="col-md-6">
 
                                    <div>&nbsp;</div>
                                    <div class="form-group">
                                        <label for="total" class="col-sm-4 control-label">PO Date
</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                                <?php $podate =  ($purchaseOrder->po_date == '0000-00-00')  ? '' : $purchaseOrder->po_date; ?>
                                                {!! Form::text('po_date', $podate, array('class' => 'form-control datepicker', 'id' => 'po_date')) !!}
                                            </div>
                                        </div>
                                    </div>
                                    <div>&nbsp;</div>
                                    <div class="form-group">
                                        <label for="total" class="col-sm-4 control-label">Delivery Date
</label>
                                        <div class="col-sm-8">
                                            <div class="input-group">
                                                <div class="input-group-addon"><i class="fa fa-calendar"></i></div>
                                                <?php $dodate =  ($purchaseOrder->delivery_date == '0000-00-00')  ? '' : $purchaseOrder->delivery_date; ?>
                                                {!! Form::text('delivery_date', $dodate , array('class' => 'form-control datepicker', 'id' => 'delivery_date')) !!}
                                            </div>
                                        </div>
                                    </div>
                                     <div>&nbsp;</div>

                                    <div class="form-group">
                                                                       
                                        @if ($purchaseOrder->quotation_path)
                                        <label for="quotation" class="col-sm-4 control-label">Uploaded Quotation: </label> {{ $purchaseOrder->quotation_path }}
                                        <br/><a class="btn btn-sm btn-primary" href="{{ URL::to('/uploads/' . $purchaseOrder->quotation_path) }}" target="_blank">Download</a> 
                                        @else 
                                        <label for="quotation" class="col-sm-4 control-label">Upload Quotation: </label> 
                                        @endif

                                        <div class="col-sm-8">
                                            @if ($purchaseOrder->quotation_path) Reupload New File @endif
                                        {!! Form::file('quotation', null, array('class' => 'form-control')) !!}
                                             
                                        </div>
                                        <div>&nbsp;</div>
                                    </div>
                                    <div>&nbsp;</div>
                                    <div class="form-group">
                                        <div class="col-sm-12">
                                            <button type="submit" class="btn btn-success btn-block">Update</button>
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
$.fn.datepicker.defaults.format = "yyyy-mm-dd";
$('.datepicker').datepicker();
</script>
@endsection