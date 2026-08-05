<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    public function index()
    {
        // Mengambil semua data negara dan diurutkan sesuai abjad
        $countries = Country::orderBy('name', 'asc')->get();
        
        return response()->json([
            'success' => true,
            'data'    => $countries
        ]);
    }
}
