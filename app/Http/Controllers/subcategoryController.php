<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class subcategoryController extends Controller
{
    public function subcategoryHome()
    {
        return view('master.sub-kategori');
    }
}
