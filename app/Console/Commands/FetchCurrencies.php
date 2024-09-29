<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Currency;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class FetchCurrencies extends Command
{
    protected $signature = 'currencies:fetch';
    protected $description = 'Fetch currency data from the Central Bank of Azerbaijan';

    public function handle()
    {
        // Get today's date
        $today = Carbon::today()->format('d.m.Y');
        $url = "https://www.cbar.az/currencies/{$today}.xml";

        // Fetch the XML
        try {
            $response = Http::get($url);
            if ($response->ok()) {
                $xml = simplexml_load_string($response->body());
                $date = Carbon::parse($xml['Date'])->format('Y-m-d');

                foreach ($xml->ValType as $valType) {
                    foreach ($valType->Valute as $valute) {
                        // Insert or update the currency data
                        Currency::updateOrCreate(
                            [
                                'code' => (string) $valute['Code'],
                                'date' => $date,
                            ],
                            [
                                'name' => (string) $valute->Name,
                                'nominal' => (int) $valute->Nominal,
                                'value' => (float) $valute->Value,
                            ]
                        );
                    }
                }

                $this->info('Currencies fetched and saved successfully.');
            } else {
                $this->error("Failed to fetch the data. HTTP Status: {$response->status()}");
            }
        } catch (\Exception $e) {
            $this->error("An error occurred: " . $e->getMessage());
        }
    }
}
