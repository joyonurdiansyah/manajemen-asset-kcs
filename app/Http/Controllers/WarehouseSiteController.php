<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WarehouseMasterSite;
use DataTables;

class WarehouseSiteController extends Controller
{
    public function siteHome()
    {
        return view('master.site');
    }

    public function getSiteData(Request $request)
    {
        if ($request->ajax()) {
            $query = WarehouseMasterSite::select('id', 'kode', 'nama_lokasi', 'alamat');
            
            // Apply filters if they exist
            if ($request->has('kode') && !empty($request->kode)) {
                // Handle multiple selections
                $query->whereIn('kode', $request->kode);
            }
            
            if ($request->has('nama_lokasi') && !empty($request->nama_lokasi)) {
                // Handle multiple selections
                $query->whereIn('nama_lokasi', $request->nama_lokasi);
            }
            
            if ($request->has('alamat') && !empty($request->alamat)) {
                // Handle multiple selections
                $query->whereIn('alamat', $request->alamat);
            }
            
            $data = $query->get();
    
            return response()->json([
                'data' => $data
            ]);
        }
    
        return abort(404);
    }
    
    public function exportExcel(Request $request)
    {
        // Apply the same filters as in the DataTable for consistency
        $query = WarehouseMasterSite::select('id', 'kode', 'nama_lokasi', 'alamat');
        
        if ($request->has('kode') && !empty($request->kode)) {
            $query->where('kode', 'like', '%' . $request->kode . '%');
        }
        
        if ($request->has('nama_lokasi') && !empty($request->nama_lokasi)) {
            $query->where('nama_lokasi', 'like', '%' . $request->nama_lokasi . '%');
        }
        
        if ($request->has('alamat') && !empty($request->alamat)) {
            $query->where('alamat', 'like', '%' . $request->alamat . '%');
        }
        
        $sites = $query->get();
        
        // Format the data for Excel export
        $exportData = [];
        $no = 1;
        
        foreach ($sites as $site) {
            $exportData[] = [
                'No' => $no++,
                'Kode Warehouse' => $site->kode,
                'Nama Lokasi' => $site->nama_lokasi,
                'Alamat' => $site->alamat
            ];
        }
        
        return response()->json($exportData);
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'kode' => 'required|unique:warehouse_master_sites,kode',
            'nama_lokasi' => 'required',
            'alamat' => 'required',
        ]);
        
        $site = WarehouseMasterSite::create([
            'kode' => $request->kode,
            'nama_lokasi' => $request->nama_lokasi,
            'alamat' => $request->alamat,
        ]);
        
        return response()->json(['success' => true, 'message' => 'Data site berhasil ditambahkan']);
    }
    
    public function edit($id)
    {
        $site = WarehouseMasterSite::find($id);
        return response()->json($site);
    }
    
    public function update(Request $request, $id)
    {
        $request->validate([
            'kode' => 'required|unique:warehouse_master_sites,kode,'.$id,
            'nama_lokasi' => 'required',
            'alamat' => 'required',
        ]);
        
        $site = WarehouseMasterSite::find($id);
        $site->update([
            'kode' => $request->kode,
            'nama_lokasi' => $request->nama_lokasi,
            'alamat' => $request->alamat,
        ]);
        
        return response()->json(['success' => true, 'message' => 'Data site berhasil diperbarui']);
    }
    
    public function destroy($id)
    {
        $site = WarehouseMasterSite::find($id);
        $site->delete();
        
        return response()->json(['success' => true, 'message' => 'Data site berhasil dihapus']);
    }
}