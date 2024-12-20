<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 13px;
            color: rgb(55, 55, 55);
            margin: 0;
            padding: 0;
        }

        .container {
            width: 100%;
            margin: auto;
            /*border: 1px solid grey;*/
            box-sizing: border-box;
        }

        .header {
            padding: 0px 0px;
            border-bottom: 2px solid rgb(230, 230, 230);
            font-size: 24px;
        }

        .section {
            padding: 5px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th, .table td {
            padding: 8px 4px;
            border: 1px solid rgb(230, 230, 230);
            text-align: left;
        }

        .align-right {
            text-align: right;
        }

        .align-center {
            text-align: center;
        }

        .border-none {
            border: none;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        Online Order No # {{ $data->order_no ?? '' }}
    </div>
    <div class="section">
        <table class="table">
            <tr>
                <td style="width: 50%; vertical-align: top;border: none;">
                    <strong>Delivery Address</strong><br>
                    {{ $data->customer_name ?? '' }}<br>
                    {{ $data->customer_mobile ?? '' }}/ {{ $data->customer_phone ?? '' }}<br>
                    {{ $data->delivery_address ?? '' }}<br>
                    {{ $data->delivery_zip ?? '' }} {{ $data->delivery_city ?? '' }}<br>
                    {{ $data->delivery_country ?? '' }}
                </td>
                <td style="width: 50%; vertical-align: top; text-align: right;border: none;">
                    <strong>Invoice Address</strong><br>
                    {{ $data->customer_name ?? '' }}<br>
                    {{ $data->customer_mobile ?? '' }}/ {{ $data->customer_phone ?? '' }}<br>
                    {{ $data->delivery_address ?? '' }}<br>
                    {{ $data->delivery_zip ?? '' }} {{ $data->delivery_city ?? '' }}<br>
                    {{ $data->delivery_country ?? '' }}
                </td>
            </tr>
        </table>
        <table class="table" style="margin-top: 15px;white-space: nowrap;">
            <tr>
                <td style="width: 30%; vertical-align: top;border: none;">
                    <strong>Shipping</strong><br>
                    {{ $data->delivery_type ?? '' }}
                </td>
                <td style="width: 20%; vertical-align: top;border: none;">
                    <strong>Payment</strong><br>
                    {{ $data->payment_method ?? '' }}
                </td>
                <td style="width: 60%; vertical-align: top;border: none;">
                    <table style="width: 100%;">
                        <tbody>
                        <tr>
                            <th class="align-right" style="border: none;">Date</th>
                            <td style="border: none;">:</td>
                            <td class="align-right"
                                style="border: none;">{{ date('d M, Y',strtotime($data->created_at)) }}</td>
                        </tr>
                        <tr>
                            <th class="align-right" style="border: none;">Requisition</th>
                            <td style="border: none;">:</td>
                            <td class="align-right" style="border: none;">{{ $data->order_no ?? '' }}</td>
                        </tr>
                        <tr>
                            <th class="align-right" style="border: none;">Customer number</th>
                            <td style="border: none;">:</td>
                            <td class="align-right" style="border: none;">{{ $data->customer_mobile ?? '' }}</td>
                        </tr>
                        <tr>
                            <th class="align-right" style="border: none;">Seller</th>
                            <td style="border: none;">:</td>
                            <td class="align-right" style="border: none;"></td>
                        </tr>
                        <tr>
                            <th class="align-right" style="border: none;">Buyer</th>
                            <td style="border: none;">:</td>
                            <td class="align-right" style="border: none;"></td>
                        </tr>
                        <tr>
                            <th class="align-right" style="border: none;">Online order no.</th>
                            <td style="border: none;">:</td>
                            <td style="padding: 0;border: none;"><span
                                    style="background-color: rgb(10, 122, 10); color: white; border-radius: 4px; padding: 2px 4px;">{{ $data->order_no ?? '' }}</span>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>
        <table class="table" style="margin-top: 10px;">
            <thead>
            <tr>
                <th>Status</th>
                <th>SKU</th>
                <th>Name</th>
                <th class="align-center">Qty</th>
                <th class="align-right">Unit price</th>
                <th class="align-right">Total excl. Tax</th>
                <th class="align-right">Total incl. Tax</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data->order_items as $item)
                <tr>
                    <td class="align-center">{{ $loop->index + 1 }}</td>
                    <td>{{ $item->product->product_number ?? '' }}</td>
                    <td>{{ $item->product->product_name ?? '' }}</td>
                    <td class="align-center">{{ $item->quantity ?? '' }}</td>
                    <td class="align-right">{{ $item->order_price ?? '' }}</td>
                    <td class="align-right">6</td>
                    <td class="align-right">7</td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>

</div>
<div style="margin-top: 16px;" class="align-right">
    <div style="font-size: 24px;">
        <span style="margin-right: 4px;">{{ $data->total_excluding_vat ?? '' }} SEK</span>
        <span>excl. tax</span>
    </div>
    <div style="font-size: 16px;">
        <span style="margin-right: 4px;">{{ $data->total_including_vat ?? '' }} SEK</span>
        <span>incl. tax</span>
    </div>
</div>
</body>
</html>
