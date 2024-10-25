<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use App\Http\Requests\Admin\DosenRequest;
use App\Models\Dosen;

class DosenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('dosen_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dosen = Dosen::all();

        return view('admin.dosen.index', compact('dosen'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('dosen_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.dosen.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DosenRequest $request)
    {
        abort_if(Gate::denies('dosen_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $qr = mt_rand(1000000000, 9999999999);
            if ($this->qrCodeExists($qr)) 
            {
                 $qr = mt_rand(1000000000, 99999999);
            }


        Dosen::create($request->validated() + ['qr_code' => $qr]);

        return redirect()->route('admin.dosen.index')->with([
            'message' => 'successfully created !',
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
        abort_if(Gate::denies('dosen_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $dosen = Dosen::where('id', $id )->first();

        return view('admin.dosen.show', compact('dosen'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Dosen $dosen)
    {
        abort_if(Gate::denies('dosen_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.dosen.edit', compact('dosen'));
    }
    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DosenRequest $request, Dosen $dosen)
    {
        abort_if(Gate::denies('dosen_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $qr = mt_rand(1000000000, 9999999999);
            if ($this->qrCodeExists($qr)) 
            {
                 $qr = mt_rand(1000000000, 99999999);
            }

        $dosen->update($request->validated() + ['qr_code' => $qr]);

        return redirect()->route('admin.dosen.index')->with([
            'message' => 'successfully updated !',
            'alert-type' => 'info'
        ]);
    }

    public function qrCodeExists($qr){
        return Dosen::whereQrCode($qr)->exists();
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dosen $dosen)
    {
        abort_if(Gate::denies('dosen_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        
        $dosen->delete();

        return redirect()->route('admin.dosen.index')->with([
            'message' => 'successfully deleted !',
            'alert-type' => 'danger'
        ]);
    }

    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('dosen_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Dosen::whereIn('id', request('ids'))->delete();

        return response()->noContent();
    }
}
