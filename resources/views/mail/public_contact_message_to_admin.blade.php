<p><strong>Dear TELLECTO Admin,</strong></p>
<p>
    {{ $mail_data['message'] }}
</p>
<p>
    <span><strong>Name:</strong> {{ $mail_data['name'] ?? '' }}</span><br>
    <span><strong>Email:</strong> {{ $mail_data['email'] ?? '' }}</span><br>
    <span><strong>Phone:</strong> {{ $mail_data['phone'] ?? '' }}</span><br>
</p>
