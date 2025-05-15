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
}
