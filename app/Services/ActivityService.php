<?php 

namespace App\Services;

use App\Models\AssetStatus;
use App\Models\AssetCheckSchedule;
use Carbon\Carbon;

class ActivityService {
    public static function getLatestActivities($limit = 4)
    {
        // Get latest 2 asset statuses
        $assetStatuses = AssetStatus::with(['asset'])
            ->latest()
            ->take(2)
            ->get()
            ->map(function($status) {
                return [
                    'type' => 'asset_status',
                    'id' => $status->id,
                    'created_at' => $status->created_at,
                    'time_ago' => Carbon::parse($status->created_at)->diffForHumans(null, true),
                    'content' => 'telah memperbarui status <span class="fw-bold text-info">' .
                                ($status->asset ? $status->asset->asset_code : 'Barang') .
                                ' menjadi "' . $status->status . '"</span>',
                    'user' => $status->created_by ?? 'System',
                    'badge_class' => 'text-info'
                ];
            });

        // Get latest 2 asset check schedules
        $assetSchedules = AssetCheckSchedule::with(['warehouseMasterSite'])
            ->latest()
            ->take(2)
            ->get()
            ->map(function($schedule) {
                $location = $schedule->warehouseMasterSite ? $schedule->warehouseMasterSite->nama_lokasi : 'lokasi tidak diketahui';
                return [
                    'type' => 'asset_schedule',
                    'id' => $schedule->id,
                    'created_at' => $schedule->created_at,
                    'time_ago' => Carbon::parse($schedule->created_at)->diffForHumans(null, true),
                    'content' => 'telah menambahkan jadwal <span class="fw-bold text-warning">' .
                                $schedule->request_subject .
                                ' ke ' . $location . '</span>',
                    'user' => $schedule->created_by ?? 'System',
                    'badge_class' => 'text-warning'
                ];
            });

        // Merge collections and sort by created_at
        $activities = $assetStatuses->concat($assetSchedules)
            ->sortByDesc('created_at')
            ->take($limit)
            ->values();

        return $activities;
    }
}