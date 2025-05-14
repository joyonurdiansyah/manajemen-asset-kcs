<?php

namespace App\Http\Controllers;

use App\Models\AssetCheckSchedule;
use App\Models\WarehouseMasterSite;
use App\Models\AssetStatusHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class detailProgresController extends Controller
{
    public function index()
    {
        $assetSchedules = AssetCheckSchedule::with('warehouseMasterSite')->get();
        $sites = WarehouseMasterSite::all();
        
        return view('audit.detail-progres-audit', compact('assetSchedules', 'sites'));
    }
    
    /**
     * Display the specified asset check schedule.
     */
    public function show($id)
    {
        $assetSchedule = AssetCheckSchedule::with(['warehouseMasterSite', 'statusHistory'])
            ->findOrFail($id);
            
        return response()->json($assetSchedule);
    }
    
    /**
     * Update the status of an asset check schedule.
     */

    // on progres
    public function updateStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'asset_check_schedule_id' => 'required|exists:asset_check_schedules,id',
            'status' => 'required|in:unassigned,open,waiting,resolved',
            'note' => 'nullable|string',
            'arrival_completed_date' => 'nullable|date'
        ]);
        
        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        $assetSchedule = AssetCheckSchedule::findOrFail($request->asset_check_schedule_id);
        $newStatus = $request->status;
        
        // Update data
        $assetSchedule->status = $newStatus;
        $assetSchedule->note = $request->note;
        $assetSchedule->changed_by = auth()->user()->name ?? 'System';
    
        if ($newStatus === 'resolved') {
            $assetSchedule->arrival_completed_date = $request->arrival_completed_date ?? now();
        }
    
        $assetSchedule->save();
    
        return redirect()->route('asset-check-schedules.index')
            ->with('success', 'Status aset berhasil diperbarui.');
    }
    
    
    /**
     * Filter asset check schedules based on search criteria.
     */
    public function filter(Request $request)
    {
        $query = AssetCheckSchedule::with('warehouseMasterSite');
        
        if ($request->has('status') && !empty($request->status)) {
            $query->where('status', $request->status);
        }
        
        if ($request->has('priority') && !empty($request->priority)) {
            $query->where('priority', $request->priority);
        }
        
        if ($request->has('location') && !empty($request->location)) {
            $query->where('warehouse_master_site_id', $request->location);
        }
        
        if ($request->has('search') && !empty($request->search)) {
            $query->where('request_subject', 'like', '%' . $request->search . '%');
        }
        
        $assetSchedules = $query->get();
        
        if ($request->ajax()) {
            return response()->json($assetSchedules);
        }
        
        $sites = WarehouseMasterSite::all();
        return view('audit.detail-progres-audit', compact('assetSchedules', 'sites'));
    }
}
