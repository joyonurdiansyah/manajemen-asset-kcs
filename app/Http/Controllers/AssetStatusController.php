<?php

namespace App\Http\Controllers;

use App\Models\AssetStatus;
use App\Models\Category;
use App\Models\WarehouseMasterSite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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

    public function exportExcelAsset()
    {
        $assets = AssetStatus::with(['warehouseMasterSite', 'category'])->get();
        
        $data = [];
        
        // Header row
        $data[] = [
            'No',
            'Nomor Asset',
            'Merk Barang',
            'Serial Number',
            'Lokasi Awal',
            'Lokasi Tujuan',
            'Type Barang',
            'Tanggal Kunjungan',
            'Status Barang',
            'Notes',
            'Warehouse',
            'Category',
            'Dibuat Tanggal'
        ];
        
        // Data rows
        foreach ($assets as $index => $asset) {
            $data[] = [
                $index + 1,
                $asset->asset_code,
                $asset->brand,
                $asset->serial_number,
                $asset->lokasi_awal,
                $asset->lokasi_tujuan,
                $asset->type,
                $asset->tanggal_visit ? date('Y-m-d', strtotime($asset->tanggal_visit)) : '',
                ucfirst($asset->status_barang),
                $asset->notes,
                $asset->warehouseMasterSite ? $asset->warehouseMasterSite->name : '',
                $asset->category ? $asset->category->name : '',
                date('Y-m-d H:i', strtotime($asset->created_at))
            ];
        }
        
        // Create Excel
        $filename = 'Data_Assets_IT_' . date('YmdHis') . '.xlsx';
        
        // Create a temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'excel');
        
        // Create workbook
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Add data
        $sheet->fromArray($data, NULL, 'A1');
        
        // Style header row
        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
            ],
        ];
        
        $sheet->getStyle('A1:M1')->applyFromArray($headerStyle);
        
        // Auto size columns
        foreach(range('A', 'M') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        // Save file
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);
        
        // Return file as download
        return response()->download($tempFile, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    public function storeAsset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'warehouse_master_site_id' => 'required|exists:warehouse_master_sites,id',
            'category_id' => 'required|exists:categories,id',
            'asset_code' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'tipe' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'lokasi_awal' => 'nullable|string|max:255',
            'lokasi_tujuan' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'tanggal_visit' => 'nullable|date',
            'status_barang' => 'required|in:oke,rusak,perbaikan',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $asset = AssetStatus::create($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Asset created successfully',
            'asset' => $asset
        ]);
    }

    /**
     * Get asset for editing
     */
    public function editAsset($id)
    {
        $asset = AssetStatus::findOrFail($id);
        
        return response()->json([
            'status' => true,
            'asset' => $asset
        ]);
    }

    /**
     * Update the specified asset in storage.
     */
    public function updateAsset(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'warehouse_master_site_id' => 'required|exists:warehouse_master_sites,id',
            'category_id' => 'required|exists:categories,id',
            'asset_code' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'tipe' => 'nullable|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'lokasi_awal' => 'nullable|string|max:255',
            'lokasi_tujuan' => 'nullable|string|max:255',
            'type' => 'nullable|string|max:255',
            'tanggal_visit' => 'nullable|date',
            'status_barang' => 'required|in:oke,rusak,perbaikan',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $asset = AssetStatus::findOrFail($id);
        $asset->update($request->all());

        return response()->json([
            'status' => true,
            'message' => 'Asset updated successfully',
            'asset' => $asset
        ]);
    }

    /**
     * Remove the specified asset from storage.
     */
    public function destroyAsset($id)
    {
        $asset = AssetStatus::findOrFail($id);
        $asset->delete();

        return response()->json([
            'status' => true,
            'message' => 'Asset deleted successfully'
        ]);
    }
}
