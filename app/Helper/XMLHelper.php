<?php

namespace App\Helper;

class XMLHelper
{
    private $root = '<?xml version="1.0" encoding="UTF-8"?><pnarequest/>';
    public function __construct($root = null)
    {
        if ($root != null) {
            $this->root = $root;
        }
    }


    /**
     * Convert Array to XML String
     * @param   array  $data    Input PHP Array e.g.
     *  array(
     *  'customer_nr' => 'username',
     *  'password' => '************',
     *  array('vare_nr' => '1001007464'),
     *  array('vare_nr' => '1000982843')
     *  )
     */
    public function xml_encode(array $data)
    {
        // Create a new SimpleXMLElement
        $xml = new \SimpleXMLElement($this->root);

        // Call the function to convert array to XML
        $this->arrayToXml($data, $xml);

        // Output the XML
        return $xml->asXML();
    }

    public function xml_decode(string $data)
    {
        $xml = simplexml_load_string($data, "SimpleXMLElement", LIBXML_NOCDATA);
        $json = json_encode($xml);
        $array = json_decode($json, true);

        return $array;
    }

    // Function to convert array to XML
    private function arrayToXml($array, &$xml)
    {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                if (is_numeric($key)) {

                    $subnode = $xml->addChild('item');
                    $this->arrayToXml($value, $subnode);
                } else {
                    $subnode = $xml->addChild($key);
                    $this->arrayToXml($value, $subnode);
                }
            } else {
                $xml->addChild($key, htmlspecialchars($value));
            }
        }
    }
}
