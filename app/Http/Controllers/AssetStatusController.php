<?php

namespace App\Http\Controllers;

use App\Models\AssetStatus;
use App\Models\Category;
use App\Models\Subcategory;
use App\Models\WarehouseMasterSite;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;

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
        foreach (range('A', 'M') as $column) {
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

    public function downloadImportTemplate()
    {
        // Create Excel
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Headers
        $headers = [
            'Nomor Asset',
            'Merk Barang',
            'Serial Number',
            'Lokasi Awal (Kode)',
            'Lokasi Tujuan (Kode)',
            'Kategori (Nama)',
            'Subkategori (Nama)',
            'Tanggal Kunjungan (YYYY-MM-DD)',
            'Status Barang (oke/rusak/perbaikan)',
            'Notes'
        ];

        // Add headers
        $sheet->fromArray([$headers], NULL, 'A1');

        // Add example data
        $exampleData = [
            'A001',
            'Lenovo',
            'SN12345678',
            'LOK001',
            'LOK002',
            'Laptop',
            'Notebook',
            date('Y-m-d'),
            'oke',
            'Asset baru'
        ];
        $sheet->fromArray([$exampleData], NULL, 'A2');

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

        $sheet->getStyle('A1:J1')->applyFromArray($headerStyle);

        // Auto size columns
        foreach (range('A', 'J') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        // Create a temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'excel');

        // Save file
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempFile);

        // Return file as download
        return response()->download($tempFile, 'Template_Import_Assets_IT.xlsx', [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ])->deleteFileAfterSend(true);
    }

    public function importExcelAsset(Request $request)
    {
        // Validate the file input
        $validator = Validator::make($request->all(), [
            'excel_file' => 'required|file|mimes:xlsx|max:10240', // 10MB max
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => 'Validation failed', 'errors' => $validator->errors()], 422);
        }

        $file = $request->file('excel_file');

        try {
            $spreadsheet = IOFactory::load($file);
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            array_shift($rows);

            $successCount = 0;
            $errors = [];
            $locationsByCode = WarehouseMasterSite::pluck('id', 'kode')->toArray();
            $categoriesByName = Category::pluck('id', 'name')->toArray();
            $subcategoriesByNameAndCategory = [];

            foreach (Subcategory::with('category')->get() as $subcategory) {
                $key = strtolower($subcategory->name . '_' . $subcategory->category_id);
                $subcategoriesByNameAndCategory[$key] = $subcategory->id;
            }

            DB::beginTransaction();

            try {
                foreach ($rows as $index => $row) {
                    $rowNumber = $index + 2; 
                    if (empty($row[0]) && empty($row[1]) && empty($row[2])) {
                        continue;
                    }

                    // Extract data from row
                    $assetCode = trim($row[0]);
                    $brand = trim($row[1]);
                    $serialNumber = trim($row[2]);
                    $lokasiAwalCode = trim($row[3]);
                    $lokasiTujuanCode = trim($row[4]);
                    $categoryName = trim($row[5]);
                    $subcategoryName = trim($row[6]);
                    $tanggalVisit = trim($row[7]);
                    $statusBarang = strtolower(trim($row[8]));
                    $notes = trim($row[9]);

                    // Validate required fields
                    if (empty($assetCode) || empty($brand) || empty($lokasiAwalCode) || empty($categoryName)) {
                        $errors[] = [
                            'row' => $rowNumber,
                            'message' => 'Nomor Asset, Merk Barang, Lokasi Awal, dan Kategori wajib diisi.'
                        ];
                        continue;
                    }

                    // Validate status_barang
                    $validStatuses = ['oke', 'rusak', 'perbaikan'];
                    if (!empty($statusBarang) && !in_array($statusBarang, $validStatuses)) {
                        $errors[] = [
                            'row' => $rowNumber,
                            'message' => 'Status Barang harus berupa: oke, rusak, atau perbaikan.'
                        ];
                        continue;
                    }

                    // Find lokasi_awal_id by code
                    $lokasiAwalId = isset($locationsByCode[strtoupper($lokasiAwalCode)])
                        ? $locationsByCode[strtoupper($lokasiAwalCode)]
                        : null;

                    if (!$lokasiAwalId) {
                        $errors[] = [
                            'row' => $rowNumber,
                            'message' => "Lokasi Awal dengan kode '{$lokasiAwalCode}' tidak ditemukan."
                        ];
                        continue;
                    }

                    // Find lokasi_tujuan_id by code (optional)
                    $lokasiTujuanId = null;
                    if (!empty($lokasiTujuanCode)) {
                        $lokasiTujuanId = isset($locationsByCode[strtoupper($lokasiTujuanCode)])
                            ? $locationsByCode[strtoupper($lokasiTujuanCode)]
                            : null;

                        if (!$lokasiTujuanId) {
                            $errors[] = [
                                'row' => $rowNumber,
                                'message' => "Lokasi Tujuan dengan kode '{$lokasiTujuanCode}' tidak ditemukan."
                            ];
                            continue;
                        }
                    }

                    // Find category_id by name
                    $categoryId = isset($categoriesByName[ucwords(strtolower($categoryName))])
                        ? $categoriesByName[ucwords(strtolower($categoryName))]
                        : null;

                    if (!$categoryId) {
                        $errors[] = [
                            'row' => $rowNumber,
                            'message' => "Kategori dengan nama '{$categoryName}' tidak ditemukan."
                        ];
                        continue;
                    }

                    // Find subcategory_id by name and category_id (optional)
                    $subcategoryId = null;
                    if (!empty($subcategoryName) && $categoryId) {
                        $subcategoryKey = strtolower($subcategoryName . '_' . $categoryId);
                        $subcategoryId = isset($subcategoriesByNameAndCategory[$subcategoryKey])
                            ? $subcategoriesByNameAndCategory[$subcategoryKey]
                            : null;
                    }

                    // Validate date format
                    $formattedDate = null;
                    if (!empty($tanggalVisit)) {
                        try {
                            $formattedDate = Carbon::parse($tanggalVisit)->format('Y-m-d');
                        } catch (\Exception $e) {
                            $errors[] = [
                                'row' => $rowNumber,
                                'message' => "Format tanggal kunjungan tidak valid. Gunakan format YYYY-MM-DD."
                            ];
                            continue;
                        }
                    }

                    // Check if asset_code already exists
                    $existingAsset = AssetStatus::where('asset_code', $assetCode)->first();
                    if ($existingAsset) {
                        $errors[] = [
                            'row' => $rowNumber,
                            'message' => "Nomor Asset '{$assetCode}' sudah ada di database."
                        ];
                        continue;
                    }

                    // Create new asset record
                    $asset = new AssetStatus();
                    $asset->asset_code = $assetCode;
                    $asset->brand = $brand;
                    $asset->serial_number = $serialNumber;
                    $asset->lokasi_awal_id = $lokasiAwalId;
                    $asset->lokasi_tujuan_id = $lokasiTujuanId;
                    $asset->category_id = $categoryId;
                    $asset->subcategory_id = $subcategoryId;
                    $asset->tanggal_visit = $formattedDate;
                    $asset->status_barang = $statusBarang ?: 'oke'; 
                    $asset->notes = $notes;
                    $asset->warehouse_master_site_id = $lokasiAwalId; 
                    $asset->save();

                    $successCount++;
                }

                // Commit transaction if no errors
                DB::commit();

                return response()->json([
                    'success' => $successCount,
                    'errors' => $errors,
                    'message' => 'Import selesai'
                ]);

            } catch (\Exception $e) {
                // Rollback transaction if any error
                DB::rollBack();

                return response()->json([
                    'message' => 'Error during import: ' . $e->getMessage()
                ], 500);
            }

        } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
            return response()->json([
                'message' => 'Error reading Excel file: ' . $e->getMessage()
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
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