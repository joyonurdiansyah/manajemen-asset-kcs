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
use Illuminate\Support\Facades\Auth;

class AssetStatusController extends Controller
{
    public function assetHome()
    {
        return view('asset');
    }

    public function assetData()
    {
        $assets = AssetStatus::with(['warehouseMasterSite', 'category', 'lokasiAwal', 'lokasiTujuan', 'subcategory', 'pic'])->get();
        return response()->json(['data' => $assets]);
    }

    public function exportExcelAsset()
    {
        $assets = AssetStatus::with(['warehouseMasterSite', 'category', 'lokasiAwal', 'lokasiTujuan', 'subcategory','pic'])->get();

        return $this->generateExcel($assets);
    }

    public function exportFilteredExcelAsset(Request $request)
    {
        // Validate request
        $request->validate([
            'filtered_data' => 'required|string',
        ]);

        // Decode the JSON string to get filtered data IDs
        $filteredData = json_decode($request->filtered_data, true);
        
        // Extract IDs from filtered data
        $filteredIds = array_map(function($item) {
            return $item['id'];
        }, $filteredData);
        
        // Query only the filtered assets
        $assets = AssetStatus::with(['warehouseMasterSite', 'category', 'lokasiAwal', 'lokasiTujuan', 'subcategory','pic'])
                    ->whereIn('id', $filteredIds)
                    ->get();

        return $this->generateExcel($assets);
    }

    /**
     * Generate Excel file from assets collection
     */
    private function generateExcel($assets)
    {
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
            'PIC',
            'Status Barang',
            'Notes',
            'Warehouse',
            'Dibuat Tanggal'
        ];

        // Data rows
        foreach ($assets as $index => $asset) {
            $data[] = [
                $index + 1,
                $asset->asset_code ?: '-',           
                $asset->brand,
                $asset->serial_number ?: '-',        
                $asset->lokasiAwal ? $asset->lokasiAwal->nama_lokasi : '',
                $asset->lokasiTujuan ? $asset->lokasiTujuan->nama_lokasi : '',
                $asset->category ? $asset->category->name : '',
                $asset->subcategory ? $asset->subcategory->name : '',
                $asset->tanggal_visit ? date('Y-m-d', strtotime($asset->tanggal_visit)) : '',  
                (is_object($asset->pic) && isset($asset->pic->name)) ? $asset->pic->name : '-', 
                ucfirst($asset->status_barang),
                $asset->notes ?: '-',                 
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
        foreach (range('A', 'N') as $column) {
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

        // Example data rows based on your sample
        $exampleData = [
            [
                '0311100004929', 'MONITOR DELL', '', '111', '100', 'Monitor', '', date('Y-m-d'), 'Oke', 'RUANG ADMIN'
            ],
            [
                '0311100004899', 'CPU DELL', '', '111', '100', 'CPU', '', date('Y-m-d'), 'Oke', ''
            ],
            [
                '', 'KEYBOARD', '', '111', '100', 'KEYBOARD', '', date('Y-m-d'), 'Oke', ''
            ],
            [
                '', 'MOUSE', '', '111', '100', 'MOUSE', '', date('Y-m-d'), 'Oke', ''
            ],
            [
                '0311200000395', 'MONITOR DELL', '', '111', '100', 'MONITOR', '', date('Y-m-d'), 'Oke', 'RUANG ADMIN'
            ]
        ];

        // Add the example data rows
        $row = 2;
        foreach ($exampleData as $rowData) {
            $sheet->fromArray([$rowData], NULL, "A{$row}");
            $row++;
        }

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

        // Add data validation for Status Barang column
        $validation = $sheet->getCell('I2')->getDataValidation();
        $validation->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST);
        $validation->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION);
        $validation->setAllowBlank(false);
        $validation->setShowInputMessage(true);
        $validation->setShowErrorMessage(true);
        $validation->setShowDropDown(true);
        $validation->setFormula1('"oke,rusak,perbaikan"');
        
        // Copy validation to all cells in the Status column
        for ($i = 3; $i <= 20; $i++) {
            $sheet->getCell("I{$i}")->setDataValidation(clone $validation);
        }
        
        // Set date format for the date column
        $dateStyle = [
            'numberFormat' => [
                'formatCode' => 'YYYY-MM-DD',
            ],
        ];
        $sheet->getStyle('H2:H20')->applyFromArray($dateStyle);

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
            'excel_file' => 'required|file|mimes:xlsx|max:10240', 
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

                    if (!empty($assetCode)) {
                        $existingAsset = AssetStatus::where('asset_code', $assetCode)->first();
                        if ($existingAsset) {
                            $errors[] = [
                                'row' => $rowNumber,
                                'message' => "Nomor Asset '{$assetCode}' sudah ada di database."
                            ];
                            continue;
                        }
                    }

                    // Find lokasi_awal_id by code (if provided)
                    $lokasiAwalId = null;
                    if (!empty($lokasiAwalCode)) {
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

                    // Find category_id by name (if provided)
                    $categoryId = null;
                    if (!empty($categoryName)) {
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

                    // Validate status_barang
                    $validStatuses = ['oke', 'rusak', 'perbaikan'];
                    $normalizedStatus = strtolower(trim($statusBarang));
                    
                    // Case-insensitive matching and additional status normalization
                    if (!empty($normalizedStatus)) {
                        // Map similar values to valid statuses
                        if (in_array($normalizedStatus, ['ok', 'baik', 'bagus', 'normal'])) {
                            $normalizedStatus = 'oke';
                        } elseif (in_array($normalizedStatus, ['repair', 'fixing', 'maintenance'])) {
                            $normalizedStatus = 'perbaikan';
                        } elseif (in_array($normalizedStatus, ['broken', 'damaged', 'bad'])) {
                            $normalizedStatus = 'rusak';
                        }
                        
                        // Final validation
                        if (!in_array($normalizedStatus, $validStatuses)) {
                            $errors[] = [
                                'row' => $rowNumber,
                                'message' => "Status Barang harus berupa: oke, rusak, atau perbaikan."
                            ];
                            continue;
                        }
                        
                        // Update the status with normalized value
                        $statusBarang = $normalizedStatus;
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
                    $asset->status_barang = !empty($statusBarang) ? $statusBarang : 'oke'; 
                    $asset->notes = $notes;
                    $asset->warehouse_master_site_id = $lokasiAwalId;
                    // Removed pic_id assignment to prevent errors
                    $asset->save();

                    $successCount++;
                }

                DB::commit();

                // Change message based on success count
                $message = $successCount > 0 ? 'Import selesai' : 'Import gagal';
                
                return response()->json([
                    'success' => $successCount,
                    'errors' => $errors,
                    'message' => $message
                ]);

            } catch (\Exception $e) {
                // kembalikan ke awal jika terjadi error
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

        // Pastikan warehouse_master_site_id diisi jika kosong
        if (empty($request->warehouse_master_site_id) && !empty($request->lokasi_awal_id)) {
            $request->merge(['warehouse_master_site_id' => $request->lokasi_awal_id]);
        }

        $data = $validator->validated();
        $data['pic'] = Auth::id();
        $asset = AssetStatus::create($data);

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