<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN""http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml"
      xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <!--[if gte mso 9]>
    <xml>
        <o:OfficeDocumentSettings>
            <o:AllowPNG/>
            <o:PixelsPerInch>96</o:PixelsPerInch>
        </o:OfficeDocumentSettings>
    </xml><![endif]-->
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <title>Tellecto</title>
    <style type="text/css">
        .app_fff a {
            color: #ffffff;
            text-decoration: none;
        }

        .app_444 a {
            color: #444444;
            text-decoration: none;
        }

        body {
            width: 100% !important;
            min-width: 100%;
            margin: 0 auto !important;
            padding: 0;
            font-size: 0;
            -webkit-text-size-adjust: none;
            -ms-text-size-adjust: none;
            font-family: Arial, Helvetica, sans-serif;
        }

        #outlook a {
            padding: 0px;
        }

        .ReadMsgBody {
            width: 100%;
        }

        .ExternalClass {
            width: 100%;
        }

        .ExternalClass * {
            line-height: 100%;
        }

        .ExternalClass * {
            font-size: 100%
        }

        .mph {
            display: none !important;
        }

        .menu-item {
            text-decoration: none;
            color: #666;
            text-transform: uppercase;
        }

        .menu-item:hover {
            color: #F9B418;
        }

        .menu-seperator {
            margin-left: 7px;
            margin-right: 7px;
        }

        .text-link {
            text-decoration: none;
            color: #F9B418;
        }

        .btn {
            font-size: 17px;
            padding: 10px 20px 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            color: #ffffff;
            background: #222222;
            text-decoration: none;
            width: 85%;
            display: block;
            text-align: center;
        }

        .btn:hover {
            background: #facc15;
            color: #000;
        }

        @media only screen and (max-device-width: 600px), (max-width: 600px) {
            .mobilewrap {
                width: 100% !important;
                height: auto !important;
            }

            .wd_auto {
                width: 100% !important;
                height: auto !important;
            }

            .noneMobile {
                display: none !important;
            }

        }
    </style>
</head>

<body bgcolor="#ffffff" style="font-family:Arial, Helvetica, sans-serif; mso-line-height-rule: exactly;">
@if(isset($mail_data['activity_type']) && $mail_data['activity_type'] == 'order_confirmed_by_business_to_agent')
    @include('mail.order.order_confirmed_by_business_to_agent')
@endif
{{-- Agent part --}}
@if(isset($mail_data['activity_type']) && $mail_data['activity_type'] == 'order_confirmed_by_business_to_agent')
    @include('mail.order.order_confirmed_by_business_to_agent')
@endif
@if(isset($mail_data['activity_type']) && $mail_data['activity_type'] == 'order_canceled_by_business_to_agent')
    @include('mail.order.order_canceled_by_business_to_agent')
@endif
@if(isset($mail_data['activity_type']) && $mail_data['activity_type'] == 'order_approved_by_tellecto_to_agent')
    @include('mail.order.order_approved_by_tellecto_to_agent')
@endif
@if(isset($mail_data['activity_type']) && $mail_data['activity_type'] == 'order_reject_by_tellecto_to_agent')
    @include('mail.order.order_reject_by_tellecto_to_agent')
@endif
{{-- Business part --}}
@if(isset($mail_data['activity_type']) && $mail_data['activity_type'] == 'order_placed_by_agent_to_business')
    @include('mail.order.order_placed_by_agent_to_business')
@endif
@if(isset($mail_data['activity_type']) && $mail_data['activity_type'] == 'order_approved_by_tellecto_to_business')
    @include('mail.order.order_approved_by_tellecto_to_business')
@endif
@if(isset($mail_data['activity_type']) && $mail_data['activity_type'] == 'order_rejected_by_tellecto_to_business')
    @include('mail.order.order_rejected_by_tellecto_to_business')
@endif
@if(isset($mail_data['activity_type']) && $mail_data['activity_type'] == 'order_confirmed_by_business_to_business')
    @include('mail.order.order_confirmed_by_business_to_business')
@endif
{{-- Admin part--}}
@if(isset($mail_data['activity_type']) && $mail_data['activity_type'] == 'order_confirmed_by_business_to_admin')
    @include('mail.order.order_confirmed_by_business_to_admin')
@endif
@if(isset($mail_data['activity_type']) && $mail_data['activity_type'] == 'order_tracking_update_to_admin')
    @include('mail.order.order_tracking_update_to_admin')
@endif
</body>
</html>
