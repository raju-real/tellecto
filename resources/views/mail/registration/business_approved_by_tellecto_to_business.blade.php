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
                                                            <b>{{ $mail_data['subject'] ?? '' }}</b>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td height="6" bgcolor="#ffffff"
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
                                                                        <b>Dear {{ $mail_data['user']['name'] ?? '' }}
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
                                                                        Congratulations! Your
                                                                        company, {{ $mail_data['user']['user_information']['company_name'] }}
                                                                        , has been successfully registered and approved
                                                                        as a business account on the TELLECTO B2B
                                                                        eCommerce platform.
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td height="25" bgcolor="#ffffff"
                                                                        style="font-size:0px; line-height:0px; border:0px;">
                                                                        &nbsp;
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="left" valign="top"
                                                                        style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; padding: 0 25px;">
                                                                        You can now log in and start exploring our wide
                                                                        range of products and services tailored to your
                                                                        business needs.
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td height="25" bgcolor="#ffffff"
                                                                        style="font-size:0px; line-height:0px; border:0px;">
                                                                        &nbsp;
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="left" valign="top"
                                                                        style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; padding: 0 25px;">
                                                                        Here are your account details:
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td height="25" bgcolor="#ffffff"
                                                                        style="font-size:0px; line-height:0px; border:0px;">
                                                                        &nbsp;
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="left" valign="top"
                                                                        style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; padding: 0 25px;">
                                                                        <ol style="list-style-type: disc; list-style-position: inside;">
                                                                            <li><strong>Login URL: </strong> <a
                                                                                    href="https://admin.tellecto.se/login">https://admin.tellecto.se/login</a>
                                                                            </li>
                                                                            <li>
                                                                                <strong>Username: </strong> {{ $mail_data['user']['email'] }}
                                                                            </li>
                                                                            <li><strong>Temporary
                                                                                    Password: </strong> {{ $mail_data['password'] }}
                                                                                (Please update your password upon first
                                                                                login)
                                                                            </li>
                                                                        </ol>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td height="25" bgcolor="#ffffff"
                                                                        style="font-size:0px; line-height:0px; border:0px;">
                                                                        &nbsp;
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="left" valign="top"
                                                                        style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; padding: 0 25px;">
                                                                       For assistance or further questions, our support team is here to help. You can reach us at
                                                                        <a href="mailto:support@tellecto.se"> support@tellecto.se </a>
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td height="25" bgcolor="#ffffff"
                                                                        style="font-size:0px; line-height:0px; border:0px;">
                                                                        &nbsp;
                                                                    </td>
                                                                </tr>
                                                                <tr>
                                                                    <td align="left" valign="top"
                                                                        style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; padding: 0 25px;">
                                                                       We look forward to partnering with your business!
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>

                                                    <tr>
                                                        <td height="25" bgcolor="#ffffff"
                                                            style="font-size:0px; line-height:0px; border:0px;">&nbsp;
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
                                                                            class="text-link"
                                                                            href="https://tellecto.se/contact-us"
                                                                            target="_blank">here</a>
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
