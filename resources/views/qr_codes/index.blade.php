<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Codes</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div class="container">
        <h1>QR Codes</h1>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>QR Code</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($qrCodes as $qrCode)
                    <tr>
                        <td>{{ $qrCode->id }}</td>
                        <td>{{ $qrCode->qr_code }}</td>
                        <td>{{ $qrCode->created_at }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
