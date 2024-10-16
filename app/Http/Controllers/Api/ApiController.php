<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

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

        $currentCacheKey = 'cbar_data_' . $date;
        $previousDate = date('d.m.Y', strtotime('-1 day', strtotime($date)));
        $previousCacheKey = 'cbar_data_' . $previousDate;

        $currentData = $this->fetchAndCacheData($currentCacheKey, $date);
        $previousData = $this->fetchAndCacheData($previousCacheKey, $previousDate);

        $convertedData = $this->convertData($currentData, $previousData, $date);

        return Response::json($convertedData);
    }

    private function fetchAndCacheData($cacheKey, $date)
    {
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $response = Http::get("http://cbar.az/currencies/{$date}.xml");
        $xmlData = simplexml_load_string($response->body(), 'SimpleXMLElement', LIBXML_NOCDATA);
        $jsonData = json_decode(json_encode($xmlData), true);

        Cache::put($cacheKey, $jsonData, now()->addDay());

        return $jsonData;
    }

    private function convertData($currentData, $previousData, $date)
    {
        return Cache::remember('converted_data_' . $date, now()->addDay(), function () use ($currentData, $previousData, $date) {
            $convertedData = [
                'body' => [
                    'metal' => [],
                    'bankNote' => [],
                ],
                'date' => $currentData['@attributes']['Date'],
                'name' => $currentData['@attributes']['Name'],
                'description' => $currentData['@attributes']['Description'],
            ];

            foreach ($currentData['ValType'] as $valType) {
                $type = $valType['@attributes']['Type'];

                foreach ($valType['Valute'] as $valute) {
                    $code = $valute['@attributes']['Code'];
                    $value = $valute['Value'];
                    $nominal = $valute['Nominal'];

                    $previousValue = $this->getPreviousValue($code, $previousData);

                    $change = $this->checkChange($value, $previousValue);

                    $convertedValute = [
                        'code' => $code,
                        'name' => $valute['Name'],
                        'value' => $value,
                        'nominal' => $nominal,
                        'change' => $change,
                    ];

                    $convertedData['body'][$type === 'Bank metalları' ? 'metal' : 'bankNote'][] = $convertedValute;
                }
            }

            return $convertedData;
        });
    }

    private function checkChange($currentValue, $previousValue)
    {
        return $previousValue !== null ? ($currentValue > $previousValue ? 'increased' : ($currentValue < $previousValue ? 'decreased' : 'constant')) : 'constant';
    }

    private function getPreviousValue($code, $previousData)
    {
        $previousValue = null;

        foreach ($previousData['ValType'] as $valType) {
            foreach ($valType['Valute'] as $valute) {
                if ($valute['@attributes']['Code'] === $code) {
                    $previousValue = $valute['Value'];
                    break 2;
                }
            }
        }

        return $previousValue;
    }
}
