<?php

namespace App\Http\Controllers;

use App\Models\AssetCheckSchedule;
use App\Models\WarehouseMasterSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


class AssetCheckScheduleController extends Controller
{
    public function jadwalHome()
    {
        $sites = WarehouseMasterSite::all();
        return view('audit.penjadwalan-audit', compact('sites'));
    }

    public function fetch()
    {
        $events = AssetCheckSchedule::with('warehouseMasterSite')->get()->map(function($event) {
            return [
                'id' => $event->id,
                'title' => $event->request_subject,
                'start' => $event->arrival_date,
                'end' => $event->arrival_completed_date ?? $event->arrival_date, 
                'allDay' => true, 
                'status' => $event->status,
                'warehouseMasterSite' => $event->warehouseMasterSite,
                'description' => $event->description,
                'priority' => $event->priority
            ];
        });
    
        return response()->json($events);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'warehouse_master_site_id' => 'required|exists:warehouse_master_sites,id',
            'request_subject' => 'required|string|max:255', 
            'description' => 'nullable|string',
            'priority' => 'nullable|string|in:low,medium,high', 
            'arrival_date' => 'required|date',
            'arrival_completed_date' => 'nullable|date|after_or_equal:arrival_date',
            'status' => 'nullable|string|in:unassigned,open,waiting,resolved',
        ]);
    
        $event = AssetCheckSchedule::create($validated);
    
        return response()->json(['status' => 'success', 'data' => $event]);
    }
    

    public function edit($id)
    {
        $event = AssetCheckSchedule::findOrFail($id);
        return response()->json($event);
    }

    public function update(Request $request, $id)
    {
        try {
            $event = AssetCheckSchedule::findOrFail($id);
        
            $validated = $request->validate([
                'warehouse_master_site_id' => 'required|exists:warehouse_master_sites,id',
                'request_subject' => 'required|string|max:255', 
                'description' => 'nullable|string',
                'priority' => 'nullable|string|in:low,medium,high', 
                'arrival_date' => 'required|date', 
                'arrival_completed_date' => 'nullable|date|after_or_equal:arrival_date',
                'status' => 'nullable|string|in:unassigned,open,waiting,resolved',
            ]);
        
            // Log the original and new data for debugging
            Log::info('Updating event', [
                'event_id' => $event->id,
                'old_data' => $event->toArray(),
                'new_data' => $validated
            ]);
        
            // Update the event with validated data
            $event->update($validated);
        
            return response()->json([
                'status' => 'updated', 
                'data' => $event
            ]);
        } catch (\Exception $e) {
            // Log the full error for debugging
            Log::error('Event update error', [
                'event_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update event: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string',
            'note' => 'nullable|string',
        ]);

        $event = AssetCheckSchedule::findOrFail($id);
        $event->status = $request->status;
        $event->note = $request->note;
        $event->save();

        return response()->json(['status' => 'status_updated']);
    }

    public function destroy($id)
    {
        $event = AssetCheckSchedule::findOrFail($id);
        $event->delete();

        return response()->json(['status' => 'deleted']);
    }

    public function show($id)
    {
        $event = AssetCheckSchedule::with('warehouseMasterSite')->findOrFail($id);

        return response()->json([
            'status' => 'success',
            'data' => $event
        ]);
    }


}
