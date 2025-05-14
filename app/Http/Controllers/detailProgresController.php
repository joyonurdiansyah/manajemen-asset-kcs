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
        
        return view('asset_check_schedules', compact('assetSchedules', 'sites'));
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
    // public function updateStatus(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'asset_check_schedule_id' => 'required|exists:asset_check_schedules,id',
    //         'status' => 'required|in:unassigned,open,waiting,resolved',
    //         'note' => 'nullable|string',
    //         'arrival_completed_date' => 'nullable|date'
    //     ]);
        
    //     if ($validator->fails()) {
    //         return redirect()->back()
    //             ->withErrors($validator)
    //             ->withInput();
    //     }
        
    //     $assetSchedule = AssetCheckSchedule::findOrFail($request->asset_check_schedule_id);
    //     $oldStatus = $assetSchedule->status;
    //     $newStatus = $request->status;
        
    //     // Update asset check schedule status
    //     $assetSchedule->status = $newStatus;
        
    //     // If status is resolved, update the completion date
    //     if ($newStatus === 'resolved') {
    //         $assetSchedule->arrival_completed_date = $request->arrival_completed_date ?? now();
    //     }
        
    //     $assetSchedule->save();
        
    //     // Create status history entry
    //     AssetStatusHistory::create([
    //         'asset_check_schedule_id' => $assetSchedule->id,
    //         'status' => $newStatus,
    //         'note' => $request->note,
    //         'changed_by' => auth()->id() ?? 1 // Default to ID 1 if not authenticated
    //     ]);
        
    //     return redirect()->route('asset-check-schedules.index')
    //         ->with('success', 'Status aset berhasil diperbarui.');
    // }
    
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
        return view('asset_check_schedules', compact('assetSchedules', 'sites'));
    }
}
