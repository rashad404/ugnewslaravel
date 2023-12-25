<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Response;

class ApiController extends Controller
{
    public function fetchAndConvertData($date)
    {
        if ($date == 'now') {
            $date = now()->format('d.m.Y');
        }

        // Önbellek anahtarını oluştur
        $cacheKey = 'cbar_data_' . $date;

        // Eğer önbellekte varsa, önbellekten verileri al
        if (Cache::has($cacheKey)) {
            $cachedData = Cache::get($cacheKey);
            return Response::json($cachedData);
        }

        // Eğer önbellekte yoksa, API'den verileri çek
        $response = Http::get("http://cbar.az/currencies/{$date}.xml");

        // Parse XML response
        $xmlData = simplexml_load_string($response->body(), 'SimpleXMLElement', LIBXML_NOCDATA);

        // Convert XML to JSON
        $jsonData = json_encode($xmlData);
        $dataArray = json_decode($jsonData, true);

        // Initialize converted data array
        $convertedData = [
            'body' => [
                'metal' => [],
                'bankNote' => [],
            ],
            'date' => $dataArray['@attributes']['Date'],
            'name' => $dataArray['@attributes']['Name'],
            'description' => $dataArray['@attributes']['Description'],
        ];

        // Loop through ValType and Valute elements
        foreach ($dataArray['ValType'] as $valType) {
            $type = $valType['@attributes']['Type'];

            foreach ($valType['Valute'] as $valute) {
                $code = $valute['@attributes']['Code'];
                $value = $valute['Value'];
                $nominal = $valute['Nominal'];

                // Check inflation and determine change
                $change = $this->checkChange($code, $value, $date);

                $convertedValute = [
                    'code' => $code,
                    'name' => $valute['Name'],
                    'value' => $value,
                    'nominal' => $nominal,
                    'change' => $change,
                ];

                // Add converted value to the appropriate array
                $convertedData['body'][$type === 'Bank metalları' ? 'metal' : 'bankNote'][] = $convertedValute;
            }
        }

        // Cache the data for the day
        Cache::put($cacheKey, $convertedData, now()->addDay());

        // Return the final JSON response
        return Response::json($convertedData);
    }

    // Function to check change
    private function checkChange($code, $value, $date)
    {
        // Get the previous day's data
        $previousDate = date('d.m.Y', strtotime('-1 day', strtotime($date)));
        $previousResponse = Http::get("http://cbar.az/currencies/{$previousDate}.xml");
        $previousXmlData = simplexml_load_string($previousResponse->body(), 'SimpleXMLElement', LIBXML_NOCDATA);
        $previousJsonData = json_decode(json_encode($previousXmlData), true);

        // Find the value for the previous day
        $previousValue = null;
        foreach ($previousJsonData['ValType'] as $valType) {
            foreach ($valType['Valute'] as $valute) {
                if ($valute['@attributes']['Code'] === $code) {
                    $previousValue = $valute['Value'];
                    break 2; // Exit both loops when the value is found
                }
            }
        }

        // Check for change
        if ($previousValue !== null) {
            $change = $value > $previousValue ? 'increased' : ($value < $previousValue ? 'decreased' : 'constant');
        } else {
            // If there is no previous value, set change as 'constant'
            $change = 'constant';
        }

        return $change;
    }
}
