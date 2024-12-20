<p><strong>Dear {{ $mail_data['user']['name'] ?? '' }},</strong></p>
<p>
    Thank you for your interest in registering your company, {{ $mail_data['user']['user_information']['company_name'] }}, on the TELLECTO B2B eCommerce platform.
</p>
<p>After carefully reviewing your application, we regret to inform you that your request for a business account has not been approved at this time.</p>
<p><strong>Reasons for Rejection:</strong></p>
<p>{{ $mail_data['user']['user_information']['rejected_for'] ?? 'N/A' }}</p>
<br>
<p>If you believe this decision was made in error or if you wish to provide additional information for reconsideration, please feel free to contact us at
    <a href="mailto:info@tellecto.se">info@tellecto.se</a>. We are happy to assist you with any questions or clarifications.</p>
<p>We appreciate your understanding, and thank you for your interest in TELLECTO.</p>
