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
                                                                        <b>Hi <span>{{ $mail_data['to_name'] }},</b>
                                                                    </td>
                                                                </tr>
                                                                @if(isset($mail_data['password_resend_link']))
                                                                    <tr>
                                                                        <td bgcolor="#fff" height="20"
                                                                            style="font-size:0px; line-height:0px; border:0px;">
                                                                            &nbsp;
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="left" valign="top"
                                                                            style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; padding: 0 25px;">
                                                                            We received a request to reset your password
                                                                            for your tellecto.se account. No worries!
                                                                            You can easily set a new password by
                                                                            clicking the link below:
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td bgcolor="#fff" height="20"
                                                                            style="font-size:0px; line-height:0px; border:0px;">
                                                                            &nbsp;
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <td align="center">
                                                                            <table bgcolor="#fff" width="100%"
                                                                                   border="0"
                                                                                   cellspacing="0" cellpadding="0">
                                                                                <tr>
                                                                                    <td align="center" valign="top"
                                                                                        style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px;">
                                                                                        <a class="btn"
                                                                                           href="{{ $mail_data['password_resend_link'] }}">Reset
                                                                                            Your Password</a>
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
                                                                    <tr>
                                                                        <td align="left" valign="top"
                                                                            style="font-family:Arial, Helvetica, sans-serif; font-size:16px; line-height:20px; color:#222; padding: 0 25px;">
                                                                            If you didn't request a password reset, you
                                                                            can safely ignore this email—your account is
                                                                            secure.
                                                                        </td>
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
                                                                            For your security, this password reset link
                                                                            will expire in 24 hours.
                                                                        </td>
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
                                                                            If you need further assistance, feel free to
                                                                            reach out to our support team at
                                                                            <a href="mailto:support@tellecto.se">support@tellecto.se</a>
                                                                            or visit our Help Center.
                                                                        </td>
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
                                                                            Thank you for being a valued member of the Tellecto.se community!
                                                                        </td>
                                                                    </tr>
                                                                @endif
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
