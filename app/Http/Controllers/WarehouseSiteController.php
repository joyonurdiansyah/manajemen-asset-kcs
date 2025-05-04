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
            $data = WarehouseMasterSite::select('id', 'kode', 'nama_lokasi', 'alamat')->get();

            return response()->json([
                'data' => $data
            ]);
        }

        return abort(404);
    }
    
    public function exportExcel()
    {
        $sites = WarehouseMasterSite::all();
        return response()->json($sites);
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
