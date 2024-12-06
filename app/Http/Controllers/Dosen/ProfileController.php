<?php

namespace App\Http\Controllers\Dosen;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function show()
    {
        // Ambil data dosen yang terhubung dengan user yang sedang login
        $dosen = Auth::user()->dosen;

        if (!$dosen) {
            return redirect()->route('dosen.dashboard')->withErrors([
                'message' => 'Data dosen tidak ditemukan.',
            ]);
        }

        return view('dosen.profile', compact('dosen'));
    }

    public function changePassword(Request $request)
    {
        // Validasi input
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
