<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body>
<center><h1>Purchase Order</h1></center>
<table width="100%">
<tr>
    <td>
        {{ $purchaseOrder->supplier->company_name }}
    </td>
    <td style="text-align:right;">
        PO Date: {{ $purchaseOrder->po_date }}
    </td>
</tr>
</table>
<br/><br/>
<table width="100%" border="1">
    @foreach ($items as $item)
    <tr>
       <td>{{ $item->item->item_name }}</td>
       <td>{{ $item->cost_price }}</td>
       <td>{{ $item->quantity }}</td>
       <td>{{ $item->total_cost }}</td>
    </tr>
    @endforeach
</table>
Notes: More PO format to be confirmed later..
</body>
</html>