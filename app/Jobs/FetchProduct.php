<?php

namespace App\Jobs;

use Exception;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;
use League\Csv\Writer;

class FetchProduct implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        ini_set('memory_limit', '2048M');
        set_time_limit(0); // Remove the execution time limit
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        try {
            Log::info("Product fetching and store as csv job started...");
            // Fetch the CSV data from the API with a longer timeout
            $response = Http::timeout(300) // Set timeout to 300 seconds (5 minutes)
                            ->get('https://dcs.dk/pricelist?kundenummer=Tellecto&type=90823b9ee27a46f225f60e5b529d269d5c5bc');

            if ($response->successful()) {
                $csvData = $response->body();

                // Open a temporary file to write the reformatted CSV data
                $tempFilePath = storage_path('app/csv/tellecto_products.csv');
                $tempFile = fopen($tempFilePath, 'w');

                // Create a CSV reader from the response body
                $csvReader = Reader::createFromString($csvData);
                $csvReader->setDelimiter(';'); // Use the semicolon as the delimiter
                $csvReader->setEnclosure('"'); // Handle fields enclosed with double quotes

                // Get the header row
                $header = $csvReader->fetchOne();
                $expectedColumnCount = count($header);

                // Create a CSV writer
                $csvWriter = Writer::createFromPath($tempFilePath, 'w');
                $csvWriter->setDelimiter(','); // Write with comma delimiter
                $csvWriter->insertOne($header);

                // Process rows in chunks
                $csvReader->setHeaderOffset(0);
                foreach ($csvReader->getRecords() as $record) {
                    $row = array_values($record);
                    // Fill missing columns with empty strings
                    if (count($row) < $expectedColumnCount) {
                        $row = array_pad($row, $expectedColumnCount, '');
                    }
                    // Trim excess columns
                    if (count($row) > $expectedColumnCount) {
                        $row = array_slice($row, 0, $expectedColumnCount);
                    }
                    // Write the row to the temporary file
                    $csvWriter->insertOne($row);
                }

                // Close the temporary file
                fclose($tempFile);

                // Move the temporary file to the final location
                $finalFilePath = 'csv/tellecto_products.csv';
                Storage::disk('local')->move($tempFilePath, $finalFilePath);

                // Dispatch the next job to process the CSV data
                //StoreProduct::dispatch();
                echo "CSV data reformatted and saved successfully to " . storage_path('app/' . $finalFilePath);
            } else {
                // Handle the error
                Log::error('HTTP request failed with status: ' . $response->status());
                echo 'Request failed: ' . $response->status();
            }
        } catch (Exception $e) {
            // Log the exception
            Log::error('Error processing CSV data: ' . $e->getMessage());
            echo 'Error: ' . $e->getMessage();
        }
    }
}
