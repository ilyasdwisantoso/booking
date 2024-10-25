<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'Kode_Kelas' => 'required',
            'kode_qrkelas' => 'required',
            'mahasiswas_NIM' => 'required',
            'prodi_id' => 'required',
            'matakuliah_id' => 'required',
            'dosen_id' => 'required',
            'ruangan_id' => 'required',
            'time_from' => 'required|date_format:Y-m-d H:i',
            'time_to' => 'required|date_format:Y-m-d H:i',
            'additional_information' => 'required',
            'status' => 'required',
        
        ];
    }
}
