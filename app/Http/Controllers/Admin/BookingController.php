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

        $bookings = Booking::all();

        return view('admin.booking.index', compact('bookings')); 
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
            } else {
                $booking->status = 'kelas belum dimulai';
            }
            $booking->save();
        }

        return response()->json(['success' => 'Class statuses updated']);
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
        abort_if(Gate::denies('booking_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mahasiswas = Mahasiswa::get()->pluck('Nama', 'NIM');
        $prodi = Prodi::get()->pluck('nama_prodi', 'id');
        $matakuliah = Matakuliah::get()->pluck('Nama_MK', 'id');
        $dosen = Dosen::get()->pluck('nama_dosen', 'id');
        $ruangan = Ruangan::get()->pluck('no_ruangan', 'id');

        return view('admin.booking.create', compact('mahasiswas', 'prodi', 'matakuliah', 'dosen', 'ruangan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreBookingRequest $request)
    {
        abort_if(Gate::denies('booking_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $start_time = $request->input('start_time');
        $end_time = $request->input('end_time');
        $day_of_week = $request->input('day_of_week');
        $ruangan_id = $request->input('ruangan_id');

        // Check for scheduling conflicts
        $conflict = Booking::where('ruangan_id', $ruangan_id)
            ->where('day_of_week', $day_of_week)
            ->where(function($query) use ($start_time, $end_time) {
                $query->where(function($q) use ($start_time, $end_time) {
                    $q->where('start_time', '<', $end_time)
                      ->where('end_time', '>', $start_time);
                });
            })->exists();

        if ($conflict) {
            return redirect()->route('admin.booking.create')->with([
                'message' => 'Maaf, pada ruangan ini sudah dipakai.',
                'alert-type' => 'danger'
            ])->withInput($request->all());
        }

        // Generate token
        $token = mt_rand(100000, 999999);
        while ($this->codeTokenExists($token)) {
            $token = mt_rand(100000, 999999);
        }

        // Save booking
        $booking = Booking::create($request->validated() + ['code_token' => $token]);
        $booking->mahasiswas()->sync($request->input('mahasiswas'));

        return redirect()->route('admin.booking.index')->with([
            'message' => 'Successfully created!',
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

        return view('admin.booking.edit', compact('booking', 'mahasiswas', 'prodi', 'matakuliah', 'dosen', 'ruangan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBookingRequest $request, $id)
    {
        abort_if(Gate::denies('booking_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $start_time = $request->input('start_time');
        $end_time = $request->input('end_time');
        $day_of_week = $request->input('day_of_week');
        $ruangan_id = $request->input('ruangan_id');

        // Get the booking by ID
        $booking = Booking::findOrFail($id);

        // Check for scheduling conflicts
        $conflict = Booking::where('ruangan_id', $ruangan_id)
            ->where('day_of_week', $day_of_week)
            ->where('id', '!=', $booking->id)
            ->where(function($query) use ($start_time, $end_time) {
                $query->where(function($q) use ($start_time, $end_time) {
                    $q->where('start_time', '<', $end_time)
                      ->where('end_time', '>', $start_time);
                });
            })->exists();

        if ($conflict) {
            return redirect()->route('admin.booking.edit', $booking->id)->with([
                'message' => 'Maaf, pada ruangan ini sudah dipakai.',
                'alert-type' => 'danger'
            ])->withInput($request->all());
        }

        // Update booking
        $booking->update($request->validated());
        $booking->mahasiswas()->sync($request->input('mahasiswas'));

        return redirect()->route('admin.booking.index')->with([
            'message' => 'Successfully updated!',
            'alert-type' => 'info'
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
}
