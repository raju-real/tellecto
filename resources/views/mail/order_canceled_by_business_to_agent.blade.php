<p><strong>Dear {{ $mail_data['order']['agent']['full_name'] ?? '' }},</strong></p>
<p>
    We regret to inform you that your order #{{ $mail_data['order']['tellecto_order_no'] ?? '' }}, has been cancelled by
    the {{ $mail_data['order']['business']['user_information']['company_name'] ?? '' }}.
</p>
<p><strong>Order Summary:</strong></p>
<ol style="list-style-type: disc; list-style-position: inside;">
    <li><strong>Order Number: </strong> {{ $mail_data['order']['tellecto_order_no'] ?? '' }}</li>
    <li><strong>Order Date: </strong> {{ date('d-m-y',strtotime($mail_data['order']['created_at'])) ?? '' }}</li>
    <li><strong>Items Ordered:</strong>
        <ol style="list-style-type: disc; list-style-position: inside;">
            @foreach($mail_data['order']['order_items'] as $item)
                <li>{{ $item->product->product_name ?? ''  }}
                    - {{ $item->quantity ?? '' }}
                    - {{ $item->total_price_agent ?? '' }}</li>
            @endforeach
        </ol>
    </li>
    <li><strong>Total Amount: </strong> {{ $mail_data['order']['total_order_amount_agent'] ?? '' }}</li>
    <li><strong>Shipping Method: </strong> {{ $mail_data['order']['delivery_type'] ?? '' }}</li>
    <li><strong>Shipping Address: </strong> {{ $mail_data['order']['delivery_address'] ?? '' }}, {{ $mail_data['order']['delivery_zip'] }} {{ $mail_data['order']['delivery_city'] }}</li>
    <li><strong>Payment Method: </strong> {{ $mail_data['order']['payment_method'] ?? '' }}</li>
    <li><strong>Reject by: </strong> {{ getUserNameByID($mail_data['order']['canceled_by']) ?? '' }}</li>
</ol>

<br>
<p>Reason for Cancellation:</p>
<p>{{ $mail_data['order']['canceled_for'] ?? 'N/A' }}</p>
<p>If you would like to discuss this decision or need assistance in placing a new order, please feel free to contact us at
    <a href="mailto:info@tellecto.se"> info@tellecto.se</a></p>
<p>We appreciate your understanding and look forward to serving you in the future.</p>
