<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Subcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class SubcategoryController extends Controller
{
    /**
     * Display the subcategory management page
     */
    public function subcategoryHome()
    {
        return view('master.sub-kategori');
    }

    public function getList()
    {
        $categories = Category::select('id', 'name')
            ->orderBy('name', 'asc')
            ->get();
        
        return response()->json([
            'success' => true,
            'data' => $categories
        ]);
    }

    /**
     * Get subcategory data for DataTables
     */
    public function getData()
    {
        $subcategories = Subcategory::with('category')->get();

        $data = [];
        foreach ($subcategories as $index => $subcategory) {
            $actionBtn = '
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-info btn-sm detail-btn" data-id="'.$subcategory->id.'">
                        <i class="fas fa-eye"></i>
                    </button>
                    <button type="button" class="btn btn-primary btn-sm edit-btn" data-id="'.$subcategory->id.'">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button type="button" class="btn btn-danger btn-sm delete-btn" data-id="'.$subcategory->id.'">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            ';
            
            $data[] = [
                'DT_RowIndex' => $index + 1,
                'category_code' => $subcategory->category->code ?? '-',
                'category_name' => $subcategory->category->name,
                'name' => $subcategory->name,
                'action' => $actionBtn
            ];
        }
        
        return response()->json([
            'data' => $data
        ]);
    }

    /**
     * Store a newly created subcategory
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:subcategories,name',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $subcategory = Subcategory::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sub Kategori berhasil ditambahkan!'
        ]);
    }

    /**
     * Display the specified subcategory details
     */
    public function show($id)
    {
        $subcategory = Subcategory::with(['category', 'assetStatuses'])->findOrFail($id);
        
        return response()->json($subcategory);
    }

    /**
     * Show the form for editing the specified subcategory
     */
    public function edit($id)
    {
        $subcategory = Subcategory::findOrFail($id);
        
        return response()->json($subcategory);
    }

    /**
     * Update the specified subcategory
     */
    public function update(Request $request, $id)
    {
        $subcategory = Subcategory::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255|unique:subcategories,name,' . $id,
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $subcategory->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Sub Kategori berhasil diperbarui!'
        ]);
    }

    /**
     * Remove the specified subcategory
     */
    public function destroy($id)
    {
        $subcategory = Subcategory::findOrFail($id);
        
        // Check if subcategory has related asset statuses
        if($subcategory->assetStatuses()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Sub Kategori tidak dapat dihapus karena masih memiliki status aset terkait!'
            ], 422);
        }
        
        $subcategory->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Sub Kategori berhasil dihapus!'
        ]);
    }

    /**
     * Export subcategories to Excel
     */
    public function export()
    {
        $subcategories = Subcategory::with('category')
            ->orderBy('id', 'asc')
            ->get();
        
        $data = [];
        foreach($subcategories as $index => $subcategory) {
            $data[] = [
                'No' => $index + 1,
                'Kode Kategori' => $subcategory->category->code ?? '-',
                'Nama Kategori' => $subcategory->category->name,
                'Nama Sub Kategori' => $subcategory->name
            ];
        }
        
        return response()->json([
            'success' => true,
            'data' => $data
        ]);
    }
}