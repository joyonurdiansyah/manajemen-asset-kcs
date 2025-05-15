<?php

namespace App\Http\Controllers;

use App\Models\AssetStatus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function getBrandData()
    {
        // Group by brand and count
        $brandData = AssetStatus::select('brand', DB::raw('count(*) as count'))
                        ->whereNotNull('brand')
                        ->groupBy('brand')
                        ->get();
        
        // Format data for chart.js
        $labels = $brandData->pluck('brand')->toArray();
        $data = $brandData->pluck('count')->toArray();
        
        // Generate random colors for consistency
        $backgroundColors = [];
        $borderColors = [];
        
        foreach ($labels as $index => $label) {
            $r = rand(0, 255);
            $g = rand(0, 255);
            $b = rand(0, 255);
            
            $backgroundColors[] = "rgba($r, $g, $b, 0.2)";
            $borderColors[] = "rgba($r, $g, $b, 1)";
        }
        
        return response()->json([
            'labels' => $labels,
            'data' => $data,
            'backgroundColors' => $backgroundColors,
            'borderColors' => $borderColors
        ]);
    }

    public function getLocationData()
    {
        // Join with WarehouseMasterSite to get location names and count assets by location
        $locationData = AssetStatus::select('warehouse_master_sites.nama_lokasi', DB::raw('count(*) as count'))
                        ->join('warehouse_master_sites', 'asset_statuses.lokasi_awal_id', '=', 'warehouse_master_sites.id')
                        ->whereNotNull('lokasi_awal_id')
                        ->groupBy('warehouse_master_sites.nama_lokasi')
                        ->get();
        
        // Format data for chart.js
        $labels = $locationData->pluck('nama_lokasi')->toArray();
        $data = $locationData->pluck('count')->toArray();
        
        // Calculate percentages
        $total = array_sum($data);
        $percentages = [];
        
        foreach ($data as $value) {
            $percentages[] = $total > 0 ? round(($value / $total) * 100, 1) : 0;
        }
        
        // Define vibrant colors for pie chart
        $backgroundColors = [
            'rgb(255, 99, 132)',   // Red
            'rgb(54, 162, 235)',   // Blue
            'rgb(255, 205, 86)',   // Yellow
            'rgb(75, 192, 192)',   // Teal
            'rgb(153, 102, 255)',  // Purple
            'rgb(255, 159, 64)',   // Orange
            'rgb(201, 203, 207)'   // Grey
        ];
        
        // If we have more locations than colors, generate additional colors
        if (count($labels) > count($backgroundColors)) {
            for ($i = count($backgroundColors); $i < count($labels); $i++) {
                $r = rand(0, 255);
                $g = rand(0, 255);
                $b = rand(0, 255);
                $backgroundColors[] = "rgb($r, $g, $b)";
            }
        }
        
        return response()->json([
            'labels' => $labels,
            'data' => $data,
            'percentages' => $percentages,
            'backgroundColors' => array_slice($backgroundColors, 0, count($labels))
        ]);
    }
}
