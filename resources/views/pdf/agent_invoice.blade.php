<!DOCTYPE html>
<html>
<head>
    <title>Invoice</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: rgb(55, 55, 55);
        }

        .container {
            width: 100%;
            margin: auto;
            box-sizing: border-box;
        }

        .section {
            padding: 0px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
        }

        .table th, .table td {
            padding: 8px 4px;
            /*border: 1px solid rgb(230, 230, 230);*/
            text-align: left;
        }

    </style>
</head>
<body>
<div class="container">
    <div class="section">
        <table class="table">
            <tr>
                <td style="width: 60%;vertical-align: top;border: none">
                    <img src="https://dev.tellecto.se/assets/images/logo/logo.svg" alt="" width="150px">
                    <table class="table">
                        <tr>
                            <td style="width: 50%; vertical-align: top;border: none;">
                                <strong style="font-size: 15px; color: black">Billing Address</strong><br>
                                {{ $data->agent_billing_address->company_name ?? '' }}<br>
                                {{ $data->agent_billing_address->phone ?? '' }}<br>
                                {{ $data->agent_billing_address->street ?? '' }},
                                {{ $data->agent_billing_address->city ?? '' }} {{ $data->agent_billing_address->zip_code ?? '' }}
                                <br>
                                {{ "Sweden" }}
                            </td>
                            <td style="width: 50%; vertical-align: top; text-align: right;border: none;">
                                <strong style="font-size: 15px; color: black">Shipping Address</strong><br>
                                {{ $data->delivery_name ?? '' }}<br>
                                {{ $data->delivery_phone ?? '' }}<br>
                                {{ $data->delivery_address ?? '' }}<br>
                                {{ $data->delivery_zip ?? '' }} {{ $data->delivery_city ?? '' }}<br>
                                {{ $data->delivery_country ?? '' }}
                                <br>
                                <br>
                                 @if($data->parcel_shop)
                                    {{ "Parcel Shop Id: " }} {{ $data['parcel_shop']['service_point_id'] }} <br>
                                    {{ "Address: " }} {{ $data['parcel_shop']['address_line'] }} <br>
                                @endif
                            </td>
                        </tr>
                    </table>
                </td>
                <td style="width: 40%;border: none;text-align: left;">
                    <strong style="font-size: 25px; color: black">Invoice</strong>
                    <table class="table">
                        <tbody>
                        <tr>
                            <td style="border: none;padding: 2px 4px;">Invoice No.</td>
                            <td style="border: none;padding: 2px 4px;">:</td>
                            <td style="border: none;padding: 2px 4px;">{{ $data->invoice_no ?? '' }}</td>
                        </tr>
                        <tr>
                            <td style="border: none;padding: 2px 4px;">Invoice Date</td>
                            <td style="border: none;padding: 2px 4px;">:</td>
                            <td style="border: none;padding: 2px 4px;">{{ date('d M, Y',strtotime($data->created_at)) }}</td>
                        </tr>
                        <tr>
                            <td style="border: none;padding: 2px 4px;">Order No.</td>
                            <td style="border: none;padding: 2px 4px;">:</td>
                            <td style="border: none;padding: 2px 4px;">{{ $data->tellecto_order_no.' / '.$data->dcs_order_no ?? '' }}</td>
                        </tr>
                        <tr>
                            <td style="border: none;padding: 2px 4px;">Requsition</td>
                            <td style="border: none;padding: 2px 4px;">:</td>
                            <td style="border: none;padding: 2px 4px;">{{ $data->requsition ?? $data->delivery_name ?? '' }}</td>
                        </tr>
                        <tr>
                            <td style="border: none;padding: 2px 4px;">Payment Term</td>
                            <td style="border: none;padding: 2px 4px;">:</td>
                            <td style="border: none;padding: 2px 4px;">{{ "14 Days" ?? '' }}</td>
                        </tr>
                        <tr>
                            <td style="border: none;padding: 2px 4px;">Payment before</td>
                            <td style="border: none;padding: 2px 4px;">:</td>
                            <td style="border: none;padding: 2px 4px;">{{ date('d M, Y', strtotime($data->created_at . ' +14 days')) }}</td>
                        </tr>
                        <tr>
                            <td style="border: none;padding: 2px 4px;">Shipping</td>
                            <td style="border: none;padding: 2px 4px;">:</td>
                            <td style="border: none;padding: 2px 4px;">{{ $data->carrier ?? $data->delivery_type ?? '' }}</td>
                        </tr>
                        <tr>
                            <td style="border: none;padding: 2px 4px;">Your Reference</td>
                            <td style="border: none;padding: 2px 4px;">:</td>
                            <td style="border: none;padding: 2px 4px;">{{ $data->business->user_information->contact_person ?? '' }}</td>
                        </tr>
                        <tr>
                            <td style="border: none;padding: 2px 4px;">Our Reference</td>
                            <td style="border: none;padding: 2px 4px;">:</td>
                            <td style="border: none;padding: 2px 4px;">{{ env('ORDER_REF_NAME') ?? "N/A" }}</td>
                        </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
        </table>

        <table class="table" style="margin-top: 10px;">
            <thead>
            <tr style="background-color: black;">
                <th style="color: white;text-align: left;padding-left: 3px;">SKU</th>
                <th style="color: white;text-align: left;padding-left: 3px;">Name</th>
                <th style="color: white;text-align: left;padding-left: 3px;">Qty</th>
                <th style="color: white;text-align: left;padding-left: 3px;">Unit price</th>
                <th style="color: white;text-align: left;padding-left: 3px;">Total ex. Vat</th>
                <th style="color: white;text-align: left;padding-left: 3px;">Total inc. Vat</th>
            </tr>
            </thead>
            <tbody>
            @foreach($data->order_items as $item)
                <tr>
                    <td style="text-align: left;padding-left: 3px;">{{ $item->product->product_number ?? '' }}</td>
                    <td style="text-align: left;padding-left: 3px;">{{ $item->product->product_name ?? '' }}</td>
                    <td style="text-align: left;padding-left: 3px;">{{ $item->quantity ?? '' }}</td>
                    <td style="text-align: left;padding-left: 3px;">{{ $item->business_last_price ?? '' }} SEK</td>
                    <td style="text-align: left;padding-left: 3px;">{{ $item->item_total_agent }} SEK</td>
                    <td style="text-align: left;padding-left: 3px;">{{ $item->total_price_agent }} SEK</td>
                </tr>
            @endforeach
            <tr style="margin-top: 15px;border-top: 1px solid black;">
                <td style="text-align: left;padding-left: 3px;">{{ $data->delivery_code ?? '' }}</td>
                <td style="text-align: left;padding-left: 3px;">{{ $data->delivery_type ?? '' }}</td>
                <td style="text-align: left;padding-left: 3px;">{{ '1' }}</td>
                <td style="text-align: left;padding-left: 3px;">{{ $data->delivery_charge ?? '' }} SEK</td>
                <td style="text-align: left;padding-left: 3px;">{{ $data->delivery_charge ?? '' }} SEK</td>
                <td style="text-align: left;padding-left: 3px;">{{ $data->delivery_charge_with_vat ?? '' }} SEK</td>
            </tr>
            </tbody>
        </table>

    </div>
</div>

<div class="bottom-box" style="position: absolute;bottom: 2;">
    <div class="charge-box">
        <table class="table" style="margin-top: 30px;">
            <thead>
            <tr style="background-color: black">
                <th style="color: white;text-align: center;">Net amount</th>
                <th style="color: white;text-align: center;">Total vat. amount</th>
                <th style="color: white;text-align: center;">Vat rate</th>
                <th style="color: white;text-align: center;">Total amount SEK</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td style="text-align: center;">{{ $data->total_excluding_vat_agent ?? 0.00 }}</td>
                <td style="text-align: center;">{{ $data->total_vat_agent ?? 0.00 }}</td>
                <td style="text-align: center;">{{ '25% / 0%' }}</td>
                <td style="text-align: center;">{{ $data->total_including_vat_agent ?? 0.00 }}</td>
            </tr>
            </tbody>
            </thead>
        </table>
{{--        <br>--}}
{{--        <small style="font-size: 12px;">Track and Trace: {{ $data->tracking_number ?? '' }}</small>--}}
    </div>
    <div class="comment-box">
        <p>Comment: {{ $data->comment ?? "" }}</p>
    </div>
    <div class="message-box" style="margin-top: 20px;">
        <small style="font-size: 13px;">In case of late payment, 8% interest per started month and a reminder fee of SEK 100. Bagela AB (Tellecto) delivery and payment condition apply for shipping.</small>
    </div>
    <table class="table" style="margin-top: 10px;">
        <thead>
        <tr style="background-color: black">
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        </thead>
        <tr>
            <td style="border: none; padding: 5px"></td>
        </tr>
        <tbody>
        <tr>
            <td style="width: 33%; text-align: left;border-right: 2px solid black">
                <p style="color: black;font-size: 13px;">Bagela AB (Tellecto)</p>

                    <span style="color: black">Adress:</span> Skeppsgatan 19<br>
                    211 11 Malmö | Sweden<br>
                    F-Tax Registered <br>
                    faktura@tellecto.se<br>
            </td>
             <td style="width: 33%; text-align: left;border-right: 2px solid black">
                <p style="color: black;font-size: 13px;">Payment Info</p>
                    <span style="color: black">Bankgiro: </span> 330-2478<br>
                    <span style="color: black">IBAN:</span>  SE72 6000 0000 0007 1268 8412 <br>
                    <span style="color: black">BIC:</span>  HANDSESS <br>
                    <span style="color: black">VAT. No:</span>  SE559453730901
            </td>
            <td style="width: 33%; text-align: left;">
                <p style="color: black;font-size: 13px;">Contact</p>
                    <span style="color: black">Email:</span> faktura@tellecto.se<br>
                    <span style="color: black">Telefon:</span> 0762164706<br>
                    <span style="color: black">Office Hour:</span> Mån-fre 08:00 - 17:00
            </td>
        </tr>
        </tbody>
    </table>
</div>
</body>
</html>
