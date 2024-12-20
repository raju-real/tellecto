<?php

namespace App\Helper;

use Illuminate\Support\Facades\Http;

class DCSHelper
{
    private $username = null;
    private $api_key = null;

    public function __construct()
    {
        $this->username = "Tellecto";
        $this->api_key = "ZGpoaU95V3JVc2dZZmlWSUplSjdSbGhO";
    }

    /**
     * This method will return the live product information
     * @params array Product Code
     */
    public function getProductsInfo(array $product_codes)
    {
        $request_array = [
            'customer_nr' => 'Tellecto',
            'password' => $this->api_key,
//            'glspakkeshopid' => 174580,
        ];
        foreach ($product_codes as $value) {
            $request_array[] = ["vare_nr" => $value];
        }
        $xh = new XMLHelper();
        $request_xml = $xh->xml_encode($request_array);

        //Making HTTP Request
        // API endpoint
        $url = env('DCS_TRACK_ORDER');

        // Make the POST request
        $response = $this->submitXMLRequest($url, $request_xml);
        return $xh->xml_decode($response);
    }

    public function placeOrder(array $order_info, array $item_info)
    {
        $request_array = [
            'customer_nr' => 'Tellecto',
            'password' => $this->api_key
        ];
        $request_array = array_merge($request_array, $order_info, $item_info);
        $xh = new XMLHelper('<?xml version="1.0" encoding="UTF-8"?><order/>');
        $request_xml = $xh->xml_encode($request_array);

        $url = 'https://dcs.dk/xml/xmlOrder/';
        $response = $this->submitXMLRequest($url, $request_xml);
        return $xh->xml_decode($response);
    }

    private function submitXMLRequest($url, $xml_body)
    {
        // POST data (you can include additional parameters here)
        $req_body = [
            'xml' => $xml_body,
        ];

        // Create HTTP headers
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => 'Content-type: application/x-www-form-urlencoded',
                'content' => http_build_query($req_body),
            ],
        ];

        // Create stream context
        $context = stream_context_create($options);

        // Make the POST request
        return file_get_contents($url, false, $context);
    }

    private function checkReserveOrder($order_id)
    {
        $request_array = [
            'customer_nr' => 'Tellecto',
            'password' => $this->api_key
        ];

        $request_array = array_merge($request_array);

        $xh = new XMLHelper('<?xml version="1.0" encoding="UTF-8"?><order/>');
        return $request_xml = $xh->xml_encode($request_array);
    }

    public function getReserveStatus($requestData)
    {
        $request_array = [
            'customer_nr' => 'Tellecto',
            'password' => $this->api_key
        ];

        $requestData = array_merge($request_array, $requestData);

        $xh = new XMLHelper('<?xml version="1.0" encoding="UTF-8"?><reserveinfo/>');
        $request_xml = $xh->xml_encode($requestData);
        $url = 'https://dcs.dk/en/page/xml-order-docs#reserve-order/';

        $response = $this->submitXMLRequest($url, $request_xml);
        // info($response);
        return $xh->xml_decode($response);
    }

    public function getTrackStatus($requestData)
    {
        $request_array = [
            'customer_nr' => 'Tellecto',
            'password' => $this->api_key
        ];

        $requestData = array_merge($request_array, $requestData);

        $xh = new XMLHelper('<?xml version="1.0" encoding="UTF-8"?><tracking/>');
        $request_xml = $xh->xml_encode($requestData);
        $url = 'https://dcs.dk/xml/tt/';

        $response = $this->submitXMLRequest($url, $request_xml);
        // info($response);
        return $xh->xml_decode($response);
    }
}
