<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body style="font-family: sans-serif;">
<table width="600px" style="border: 3px solid #000;" cellpadding=0>
<tr>
    <td style="vertical-align: middlel;font-size: 26px;font-weight: bold; padding: 10px;width:420px">
       {{ $sales->store_name }}
    </td>
    <td style="font-size: 14px">
        <b>Tanggal</b><br/>
        {{ $sales->created_at->format('d/m/Y') }}
    </td>
</tr>
<tr>
    <td colspan="2" style="border-top: 3px solid #000;padding: 5px;">
        <table style="border: 1px solid #ccc;width: 100%;">
            <tr>
                <td style="font-size: 14px;line-height: 24px;">
                    <b style="font-size: 16px;">Tujuan Pengiriman</b><br/>
                    <b>{{ $sales->customer->name}}</b><br/>
                    {{ $sales->customer->address}}<br/>
                    {{ $sales->customer->city}}, {{ $sales->customer->zip}} <br/>
                    {{ $sales->customer->state}}<br/>
                    Telp: {{ $sales->customer->phone_number}}

                </td>
                <td style="text-align:  right">
                    <img src="{{ asset('/images/sicumi_qrcode_2.png') }}"/>
                </td>
            </tr>

        </table>
    </td>
</tr>
<tr>
   <td colspan="2" style="border-top: 3px solid #000;padding: 5px;">
        <table style="width: 100%;">
            <tr>
                <td width="380px">
                    <b>Content:</b> {{ $saleItems[0]->item->item_name }}
                </td>
                <td>
                    <b>Value:</b> {{ number_format($sales->total, 0) }}
                </td>
            </tr>
        </table>
   </td> 
</tr>
<tr>
   <td colspan="2" style="border-top: 3px solid #000;padding: 5px;">
        <table style="width: 100%;">
            <tr>
                <td width="380px">
                    <b>No Tel Pengirim:</b> 0821-5300-5379
                </td>
                <td>
                    <b>Insurance:</b> {{ number_format($sales->insurance, 0) }}
                </td>
            </tr>
        </table>
   </td> 
</tr>
<tr>
   <td colspan="2" style="border-top: 3px solid #000;padding: 5px;">
        <table style="width: 100%;">
            <tr>
                <td width="380px">
                    <b>Kurir:</b> {{ $sales->shipping_method }}
                </td>
                <td>
                    <b>Biaya Kurir:</b> {{ number_format($sales->shipping_price , 0) }}
                </td>
            </tr>
        </table>
   </td> 
</tr>
</table>
<table  width="600px" >
    <tr><td style="text-align:right;font-size: 24px"><b>PLEASE HANDLE WITH CARE</b></td></tr>
</table>
</html>