<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Notifications\TaskReminder;
use App\Scopes\YearScope;
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
    protected $description = 'Send Mails every 2 days When Task is left with less than 8 days to end date and task is not yet completed.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Get the current datetime
        $current = Carbon::now();
        $parentTasks = Task::withoutGlobalScope(YearScope::class)
            ->select('tasks.id', 'tasks.text')
            ->distinct()
            ->join('tasks as t2', 'tasks.id', '=', 't2.parent')
            ->whereYear('tasks.created_at', $current->year)
            ->get();

        $tasks = Task::withoutGlobalScope(YearScope::class)->where(DB::raw('(progress * 100)'), '<', 100)->whereNotIn('id', $parentTasks->pluck('id')->unique())
            ->get();
        foreach ($tasks as $task) {
            $diff = Carbon::parse($task->start_date)->addDay($task->duration)->diffInDays($current);
            if ($diff < 8 && $diff % 2 == 0) {
                $task->notify(new TaskReminder($task));
            }
            if ($diff == 1) {
                $task->notify(new TaskReminder($task));
            }
        }
    }
}
