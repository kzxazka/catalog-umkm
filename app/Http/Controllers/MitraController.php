<?php

namespace App\Http\Controllers;

use App\Mail\MitraApplicationSubmitted;
use App\Mail\MitraStatusUpdated;
use App\Models\MitraApplication;
use App\Models\Store;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MitraController extends Controller
{
    /** Form pendaftaran mitra */
    public function create()
    {
        $user = auth()->user();

        // Cek apakah sudah ada pengajuan
        $existing = MitraApplication::where('user_id', $user->id)->orderBy('_id', -1)->first();

        return view('mitra.register', compact('user', 'existing'));
    }

    /** Submit pendaftaran mitra */
    public function store(Request $request)
    {
        $user = auth()->user();

        // Cegah double submit
        if ($user->hasPendingMitraApplication()) {
            return back()->with('error', 'Anda sudah memiliki pengajuan yang sedang diproses.');
        }

        $request->validate([
            'business_name'     => 'required|string|max:255',
            'business_category' => 'required|string|in:Fashion,Food & Beverage,Healthy Product,Other',
            'business_address'  => 'required|string|max:500',
            'city'              => 'required|string|max:100',
            'district'          => 'required|string|max:100',
            'description'       => 'required|string|max:1000',
            'whatsapp'          => 'required|string|max:20',
            'instagram'         => 'nullable|string|max:100',
        ]);

        // Enforce Bandar Lampung city
        $city = strtolower(trim($request->city));
        if ($city !== 'bandar lampung' && $city !== 'kota bandar lampung') {
            return back()->withInput()->withErrors(['city' => 'Pendaftaran kemitraan hanya dibuka untuk UMKM yang berdomisili di Bandar Lampung.']);
        }

        $application = MitraApplication::create([
            'user_id'           => $user->id,
            'business_name'     => $request->business_name,
            'business_category' => $request->business_category,
            'business_address'  => $request->business_address,
            'city'              => $request->city,
            'district'          => $request->district,
            'description'       => $request->description,
            'whatsapp'          => $request->whatsapp,
            'instagram'         => $request->instagram,
            'ktp_path'          => null,
            'nib_path'          => null,
            'status'            => 'pending',
        ]);

        // Kirim email notifikasi ke admin (Dinonaktifkan sementara)
        // $adminEmail = config('mail.admin_email', env('ADMIN_EMAIL', 'admin@disperdagkota.go.id'));
        // try {
        //     Mail::to($adminEmail)->send(new MitraApplicationSubmitted($application));
        // } catch (\Exception $e) {
        //     // Jangan block proses jika email gagal
        // }

        return redirect()->route('mitra.status')
            ->with('success', 'Pengajuan berhasil dikirim! Tim Dinas Perdagangan akan meninjau dalam 3-5 hari kerja.');
    }

    /** Halaman status pengajuan mitra */
    public function status()
    {
        $user        = auth()->user();
        $application = MitraApplication::where('user_id', $user->id)->orderBy('_id', -1)->first();

        return view('mitra.status', compact('user', 'application'));
    }

    /** Admin: Approve pengajuan mitra */
    public function approve(Request $request, $id)
    {
        $application = MitraApplication::findOrFail($id);

        $application->update([
            'status'      => 'approved',
            'reviewed_by' => auth()->id(),
            'reviewed_at' => now(),
        ]);

        // Upgrade buyer → owner + buat toko jika belum ada
        $buyer = User::find($application->user_id);
        if ($buyer) {
            // Ubah role ke owner
            $buyer->role = 'owner';
            $buyer->save();

            // Buat Store jika belum ada
            if (!$buyer->store) {
                $baseSlug = Str::slug($application->business_name);
                $slug     = $baseSlug;
                $i        = 1;
                while (Store::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $i++;
                }

                Store::create([
                    'user_id'     => $buyer->id,
                    'name'        => $application->business_name,
                    'slug'        => $slug,
                    'description' => $application->description,
                    'category'    => $application->business_category,
                    'city'        => $application->city,
                    'district'    => $application->district,
                    'whatsapp'    => $application->whatsapp,
                    'instagram'   => $application->instagram ?? '',
                ]);
            }

            // Kirim email notifikasi ke buyer (Dinonaktifkan sementara)
            // try {
            //     Mail::to($buyer->email)->send(new MitraStatusUpdated($application, 'approved'));
            // } catch (\Exception $e) {}
        }

        return back()->with('success', 'Pengajuan mitra telah disetujui, akun diupgrade ke Owner, dan toko dibuat otomatis.');
    }

    /** Admin: Reject pengajuan mitra */
    public function reject(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:500']);

        $application = MitraApplication::findOrFail($id);

        $application->update([
            'status'           => 'rejected',
            'reviewed_by'      => auth()->id(),
            'reviewed_at'      => now(),
            'rejection_reason' => $request->reason,
        ]);

        // Kirim email ke buyer (Dinonaktifkan sementara)
        // $buyer = User::find($application->user_id);
        // if ($buyer) {
        //     try {
        //         Mail::to($buyer->email)->send(new MitraStatusUpdated($application, 'rejected'));
        //     } catch (\Exception $e) {}
        // }

        return back()->with('success', 'Pengajuan mitra telah ditolak dan notifikasi dikirim ke pemohon.');
    }

    /** Admin: Request revision on pengajuan mitra */
    public function revision(Request $request, $id)
    {
        $request->validate(['reason' => 'required|string|max:500']);

        $application = MitraApplication::findOrFail($id);

        $application->update([
            'status'           => 'revision',
            'reviewed_by'      => auth()->id(),
            'reviewed_at'      => now(),
            'rejection_reason' => $request->reason,
        ]);

        // Kirim email ke buyer (Dinonaktifkan sementara)
        // $buyer = User::find($application->user_id);
        // if ($buyer) {
        //     try {
        //         Mail::to($buyer->email)->send(new MitraStatusUpdated($application, 'revision'));
        //     } catch (\Exception $e) {}
        // }

        return back()->with('success', 'Permintaan revisi pengajuan mitra telah dikirim ke pemohon.');
    }

    /** Admin: Daftar semua pengajuan mitra */
    public function adminList(Request $request)
    {
        $query = MitraApplication::query();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $applications = $query->orderBy('_id', -1)->paginate(20)->withQueryString();
        $pendingCount = MitraApplication::where('status', 'pending')->count();

        return view('admin.mitra', compact('applications', 'pendingCount'));
    }
}
