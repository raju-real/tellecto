<p><strong>Dear TELLECTO Admin,</strong></p>
<p>
    We are pleased to inform you that the business owner of {{ $mail_data['order']['agent_billing_address']['company_name'] }} has approved order #{{ $mail_data['order']['tellecto_order_no'] }}, placed by their agent on the TELLECTO B2B eCommerce platform.

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
                    - {{ $item->total_price_admin ?? '' }}</li>
            @endforeach
        </ol>
    </li>
    <li><strong>Total Amount: </strong> {{ $mail_data['order']['total_order_amount_admin'] ?? '' }}</li>
    <li><strong>Shipping Method: </strong> {{ $mail_data['order']['delivery_type'] ?? '' }}</li>
    <li><strong>Shipping Address: </strong> {{ $mail_data['order']['delivery_address'] ?? '' }}, {{ $mail_data['order']['delivery_zip'] }} {{ $mail_data['order']['delivery_city'] }}</li>
    <li><strong>Payment Method: </strong> {{ $mail_data['order']['payment_method'] ?? '' }}</li>
    <li><strong>Approved by: </strong> {{ getUserNameByID($mail_data['order']['confirmed_by']) ?? '' }}</li>
</ol>

<br>
<p>Please log in to the admin panel at <a href="https://admin.tellecto.se/login">https://admin.tellecto.se/login</a>  to review and approve the order for processing.</p>
<p>For any questions or additional details, feel free to reach out to us.</p>
<p>Thank you for choosing TELLECTO!</p>
