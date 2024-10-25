<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use PhpMqtt\Client\MqttClient;
use PhpMqtt\Client\ConnectionSettings;

class MqttServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('mqtt', function () {
            $host = 'test.mosquitto.org';
            $port = 1883;
            $clientId = 'your-client-id';

            $client = new MqttClient($host, $port, $clientId);

            // Lakukan pengaturan koneksi jika diperlukan
            $connectionSettings = new ConnectionSettings();
            $client->connect($connectionSettings, true);

            return $client;
        });
    }
}
