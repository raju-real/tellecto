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
                                                                        <b>Dear Tellecto Team,</b></td>
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
                                                                        A new
                                                                        company, {{ $mail_data['user']['user_information']['company_name'] ?? 'N/A' }}
                                                                        , has submitted a registration form on our B2B
                                                                        eCommerce platform, Tellecto.se .
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
                                                                        style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; padding-left: 25px;padding-bottom: 10px;">
                                                                        <table bgcolor="#fff" width="95%"
                                                                               cellspacing="0" cellpadding="0">
                                                                            <tr>
                                                                                <td align="left"
                                                                                    valign="top"
                                                                                    style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; width: 50%; padding: 10px;border: 1px solid dimgrey;">
                                                                                    <b>Company Name</b>
                                                                                </td>
                                                                                <td align="left"
                                                                                    valign="top"
                                                                                    style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; width: 50%; padding: 10px;border: 1px solid dimgrey;">
                                                                                    <b>{{ $mail_data['user']['user_information']['company_name'] ?? '' }}</b>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="left"
                                                                                    valign="top"
                                                                                    style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; width: 50%; padding: 10px;border: 1px solid dimgrey;">
                                                                                    <b>Email</b>
                                                                                </td>
                                                                                <td align="left"
                                                                                    valign="top"
                                                                                    style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; width: 50%; padding: 10px;border: 1px solid dimgrey;">
                                                                                    <b>{{ $mail_data['user']['email'] ?? '' }}</b>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="left"
                                                                                    valign="top"
                                                                                    style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; width: 50%; padding: 10px;border: 1px solid dimgrey;">
                                                                                    <b>Mobile</b>
                                                                                </td>
                                                                                <td align="left"
                                                                                    valign="top"
                                                                                    style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; width: 50%; padding: 10px;border: 1px solid dimgrey;">
                                                                                    <b>{{ $mail_data['user']['mobile'] ?? '' }}</b>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="left"
                                                                                    valign="top"
                                                                                    style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; width: 50%; padding: 10px;border: 1px solid dimgrey;">
                                                                                    <b>VAT No.</b>
                                                                                </td>
                                                                                <td align="left"
                                                                                    valign="top"
                                                                                    style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; width: 50%; padding: 10px;border: 1px solid dimgrey;">
                                                                                    <b>{{ $mail_data['user']['user_information']['vat_no'] ?? '' }}</b>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="left"
                                                                                    valign="top"
                                                                                    style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; width: 50%; padding: 10px;border: 1px solid dimgrey;">
                                                                                    <b>Org. No</b>
                                                                                </td>
                                                                                <td align="left"
                                                                                    valign="top"
                                                                                    style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; width: 50%; padding: 10px;border: 1px solid dimgrey;">
                                                                                    <b>{{ $mail_data['user']['user_information']['org_no'] ?? '' }}</b>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="left"
                                                                                    valign="top"
                                                                                    style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; width: 50%; padding: 10px;border: 1px solid dimgrey;">
                                                                                    <b>Address</b>
                                                                                </td>
                                                                                <td align="left"
                                                                                    valign="top"
                                                                                    style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; width: 50%; padding: 10px;border: 1px solid dimgrey;">
                                                                                    <b>{{ $mail_data['user']['user_information']['street']. ', '. $mail_data['user']['user_information']['zip_code']. ', '.$mail_data['user']['user_information']['city']  }}</b>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="left"
                                                                                    valign="top"
                                                                                    style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; width: 50%; padding: 10px;border: 1px solid dimgrey;">
                                                                                    <b>Website</b>
                                                                                </td>
                                                                                <td align="left"
                                                                                    valign="top"
                                                                                    style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; width: 50%; padding: 10px;border: 1px solid dimgrey;">
                                                                                    <b>{{ $mail_data['user']['user_information']['website_url'] ?? '' }}</b>
                                                                                </td>
                                                                            </tr>
                                                                        </table>
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