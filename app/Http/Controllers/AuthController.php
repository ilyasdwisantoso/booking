<?php
// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\Role;
use App\Models\Mahasiswa;
use App\Models\Dosen;

class AuthController extends Controller
{
    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:mahasiswa,dosen',
        ]);

        try {
            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            Log::info('User created: ', ['user' => $user]);

            // Generate app credentials
            $user->generateAppCredentials();

            // Attach role to user
            $role = Role::where('title', $request->role)->firstOrFail(); // Make sure role exists
            $user->roles()->attach($role);

            // Handle specific role-related data creation
            if ($request->role === 'mahasiswa') {
                $request->validate([
                    'NIM' => 'required|digits:10|unique:mahasiswas,NIM',
                    'Nama' => 'required|string|max:255',
                    'tgl_lahir' => 'required|date',
                ]);

                $qr = mt_rand(1000000000, 9999999999);
                if ($this->qrCodeExists($qr)) {
                    $qr = mt_rand(1000000000, 99999999);
                }

                $mahasiswa = Mahasiswa::create([
                    'NIM' => $request->NIM,
                    'Nama' => $request->Nama,
                    'tgl_lahir' => $request->tgl_lahir,
                    'user_id' => $user->id,
                    'qr_code' => $qr,
                ]);

                Log::info('Mahasiswa created: ', ['mahasiswa' => $mahasiswa]);

            } elseif ($request->role === 'dosen') {
                $request->validate([
                    'nama_dosen' => 'required|string|max:255',
                    'nip' => 'required|string|max:255|unique:dosen,nip',
                    'no_tlp' => 'required|string|max:255',
                ]);

                $qr = mt_rand(1000000000, 9999999999);
                if ($this->qrCodeExists($qr)) {
                    $qr = mt_rand(1000000000, 99999999);
                }


                $dosen = Dosen::create([
                    'nama_dosen' => $request->nama_dosen,
                    'nip' => $request->nip,
                    'no_tlp' => $request->no_tlp,
                    'user_id' => $user->id,
                    'qr_code' => $qr
                ]);

                Log::info('Dosen created: ', ['dosen' => $dosen]);
            }

            return redirect()->route('login')->with('success', 'Registration successful');

        } catch (\Exception $e) {
            Log::error('Registration failed: ', ['error' => $e->getMessage()]);
            return back()->withErrors(['error' => 'Registration failed: ' . $e->getMessage()]);
        }
    }

    public function qrCodeExists($qr)
    {
        return Mahasiswa::whereQrCode($qr)->exists();
    }

    

    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if (Auth::attempt($request->only('email', 'password'))) {
            $request->session()->regenerate();

            // Redirect based on user role
            $user = Auth::user();
            if ($user->hasRole('admin')) {
                return redirect()->route('admin.dashboard.index');
            } elseif ($user->hasRole('dosen')) {
                return redirect()->route('dosen.dashboard.index');
            } elseif ($user->hasRole('mahasiswa')) {
                return redirect()->route('mahasiswa.dashboard.index');
            }

            return redirect()->intended('dashboard');
        }

    return back()->withInput($request->only('email'))
                ->withErrors([
                    'email' => 'Akun tidak tersedia di sistem kami.',
                ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
