<?php

namespace App\Console\Commands;

use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;
use Illuminate\Console\Command;
use App\Models\TempQrCode;
use PhpMqtt\Client\Facades\MQTT;
use App\Models\Mahasiswa;
use App\Models\Dosen;
use App\Models\Attendance;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class MqttListenerCommand extends Command
{

    protected $client;

    public function __construct()
    {

        parent::__construct();

        
        $host = 'test.mosquitto.org';
        $port = 1883;
        $clientId = '8a9a16c1-9910-480a-9086-7c7db3de77da';

        $this->client = new MqttClient($host, $port, $clientId);

        $connectionSettings = (new ConnectionSettings);
        $this->client->connect($connectionSettings, true);
    }

    /**
     * Nama dan tanda tangan dari perintah konsol.
     *
     * @var string
     */
    protected $signature = 'mqtt:listen';

    /**
     * Deskripsi dari perintah konsol.
     *
     * @var string
     */
    protected $description = 'Dengarkan pesan MQTT untuk presensi dan akses ruangan';

    /**
     * Eksekusi perintah konsol.
     *
     * @return int
     */
    public function handle()
    {
        $mqtt = MQTT::connection();

        // Berlangganan ke topik untuk presensi dan akses
        $mqtt->subscribe('classroom/presensi', function (string $topic, string $message) {
            $this->handlePresensi($message);
        }, 0);

        $mqtt->subscribe('classroom/access', function (string $topic, string $message) {
            $this->handleAccess($message);
        }, 0);

        // Dengarkan pesan secara terus-menerus
        $mqtt->loop(true);
    }

    public function handlePresensi($message)
    {
        
        $qrCode = $message['qr_code'];

        $currentDateTime = Carbon::now();
        $dayOfWeek = $currentDateTime->format('w');
        $currentTime = $currentDateTime->format('H:i:s');
    
        // Find Mahasiswa by QR code
        $mahasiswa = Mahasiswa::where('qr_code', $qrCode)->first();
    
        if (!$mahasiswa) {
            echo "Invalid QR Code: $qrCode\n";
            // Kirim sinyal LED merah
            $this->sendMqttMessage('device/led', 'red');
            return;
        }
    
        // Find class schedule matching the current time
        $classSchedule = Booking::where('day_of_week', $dayOfWeek)
            ->where('start_time', '<=', $currentTime)
            ->where('end_time', '>=', $currentTime)
            ->whereHas('mahasiswas', function ($query) use ($mahasiswa) {
                $query->where('mahasiswas_NIM', $mahasiswa->NIM);
            })
            ->first();
    
        if (!$classSchedule) {
            echo "No class schedule for this time or student not registered\n";
            // Kirim sinyal LED merah
            $this->sendMqttMessage('device/led', 'red');
            return;
        }
    
        // Check if attendance already exists
        $existingAttendance = Attendance::where('mahasiswas_NIM', $mahasiswa->NIM)
            ->where('booking_id', $classSchedule->id)
            ->whereDate('attended_at', $currentDateTime->toDateString())
            ->first();
    
        if ($existingAttendance) {
            echo "Already attended\n";
            // Kirim sinyal LED hijau
            $this->sendMqttMessage('device/led', 'green');
            return;
        }
    
        // Save QR code temporarily for image upload
        TempQrCode::create([
            'qr_code' => $qrCode,
            'mahasiswas_NIM' => $mahasiswa->NIM,
            'booking_id' => $classSchedule->id,
            'expires_at' => now()->addMinutes(5),
        ]);
    }
    protected function handleAccess($message)
    {
        // Decode pesan JSON
        $data = json_decode($message, true);
        $qrCode = $data['qr_code'];

        // Dapatkan waktu dan hari saat ini
        $currentDateTime = Carbon::now();
        $dayOfWeek = $currentDateTime->format('w');
        $currentTime = $currentDateTime->format('H:i:s');

        // Temukan jadwal kelas yang sesuai dengan waktu dan dosen saat ini
        $classSchedule = Booking::where('day_of_week', $dayOfWeek)
            ->where('start_time', '<=', $currentTime)
            ->where('end_time', '>=', $currentTime)
            ->whereHas('dosen', function ($query) use ($qrCode) {
                $query->where('qr_code', $qrCode);
            })
            ->first();

        if (!$classSchedule) {
            echo "Kode QR dosen tidak valid atau tidak ada jadwal pada waktu ini\n";
            // Kirim sinyal LED merah
            $this->sendMqttMessage('device/led', 'red');
            return;
        }

        // Logika akses ruangan berhasil
        echo "Akses ruangan berhasil\n";
        // Kirim sinyal LED hijau
        $this->sendMqttMessage('device/led', 'green');
    }

    protected function sendMqttMessage($topic, $message)
    {
        $mqtt = MQTT::connection();
        $mqtt->publish($topic, $message);
    }

    public function __destruct()
    {
        $this->client->disconnect();
    }
}
