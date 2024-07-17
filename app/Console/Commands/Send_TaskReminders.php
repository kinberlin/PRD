<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Notifications\TaskReminder;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class Send_TaskReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-task-reminders';

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
        $current = Carbon::now();
        $tasks = Task::where(DB::raw('DATE_ADD(start_date, INTERVAL duration DAY)'), '>=', $current)
            ->get();
        foreach ($tasks as $task) {
            $diff = $current->diffInDays(Carbon::parse($task->start_date)->addDay($task->duration));
if ($diff > 0 && $diff < 8 && $diff % 2 == 0) {
            $task->notify(new TaskReminder($task));
        }}
    }
}
