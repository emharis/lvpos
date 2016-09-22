@extends('app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">



            <div class="panel panel-primary">
                <div class="panel-heading">Cost &amp; Expenses</div>

                <div class="panel-body">
              
                               
                <div >
                <a class="btn btn-small btn-success" href="{{ URL::to('expenses/create') }}">New Expenses</a>

                @if (Session::has('message'))
                    <p class="alert {{ Session::get('alert-class', 'alert-info') }}">{{ Session::get('message') }}</p>
                @endif
                </div>
                
                <div>&nbsp;</div>
                <div>
                    {!! Form::open(['method'=>'GET','url'=>'expenses','class'=>'form-inline'])  !!}
  
                    <div class="form-group"><label>From </label> 
                        {!! Form::text('from', Input::get('from'), array('class' => 'form-control datepicker')) !!} 
                    </div>
                    <div class="form-group"><label>To </label> 
                        {!! Form::text('to', Input::get('to'), array('class' => 'form-control datepicker')) !!} 
                    </div>
                    @if (Auth::user()->is_admin == 1)
                        <!--<div class="form-group"><label>User </label> 
                            {!! Form::select('user_id', $employees, Input::get('user_id'), array('class' => 'form-control', 'id' => 'user_filter')) !!}
                        </div>-->

                        <div class="form-group"><label>Post </label> 
                            {!! Form::select('post_id', $posts, Input::get('post_id'), array('class' => 'form-control', 'id' => 'post_id_filter')) !!}
                        </div>
                    @endif
                     {!! Form::submit('Filter', array('class' => 'btn btn-info btn-sm')) !!}
                    {!! Form::close() !!}
                </div>
                <div>&nbsp;</div>
  

<table class="table table-striped table-bordered" id="expense-index">
    <thead>
        <tr>
            <td>Date</td>
            <td>Description</td>
            <td>Post</td>
            <td>Value</td>
            <td>Received</td>
            <td>PO / Invoice #</td>
            <td>Keterangan</td>
            <td>Account</td>
            <td></td>
        </tr>
    </thead>
    <tbody>
    @foreach($expenses as $value)
        <tr>
            <td>{{ $value->spent_at }}</td>
            <td>{{ $value->description }}</td>
            <td>{{ $value->post_name }}</td>
            <td>{{ $value->received == 1 ? 'Yes' : 'No' }}</td>
            <td>{{ number_format($value->value, 2) }}</td>
            <td>{{ $value->po_invoice_number }}</td>
            <td>{{ $value->remarks }}</td>
            <td>{{ $value->bank_account_name }}</td>
            <td>

                <a class="btn btn-sm btn-info" href="{{ URL::to('expenses/' . $value->id . '/edit') }}">Edit</a>
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

//$("#user_filter").prepend("<option value='' selected='selected'></option>");
$("#post_id_filter").prepend("<option value='' selected='selected'></option>");
</script>

<link href="//datatables.net/download/build/nightly/jquery.dataTables.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.12/css/dataTables.bootstrap.min.css"/>
<script src="//datatables.net/download/build/nightly/jquery.dataTables.js"></script>
<script>
$(document).ready(function() {
    $('#expense-index').DataTable();
} );
</script>
@endsection