<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Booking;
use Carbon\Carbon;

class UpdateClassScheduleStatus extends Command
{
    protected $signature = 'update:class-schedule-status';
    protected $description = 'Update status of class schedules based on current time';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $now = Carbon::now();
        $schedules = Booking::all();

        foreach ($schedules as $schedule) {
            $classDay = Carbon::createFromFormat('Y-m-d H:i:s', $now->startOfWeek()->addDays($schedule->day_of_week)->format('Y-m-d') . ' ' . $schedule->start_time);

            if ($now->lt($classDay)) {
                $schedule->status = 'Upcoming';
            } elseif ($now->between($classDay, $classDay->copy()->addMinutes($schedule->getDuration()))) {
                $schedule->status = 'Ongoing';
            } else {
                $schedule->status = 'Completed';
            }

            $schedule->save();
        }

        return 0;
    }
}
