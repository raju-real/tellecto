<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use PhpOffice\PhpSpreadsheet\IOFactory;

class MaxExcelRows implements Rule
{
    protected $maxRows;

    public function __construct($maxRows = 1000)
    {
        $this->maxRows = $maxRows;
    }

    public function passes($attribute, $value)
    {
        try {
            // Load the Excel file and count rows
            $spreadsheet = IOFactory::load($value->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rowCount = $worksheet->getHighestRow();

            return $rowCount <= $this->maxRows;
        } catch (\Exception $e) {
            return false;
        }
    }

    public function message()
    {
        return "The :attribute must not contain more than {$this->maxRows} rows.";
    }
}
