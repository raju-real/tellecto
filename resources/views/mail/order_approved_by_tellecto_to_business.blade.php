<p><strong>Dear {{ $mail_data['order']['business']['name'] ?? '' }},</strong></p>
<p>
    We are pleased to inform you that your order #{{ $mail_data['order']['tellecto_order_no'] }}, placed by {{ $mail_data['order']['agent']['full_name'] }}, has been approved by the TELLECTO team and is now being processed.

</p>
<p><strong>Order Summary:</strong></p>
<ol style="list-style-type: disc; list-style-position: inside;">
    <li><strong>Order Number: </strong> {{ $mail_data['order']['tellecto_order_no'] ?? '' }}</li>
    <li><strong>Order Date: </strong> {{ date('d-m-y',strtotime($mail_data['order']['created_at'])) ?? '' }}
    </li>
    <li><strong>Items Ordered:</strong>
        <ol style="list-style-type: disc; list-style-position: inside;">
            @foreach($mail_data['order']['order_items'] as $item)
                <li>{{ $item->product->product_name ?? ''  }}
                    - {{ $item->quantity ?? '' }}
                    - {{ $item->total_price_business ?? '' }}</li>
            @endforeach
        </ol>
    </li>
    <li><strong>Total Amount: </strong> {{ $mail_data['order']['total_order_amount_business'] ?? '' }}</li>
    <li><strong>Shipping Method: </strong> {{ $mail_data['order']['delivery_type'] ?? '' }}</li>
    <li><strong>Shipping Address: </strong> {{ $mail_data['order']['delivery_address'] ?? '' }}, {{ $mail_data['order']['delivery_zip'] }} {{ $mail_data['order']['delivery_city'] }}</li>
    <li><strong>Payment Method: </strong> {{ $mail_data['order']['payment_method'] ?? '' }}</li>
    <li><strong>Approved by: </strong> {{ getUserNameByID($mail_data['order']['approved_by']) ?? '' }}</li>
</ol>

<br>
<p>You can track the order's progress and view any updates by logging into your account at</p>
<p><a href="https://admin.tellecto.se/login">https://admin.tellecto.se/login</a></p>
<br>
<p>For any questions or additional details, feel free to reach out to us.</p>
<p>Thank you for choosing TELLECTO!</p>
