<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        /* ================= TOPLAM İHTİYAÇ ================= */
        try {
            $response = Http::timeout(3)->get('http://127.0.0.1:8001/analytics/summary');

            if ($response->successful()) {
                $data = $response->json();
                $totalNeed =
                    ($data['total_water'] ?? 0) +
                    ($data['total_food'] ?? 0) +
                    ($data['total_tent'] ?? 0) +
                    ($data['total_medicine'] ?? 0);
            } else {
                $totalNeed = 0;
            }
        } catch (\Exception $e) {
            $totalNeed = 0;
        }

        /* ================= EN ÇOK İHTİYAÇ OLAN 5 BÖLGE ================= */
        $topRegions = [];
        try {
            $res = Http::timeout(3)
                ->get('http://127.0.0.1:8001/analytics/regions/top');

            if ($res->successful()) {
                $topRegions = $res->json();
            }
        } catch (\Exception $e) {
            $topRegions = [];
        }

        /* ================= KRİTİK BÖLGE SAYISI ================= */
        try {
            $criticalResponse = Http::timeout(3)
                ->get('http://127.0.0.1:8001/analytics/regions/critical-count');

            if ($criticalResponse->successful()) {
                $criticalRegionCount =
                    $criticalResponse->json()['critical_count'] ?? 0;
            } else {
                $criticalRegionCount = 0;
            }
        } catch (\Exception $e) {
            $criticalRegionCount = 0;
        }

        /* ================= DAĞITIM ÖZETİ ================= */
        try {
            $distResponse = Http::timeout(3)
                ->get('http://127.0.0.1:8001/analytics/distribution/summary');

            if ($distResponse->successful()) {
                $distData = $distResponse->json();
                $totalDistributed = $distData['total_distributed'] ?? 0;
                $pendingNeed = $distData['pending_need'] ?? 0;
            } else {
                $totalDistributed = 0;
                $pendingNeed = 0;
            }
        } catch (\Exception $e) {
            $totalDistributed = 0;
            $pendingNeed = 0;
        }

        /* ================= İHTİYAÇ TREND (7 GÜN) ================= */
        try {
            $trendResponse = Http::timeout(3)
                ->get('http://127.0.0.1:8001/analytics/needs/trend/7days');

            if ($trendResponse->successful()) {
                $trend = $trendResponse->json();
                $trendTotal = $trend['last_7_total'] ?? 0;
                $trendPercent = $trend['change_percent'] ?? 0;
            } else {
                $trendTotal = 0;
                $trendPercent = 0;
            }
        } catch (\Exception $e) {
            $trendTotal = 0;
            $trendPercent = 0;
        }

        /* ================= İHTİYAÇ DAĞILIMI ================= */
        try {
            $needDistResponse = Http::timeout(3)
                ->get('http://127.0.0.1:8001/analytics/needs/distribution');

            $needDistribution = $needDistResponse->successful()
                ? $needDistResponse->json()
                : ['water'=>0,'food'=>0,'tent'=>0,'medicine'=>0];
        } catch (\Exception $e) {
            $needDistribution = ['water'=>0,'food'=>0,'tent'=>0,'medicine'=>0];
        }

        /* ================= EKİP DURUMU ================= */
        try {
            $teamStatusResponse = Http::timeout(3)
                ->get('http://127.0.0.1:8001/analytics/teams/status-distribution');

            $teamStatus = $teamStatusResponse->successful()
                ? $teamStatusResponse->json()
                : [];
        } catch (\Exception $e) {
            $teamStatus = [];
        }
        

        return view(
            'admin.index.dashboard',
            compact(
                'totalNeed',
                'criticalRegionCount',
                'totalDistributed',
                'pendingNeed',
                'trendTotal',
                'trendPercent',
                'needDistribution',
                'teamStatus',
                'topRegions'
            )
        );
    }
}
