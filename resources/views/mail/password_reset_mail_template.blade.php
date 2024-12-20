<p>Hi <span>{{ $mail_data['to_name'] }},</span></p>

@if(isset($mail_data['password_resend_link']))
    <p>We received a request to reset your password for your tellecto.se account. No worries! You can easily set a new password by clicking the link below:</p>
    <p>
        <a href="{{ $mail_data['password_resend_link'] }}"
           style="font-weight: bold;color: #2462D0">
            <u>Reset Your Password</u>
        </a>
    </p>
    <p>If you didn't request a password reset, you can safely ignore this email—your account is secure.</p>
    <p>For your security, this password reset link will expire in 24 hours.</p>
    <p>If you need further assistance, feel free to reach out to our support team at
        <a href="mailto:support@tellecto.se">support@tellecto.se</a> or visit our Help Center.</p>
    <p>Thank you for being a valued member of the Tellecto.se community!</p>
@else
    <p>{{ $mail_data['mail_body'] }}</p>
@endif
