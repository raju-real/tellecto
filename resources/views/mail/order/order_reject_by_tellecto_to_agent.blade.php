<table bgcolor="#f5f5f5" align="center" width="100%" border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td align="center">
            <table class="mobilewrap" width="650" style="width:650px" border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td style="width:25px;" class="noneMobile">&nbsp;</td>
                    <td valign="top">
                        <table width="100%" border="0" cellspacing="0" cellpadding="0">
                            <tr>
                                <td class="h_10" bgcolor="#e6e6e6" height="15"
                                    style="font-size:0px; line-height:0px; border:0px;">&nbsp;
                                </td>
                            </tr>

                            <tr>
                                <td bgcolor="#ffffff" class="h_10" height="20"
                                    style="font-size:0px; line-height:0px; border:0px;">
                                    <table bgcolor="#ffffff" width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td width="15" bgcolor="#e6e6e6"
                                                style="font-size:0px; line-height:0px; border:0px;">&nbsp;
                                            </td>
                                            <td bgcolor="#ffffff" style="font-size:0px; line-height:0px; border:0px;">
                                                <table bgcolor="#ffffff" width="100%" border="0" cellspacing="0"
                                                       cellpadding="0">
                                                    <tr>
                                                        <td height="25" bgcolor="#ffffff"
                                                            style="font-size:0px; line-height:0px; border:0px;">&nbsp;
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" valign="middle"><a
                                                                href="https://tellecto.se/" target="_blank"><img
                                                                    src="https://apis.tellecto.se/public/assets/images/logo-tellecto.png"
                                                                    alt="Tellecto" title="Tellecto" width="160"
                                                                    height="50" style="display:block;"/></a></td>
                                                    </tr>
                                                    <tr>
                                                        <td height="25" bgcolor="#ffffff"
                                                            style="font-size:0px; line-height:0px; border:0px;">&nbsp;
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" valign="top"
                                                            style="font-family:Arial, Helvetica, sans-serif; font-size:20px; line-height:20px; color:#222;">
                                                            <b>#{{ $mail_data['subject'] ?? '' }}</b>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td height="6" bgcolor="#ffffff"
                                                            style="font-size:0px; line-height:0px; border:0px;">&nbsp;
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" valign="top"
                                                            style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222;">
                                                            Order Date:
                                                            <b>{{ date('d M, Y',strtotime($mail_data['order']['created_at'])) }}</b>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td valign="top" bgcolor="#fff">
                                                            <table bgcolor="#fff" width="100%" border="0"
                                                                   cellspacing="0" cellpadding="0">
                                                                <tr>
                                                                    <td bgcolor="#fff" height="30"
                                                                        style="font-size:0px; line-height:0px; border:0px;">
                                                                        &nbsp;
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="left" valign="top"
                                                                        style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; padding-left: 25px;">
                                                                        <b>Dear {{ $mail_data['order']['agent']['full_name'] ?? '' }}
                                                                            ,</b></td>
                                                                </tr>
                                                                <tr>
                                                                    <td bgcolor="#fff" height="20"
                                                                        style="font-size:0px; line-height:0px; border:0px;">
                                                                        &nbsp;
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="left" valign="top"
                                                                        style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; padding: 0 25px;">
                                                                        We regret to inform you that your order
                                                                        #{{ $mail_data['order']['tellecto_order_no'] ?? '' }}
                                                                        , has been cancelled by
                                                                        the TELLECTO team.
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td bgcolor="#fff" height="30"
                                                                        style="font-size:0px; line-height:0px; border:0px;">
                                                                        &nbsp;
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td align="left" valign="top"
                                                                        style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; padding-left: 25px;margin-top: 10px;">
                                                                        <b>Reason for Cancellation</b></td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="left" valign="top"
                                                                        style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; padding-left: 25px;">
                                                                        {{ $mail_data['order']['rejected_for'] ?? 'N/A' }}
                                                                        <p>If you would like to discuss this decision or
                                                                            need assistance in placing a new order,
                                                                            please feel free to contact us at
                                                                            <a href="mailto:info@tellecto.se">
                                                                                info@tellecto.se</a></p>
                                                                        <p>We appreciate your understanding and look
                                                                            forward to serving you in the future.</p>
                                                                    </td>
                                                                </tr>

                                                                <tr>
                                                                    <td align="left" valign="top"
                                                                        style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; padding-left: 25px;">
                                                                        <b>Follow your order</b></td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="left" valign="top"
                                                                        style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; padding-left: 25px;">
                                                                        Click below to track your order status
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td bgcolor="#fff" height="20"
                                                                        style="font-size:0px; line-height:20px; border:0px;">
                                                                        &nbsp;
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="center">
                                                                        <table bgcolor="#fff" width="100%" border="0"
                                                                               cellspacing="0" cellpadding="0">
                                                                            <tr>
                                                                                <td align="center" valign="top"
                                                                                    style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px;">
                                                                                    <a class="btn"
                                                                                       href="https://tellecto.se/my-account/orders">Order
                                                                                        Status</a>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td bgcolor="#fff" height="45"
                                                                        style="font-size:0px; line-height:0px; border:0px;">
                                                                        &nbsp;
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td bgcolor="#e6e6e6" height="15"
                                                            style="font-size:0px; line-height:0px; border:0px;">&nbsp;
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td valign="top" bgcolor="#fff">
                                                            <table bgcolor="#fff" width="100%" border="0"
                                                                   cellspacing="0" cellpadding="0">
                                                                <tr>
                                                                    <td bgcolor="#fff" height="30"
                                                                        style="font-size:0px; line-height:0px; border:0px;">
                                                                        &nbsp;
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="left" valign="top"
                                                                        style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; padding-left: 25px;">
                                                                        <b>Delivery Address</b></td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="left" valign="top"
                                                                        style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; padding-left: 25px;">
                                                                        {{ $mail_data['order']['delivery_name'] ?? '' }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="left" valign="top"
                                                                        style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; padding-left: 25px;">
                                                                        {{ $mail_data['order']['delivery_address'] ?? '' }}, {{ $mail_data['order']['delivery_zip'] }} {{ $mail_data['order']['delivery_city'] }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td bgcolor="#fff" height="10"
                                                                        style="font-size:0px; line-height:0px; border:0px;">
                                                                        &nbsp;
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="left" valign="top"
                                                                        style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; padding-left: 25px;">
                                                                        <b>Delivery Method</b></td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="left" valign="top"
                                                                        style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; padding-left: 25px;">
                                                                        {{ $mail_data['order']['delivery_type'] ?? '' }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td bgcolor="#fff" height="10"
                                                                        style="font-size:0px; line-height:0px; border:0px;">
                                                                        &nbsp;
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="left" valign="top"
                                                                        style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; padding-left: 25px;">
                                                                        <b>Payment Method</b></td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="left" valign="top"
                                                                        style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; padding-left: 25px;">
                                                                        {{ $mail_data['order']['payment_method'] ?? '' }}
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td bgcolor="#fff" height="30"
                                                                        style="font-size:0px; line-height:0px; border:0px;">
                                                                        &nbsp;
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td bgcolor="#e6e6e6" height="15"
                                                            style="font-size:0px; line-height:0px; border:0px;">&nbsp;
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td valign="top" bgcolor="#fff">
                                                            <table bgcolor="#fff" width="100%" border="0"
                                                                   cellspacing="0" cellpadding="0">
                                                                <tr>
                                                                    <td bgcolor="#fff" height="30"
                                                                        style="font-size:0px; line-height:0px; border:0px;">
                                                                        &nbsp;
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="left" valign="top"
                                                                        style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; padding-left: 25px;">
                                                                        <b>Order Details</b></td>
                                                                </tr>
                                                                <tr>
                                                                    <td bgcolor="#fff" height="20"
                                                                        style="font-size:0px; line-height:0px; border:0px;">
                                                                        &nbsp;
                                                                    </td>
                                                                </tr>


                                                                <tr>
                                                                    <td bgcolor="#fff" height="15"
                                                                        style="font-size:0px; line-height:0px; border:0px;">
                                                                        &nbsp;
                                                                    </td>
                                                                </tr>
                                                                @foreach($mail_data['order']['order_items'] as $item)
                                                                    <tr>
                                                                        <td align="left" valign="top"
                                                                            style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; padding-left: 25px;padding-bottom: 10px;">
                                                                            <table bgcolor="#fff" width="95%" border="0"
                                                                                   cellspacing="0" cellpadding="0">
                                                                                <tr>
                                                                                    <td align="left" valign="top"
                                                                                        style="width:12%; font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222;">
                                                                                        <img
                                                                                            src="{{ asset($item->product->product_image) }}"
                                                                                            alt="Tellecto"
                                                                                            title="Tellecto"
                                                                                            width="80" height="80"
                                                                                            style="display:block;"/>
                                                                                    </td>
                                                                                    <td bgcolor="#fff"
                                                                                        style="width:60%;">
                                                                                        <table bgcolor="#fff"
                                                                                               width="100%"
                                                                                               border="0"
                                                                                               cellspacing="0"
                                                                                               cellpadding="0">
                                                                                            <tr>
                                                                                                <td align="left"
                                                                                                    valign="top"
                                                                                                    style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; width: 80%; padding-left: 15px;">
                                                                                                    <b>{{ $item->product->product_name ?? ''  }}</b>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td bgcolor="#fff"
                                                                                                    height="20"
                                                                                                    style="font-size:0px; line-height:0px; border:0px;">
                                                                                                    &nbsp;
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td align="left"
                                                                                                    valign="top"
                                                                                                    style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; padding-left: 15px;">
                                                                                                    SKU:&nbsp;{{ $item->product->product_number ?? ''  }},
                                                                                                    PRICE:&nbsp;<b>{{ $item->business_last_price ?? ''  }}</b>,
                                                                                                    Qty:&nbsp;<b>{{ $item->quantity ?? '' }}</b>
                                                                                                </td>
                                                                                            </tr>
                                                                                        </table>
                                                                                    </td>
                                                                                    <td align="right" valign="top"
                                                                                        style="width:18%; font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; padding-left: 10px;">
                                                                                        {{--                                                                                        <b style="text-decoration: line-through;">SEK--}}
                                                                                        {{--                                                                                            4990</b><br/>--}}
                                                                                        <b style="color: #ff0000;">SEK {{ $item->item_total_agent ?? '' }}</b>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                @endforeach
                                                                <tr>
                                                                    <td bgcolor="#fff" height="20"
                                                                        style="font-size:0px; line-height:0px; border:0px;">
                                                                        &nbsp;
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td bgcolor="#e6e6e6" height="1"
                                                            style="font-size:0px; line-height:0px; border:0px;">&nbsp;
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td valign="top" bgcolor="#fff">
                                                            <table bgcolor="#fff" width="100%" border="0"
                                                                   cellspacing="0" cellpadding="0">
                                                                <tr>
                                                                    <td bgcolor="#fff" height="20"
                                                                        style="font-size:0px; line-height:0px; border:0px;">
                                                                        &nbsp;
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="left" valign="top"
                                                                        style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; padding-left: 25px;">
                                                                        <b>Order Summary</b></td>
                                                                </tr>
                                                                <tr>
                                                                    <td valign="top" bgcolor="#fff">
                                                                        <table bgcolor="#fff" width="100%" border="0"
                                                                               cellspacing="0" cellpadding="0">
                                                                            <tr>
                                                                                <td bgcolor="#fff" height="15"
                                                                                    style="font-size:0px; line-height:0px; border:0px;">
                                                                                    &nbsp;
                                                                                </td>
                                                                            </tr>

                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td bgcolor="#fff" height="10"
                                                                        style="font-size:0px; line-height:0px; border:0px;">
                                                                        &nbsp;
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td bgcolor="#e6e6e6" height="1"
                                                            style="font-size:0px; line-height:0px; border:0px;">&nbsp;
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td valign="top" bgcolor="#fff">
                                                            <table bgcolor="#fff" width="100%" border="0"
                                                                   cellspacing="0" cellpadding="0">
                                                                <tr>
                                                                    <td bgcolor="#fff" height="20"
                                                                        style="font-size:0px; line-height:0px; border:0px;">
                                                                        &nbsp;
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td valign="top" bgcolor="#fff">
                                                                        <table bgcolor="#fff" width="100%" border="0"
                                                                               cellspacing="0" cellpadding="0">
                                                                            <tr>
                                                                                <td valign="middle" class="wd_auto">
                                                                                    <table width="100%" border="0"
                                                                                           cellspacing="0"
                                                                                           cellpadding="0">
                                                                                        <tr>
                                                                                            <td bgcolor="#fff"
                                                                                                width="25px" height="30"
                                                                                                style="font-size:0px; line-height:0px;">
                                                                                                &nbsp;
                                                                                            </td>
                                                                                            <td align="left"
                                                                                                valign="top"
                                                                                                style="width:45%; font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222;">
                                                                                                Sub-Total:
                                                                                            </td>
                                                                                            <td align="right"
                                                                                                valign="top"
                                                                                                style="width:45%; font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; padding-left: 25px;">
                                                                                                SEK {{ $mail_data['order']['total_sales_amount_business'] ?? '' }}
                                                                                            </td>
                                                                                            <td bgcolor="#fff"
                                                                                                width="25px" height="30"
                                                                                                style="font-size:0px; line-height:0px;">
                                                                                                &nbsp;
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td valign="middle" class="wd_auto">
                                                                                    <table width="100%" border="0"
                                                                                           cellspacing="0"
                                                                                           cellpadding="0">
                                                                                        <tr>
                                                                                            <td bgcolor="#fff"
                                                                                                width="25px" height="30"
                                                                                                style="font-size:0px; line-height:0px;">
                                                                                                &nbsp;
                                                                                            </td>
                                                                                            <td align="left"
                                                                                                valign="top"
                                                                                                style="width:45%; font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222;">
                                                                                                Shipping
                                                                                            </td>
                                                                                            <td align="right"
                                                                                                valign="top"
                                                                                                style="width:45%; font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#FB7701; padding-left: 25px;">
                                                                                                @if($mail_data['order']['delivery_charge'] > 0)
                                                                                                    &nbsp;<span
                                                                                                        style="color:#222;">SEK {{ $mail_data['order']['delivery_charge'] }}</span>
                                                                                                @else
                                                                                                    FREE
                                                                                                @endif

                                                                                            </td>
                                                                                            <td bgcolor="#fff"
                                                                                                width="25px" height="30"
                                                                                                style="font-size:0px; line-height:0px;">
                                                                                                &nbsp;
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td valign="middle" class="wd_auto">
                                                                                    <table width="100%" border="0"
                                                                                           cellspacing="0"
                                                                                           cellpadding="0">
                                                                                        <tr>
                                                                                            <td bgcolor="#fff"
                                                                                                width="25px" height="30"
                                                                                                style="font-size:0px; line-height:0px;">
                                                                                                &nbsp;
                                                                                            </td>
                                                                                            <td align="left"
                                                                                                valign="top"
                                                                                                style="width:45%; font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222;">
                                                                                                VAT
                                                                                            </td>
                                                                                            <td align="right"
                                                                                                valign="top"
                                                                                                style="width:45%; font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; padding-left: 25px;">
                                                                                                SEK {{ $mail_data['order']['total_vat_agent'] }}
                                                                                            </td>
                                                                                            <td bgcolor="#fff"
                                                                                                width="25px" height="30"
                                                                                                style="font-size:0px; line-height:0px;">
                                                                                                &nbsp;
                                                                                            </td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td bgcolor="#fff" height="10"
                                                                        style="font-size:0px; line-height:0px; border:0px;">
                                                                        &nbsp;
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td bgcolor="#e6e6e6" height="1"
                                                            style="font-size:0px; line-height:0px; border:0px;">&nbsp;
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td valign="top" bgcolor="#fff">
                                                            <table bgcolor="#fff" width="100%" border="0"
                                                                   cellspacing="0" cellpadding="0">
                                                                <tr>
                                                                    <td bgcolor="#fff" height="20"
                                                                        style="font-size:0px; line-height:0px; border:0px;">
                                                                        &nbsp;
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td valign="middle" class="wd_auto">
                                                                        <table width="100%" border="0" cellspacing="0"
                                                                               cellpadding="0">
                                                                            <tr>
                                                                                <td bgcolor="#fff" width="25px"
                                                                                    height="30"
                                                                                    style="font-size:0px; line-height:0px;">
                                                                                    &nbsp;
                                                                                </td>
                                                                                <td align="left" valign="top"
                                                                                    style="width:45%; font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222;">
                                                                                    <b>Total</b> (Incl. VAT)
                                                                                </td>
                                                                                <td align="right" valign="top"
                                                                                    style="width:45%; font-family:Arial, Helvetica, sans-serif; font-size:20px; line-height:20px; color:#222; padding-left: 25px;">
                                                                                    <b>SEK {{ $mail_data['order']['total_order_amount_agent'] ?? '' }}</b>
                                                                                </td>
                                                                                <td bgcolor="#fff" width="25px"
                                                                                    height="30"
                                                                                    style="font-size:0px; line-height:0px;">
                                                                                    &nbsp;
                                                                                </td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td bgcolor="#fff" height="20"
                                                                        style="font-size:0px; line-height:0px; border:0px;">
                                                                        &nbsp;
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td valign="top" bgcolor="#222">
                                                            <table bgcolor="#222" width="100%" border="0"
                                                                   cellspacing="0" cellpadding="0">
                                                                <tr>
                                                                    <td bgcolor="#222" height="30"
                                                                        style="font-size:0px; line-height:0px; border:0px;">
                                                                        &nbsp;
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="center" valign="middle"><a
                                                                            href="https://tellecto.se/" target="_blank"><img
                                                                                src="https://apis.tellecto.se/public/assets/images/logo-tellecto-white.png"
                                                                                alt="Tellecto" title="Tellecto"
                                                                                width="130" height="40"
                                                                                style="display:block;"/></a></td>
                                                                </tr>
                                                                <tr>
                                                                    <td bgcolor="#222" height="15"
                                                                        style="font-size:0px; line-height:0px; border:0px;">
                                                                        &nbsp;
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td bgcolor="#222" height="10"
                                                                        style="font-size:0px; line-height:0px; border:0px;">
                                                                        &nbsp;
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="left" valign="top"
                                                                        style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#fff; padding-left: 25px;">
                                                                        Do you have questions about your order? Click <a
                                                                            class="text-link" href="https://tellecto.se/contact-us" target="_blank">here</a>
                                                                        to contact customer service.
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td bgcolor="#222" height="30"
                                                                        style="font-size:0px; line-height:0px; border:0px;">
                                                                        &nbsp;
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                            <td width="15" bgcolor="#e6e6e6"
                                                style="font-size:0px; line-height:0px; border:0px;">&nbsp;
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <td bgcolor="#e6e6e6" class="h_10" height="20"
                                    style="font-size:0px; line-height:0px; border:0px;">
                                    <table bgcolor="#e6e6e6" width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td height="20" bgcolor="#e6e6e6"
                                                style="font-size:0px; line-height:0px; border:0px;">&nbsp;
                                            </td>
                                        </tr>
                                        <!-- <tr>
                                           <td align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; text-align: center; line-height:20px; color:#666; padding: 0 25px;">This is an automatically generated e-mail. Please do not reply to this e-mail.</td>
                                         </tr>
                                         <tr>
                                           <td bgcolor="#e6e6e6" height="25" style="font-size:0px; line-height:0px; border:0px;">&nbsp;</td>
                                         </tr> -->
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <td class="h_10" height="5" style="font-size:0px; line-height:0px; border:0px;">&nbsp;
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td style="width:25px;" class="noneMobile">&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td class="h_10" align="center" height="20" style="font-size:0px; line-height:0px; border:0px;">
            <table class="noneMobile" width="600" align="center" cellpadding="0" cellspacing="0"
                   style="min-width: 600px; mso-hide:all;" border="0">
                <tr>
                    <td style="font-size:0px; line-height:0px; border:0px;">&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr>
</table>
