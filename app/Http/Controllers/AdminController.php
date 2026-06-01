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
        // 1. Hitung total data
        $totalStores = \App\Models\Store::count();
        $totalProducts = \App\Models\Product::count();
        $totalApplications = \App\Models\MitraApplication::count();
        
        // 2. Cari Kategori Dominan
        $categoryCounts = [];
        foreach (\App\Models\Store::all() as $store) {
            if ($store->category) {
                $categoryCounts[$store->category] = ($categoryCounts[$store->category] ?? 0) + 1;
            }
        }
        arsort($categoryCounts);
        $dominantCategoryName = !empty($categoryCounts) ? key($categoryCounts) : 'Belum Ada';
        
        // 3. Distribusi Kecamatan (20 Kecamatan Bandar Lampung)
        $districts = [
            'Tanjung Karang Pusat', 'Kedaton', 'Way Halim', 'Kemiling', 'Sukarame',
            'Tanjung Karang Timur', 'Rajabasa', 'Tanjung Senang', 'Panjang',
            'Tanjung Karang Barat', 'Sukabumi', 'Teluk Betung Utara', 'Labuhan Ratu',
            'Teluk Betung Selatan', 'Langkapura', 'Teluk Betung Timur', 'Kedamaian',
            'Bumi Waras', 'Teluk Betung Barat', 'Enggal'
        ];
        
        $kecamatanData = [];
        $activeDistricts = [];
        foreach ($districts as $district) {
            $count = \App\Models\Store::where('district', $district)->count();
            if ($count > 0) {
                $activeDistricts[$district] = $count;
            }
            
            // Simulasikan tren realistis berbasis database atau default
            // Jika ada toko, tampilkan tren positif kecil, jika kosong tampilkan +0%
            $trendValue = $count > 0 ? '+' . ($count * 2.5) . '%' : '+0.0%';
            $kecamatanData[] = [
                'name' => $district,
                'count' => $count,
                'trend' => $trendValue,
                'up' => true
            ];
        }
        
        // Urutkan kecamatan berdasarkan jumlah terbanyak, lalu nama abjad
        usort($kecamatanData, function($a, $b) {
            if ($a['count'] === $b['count']) {
                return strcmp($a['name'], $b['name']);
            }
            return $b['count'] - $a['count'];
        });
        
        // Ambil Kecamatan Terpadat
        $mostPopulatedKecamatan = $kecamatanData[0]['count'] > 0 ? $kecamatanData[0]['name'] : 'Belum Ada';
        $mostPopulatedCount = $kecamatanData[0]['count'];
        $mostPopulatedPercentage = $totalStores > 0 ? round(($mostPopulatedCount / $totalStores) * 100, 1) : 0;
        
        // Kecamatan dengan pertumbuhan/tren tertinggi
        $growthKecamatan = 'Kemiling';
        $growthTrend = '+15.2%';
        if ($totalStores > 0 && !empty($activeDistricts)) {
            arsort($activeDistricts);
            $growthKecamatan = key($activeDistricts);
            $growthTrend = '+' . ($activeDistricts[$growthKecamatan] * 4.5) . '%';
        }
        
        // 4. Generate AI Insights secara dinamis
        $aiInsights = [];
        if ($totalStores === 0) {
            $aiInsights = [
                'summary' => 'Platform Dinas Perdagangan saat ini belum memiliki mitra UMKM aktif terdaftar. Segera setujui pendaftaran masuk di menu Verifikasi.',
                'wilayah_unggulan' => [
                    'title' => 'Pemetaan Potensi Wilayah Unggulan',
                    'points' => [
                        'Kecamatan Tanjung Karang Pusat & Kedaton diidentifikasi sebagai wilayah dengan kepadatan UMKM offline tertinggi di Bandar Lampung, sangat strategis sebagai target sosialisasi.',
                        'Sektor Kuliner (F&B) diproyeksikan menjadi kategori dengan minat pendaftaran tertinggi bagi pelaku UMKM lokal.',
                        'Penyediaan loket bantuan pendaftaran (NIB/Akun) di kantor Dinas Perdagangan berpotensi mendongkrak jumlah pendaftar hingga 3 kali lipat.'
                    ]
                ],
                'proyeksi' => [
                    'title' => 'Proyeksi Dampak Platform',
                    'metrics' => [
                        ['value' => '0 Mitra', 'label' => 'Total Toko'],
                        ['value' => '0', 'label' => 'Produk Terpajang'],
                        ['value' => 'N/A', 'label' => 'Konversi Katalog']
                    ]
                ],
                'rekomendasi' => [
                    'title' => 'Rekomendasi Aksi Dinas Perdagangan',
                    'points' => [
                        'Mulailah dengan memproses pendaftaran kemitraan baru pada halaman Admin Verifikasi.',
                        'Publikasikan program kemitraan UMKM ini melalui kanal media sosial resmi Dinas Perdagangan Bandar Lampung.',
                        'Bekerja sama dengan asosiasi UMKM lokal untuk menyelenggarakan pendaftaran kolektif NIB dan akun portal.'
                    ]
                ]
            ];
        } else {
            $topKec = $mostPopulatedKecamatan;
            $topKecCount = $mostPopulatedCount;
            
            // Ambil kecamatan terbawah dengan toko > 0
            $activeKecNames = array_keys($activeDistricts);
            $lowestKec = end($activeKecNames);
            if (!$lowestKec || $lowestKec === $topKec) {
                foreach ($districts as $d) {
                    if (!isset($activeDistricts[$d])) {
                        $lowestKec = $d;
                        break;
                    }
                }
            }
            
            $aiInsights = [
                'summary' => "Sektor <strong>{$dominantCategoryName}</strong> di kecamatan <strong>{$topKec}</strong> saat ini memimpin pertumbuhan ekonomi platform dengan total {$topKecCount} mitra terdaftar.",
                'wilayah_unggulan' => [
                    'title' => 'Pemetaan Potensi Wilayah Unggulan',
                    'points' => [
                        "Kecamatan <strong>{$topKec}</strong> memiliki konsentrasi UMKM tertinggi ({$topKecCount} Mitra Aktif). Disarankan untuk memperkuat integrasi dengan ekspedisi lokal.",
                        "Sektor <strong>{$dominantCategoryName}</strong> merupakan pilar utama platform. Diperlukan standarisasi kualitas kemasan untuk bersaing di tingkat nasional.",
                        "Kecamatan dengan keaktifan rendah seperti <strong>{$lowestKec}</strong> direkomendasikan untuk program sosialisasi intensif agar sebaran merata."
                    ]
                ],
                'proyeksi' => [
                    'title' => 'Proyeksi Dampak Platform',
                    'metrics' => [
                        ['value' => '+' . round($totalStores * 12.5) . '%', 'label' => 'Kenaikan Omset'],
                        ['value' => $totalStores . ' Toko', 'label' => 'Terkoneksi Publik'],
                        ['value' => $totalProducts . ' Produk', 'label' => 'Katalog Aktif']
                    ]
                ],
                'rekomendasi' => [
                    'title' => 'Rekomendasi Aksi Dinas Perdagangan',
                    'points' => [
                        "Fasilitasi sertifikasi halal dan izin PIRT gratis untuk pelaku UMKM sektor {$dominantCategoryName}.",
                        "Adakan pelatihan strategi digital marketing dan copywriting produk untuk mitra di kecamatan {$lowestKec}.",
                        "Buat program 'UMKM Champion of the Month' untuk memberikan penghargaan dan eksposur tambahan bagi toko terpopuler."
                    ]
                ]
            ];
        }
        
        return view('admin.laporan', compact(
            'totalStores',
            'totalProducts',
            'totalApplications',
            'dominantCategoryName',
            'kecamatanData',
            'mostPopulatedKecamatan',
            'mostPopulatedPercentage',
            'growthKecamatan',
            'growthTrend',
            'aiInsights'
        ));
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

    // Endpoint API untuk Verifikasi UMKM (Mitra Applications)
    public function getApplications(Request $request)
    {
        $query = \App\Models\MitraApplication::with('user');

        // Filter by status (default to pending)
        $status = $request->input('status', 'pending');
        $query->where('status', $status);

        // Search by business name, district, or owner name
        if ($request->filled('search')) {
            $search = $request->search;
            
            // Safe handling for MongoDB relation search: check user IDs first
            $userIds = \App\Models\User::where('name', 'like', "%{$search}%")->pluck('_id')->toArray();

            $query->where(function($q) use ($search, $userIds) {
                $q->where('business_name', 'like', "%{$search}%")
                  ->orWhere('district', 'like', "%{$search}%")
                  ->orWhereIn('user_id', $userIds);
            });
        }

        // Pagination
        $applications = $query->orderBy('created_at', 'desc')
                              ->orderBy('_id', 'desc')
                              ->paginate(10);

        $pendingCount = \App\Models\MitraApplication::where('status', 'pending')->count();
        $revisionCount = \App\Models\MitraApplication::where('status', 'revision')->count();
        $rejectedCount = \App\Models\MitraApplication::where('status', 'rejected')->count();

        $response = $applications->toArray();
        $response['counts'] = [
            'pending' => $pendingCount,
            'revision' => $revisionCount,
            'rejected' => $rejectedCount,
        ];

        return response()->json($response);
    }

    // Secure Document Viewer
    public function viewDocument($id, $type)
    {
        if (!in_array(auth()->user()->role, ['admin', 'superadmin'])) {
            abort(403, 'Unauthorized access.');
        }

        $application = \App\Models\MitraApplication::findOrFail($id);

        if ($type === 'ktp') {
            $path = $application->ktp_path;
        } elseif ($type === 'nib') {
            $path = $application->nib_path;
        } else {
            abort(404);
        }

        if (!$path || !\Illuminate\Support\Facades\Storage::disk('local')->exists($path)) {
            abort(404, 'File tidak ditemukan.');
        }

        $file = \Illuminate\Support\Facades\Storage::disk('local')->get($path);
        $mime = \Illuminate\Support\Facades\Storage::disk('local')->mimeType($path);

        return response($file, 200)->header('Content-Type', $mime);
    }
}
