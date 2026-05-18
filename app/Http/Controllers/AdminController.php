<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function verifikasi()
    {
        return view('admin.verifikasi');
    }

    public function smeDatabase()
    {
        return view('admin.sme_database');
    }

    public function laporan()
    {
        return view('admin.laporan');
    }

    // Endpoint API untuk AJAX (Alpine.js)
    public function getStores(Request $request)
    {
        $query = \App\Models\Store::query();

        // Filter by category
        if ($request->filled('category') && $request->category !== 'Semua') {
            $query->where('category', $request->category);
        }

        // Search by name or NIB
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('nib', 'like', "%{$search}%");
            });
        }

        // Pagination (10 per page)
        $stores = $query->paginate(10);

        return response()->json($stores);
    }

    public function exportSmeCsv()
    {
        $stores = \App\Models\Store::all();
        
        $csvData = "Nama UMKM,Pemilik,Kategori,NIB,Deskripsi\n";
        
        foreach ($stores as $store) {
            // Escape commas and quotes to prevent CSV breaking
            $name = '"' . str_replace('"', '""', $store->name) . '"';
            $owner = '"' . str_replace('"', '""', $store->user->name ?? 'Unknown') . '"';
            $category = '"' . str_replace('"', '""', $store->category ?? '') . '"';
            $nib = '"' . str_replace('"', '""', $store->nib ?? '') . '"';
            $desc = '"' . str_replace('"', '""', $store->description ?? '') . '"';
            
            $csvData .= "{$name},{$owner},{$category},{$nib},{$desc}\n";
        }
        
        return response($csvData)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="sme_database_export.csv"');
    }
}
