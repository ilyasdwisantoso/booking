<!-- resources/views/qr_code_data/index.blade.php -->

@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Data QR Code</h1>
        
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Data QR Code</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($qrCodes as $qrCode)
                    <tr>
                        <td>{{ $qrCode->id }}</td>
                        <td>{{ $qrCode->data }}</td>
                        <td>{{ $qrCode->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
