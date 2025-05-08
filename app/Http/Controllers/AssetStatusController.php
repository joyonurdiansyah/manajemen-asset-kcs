<?php

namespace App\Http\Controllers;

use App\Models\AssetStatus;
use App\Models\Category;
use App\Models\Subcategory;
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
        $assets = AssetStatus::with(['warehouseMasterSite', 'category', 'lokasiAwal', 'lokasiTujuan', 'subcategory'])->get();
        return response()->json(['data' => $assets]);
    }

    public function exportExcelAsset()
    {
        $assets = AssetStatus::with(['warehouseMasterSite', 'category', 'lokasiAwal', 'lokasiTujuan', 'subcategory'])->get();
        
        $data = [];
        
        // Header row
        $data[] = [
            'No',
            'Nomor Asset',
            'Merk Barang',
            'Serial Number',
            'Lokasi Awal',
            'Lokasi Tujuan',
            'Kategori',
            'Subkategori',
            'Tanggal Kunjungan',
            'Status Barang',
            'Notes',
            'Warehouse',
            'Dibuat Tanggal'
        ];
        
        // Data rows
        foreach ($assets as $index => $asset) {
            $data[] = [
                $index + 1,
                $asset->asset_code,
                $asset->brand,
                $asset->serial_number,
                $asset->lokasiAwal ? $asset->lokasiAwal->nama_lokasi : '',
                $asset->lokasiTujuan ? $asset->lokasiTujuan->nama_lokasi : '',
                $asset->category ? $asset->category->name : '',
                $asset->subcategory ? $asset->subcategory->name : '',
                $asset->tanggal_visit ? date('Y-m-d', strtotime($asset->tanggal_visit)) : '',
                ucfirst($asset->status_barang),
                $asset->notes,
                $asset->warehouseMasterSite ? $asset->warehouseMasterSite->nama_lokasi : '',
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
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'asset_code' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'lokasi_awal_id' => 'required|exists:warehouse_master_sites,id',
            'lokasi_tujuan_id' => 'nullable|exists:warehouse_master_sites,id',
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
    
        // Ensure warehouse_master_site_id is set from lokasi_awal_id
        if (empty($request->warehouse_master_site_id) && !empty($request->lokasi_awal_id)) {
            $request->merge(['warehouse_master_site_id' => $request->lokasi_awal_id]);
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
            'subcategory_id' => 'nullable|exists:subcategories,id',
            'asset_code' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'serial_number' => 'nullable|string|max:255',
            'lokasi_awal_id' => 'nullable|exists:warehouse_master_sites,id',
            'lokasi_tujuan_id' => 'nullable|exists:warehouse_master_sites,id',
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
    
    /**
     * Get subcategories based on category
     */
    public function getSubcategories($category_id)
    {
        $subcategories = Subcategory::where('category_id', $category_id)->get();
        return response()->json($subcategories);
    }
}