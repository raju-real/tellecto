<p><strong>Dear {{ $mail_data['order']['business']['name'] ?? '' }},</strong></p>
<p>
    We are writing to provide you with an important update regarding your recent order #{{ $mail_data['order']['tellecto_order_no'] }}.
</p>
<p><strong>Order Summary:</strong></p>
<ol style="list-style-type: disc; list-style-position: inside;">
    <li><strong>Order Number: </strong> {{ $mail_data['order']['tellecto_order_no'] ?? '' }}</li>
    <li><strong>Order Date: </strong> {{ date('d-m-y',strtotime($mail_data['order']['created_at'])) ?? '' }}</li>
    <li><strong>Shipping Information:</strong>
        <ol style="list-style-type: disc; list-style-position: inside;">
            <li><strong>Carrier:</strong> {{ $mail_data['order']['carrier'] ?? '' }}</li>
            <li><strong>Tracking Number:</strong> {{ $mail_data['order']['tracking_number'] ?? '' }}</li>
            <li><strong>Shipping Address: </strong> {{ $mail_data['order']['delivery_address'] ?? '' }}, {{ $mail_data['order']['delivery_zip'] }} {{ $mail_data['order']['delivery_city'] }}</li>
        </ol>
    </li>
</ol>

<br>
<p>You can track your shipment using the tracking number provided above on the carrier's website for real-time updates.</p>
<p>If you have any questions or need further assistance, please feel free to reach out to us at
    <a href="mailto:info@tellecto.se"> info@tellecto.se</a></p>
<p>Thank you for choosing TELLECTO. We appreciate your business!</p>
