<p><strong>Dear Tellecto Team,</strong></p>
<p>
    A new company, {{ $mail_data['user']['user_information']['company_name'] ?? 'N/A' }}, has submitted a registration form on our B2B eCommerce platform, Tellecto.se
</p>
<p>Please review the details and approve or reject the registration through the admin panel at <a href="http://admin.tellecto.se/">http://admin.tellecto.se/</a></p>
<p><strong>Registration Details in a Table:</strong></p>

<table style="border-collapse: collapse;width: 100%;">
    <tr style="border: 1px solid black;">
        <th style="border: 1px solid black;text-align: left;padding: 8px;">Company Name</th>
        <td style="border: 1px solid black;text-align: left;padding: 8px;">{{ $mail_data['user']['user_information']['company_name'] ?? '' }}</td>
    </tr>
{{--    <tr style="border: 1px solid black;">--}}
{{--        <th style="border: 1px solid black;text-align: left;padding: 8px;">Contact Person</th>--}}
{{--        <td style="border: 1px solid black;text-align: left;padding: 8px;">{{ $mail_data['user']['user_information']['contact_person'] ?? '' }}</td>--}}
{{--    </tr>--}}
    <tr style="border: 1px solid black;">
        <th style="border: 1px solid black;text-align: left;padding: 8px;">Email</th>
        <td style="border: 1px solid black;text-align: left;padding: 8px;">{{ $mail_data['user']['email'] ?? '' }}</td>
    </tr>
    <tr style="border: 1px solid black;">
        <th style="border: 1px solid black;text-align: left;padding: 8px;">Mobile</th>
        <td style="border: 1px solid black;text-align: left;padding: 8px;">{{ $mail_data['user']['mobile'] ?? '' }}</td>
    </tr>
    <tr style="border: 1px solid black;">
        <th style="border: 1px solid black;text-align: left;padding: 8px;">VAT No.</th>
        <td style="border: 1px solid black;text-align: left;padding: 8px;">{{ $mail_data['user']['user_information']['vat_no'] ?? '' }}</td>
    </tr>
    <tr style="border: 1px solid black;">
        <th style="border: 1px solid black;text-align: left;padding: 8px;">Org. No</th>
        <td style="border: 1px solid black;text-align: left;padding: 8px;">{{ $mail_data['user']['user_information']['org_no'] ?? '' }}</td>
    </tr>
    <tr style="border: 1px solid black;">
        <th style="border: 1px solid black;text-align: left;padding: 8px;">Address</th>
        <td style="border: 1px solid black;text-align: left;padding: 8px;">{{ $mail_data['user']['user_information']['street']. ', '. $mail_data['user']['user_information']['zip_code']. ', '.$mail_data['user']['user_information']['city']  }}</td>
    </tr>
    <tr style="border: 1px solid black;">
        <th style="border: 1px solid black;text-align: left;padding: 8px;">Website</th>
        <td style="border: 1px solid black;text-align: left;padding: 8px;">{{ $mail_data['user']['user_information']['website_url'] ?? '' }}</td>
    </tr>
</table>
