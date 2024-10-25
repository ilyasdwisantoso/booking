<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Gate;
use Illuminate\Http\Request;
use App\Http\Requests\Admin\MatakuliahRequest;
use App\Models\Matakuliah;


class MatakuliahController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        abort_if(Gate::denies('matakuliah_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $matakuliah = Matakuliah::all();

        return view('admin.matakuliah.index', compact('matakuliah'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        abort_if(Gate::denies('matakuliah_create'), Response::HTTP_FORBIDDEN, '404 Forbidden');
        return view('admin.matakuliah.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MatakuliahRequest $request)
    {
        abort_if(Gate::denies('matakuliah_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Matakuliah::create($request->validated());

        return redirect()->route('admin.matakuliah.index')->with([
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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Matakuliah $matakuliah)
    {
        abort_if(Gate::denies('matakuliah_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        return view('admin.matakuliah.edit', compact('matakuliah'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(MatakuliahRequest $request, Matakuliah $matakuliah)
    {
        abort_if(Gate::denies('matakuliah_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $matakuliah->update($request->validated());

        return redirect()->route('admin.matakuliah.index')->with([
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
    public function destroy(Matakuliah $matakuliah)
    {
        abort_if(Gate::denies('matakuliah_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        
        $matakuliah->delete();

        return redirect()->route('admin.matakuliah.index')->with([
            'message' => 'successfully deleted !',
            'alert-type' => 'danger'
        ]);
    }
    
    public function massDestroy(Request $request)
    {
        abort_if(Gate::denies('matakuliah_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        Matakuliah::whereIn('id', request('ids'))->delete();

        return response()->noContent();
    }
}
