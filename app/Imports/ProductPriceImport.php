<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Exception;

class ProductPriceImport implements ToCollection
{
    public $data = [];

    /**
     * This method is called for each row in the imported file.
     *
     * @param Collection $rows
     * @throws Exception
     */
    public function collection(Collection $rows)
    {
        if ($rows->count() - 1 > 5000) {  // Subtracting 1 for the header row
            throw new Exception("The file contains more than 5000 rows, which is not allowed.");
        }
        // Assuming the first row is the header
        $header = $rows->first();
        // Define the expected headers
        $expectedHeaders = ['product_number', 'product_name', 'price', 'sale_price'];
        // Validate headers
        $missingHeaders = array_diff($expectedHeaders, $header->toArray());
        $extraHeaders = array_diff($header->toArray(), $expectedHeaders);

        if (!empty($missingHeaders) || !empty($extraHeaders)) {
            $errorMessage = "Header validation failed. ";
            if (!empty($missingHeaders)) {
                $errorMessage .= "Missing headers: " . implode(', ', $missingHeaders) . ". ";
            }
            if (!empty($extraHeaders)) {
                $errorMessage .= "Unexpected headers: " . implode(', ', $extraHeaders) . ".";
            }
            throw new Exception($errorMessage);
        }

        // Skip the header row
        $rows = $rows->skip(1);

        foreach ($rows as $row) {
            // Map the fields to the desired keys
            $rowObject = (object)[
                'product_number' => $this->getValue($row, $header, 'product_number'),
                'product_name' => $this->getValue($row, $header, 'product_name'),
                'price' => $this->getValue($row, $header, 'price'),
                'sale_price' => $this->getValue($row, $header, 'sale_price')
            ];
            // Add the object to the data array
            $this->data[] = $rowObject;
        }
    }


    /**
     * Return the processed data.
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Get the value from the row based on the header key.
     *
     * @param Collection $row
     * @param Collection $header
     * @param string $key
     * @return mixed
     */
    protected function getValue(Collection $row, Collection $header, string $key)
    {
        $index = $header->search($key);
        if ($index === false) {
            return null;
        }
        return $row[$index] ?? null;
    }
}
