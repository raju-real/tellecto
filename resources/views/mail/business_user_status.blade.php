<!doctype html>
<html lang="en">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Simple Transactional Email</title>
    <style media="all" type="text/css">
        /* -------------------------------------
        GLOBAL RESETS
    ------------------------------------- */

        body {
            font-family: Helvetica, sans-serif;
            -webkit-font-smoothing: antialiased;
            font-size: 15px;
            line-height: 1.3;
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        table {
            border-collapse: separate;
            mso-table-lspace: 0pt;
            width: 100%;
        }

        table td {
            font-family: Helvetica, sans-serif;
            font-size: 16px;
            vertical-align: top;
        }

        body {
            background-color: #f4f5f6;
            margin: 0;
            padding: 0;
        }

        .body {
            background-color: #f4f5f6;
            width: 100%;
        }

        .container {
            margin: 0 auto !important;
            max-width: 600px;
            padding: 0;
            padding-top: 24px;
            width: 600px;
        }

        .content {
            box-sizing: border-box;
            display: block;
            margin: 0 auto;
            max-width: 600px;
            padding: 0;
        }

        .main {
            border-top: 3px solid #0000ff8c;
            background: #ffffff;
            /* border: 1px solid #eaebed; */
            border-radius: 16px;
            width: 100%;
        }

        .wrapper {
            box-sizing: border-box;
            padding: 24px;
        }

        p {
            font-family: Helvetica, sans-serif;
            font-size: 16px;
            font-weight: normal;
            margin: 0;
            margin-bottom: 16px;
        }

        a {
            color: #0867ec;
            text-decoration: underline;
        }

        .btn {
            box-sizing: border-box;
            min-width: 100% !important;
            width: 100%;
        }

        .btn > tbody > tr > td {
            padding-bottom: 16px;
        }

        .btn table {
            width: auto;
        }

        .btn table td {
            background-color: #ffffff;
            border-radius: 4px;
            text-align: center;
        }

        .btn a {
            background-color: #ffffff;
            border: solid 2px #0867ec;
            border-radius: 4px;
            box-sizing: border-box;
            color: #0867ec;
            cursor: pointer;
            display: inline-block;
            font-size: 16px;
            font-weight: bold;
            margin: 0;
            padding: 12px 24px;
            text-decoration: none;
            text-transform: capitalize;
        }

        .btn-primary table td {
            background-color: #0867ec;
        }

        .btn-primary a {
            background-color: #0867ec;
            border-color: #0867ec;
            color: #ffffff;
        }

        .footer-text {
            border-top: 1px solid #80808029;
            text-align: center;
            padding: 20px;
        }

        @media all {
            .btn-primary table td:hover {
                background-color: #ec0867 !important;
            }

            .btn-primary a:hover {
                background-color: #ec0867 !important;
                border-color: #ec0867 !important;
            }

            .footer-text {
                border-top: 1px solid #80808029;
                text-align: center;
                padding: 20px;
            }
        }

        /* -------------------------------------
        OTHER STYLES THAT MIGHT BE USEFUL
    ------------------------------------- */

        .last {
            margin-bottom: 0;
        }

        .first {
            margin-top: 0;
        }

        .align-center {
            text-align: center;
        }

        .align-right {
            text-align: right;
        }

        .align-left {
            text-align: left;
        }

        .text-link {
            color: #0867ec !important;
            text-decoration: underline !important;
        }

        .clear {
            clear: both;
        }

        .mt0 {
            margin-top: 0;
        }

        .mb0 {
            margin-bottom: 0;
        }

        .preheader {
            color: transparent;
            display: none;
            height: 0;
            max-height: 0;
            max-width: 0;
            opacity: 0;
            overflow: hidden;
            mso-hide: all;
            visibility: hidden;
            width: 0;
        }

        .powered-by a {
            text-decoration: none;
        }

        /* -------------------------------------
        RESPONSIVE AND MOBILE FRIENDLY STYLES
    ------------------------------------- */

        @media only screen and (max-width: 640px) {
            .main p,
            .main td,
            .main span {
                font-size: 16px !important;
            }

            .wrapper {
                padding: 8px !important;
            }

            .content {
                padding: 0 !important;
            }

            .container {
                padding: 0 !important;
                padding-top: 8px !important;
                width: 100% !important;
            }

            .main {
                border-left-width: 0 !important;
                border-radius: 0 !important;
                border-right-width: 0 !important;
            }

            .btn table {
                max-width: 100% !important;
                width: 100% !important;
            }

            .btn a {
                font-size: 16px !important;
                max-width: 100% !important;
                width: 100% !important;
            }

            .footer-text {
                border-top: 1px solid #80808029;
                text-align: center;
                padding: 20px;
            }
        }

        /* -------------------------------------
        PRESERVE THESE STYLES IN THE HEAD
    ------------------------------------- */

        @media all {
            .ExternalClass {
                width: 100%;
            }

            .ExternalClass,
            .ExternalClass p,
            .ExternalClass span,
            .ExternalClass font,
            .ExternalClass td,
            .ExternalClass div {
                line-height: 100%;
            }

            .apple-link a {
                color: inherit !important;
                font-family: inherit !important;
                font-size: inherit !important;
                font-weight: inherit !important;
                line-height: inherit !important;
                text-decoration: none !important;
            }

            #MessageViewBody a {
                color: inherit;
                text-decoration: none;
                font-size: inherit;
                font-family: inherit;
                font-weight: inherit;
                line-height: inherit;
            }

            .footer-text {
                border-top: 1px solid #80808029;
                text-align: center;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
<table role="presentation" border="0" cellpadding="0" cellspacing="0" class="body">
    <tr>
        <td>&nbsp;</td>
        <td class="container">
            <div class="content">
                <span class="preheader">This is preheader text. Some clients will show this text as a preview.</span>
                <table role="presentation" border="0" cellpadding="0" cellspacing="0" class="main">
                    <tr>
                        <td class="wrapper">
                            @if(isset($mail_data['activity_type']) && $mail_data['activity_type'] === 'business_accept')
                                <p>Congratulations! Your company, [Customer's Company Name], has been successfully
                                    registered and approved as a business account on the TELLECTO B2B eCommerce
                                    platform.</br>
                                    You can now log in and start exploring our wide range of products and services
                                    tailored to your business needs.</br>
                                    <b>Here are your account details:</b></p>
                                <li>Login URL: https://admin.tellecto.se/login</li>
                                <li>Username: [Customer's Email or Username]</li>
                                <li>Temporary Password: [Temporary Password] (Please update your password upon first
                                    login)
                                </li>
                                <p>For assistance or further questions, our support team is here to help. You can reach
                                    us at
                                    <a href="mailto:support@tellecto.se">support@tellecto.se</a>.</p>
                                <p>We look forward to partnering with your business!</p>
                            @endif

                            @if(isset($mail_data['activity_type']) && $mail_data['activity_type'] === 'business_suspended')
                                <p>Thank you for your interest in registering your company, [Company Name], on the
                                    TELLECTO
                                    B2B eCommerce platform.</br>
                                    After carefully reviewing your application, we regret to inform you that your
                                    request for a
                                    business account has not been approved at this time.</br>
                                    <b>Reasons for Rejection:</b>
                                    [Insuffecient information]</br>
                                    If you believe this decision was made in error or if you wish to provide additional
                                    information for
                                    reconsideration, please feel free to contact us at <a href="mailto:info@tellecto.se">info@tellecto.se</a>. We are happy to
                                    assist you
                                    with any questions or clarifications.</br>
                                    We appreciate your understanding and thank you for your interest in TELLECTO.</p>
                            @endif

                            <br>


                            <br>
                            <p>
                                <span>Best regards,</span> <br>
                                <span>Tellecto (Bagela AB)</span> <br>
                                <span>Org.nummer: 559453-7309</span> <br>
                                <span>Address: SKEPPSGATAN 19, 211 11 Malmö, Sweden</span> <br>
                                <span>Email: <a href="mailto:info@tellecto.se">info@tellecto.se</a></span><br>
                                <span><a href="www.tellecto.se">www.tellecto.se</a></span>
                            </p>
                        </td>
                    </tr>


                    <tr>
                        <td class="footer-text">
                            © {{ date('Y',strtotime(today())) }} Tellecto.se (Bagela AB) | All Rights Reserved
                        </td>
                    </tr>

                </table>
            </div>
        </td>
        <td>&nbsp;</td>
    </tr>
</table>
</body>
</html>