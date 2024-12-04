<?php

namespace App\Http\Controllers\Admin;

use App\Models\Mahasiswa;
use App\Models\Prodi;
use App\Models\Matakuliah;
use App\Models\Dosen;
use App\Models\Ruangan;
use App\Models\Booking;
use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\Admin\StoreBookingRequest;
use App\Http\Requests\Admin\UpdateBookingRequest;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
{
    abort_if(Gate::denies('booking_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    $today = Carbon::now();
    $todayDayOfWeek = $today->format('w');

    // Ambil jadwal untuk hari ini
    $todayBookings = Booking::where('day_of_week', $todayDayOfWeek)->get();

    // Ambil jadwal untuk hari mendatang
    $upcomingBookings = Booking::where('day_of_week', '!=', $todayDayOfWeek)->get();

    return view('admin.booking.index', compact('todayBookings', 'upcomingBookings'));
}


    public function updateClassStatus()
{
    $currentDateTime = Carbon::now();
    $dayOfWeek = $currentDateTime->format('w');
    $currentTime = $currentDateTime->format('H:i:s');

    $bookings = Booking::all();

    foreach ($bookings as $booking) {
        if ($booking->day_of_week == $dayOfWeek &&
            $booking->start_time <= $currentTime &&
            $booking->end_time >= $currentTime) {
            $booking->status = 'kelas dimulai';
            $booking->room_status = 'open'; // Ubah status ruangan menjadi "open"
        } else {
            $booking->status = 'kelas belum dimulai';
            $booking->room_status = 'closed'; // Ubah status ruangan menjadi "closed"
        }
        $booking->save();
    }

    return response()->json(['success' => 'Class statuses and room statuses updated']);
}


    public function getClassStatuses()
{
    try {
        \Log::info('Fetching class statuses');
        $bookings = Booking::all();
        \Log::info('Bookings fetched successfully', ['bookings' => $bookings]);
        return response()->json(['bookings' => $bookings]);
    } catch (\Exception $e) {
        \Log::error('Error fetching class statuses: ' . $e->getMessage());
        return response()->json(['error' => 'Something went wrong'], 500);
    }
}


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $user = auth()->user();
    
        // Ambil data yang sama dengan admin
        $prodi = Prodi::get()->pluck('nama_prodi', 'id');
        $matakuliah = Matakuliah::get()->pluck('Nama_MK', 'id');
        $ruangan = Ruangan::get()->pluck('no_ruangan', 'id');
    
        if ($user->isAdmin()) {
            // Admin: dapat melihat semua data mahasiswa dan dosen
            $mahasiswas = Mahasiswa::get()->pluck('Nama', 'NIM');
            $dosen = Dosen::get()->pluck('nama_dosen', 'id');
    
            return view('admin.booking.create', compact('mahasiswas', 'prodi', 'matakuliah', 'dosen', 'ruangan'));
        } elseif ($user->isDosen()) {
            // Dosen: hanya melihat data dosen yang login dan semua data mahasiswa
            $mahasiswas = Mahasiswa::get()->pluck('Nama', 'NIM');
            
            // Ambil data dosen yang login
            $dosen = $user->dosen; // Mengambil relasi dosen dari user
    
            return view('dosen.courses.create', compact('mahasiswas', 'prodi', 'matakuliah', 'dosen', 'ruangan'));
        }
    
        abort(403, 'Unauthorized action.');
    }
    


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBookingRequest $request)
{
    $user = auth()->user();
    $start_time = $request->input('start_time');
    $end_time = $request->input('end_time');
    $day_of_week = $request->input('day_of_week');
    $ruangan_id = $request->input('ruangan_id');

    // Cek konflik jadwal di ruangan yang sama
    $conflict = Booking::where('ruangan_id', $ruangan_id)
        ->where('day_of_week', $day_of_week)
        ->where(function($query) use ($start_time, $end_time) {
            $query->where('start_time', '<', $end_time)
                  ->where('end_time', '>', $start_time);
        })->exists();

    if ($conflict) {
        $routeRedirect = $user->isDosen() ? 'dosen.courses.create' : 'admin.booking.create';
        return redirect()->route($routeRedirect)->with([
            'message' => 'Jadwal bentrok dengan waktu lain di ruangan ini. Silakan pilih waktu atau ruangan lain.',
            'alert-type' => 'danger'
        ])->withInput($request->all());
    }

    // Generate token unik untuk setiap booking
    $token = mt_rand(100000, 999999);
    while ($this->codeTokenExists($token)) {
        $token = mt_rand(100000, 999999);
    }

    // Menyimpan booking berdasarkan peran user
    $data = $request->validated() + ['code_token' => $token];

    if ($user->isDosen()) {
        // Set dosen_id untuk dosen yang login
        $data['dosen_id'] = $user->dosen->id;
    } else if ($user->isAdmin()) {
        // Admin dapat memilih dosen_id dari form
        $data['dosen_id'] = $request->input('dosen_id');
    }

    $booking = Booking::create($data);

    // Dosen dan admin dapat menambahkan mahasiswa ke booking
    if ($request->has('mahasiswas')) {
        $booking->mahasiswas()->sync($request->input('mahasiswas'));
    }

    // Redirect sesuai role
    $routeRedirect = $user->isDosen() ? 'dosen.courses.index' : 'admin.booking.index';
    return redirect()->route($routeRedirect)->with([
        'message' => 'Jadwal berhasil dibuat!',
        'alert-type' => 'success'
    ]);
}


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort_if(Gate::denies('booking_view'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        $booking = Booking::findOrFail($id);
        return view('admin.booking.show', compact('booking'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  Booking $booking
     * @return \Illuminate\Http\Response
     */
    public function edit(Booking $booking)
    {
        abort_if(Gate::denies('booking_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
    
        $mahasiswas = Mahasiswa::get()->pluck('Nama', 'NIM');
        $prodi = Prodi::get()->pluck('nama_prodi', 'id');
        $matakuliah = Matakuliah::get()->pluck('Nama_MK', 'id');
        $dosen = Dosen::get()->pluck('nama_dosen', 'id');
        $ruangan = Ruangan::get()->pluck('no_ruangan', 'id');
    
        // Pastikan data `Booking` terkait mahasiswa di-load
        $booking->load('mahasiswas');
    
        return view('admin.booking.edit', compact('booking', 'mahasiswas', 'prodi', 'matakuliah', 'dosen', 'ruangan'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBookingRequest $request, Booking $booking)
{
    abort_if(Gate::denies('booking_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

    $start_time = $request->input('start_time');
    $end_time = $request->input('end_time');
    $day_of_week = $request->input('day_of_week');
    $ruangan_id = $request->input('ruangan_id');

    // Cek konflik jadwal di ruangan yang sama
    $conflict = Booking::where('ruangan_id', $ruangan_id)
        ->where('day_of_week', $day_of_week)
        ->where('id', '!=', $booking->id) // Jangan cek konflik dengan dirinya sendiri
        ->where(function ($query) use ($start_time, $end_time) {
            $query->where('start_time', '<', $end_time)
                  ->where('end_time', '>', $start_time);
        })
        ->exists();

    if ($conflict) {
        return redirect()->route('admin.booking.edit', $booking->id)
            ->with([
                'message' => 'Jadwal bentrok dengan waktu lain di ruangan ini. Silakan pilih waktu atau ruangan lain.',
                'alert-type' => 'danger'
            ])
            ->withInput($request->all());
    }

    // Perbarui data booking
    $data = $request->validated(); // Validasi request menggunakan UpdateBookingRequest

    // Jika mahasiswa terkait diubah
    if ($request->has('mahasiswas')) {
        $booking->mahasiswas()->sync($request->input('mahasiswas')); // Sinkronisasi mahasiswa
    }

    // Perbarui data utama booking
    $booking->update($data);

    return redirect()->route('admin.booking.index')->with([
        'message' => 'Jadwal berhasil diperbarui!',
        'alert-type' => 'success'
    ]);
}

    public function codeTokenExists($token)
    {
        return Booking::whereCodeToken($token)->exists();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Booking $booking
     * @return \Illuminate\Http\Response
     */
    public function destroy(Booking $booking)
    {
        abort_if(Gate::denies('booking_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $booking->delete();

        return redirect()->route('admin.booking.index')->with([
            'message' => 'successfully deleted!',
            'alert-type' => 'danger'
        ]);
    }

    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('booking_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Booking::whereIn('id', request('ids'))->delete();

        return response()->noContent();
    }

    public function generate($id)
    {
        $booking = Booking::findOrFail($id);
        $qrcode = QrCode::size(400)->generate($booking->Kode_Kelas);
        return view('qrcode', compact('qrcode'));
    }

    public function editkelasDosen(Booking $booking)
{
    // Validasi bahwa dosen yang login adalah dosen yang memiliki booking ini
    if (auth()->user()->dosen->id !== $booking->dosen_id) {
        abort(403, 'Unauthorized action.');
    }

    // Ambil data yang diperlukan untuk form
    $mahasiswas = Mahasiswa::get()->pluck('Nama', 'NIM');
    $prodi = Prodi::get()->pluck('nama_prodi', 'id');
    $matakuliah = Matakuliah::get()->pluck('Nama_MK', 'id');
    $ruangan = Ruangan::get()->pluck('no_ruangan', 'id');

    // Pastikan mahasiswa yang terkait di-load
    $booking->load('mahasiswas');

    return view('dosen.courses.edit', compact('booking', 'mahasiswas', 'prodi', 'matakuliah', 'ruangan'));

}

public function updatekelasDosen(Request $request, Booking $booking)
{
    \Log::info('User mencoba mengupdate Booking:', [
        'user_id' => auth()->id(),
        'dosen_id' => auth()->user()->dosen->id,
        'booking_dosen_id' => $booking->dosen_id,
    ]);

    if (auth()->user()->dosen->id !== $booking->dosen_id) {
        \Log::warning('Unauthorized action detected:', ['user_id' => auth()->id()]);
        abort(403, 'Unauthorized action.');
    }

    \Log::info('Data yang diterima sebelum validasi:', $request->all());

    try {
        // Normalisasi waktu ke format H:i:s jika perlu
        $normalizedData = $request->all();
        $normalizedData['start_time'] = $this->normalizeTimeFormat($request->start_time);
        $normalizedData['end_time'] = $this->normalizeTimeFormat($request->end_time);

        \Log::info('Data setelah normalisasi waktu:', $normalizedData);

        // Validasi input
        $validatedData = \Validator::make($normalizedData, [
            'Kode_Kelas' => 'required|string|max:255',
            'prodi_id' => 'required|exists:prodi,id',
            'matakuliah_id' => 'required|exists:matakuliah,id',
            'ruangan_id' => 'required|exists:ruangan,id',
            'day_of_week' => 'required|integer|between:0,6',
            'start_time' => 'required|date_format:H:i:s',
            'end_time' => 'required|date_format:H:i:s|after:start_time',
            'mahasiswas' => 'nullable|array',
            'mahasiswas.*' => 'exists:mahasiswas,NIM',
        ])->validate();

        \Log::info('Data yang telah divalidasi:', $validatedData);

        // Cek konflik jadwal di ruangan yang sama
        $conflict = Booking::where('ruangan_id', $validatedData['ruangan_id'])
            ->where('day_of_week', $validatedData['day_of_week'])
            ->where('id', '!=', $booking->id)
            ->where(function ($query) use ($validatedData) {
                $query->where('start_time', '<', $validatedData['end_time'])
                      ->where('end_time', '>', $validatedData['start_time']);
            })
            ->exists();

        if ($conflict) {
            \Log::warning('Konflik ditemukan saat update:', [
                'ruangan_id' => $validatedData['ruangan_id'],
                'day_of_week' => $validatedData['day_of_week'],
                'start_time' => $validatedData['start_time'],
                'end_time' => $validatedData['end_time'],
            ]);

            return redirect()->route('dosen.courses.edit', $booking->id)
                ->with([
                    'message' => 'Jadwal bentrok dengan waktu lain di ruangan ini. Silakan pilih waktu atau ruangan lain.',
                    'alert-type' => 'danger',
                ])
                ->withInput($request->all());
        }

        \Log::info('Booking sebelum diupdate:', $booking->toArray());

        // Update data utama
        $booking->update($validatedData);

        \Log::info('Booking setelah diupdate:', $booking->fresh()->toArray());

        if ($request->has('mahasiswas')) {
            \Log::info('Sinkronisasi mahasiswa:', $validatedData['mahasiswas']);
            $booking->mahasiswas()->sync($validatedData['mahasiswas']);
        }

        \Log::info('Proses update berhasil:', [
            'booking_id' => $booking->id,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('dosen.courses.index')->with([
            'message' => 'Jadwal berhasil diperbarui!',
            'alert-type' => 'success',
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        \Log::error('Kesalahan validasi terjadi:', [
            'errors' => $e->errors(),
            'input' => $request->all(),
        ]);

        return redirect()->back()->withErrors($e->errors())->withInput();
    }
}

/**
 * Normalisasi waktu ke format H:i:s.
 */
private function normalizeTimeFormat($time)
{
    if (preg_match('/^\d{2}:\d{2}$/', $time)) {
        return $time . ':00';
    }

    return $time;
}



public function hapuskelasDosen(Booking $booking)
{
    // Pastikan dosen yang login adalah pemilik kelas
    if (auth()->user()->dosen->id !== $booking->dosen_id) {
        abort(403, 'Unauthorized action.');
    }

    // Hapus kelas
    $booking->delete();

    return redirect()->route('dosen.courses.index')->with([
        'message' => 'Kelas berhasil dihapus!',
        'alert-type' => 'success'
    ]);
}

}
