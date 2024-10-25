<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Models\Ruangan;
use App\Http\Requests\Admin\RuanganRequest;

class RuanganController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('ruangan_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ruangan = Ruangan::all();
        return view('admin.ruangan.index', compact('ruangan'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('ruangan_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.ruangan.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RuanganRequest $request)
    {
        abort_if(Gate::denies('ruangan_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        
        Ruangan::create($request->validated());

        return redirect()->route('admin.ruangan.index')->with([
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
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Ruangan $ruangan)
    {
        abort_if(Gate::denies('ruangan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');
        return view('admin.ruangan.edit', compact('ruangan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(RuanganRequest $request, Ruangan $ruangan)
    {
        abort_if(Gate::denies('ruangan_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ruangan->update($request->validated());

        return redirect()->route('admin.ruangan.index')->with([
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
    public function destroy(Ruangan $ruangan)
    {
        abort_if(Gate::denies('ruangan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $ruangan->delete();

        return redirect()->route('admin.ruangan.index')->with([
            'message' => 'successfully deleted !',
            'alert-type' => 'danger'
        ]);
    }

    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('ruangan_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Ruangan::whereIn('id', request('ids'))->delete();

        return response()->noContent();
    }
}
