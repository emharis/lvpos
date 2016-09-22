@extends('app')

@section('content')


<div class="container">
    <div class="row">
        <div class="col-md-12">

            
            <div class="panel panel-default">
              <div class="panel-heading">Posisi Kas</div>
              <div class="panel-body">
                <div class="col-md-6">
                   <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <td>Account</td>
                                <td>Expense</td>
                                <td>Revenue</td>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($bank as $account)
                            <tr>
                                <td>{{ $account['name'] }}</td>
                                <td>{{ number_format($account['out'], 2) }}</td>
                                <td>{{ number_format($account['in'], 2) }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>


                </div>

                <div class="col-md-6">
                    <div id="posisikascharts" style="width: 100%;height:400px;"></div>
                </div>
                
              </div>

            </div>



            <div class="panel panel-default">
                <div class="panel-heading">Monthly Sales</div>
                <div class="panel-body">
                    <div class="col-md-6">
                       <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <td>Month</td>
                                    <td>Revenue</td>
                                    <td>Cost</td>
                                    <td>Profit</td>
                                    <td>Margin (%)</td>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($salesReport as $month => $item)
                                <tr>
                                    <td>{{ $month }}</td>
                                    <td>{{ number_format($item['revenue'], 2) }}</td>
                                    <td>{{ number_format($item['cost'], 2) }}</td>
                                    <td>{{ number_format(($item['revenue'] - $item['cost']), 2) }}</td>
                                    <td>{{ round((($item['revenue'] - $item['cost']) / $item['revenue'])  * 100, 2) }}</td>
                                </tr>
                            @endforeach
                            <tr style="font-weight:bold;">
                                 <td>Total</td>
                                 <td>{{ number_format($totalRevenue, 2) }}</td>
                                 <td>{{ number_format($totalCost, 2) }}</td>
                                 <td>{{ number_format($totalProfit, 2) }}</td>
                                 <td>{{ number_format($percentageAverage, 2) }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <div id="salescharts" style="width: 100%;height:400px;"></div>
                    </div>
                </div>
            </div>
            
             <div class="panel panel-default">
              <div class="panel-heading">Stock Status</div>
              <div class="panel-body"><div class="well">Total Stock Amount: {{ number_format($totalStockAmount, 2) }}</div></div>
            </div>
            

             <div class="panel panel-default">
                <div class="panel-heading">Expenses</div>
                <div class="panel-body">

                    <div class="col-md-6">
                       <table class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    <td>Month</td>
                                    <td>Expenses</td>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($monthExpenses as $item)
                                <tr>
                                    <td>{{ $item->monthname }}</td>
                                    <td>{{ number_format($item->total_out, 2) }}</td>
                                </tr>
                            @endforeach
                            <tr style="font-weight:bold;">
                                 <td>Total</td>
                                 <td>{{ number_format($totalExpenses, 2) }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <div id="expensescharts" style="width: 100%;height:400px;"></div>
                    </div>
                </div>
            </div>



        </div>
    </div>
</div>
@endsection

@section('pagejs')
<script src="http://code.highcharts.com/highcharts.js"></script>
<script>
$(function () { 


    $('#posisikascharts').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Posisi Kas'
        },
        xAxis: {
            categories: <?php echo json_encode($posisikascharts['x']);?>
        },
        yAxis: {
            title: {
                text: 'Volume'
            }
        },
        series: [{
            name: 'Expense',
            data: <?php echo json_encode($posisikascharts['expense'], JSON_NUMERIC_CHECK);?>
        }, {
            name: 'Revenue',
            data: <?php echo json_encode($posisikascharts['revenue'],JSON_NUMERIC_CHECK);?>
        }]
    });


    $('#salescharts').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Monthly Sales'
        },
        xAxis: {
            categories: <?php echo json_encode($salescharts['x']);?>
        },
        yAxis: {
            title: {
                text: 'Volume'
            }
        },
        series: [{
            name: 'Revenue',
            data: <?php echo json_encode($salescharts['revenue'], JSON_NUMERIC_CHECK);?>
        }, {
            name: 'Cost',
            data: <?php echo json_encode($salescharts['cost'], JSON_NUMERIC_CHECK);?>
        }, {
            name: 'Profit',
            data: <?php echo json_encode($salescharts['profit'], JSON_NUMERIC_CHECK);?>
        }, {
            name: 'Margin',
            data: <?php echo json_encode($salescharts['margin'], JSON_NUMERIC_CHECK);?>
        }]
    });


    $('#expensescharts').highcharts({
        chart: {
            type: 'column'
        },
        title: {
            text: 'Monthly Expenses'
        },
        xAxis: {
            categories: <?php echo json_encode($expensescharts['x']);?>
        },
        yAxis: {
            title: {
                text: 'Amount'
            }
        },
        series: [{
            name: 'Expenses',
            data: <?php echo json_encode($expensescharts['expenses'], JSON_NUMERIC_CHECK);?>
        }]
    });
});

</script>
@endsection