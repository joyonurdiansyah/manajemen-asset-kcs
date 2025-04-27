<?php

namespace App\Http\Controllers;
use App\Models\AssetStatus;

use Illuminate\Http\Request;

class AssetStatusController extends Controller
{
    public function assetHome()
    {
        return view('asset');
    }

    public function assetData()
    {
        $assets = AssetStatus::all();
        return response()->json(['data' => $assets]);
    }
    
}
