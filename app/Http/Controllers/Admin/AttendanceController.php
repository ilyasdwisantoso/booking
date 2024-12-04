<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Mahasiswa;
use App\Models\Attendance;
use App\Models\QRCode;
use App\Models\Booking;
use Carbon\Carbon;

class AttendanceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $attendances = Attendance::with(['mahasiswas', 'booking'])->get();

        $attendanceData = [];

        foreach ($attendances as $attendance) {
            $student = $attendance->mahasiswas;
            $booking = $attendance->booking;
            
            // Menghitung jumlah kehadiran mahasiswa pada booking tertentu
            $attendanceCount = Attendance::where('mahasiswas_NIM', $student->NIM)
                ->where('booking_id', $booking->id)
                ->count();

            // Menghitung jumlah total pertemuan dari booking
            $totalMeetings = $booking->jumlah_pertemuan;
            $attendancePercentage = ($totalMeetings > 0) ? ($attendanceCount / $totalMeetings) * 100 : 0;

            $attendanceData[] = [
                'student' => $student,
                'booking' => $booking,
                'attendanceCount' => $attendanceCount,
                'attendancePercentage' => $attendancePercentage,
            ];
        }

        return view('admin.attendance.index', compact('attendanceData', 'attendances')); 
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function verifyStudent(Request $request)
{
    $qrCode = $request->input('qr_code');
    $mahasiswa = Mahasiswa::where('qr_code', $qrCode)->first();

    if (!$mahasiswa) {
        return response()->json(['error' => 'Invalid QR Code'], 404);
    }

    $currentDateTime = Carbon::now();
    $dayOfWeek = $currentDateTime->format('w');
    $currentTime = $currentDateTime->format('H:i:s');

    // Cek apakah mahasiswa terdaftar pada kelas yang sesuai dengan waktu saat ini
    $classSchedule = Booking::where('day_of_week', $dayOfWeek)
        ->where('start_time', '<=', $currentTime)
        ->where('end_time', '>=', $currentTime)
        ->where('room_status', 'open') // Pastikan ruangan sudah "open"
        ->whereHas('mahasiswas', function ($query) use ($mahasiswa) {
            $query->where('mahasiswas_NIM', $mahasiswa->NIM);
        })
        ->first();

    if (!$classSchedule) {
        return response()->json(['error' => 'No active class for this schedule or room not opened'], 404);
    }

    // Cek apakah mahasiswa sudah presensi untuk kelas ini pada hari ini
    $existingAttendance = Attendance::where('mahasiswas_NIM', $mahasiswa->NIM)
        ->where('booking_id', $classSchedule->id)
        ->whereDate('attended_at', $currentDateTime->toDateString())
        ->first();

    if ($existingAttendance) {
        return response()->json(['error' => 'Already attended'], 400);
    }

    // Tentukan "Pertemuan ke" berdasarkan jumlah presensi sebelumnya
    $lastAttendance = Attendance::where('mahasiswas_NIM', $mahasiswa->NIM)
        ->where('booking_id', $classSchedule->id)
        ->orderBy('pertemuan_ke', 'desc')
        ->first();

    $pertemuanKe = $lastAttendance ? $lastAttendance->pertemuan_ke + 1 : 1;

    // Catat presensi baru
    $attendance = Attendance::create([
        'mahasiswas_NIM' => $mahasiswa->NIM,
        'booking_id' => $classSchedule->id,
        'attended_at' => $currentDateTime,
        'pertemuan_ke' => $pertemuanKe,
    ]);

    return response()->json([
        'message' => 'Attendance marked successfully',
        'attendance_id' => $attendance->id,
        'pertemuan_ke' => $attendance->pertemuan_ke,
    ], 200);
}


    public function uploadPhoto(Request $request)
    {
        // Verifikasi attendance_id
        $attendanceId = $request->input('attendance_id');
        $attendance = Attendance::find($attendanceId);

        if (!$attendance) {
            return response()->json(['error' => 'Invalid attendance ID'], 404);
        }

        // Cek apakah ada file gambar yang diupload
        if ($request->hasFile('photo')) { // Ubah 'file' menjadi 'photo'
            $file = $request->file('photo'); // Ubah 'file' menjadi 'photo'
            $photo_ekstensi = $file->extension();
            $photo_nama = date('ymdhis') . '.' . $photo_ekstensi;
            $file->move(public_path('photo'), $photo_nama);

            $attendance->photo = $photo_nama;
            $attendance->save();

            return response()->json(['success' => 'Photo uploaded successfully'], 200);
        } else {
            return response()->json(['error' => 'No file uploaded'], 400);
        }
    }

    
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $attendance = Attendance::where('id', $id)->first();
        return view('admin.attendance.show', compact('attendance'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $attendance = Attendance::findOrFail($id);
        $mahasiswas = Mahasiswa::get()->pluck('Nama', 'NIM');
        $bookings = Booking::get()->pluck('Kode_Kelas', 'id');
    // Add logic as needed
        return view('admin.attendance.edit', compact('attendance','mahasiswas','bookings'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'mahasiswas_NIM' => 'required',
            'booking_id' => 'required',
            'attended_at' => 'required',
        ]);
    
        // Update attendance record
        Attendance::where('id', $id)->update($validatedData);

        return redirect()->route('admin.attendance.index')->with([
            'message' => 'successfully updated !',
            'alert-type' => 'info'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Attendance::destroy($id);

        return redirect()->route('admin.attendance.index')->with([
            'message' => 'successfully deleted !',
            'alert-type' => 'danger'
        ]);
    }

    public function massDestroy(Request $request)
    {
        
        Attendance::whereIn('id', request('ids'))->delete();

        return response()->noContent();
    }
}
