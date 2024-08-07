<?php

namespace App\Console\Commands;

use App\Models\Dysfunction;
use App\Notifications\DysfunctionReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SendDysfunctionReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-dysfunction-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Dysfunction Reminder';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get the current date
        $currentDate = Carbon::now();
        //Dysfunction with task whose dates are more than 30 days behind current date
        $dysfunctions = Dysfunction::whereNull('closed_at')->whereHas('tasks', function ($query) use ($currentDate) {
            $query->whereDate(DB::raw('DATE_ADD(start_date, INTERVAL duration DAY)'), '<=', $currentDate->subDays(30));
        })->get();
        foreach ($dysfunctions as $dys) {
            $dys->notify(new DysfunctionReminder($dys));
        }
    }
}
