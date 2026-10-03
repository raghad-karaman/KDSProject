<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class PythonApiController extends Controller
{
    public function summary()
    {
        // Python API URL (FastAPI)
        $response = Http::get('http://127.0.0.1:8001/analytics/summary');

        // JSON olarak döndür
        return $response->json();
    }

    public function topRegions()
    {
        $response = Http::get('http://127.0.0.1:8001/analytics/regions/top');
        return $response->json();
    }

    public function needsDistribution()
{
    return Http::get(
        'http://127.0.0.1:8001/analytics/needs/distribution'
    )->json();
}


    public function mapData()
    {
        $response = Http::get('http://127.0.0.1:8001/analytics/map');
        return $response->json();
    }

    public function criticalRegionCount()
{
    $response = Http::get('http://127.0.0.1:8001/analytics/regions/critical-count');
    return $response->json();
}

}
