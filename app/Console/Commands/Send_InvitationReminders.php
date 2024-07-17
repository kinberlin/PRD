<?php

namespace App\Console\Commands;

use App\Models\Invitation;
use App\Notifications\MeetingReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Send_InvitationReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-invitation-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get the current datetime plus one hour
        $currentTimePlusOneHour = Carbon::now()->addHour()->toDateTimeString();
        $invitations = Invitation::where(DB::raw('STR_TO_DATE(CONCAT(odates, " ", begin), "%Y-%m-%d %H:%i")'), '=', $currentTimePlusOneHour)
            ->get();
        foreach ($invitations as $invitation) {
            $invitation->notify(new MeetingReminder($invitation));
        }
    }
}
