<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use App\Models\Booking;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();

        // Tambahkan View Composer untuk menghitung Booking hari ini
        View::composer('*', function ($view) {
            $today = Carbon::now()->format('w'); // Hari ini (0 untuk Minggu, 6 untuk Sabtu)
            $countTodayBookings = Booking::where('day_of_week', $today)->count(); // Hitung Booking hari ini
            $view->with('countTodayBookings', $countTodayBookings); // Kirim variabel ke semua view
        });
    }
}
