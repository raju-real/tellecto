<p><strong>Dear {{ $mail_data['order']['agent']['full_name'] ?? '' }},</strong></p>
<p>
    Thank you for your order! We’re pleased to confirm that your order
    #{{ $mail_data['order']['tellecto_order_no'] ?? '' }} has been successfully placed
    through
    the TELLECTO B2B eCommerce platform.
</p>
<p><strong>Order Summary:</strong></p>
<ol style="list-style-type: disc; list-style-position: inside;">
    <li><strong>Order
            Number: </strong> {{ $mail_data['order']['tellecto_order_no'] ?? '' }}</li>
    <li><strong>Order
            Date: </strong> {{ date('d-m-y',strtotime($mail_data['order']['created_at'])) ?? '' }}
    </li>
    <li><strong>Items Ordered:</strong>
        <ol style="list-style-type: disc; list-style-position: inside;">
            @foreach($mail_data['order']['order_items'] as $item)
                <li>{{ $item->product->product_name ?? ''  }}
                    - {{ $item->quantity ?? '' }}
                    - {{ $item->total_price_agent ?? '' }}</li>
            @endforeach
        </ol>
    </li>
    <li><strong>Total
            Amount: </strong> {{ $mail_data['order']['total_order_amount_agent'] ?? '' }}
    </li>
    <li><strong>Shipping
            Method: </strong> {{ $mail_data['order']['delivery_type'] ?? '' }}</li>
    <li><strong>Shipping
            Address: </strong> {{ $mail_data['order']['delivery_address'] ?? '' }}, {{ $mail_data['order']['delivery_zip'] }} {{ $mail_data['order']['delivery_city'] }}</li>
    <li><strong>Payment
            Method: </strong> {{ $mail_data['order']['payment_method'] ?? '' }}</li>
</ol>

<br>
<p>Log into your TELLECTO account at <a href="https://tellecto.se/my-account/orders">https://tellecto.se/my-account/orders</a>
    to track the status of your order and view further details.</p>
<p>If you have any questions or need further assistance, our support team is here to help.
    Please email us at
    <a href="mailto:info@tellecto.se">info@tellecto.se</a>.</p>
<p>Thank you for choosing TELLECTO!</p>
