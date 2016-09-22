@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-primary">
                <div class="panel-heading">New Expenses</div>

                <div class="panel-body">
                    {!! Html::ul($errors->all()) !!}
                    {!! Form::model($expenses, array('route' => array('expenses.update', $expenses->id), 'method' => 'PUT', 'files' => true)) !!}
                    <div class="col-md-5">
                        

                        <div class="form-group">
                        {!! Form::label('spent_at', 'Expense Date *') !!}
                        {!! Form::text('spent_at', null, array('class' => 'form-control datepicker')) !!}
                        </div>

                        <div class="form-group">
                        {!! Form::label('description', 'Description') !!}
                        {!! Form::text('description', null, array('class' => 'form-control')) !!}
                        </div>

                        <div class="form-group">
                        {!! Form::label('post_id', 'Post') !!}
                           {!! Form::select('post_id', $postSettings, null, array('class' => 'form-control')) !!}
                        </div>

                        <div class="form-group">
                        {!! Form::label('value', 'Value Rp.') !!}
                        {!! Form::text('value', null, array('class' => 'form-control')) !!}
                        </div>


                        <div class="form-group">
                            {!! Form::label('received', 'Received') !!}
                            <label  class="radio-inline"><input type="radio" name="received" value="1"  {{ $expenses->received == "1" ?  ' checked ' : ''  }} />Yes</label>

                             <label  class="radio-inline"><input type="radio" name="received" value="0"  {{ $expenses->received == "0" ? ' checked ' : '' }} />No</label>
                        </div>
        
                    </div>
                    <div class="col-md-5">
                        <div class="form-group">
                        {!! Form::label('po_invoice_number', 'PO / Invoice number') !!}
                        {!! Form::text('po_invoice_number', null, array('class' => 'form-control')) !!}
                        </div>
                        <div class="form-group">
                        {!! Form::label('remarks', 'Keterangan') !!}
                        {!! Form::text('remarks', null, array('class' => 'form-control')) !!}
                        </div>
                         <div class="form-group">
                        {!! Form::label('no_resi', 'No Resi') !!}
                        {!! Form::text('no_resi', null, array('class' => 'form-control')) !!}
                        </div>
                        <div class="form-group">
                        {!! Form::label('shipping_method', 'Shipping Method') !!}
                        {!! Form::text('shipping_method', null, array('class' => 'form-control')) !!}
                        </div>
                        <div class="form-group">
                            {!! Form::label('bank_account_id', 'Account') !!}
                          {!! Form::select('bank_account_id', $bankAccounts, null, array('class' => 'form-control')) !!}
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


@section('pagejs')
<script type="text/javascript">
$.fn.datepicker.defaults.format = "yyyy-mm-dd";
$('.datepicker').datepicker();
</script>
@endsection