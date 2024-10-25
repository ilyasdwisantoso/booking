<?php

namespace App\Http\Controllers\Admin;


use App\Models\Mahasiswa;
use App\Models\Booking;
use App\Http\Controllers\Controller;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class QRcodeGenerateController extends Controller
{
    public function index(){
        return view('admin.qrcode.index');

    }
}
