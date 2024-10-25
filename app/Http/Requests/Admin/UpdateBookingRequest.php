<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateBookingRequest extends FormRequest
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
            'Kode_Kelas' => ['required'],
            'prodi_id' => ['required'],
            'matakuliah_id' => ['required'],
            'dosen_id' => ['required'],
            'ruangan_id' => ['required'],
            'day_of_week' => ['required'],
            'start_time' => ['required'],
            'end_time' => ['required'],
            'mahasiswas'  => ['required', 'array'],
            'mahasiswas.*'=> ['integer'],
        ];
    }
}
