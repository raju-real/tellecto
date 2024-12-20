<p><strong>Dear {{ $mail_data['user']['name'] ?? '' }},</strong></p>
<p>
    Congratulations! Your company, {{ $mail_data['user']['user_information']['company_name'] }}, has been successfully registered and approved as a business account on the TELLECTO B2B eCommerce platform.
</p>
<p>You can now log in and start exploring our wide range of products and services tailored to your business needs.</p>
<p><strong>Here are your account details:</strong></p>
<ol style="list-style-type: disc; list-style-position: inside;">
    <li><strong>Login URL: </strong> <a href="https://admin.tellecto.se/login">https://admin.tellecto.se/login</a></li>
    <li><strong>Username: </strong> {{ $mail_data['user']['email'] }}</li>
    <li><strong>Temporary Password: </strong> {{ $mail_data['password'] }} (Please update your password upon first login)
</li>
</ol>

<br>
<p>For assistance or further questions, our support team is here to help. You can reach us at
    <a href="mailto:support@tellecto.se"> support@tellecto.se</a></p>
<p>We look forward to partnering with your business!</p>
