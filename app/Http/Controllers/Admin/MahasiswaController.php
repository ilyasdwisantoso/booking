<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mahasiswa;
use App\Models\Qrmahasiswa;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\Admin\MahasiswaRequest;



class MahasiswaController extends Controller
{

    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('mahasiswa_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $data = Mahasiswa::all();

        return view('admin.mahasiswas.index', compact('data'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('mahasiswa_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.mahasiswas.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        abort_if(Gate::denies('mahasiswa_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');


        $request->validate([
            'NIM' => 'required|numeric',
            'Nama' => 'required',
            'tgl_lahir' => 'required',
            'photo' => 'required|mimes:jpeg,png,jpg,gif,svg'
        ],[
            'NIM.required' => 'NIM wajib diisi',
            'NIM.numeric' => 'NIM wajib diisi dengan angka',
            'Nama.required' => 'Nama wajib diisi',
            'tgl_lahir.required' => 'tgl_lahir wajib diisi',
            'photo.required' => 'photo wajib diisi',
            'photo.mimes' => 'photo hanya diperbolehkan berekstensi JPEG,PNG,JPG,GIF,SVG',

        ]);

        $photo_file = $request->file('photo');
        $photo_ekstensi = $photo_file->extension();
        $photo_nama = date('ymdhis') . "." . $photo_ekstensi;
        $photo_file->move(public_path('photo'), $photo_nama);
        $qr = mt_rand(1000000000, 9999999999);
        
        if ($this->qrCodeExists($qr)){
            $qr = mt_rand(1000000000, 99999999);
        }

        $data = [
            'Nama' => $request->input('Nama'),
            'NIM' =>$request->input('NIM'),
            'tgl_lahir' => $request->input('tgl_lahir'),
            'photo' => $photo_nama,
            'qr_code' => $qr
        ];

      

        Mahasiswa::create($data);
    
        return redirect()->route('admin.mahasiswas.index')->with([
            'message' => 'successfully created !',
            'alert-type' => 'success'
        ]);

        
    }

    

    /**
     * Display the specified resource.
     *
     * @param  int  $NIM
     * @return \Illuminate\Http\Response
     */
    public function show($NIM)
    {
        abort_if(Gate::denies('mahasiswa_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $mahasiswas = Mahasiswa::where('NIM', $NIM )->first();

        return view('admin.mahasiswas.show', compact('mahasiswas'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $NIM
     * @return \Illuminate\Http\Response
     */
    public function edit(Mahasiswa $mahasiswa)
    {
        abort_if(Gate::denies('mahasiswa_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.mahasiswas.edit', compact('mahasiswa'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $NIM
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $NIM)
    {
        abort_if(Gate::denies('mahasiswa_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $request->validate([
            'NIM' => 'required|numeric',
            'Nama' => 'required',
            'tgl_lahir' => 'required',
        ],[
            'NIM.required' => 'NIM wajib diisi',
            'NIM.numeric' => 'NIM wajib diisi dengan angka',
            'Nama.required' => 'Nama wajib diisi',
            'tgl_lahir.required' => 'tgl_lahir wajib diisi',
        ]);

        $qr = mt_rand(1000000000, 9999999999);
        
        if ($this->qrCodeExists($qr)){
            $qr = mt_rand(1000000000, 99999999);
        }

        $data = [
            'Nama' => $request->Nama,
            'NIM' => $request->NIM,
            'tgl_lahir' => $request->tgl_lahir,
            'qr_code' => $qr
        ];

        if ($request->hasFile('photo')) {
            $request->validate([
                'photo' => 'mimes:jpeg,jpg,png,gif,svg'
            ], [
                'photo.mimes' => 'Foto hanya diperbolehkan JPEG, JPG, PNG, GIF, dan SVG'
            ]);

            $photo_file = $request->file('photo');
            $photo_ekstensi = $photo_file->extension();
            $photo_nama = date('ymdhis') . '.' . $photo_ekstensi;
            $photo_file->move(public_path('photo'), $photo_nama);
            $data_photo = Mahasiswa::where('NIM', $NIM)->first();
            File::delete(public_path('photo') . '/' . $data_photo->photo);
            $data['photo'] = $photo_nama;
        }

        DB::transaction(function() use ($NIM, $request, $data) {
            // Update related tables first
            DB::table('classmahasiswa')->where('mahasiswas_NIM', $NIM)->update(['mahasiswas_NIM' => $request->NIM]);
            DB::table('attendances')->where('mahasiswas_NIM', $NIM)->update(['mahasiswas_NIM' => $request->NIM]);

            // Update mahasiswa table
            Mahasiswa::where('NIM', $NIM)->update($data);
            return response()->json(['success' => 'Attendance marked successfully'], 200);
        });

        return redirect()->route('admin.mahasiswas.index')->with([
            'message' => 'successfully updated!',
            'alert-type' => 'info'
        ]);
    }

    


    public function qrCodeExists($qr){
        return Mahasiswa::whereQrCode($qr)->exists();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Mahasiswa $mahasiswa)
    {
        abort_if(Gate::denies('mahasiswa_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        File::delete(public_path('photo') . '/' . $mahasiswa->photo);
        $mahasiswa->delete();

        return redirect()->route('admin.mahasiswas.index')->with([
            'message' => 'successfully deleted !',
            'alert-type' => 'danger'
        ]);
    }

    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('mahasiswa_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Mahasiswa::whereIn('NIM', request('NIMs'))->delete();

        return response()->noContent();
    }
}
