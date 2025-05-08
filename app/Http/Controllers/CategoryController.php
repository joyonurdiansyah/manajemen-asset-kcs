<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{

    public function categoryHome()
    {
        return view('master.category');
    }

    /**
     * Get all categories as JSON
     */
    public function categoryGet()
    {
        $categories = Category::orderBy('id', 'desc')->get();
        
        return response()->json([
            'success' => true,
            'kategori_item' => $categories
        ]);
    }

    /**
     * Store a new category
     */
    public function categoryStore(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        $category = Category::create([
            'name' => $request->name
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil ditambahkan',
            'data' => $category
        ]);
    }

    /**
     * Get a specific category
     */
    public function categoryShow($id)
    {
        $category = Category::find($id);
        
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak ditemukan'
            ]);
        }

        return response()->json([
            'success' => true,
            'data' => $category
        ]);
    }

    /**
     * Update a category
     */
    public function categoryUpdate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255|unique:categories,name,'.$id
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ]);
        }

        $category = Category::find($id);
        
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak ditemukan'
            ]);
        }

        $category->update([
            'name' => $request->name
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil diperbarui',
            'data' => $category
        ]);
    }

    /**
     * Delete a category
     */
    public function categoryDestroy($id)
    {
        $category = Category::find($id);
        
        if (!$category) {
            return response()->json([
                'success' => false,
                'message' => 'Kategori tidak ditemukan'
            ]);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Kategori berhasil dihapus'
        ]);
    }

    public function getCategoryAssetStatus($id)
    {
        try {
            $category = Category::with(['assetStatuses' => function($query) {
                $query->with(['warehouseMasterSite', 'subcategory', 'lokasiAwal', 'lokasiTujuan']);
            }])->findOrFail($id);
            
            $assetStatuses = $category->assetStatuses->map(function($assetStatus) {
                return [
                    'asset_code' => $assetStatus->asset_code ?? '-',
                    'brand' => $assetStatus->brand ?? '-',
                    'serial_number' => $assetStatus->serial_number ?? '-',
                    'subcategory' => $assetStatus->subcategory ? $assetStatus->subcategory->name : '-',
                    'tanggal_visit' => $assetStatus->tanggal_visit ? date('d-m-Y', strtotime($assetStatus->tanggal_visit)) : '-',
                    'status_barang' => $assetStatus->status_barang ? ucfirst($assetStatus->status_barang) : '-',
                    'notes' => $assetStatus->notes ?? '-',
                ];
            });
            
            return response()->json([
                'success' => true,
                'data' => [
                    'category' => $category->name ?? '-',
                    'asset_statuses' => $assetStatuses
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mendapatkan data asset status: ' . $e->getMessage()
            ], 500);
        }
    }
}