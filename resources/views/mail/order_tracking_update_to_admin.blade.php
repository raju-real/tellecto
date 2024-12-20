<p><strong>Dear TELLECTO Admin Team,</strong></p>
<p>
    We are pleased to inform you that we have received a shipping update from our vendor, DCS, for the following order:
</p>
<hr>
<p><strong>Order Summary:</strong></p>
<ol style="list-style-type: disc; list-style-position: inside;">
    <li><strong>Tellecto Order Number: </strong> {{ $mail_data['order']['tellecto_order_no'] ?? '' }}</li>
    <li><strong>DCS Order Number: </strong> {{ $mail_data['order']['dcs_order_no'] ?? '' }}</li>
    <li><strong>DCS Invoice Number: </strong> {{ $mail_data['order']['invoice_no'] ?? '' }}</li>
    <li><strong>Vendor: </strong> DCS</li>
    <li><strong>Shipping Information:</strong>
        <ol style="list-style-type: disc; list-style-position: inside;">
            <li><strong>Carrier:</strong> {{ $mail_data['order']['carrier'] ?? '' }}</li>
            <li><strong>Tracking Number:</strong> {{ $mail_data['order']['tracking_number'] ?? '' }}</li>
            <li><strong>Shipping Address: </strong> {{ $mail_data['order']['delivery_address'] ?? '' }}, {{ $mail_data['order']['delivery_zip'] }} {{ $mail_data['order']['delivery_city'] }}</li>
        </ol>
    </li>
</ol>

<br>
<p>Please ensure that this updated shipping information is communicated to the relevant business owner and agent associated with this order. Timely updates help us maintain excellent service and transparency.</p>
