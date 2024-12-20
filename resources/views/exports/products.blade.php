<table>
    <thead>
        <tr>
            <th>product_number</th>
            <th>product_name</th>
            <th>price</th>
            <th>sale_price</th>
        </tr>
    </thead>
    <tbody>
        @foreach($products as $product)
            <tr>
                <td>{{ $product->product_number }}</td>
                <td>{{ $product->product_name }}</td>
                <td>{{ $product->price }}</td>
                <td>{{ $product->sale_price }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
