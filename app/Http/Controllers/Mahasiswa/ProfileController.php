<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Models\Mahasiswa;

class ProfileController extends Controller
{
    public function show()
    {
        $mahasiswa = Mahasiswa::where('user_id', Auth::id())->first(); // Ambil data mahasiswa berdasarkan user_id

        if (!$mahasiswa) {
            return redirect()->route('mahasiswa.dashboard.index')->with([
                'message' => 'Data mahasiswa tidak ditemukan!',
                'alert-type' => 'danger'
            ]);
        }

        return view('mahasiswa.profile', compact('mahasiswa'));
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $user = Auth::user();

        // Periksa apakah password saat ini cocok
        if (!Hash::check($request->current_password, $user->password)) {
            return redirect()->back()->withErrors(['current_password' => 'Password saat ini salah.']);
        }

        // Perbarui password
        $user->update([
            'password' => Hash::make($request->new_password),
        ]);

        return redirect()->back()->with('success', 'Password berhasil diubah.');
    }
}
